export default {
    state: {
        update_package_color: [],
    },
    getters: {
        update_package_color: s => s.update_package_color,
    },
    mutations: {
        saveUpdatePackageColor(state, update_package_color) {
            state.update_package_color = update_package_color;
        }
    },
    actions: {

        appendUpdatePackageColor({ commit, state }, data) {
            const states = state.update_package_color;
            const index = states.findIndex(s => s.id === data.id);
            if (index !== -1) {
                states[index] = data;
                commit('saveUpdatePackageColor', states);
            } else {
                states.push(data);
            }
        },

        deleteUpdatePackageColor({ commit, state }, data) {
            const states = state.update_package_color;
            const index = states.findIndex(s => s.id === data.id);
            if (index !== -1) {
                states.splice(index, 1);
                commit('saveUpdatePackageColor', states);
            }
        }
    },
    modules: {}
}
