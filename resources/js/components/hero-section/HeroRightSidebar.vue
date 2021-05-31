<template>
  <div class="hidden-div shadow-lg h-16  bottom-0 mt-12 md:relative md:h-screen z-10  md:w-1/4 ">

    <div class="md:mt-12 md:w-1/4 fixed md:right-0 md:top-0 content-center md:content-start text-left justify-between overflow-y-auto max-h-screen">
      <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-2 md:px-2 text-center md:text-left mt-5">
          <li class="mr-3 flex-1" v-for="user in allusers" v-bind:key="user.id">
              <div class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white hover:border-pink-500">
                <router-link :to="'/users/'+user.id">
                  <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                  <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                  <span class="text-gray-600">{{user.name}}</span>
                </router-link>
                  <button class="py-2 px-4 bg-orange-400 float-right rounded-xl" @click="userFollow(user.id)" >
                    <span :id='"follow"+user.id' v-if="user.following.length == 0">Follow</span>
                    <span :id='"follow"+user.id' v-else>Unfollow</span>
                  </button>
              </div>
          </li>
          
          
      </ul>
    </div>
  </div>
</template>

<script>
import ApiService from '../../services/api.service';
export default {
    name:"HeroRightSidebar",
    props:['allusers','authuser'],
    data(){
      return{
        services:new ApiService(),
      }
    },
   
    methods:{

      /**
       * follow user
       */
      userFollow(user_id){
        var follow = document.getElementById("follow"+user_id);
        var formData = new FormData();
        formData.append("user_2",user_id);

        this.services.userFollow(formData)
        .then((response) => {
          if(response.data.status)
          {
            if(follow.innerHTML == "Follow")
            {
              follow.innerHTML = "Unfollow";
            }
            else
            {
              follow.innerHTML = "Follow";
            }
          }
        })
        
      }
    },
}
</script>

<style>

</style>