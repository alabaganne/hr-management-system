<template>
  <div id="app">
    <!-- <div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </div>-->
    <router-view />
    <Toast />
    <!-- <transition name="fade">
      <Loader v-if="isLoading" />
    </transition> -->
  </div>
</template>

<script>
import Toast from './components/Toast.vue'

export default {
  components: {
    Toast
  }
}
</script>

<style lang="scss">
@import "./assets/scss/main.scss";
</style>
