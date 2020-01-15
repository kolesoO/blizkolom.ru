import VueResource from 'vue-resource';

window.Vue = require('vue');
window.Vue.use(VueResource);
window.$ = window.jQuery = require('jquery');
window.formDataJSON = require('formdata-json');

let token = document.head.querySelector('meta[name="csrf-token"]');
