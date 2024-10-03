export default {
    state: {
        user: JSON.parse(localStorage.getItem("userdata")) || null,
        cdUsers: JSON.parse(localStorage.getItem("userdata")) || null,
        changedUserInfo: JSON.parse(localStorage.getItem('userdata')) || null
    },
    getters: {
        user: s => s.user,
        cdUsers: s => s.cdUsers,
        changedUserInfo: (s) => s.changedUserInfo,
    },
    mutations: {

        saveUser(state, user) {
            state.user = user;
        },

        saveCdUser(state, CdUser) {
            state.cdUsers = CdUser;
        },

        saveChangedUserInfo(state, data) {
            state.changedUserInfo = data;
        }
    },
    actions: {},
    modules: {}
}

