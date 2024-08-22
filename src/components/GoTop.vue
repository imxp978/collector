<template>
  <div class="text-end">
    <div v-if="showGoTop" @click="goTop" id="gotop"
      class="position-fixed bottom-0 end-0 p-4 mb-4 me-4 bg-light text-white bg-opacity-10 rounded">
      <i id="arrow" class="bi bi-chevron-double-up text-white"></i>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
const showGoTop = ref(false);

function handleScroll () {
  showGoTop.value = window.scrollY > 100;
};

function goTop ()  {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});
</script>

<style scoped>
    
  /* gotop */
  #gotop {
    position: fixed;
    background: rgba(0, 0, 0, 0.2) !important;
    cursor: pointer;
    color: gray !important;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #arrow {
    position: absolute;
    transition: 0.3s;
    animation: blink 0.3s infinite;
  }

  @keyframes blink {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(5px);
    }
  }

  #gotop:hover {
    background: rgba(0, 0, 0, 0.5) !important;
    color: white !important;
    transition: 0.3s;
  }
</style>