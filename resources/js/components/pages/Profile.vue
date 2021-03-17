<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-indigo-900 p-2 shadow text-xl text-white">
       <center> <h3 class="font-bold pl-2">Wecome to {{user.name}} profile</h3></center>
    </div>
    <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full md:w-1/2 xl:w-1/3 p-3" v-for="post in posts" v-bind:key="post.id" >
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-lg">
                        <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                            <router-link :to="'/users/'+user.id">
                                <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                                <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                                <span class="text-gray-600">{{user.name}}</span>
                            </router-link>
                        </div>
                        <div class="p-5">
                            
                        <iframe 
                        :src="post.video_path"
                        allowfullscreen
                        allowtransparency
                        notplay
                        ></iframe>
                            
                            {{post.discription}}
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>


            </div>
  </div>
</template>

<script>
import ApiService from '../../services/api.service';
export default {
    name:'Profile',
    data(){
      return{
        services:new ApiService(),
        posts:[],
        user:[],
      }
    },
    created(){
      var id = this.$route.params.id;

      this.getUserPost(id);
    },
    
    methods:{
      // get spicifig user post and profile
      getUserPost(id){
        this.services.getUserPost(id)
        .then((response) => {
          if(response.data.status){

            this.posts = response.data.posts;

            this.user  = response.data.user;
          }
        })
      }
    }
}
</script>

<style>

</style>