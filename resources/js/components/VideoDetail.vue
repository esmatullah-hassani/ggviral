<template>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

        <div class="bg-orange-400 p-2 shadow text-xl text-white text-center">
            <h3 class="font-bold pl-2">video</h3>
        </div>

        <div class="flex flex-row flex-wrap flex-grow mt-2 ">

            <div class="w-full sm:w-full md:w-2/3 bg-white border-transparent rounded-lg shadow-lg">
                <div class="bg-gray-200  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">

                    <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                    <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                    <span class="text-gray-600">{{user.name}}</span>
                </div>
                <div class="p-5">
                    
                    <iframe controls allow="autoplay" class="bg-black mx-auto w-full h-96" x-ref="player" :src="post.video_path"  frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    {{post.discription}}
                </div>
                
            </div>
            <div class="w-full sm:w-full md:w-1/3 bg-white border-transparent rounded-lg shadow-lg ">
                <div class="bg-gray-200  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                    <center>Comments</center>
                </div>
                <div class="p-5 overflow-y-auto max-h-96 usercomment" v-chat-scroll @scroll-top="onNextPage">
                    <span v-if="nextLink">
                        <center><i class="fas fa-spinner fa-pulse text-blue-600" ></i></center> 
                    </span>
                    <div class="container mb-10" v-for="comment in comments" v-bind:key="comment.id">
                        <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+comment.user.photo" v-if="comment.user.social_path == null">
                        <img class="w-7 h-7 rounded-full inline"  :src="comment.user.social_path" v-else> 
                        <span class="text-gray-600">{{comment.user.name}} say</span>
                        <br>
                        <p class="ml-4">{{comment.comment}}</p>
                    </div>

                </div>
                <div class="shadow flex">
                    <input 
                        class="w-full rounded p-2 focus:outline-none" 
                        type="text" 
                        placeholder="Write a comment ..."
                        v-model="comment"
                        @keyup="checkKey"
                    >
                    <button
                     @click="setComment"
                     class="bg-white w-auto flex justify-end items-center text-blue-500 p-2 hover:text-blue-400 focus:outline-none">
                       
                        <span v-if="load_button">
                            <i class="fas fa-spinner fa-pulse text-blue-600" ></i>
                        </span>

                        <span v-if="send_button">
                           <i class="fa fa-paper-plane  text-blue-600" ></i>
                        </span> 
                        
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
            comment:null,
            post_id:null,
            comments:[],
            send_button:true,
            load_button:false,
            currentpage: 1,
            nextLink: true,
        }
    },

    created(){
        this.post_id = this.$route.params.id;
        this.getVideoDetail(this.post_id);
        this.getComment();
    },

    methods:{

        /**
         * display a spicific video
         * @param id
         */
        getVideoDetail(id){
            this.services.getVideoDetail(id)
            .then((response) => {
                this.post = response.data.post;
                this.user = response.data.user;
            })
            .catch((error) => {
                console.log(error);
            });
        },

        /**
         * display all comment of spicifig video
         */
        getComment(pageNumber = null){
            this.services.getComment(this.post_id,pageNumber)
            .then((response) => {
                if(pageNumber == null){
                    this.comments = response.data.comments.data;
                }
                else{
                    var container = this.$el.querySelector(".usercomment");
			        container.scrollTop = container.scrollTop;
                    for(let x =0;x < response.data.comments.data.length;x++){
                        
                        this.comments.splice(0,0,response.data.comments.data[x]);
                    }
                }
                console.log(response);
                if (response.data.comments.to == response.data.comments.total){
                    this.nextLink = false;
                } 
                else if(response.data.comments.to == null){
                    this.nextLink = false;
                }

            })
            .catch((error) => {
                console.log(error);
            })
        },

        /**
         * scroll pagination
         */
        onNextPage: function() {
            ++this.currentpage, this.getComment(this.currentpage);
        },

        /**
         * store comment of spicifig video
         */
        setComment(){
            this.load_button = true;
            var formData = new FormData();
            formData.append('comment',this.comment);
            formData.append("post_id",this.post.id);
            this.services.setComment(formData)
            .then((response) => {
                if(response.data.status){
                    this.comment = "";
                    this.load_button = false;
                }
            })
            .catch((error) => {
                console.log(error);
            });
        },

        /**
         * Submit if press enter button
         */
        checkKey(e){
            if(e.keyCode == 13){
                this.setComment();
            }
        },

        /**
         * scroll down method
         */
        scrollToElement() {
			var container = this.$el.querySelector(".usercomment");
			container.scrollTop = container.scrollHeight;
		}
    },

    mounted(){
        this.scrollToElement();
        Echo.private("video-comment-"+this.post_id)
        .listen("CommentEvent",(e)=>{
            this.comments.push(e.data);
            this.scrollToElement();
		});
    }

    
}
</script>

<style>

</style>