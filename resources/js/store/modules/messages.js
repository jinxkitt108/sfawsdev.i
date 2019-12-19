import axios from "axios";

const state = {
    messages: [],
    participants: [],
};

const getters = {
    allParticipants: (state) => state.participants,
    userAvatar: (state) => state.participants.map(({ imageUrl }) => imageUrl).toString(),
    userName: (state) => state.participants.map(({ name }) => name).toString(),
};

const actions = {
    async fetchMessages({ commit }) {
        const response = await axios.get('api/chat');
    },
    directChat({ commit }, user) {
        state.participants = [];
        commit('setParticipants', user);
    }
};

const mutations = {
    setParticipants: (state, user) => (state.participants.push({"id": user.id, "name": user.name, "imageUrl": 'storage/profile_photo/' + user.profile.photo}))
};

export default {
    state,
    getters,
    actions,
    mutations
};
