<template>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

        <div class="flex flex-row flex-wrap flex-grow mt-2" v-infinite-scroll="onNextPage">

            <div class="w-full  p-3" v-for="(post,index) in posts" v-bind:key="post.id" >
                <!--Graph Card-->
                <div class="bg-white border-transparent rounded-lg shadow-lg " >
                    <div class="bg-gray-200  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                        <router-link :to="'/users/'+post.user.id">
                            <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+post.user.photo" v-if="post.user.social_path == null">
                            <img class="w-7 h-7 rounded-full inline"  :src="post.user.social_path" v-else> 
                            <span class="text-gray-600">{{post.user.name}}</span>
                        </router-link>

                        <div class="relative float-right inline-block ">
                            <button @click="toggleDDd('myDropdown'+post.id)" class="drop-button text-orange-400 focus:outline-none font-bold">
                                ...
                            </button>
                            <div :id="'myDropdown'+post.id" class="dropdownlist absolute w-60 bg-gray-200 rounded-tl-lg rounded-tr-lg text-black right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                <a  class="p-2 hover:bg-orange-400 hover:text-white text-blue-700 text-sm no-underline hover:no-underline block cursor-pointer" @click="shareOnfacebook(post.id,post.user.name)"> <i class="fab fa-facebook"></i> Share</a>
                                <a  class="p-2 hover:bg-orange-400 hover:text-white text-blue-500 text-sm no-underline hover:no-underline block cursor-pointer " @click="shareOntwitter(post.id,post.user.name)"> <i class="fab fa-twitter"></i> Share </a>
                                <a   class="p-2 hover:bg-orange-400 hover:text-white text-black text-sm no-underline hover:no-underline block cursor-pointer"  @click="copyToClipboard(post.id);showToast('Link copied to clipboard.')"><i class="fa fa-copy" ></i> Copy</a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="p-5">

                            <video  :id="'video'+post.id"  class="max-h-screen mb-2"
                                
                                >
                                <source :src="post.video_path" type="video/mp4">
                                <source src="" type="video/ogg">
                                Your browser does not support HTML video.
                            </video>
                            <button   @click="playVideo(post.id);"   class="active video-button focus:outline-none"><i  class="fa fa-play text-orange-400" :id="'video-play'+post.id"></i></button>

                            
                    
                        <span class="pt-5">
                            {{post.discription.substring(0,30)}}
                            {{post.created_at.diffForHumans}}   
                        </span>
                        <p v-for="comment in post.comments" v-bind:key="comment.id">
                            <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+comment.photo" v-if="comment.social_path == null">
                            <img class="w-7 h-7 rounded-full inline"  :src="comment.social_path" v-else> 
                            <span class="font-bold text-black">
                                {{comment.name}} 
                            </span><br>
                            <span>
                                 {{comment.comment.substring(0,90)}}
                            </span>
                        </p>
                       
                    </div>
                    <div class="shadow flex">
                        <input 
                            class="w-full  text-sm text-black transition   focus:outline-none focus:border-gray-700 rounded py-1 px-2 pl-10 appearance-none leading-normal" 
                            type="text" 
                            placeholder="Write a comment ..."
                            v-model="comment"
                            @keyup="checkKey($event,post.id,index)"
                        >
                        <button
                        @click="setComment(post.id,index)"
                        class="bg-white w-auto flex justify-end items-center text-blue-500 p-2 hover:text-blue-400 focus:outline-none">
                        
                            <span :id="'load_button'+post.id" class="invisible">
                                <i class="fas fa-spinner fa-pulse text-blue-600" ></i>
                            </span>

                            <span v-if="send_button">
                            <i class="fa fa-paper-plane  text-orange-400" ></i>
                            </span> 
                            
                        </button>
                    </div>
                    
                </div>
                <!--/Graph Card-->
            </div>

        </div>
        <span v-if="nextLink">
            <center>
                <i class="fas fa-spinner fa-pulse text-blue-600" ></i>
            </center> 
        </span>
    </div>
</template>

<script>
import ApiService from '../services/api.service'
export default {
    name:"HomeContent",
    props:["authuser"],
    data(){
        return{
            currentpage:1,
            posts:[],
            services:new ApiService(),
            nextLink:true,
            follow_collor:"bg-pink-500 hover:bg-blue-700",
            follows:null,
            send_button:true,
            load_button:false,
            comment:null,
            comments:[],
        }
    },
    created(){
        this.getPost();
    },
    mounted(){
        Echo.private("video-comment")
        .listen("CommentEvent",(e)=>{
            // this.posts[].comments.push(e.data);
           console.log(e);
		});
    },
    methods:{

        /**
         * store comment of spicifig post
         */
        setComment(post_id,index){
            var load_button = document.getElementById("load_button"+post_id);
            
            load_button.classList.add("invisible",'visible'); 
            var formData = new FormData();
            formData.append('comment',this.comment);
            formData.append("post_id",post_id);
            formData.append("post_index",index);
            this.comment = "";
            this.services.setComment(formData)
            .then((response) => {
                if(response.data.status){
                    this.posts[index].comments.push(...response.data.message.data);
                    load_button.classList.add("visible",'invisible'); 
                }
            })
            .catch((error) => {
                console.log(error);
            });
        },

        /**
         * Submit if press enter button
         */
        checkKey(event,post_id,index){
            if(event.keyCode == 13){
                this.setComment(post_id,index);
            }
        },

        //share to facebook
        shareOnfacebook(video,name){
            var url = `https://www.facebook.com/sharer/sharer.php?u=ggviral.com/#/video/${video}`;
            window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
            return false;
        },
        //share to twitter
        shareOntwitter(video,name){
            var url = `https://twitter.com/intent/tweet?url=${video}&via=${name}&text=See this image`;
            TwitterWindow = window.open(url, 'TwitterWindow',width=600,height=300);
            return false;
        },

        //coppy the link
        copyToClipboard(video) {
        var input = document.body.appendChild(document.createElement("input"));
        input.value = "ggviral.com/#/video/"+video;
        input.select();
        document.execCommand('copy');
        input.parentNode.removeChild(input);
        },

        //show toast
        showToast(text) {
        // Get the snackbar DIV
        var x = document.getElementById("snackbar");
        x.innerText = text;

        // Add the "show" class to DIV
        x.className = "show";

        // After 3 seconds, remove the show class from DIV
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        },
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
                    if(response.data.posts.data.length == 0){
                        this.nextLink = false;
                    }
                    if(pageNumber == null){
                        this.posts = response.data.posts.data;
                        this.follows = response.data.follows;
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
            var videoplay = document.getElementById('video-play'+id);
            
            if(videoplay.classList.contains('fa-play')){
                videoplay.classList.add('fa-play','fa-stop');
                myVideo.play();
            }
            else if(videoplay.classList.contains("fa-stop")){
                videoplay.classList.add("fa-stop",'fa-play');
                myVideo.pause();
            }
            // myVideo.play();<i class="fas fa-stop"></i>
        },

        /**
         * stop video
         */
        stopVideo(id){
            var myVideo = document.getElementById("video"+id);
            myVideo.pause();
        },

        /**
         * follow
         */
        userFollow(user_2){
            var formData = new FormData();
            formData.append("user_1",this.authuser.id);
            formData.append("user_2",user_2);
            this.services.userFollow(formData)
            .then((response) => {
                if(response.data.status){
                    this.getPost(this.currentpage);
                    // if(response.data.message == 1){
                        
                    // }
                    // else if(response.data.message == 2){
                    //     var dd = document.getElementById("follow_class"+user_2);
                    //     dd = "UnFollow";
                    //     console.log(dd);
                    // }
                }
            })
            .catch((errors) => {
                console.log(errors);
            });
        }
    }

}
</script>

<style>

</style>