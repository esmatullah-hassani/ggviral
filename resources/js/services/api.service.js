import request from '../environment/request';
export default class ApiService{

    /**
     * get all video
     */
    getVideo(){
        return request()
            .get('/get-video')
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

}