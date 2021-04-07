<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

            <div class="bg-indigo-900 p-2 shadow text-xl text-white">
                <h3 class="font-bold pl-2">Analytics</h3>
            </div>

            <div class="flex flex-row flex-wrap flex-grow mt-2" v-infinite-scroll="onNextPage">

                <div class="w-full md:w-1/2 xl:w-1/3 p-3" v-for="post in posts" v-bind:key="post.id" >
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-lg">
                        <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                            <router-link :to="'/users/'+post.user.id">
                                <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+post.user.photo" v-if="post.user.social_path == null">
                                <img class="w-7 h-7 rounded-full inline"  :src="post.user.social_path" v-else> 
                                <span class="text-gray-600">{{post.user.name}}</span>
                            </router-link>

                            <div class="relative float-right inline-block">
                                <button @click="toggleDDd('myDropdown'+post.id)" class="drop-button text-black focus:outline-none font-bold">
                                    ...
                                </button>
                                <div :id="'myDropdown'+post.id" class="dropdownlist absolute w-60 bg-gray-400 rounded-tl-lg rounded-tr-lg text-black right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                    <a  class="p-2 hover:bg-gray-800 text-blue-700 text-sm no-underline hover:no-underline block cursor-pointer" > Share to facebook </a>
                                    <a  class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block cursor-pointer " > Share to tw </a>
                                    <a   class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block cursor-pointer" ><i class="    fa-cog fa-fw" ></i> Copy</a>
                                    <div class="border border-gray-800"></div>
                                    <a href="/logout" class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Share</a>
                            
                                </div>
                            </div>
                            
                        </div>
                        <div class="p-5">
                            <router-link :to="'/video/'+post.id">
                                <video :id="'video'+post.id"  class="cursor-pointer w-full h-60"
                                 v-on:mouseover="playVideo(post.id);" 
                                 v-on:mouseout="stopVideo(post.id)"
                                 v-on:scroll.passive="onScroll"
                                 >
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
import ApiService from '../services/api.service'
export default {
    name:"HomeContent",
    data(){
        return{
            currentpage:1,
            posts:[],
            services:new ApiService(),
            nextLink:true,
        }
    },
    created(){
        this.getPost();
    },
    
    methods:{

        toggleDDd(myDropMenu){document.getElementById(myDropMenu).classList.toggle("invisible");},

        onScroll(){
            alert("hi");
        },
        /**
         * Display all post
         */
        getPost(pageNumber = null){
            this.services.getPost(pageNumber)
            .then((response)=> {
                if(response.data.status){
                    if(pageNumber == null){
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
            .catch((errors)=>{
                console.log(errors+" errors");
            })
        },

        /**
         * scroll pagination
         */
        onNextPage: function() {
            ++this.currentpage, this.getPost(this.currentpage);
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