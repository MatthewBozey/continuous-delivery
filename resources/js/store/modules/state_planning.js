export default {
    state: {
        state_planning: [],
    },
    getters: {
        state_planning: s => s.state_planning,
    },
    mutations: {
        saveStatePlanning(state, state_planning) {
            state.state_planning = state_planning;
        }
    },
    actions: {

        appendState({ commit, state }, data) {
            const states = state.state_planning;
            const index = states.findIndex(s => s.server_status_id === data.server_status_id);
            if (index !== -1) {
                states[index] = data;
                commit('saveStatePlanning', states);
            } else {
                states.push(data);
            }
        },

        deleteStatePlanning({ commit, state }, data) {
            const states = state.state_planning;
            const index = states.findIndex(s => s.server_status_id === data.server_status_id);
            if (index !== -1) {
                states.splice(index, 1);
                commit('saveStatePlanning', states);
            }
        }
    },
    modules: {}
}
