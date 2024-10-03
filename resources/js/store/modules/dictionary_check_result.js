export default {
    state: {
        dictionaryCheckResult: [],
    },
    getters: {
        dictionaryCheckResult: s => s.dictionaryCheckResult,
    },
    mutations: {
        saveDictionaryCheckResult(state, dictionaryCheckResult) {
            state.dictionaryCheckResult = dictionaryCheckResult;
        }
    },
    actions: {
        appendDictionaryCheckResult({commit, state}, data) {
            const states = state.dictionaryCheckResult;
            const index = states.findIndex(s => s.id === data.id);
            if (index !== -1) {
                states[index] = data;
                commit('saveDictionaryCheckResult', states);
            } else {
                states.push(data);
            }
        },

        deleteDictionaryCheckResult({commit, state}, data) {
            const states = state.dictionaryCheckResult;
            const index = states.findIndex(s => s.id === data.id);
            if (index !== -1) {
                states.splice(index, 1);
                commit('saveDictionaryCheckResult', states);
            }
        }

    },
    modules: {}
}

