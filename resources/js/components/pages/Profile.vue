<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

            <div class="bg-indigo-900 p-2 shadow text-xl text-white">
                <h3 class="font-bold pl-2">Welcome to {{user.name}} profile</h3>
            </div>

            <div class="flex flex-row flex-wrap flex-grow mt-2" v-infinite-scroll="onNextPage">

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
                            <router-link :to="'/video/'+post.id">
                                <video :id="'video'+post.id" width="420" class="cursor-pointer h-56" v-on:mouseover="playVideo(post.id);" v-on:mouseout="stopVideo(post.id)">
                                    <source :src="post.video_path" type="video/mp4">
                                    <source src="" type="video/ogg">
                                    Your browser does not support HTML video.
                                </video>
                            </router-link>        
                       
                            {{post.discription.substring(0,40)}}
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

            </div>
            <span v-if="nextLink">
                <center><i class="fas fa-spinner fa-pulse text-blue-600" ></i></center> 
            </span>
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
        currentpage: 1,
        nextLink: true,
        user_id:null,
      }
    },
    created(){
      this.user_id = this.$route.params.id;
      this.getUserPost(this.user_id,null);
    },
    
    methods:{

      // get spicifig user post and profile
      getUserPost(user_id,pageNumber = null){
        var formData = new FormData();
        formData.append("user_id",user_id);
        this.services.getUserPost(formData,pageNumber)
        .then((response) => {
          console.log(response);
          if(response.data.status){
            this.user  = response.data.user;
             if(pageNumber == null){
               console.log(response.data.posts);
                  this.posts = response.data.posts.data;
              }
              else{
                  for(let x =0;x<response.data.posts.data.length;x++){
                      this.posts.push(response.data.posts.data[x]);
                  }
              }
              if (response.data.posts.to == response.data.posts.total) this.nextLink = false;

          }
        })
      },

      /**
         * scroll pagination
         */
        onNextPage: function() {
            ++this.currentpage, this.getUserPost(this.user_id,this.currentpage);
        },

      /**
         * Play video
         */
        playVideo(id){
            var myVideo = document.getElementById("video"+id);
            myVideo.play();
        },

        /**
         * stop video
         */
        stopVideo(id){
            var myVideo = document.getElementById("video"+id);
            myVideo.pause();
        }
    }
}
</script>

<style>

</style>