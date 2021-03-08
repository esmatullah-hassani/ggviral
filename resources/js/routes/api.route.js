import VueRouter from 'vue-router';
import Vue from 'vue';
import HomeContent from '../components/HomeContent';
import Profile from '../components/pages/Profile';
import Video from '../components/pages/Video';
import ChatContent from '../components/pages/ChatContent';
import UploadContent from '../components/pages/UploadContent';
Vue.use(VueRouter)

const router = new VueRouter({
  routes: [
    {path: '/', component: HomeContent},
    {path:"/profiles",component:Profile},
    {path:"/video",component:Video},
    {path:'/chats',component:ChatContent},
    {path:'/posts/create',component:UploadContent}
  ]
});

export default router;