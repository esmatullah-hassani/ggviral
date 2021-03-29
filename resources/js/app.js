require('./bootstrap');
import Vue from 'vue';
require('alpinejs');
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import App from './components/App';
import router from './routes/api.route';
import infiniteScroll from "vue-infinite-scroll";
import VueChatScroll from 'vue-chat-scroll-top-scroll';
Vue.use(infiniteScroll);
Vue.use(VueChatScroll);

const app = new Vue({
    el: '#app',
    router,
    infiniteScroll,
    VueChatScroll,
    components:{
     App
    },
 
  });
 