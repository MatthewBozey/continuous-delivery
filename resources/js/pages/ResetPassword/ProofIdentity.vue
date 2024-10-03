<template>
  <div class="card m-auto hover:shadow-5 lg:w-4">
    <h2 class="text-indigo-400 text-center w-ful my-1">Подтверждение личности</h2>
    <h6 class="text-gray-500 text-center w-ful my-1">Для сброса пароля введите ваш логин в системе</h6>
    <Message v-show="!error.noEmail" severity="error" :closable="false">К вашему профилю не привязана электронная почта.
      Обратитесь к разработчикам системы для привязки электронной почты к профилю.
    </Message>
    <InputText v-model="username" :loading="loading" type="text" class="w-full my-1" placeholder="Логин"/>

    <Button label="Подтвердить личность" :loading="loading" class="w-full my-2 p-button-success" :disabled="!username" @click="sendUsername"></button>
    <Button label="Вернуться" :loading="loading" class="w-full my-2 p-button-danger"
            @click="$router.push('login')"></button>
  </div>

</template>

<script>


'use strict';
import axios from 'axios';

export default {
  name: 'ProofIdentity',
  data() {
    return {
      username: '',
      loading: false,
      error: {
        noEmail: true
      }
    };
  },
  methods: {
    sendUsername() {
      /** @type {boolean} */
      this.loading = true;
      axios.post('/api/reset-password/validate-username', {
        username: this.username,
      }).then(result => {
        this.error.noEmail = result.data.hasEmail || false;
        if (result.data?.hasEmail) {
          this.$router.push({name: 'SendCode', params: result.data});
        }
      }).catch((err) => {
        this.$toast.add({
          severity: 'error',
          summary: err.code,
          detail: err.response?.data?.message || err.message,
          life: 5000,
        });
      }).finally(() => {
        this.username = '';
        this.loading = false;
      });
    },
  },
};
</script>

<style scoped>

</style>