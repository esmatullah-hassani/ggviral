<template>
    <div class="main-content flex-1 bg-gray-100 mt-4 md:mt-4 pb-24 md:pb-5 justify-center">
    
    <center><button @click="getLive" class="py-2 px-4 bg-orange-400 rounded-full text-white hover:font-bold"> <i class="fa fa-video"></i> Live</button></center>

    
    <div class="w-full sm:w-3/4 md:w-3/4 md:ml-28" v-if="callPlaced">
    <video
      ref="userVideo"
      muted
      playsinline
      autoplay
      class="cursor-pointer"
      :class="isFocusMyself === true ? 'user-video' : 'partner-video'"
      @click="toggleCameraArea"
    />
    <video
      ref="partnerVideo"
      playsinline
      autoplay
      class="cursor-pointer"
      :class="isFocusMyself === true ? 'partner-video' : 'user-video'"
      @click="toggleCameraArea"
      v-if="videoCallParams.callAccepted"
    />
    <div class="partner-video" v-else>
      <div v-if="callPartner" class="column items-center q-pt-xl">
        <div class="col q-gutter-y-md text-center">
          <p class="q-pt-md">
            <strong>{{ callPartner }}</strong>
          </p>
          <p class="text-orange-400">In live...</p>
        </div>
      </div>
    </div>
    <div class="action-btns ">
      
      <div class="mb-0" v-show="controlCall">
        <button
        type="button"
        class="btn btn-primary mx-4 py-2 px-4 hover:bg-orange-400 hover:text-white rounded-full"
        @click="toggleMuteVideo"
        >
          {{ mutedVideo ? "ShowVideo" : "HideVideo" }}
        </button>
        <button type="button" class="btn btn-danger py-2 px-4 bg-transparent hover:bg-orange-400 hover:text-white rounded-full" @click="endCall">
          EndLive
        </button>
      </div>
      <div class="buttons coin-div" v-show="giftdiv">
            <a style="color:blue;" class="cursor-pointer" @click="setGift(10)">
              <span>10$</span>
              <i class="fas fa-gift"></i>
            </a>
            <a style="color:red; padding-left:10%;" class="cursor-pointer" @click="setGift(8)">
              <span>8$</span>
              <i class="fas fa-heart mp" ></i>
            </a>
            <a style="color: rgb(51, 255, 0); padding-left:10%;" class="cursor-pointer" @click="setGift(5)">
              <span>5$</span>
              <img src="/images/gift-image.jpeg" class="w-8 cursor-pointer">
            </a>
      </div>
    </div>
    
    <br>
    

  </div>
  <!-- Start coin modal -->
      <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" v-if="showpaymentmodal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Heroicon name: outline/exclamation -->
                    <img src="/images/buy-for-change.png"/>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Buy coins
                    </h3>
                    <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        You need to buy some coin,Please buy 
                    </p>
                    </div>
                </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="buyCoin()"  class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Yes Buy
                </button>
                <button type="button" @click="hideDeleteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
                </button>
            </div>
            </div>
        </div>
    </div>
    <!-- End of coin modal -->
  </div>
</template>
<script>
import Peer from "simple-peer";
import { getPermissions } from "../../helper";
import axios from 'axios';
export default {
     props: [
    "allusers",
    "authuser",
    "turn_url",
    "turn_username",
    "turn_credential",
    'livedata',
    'userLive',
    
  ],
  data() {
    return {
      isFocusMyself: true,
      callPlaced: false,
      callPartner: null,
      mutedAudio: false,
      mutedVideo: false,
      videoCallParams: {
        users: [],
        stream: null,
        receivingCall: false,
        caller: null,
        callerSignal: null,
        callAccepted: false,
        channel: null,
        peer1: null,
        peer2: null,
      },
      allMessages:[],
      user:[],
      showpaymentmodal:false,
      controlCall:true,
      giftdiv:false,
    };
  },
  created(){
    if(this.userLive){
      this.incommingLive(this.livedata);
      this.controlCall = false;
      this.giftdiv = true;
    }
  },
  mounted() {
  
    this.initializeChannel(); // this initializes laravel echo
     this.initializeCallListeners(); // subscribes to video presence channel and listens to video events
    
     
  },
  computed: {
    

    callerDetails() {
      if (
        this.videoCallParams.caller &&
        this.videoCallParams.caller !== this.authuser.id
      ) {
        const incomingCaller = this.allusers.filter(
          (user) => user.id === this.videoCallParams.caller
        );

        return {
          id: this.videoCallParams.caller,
          name: `${incomingCaller[0].name}`,
        };
      }
      return null;
    },
  },
  methods: {

    /**
       * Hide delete modal
       */
      hideDeleteModal(){
          this.showpaymentmodal = false;
      },

     checkGift(price){
      var formData = new FormData();
      formData.append("user_id",this.authuser.id);
      formData.append("price",price);
      axios.post("/check-coin",formData)
      .then((response) => {
        if(response.data.status){
          if(response.data.message >= price){
            return true;
          }
          else{
              this.showpaymentmodal = true;
          }
        }
        else{
          this.showpaymentmodal = true; 
          
        }
      })
      .catch((error)=>{
        console(error);
      });
    },

    buyCoin(){
      this.showdiv=false;
      this.$router.push("/buy-coin/"+this.authuser.id)
      .catch((er)=>{})
      ;
    },

    setGift(price){
      var formData = new FormData();
      formData.append("user_id",this.authuser.id);
      formData.append("price",price);
      axios.post("/check-coin",formData)
      .then((response) => {
        if(response.data.status){
          if(response.data.message >= price){
            axios.post("/set-gift",formData)
            .then((response)=>{
              if(response.data.status){
                alert("Thanks");
              }
            })
            .catch((err)=>{

            })
          }
          else{
              this.showpaymentmodal = true;
          }
        }
        else{
          this.showpaymentmodal = true; 
          
        }
      })
      .catch((error)=>{
        console(error);
      });
    },

    /**
     * get spicific user
     * @param id
     */
     getUser(){
       alert("hello");
       axios.get("/users-get")
          .then(response => {
              console.log(response);
          })
     },
    initializeChannel() {
      this.videoCallParams.channel = window.Echo.join("presence-video-channel");
      
    },

    getMediaPermission() {
      return getPermissions()
        .then((stream) => {
          this.videoCallParams.stream = stream;
          if (this.$refs.userVideo) {
            this.$refs.userVideo.srcObject = stream;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    initializeCallListeners() {
      this.videoCallParams.channel.here((users) => {
        this.videoCallParams.users = users;
      });

      this.videoCallParams.channel.joining((user) => {
        // check user availability
        const joiningUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        if (joiningUserIndex < 0) {
          this.videoCallParams.users.push(user);
        }
      });

      this.videoCallParams.channel.leaving((user) => {
        const leavingUserIndex = this.videoCallParams.users.findIndex(
          (data) => data.id === user.id
        );
        this.videoCallParams.users.splice(leavingUserIndex, 1);
      });
      // listen to incomming call
      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        if (data.type === "incomingLive") {
          // add a new line to the sdp to take care of error
          this.incommingLive(data);
          this.controlCall = false;
          this.giftdiv = true;
        }
      });
      
    },
    incommingLive(data){
      const updatedSignal = {
        ...data.signalData,
        sdp: `${data.signalData.sdp}\n`,
      };

      this.videoCallParams.receivingCall = true;
      this.videoCallParams.caller = data.from;
      this.videoCallParams.callerSignal = updatedSignal;
        if (
          this.videoCallParams.receivingCall &&
          this.videoCallParams.caller !== this.authuser.id.id
        ) {
          this.acceptCall();
          
        }
    },
    async placeVideoCall(id, name) {
      this.callPlaced = true;
      this.callPartner = name;
      await this.getMediaPermission();
      this.videoCallParams.peer1 = new Peer({
        initiator: true,
        trickle: false,
        stream: this.videoCallParams.stream,
       
      });

      this.videoCallParams.peer1.on("signal", (data) => {
        // send user call signal
        axios
          .post("/video/call-user", {
            user_to_call: id,
            signal_data: data,
            from: this.authuser.id.id,
          })
          .then(() => {})
          .catch((error) => {
            console.log("error"+error);
          });
      });

      this.videoCallParams.peer1.on("stream", (stream) => {
        console.log("call streaming");
        if (this.$refs.partnerVideo) {
          this.$refs.partnerVideo.srcObject = stream;
        }
      });

      this.videoCallParams.peer1.on("connect", () => {
        console.log("peer connected");
      });

      this.videoCallParams.peer1.on("error", (err) => {
        console.log("Peer "+err);
      });

      this.videoCallParams.peer1.on("close", () => {
        console.log("call closed caller");
        
      });

      this.videoCallParams.channel.listen("StartVideoChat", ({ data }) => {
        if (data.type === "callAccepted") {
          if (data.signal.renegotiate) {
            console.log("renegotating");
          }
          if (data.signal.sdp) {
            this.videoCallParams.callAccepted = true;
            const updatedSignal = {
              ...data.signal,
              sdp: `${data.signal.sdp}\n`,
            };
            this.videoCallParams.peer1.signal(updatedSignal);
          }
        }
      });
    },

    async acceptCall() {
      this.callPlaced = true;
      this.videoCallParams.callAccepted = true;
      this.videoCallParams.peer2 = new Peer({
        initiator: false,
        trickle: false,
        stream: this.videoCallParams.stream,
       
      });
      this.videoCallParams.receivingCall = false;
      this.videoCallParams.peer2.on("signal", (data) => {
        axios
          .post("/video/accept-call", {
            signal: data,
            to: this.videoCallParams.caller,
          })
          .then(() => {})
          .catch((error) => {
            console.log(error);
          });
      });

      this.videoCallParams.peer2.on("stream", (stream) => {
        this.videoCallParams.callAccepted = true;
        this.$refs.partnerVideo.srcObject = stream;
      });

      this.videoCallParams.peer2.on("connect", () => {
        console.log("peer connected");
        this.videoCallParams.callAccepted = true;
      });

      this.videoCallParams.peer2.on("error", (err) => {
        console.log(err);
      });

      this.videoCallParams.peer2.on("close", () => {
        console.log("call closed accepter");
        this.controlCall = true;
        this.giftdiv = false;
        this.userLive = false;
      });

      this.videoCallParams.peer2.signal(this.videoCallParams.callerSignal);
    },
    toggleCameraArea() {
      if (this.videoCallParams.callAccepted) {
        this.isFocusMyself = !this.isFocusMyself;
      }
    },
    getUserOnlineStatus(id) {
      const onlineUserIndex = this.videoCallParams.users.findIndex(
        (data) => data.id === id
      );
      if (onlineUserIndex < 0) {
        return "Offline";
      }
      return "Online";
    },
    declineCall() {
      this.videoCallParams.receivingCall = false;
    },

    toggleMuteAudio() {
      if (this.mutedAudio) {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = true;
        this.mutedAudio = false;
      } else {
        this.$refs.userVideo.srcObject.getAudioTracks()[0].enabled = false;
        this.mutedAudio = true;
      }
    },

    toggleMuteVideo() {
      if (this.mutedVideo) {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = true;
        this.mutedVideo = false;
      } else {
        this.$refs.userVideo.srcObject.getVideoTracks()[0].enabled = false;
        this.mutedVideo = true;
      }
    },

    stopStreamedVideo(videoElem) {
      const stream = videoElem.srcObject;
      const tracks = stream.getTracks();
      tracks.forEach((track) => {
        track.stop();
      });
      videoElem.srcObject = null;
    },
    endCall() {
      // if video or audio is muted, enable it so that the stopStreamedVideo method will work
      if (!this.mutedVideo) this.toggleMuteVideo();
      if (!this.mutedAudio) this.toggleMuteAudio();
      
      this.stopStreamedVideo(this.$refs.userVideo);
      if (this.authuser.id === this.videoCallParams.caller) {
        this.videoCallParams.peer1.destroy();
      } else {
        this.videoCallParams.peer2.destroy();
      }
      this.videoCallParams.channel.pusher.channels.channels[
        "private-presence-video-channel"
      ].disconnect();

      setTimeout(() => {
        this.callPlaced = false;
      }, 3000);
    },
    getLive(){
      this.placeVideoCall(this.authuser.id,this.authuser.name);
    }
  },
};
</script>

<style scoped>
#video-row {
  width: 100%;
  max-width: 90vw;
}

#incoming-call-card {
  border: 1px solid #0acf83;
}



.video-container .user-video {
  width: 20%;
  position: absolute;
  left: 10px;
  bottom: 10px;
  border: 1px solid #fff;
  border-radius: 6px;
  z-index: 2;
}

.video-container .partner-video {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  top: 0;
  z-index: 1;
  margin: 0;
  padding: 0;
}

.video-container .action-btns {
  position: absolute;
  bottom: 20px;
  left: 50%;
  margin-left: -50px;
  z-index: 3;
  display: flex;
  flex-direction: row;
  align-content: center;
}

/* Mobiel Styles */
@media only screen and (max-width: 768px) {
  .video-container {
    height: 50vh;
  }
}

</style>