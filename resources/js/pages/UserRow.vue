<template>
    <div class="card">
        <form @submit.prevent="submitUser">
            <h4 class="text-2xl ">Системные данные</h4>
            <div class="grid p-fluid m-1">
                <div class="field col-12 md:col-4">
                      <span class="p-float-label p-input-icon-left">
                        <i class="pi pi-user"></i>
                        <InputText id="username" type="text" v-model="user.username" @input.prevent="validateUsername"
                                   required/>
                        <label for="username">Логин пользователя</label>
                      </span>
                </div>

                <div class="field col-12 md:col-4">
          <span class="p-float-label">
            <Password v-model="user.password" :class="[{'p-invalid': disablePassword || disableConfirmPassword}]"
                      strong-regex="(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,}"
                      medium-label="Сильный" weak-label="Слабый"
                      autocomplete="off"
                      @input="validatePassword">
                <template #footer>
                    <Divider/>
                    <p class="mt-2">Правила</p>
                    <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                        <li>Хотя бы один знак нижнего регистра</li>
                        <li>Хотя бы один знак верхнего регистра</li>
                        <li>Хотя бы одна цифра</li>
                        <li>Хотя бы один специальный символ</li>
                        <li>Минимум 8 символов</li>
                    </ul>
                </template>
            </Password>
            <label for="password">Пароль пользователя</label>
          </span>
                    <small id="username1-help" class="flex justify-content-center text-red-400" v-if="disablePassword">Пароль
                        не
                        соответствует критериям сложности</small>
                </div>

                <div class="field col-12 md:col-4">
          <span class="p-float-label p-input-icon-left">
            <i class="pi pi-lock"></i>
            <Password v-model="confirmPassword" :class="[{'p-invalid': disableConfirmPassword}]"
                      strong-regex="(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,}">
                <template #footer>
                    <Divider/>
                    <p class="mt-2">Правила</p>
                    <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                        <li>Хотя бы один знак нижнего регистра</li>
                        <li>Хотя бы один знак верхнего регистра</li>
                        <li>Хотя бы одна цифра</li>
                        <li>Хотя бы один специальный символ</li>
                        <li>Минимум 8 символов</li>
                        <li class="text-red-400" v-if="disableConfirmPassword">Пароли не совпадают</li>
                    </ul>
                </template>
            </Password>
            <label for="passwordConfirm">Повторите пароль пользователя</label>
          </span>
                    <small id="username1-help" class="flex justify-content-center text-red-400"
                           v-if="disableConfirmPassword">Пароли
                        не совпадают</small>
                </div>

            </div>


            <h4 class="text-2xl ">Личные данные</h4>
            <div class="grid p-fluid m-1">
                <div class="field col-12 md:col-4">
                  <span class="p-float-label">
                  <InputText id="lastname" type="text" v-model="user.last_name" required/>
                    <label for="lastname">Фамилия Пользователя</label>
                  </span>
                </div>

                <div class="field col-12 md:col-4">
                  <span class="p-float-label ">
                    <InputText id="firstname" type="text" v-model="user.first_name" required/>
                    <label for="firstname">Имя Пользователя</label>
                  </span>
                </div>

                <div class="field col-12 md:col-4">
                  <span class="p-float-label">
                    <InputText id="patronymic" type="text" v-model="user.patronymic" required/>
                    <label for="patronymic">Отчество Пользователя</label>
                  </span>
                </div>

                <div class="field col-12 md:col-4">
                  <span class="p-float-label">
                    <InputText id="email" type="email" v-model="user.email" required/>
                    <label for="email">Электронная почта Пользователя</label>
                  </span>
                </div>

                <div class="field col-12 md:col-4">
                  <span class="p-float-label p-input-icon-left">
                    <i class="pi pi-lock"></i>
                      <MultiSelect id="roles" v-model="user.roles" :options="roles" option-value="name" option-label="title"/>
                    <label for="roles">Роли Пользователя</label>
                  </span>
                </div>

                <div class="field col-12 md:col-4">
                    <span class="p-float-label p-input-icon-left">
                        <MultiSelect v-model="user.permissions" :options="permissions" option-group-label="title" option-group-children="permissions" option-label="title" option-value="name"/>
                        <label for="title">Права пользователя</label>
                    </span>
                </div>

            </div>

            <div class="">
                <Button class="p-button-success p-button-lg m-2 w-full" type="submit" label="Создать пользователя"/>
                <Button class="p-button-danger p-button-lg m-2 w-full" label="Отменить" @click="$router.push('/users')"/>
            </div>
        </form>

    </div>
</template>

<script>

export default {
    name: "UserAdd",
    data() {
        return {
            user: {
                username: null,
                password: null,
                first_name: null,
                last_name: null,
                patronymic: null,
                email: null,
                roles: null,
                permissions: null,
            },
            disablePassword: false,
            confirmPassword: null,
            rolesLoading: false,
            regular: /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,}/,
            regularUsername: /[а-яё!@#$%^&*"'~()[\]/{}<>?,.-]/i,
            permissions: null,
        }
    },
    setup() {
    },
    async mounted() {
        this.rolesLoading = true;
        await this.$devops_api.get("/api/role").then(value => this.$store.commit("saveRole", value.data.data)).catch(error => this.$store.dispatch("showError", error)).finally(() => this.rolesLoading = false)
        await this.$devops_api.get("/api/permission-section").then(value => this.permissions = value.data.data).catch(reason => this.$store.dispatch("showError", reason)).finally(() => this.loading = false)
        if (this.$route.query["method"] === "update") {
            await this.$devops_api.get(`/api/users/${this.$route.params.user_id}`).then(value => {
                this.user = value.data.data;
                this.user.roles = value.data?.data?.roles.map(role => role.name);
                this.user.permissions = value.data?.data?.permissions.map(permission => permission.name);
            }).catch(reason => this.$store.dispatch("showError", reason));
        }
    },
    computed: {
        roles() {
            return this.$store.getters.role;
        },
        disableConfirmPassword() {
            return this.confirmPassword !== this.user.password;
        },
        invalidUsernameField() {
            return !this.regularUsername.test(this.user.username)
        }
    },
    methods: {
        submitUser() {
            if (this.$route.query["method"] === "update") {
                this.$devops_api.put(`/api/users/${this.$route.params.user_id}`, this.user).then((value) => {
                    this.$notification.success("Вы успешно отредактировали данные пользователя");
                    this.$router.push("/users");
                }).catch(error => {
                    this.$store.dispatch("showError", error);
                })
            } else {
                this.$devops_api.post("/api/users", this.user).then((value) => {
                    this.$store.dispatch("showToast", {
                        id: "success_user_add",
                        message: `Пользователь с ID ${value.data.userId} успешно создан`,
                        type: "success",
                        closeButton: false,
                        timeout: 10000
                    });
                    this.$router.push("/users");
                }).catch(error => {
                    this.$store.dispatch("showError", error);
                })
            }

        },
        validatePassword() {
            this.disablePassword = !this.regular.test(this.user.password) || this.user.password === "";
        },
        getRoleNumber(value) {
            return value;
        },
        validateUsername(event) {
            if (this.regularUsername.test(event.data), event) {
                this.user.username = this.user.username.replace(this.regularUsername, "");
            } else {
                return true;
            }
        }


    },
}
</script>

<style scoped>

</style>
