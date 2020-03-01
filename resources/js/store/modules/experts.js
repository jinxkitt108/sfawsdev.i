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
        commit("setAllExperts", response.data)
    }
};

const mutations = {
    setAllExperts: (state, experts) => state.allExperts = experts
};

export default {
    state,
    getters,
    actions,
    mutations
};
