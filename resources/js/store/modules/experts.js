import axios from "axios";

const state = {
    allExperts: [],
    expert: []
};

const getters = {
    getAllExperts: state => state.allExperts
};

const actions = {
    async fetchAllExperts({ commit }) {
        const response = await axios.get("api/expert");
        commit("setAllExperts", response.data);
    },

    async addExpert({ commit }, expert) {
        const response = await axios.post("api/expert", expert);
        commit("newExpert", response.data);
    }
};

const mutations = {
    setAllExperts: (state, experts) => (state.allExperts = experts),
    newExpert: (state, expert) => state.allExperts.unshift(expert)
};

export default {
    state,
    getters,
    actions,
    mutations
};
