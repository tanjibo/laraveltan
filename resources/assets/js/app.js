/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue amnd other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Quill=require('quill')
import ElementUI from 'element-ui'


window.reload = function () {
    swal({
        title: '操作成功',
        text: '1秒后关闭',
        timer: 1000
    }).then(
        function () {
            window.location.reload();
        },
    ).catch(res => {
        window.location.reload();
    })
}

window.filterDate = function (time) {
    var year = moment(time, 'YYYY-MM-DD').year();
    var month = moment(time, 'YYYY-MM-DD').month() + 1;
    month = month >= 10 ? month : "0" + month;
    var day = moment(time, 'YYYY-MM-DD').date();
    day = day >= 10 ? day : '0' + day;
    return year + '-' + month + '-' + day;
}

/**
 * 删除modal
 * @param callback
 */
window.delModal = function (callback) {
    swal({
        title: "确定删除吗？",
        text: "数据将无法恢复！",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
    }).then((isConfirm) => {
        if (isConfirm) {
            callback && callback();
        }
    }).catch(res => {

    })
}


Vue.use(ElementUI)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.prototype.$http = axios.create({});

Vue.prototype.$http.interceptors.response.use(function (response) {
    // Do something with response data
    return response.data;
}, function (error) {
    // Do something with response erro

    return Promise.reject(error.response.data);

});



Vue.component('uploader', require('./components/upload.vue'));
Vue.component('uploader-single', require('./components/upload-single.vue'));
Vue.component('special-price', require('./components/special-price.vue'));
Vue.component('lock-date', require('./components/lockDate.vue'));
Vue.component('comment', require('./components/comment.vue'));
Vue.component('editor', require('./components/editor.vue'));
Vue.component('art-show-comment', require('./components/art_show_comment.vue'));
Vue.component('tearoom-price', require('./components/tearoom_price.vue'));

Vue.component('tearoom-lock-time-select', require('./components/tearoom_lock_time_select.vue'));
Vue.component('eart-suggestion-reply', require('./components/art_suggestion_reply.vue'));
Vue.component('new-quill-editor', require('./components/quill_editor.vue'));
Vue.component("article_child",require('./components/article_child.vue'));




