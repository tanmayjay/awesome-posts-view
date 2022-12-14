import Vue from 'vue';
import Vuex from 'vuex';
import base from './modules/base';
import settings from './modules/settings';
import spinner from './modules/spinner';

Vue.use(Vuex);

// eslint-disable-next-line no-undef
const debug = process.env.NODE_ENV !== 'production';

const store = new Vuex.Store({
    modules: {
        base,
        settings,
        spinner,
    },
    strict: debug,
    plugins: []
});

export default store;
