export default {
    state: {
        password: null,
        login: null
    },
    actions: {
        setInfo: ({ commit}, val) => {
            commit('SET_INFO', val)
        },
        checkAuthentication({ commit } ) {
            let result;
            const storageTM = localStorage.kron_tm ? JSON.parse(localStorage.kron_tm):{};
            if (storageTM?.session) {
                if (storageTM?.login && storageTM?.password) {
                    const formData = {
                        login: storageTM.login,
                        password: storageTM.password,
                        session: true
                    };
                    commit("SET_INFO", formData);
                    result = true;
                } else
                    result = false;
            }  else
                result = false;
            return result;
        },

    },
    mutations: {
        SET_INFO(state, formData) {
            state.password = formData.password;
            state.login = formData.login
            localStorage.setItem('kron_tm', JSON.stringify(formData));
        },
    },
    getters: {
        password: (s, g) => g.decrypted(s.password),
        login: (s) => s.login,
        crypted: () => (value) => {
            let crypted = value.split('').map((value, index) => value.charCodeAt(0) ^ (index + index - 1));
            return crypted;
        },
        decrypted: () => (array) => {
            if (!array)
                return;
            if (typeof array === 'string')
                array = array.split(',');
            if (array.length < 2)
                return;
            const result = array.map((value, index) => value ^ (index + index - 1)).map(value => String.fromCharCode(value)).join('');
            return result;
        },
    },
}
