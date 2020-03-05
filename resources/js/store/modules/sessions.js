import axios from "axios";

const state = {
    time_slots: [],
    allSessions: []
};

const getters = {
    getTimeSlots: state => state.time_slots
    // getViewPost: state => state.viewPost,
};

const actions = {
    async addTimeSlot({ commit }, time_slot) {
        const response = await axios.post("api/time_slot", time_slot);
        commit("newTimeSlot", response.data);
    },

    async fetchTimeSlots({ commit }) {
        const response = await axios.get("api/time_slot");
        commit("setTimeSlots", response.data);
    },

    async deleteTimeSlot({ commit }, id) {
        const response = await axios.delete("api/time_slot/" + id);
        commit("removeTimeSlot", id);
    },

    async addSession({ commit }, session) {
        const response = await axios.post("api/session", session);
        commit("newSession", response.data);
    },
};

const mutations = {
    setTimeSlots: (state, time_slots) => (state.time_slots = time_slots),
    newTimeSlot: (state, time_slot) => state.time_slots.unshift(time_slot),
    removeTimeSlot: (state, id) => (state.time_slots = state.time_slots.filter(slot => slot.id !== id)),
    newSession: (state, session) => state.allSessions.unshift(session),
};

export default {
    state,
    getters,
    actions,
    mutations
};
