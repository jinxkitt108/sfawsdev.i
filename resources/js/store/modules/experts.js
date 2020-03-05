import axios from "axios";

const state = {
    allExperts: [],
    expert: []
};

const getters = {
    getAllExperts: state => state.allExperts,
    getExpert: state => state.expert
};

const actions = {
    async fetchAllExperts({ commit }) {
        const response = await axios.get("api/expert");
        commit("setAllExperts", response.data);
    },

    async addExpert({ commit }, expert) {
        const response = await axios.post("api/expert", expert);
        commit("newExpert", response.data);
    },

    async fetchExpert({ commit }, id) {
        const response = await axios.get("api/expert/" + id);
        commit("setExpert", response.data);
    }
};

const mutations = {
    setAllExperts: (state, experts) => (state.allExperts = experts),
    newExpert: (state, expert) => state.allExperts.unshift(expert),
    setExpert: (state, expert) => (state.expert = expert)
};

export default {
    state,
    getters,
    actions,
    mutations
};
