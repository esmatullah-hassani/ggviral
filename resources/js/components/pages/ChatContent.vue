<template>
  <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
    <div class="bg-indigo-900 p-2 shadow text-xl text-white">
      <h3 class="font-bold pl-2">Live</h3>
    </div>
    <center><button @click="getLive">Live</button></center>
    
    <div class="col-12 video-container" v-if="callPlaced">
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
          <p>calling...</p>
        </div>
      </div>
    </div>
    <div class="action-btns ">
      
      <button
        type="button"
        class="btn btn-primary mx-4"
        @click="toggleMuteVideo"
      >
        {{ mutedVideo ? "ShowVideo" : "HideVideo" }}
      </button>
      <button type="button" class="btn btn-danger" @click="endCall">
        EndLive
      </button>
    </div><br>
  </div>
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
      user:[]
    };
  },
  created(){
    if(this.userLive){
      this.incommingLive(this.livedata);
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
        if (data.type === "incomingCall") {
          // add a new line to the sdp to take care of error
          this.incommingLive(data);
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

.video-container {
  width: 1000px;
  height: 7000px;
  max-width: 90vw;
  max-height: 50vh;
  margin: 0 auto;
  border: 1px solid #0acf83;
  position: relative;
  box-shadow: 1px 1px 11px #9e9e9e;
  background-color: #fff;
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