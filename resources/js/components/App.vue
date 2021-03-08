<template>
  <div class="container">
    <hero-menu/>
    <div class="flex flex-col md:flex-row mt-14">
     
      <hero-sidebar/>
      <router-view 
        :allusers="allusers"
        :authuser="authuser"
        :turn_url="turn_url"
        :turn_username="turn_username"
        :turn_credential="turn_credential"
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
    created(){

    }
  }
</script>

<style>

</style>