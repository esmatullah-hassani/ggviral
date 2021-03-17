<template>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

        <div class="bg-indigo-900 p-2 shadow text-xl text-white">
            <h3 class="font-bold pl-2">Analytics</h3>
        </div>

        <div class="flex flex-row flex-wrap flex-grow mt-2">

            <div class="w-full sm:w-full md:w-2/3 bg-white border-transparent rounded-lg shadow-lg">
                <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">

                    <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                    <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                    <span class="text-gray-600">{{user.name}}</span>
                </div>
                <div class="p-5">
                    <iframe  class="w-full" :src="post.video_path" type="video/mp4" allowfullscreen allow="autoplay">
                        
                    </iframe>
                    {{post.discription}}
                </div>
                
            </div>
            <div class="w-full sm:w-full md:w-1/3 bg-indigo-400">there are comment <p class="float-right">jkf</p></div>
        </div>
    </div>
</template>

<script>
import ApiService from '../services/api.service'
export default {
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