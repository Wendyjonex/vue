<template>
<div id="app">
  <h1>hello</h1>
</div>

</template>
<script setup>
import { userStore } from '@/store/user'
const user = userStore()
// user.username='roy'

// console.log(user.username)
console.log(user.getsername)
</script>
<style></style>
