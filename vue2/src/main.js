import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import {createRouter,createWebHashHistory} from 'vue-router'
import HomePages from "@/pages/HomePages.vue"
import NewPages from "@/pages/NewPages.vue"
import AboutPage from "@/pages/AboutPage.vue"

//路由规则
const routes=[
  {path:"/home", component:HomePages},
  {path:"/about", component:AboutPage},
  {path:"/news", component:NewPages},

]
//路由器
const router =createRouter({
  history:createWebHashHistory(),//路由工作模式
  routes
})
import { createPinia } from 'pinia'
const pinia=createPinia()
//加载路由
const app=createApp(App)
app.use(pinia)
app.use(router)
app.mount('#app')
