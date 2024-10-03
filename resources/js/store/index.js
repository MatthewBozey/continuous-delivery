import {createStore} from 'vuex';
import user from './modules/user';
import role from "./modules/role";
import error from "./modules/errors";
import toasting from "./modules/tostification";
import update_package from "./modules/update_package";
import state_planning from './modules/state_planning';
import project from './modules/project';
import createMultiTabState from 'vuex-multi-tab-state';
import server from "./modules/server";
import project_log from './modules/project_log';
import update_package_color from "./modules/update_package_color";
import kron_tm from "./modules/kron_tm";

export default createStore({
    state: {
        defaultBodyBackground: null,
        loading: false,
        secondary_loading: false
    },
    getters: {
        defaultBodyBackground: (state) => (state.defaultBodyBackground),
        loading: (state) => (state.loading),
        secondary_loading: (state) => (state.secondary_loading)
    },
    mutations: {
        saveDefaultBodyBackground: (state, val) => {
            state.defaultBodyBackground = val;
        },

        SET_LOADING: (state, value) => {
            state.loading = value;
        },

        SET_SECONDARY_LOADING: (state, value) => {
            state.secondary_loading = value;
        }
    },
    actions: {
        setDefaultBodyBackground: ({commit}, val) => {
            commit('saveDefaultBodyBackground', val);
        },

        getDefaultBackgroundColor: ({commit}) => {
            const bodyStyles = window.getComputedStyle(document.body);
            const hex = bodyStyles.getPropertyValue('--surface-a');
            const hexToRgba = (hex, opacity = 1) => {
                let c;
                if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                    c = hex.substring(1).split('');
                    if (c.length === 3) {
                        c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                    }
                    c = '0x' + c.join('');
                    return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ', ' + opacity + ')';
                }
                throw new Error('Bad Hex');
            }
            const RGBAToHexA = (rgba, forceRemoveAlpha = false) => {
                return "#" + rgba.replace(/^rgba?\(|\s+|\)$/g, '')
                    .split(',')
                    .filter((string, index) => !forceRemoveAlpha || index !== 3)
                    .map(string => parseFloat(string))
                    .map((number, index) => index === 3 ? Math.round(number * 255) : number)
                    .map(number => number.toString(16))
                    .map(string => string.length === 1 ? "0" + string : string)
                    .join("")
            }
            if (hex) {
                const rgbaHex = hexToRgba(hex, 0.3);
                const hexOutput = RGBAToHexA(rgbaHex);
                commit('saveDefaultBodyBackground', hexOutput);
            }

        },

    },
    modules: {
        user,
        role,
        error,
        toasting,
        update_package,
        state_planning,
        project,
        server,
        project_log,
        update_package_color,
        kron_tm
    },
    plugins: [createMultiTabState()]
})
