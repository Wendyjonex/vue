<template>
<dIV class="user">
  姓名：<input v-model="msc" @input="sava"/> {{msc}}<br />
  薪水：<input v-model.number="re" placeholder="输入薪水" @input="sava"/>{{re}}<br />
  <button class="width" v-on:click="add">提交</button>
  <button  @user="CheageUser">个人信息页</button>
  <hr />
  <div class="userInfo" >
<h2>个人信息</h2>

<p>年龄：<input type="number" v-model="age" @input="sava"/> </p>
<p>性别：<input type="radio" value="1" v-model="sex">男 </input> <input type="radio" value="2" v-model="sex">女</input></p>

  </div>
</dIV>
    
</template>

<script setup>
// export default{
//   data(){
//     return{
//       userName:'roy',
//       salary:15000
//     }
//   },
//   methods:{
//     addSalary(){  
//       this.salary+=1000
//     }
//   }
// }
import {ref,onMounted} from 'vue'
const msc = ref(localStorage.getItem('msc')||'')
const re =ref(Number(localStorage.getItem('re'))||0) 
const age=ref(Number(localStorage.getItem('age'))||0) 

const sava=()=>{localStorage.setItem('msc',msc.value)
  localStorage.setItem('re',String(re.value))
  localStorage.setItem("age",String(age))
 
}

const add =()=>{
  console.log('点击前：',re.value)
  re.value += 1000
  console.log('点击后',re.value)
      sava()}
      

</script>



<style scoped>
.user{
  background-color: antiquewhite;
  color: black;
  height: 100%;
  width: 100%;
}
.width{
  margin-right: 65px;
  border: 1;
border-radius: 5px;
}
</style>
