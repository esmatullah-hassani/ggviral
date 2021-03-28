<template>
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

        <div class="bg-indigo-900 p-2 shadow text-xl text-white">
            <h3 class="font-bold pl-2">Analytics</h3>
        </div>

        <div class="flex flex-col flex-wrap flex-grow mt-2 justify-center">
            <center>
            <div class="w-full md:w-1/2 xl:w-1/3 p-3">
                    <label class="w-96 flex flex-col  items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer hover:bg-blue hover:text-white mt-5 ">
                    <svg class="w-9 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z"></path>
                    </svg>
                    <span class="mt-2 text-base leading-normal">Select a video</span>
                    <input type="file" class="hidden" name="video" id="video" ref="video" v-on:change="uploadVideo">
                </label><br>
                <div class="relative pt-1" v-show="progrescount">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-pink-600 bg-pink-200">
                            {{videouploading}}
                        </span>
                        </div>
                        <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-pink-600">
                            {{uploadPercentage}}%
                        </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-pink-200">
                        <div :style="'width:'+uploadPercentage+'%'" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500"></div>
                    </div>
                </div>
                <textarea class="resize-y border rounded-md w-96 focus:outline-none focus:shadow-outline" placeholder="Write any things.." v-model="discription"></textarea>
                <button @click="submitPost" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 w-96 focus:outline-none rounded-full">
                Post
                </button>
            </div>
            </center>
            

            
        </div>
    </div>
</template>

<script>
export default {
    name:'UploadVideo',
    data(){
        return{
            video: '',
            uploadPercentage: 0,
            discription:"",
            upload:"Chose a video...",
            error:"",
            showbutton:true,
            progrescount:false,
            video_name:'',
            videouploading:"Video in uploadin",
        }
    },
    methods:{
        /*
            Handles a change on the file upload
        */
        handleFileUpload(){
            this.video = this.$refs.video.files[0];
            this.upload = "Chose a video...";
            this.error = "";
        },

        /*
        Submits the file to the server
        */
        uploadVideo(){
            this.videouploading = "Video in uploading";
            this.video = this.$refs.video.files[0];
            this.upload = "Chose a video...";
            this.error = "";
        if( this.video != ""){
            this.upload = "Wait...";
            this.showbutton=false;
            this.progrescount = true;
            /*
                Initialize the form data
            */
            let formData = new FormData();

            /*
                Add the form data we need to submit
            */
            formData.append('video', this.video);

            /*
                Make the request to the POST /single-file URL
            */
            axios.post('/upload',
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
                    this.videouploading="Video uploaded";
                    this.video_name = data.data.message;
                    // this.upload = "uploaded successfully";
                    // this.showbutton = true;
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
        submitPost(){
            if(this.video_name != ""){
                var formData = new FormData();
                formData.append('video_path', this.video_name);
                formData.append("discription",this.discription);

                /*
                    Make the request to the POST /single-file URL
                */
                axios.post('/posts',
                    formData,
                )
                .then((data) => {
                    if(data.data.status){
                        this.video ="";
                        this.video_name="";
                        this.discription = "";
                        this.progrescount = false;
                    }
                })
                .catch(function(err){
                    console.log(err);
                });
            }
        }
    },
}
</script>

<style>
.progress-count{
    text-align: center;
}
</style>