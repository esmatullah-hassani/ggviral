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

const app = new Vue({
    el: '#app',
    router,
    components:{
     App
    },
 
  });
 