<template>
  <div class="container">
    <h1>📝 我的待办事项</h1>

    <!-- 输入框区域 -->
    <div class="input-group">
      <input
        v-model="newTaskTitle"
        @keyup.enter="addTask"
        type="text"
        placeholder="输入新任务..."
      />
      <button @click="addTask">添加</button>
    </div>

    <!-- 列表区域 -->
    <ul class="task-list">
      <li v-for="task in tasks" :key="task.id" class="task-item">
        <div class="task-content" @click="toggleStatus(task)">
          <!-- 根据 status 显示不同的图标 -->
          <span class="icon">{{ task.status === 1 ? '✅' : '⬜' }}</span>
          <!-- 根据 status 决定是否划掉文字 -->
          <span :class="{ 'done': task.status === 1 }">{{ task.title }}</span>
        </div>
        <button class="del-btn" @click="deleteTask(task.id)">删除</button>
      </li>
    </ul>

    <p v-if="loading" class="loading">加载中...</p >
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// API 地址 (对应你的 php 文件路径)
const API_URL = 'http://localhost:8080/api.php';

const tasks = ref([]);
const newTaskTitle = ref('');
const loading = ref(false);

// 1. 获取列表
const fetchTasks = async () => {
  loading.value = true;
  try {
    const res = await fetch(`${API_URL}?action=list`);
    const data = await res.json();
    tasks.value = data;
  } catch (e) {
    console.error("获取失败", e);
  } finally {
    loading.value = false;
  }
};

// 2. 添加任务
const addTask = async () => {
  if (!newTaskTitle.value.trim()) return;

  try {
    await fetch(`${API_URL}?action=add`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title: newTaskTitle.value })
    });
    newTaskTitle.value = ''; // 清空输入框
    fetchTasks(); // 重新刷新列表
  } catch (e) {
    alert("添加出错");
  }
};

// 3. 切换完成状态
const toggleStatus = async (task) => {
  const newStatus = task.status === 1 ? 0 : 1;

  // 乐观更新：先改变界面，再请求后台
  task.status = newStatus;

  try {
    await fetch(`${API_URL}?action=update`, {
      method: 'POST', // 有些服务器不支持 PUT，用 POST 兼容
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: task.id, status: newStatus })
    });
  } catch (e) {
    // 如果失败，可以回滚状态（这里简化处理）
    console.error("更新失败", e);
  }
};

// 4. 删除任务
const deleteTask = async (id) => {
  if(!confirm("确定删除吗？")) return;

  try {
    await fetch(`${API_URL}?action=delete`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    fetchTasks();
  } catch (e) {
    console.error("删除失败", e);
  }
};

// 页面加载时获取数据
onMounted(() => {
  fetchTasks();
});
</script>

<style scoped>
.container {
  max-width: 500px;
  margin: 50px auto;
  font-family: sans-serif;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  border-radius: 8px;
}

.input-group {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  padding: 10px 20px;
  background-color: #42b983;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.task-list {
  list-style: none;
  padding: 0;
}

.task-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.task-content {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  flex: 1;
}

.done {
  text-decoration: line-through;
  color: #888;
}

.del-btn {
  background-color: #ff4d4f;
  padding: 5px 10px;
  font-size: 12px;
}

.loading {
  text-align: center;
  color: #666
  ;
}
</style>
