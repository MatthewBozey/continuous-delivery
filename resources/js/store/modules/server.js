export default {
    state: {
        server: [],
    },
    getters: {
        server: s => s.server,
    },
    mutations: {
        saveServer(state, server) {
            state.server = server;
        }
    },
    actions: {
        appendServer({ commit, state }, data) {
            const states = state.server;
            const index = states.findIndex(s => s.server_id === data.server_id);
            if (index !== -1) {
                states[index] = data;
                commit('saveServer', states);
            } else {
                states.push(data);
            }
        },

        deleteServer({ commit, state }, data) {
            const states = state.server;
            const index = states.findIndex(s => s.server_id === data.server_id);
            if (index !== -1) {
                states.splice(index, 1);
                commit('saveServer', states);
            }
        }

    },
    modules: {}
}

