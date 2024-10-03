<template>

  <div class="card m-auto hover:shadow-4 lg:w-4">
    <h2 class="text-indigo-400 text-center w-ful my-1">Изменение пароля</h2>
    <h6 class="text-gray-500 text-center w-ful my-1">Введите Ваш новый пароль
    </h6>
    <Password v-model="password" toggleMask placeholder="Пароль" :class="['w-full', 'my-1', {'p-invalid': mediumPass}]"
              strong-label="Сильный" strong-regex="((?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,})"
              weak-label="Простой" input-class="w-full"
              medium-label="Средний" prompt-label="Введите пароль">
      <template #header><h5>Введите пароль</h5></template>
      <template #footer>
        <Divider />
        <p class="mt-2">Правила</p>
        <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
          <li>По крайней мере, одна строчная буква</li>
          <li>По крайней мере, одна заглавная буква</li>
          <li>По крайней мере, одно число</li>
          <li>По крайней мере, один спецсимвол</li>
          <li>Минимум 8 символов</li>
        </ul>
      </template>
    </Password>
    <Password v-model="confirmPassword" placeholder="Повторите пароль" input-class="w-full" :disabled="!mediumPass"
              :class="['w-full', 'my-1', {'p-invalid': passConf}]" :feedback="false"/>
    <Button label="Изменить пароль" :loading="loading" class="p-button-success w-full my-2"
            @click="changePassword($event)"
            :disabled="passConf"/>
    <Button label="Отменить" :loading="loading" class="p-button-danger w-full my-2"
            @click="cancelCode($event)"/>
  </div>

</template>

<script>
import axios from "axios";

export default {
  name: "ChangePassword",
  data() {
    return {
      loading: false,
      password: null,
      confirmPassword: null,
      regular: /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{8,}/
    }
  },
  activated() {
    if (!this.$route.params.userId && !this.$route.params.result) {
      this.$router.push('/proof-identity');
    }
  },
  computed: {
    passConf() {
      return this.password !== this.confirmPassword || this.password === null;
    },
    mediumPass() {
      return this.regular.test(this.password);
    }
  },
  methods: {
    changePassword(event) {
      this.$confirm.require({
        target: event.currentTarget,
        message: 'Вы действительно хотите изменить пароль?',
        icon: 'pi pi-question',
        accept: () => {
          this.loading = true;
          axios.put("/api/reset-password/change-password", {...this.$route.params, "password": this.password}).then(d => {
            this.$toast.add({
              severity: 'error',
              summary:  "Пароль изменен",
              detail:   d?.data?.message,
              life:     5000,
            });
            this.$router.push("/login");
          }).catch(err => {
            this.$toast.add({
              severity: 'error',
              summary:  err.code,
              detail:   err.response?.data?.message || err.message,
              life:     5000,
            });
          }).finally(() => {
            this.loading = false;
          })
        },
        reject: () => {
          this.$confirm.close();
        },
        acceptLabel: "Да",
        acceptClass: "p-button-danger",
        rejectLabel: "Нет",
        position: "right"
      });
    }
  },
}
</script>

<style scoped>

</style>