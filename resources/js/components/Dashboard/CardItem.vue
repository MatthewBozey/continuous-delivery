<template>
  <div class="col-12 lg:col-6 xl:col-3">
    <div class="card mb-0">
      <div class="flex justify-content-between mb-3">
        <div>
          <span class="block text-500 font-medium mb-3">{{ title }}</span>
          <div class="text-900 font-medium text-xl">{{  desc }}</div>
        </div>
        <div :class="['flex align-items-center justify-content-center', bgColor, ' border-round']" style="width:2.5rem;height:2.5rem">
          <i :class="[icon, textColor]"></i>
        </div>
      </div>
      <span class="text-green-500 font-medium">{{ desc }} новых </span>
      <span class="text-500">с момента последнего визита</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CardItem',
  props: {
    title: {
      required: true
    },
    desc: {
      required: true
    },
    icon: {
      default: "pi pi-box"
    },
    textColor: {
      default: "text-blue-400"
    },
    bgColor: {
      default: "bg-blue-100"
    }
  }
};
</script>

<style scoped>

</style>