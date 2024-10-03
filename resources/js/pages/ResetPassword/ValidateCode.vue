<template>
  <div class="card m-auto hover:shadow-5 lg:w-4">
    <h2 class="text-green-400 text-center w-ful my-1">Подтверждение кода восстановления</h2>
    <h6 class="text-gray-500 text-center w-ful my-1">Введите код восстановления пароля</h6>
    <InputMask v-model="code" mask="999-999-999" :unmask="true" placeholder="Код восстановления" class="w-full text-center" />
    <Button label="Подтвердить код" :loading="loading" class="w-full m-2 p-button-success" @click="validateCode" :disabled="!code" />
    <Button label="Отменить" :loading="loading" class="w-full m-2  p-button-danger" @click="cancelCode($event)"></button>
  </div>
</template>

<script>

import axios from "axios";

export default {
  name: 'ValidateCode',
  data() {
    return {
      code: null,
      loading: false
    }
  },
  activated() {
    if (!this.$route.params.userId && !this.$route.params.resetPasswordId) {
      this.$router.push('/proof-identity');
    }

  },
  mounted() {

  },
  methods: {
    validateCode(event) {
      this.$confirm.require({
        target: event.currentTarget,
        message: 'Отправить код на проверку?',
        icon: 'pi pi-question',
        accept: () => {
          this.loading = true;
          axios.post("/api/reset-password/validate-code", {code: this.code, userId: this.$route.params.userId}).then(d => {
            this.$router.push({name: "ChangePassword", params: d.data})
          }).catch(err => {
            this.$toast.add({
              severity: 'error',
              summary:  err.code,
              detail:   err.response?.data?.message || err.message,
              life:     5000,
            });
            this.code = null;
          }).finally(() => {
            this.loading = false;
          });
        },
        reject: () => {
          this.$confirm.close();
        },
        acceptLabel: "Да",
        acceptClass: "p-button-success",
        rejectLabel: "Нет",
        rejectClass: "p-button-danger",
        position: "right"
      });
    },
    cancelCode(event) {
      this.$confirm.require({
        target: event.currentTarget,
        message: 'Вы действительно хотите вернуться на страницу авторизации?',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
          this.$router.push('login')
        },
        reject: () => {
          this.$confirm.close();
        },
        acceptLabel: "Перейти на страницу авторизации",
        acceptClass: "p-button-danger",
        rejectLabel: "Нет",
        position: "right"
      });
    }
  },
};
</script>

<style scoped>

</style>