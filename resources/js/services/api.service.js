import request from '../environment/request';
export default class ApiService{

    /**
     * get all video
     */
    getPost(page){
        return request()
            .get(`/posts?page=${page}`)
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
    getUserPost(data,page){
        return request()
        .post(`/user-posts?page=${page}`,data)
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

    /**
     * display comment of spicifig video
     * @param id
     */
    getComment(id,page = null){
        return request()
        .get(`/comments/${id}?page=${page}`)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * store comment to spicifig video
     * @param id
     */
    setComment(data){
        return request()
        .post(`/comments`,data)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * delete post 
     * @param id
     */
    deletePost(id){
        return request()
        .delete(`/posts/${id}/destroy`)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * hide post
     * @param id
     */
     hidePost(id){
        return request()
        .delete(`/posts/${id}/hide`)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
     }

    /**
     * follow
     */
    userFollow(data){
        return request()
        .post("/follows",data)
        .then(response => response)
        .catch(errors => errors.response.data.errors);
    }

    /**
     * get following post
     */
     getFollowPost(page){
        return request()
            .get(`/posts-following?page=${page}`)
            .then(response => response)
            .catch(errors => errors.response.data.errors);
    }

}