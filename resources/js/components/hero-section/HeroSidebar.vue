<template>
  <div class="shadow-lg h-16 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48 sm:overflow-x-auto">

            <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between overflow-x-auto sm:overflow-x-auto md:overflow-y-auto">
                <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">
                    <li class="mr-3 flex-1 ">
                        <router-link to="/" class="block py-1 md:py-3 pl-1 align-middle  no-underline border-b-2  hover:border-orange-400  ">
                         <i class="fas fa-home pr-0 md:pr-0 text-orange-400"></i><span class="pb-1 md:pb-0 text-xs      md:text-base   block md:inline-block pl-5">Home</span>
                         </router-link>
                    </li>
                    <li class="mr-3 flex-1">
                        <router-link to="/posts/create" class="block py-1 md:py-3 pl-1 align-middle  no-underline  border-b-2  hover:border-orange-400">
                            <i class="fas fa-upload pr-0 md:pr-0 text-orange-400"></i><span class="pb-1 md:pb-0 text-xs md:text-base   block md:inline-block pl-5">Upload</span>
                        </router-link>
                    </li>
                    <li class="mr-3 flex-1">
                        <router-link to="/chats" class="block py-1 md:py-3 pl-1 align-middle  no-underline  border-b-2  hover:border-orange-400">
                            <i class="fa fa-video pr-0 md:pr-0 text-orange-400" title="live"></i><span class="pb-1 md:pb-0 text-xs md:text-base   block md:inline-block pl-5">Live</span>
                            <i v-show="showEye" class="fa fa-eye pr-0 md:pr-0 text-orange-600"></i>
                        </router-link>
                    </li>
                    <li class="mr-3 flex-1">
                        <router-link to="/following" class="block py-1 md:py-3 pl-1 align-middle  no-underline  border-b-2  hover:border-orange-400">

                            <i class="fa fa-user pr-0 md:pr-0 text-orange-400"></i><span class="pb-1 md:pb-0 text-xs md:text-base   block md:inline-block pl-5">Following</span>
                        </router-link>
                    </li>
                    <li class="mr-3 flex-1">
                        <router-link to="/follower" class="block py-1 md:py-3 pl-1 align-middle  no-underline  border-b-2 hover:border-orange-400">

                            <i class="fas fa-user pr-0 md:pr-0  text-orange-400"></i><span class="pb-1 md:pb-0 text-xs md:text-base   block md:inline-block pl-5">Follower</span>

                        </router-link>
                    </li>
                    
                </ul>
            </div>


        </div>
</template>

<script>
export default {
    props:['userLive','livedata'],
    data(){
        return{
            showEye:false,
        }
    },
    mounted(){
      
        window.Echo.join("presence-video-channel").listen("StartVideoChat", ({ data }) => {
            this.showEye = true;
            
        });
    },
   
}
</script>

<style>

</style>