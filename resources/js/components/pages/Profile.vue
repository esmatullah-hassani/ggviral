<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

            <div class="bg-indigo-900 p-2 shadow text-xl text-white">
                <h3 class="font-bold pl-2">Welcome to {{user.name}} profile</h3>
            </div>
            <center>
                <div class="bg-white w-full  md:w-2/3 mt-3 lg:w-1/2 xl:w-1/2 border-transparent rounded-lg shadow-lg" v-if="user_id == authuser.id">
                    <div >
                    <img class="w-40 h-40 rounded-full inline"  :src="'/uploads/users/photo/'+authuser.photo" v-if="authuser.social_path == null">
                    <img class="w-40 h-40 rounded-full inline"  :src="authuser.social_path" v-else> 
                    <div v-show="progrescount" class="overflow-hidden w-40 h-2 mb-4 text-xs flex rounded bg-pink-200">
                        <div :style="'width:'+uploadPercentage+'%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500"></div>
                    </div>
                    <label class="w-10 flex flex-col text-blue-600  items-center px-2 py-2 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-white mb-4">
                        <i class="fa fa-edit"></i>
                        <input type="file" class="hidden" name="image" id="image" ref="image" v-on:change="editImage">
                    </label>
                    
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-600">{{authuser.name}}</span>
                    </div>
                    <div class="pb-5">
                        <span class="mr-32">Followers:
                            <button class="bg-gray-500 w-4 h-4 text-orange-400 rounded-full">
                                {{follower}}
                            </button>
                        </span>
                        <span class="ml-0">Following:
                            <button class="bg-gray-500 text-blue-600 w-4 h-4 rounded-full">
                                {{following}}
                            </button>
                        </span>

                    </div>
                    
                </div>
            </center>
            <div class="flex flex-row flex-wrap flex-grow mt-2" v-infinite-scroll="onNextPage">
                
                <div class="w-full  p-3" v-for="post in posts" v-bind:key="post.id" >
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-lg">
                        <div class="bg-gray-400  text-gray-800 border-b-2 border-gray-500 rounded-tl-lg rounded-tr-lg p-2">
                            <router-link :to="'/users/'+user.id">
                                <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                                <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                                <span class="text-gray-600">{{user.name}}</span>
                            </router-link>
						        <div class="relative float-right inline-block">
                                    <button @click="toggleDDd('myDropdown'+post.id)" class="drop-button text-black focus:outline-none font-bold">
                                        ...
                                    </button>
                                    <div :id="'myDropdown'+post.id" class="dropdownlist absolute  bg-gray-400 rounded-tl-lg rounded-tr-lg text-black right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                        <a @click="showHideModal(post.id)" class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block cursor-pointer" v-if="post.user_id == user.id && post.status ==1"> Deactive </a>
                                        <a  class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block cursor-pointer text-red-700" v-else> Deactived </a>
                                        <a  @click="showDeleteModal(post.id)" class="p-2 hover:bg-gray-800 text-red-700 text-sm no-underline hover:no-underline block cursor-pointer" v-if="post.user_id == user.id"><i class="    fa-cog fa-fw" ></i> Delete</a>
                                        <div class="border border-gray-800"></div>
                                        <a href="/logout" class="p-2 hover:bg-gray-800 text-black text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Share</a>
                                
                                    </div>
                                </div>
                                
                        </div>
                        <div class="p-5">
                            <router-link :to="'/video/'+post.id">
                                <video :id="'video'+post.id" width="420" class="cursor-pointer " v-on:mouseover="playVideo(post.id);" v-on:mouseout="stopVideo(post.id)">
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

                 <!-- Start delete modal -->
                 <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" v-if="showdeletemodal">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" >
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete post
                                </h3>
                                <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete your post? your data will be permanently removed. This action cannot be undone.
                                </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="deletePost" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Yes delete
                            </button>
                            <button type="button" @click="hideDeleteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- End of delete modal -->

                <!-- Start hide modal -->
                 <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" v-if="showhidemodal">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" >
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Hide post
                                </h3>
                                <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to deactive your post? your data will be permanently deactive for another.
                                </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="hidePost" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Deactive
                            </button>
                            <button type="button" @click="hideHideModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                            </button>
                        </div>
                    </div>
                </div>
                </div>
                <!-- End of hide modal -->

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
   
    props:['authuser'],
    data(){
      return{
        services:new ApiService(),
        posts:[],
        user:[],
        currentpage: 1,
        nextLink: true,
        user_id:null,
        showdeletemodal:false,
        post_id:null,
        showhidemodal:false,
        follower:null,
        following:null,
        uploadPercentage: 0,
        progrescount:false,
        image:null,
      }
    },
    created(){
      this.user_id = this.$route.params.id;
      this.getUserPost(this.user_id,null);
    },
    
    methods:{
        toggleDDd(myDropMenu){document.getElementById(myDropMenu).classList.toggle("invisible");},
      // get spicifig user post and profile
      getUserPost(user_id,pageNumber = null){
        var formData = new FormData();
        formData.append("user_id",user_id);
        this.services.getUserPost(formData,pageNumber)
        .then((response) => {
          if(response.data.status){
            this.follower = response.data.follower;
            this.following = response.data.following;
            this.user  = response.data.user;
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
        },

        /**
         * show modal 
         */
        showDeleteModal(id){
            this.post_id = id;
            this.showdeletemodal = true;
        },

        /**
         * Hide delete modal
         */
        hideDeleteModal(){
            this.post_id = null;
            this.showdeletemodal = false;
        },

        deletePost(){
            if(this.post_id != null){
                this.services.deletePost(this.post_id)
                .then((response) => {
                    if(response.data.status){
                        this.showdeletemodal = false;
                        this.getUserPost(this.user_id,null);
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        },

        /**
         * show modal 
         */
        showHideModal(id){
            this.post_id = id;
            this.showhidemodal = true;
        },

        /**
         * Hide hide modal
         */
        hideHideModal(){
            this.post_id = null;
            this.showhidemodal = false;
        },

        hidePost(){
            if(this.post_id != null){
                this.services.hidePost(this.post_id)
                .then((response) => {
                    if(response.data.status){
                        this.showhidemodal = false;
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        },


        /*
        Submits the file to the server
        */
        editImage(){
            this.image = this.$refs.image.files[0];
            
        if( this.image != ""){
            this.progrescount = true;
            /*
                Initialize the form data
            */
            let formData = new FormData();

            /*
                Add the form data we need to submit
            */
            formData.append('photo', this.image);
            

            /*
                Make the request to the POST /single-file URL
            */
            axios.post(`/edit-image/${this.authuser.id}`,
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function( progressEvent ) {
                    this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ));
                }.bind(this)
                }
            )
            .then((data) => {
                console.log(data);
                if(data.data.status){
                    this.progrescount = false;
                    this.authuser = data.data.user;
                    window.location.href = "/";
                }
            })
            .catch(function(err){
                console.log(err);
            });
        }
        else{
            this.error = "Something was wrong! try again";
        }
        },
    }
}
</script>

<style>

</style>