<template>
    <div>
        <!--<form class="card grid" @submit.prevent="authorization" v-if="showLogin">
            <div class="col-2">
                <strong> КРОН-ТМ </strong>
            </div>
            <div class="col-1 text-center">
                <label>Логин:</label>
            </div>
            <div class="p-col-3">
                <InputText v-model="form.login" type="text" class="w-full mb-3 p-1"
                           placeholder="Логин" @keyup.enter="authorization"/>

                <small
                    v-if="!isValidateLogin && showInlineMessage"
                    class="p-error p-error-text">Логин не должен быть пустым</small>
            </div>
            <div class="col-1  text-center">
                <label>Пароль:</label>
            </div>
            <div class="p-col-3">
                <Password v-model="form.password" placeholder="Пароль" inputClass="w-full mb-3 p-1"
                          :feedback="false"
                          @keyup.enter="authorization"></Password>
                <br/>
                <small
                    class="p-error"
                    v-if="!isValidatePassword && showInlineMessage">Пароль не должен быть пустым</small>
            </div>
            <div class="p-col-3 ">
                <Button label="Авторизация" severity="success" type="submit" class=" ml-3 w-full p-button-sm"/>
            </div>
        </form>
        <form class="card grid" @submit.prevent="logout" v-if="!showLogin">
            <div class="col-2 mt-1">
                <strong> КРОН-ТМ </strong>
            </div>
            <div class="col-1 text-center mt-1">
                {{ login }}
            </div>
            <div class="col-3">
                <Button label="Выход" severity="success" type="submit" class="p-button-sm"/>
            </div>
        </form>-->
        <div>
            <TabMenu v-model:activeIndex="active" :model="routes" @click="onClickMenu"/>
            <router-view/>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";

export default {
    name: "KronTM",
    data() {
        return {
            active: null,
            showInlineMessage: false,
            showLogin: true,
            showChild: false,
            form: {
                login: '',
                password: ''
            },
            routes: []
        }
    },
    computed: {
        ...mapGetters(['password', 'login', 'crypted']),
        isValidateLogin: function () {
            return !(this.form.login === "");
        },
        isValidatePassword: function () {
            return !(this.form.password === "") && this.form.password.length >= 4;
        },
    },
    mounted() {
        this.checkAuth();
        this.prepareRoutes();
        this.getIndex();
    },
    methods: {
        ...mapActions(['checkAuthentication', 'setInfo']),
        onClickMenu() {
            this.$router.push({
                path: this.routes[this.active].path
            });
        },
        checkAuth() {
            this.checkAuthentication().then((check) => {
                    if (check) {
                        this.showLogin = false;
                        this.showChild = true;
                    } else
                        this.showLogin = true

                }
            );
        },
        prepareRoutes() {
            for (const route of this.$router.getRoutes()) {
                if (route.name === this.$route.name) {
                    if (route.children.length) {
                        this.routes = route.children;
                    } else {
                        this.routes = this.$route.matched[1].children
                    }

                }
            }
        },
        getIndex() {
            for (let i = 0; i < this.routes.length; i++) {
                if (this.routes[i].path === this.$route.path) {
                    this.active = i;
                }
            }

        },
        authorization() {
            this.showInlineMessage = true;
            if (!this.isValidateLogin || !this.isValidatePassword) return;
            this.setInfo({
                login: this.form.login,
                password: this.crypted(this.form.password),
                session: true
            });
            this.checkAuth();
        }
        ,
        logout() {
            localStorage.removeItem('kron_tm');
            this.checkAuth();
        }
    }
}
</script>

<style scoped>
.p-error-text {
    display: block;
}

.card {
    padding: 0.2rem !important;
}
</style>
