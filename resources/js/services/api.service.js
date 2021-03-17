import request from '../environment/request';
export default class ApiService{

    /**
     * get all video
     */
    getPost(){
        return request()
            .get('/posts')
            .then(response => response)
            .catch(errors => errors.response.data.errors);
    }

    /**
     * get users
     */
    getUser(){
        return request()
        .get('/video_chat')
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * get spicific user profile and post
     * @param id
     */
    getUserPost(id){
        return request()
        .get(`/user-posts/${id}`)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * get the spicific video
     * @param id
     */
    getVideoDetail(id){
        return request()
        .get(`/video-detail/${id}`)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

}