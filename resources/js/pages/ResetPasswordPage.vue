<template>
    <div class="card">
        <div class="flex align-items-center justify-content-center overflow-hidden p-4 min-h-screen min-w-screen">
            <div class="col-12 xl:col-5 shadow-4 border-round-3xl hover:shadow-8 ">

                <form @submit.prevent="changePassword">
                    <div class="w-full md:w-10 mx-auto">
                        <h5 class="text-center text-xl m-5">Для сброса пароля введите ваш логин от системы</h5>
                        <InputText id="email" v-model="username" type="text" class="w-full mb-3 p-1"
                                   placeholder="Логин"/>

                        <Button label="Сбросить пароль" type="submit" :loading="loading" class="w-full p-3 mb-4 p-button-sm"/>
                        <Button label="Назад" :loading="loading" class="w-full p-3 mb-4 p-button-outlined p-button-danger p-button-sm" @click="$router.push('/')"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ResetPasswordPage",
    data() {
        return {
            loading: false,
            username: null,
        }
    },
    methods: {
        changePassword() {
            this.$devops_api.post("/api/auth/reset-password", {username: this.username}).then(value => {
                this.$notification.success(value.data.message);
                this.$router.push("/");
            }).catch(reason => this.$store.dispatch("showError", reason));
        }
    }
}
</script>

<style scoped>

</style>
