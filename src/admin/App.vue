<template>
    <div class="apv-backend-app">
        <Navbar />
        <RouterView />
        <Loading
            :active.sync="loader"
            loader="spinner"
            color="#1a9ed4"
            :opacity="0.8"
            :is-full-page="false"
            :width="45"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Navbar from './components/navbar/Navbar.vue';
import Loading from 'vue-loading-overlay';

export default {
    name: 'ApvAdminApp',

    components: {
        Navbar,
        Loading,
    },

    computed: mapGetters({
        loader: 'spinner/getStatus',
    }),

    created() {
        this.$store.dispatch('settings/setData', {});
    },
};
</script>

<style lang="less">
@import 'vue-loading-overlay/dist/vue-loading.css';

.apv-backend-app {
    margin: 0;
}

.apv-btn {
    font-size: 14px;
    font-weight: 600;
    padding: 8px 14px;
    min-height: 35px;
    border-radius: 3px;
    border-color: transparent;
    border-width: 1.5px;
    cursor: pointer;
    outline: none;
}

.apv-btn-submit {
    border-color: rgb(44, 186, 243);
    background-color: rgb(28, 170, 226);
    color: #fff;
    margin: 5px 0 15px 0;

    &:hover {
        background-color: rgb(44, 186, 243);
    }
}

.apv-wrap {
    .apv-inside {
        background-color: #fff;
        margin-top: 10px;
    }
}
</style>
