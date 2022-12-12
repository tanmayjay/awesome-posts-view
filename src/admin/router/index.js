import Vue from 'vue';
import Router from 'vue-router';

import Table from "../components/table/Table.vue";
import Settings from "../components/settings/Settings.vue";
import Graph from "../components/graph/Graph.vue";

Vue.use(Router);

const routes = [
    {
        path     : '/',
        component: {
            render(c) {
                return c('RouterView');
            }
        },
        children : [
            {
                path     : 'table',
                name     : 'Table',
                component: Table,
                alias    : '/',
            },
            {
                path     : 'settings',
                name     : 'Settings',
                component: Settings,
            },
            {
                path     : 'graph',
                name     : 'Graph',
                component: Graph,
            }
        ]
    },
];

export default new Router({
    linkActiveClass: 'router-link-active',
    routes
});
