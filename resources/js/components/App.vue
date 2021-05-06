<template>
  <div class="container">
    <hero-menu :authuser="authuser"/>
    <div class="flex flex-col md:flex-row mt-14">
     
      <hero-sidebar />
      <router-view 
        :allusers="allusers"
        :authuser="authuser"
        :turn_url="turn_url"
        :turn_username="turn_username"
        :turn_credential="turn_credential"
        :userLive="userLive"
        :livedata="livedata"
        />
    </div>
    
  </div>
</template>

<script>
import HeroMenu from './hero-section/HeroMenu.vue'
import HeroSidebar from './hero-section/HeroSidebar.vue'
import HerroFooter from './hero-section/HerroFooter.vue'
export default {
  props: [
    "allusers",
    "authuser",
    "turn_url",
    "turn_username",
    "turn_credential",
  ],
  components: { HeroSidebar, HerroFooter, HeroMenu },
    name:'App',
    data(){
      return {
        userLive:false,
        livedata:null,
      }
    },
   

    mounted(){
      Echo.private("presence-video-channel")
          .listen("StartVideoChat",(e)=>{
              console.log(e);
      });
      this.initializeChannel();
    },
    methods:{
      initializeChannel() {
        window.Echo.join("presence-video-channel").listen("StartVideoChat", ({ data }) => {
         this.userLive = true;
         this.livedata = data;
        alert(data.title);
        });
      },
    }
  }
</script>

<style>

</style>