export default {
    state: {
        error: [],
    },
    getters: {
        error: s => s.error,
    },
    mutations: {
        saveError(state, error) {
            state.error = error;
        }
    },
    actions: {},
    modules: {}
}