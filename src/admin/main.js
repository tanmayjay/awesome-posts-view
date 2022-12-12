import Vue from 'vue';
import App from './App.vue';
import store from './store';
import router from './router';
import common from './mixins/common';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(VueToast, {
    position: 'bottom',
});

Vue.mixin(common);

new Vue({
    el: '#apv-admin-app',
    router,
    store,
    render: h => h(App),
});
