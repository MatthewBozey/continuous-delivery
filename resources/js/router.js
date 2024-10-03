import {createRouter, createWebHashHistory} from 'vue-router';
import App from './App.vue';

const routes = [
    {
        path: '/',
        name: 'app',
        component: App,
        meta: {hasAuth: true},
        children: [
            {
                path: '/kron-tm',
                name: 'KronTM',
                meta: {hasAuth: true},
                component: () => import('./pages/kron-tm/KronTM'),
                children: [
                    {
                        path: '/kron-tm/users',
                        label: 'Пользователи',
                        icon: 'pi pi-user',
                        name: 'UsersTM',
                        meta: {hasAuth: true},
                        component: () => import('./pages/kron-tm/Users')
                    },
                    {
                        path: '/kron-tm/servers',
                        label: 'Сервера',
                        icon: 'pi pi-server',
                        name: 'ServersTM',
                        meta: {hasAuth: true},
                        component: () => import('./pages/kron-tm/Servers')
                    },
                    {
                        path: '/kron-tm/info-servers',
                        label: 'Информация о сервере',
                        icon: 'pi pi-pound',
                        name: 'InfoServersTM',
                        meta: {hasAuth: true},
                        component: () => import('./pages/kron-tm/InfoServers.vue')
                    },

                ],
            },
            {
                path: '/dashboard',
                name: 'dashboardView',
                alias: '/',
                meta: {hasAuth: true, permission: ['users list']},
                component: () => import('./views/Dashboard'),
            },
            {
                path: '/access-denied',
                name: 'AccessDenied',
                meta: {hasAuth: false},
                component: () => import('./pages/Access'),
            },
            {
                path: '/profile',
                name: 'Profile',
                meta: {
                    hasAuth: true
                },
                component: () => import('./pages/Profile'),
            },
            {
                path: "/setting",
                name: "Setting",
                meta: {
                    hasAuth: true
                },
                component: () => import("./pages/Setting")
            },
            {
                path: "/users",
                name: "Users",
                meta: {
                    hasAuth: true,
                    permission: ["users list"]
                },
                component: () => import("./pages/User"),
                children: []
            },
            {
                path: "/state-plannings",
                name: "StatePlannings",
                meta: {
                    hasAuth: true,
                    permission: ["state-planning list"]
                },
                component: () => import("./pages/StatePlanning.vue"),
                children: []
            },
            {
                path: "/projects",
                name: "Projects",
                meta: {
                    hasAuth: true,
                    permission: ["project list"]
                },
                component: () => import("./pages/Project.vue"),
                children: []
            },
            {
                path: "/servers",
                name: "Servers",
                meta: {
                    hasAuth: true,
                    permission: ["server list"]
                },
                component: () => import("./pages/Server.vue"),
                children: []
            },
            {
                path: "/users-row",
                name: "UsersRow",
                meta: {
                    hasAuth: true,
                    permission: ["users edit"]
                },
                component: () => import("./pages/UserRow"),
                children: [
                    {
                        path: ":user_id",
                        name: "UsersRowItem",
                        meta: {
                            hasAuth: true,
                            permission: ["users edit"]
                        },
                        component: () => import("./pages/UserRow")
                    },
                ]
            },
            {
                path: "/roles",
                name: "Roles",
                meta: {
                    hasAuth: true,
                    permission: ["roles list"]
                },
                component: () => import("./pages/Roles"),
                children: []
            },
            {
                path: "/update-package",
                name: "UpdatePackage",
                meta: {
                    hasAuth: true,
                    permission: ["update-package list"]
                },
                component: () => import("./pages/UpdatePackage"),
                children: []
            },
            {
                path: "/moment-update-package",
                name: "MomentUpdatePackage",
                meta: {
                    hasAuth: true,
                    permission: ["update-package list"]
                },
                component: () => import("./pages/UpdatePackageResult"),
                children: []
            },
            {
                path: "/update-package-row",
                name: "UpdatePackageRow",
                meta: {
                    hasAuth: true,
                    permission: ["update-package show"]
                },
                component: () => import("./pages/UpdatePackageRow"),
                children: [
                    {
                        path: ":update_package_id",
                        name: "UpdatePackageRowId",
                        meta: {
                            hasAuth: true,
                            permission: ["update-package edit"]
                        },
                        component: () => import("./pages/UpdatePackageRow"),
                        children: []
                    },
                ]
            },
            {
                path: "/update-script-row",
                name: "UpdateScriptRow",
                meta: {
                    hasAuth: true,
                    permission: ["update-script show"]
                },
                component: () => import("./pages/UpdateScriptRow"),
                children: [
                    {
                        path: ":update_script_id",
                        name: "UpdateScriptRowId",
                        meta: {
                            hasAuth: true,
                            permission: ["update-script edit"]
                        },
                        component: () => import("./pages/UpdateScriptRow"),
                        children: []
                    },
                ]
            },
            {
                path: '/update-package-color',
                name: 'UpdatePackageColor',
                meta: {hasAuth: true, permission: ["update_package_color list"]},
                component: () => import('./pages/UpdatePackageColor'),
            }
        ],
    },
    {
        path: '/login',
        name: 'login',
        meta: {hasAuth: false},
        component: () => import('./pages/Login.vue'),
    },
    {
        path: '/reset-password',
        name: 'resetPassword',
        meta: {hasAuth: false},
        component: () => import('./pages/ResetPasswordPage'),
        children: [
            {
                path: '/proof-identity',
                name: 'ProofIdentity',
                meta: {hasAuth: false},
                component: () => import('./pages/ResetPassword/ProofIdentity'),
            },
            {
                path: '/send-code',
                name: 'SendCode',
                meta: {hasAuth: false},
                component: () => import('./pages/ResetPassword/SendCode'),
            },
            {
                path: '/validate-code',
                name: 'ValidateCode',
                meta: {hasAuth: false},
                component: () => import('./pages/ResetPassword/ValidateCode'),
            },
            {
                path: '/change-password',
                name: 'ChangePassword',
                meta: {hasAuth: false},
                component: () => import('./pages/ResetPassword/ChangePassword'),
            },
        ],
    },
    {
        path: '/project-log',
        name: 'ProjectLog',
        meta: {hasAuth: true},
        component: () => import('./pages/ProjectLog'),
    },
    {
        path: '/moment-update-package',
        name: 'MomentUpdatePackage',
        meta: {hasAuth: true},
        component: () => import('./pages/MomentUpdatePackage'),
    },


];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    function checkPermission(allPerm, perm) {
        let result = false;
        for (let index = 0; index < perm.length; index++) {
            result = (allPerm.includes(perm[index]) || result);
        }
        return result
    }

    // await store.restored;
    if (to.matched.some(record => record.meta.hasAuth)) {
        const token = localStorage.getItem('access_token');
        if (token) {
            if (to.matched.some(record => record.meta.permission)) {
                let permission = JSON.parse(localStorage.getItem('permissions'));
                if (!permission || !checkPermission(permission, to.meta.permission)) {
                    router.push('/access-denied');
                } else {
                    next();
                }
            } else {
                next();
            }
        } else {
            // window.localStorage.setItem("auth_route", to.fullPath);
            router.push({name: 'login'});
        }
    } else {
        next();
    }
});

export default router;
