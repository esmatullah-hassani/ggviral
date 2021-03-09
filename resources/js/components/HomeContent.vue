<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

            <div class="bg-indigo-900 p-2 shadow text-xl text-white">
                <h3 class="font-bold pl-2">Analytics</h3>
            </div>

            <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full md:w-1/2 xl:w-1/3 p-3" v-for="post in posts" v-bind:key="post.id" >
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-lg">
                        <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                            <router-link :to="'/users/'+post.user.id">
                                <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+post.user.photo" v-if="post.user.social_path == null">
                                <img class="w-7 h-7 rounded-full inline"  :src="post.user.social_path" v-else> 
                                <span class="text-gray-600">{{post.user.name}}</span>
                            </router-link>
                        </div>
                        <div class="p-5">
                            
                        <iframe
                        src="https://www.youtube.com/embed/bTqVqk7FSmY?amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                        allowfullscreen
                        allowtransparency
                        allow="autoplay"
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
import ApiService from '../services/api.service'
export default {
    name:"HomeContent",
    data(){
        return{
            posts:[],
            services:new ApiService(),
        }
    },
    created(){
        this.getPost();
    },
    
    methods:{

        getPost(){
            this.services.getPost()
            .then((response)=> {
                if(response.data.status){
                    this.posts = response.data.posts;
                }
            })
            .catch((errors)=>{
                console.log(errors+" errors");
            })
        },
        getPermissions(){
            return new Promise((res,rej)=>{
                navigator.mediaDevices.getUserMedia({video:true,audio:true})
                .then((stream)=>{
                    resolve(stream);
                })
                .catch(err=>{
                    throw new Error(`Unable to fetch stream ${err}`);
                })
            })
        }
    }

}
</script>

<style>

</style>