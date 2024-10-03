export default {
    state: {
        permission: [],
    },
    getters: {
        error: s => s.permission,
    },
    mutations: {
        savePermission(state, permission) {
            state.error = permission;
        }
    },
    actions: {},
    modules: {}
}
