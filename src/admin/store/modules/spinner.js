// Initial state
const state = {
    loader: false
};

// Getters
const getters = {
    getStatus: (state) => state.loader
};

// Actions
const actions = {
    setSpinner({commit}, payload) {
        commit('setSpinner', payload);
    }
};

// Mutations
const mutations = {
    setSpinner(state, payload) {
        state.loader = payload;
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
