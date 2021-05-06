<template>
   <!--Nav-->
    <nav class="bg-gray-200 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">
        <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                <router-link to="/">
                    <span class="text-xl pl-2"><i class="em em-grinning "></i>GGvirl</span>
                </router-link>
            </div>

            <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-black px-2">
                <span class="relative w-full">
                    <input type="search" @keyup="checkKey" placeholder="Search" v-model="search_post" class="w-full  text-sm text-black transition border border-transparent focus:outline-none focus:border-gray-700 rounded py-1 px-2 pl-10 appearance-none leading-normal">
                    <div @click="searchPost" class="absolute search-icon cursor-pointer" style="top: .5rem; left: .8rem;">
                        <svg class="fill-current pointer-events-none text-black w-4 h-4 "  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                        </svg>
                    </div>
                </span>
			</div>

			<div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
				<ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
				    <li class="flex-1 md:flex-none md:mr-3">
					    <a class="inline-block py-2 px-4 text-white no-underline" href="#">Active</a>
				    </li>
				    <li class="flex-1 md:flex-none md:mr-3">
                        <img class="w-5 h-5 rounded-full"  :src="'/uploads/users/photo/'+authuser.photo" v-if="authuser.social_path == null">
                                <img class="w-5 h-5 rounded-full"  :src="authuser.social_path" v-else> 
				    </li>
				    <li class="flex-1 md:flex-none md:mr-3">
						<div class="relative inline-block">
							<button onclick="toggleDD('myDropdown')" class="drop-button text-white focus:outline-none">
                                {{authuser.name}}
                                <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg></button>
                            <div id="myDropdown" class="dropdownlist absolute bg-indigo-900 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
                                <input type="text" class="drop-search p-2 text-gray-600" placeholder="Search.." id="myInput" onkeyup="filterDD('myDropdown','myInput')">
                                <router-link :to="'/users/'+authuser.id" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fa fa-user-circle fa-fw"></i> Profile</router-link>
                                <a href="/user/profile" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="    fa-cog fa-fw"></i> Settings</a>
                                <div class="border border-gray-800"></div>
                                <a href="/logout" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
                           
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </nav>
</template>

<script>
import ApiService from '../../services/api.service';
export default {
    name:"HeroMenu",
    props:['authuser'],
    data(){
        return{
            services:new ApiService(),
            search_post:"",
        }
    },
    methods:{
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

</style>