import VueResource from 'vue-resource';

window.Vue = require('vue');
window.Vue.use(VueResource);
window.$ = window.jQuery = require('jquery');
window.formDataJSON = require('formdata-json');

let token = document.head.querySelector('meta[name="csrf-token"]');

/*if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}*/

//viewport
let docWidth = document.body.clientWidth,
    viewPortContent;

if (docWidth > 1000) {
    viewPortContent = "width=device-width, maximum-scale=1, initial-scale=1.0";
} else if (docWidth > 767) {
    viewPortContent = "width=1000, user-scalable=no";
} else {
    viewPortContent = "width=375, user-scalable=no";
}
document.getElementById("viewport").setAttribute("content", viewPortContent);
//end