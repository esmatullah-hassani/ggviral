<template>
   <!--Nav-->
    <nav class="items-center bg-white p-6 border-b border-gray-300 pt-2 md:pt-1 pb-1 px-1 mt-0 h-16 fixed w-full z-20 top-0">
        <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start hover:text-gray-900 ">
               
                    <span id="openMenu"  @click="toggleMobileMenu('myDropdownMenu')"  class=" visible sm:visible md:hidden text-xl pl-2 cursor-pointer drop-button "><i class="fa fa-bars " ></i></span>
               
            </div>

            <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-black px-2">
                <span class="relative w-full">
                    <input type="search" @keyup="checkKey" placeholder="Search" v-model="search_post" class="w-full  text-sm text-black transition border  focus:outline-none focus:border-gray-700 rounded py-1 px-2 pl-10 appearance-none leading-normal">
                    <div @click="searchPost" class="absolute search-icon cursor-pointer" style="top: .5rem; left: .8rem;">
                        <svg class="fill-current pointer-events-none text-black w-4 h-4 "  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                        </svg>
                    </div>
                </span>
			</div>

			<div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end sm:float-right hidden-div">
				<ul class="list-reset flex justify-between flex-1 md:flex-none items-center ">
				    
				    <li class="flex-1 md:flex-none md:mr-3">
                        <img class="w-5 h-5 rounded-full"  :src="'/uploads/users/photo/'+authuser.photo" v-if="authuser.social_path == null">
                                <img class="w-5 h-5 rounded-full"  :src="authuser.social_path" v-else> 
				    </li>
				    <li class="flex-1 md:flex-none sm:mr-0  ">
						<div class="relative inline-block right-0">
							<button onclick="toggleDD('myDropdown')" class="drop-button  focus:outline-none hover:text-gray-900">
                                {{authuser.name}}
                                <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg></button>
                            <div id="myDropdown" class="dropdownlist absolute bg-gray-200 shadow-lg  right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                <input type="text" class="drop-search p-2 text-gray-600 border  focus:outline-none focus:border-gray-700 rounded py-1 px-2 pl-10 appearance-none leading-normal" placeholder="Search.." id="myInput" onkeyup="filterDD('myDropdown','myInput')">
                                <router-link :to="'/users/'+authuser.id" class="p-2 hover:bg-orange-400 hover:text-white  text-sm no-underline hover:no-underline block"><i class="fa fa-user-circle fa-fw"></i> Profile</router-link>
                                <a href="/user/profile" class="p-2 hover:bg-orange-400 hover:text-white text-sm no-underline hover:no-underline block"><i class="fa fa-cog"></i> Settings</a>
                                <div class="border border-orange-400"></div>
                                <a href="/logout" class="p-2 hover:bg-orange-400 hover:text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
                           
                            </div>
                        </div>
                    </li>
                </ul>
                
            </div>
            
        </div>
        <div id="myDropdownMenu" class="visible sm:visible md:hidden dropdownlist top-0  bg-white left-0 mt-0 h-screen  w-1/2 z-30 shadow-inner">
            <span @click="closeSidebarMenu('myDropdownMenu')"  id="closeMenu" class=" visible sm:visible md:hidden text-xl pl-2 cursor-pointer drop-button float-right"><i class="fas fa-times"></i></span>

            <router-link :to="'/users/'+authuser.id" class="p-2 hover:bg-orange-400 hover:text-white  text-sm no-underline hover:no-underline block "><i class="fa fa-user-circle fa-fw"></i> Profile</router-link>
            <a href="/user/profile" class="p-2 hover:bg-orange-400 hover:text-white  text-sm no-underline hover:no-underline block"><i class="fa fa-cog"></i> Settings</a>
            <a href="/logout" class="p-2 hover:bg-orange-400 hover:text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
            <div class="border hover:bg-orange-400 hover:text-white"></div>
            
                <div class="overflow-y-auto max-h-screen text-left justify-between">
                    <div v-for="user in allusers" v-bind:key="user.id">
                        <div class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white hover:border-pink-500">
                            <router-link :to="'/users/'+user.id">
                            <img class="w-7 h-7 rounded-full inline"  :src="'/uploads/users/photo/'+user.photo" v-if="user.social_path == null">
                            <img class="w-7 h-7 rounded-full inline"  :src="user.social_path" v-else> 
                            <span class="text-gray-600">{{user.name}}</span>
                            </router-link>                                      
                            <button class="py-2 px-4 bg-orange-400 float-right rounded-xl" @click="mobileUserFollow(user.id)" >
                                <span :id='"mobilefollow"+user.id' v-if="user.following.length == 0">Follow</span>
                                <span :id='"mobilefollow"+user.id' v-else>Unfollow</span>
                            </button>
                        </div>
                    </div>
                    
                </div>
            
        </div>

    </nav>
</template>

<script>
import ApiService from '../../services/api.service';
export default {
    name:"HeroMenu",
    props:['authuser','allusers'],
    data(){
        return{
            services:new ApiService(),
            search_post:"",
        }
    },
    methods:{

      /**
       * follow user
       */
        mobileUserFollow(user_id){
            var follow = document.getElementById("mobilefollow"+user_id);
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
       
        },
        toggleMobileMenu(myDropMenu){
            var menu ="";
            menu = document.getElementById(myDropMenu);
            console.log(menu);
            menu.style.visibility="visible";
            var openMenu = document.getElementById("openMenu");
            var closeMenu = document.getElementById("closeMenu");
            openMenu.style.visibility="hidden";
            closeMenu.style.visibility="visible";
        },
        closeSidebarMenu(myDropMenu){
            var menu ="";
            menu = document.getElementById(myDropMenu);
            console.log(menu);
            menu.style.visibility="hidden";
            var openMenu = document.getElementById("openMenu");
            var closeMenu = document.getElementById("closeMenu");
            openMenu.style.visibility="visible";
            closeMenu.style.visibility="hidden";
        },

        logoutUser(){
            this.services.logoutUser()
                .then(reqponse => {
                    console.log(response);
                });
        },
        searchPost(){
            if(this.search_post != "" ){
                this.$router.push("/"+this.search_post)
                .catch((er)=>{})
                ;
            }
        },

        /**
         * Submit if press enter button
         */
        checkKey(e){
            if(e.keyCode == 13){
                this.searchPost();
            }
        },
    }
}

</script>

<style>
#myDropdownMenu{
    visibility: hidden;
}
#closeMenu{
    visibility: hidden;
}

</style>