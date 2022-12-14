import store from "..";
import Api from "../../utils/api";

// initial state
const state = {
    data: {},
};

const getters = {
    getData: (state) => state.data,
};

const actions = {
    // eslint-disable-next-line no-unused-vars
    parseData: async({ commit }) => {
        store.dispatch('spinner/setSpinner', true);

        Api.get('get_settings').done(response => {
            if(response.success){
                commit('setData', response.data)
            }
            store.dispatch('spinner/setSpinner', false);
        });
    },

    updateData: async({ commit }, payload) => {
        store.dispatch('spinner/setSpinner', true);
        return new Promise((resolve, reject) => {
            Api.post('update_settings', payload).done(response => {
                if (response.success) {
                    resolve(response);
                } else {
                    reject(response);
                }
                store.dispatch('spinner/setSpinner', false);
            });
        });
    },

    setData: ({state, commit, dispatch}, payload) => {
        commit('setData', payload);

        if (Object.keys(state.data || {}).length === 0){
            dispatch('parseData');
        }
    },
};

const mutations = {
    setData: (state, payload) => {
        state.data = payload;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
