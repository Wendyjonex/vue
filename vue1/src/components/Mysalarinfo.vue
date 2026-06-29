<template>
 <div>
 姓名：<input ref="name" />
 <button @click="showRes">提交</button>

 </div>


</template>
<script>
 import { ref } from 'vue';
 let name=ref()
 function showRes(){
    console.log(name)
    console.log(name.value)
 }
</script>