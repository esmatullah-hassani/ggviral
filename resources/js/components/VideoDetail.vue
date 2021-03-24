<template>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

        <div class="bg-indigo-900 p-2 shadow text-xl text-white">
            <h3 class="font-bold pl-2">Analytics</h3>
        </div>

        <div class="flex flex-row flex-wrap flex-grow mt-2 ">

            <div class="w-full sm:w-full md:w-2/3 bg-white border-transparent rounded-lg shadow-lg">
                <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">

                    <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                    <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                    <span class="text-gray-600">{{user.name}}</span>
                </div>
                <div class="p-5">
                    
                    <iframe controls allow="autoplay" class="bg-black mx-auto" x-ref="player" :src="post.video_path" width="700" height="450" frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    {{post.discription}}
                </div>
                
            </div>
            <div class="w-full sm:w-full md:w-1/3 bg-white border-transparent rounded-lg shadow-lg ">
                <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                    <center>Comments</center>
                </div>
                <div class="p-5 overflow-y-auto max-h-96 usercomment">
                    <div class="container mb-10">
                        <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                        <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                        <span class="text-gray-600">{{user.name}}</span>
                        <br>
                        <p>This is good and very usefull</p>
                    </div>
                    <div class="container mb-10">
                        <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                        <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                        <span class="text-gray-600">{{user.name}}</span>
                        <br>
                        <p>This is good and very usefull</p>
                    </div>
                    <div class="container mb-10">
                        <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                        <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                        <span class="text-gray-600">{{user.name}}</span>
                        <br>
                        <p>This is good and very usefull</p>
                    </div>
                </div>
                <div class="shadow flex">
                    <input class="w-full rounded p-2" type="text" placeholder="Write a comment ...">
                    <button class="bg-white w-auto flex justify-end items-center text-blue-500 p-2 hover:text-blue-400">
                        <i class="fa fa-paper-plane  text-blue-600"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ApiService from '../services/api.service'
import Video from './pages/Video.vue';
export default {
  components: { Video },
    name:"VideoDetail",

    data(){
        return{
            post:[],
            user:[],
            services:new ApiService(),
        }
    },

    created(){
        var id = this.$route.params.id;
       
        this.getVideoDetail(id);
    },

    mounted(){
         var container = this.$el.querySelector(".usercomment");
		container.scrollTop = container.scrollHeight;
    },
    methods:{
        getVideoDetail(id){
            this.services.getVideoDetail(id)
            .then((response) => {
                this.post = response.data.post;
                this.user = response.data.user;
            })
            .catch((error) => {
                console.log(error);
            });
        }
    }

    
}
</script>

<style>

</style>