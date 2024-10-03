export default {
    state: {
        projectLog: [],
        hiddenProjectLog: [],
        projectLogColumns: [],
        projectLogSelectedColumns: []
    },
    getters: {
        projectLog: state => state.projectLog,
        hiddenProjectLog: state => state.hiddenProjectLog,
        projectLogColumns: state => state.projectLogColumns,
        projectLogSelectedColumns: state => state.projectLogSelectedColumns,
        projectLogById: state => id => {
            return state.projectLog.find(log => log.project_log_id === id);
        }
    },
    mutations: {

        ADD_PROJECT_LOG: (state, projectLog) => {
            state.projectLog.push(projectLog);
        },

        DELETE_PROJECT_LOG: (state, id) => {
            state.projectLog = state.projectLog.filter(log => log.id !== id);
        },

        UPDATE_PROJECT_LOG: (state, updatedProjectLog) => {
            const index = state.projectLog.findIndex(log => log.project_log_id === updatedProjectLog.id);
            if (index !== -1) {
                state.projectLog.splice(index, 1, updatedProjectLog);
            }
        },

        SAVE_PROJECT_LOG: (state, projectLog) => {
            state.projectLog = projectLog;
        },

        SAVE_HIDDEN_PROJECT_LOG: (state, hiddenProjectLog) => {
            state.hiddenProjectLog = hiddenProjectLog;
        },

        SAVE_PROJECT_LOG_COLUMNS: (state, projectLogColumns) => {
            state.projectLogColumns = projectLogColumns;
        },

        SAVE_PROJECT_LOG_SELECTED_COLUMNS: (state, projectLogColumns) => {
            state.projectLogSelectedColumns = projectLogColumns;
        }
    },
    actions: {
        addProjectLog({ commit }, projectLog) {
            commit('ADD_PROJECT_LOG', projectLog);
        },

        deleteProjectLog({ commit }, id) {
            commit('DELETE_PROJECT_LOG', id);
        },

        updateProjectLog({ commit }, updatedProjectLog) {
            commit('UPDATE_PROJECT_LOG', updatedProjectLog);
        },

        saveProjectLog({ commit }, projectLog) {
            commit('SAVE_PROJECT_LOG', projectLog);
        },

        saveHiddenProjectLog({ commit }, hiddenProjectLog) {
            commit('SAVE_HIDDEN_PROJECT_LOG', hiddenProjectLog);
        },

        saveProjectLogColumns({ commit }, projectLogColumns) {
            commit('SAVE_PROJECT_LOG_COLUMNS', projectLogColumns)
        },

        saveProjectLogSelectedColumns({ commit }, projectLogSelectedColumns) {
            commit('SAVE_PROJECT_LOG_SELECTED_COLUMNS', projectLogSelectedColumns)
        }
    },
    modules: {}
}

