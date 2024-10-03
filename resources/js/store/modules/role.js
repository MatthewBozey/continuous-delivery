export default {
    state: {
        role: [],
    },
    getters: {
        role: s => s.role,
    },
    mutations: {
        saveRole(state, role) {
            state.role = role;
        }
    },
    actions: {},
    modules: {}
}

