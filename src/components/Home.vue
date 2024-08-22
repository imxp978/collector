<template>
  <div class="container-fluid mb-3 p-3">
    <div class="row">
      <div v-if="!authStore.isLogin" class="col-4 mx-auto">
        <h3 class="text-center pt-3">登入</h3>
        <hr>
        <div>
            <img src="/images/envelopes/000015_1.jpg" class="mx-auto">
        </div>
        <hr>
        <input type="text" v-model="authStore.username" placeholder="username" class="form-control m-3" autofocus>
        <input type="password" v-model="authStore.password" placeholder="password" class="form-control m-3">
        <div class="text-end">
          <div class="text-center">
            <div v-if="authStore.isLoading" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p :class="{'text-danger': !authStore.success, 'text-dark': authStore.success}">{{ authStore.message }}</p>
          </div>
          <button class="btn btn-dark m-3" @click="authStore.login">登入</button>

        </div>
      </div>
      <div v-else class="col-6 mx-auto">
        <h3 class="text-center pt-3">成功登入</h3>
        <hr>
        <div>
          <img src="/images/envelopes/000015.jpg" class="mx-auto">
        </div>
        <hr>
        <p class="text-center">歡迎{{ authStore.username }}</p>
        <div class="text-center">
          <button class="btn btn-dark mb-3" @click="authStore.logout">登出</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/AuthStore.js';

const authStore = useAuthStore();
const login = ref(false);
const username = ref('')

onMounted(() => {
  login.value = authStore.isLogin;

  
});



</script>

<style scoped>
*{
  /* border: 1px solid black; */
}

img {
  max-width: 100%;
}

.container-fluid {
  background-color: rgb(247,247,247);
  border-radius: 10px;
  transition: 0.3s;
}

.container-fluid:hover {
  background-color: rgb(230,230,230);
  transition: 0.3s;
}

.loader {
  margin: 0 auto;
  border: 10px solid #f3f3f3;
  border-top: 10px solid #3498db;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 0.3s linear infinite;
  z-index: 1;
}

</style>