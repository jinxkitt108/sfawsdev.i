import axios from "axios";

const state = {
    mystore: [],
    allStores: []
};

const getters = {
    getMyStore: (state) => state.mystore,
    getAllStores: (state) => state.allStores
};

const actions = {
    async fetchMyStore({ commit }) {
        const response = await axios.get("api/store");
        commit('setMyStore', response.data)
    },

    async fetchAllStores({ commit }) {
        const response = await axios.get("api/allStores");
        commit('setAllStores', response.data)
    },
};

const mutations = {
    setMyStore: (state, mystore) => (state.mystore = mystore),
    setAllStores: (state, stores) => (state.allStores = stores)
};

export default {
    state,
    getters,
    actions,
    mutations
}
