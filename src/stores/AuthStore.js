import { defineStore } from 'pinia';
import { ref, watchEffect } from 'vue';
import axios from 'axios';

export const useAuthStore = defineStore('Auth', ()=> {
  const username = ref('');
  const password = ref('');
  const success = ref(true);
  const message = ref('請輸入帳號密碼');
  const isLogin = ref(localStorage.getItem('collector') !== null);
  const isLoading = ref(false);
  
  let url = 'http://localhost:8888/git-files/collector';
  url = '.';

  async function login() {
    try {
      isLoading.value = true;
      let response = await axios.post(`${url}/controllers/login.php`, {
        username: username.value,
        password: password.value
      }, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
      if (response.data.success) {
        // console.log(isLogin.value);
        message.value = response.data.message;
        console.log(message.value);
        localStorage.setItem('collector', username.value)
        isLogin.value = true;
        username.value = localStorage.getItem('collector');

        
      } else {
        console.log('login failed');
        message.value = response.data.message;
        success.value = false;
      }
    } catch(error) {
      console.error('Server error', error)    
      message.value = error.message;
    } finally {
      isLoading.value = false;
      if ( localStorage.getItem('collector') !== null) {
        username.value = localStorage.getItem('collector');
      }
    } 


  };

  function logout() {
    isLoading.value = true;
    localStorage.removeItem('collector')
    isLogin.value = false;
    message.value = '請輸入帳號密碼';
    success.value = true;
    isLoading.value = false;
  };

  return {
    username,
    password,
    success,
    message,
    isLogin,
    isLoading,
    login,
    logout
  };
})