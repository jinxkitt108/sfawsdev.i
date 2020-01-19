import axios from "axios";

const state = {
    product: []
}

const getters = {
    getProduct: (state) => state.product
}

const actions = {
    async fetchProduct( {commit}, id ) {
        const response = await axios.get("/api/product/" + id);
        commit('setProduct', response.data)
    }
}

const mutations = {
    setProduct: (state, product) => (state.product = product)
}

export default {
    state,
    getters,
    actions,
    mutations
}
