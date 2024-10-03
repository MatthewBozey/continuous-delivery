<template>
  <div class="card lg:p-5 sm:p-1">
    <Toast />
    <ConfirmPopup></ConfirmPopup>
    <Steps :model="items" :readonly="true" />
  </div>
    <router-view v-slot="{Component}" :formData="formObject" @prevPage="prevPage($event)" @nextPage="nextPage($event)" @complete="complete">
      <keep-alive>
        <component :is="Component" />
      </keep-alive>
    </router-view>
</template>

<script>
export default {
  name: 'ResetPassword',
  data() {
    return {
      items: [
        {
          label: 'Подтверждение личности',
          to:    '/proof-identity',
        },
        {
          label: 'Отправка кода',
          to:    '/send-code',
        },
        {
          label: 'Подтверждение кода',
          to:    '/validate-code',
        },
        {
          label: 'Сброс пароля',
          to:    '/change-password',
        },
      ],
      formObject: {},

    };
  },
  methods: {
    nextPage(event) {
      for (let field in event.formData) {
        this.formObject[field] = event.formData[field];
      }

      this.$router.push(this.items[event.pageIndex + 1].to);
    },
    prevPage(event) {
      this.$router.push(this.items[event.pageIndex - 1].to);
    },
    complete() {
      console.log("step success")
    }
  }
};
</script>

<style scoped>

</style>