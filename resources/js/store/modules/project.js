export default {
    state: {
        project: [],
        selected_project: null,
    },
    getters: {
        project: s => s.project,
        selected_project: s => s.selected_project
    },
    mutations: {

        saveProject(state, project) {
            state.project = project;
        },

        saveSelectedProject(state, project) {
            state.selected_project = project
        }

    },
    actions: {
        appendProject({ commit, state }, data) {
            const states = state.project;
            const index = states.findIndex(s => s.project_id === data.project_id);
            if (index !== -1) {
                states[index] = data;
                commit('saveProject', states);
            } else {
                states.push(data);
            }
        },

        deleteProject({ commit, state }, data) {
            const states = state.project;
            const index = states.findIndex(s => s.project_id === data.project_id);
            if (index !== -1) {
                states.splice(index, 1);
                commit('saveProject', states);
            }
        }

    },
    modules: {}
}

