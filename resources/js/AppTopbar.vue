<template>
    <div class="layout-topbar">
        <router-link to="/" class="layout-topbar-logo">
            <DevopsLogotype class="h-4rem w-full" />
        </router-link>
        <button
            class="p-link layout-menu-button layout-topbar-button"
            @click="onMenuToggle"
        >
            <i class="pi pi-bars"></i>
        </button>

        <button
            class="p-link layout-topbar-menu-button layout-topbar-button"
            v-styleclass="{
                selector: '@next',
                enterClass: 'hidden',
                enterActiveClass: 'scalein',
                leaveToClass: 'hidden',
                leaveActiveClass: 'fadeout',
                hideOnOutsideClick: true,
            }"
        >
            <i class="pi pi-ellipsis-v"></i>
        </button>
        <ul class="layout-topbar-menu hidden lg:flex origin-top">
            <li>
                <button class="p-link layout-topbar-button">
                    <i class="pi pi-calendar"></i> <span>Events</span>
                </button>
            </li>
            <li>
                <button
                    class="p-link layout-topbar-button"
                    @click="$router.push('profile')"
                >
                    <i class="pi pi-user"></i> <span>Профиль</span>
                </button>
            </li>
            <li>
                <button
                    class="p-link layout-topbar-button"
                    @click="darkModeHandle"
                >
                    <i class="pi pi-sun"></i> <span>Профиль</span>
                </button>
            </li>
            <li>
                <button class="p-link layout-topbar-button" @click="logout">
                    <i class="pi pi-sign-out"></i> <span>Выйти из системы</span>
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
import devops_api from "./service/devops_api";
import Access from "./pages/Profile";
import DevopsLogotype from "./components/svg/DevopsLogotype";

export default {
    methods: {
        onMenuToggle(event) {
            this.$emit("menu-toggle", event);
        },
        onTopbarMenuToggle(event) {
            this.$emit("topbar-menu-toggle", event);
        },
        topbarImage() {
            return "images/logo.png";
        },
        logout() {
            devops_api.post("/api/auth/logout").then((result) => {
                if (result.status === 200) {
                    localStorage.clear();
                    this.$router.push("/login");
                }
            });
        },
        openTest() {
            this.$dialog.open(Access, {
                props: {
                    header: "Окно просмотра профиля",
                    style: {
                        width: "85vw",
                    },
                },
            });
        },

        darkModeHandle() {
            const darkModeValue = localStorage.getItem("dark-mode") === "true";
            localStorage.setItem("dark-mode", !darkModeValue);
            const root = document.querySelector("html");
            if (!darkModeValue) {
                root.classList.add("app-dark");
            } else {
                root.classList.remove("app-dark");
            }
        },
    },
    computed: {
        darkTheme() {
            return this.$appState.darkTheme;
        },
    },
    components: {
        DevopsLogotype,
    },
};
</script>
