import axios from "axios";

const state = {
    viewPost: [],
    allPosts: []
};

const getters = {
    getViewPost: state => state.viewPost,
    getAllPosts: state => state.allPosts
};

const actions = {
    async fetchViewPost({ commit }, id) {
        const response = await axios.get("api/viewpost/" + id);
        commit("setViewPost", response.data);
    },

    async fetchAllPosts({ commit }) {
        const response = await axios.get("api/post");
        commit("setAllPosts", response.data);
    }
};

const mutations = {
    setViewPost: (state, post) => (state.viewPost = post),
    setAllPosts: (state, posts) => (state.allPosts = posts)
};

export default {
    state,
    getters,
    actions,
    mutations
};
