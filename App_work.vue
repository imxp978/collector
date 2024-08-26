<script setup>
import GoTop from './components/GoTop.vue'
import AOS from 'aos'
import 'aos/dist/aos.css'
import ScrollyVideo from 'scrolly-video/dist/ScrollyVideo.vue';

import { createApp, ref, onMounted } from 'vue';
const blurValues = ref([]);
const grayValues = ref([]);
const saturationValues = ref([]);
const dynamicColors = ref([]);
const showGoTop = ref(false);

const works = ref([
  { 
    id: 'ecommerce', 
    title: '電商網站', 
    text: '以Vue3前端框架，PHP作後端API，MySQL資料庫製作的電商購物網站。搭配Vue-Router設置路由以達成SPA單頁式應用, 使用Pinia進行狀態數據管理。以Axios與後端PHP API進行交互，透過POST/GET request方式發送JSON數據，於資料庫內新增、查詢、修改資料。',
    tool:'使用的工具為：<br>前端：Vue3, Vue-Router, Pinia <br>後端：PHP, MySQL <br>第三方套件：Axios, Bootstrap, Swiper', 
    github: 'https://github.com/imxp978/ecommerce_vue', 
    demo: 'https://ecommerce.work.tsaochun.com/',
    img:'/images/ecommerce.png'
  },
  { 
    id: 'collector', 
    title: '管理後台', 
    text:'以Vue3 以及 PHP 作為前後端製作的資料庫網站後台。這是集郵收藏品網站的管理後台，以Pinia管理登入狀態，搭配Vue-Router製作單頁式應用(SPA)，以達成即時查找、修改資料的免換頁效果。以Axios與後端PHP API交互，透過POST/GET request，並透過JSON傳送數據，FormData傳送檔案，方便使用者直接在網頁介面上進行資料庫的新增、修改、查詢。不會因為換頁而造成工作流程中斷。<br><br>[測試帳號密碼為：test/1]' ,
    tool: '使用的工具為：<br>前端：Vue3, Vue-Router, Pinia <br>後端：PHP, MySQL <br>第三方套件：Axios, Bootstrap', 
    github: 'https://github.com/imxp978/collector', 
    demo: 'https://collector.work.tsaochun.com',
    img: '/images/collector.png'
  },
  { 
    id: 'stream', 
    title: '串流平台', 
    text: '以Vue3 以及 PHP 作為前後端製作的影片串流/上傳網站。將影片儲存至伺服器，並且提供即時串流播放。將用戶上傳的檔案以formData發送至後端，並且移動至指定資料夾，透過axios向後端發出request，從資料庫取出影片清單，使後端檔案依照參數動態讀取不同影片的檔案。製作過程中使用PHP檔案相關函數fopen, fread, ftell, fseek選取檔案每次讀取的buffer範圍，依照不同的的HTTP RANGE提供不同的http header。', 
    tool:'使用的工具為：<br>前端：HTML, CSS, Vue3<br>後端：PHP, MySQL<br>第三方套件：Bootstrap, Axios', 
    github:'https://github.com/imxp978/stream', 
    demo:'https://stream.work.tsaochun.com/',
    img: '/images/stream.png'
  },
  { 
    id: 'stopwatch', 
    title: '計時碼表', 
    text: '碼表小工具。透過JS setInterval功能計時，將數據寫入DOM元素。可隨時間動態變換漸層色背景，連點紀錄可直接修改，將紀錄存入cookie。', 
    tool:'使用的工具為：JavaScript, HTML, CSS, bootstrap',
    github:'https://github.com/imxp978/js_gadget/blob/main/stopwatch.html', 
    demo:'https://work.tsaochun.com/stopwatch.html',
    img:'/images/stopwatch.png'
  },
  { 
    id: 'todo', 
    title: '待辦事項', 
    text: '待辦事項小工具，日常任務事項管理，依照重要程度將項目分類，完成後可標註為已完成，也可將任務刪除、或存入Local storage避免頁面重新整理時遺失數據。', 
    tool:'使用的工具為：JavaScript, HTML, CSS', 
    github:'https://github.com/imxp978/js_gadget/blob/main/todolist.html', 
    demo:'https://work.tsaochun.com/todolist.html',
    img:'/images/todo.png'
  }
]);

function handleScroll() {
  // intro
  const windowHeight = window.innerHeight;
  const intro = document.getElementById('intro');
  const newPosition = `${50 + window.scrollY * 0.009}%`;
  intro.style.backgroundPositionY = newPosition;

  const introH1 = document.getElementById('intro-h1');
  const newScale = `${1 - window.scrollY * 0.001}`;
  introH1.style.transform = `scale(${newScale})`;

  // works
  works.value.forEach((work, index) => {
    const element = document.getElementById(work.id);
    if (element) {
      const rect = element.getBoundingClientRect();
      const visibleHeight = Math.max(0, Math.min(rect.bottom, windowHeight) - Math.max(rect.top, 0));
      const totalHeight = rect.height;

      let visibleRatio = 0;
      if (rect.top <= 0 && rect.bottom >= windowHeight) {
        visibleRatio = 1;
      } else {
        visibleRatio = visibleHeight / totalHeight;
      }
      // blur
      blurValues.value[index] = 10 * (1 - visibleRatio);

      // grayscale
      grayValues.value[index] = 0 + (1 - visibleRatio); 
      
      // saturation
      saturationValues.value[index] = (visibleRatio);

      // color
      const startColor = 30; 
      const endColor = 255;   
      const colorValue = Math.floor(startColor + (endColor - startColor) * (1 - visibleRatio));
      dynamicColors.value[index]  = `rgb(${colorValue}, ${colorValue}, ${colorValue})`;
    }
  });

  // Update the visibility of the "Go to Top" button
  showGoTop.value = window.scrollY > 100;
}

function goTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  AOS.init();
  document.documentElement.style.scrollBehavior = "smooth";
});
</script>

<template>
  
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
      <div class="container mx-auto p-4 flex justify-between items-center">
        <a href="/" class=" text-black hover:text-orange">
          <div class="text-2xl font-bold">TSAO CHUN</div>
        </a>
        <div>
          <a href="#about" class="text-gray-800 mx-4">About</a>
          <a href="#works" class="text-gray-800 mx-4">Works</a>
          <a href="#contact" class="text-gray-800 mx-4">Contact</a>
        </div>
      </div>
    </nav>

    <!-- Intro Section -->
    <section id="intro" class="text-white h-screen flex items-center justify-center">
      <div class="text-center">
        <h2>Hi there, 我是</h2>
        <h1 id="intro-h1" class="text-6xl">曹 淳<br></h1>
        <h2>
        <a href="#ecommerce">
        <div id="hint-wrap">
          <svg id="hint" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-double-down" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.646 6.646a.5.5 0 0 1 .708 0L8 12.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
            <path fill-rule="evenodd" d="M1.646 2.646a.5.5 0 0 1 .708 0L8 8.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
          </svg><br>
          
        </div>
      </a>
      </h2>
      <h3>scroll</h3>
      </div>
    </section>
    <section class="spacer"></section>
    <ScrollyVideo class="z-0 fixed w-full brightness-200 blur-sm" src="/video/ocean.mp4"/>
    <!-- Work Section -->
    <div class="work" v-for="(work, index) in works" :key="index">
      <section  :id="work.id" :class="index % 2 === 0 ? 'left' : 'right'" :style="{ filter: `blur(${blurValues[index]}px) saturate(${saturationValues[index]}) drop-shadow(3px 3px 3px rgba(0,0,0,0.3))`, color: dynamicColors[index] || '#808080' }">
        <div class="container">
          <a :name="work.id" ></a>
          <h1>{{ work.title }}</h1>
          <p class="detail-text" data-aos="fade-down" data-aos-duration="800" v-html="work.text"></p><br>
          <p class="detail-text" data-aos="fade-down" data-aos-duration="800" v-html="work.tool"></p><br>
          <p data-aos="fade-up" data-aos-duration="1000"><a class="no-underline text-orange-400 hover:text-yellow-200" :href="work.demo" target="_blank">DEMO</a> | <a class="no-underline text-orange-400 hover:text-yellow-200" :href="work.github" target="_blank">Github</a></p>
        </div>
      </section>
      <section class="spacer"></section>
    </div>

    <!-- About Section -->
    <section id="about" class="p-10 flex justify-center items-center mb-100">
      <div id="about-content" class="p-8 rounded-md bg-opacity-60" data-aos="fade-down">
        <h3 class="text-4xl mb-6">About</h3>
        <div>
          我的工作經驗主要來自電商以及當代藝術領域。<br>
          因為製作作品集網站以及電商業務的需求， <br>
          開始在Web領域學習從而進行開發。<br>
          <br>
          我擅長使用的工具有：<br>
          前端: Vue3, CSS, HTML, Bootstrap, Vue-Router, Pinia, Axios <br>
          後端: PHP, MySQL <br>
          <br>
          這個網站使用Vue3, Tailwind CSS, AOS, scrolly-video製作<br>
          video credit: Joan Costa <br>
        </div>
      </div>
    </section>
    <!-- Works Section -->
    <section id="works" class="container mx-auto p-10">
      <div id="works-content" class="grid grid-cols-1 md:grid-cols-3 gap-12 p-8 rounded-md bg-opacity-60 mx-auto">
        <h3 class="text-4xl z-10 mb-6">Works</h3>
        <!-- Work -->
        <div class="bg-gray-100 shadow-lg rounded-md overflow-hidden h-auto " v-for="work in works" data-aos="fade-up" :key="work.id">
          <a :href="`#${work.id}`">
          <div class="h-80 work-thumbnail">
              <img :src="`${ work.img }`" class="h-full object-cover">
            </div>
            <div class="p-3">
              <h3 class="text-xl text-center">{{ work.title }}</h3>
            </div>
          </a>
        </div>
      </div>

    </section>

    <!-- Contact -->
    <section id="contact" class="p-10 flex justify-center items-center">
      <div id="contact-content" class="p-8 rounded-md bg-opacity-50 mx-auto" data-aos="fade-down">
        <h3 class="text-4xl mb-6">Contact</h3>
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
          </svg>&nbsp;<a href="mailto:contact@tsaochun.com">contact@tsaochun.com</a>
        </div>
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-telephone-plus-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM12.5 1a.5.5 0 0 1 .5.5V3h1.5a.5.5 0 0 1 0 1H13v1.5a.5.5 0 0 1-1 0V4h-1.5a.5.5 0 0 1 0-1H12V1.5a.5.5 0 0 1 .5-.5"/>
          </svg>&nbsp;0986304122
        </div>
      </div>
    </section>
    
    <GoTop/>
</template>

<style>
    @media screen and (max-width: 600px) {
      h1 {
        color: black;
        background: rgba(255, 255, 255, 0.66);
        padding: 5px;
        border-radius: 5px;   
      }
      #intro h1 {
        background: none;
        color: white;
      }
      p {
        color: black;
        background: rgba(255, 255, 255, 0.66);
        padding: 5px;
        border-radius: 5px;
      }
      #intro {
        background-attachment:scroll !important;
      }

      .left .container h1 {
        font-size: 73px !important;
      }
      .right .container h1 {
        font-size: 73px !important;
      }
      #ecommerce {
        background-attachment:scroll !important;
      }
      #collector {
        background-attachment:scroll !important;
      }
      #stream {
        background-attachment:scroll !important;
      }
      #stopwatch {
        background-attachment:scroll !important;
      }
      #todo {
        background-attachment:scroll !important;
      }
      .work-thumbnail {
        filter: brightness(1) !important;
      }
    }

    *{
      /* border: 1px solid black !important; */
      line-height: 1.7em;
      word-wrap: break-word;
      letter-spacing: 0.05rem;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      overflow-x: hidden;
    }

    #app {
      /* background: url("/images/test.jpg"); */
    }

    h1, h2 {
      font-family: "Noto Serif TC" !important;
      filter: drop-shadow(1px 1px 3px white)
    }

    #intro {
      background: url("/images/bg.jpg") fixed no-repeat 50% 50%;
      height: 150vh;
    }

    #intro h1, h2 {
      line-height: 2em;
      filter: none;
    }

    #hint-wrap {
      margin: 0 auto;
      width: 40px;
      height: 40px;
      background: white;
      border-radius: 20px;
      display: flex;
      align-items: center;
    }

    #hint {
      margin: 0 auto;
      color: black;
      animation: blink 0.5s infinite
    }

    #ecommerce {
      background: linear-gradient(85deg,rgba(245, 245, 245, 0.95) 40%,transparent 40.1%) center center / 100% 100%, url("/images/ecommerce.gif") fixed no-repeat center right / 60%;
    }

    #collector {
      background: linear-gradient(-85deg, rgba(245, 245, 245, 0.95) 40%, transparent 40.1%) center center / 100% 100%, url("/images/collector.gif") fixed no-repeat center left / 60%;
    }
    #stream {
      background: linear-gradient(85deg,rgba(245, 245, 245, 0.95) 40%,transparent 40.1%) center center / 100% 100%, url("/images/stream.gif") fixed no-repeat center right / 60%;
    }
    #stopwatch {
      background: linear-gradient(-85deg,rgba(245, 245, 245, 0.95) 40%,transparent 40.1%) center center / 100% 100%, url("/images/stopwatch.gif") fixed no-repeat center left / 60%;
    }
    #todo {
      background: linear-gradient(85deg,rgba(245, 245, 245, 0.95) 40%,transparent 40.1%) center center / 100% 100%, url("/images/todo.gif") fixed no-repeat center right / 60%;
    }

    .left {
      transition: 0.3s;
      height: 180vh; 
    }
    .left .container {
      margin: 0 auto;
      max-width: 1440px;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      padding: 10px;
    }
    .left .container h1 {
      font-size: 80px;
      border-bottom: 1px solid black;
      margin-bottom: 20px;
    }

    .right {
      transition: 0.3s;
      height: 180vh; 
    }
    .right .container {
      margin: 0 auto;
      max-width: 1440px;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: center;
      padding: 10px;
    }
    .right .container h1 {
      font-size: 80px;
      border-bottom: 1px solid black;
      margin-bottom: 20px;
    }

    .detail-text {
      width: 300px;
      filter: drop-shadow(1px 1px 3px white)
    }

    .spacer {
      height: 30vh;
    }

    #works-content {
      background: rgba(240, 240, 240, 0.6) !important;
      transition: 0.3s;
    }

    .work-thumbnail {
        filter: brightness(0.6);
        transition: 0.3s;
      }
    
    .work-thumbnail:hover {
        filter: brightness(1);
        scale: 1.05;
    }

    #about {
      height: 100vh;
      margin-bottom: 200px;
    }

    #about-content {
      background: rgba(240, 240, 240, 0.5);
      transition: 0.3s;
    }

    #about-content:hover {
      background: rgba(255, 255, 255, 0.6);
    }

    #contact {
      height: 120vh;
      animation: blink 0.5s infinite;
    }

    #contact-content {
      background: rgba(240, 240, 240, 0.6);
      transition: 0.3s;
    }

    #contact-content:hover {
      background: rgba(255, 255, 255, 0.7);
    }

    @keyframes blink {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(5px);
      }
    }
</style>
