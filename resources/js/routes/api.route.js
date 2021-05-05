import VueRouter from 'vue-router';
import Vue from 'vue';
import HomeContent from '../components/HomeContent';
import Profile from '../components/pages/Profile';
import Video from '../components/pages/Video';
import ChatContent from '../components/pages/ChatContent';
import UploadContent from '../components/pages/UploadContent';
import VideoDetail from '../components/VideoDetail';
import Follow from '../components/pages/Follow';
import BuyCoin from '../components/pages/BuyCoin';

Vue.use(VueRouter)

const router = new VueRouter({
  routes: [
    {path: '/', component: HomeContent},
    {path:"/users/:id",component:Profile},
    {path:"/video",component:Video},
    {path:'/chats',component:ChatContent},
    {path:'/posts/create',component:UploadContent},
    {path:"/video/:id",component:VideoDetail},
    {path:'/following',component:Follow},
    {path:'/buy-coin/:id',component:BuyCoin,}
  ]
});

export default router;