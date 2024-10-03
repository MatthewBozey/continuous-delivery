<template>
  <Toast/>
  <div
      class="flex align-items-center justify-content-center overflow-hidden p-4 min-h-screen min-w-screen"
  >
    <div class="col-12 xl:col-5 shadow-4 border-round-3xl hover:shadow-8">
      <div class="justify-end flex">
        <Button severity="contrast" icon="pi pi-sun" @click="darkThemeHandle"/>
      </div>
      <div class="col-12 xl:mt-0 text-center h-auto overflow-hidden">
        <LoginLogotype/>
            </div>

      <div class="w-full md:w-10 mx-auto p-2">
        <FloatLabel class="my-2">
          <InputText
              id="email"
              v-model="userData.username"
              :disabled="userData.loading"
              class="w-full"
              @keyup.enter="login"
          />
          <label for="email" class="text-h6">Логин</label>
        </FloatLabel>


        <FloatLabel class="my-2">
          <Password
              id="password"
              :disabled="userData.loading"
              v-model="userData.password"
              class="w-full"
              input-class="w-full"
              toogle-mask
              :feedback="false"
              @keyup.enter="login"
          />
          <label for="password" class="text-h6">Пароль</label>
        </FloatLabel>


        <div
            class="flex align-items-center justify-content-between mb-5"
        >
          <a
              class="font-medium no-underline ml-2 text-right cursor-pointer"
              v-show="!userData.loading"
              style="color: var(--p-primary-color)"
              @click="$router.push('/proof-identity')"
          >Неправильный пароль?</a
          >
                </div>
        <Button
            label="Войти"
            :disabled="blockButton"
            :loading="userData.loading"
            class="w-full p-3 mb-4 text-xl"
            @click="login"
        ></Button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import LoginLogotype from "../components/svg/LoginLogotype";

export default {
  name: "Login",
    data() {
        return {
            userData: {
                username: null,
                password: null,
              loading: false,
            },
            theme: "saga-blue",
          layoutMode: "static",
        };
    },
    computed: {
        blockButton() {
            return !this.userData.username || !this.userData.password;
        },
    },
  mounted() {
  },
    created() {
        this.loadTheme();
    },
    methods: {
        login() {
          if (
              this.userData.username &&
              this.userData.password &&
              !this.userData.loading
          ) {
                this.userData.loading = true;
            axios
                .post("/api/auth/login", this.userData)
                .then((result) => {
                  localStorage.setItem(
                      "access_token",
                      result?.data?.access_token
                  );
                  localStorage.setItem(
                      "userdata",
                      JSON.stringify(result.data.user)
                  );
                  localStorage.setItem(
                      "permissions",
                      JSON.stringify(result.data.permissions) || []
                  );
                  localStorage.setItem(
                      "user_id",
                      result.data?.user?.user_id
                  );

                  // this.$store.commit("saveUser", result?.data?.user);
                  if (localStorage.getItem("auth_route")) {
                    this.$router.push({
                      path: localStorage.getItem("auth_route"),
                    });
                    localStorage.removeItem("auth_route");
                  } else {
                    this.$router.push("/");
                  }
                })
                .catch((error) => {
                  console.error(error.response.data.error);
                  this.$notification(
                      error?.response?.data?.message || error?.message,
                      {type: "error", timeout: 15000}
                  );
                  this.userData.password = "";
                })
                .finally(() => {
                  this.userData.loading = false;
                });
            }
        },
        onLayoutChange(layoutMode) {
            this.layoutMode = layoutMode;
        },
        loadTheme() {
          const darkModeValue = localStorage.getItem("dark-mode") === "true";
          const root = document.querySelector("html");
          if (darkModeValue) {
            root.classList.add("app-dark");
          }
        },
      darkThemeHandle() {
        const darkModeValue = localStorage.getItem("dark-mode") === "true";
        localStorage.setItem("dark-mode", !darkModeValue);
        const root = document.querySelector("html");
        if (!darkModeValue) {
          root.classList.add("app-dark");
        } else {
          root.classList.remove("app-dark");
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
            this.activeMenuIndex = null;
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
  components: {LoginLogotype},
};
</script>

<style scoped></style>
