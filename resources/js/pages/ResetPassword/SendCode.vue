<template>
  <div class="card m-auto hover:shadow-5 lg:w-4">
    <h2 class="text-green-400 text-center w-ful my-1">Код отправлен</h2>
    <h6 class="text-gray-500 text-center w-ful my-1">На вашу электронную почту отправлено письмо с кодом для сброса пароля</h6>
    <Button class="p-button-success w-full my-2" label="Подтвердить код" @click="validateCode"></Button>
    <Button class="p-button-danger w-full my-2" label="Отменить" @click="cancelSendCode"></Button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'SendCode',
  data() {
    return {
      resetPasswordId: null,
      userId: this.$route.params.userId
    }
  },
  activated() {
    if (this.$route.params.userId && this.$route.params.username) {
      axios.post('/api/reset-password/send-code', this.$route.params).then(result => {
        this.$toast.add({
            severity: 'success',
            summary:  "Сброс пароля",
            detail:   result.data.message,
            life:     3000
        });
        this.resetPasswordId = result.data.resetPasswordId;
      }).catch((err) => {
        this.$toast.add({
          severity: 'error',
          summary:  err.code,
          detail:   err.response?.data?.message || err.message,
          life:     5000,
        });
      }).finally(() => {
        this.username = '';

      });
    } else {
      this.$router.push('/proof-identity');
    }
  },
  mounted() {

  },
  methods: {
    cancelSendCode() {
      this.$router.push("/login")
    },
    validateCode() {
      this.$router.push({name: "ValidateCode", params: {userId: this.$route.params.userId, resetPasswordId: this.resetPasswordId}})
    }
  }
};
</script>

<style scoped>

</style>