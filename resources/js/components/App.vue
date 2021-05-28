<template>
  <div class="container">
    <hero-menu :authuser="authuser" :allusers="allusers"/>
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
      <hero-right-sidebar :allusers="allusers"/>

    </div>
    
  </div>
</template>

<script>
import HeroMenu from './hero-section/HeroMenu.vue'
import HeroRightSidebar from './hero-section/HeroRightSidebar.vue'
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
  components: { HeroSidebar, HerroFooter, HeroMenu, HeroRightSidebar },
    name:'App',
    data(){
      return {
        userLive:false,
        livedata:null,
      }
    },
   created(){
     
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
@media (max-width: 768px)
{
    .hidden-div{
        display: none;
    }
}

video {
  
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  min-width: 100%;
  min-height: 100%;
  z-index: -1;
  -webkit-transition: all 1s;
  -moz-transition: all 1s;
  -o-transition: all 1s;
  transition: all 1s;
}
.video-button{
    background-color: #666;
    border: medium none;
    color: #fff;
    display: block;
    font-size: 18px;
    left: 0;
    margin: 0 auto;
    padding: 8px 16px;
    position: relative;
    right: 0;
    top: 91%;
    margin-top: -55px;
}
button.active {
  background-color: transparent;
}
</style>