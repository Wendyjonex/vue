import { defineStore } from "pinia";
export const userStore = defineStore("userStore",{
  actions:{
    changeUsername(value:string){
       if(value && value.length<10){
          this.username+=value
       }
    }
  },
  state(){
    return{
      username:'--'
    }
  },
  getters:{
    getsername():string{
    return this.username.toUpperCase()
  }
},
})
