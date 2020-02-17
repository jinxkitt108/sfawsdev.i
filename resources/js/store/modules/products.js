import axios from "axios";

const state = {
    product: [],
    allProducts: [],
    latestProducts: [],
    latestVegetables: [],
    latestFruits: []

};

const getters = {
    getProduct: state => state.product,
    getAllProducts: state => state.allProducts,
    getLatestProducts: state => state.latestProducts,
    getLatestVegetables: state => state.latestVegetables,
    getLatestFruits: state => state.latestFruits
};

const actions = {
    async fetchProduct({ commit }, id) {
        const response = await axios.get("/api/product/" + id);
        commit("setProduct", response.data);
    },

    async fetchAllProducts({ commit }) {
        const response = await axios.get("api/allProducts");
        commit("setAllProducts", response.data);
    },

    async fetchLatestProducts({ commit }) {
        const response = await axios.get("api/latestProducts");
        commit("setLatestProducts", response.data);
    },

    async fetchLatestVegetables({ commit }) {
        const response = await axios.get("api/vegetables");
        commit("setLatestVegetables", response.data);
    },

    async fetchLatestFruits({ commit }) {
        const response = await axios.get("api/fruits");
        commit("setLatestFruits", response.data);
    }
};

const mutations = {
    setProduct: (state, product) => (state.product = product),
    setAllProducts: (state, products) => (state.allProducts = products),
    setLatestProducts: (state, products) => (state.latestProducts = products),
    setLatestVegetables: (state, products) => (state.latestVegetables = products),
    setLatestFruits: (state, products) => (state.latestFruits = products)
};

export default {
    state,
    getters,
    actions,
    mutations
};
