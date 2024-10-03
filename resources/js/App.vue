<template>
    <DynamicDialog />
    <ConfirmPopup></ConfirmPopup>
    <Toast />
    <div :class="containerClass" @click="onWrapperClick">
        <AppTopBar @menu-toggle="onMenuToggle" />
        <div class="layout-sidebar" @click="onSidebarClick">
            <AppMenu :model="menu" @menuitem-click="onMenuItemClick" />
        </div>

        <div class="layout-main-container">
            <div class="layout-main">
                <router-view />
            </div>
            <AppFooter />
        </div>

        <transition name="layout-mask">
            <div
                class="layout-mask p-component-overlay"
                v-if="mobileMenuActive"
            ></div>
        </transition>
    </div>
</template>

<script>
import AppTopBar from "./AppTopbar.vue";
import AppMenu from "./AppMenu.vue";
import AppConfig from "./AppConfig.vue";
import AppFooter from "./AppFooter.vue";
import { mapActions } from "vuex";

export default {
    emits: ["change-theme"],
    data() {
        return {
            layoutMode: "static",
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu: [],
            theme: "bootstrap4-light-blue",
        };
    },
    watch: {
        $route() {
            this.menuActive = false;
            this.$toast.removeAllGroups();
        },
    },
    setup() {},
    methods: {
        ...mapActions(["getDefaultBackgroundColor"]),
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === "overlay") {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }

                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === "static") {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }

            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        onLayoutChange(layoutMode) {
            localStorage.layout = layoutMode;
            this.layoutMode = layoutMode;
        },
        addClass(element, className) {
            if (element.classList) element.classList.add(className);
            else element.className += " " + className;
        },
        removeClass(element, className) {
            if (element.classList) element.classList.remove(className);
            else
                element.className = element.className.replace(
                    new RegExp(
                        "(^|\\b)" + className.split(" ").join("|") + "(\\b|$)",
                        "gi"
                    ),
                    " "
                );
        },
        isDesktop() {
            return window.innerWidth >= 992;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === "static")
                    return !this.staticMenuInactive;
                else if (this.layoutMode === "overlay")
                    return this.overlayMenuActive;
            }

            return true;
        },
        loadTheme() {
            const darkModeValue = localStorage.getItem("dark-mode") === "true";
            const root = document.querySelector("html");
            if (darkModeValue) {
                root.classList.add("app-dark");
            }
        },
        loadLayout() {
            if (
                localStorage.layout &&
                localStorage.layout !== this.layoutMode
            ) {
                this.onLayoutChange(localStorage.layout);
            }
        },
        changeTheme(event) {
            let themeElement = document.getElementById("theme-link");
            themeElement.setAttribute(
                "href",
                themeElement
                    .getAttribute("href")
                    .replace(this.theme, event.theme)
            );
            this.theme = event.theme;
            // EventBus.emit("theme-change", event);
            this.$appState.darkTheme = event.dark;
            if (event.theme.startsWith("md")) {
                this.$primevue.config.ripple = true;
            }
            localStorage.theme = this.theme;
            localStorage.ripple = this.$primevue.config.ripple;
            if (event.dark === undefined) localStorage.removeItem("dark");
            else localStorage.dark = event.dark;
        },
    },
    created() {
        this.loadTheme();
        this.loadLayout();
    },
    computed: {
        containerClass() {
            return [
                "layout-wrapper",
                {
                    "layout-overlay": this.layoutMode === "overlay",
                    "layout-static": this.layoutMode === "static",
                    "layout-static-sidebar-inactive":
                        this.staticMenuInactive && this.layoutMode === "static",
                    "layout-overlay-sidebar-active":
                        this.overlayMenuActive && this.layoutMode === "overlay",
                    "layout-mobile-sidebar-active": this.mobileMenuActive,
                    "p-input-filled":
                        this.$primevue.config.inputStyle === "filled",
                    "p-ripple-disabled": this.$primevue.config.ripple === false,
                },
            ];
        },
        logo() {
            return "images/logo.png";
        },
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, "body-overflow-hidden");
        else this.removeClass(document.body, "body-overflow-hidden");
    },
    components: {
        AppTopBar: AppTopBar,
        AppMenu: AppMenu,
        AppConfig: AppConfig,
        AppFooter: AppFooter,
    },
    mounted() {
        if (localStorage.getItem("user_id")) {
            window.Echo.private(
                `App.Models.User.${localStorage.getItem("user_id")}`
            )
                .listen("updated", (e) => {
                    localStorage.setItem("userdata", JSON.stringify(e.user));
                    localStorage.setItem(
                        "permissions",
                        JSON.stringify(e.user.permissions) || []
                    );
                })
                .listen(".updated", (e) => {
                    localStorage.setItem("userdata", JSON.stringify(e.user));
                    localStorage.setItem(
                        "permissions",
                        JSON.stringify(e.user.permissions) || []
                    );
                })
                .listen(".update-permission", (e) => {
                    console.log(e);
                    localStorage.setItem(
                        "permissions",
                        JSON.stringify(e.permissions) || []
                    );
                })

                .listen(".UserUpdated", (e) => console.log(e));
        }
        this.loadTheme();
    },
};
</script>

<style lang="scss">
@import "./App.scss";
</style>
