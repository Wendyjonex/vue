<template>
  <div class="todo-container">
    <h2>我的待办事项</h2>

    <!-- 输入框区域 -->
    <div class="input-group">
      <input v-model="newTask" placeholder="输入新任务..." @keyup.enter="addTask" />
      <button @click="addTask">添加</button>
    </div>

    <!-- 列表区域 -->
    <ul>
      <li v-for="task in tasks" :key="task.id">
        <span>{{ task.title }}</span>
        <button class="del-btn" @click="deleteTask(task.id)">删除</button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// 1. 定义变量
const tasks = ref([]); // 存放任务列表
const newTask = ref(''); // 存放输入框的内容

// 【重要】这里填你的 PHP 接口地址
const API_URL = 'http://localhost:8080/api.php';

// 2. 获取数据
const fetchTasks = async () => {
  const res = await fetch(`${API_URL}?action=list`);
  tasks.value = await res.json();
};

// 3. 添加任务
const addTask = async () => {
  if (!newTask.value) return;

  await fetch(`${API_URL}?action=add`, {
    method: 'POST',
    body: JSON.stringify({ title: newTask.value })
  });

  newTask.value = ''; // 清空输入框
  fetchTasks(); // 重新刷新列表
};

// 4. 删除任务
const deleteTask = async (id) => {
  await fetch(`${API_URL}?action=delete`, {
    method: 'POST',
    body: JSON.stringify({ id })
  });
  fetchTasks(); // 重新刷新列表
};

// 页面加载时自动执行一次
onMounted(fetchTasks);
</script>

<style scoped>
/* 简单的美化 */
.todo-container { max-width: 400px; margin: 50px auto; font-family: sans-serif; }
.input-group { display: flex; gap: 10px; margin-bottom: 20px; }
input { flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
button { padding: 8px 16px; background: #42b983; color: white; border: none; border-radius: 4px; cursor: pointer; }
.del-btn { background: #ff4d4f; margin-left: auto; }
li { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #eee; }
</style>
