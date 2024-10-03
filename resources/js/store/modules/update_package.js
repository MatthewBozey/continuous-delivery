export default {
    state: {
        update_package: [],
        update_package_filter: {
            update_package_id: null,
            author_name: null,
            version: null,
            project_id: null,
            package_type_id: null,
            package_create_date: null,
            hide_is_done: false,
        },
        selected_packages: [],
        selected_scripts: [],
        project: [],
        packageType: [],
        server: [],
        script_type: [],
        overwriting_servers: false,
        filtered_packages: [],
        user_list: [],
    },
    getters: {
        filtered_packages: (s) => s.filtered_packages,
        overwriting_servers: (s) => s.overwriting_servers,
        package_type: (s) => s.packageType,
        script_type: (s) => s.script_type,
        selected_packages: (s) => s.selected_packages,
        selected_scripts: (s) => s.selected_scripts,
        update_package: (s) => s.update_package,
        update_package_filter: (s) => s.update_package_filter,
        user_list: (s) => s.user_list,
    },
    mutations: {
        saveUpdatePackage(state, updatePackage) {
            state.update_package = updatePackage;
        },

        saveSelectedPackage(state, selectedPackage) {
            state.selected_packages = selectedPackage;
        },

        saveSelectedScripts(state, selectedPackage) {
            state.selected_scripts = selectedPackage;
        },

        saveScriptType(state, scriptType) {
            state.script_type = scriptType;
        },

        saveProject(state, project) {
            state.project = project;
        },

        savePackageType(state, package_type) {
            state.packageType = package_type;
        },

        saveServer(state, server) {
            state.server = server;
        },

        deleteSelectedUpdatePackage(state, index) {
            state.selected_packages.splice(index, 1);
        },

        trashSelectedUpdatePackage(state) {
            state.selected_packages = [];
        },

        updateUpdatePackage(state, data) {
            const index = state.update_package.findIndex(
                (p) => p.update_package_id === data.update_package_id
            );
            if (index !== -1) {
                state.update_package.splice(index, 1, data);
            } else {
                const data = state.update_package[index];
                if (data) {
                    state.update_package.unshift(data);
                }
            }
        },

        addUpdatePackage(state, data) {
            state.update_package.unshift(data);
        },

        deleteUpdatePackage(state, updatePackageId) {
            const index = state.update_package.findIndex(
                (p) => p.update_package_id === updatePackageId
            );
            if (index !== -1) {
                state.update_package.splice(index, 1);
            } else {
                const data = state.update_package[index];
                if (data) {
                    state.update_package.unshift(data);
                }
            }
        },

        deleteUpdateScript(state, updateScriptId) {
            const index = state.selected_scripts.findIndex(
                (p) => p.update_script_id === updateScriptId
            );
            if (index !== -1) {
                state.selected_scripts.splice(index, 1);
            }
        },

        setOverwritingServers(state, value) {
            state.overwriting_servers = value;
        },

        saveFilteredPackage(state, value) {
            state.update_package_filter = value;
        },

        updateFilteredPackage(state, data) {
            const index = state.filtered_packages.findIndex(
                (p) => p.update_package_id === data.update_package_id
            );
            if (index !== -1) {
                state.filtered_packages.splice(index, 1, data);
            } else {
                if (data) {
                    state.filtered_packages.unshift(data);
                }
            }
        },

        deleteFilteredPackage(state, updatePackageId) {
            const index = state.filtered.findIndex(
                (p) => p.update_package_id === updatePackageId
            );
            if (index !== -1) {
                state.filtered.splice(index, 1);
            } else {
                const data = state.filtered_packages[index];
                if (data) {
                    state.filtered_packages.unshift(data);
                }
            }
        },

        addUpdatePackageFilter(state, data) {
            const index = state.update_package_filter.findIndex();
        },

        saveUserList(state, data) {
            state.user_list = data;
        },
    },
    actions: {
        unSelectUpdatePackage(state, data) {
            state.commit("saveSelectedPackage", data);
            if (data?.update_package_id) {
                const index = state.getters.selected_packages.findIndex(
                    (s) => s.update_package_id === data.update_package_id
                );
                if (index !== -1) {
                    state.commit(
                        "saveSelectedPackage",
                        state.getters.selected_packages.splice(index, 1)
                    );
                }
            }
        },

        deleteSelectedPackagesByUpdatePackageId({ commit, state }, id) {
            const index = state.selected_packages.findIndex(
                (obj) => obj.update_package_id === id
            );
            if (index !== -1) {
                commit("deleteSelectedUpdatePackage", index);
            }
        },
    },
    modules: {},
};

