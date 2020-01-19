import axios from "axios";

const state = {
    mystore: []
};

const getters = {
    getMyStore: (state) => state.mystore
};

const actions = {
    async fetchMyStore({ commit }) {
        const response = await axios.get("api/store");
        commit('setMyStore', response.data)
    }
};

const mutations = {
    setMyStore: (state, mystore) => (state.mystore = mystore)
};

export default {
    state,
    getters,
    actions,
    mutations
}