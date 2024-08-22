<template>
  <div>
    <div v-if="!authStore.isLogin" class="container-fluid">
      <h3 class="text-center pt-3">
        請登入
      </h3>
      <hr>
      <div class="mx-auto text-center p-3">
        <RouterLink class="mx-auto" to="/">登入</RouterLink>
      </div>
    </div>
    <div v-else>
      <!-- query & add -->
      <div class="container-fluid mb-3 p-3">
      <h3 class="text-center pt-3">郵票工作區</h3>
      <hr>
      <div class="row d-flex justify-content-center">
        <div class="col-12 row">
          <div class="col-12 col-md-2">
            <!-- <select v-model.number="selectedCountry" class="form-control mb-3" value="" autofocus required>
              <option value="" selected disabled>選擇國家 Country</option>
              <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.code }} | {{
                country.name }} {{ country.c_name }}</option>
            </select> -->
            <input class="form-control" type="text" v-model.number="selectedCountry" list="sendCountry" placeholder="發行國家 Country From">
                <datalist id="sendCountry" autofocus required>
                  <!-- <option value="" selected disabled>寄出國家 Country From</option> -->
                  <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }} | {{
                    country.code }} {{ country.c_name }}</option>
                </datalist><br>
                
          </div>
          <div class="col-12 col-md-2">
            <input type="text" class="form-control" v-model="price" placeholder="郵票面額 Price" />
          </div>
          <div class="col-12 col-md-2">
            <input type="text" class="form-control" v-model="year" placeholder="發行年分 Year(YYYY)" />
          </div>
          <div class="col-12 col-md-2">
            <input type="text" class="form-control" v-model="id" placeholder="郵票ID" />
          </div>
          <div class="col-12 col-md-4">
            <div class="text-end">
              <button @click="checkStamp(1)" class="btn btn-dark my-3 mx-3">最新郵票</button>
              <button @click="checkStamp(0)" class="btn btn-dark my-3">查閱郵票</button><br>
            </div>
          </div>
        </div>
        <hr>
        <div class="col-12 row">
          <div class="col-12 col-md-4">
            <input type="text" class="form-control mb-1" v-model="unit" placeholder="輸入單位 Unit" />
          </div>
          <div class="col-12 col-md-4">
            <!-- <textarea v-model="remark" placeholder="輸入備註 Remark" class="form-control mb-1" rows="4"></textarea> -->
            <tinycme-editor v-model="remark"  placeholder="輸入備註 Remark"></tinycme-editor>
          </div>
          <div class="col-12 col-md-4">
            <input type="file" class="form-control mb-3" @change="selectFile" placeholder="輸入檔名 Image" />
            <div class="text-end">
              <button @click.prevent="reset" class="btn btn-secondary my-3 mx-3">RESET </button>
              <button @click="addStamp" @submit.prevent class="btn btn-dark">新增郵票</button><br>
          </div>
          </div>
        </div>
      </div>
      </div>
      <hr>
      <!-- thumbnail -->
      <div class="container-fluid mb-3 p-3">
      <div v-if="isLoading" class="loader"></div>
      <div v-else>
        <h3 class="my-2 text-center" :class="success? 'text-primary': 'text-danger'">{{ message }}</h3>
      </div>
      <div class="col-12 row">
        <div class="col-6 col-lg-2" v-for="i in stamps" :key="i.id">
          <hr class="mt-2">
          <p>[{{ i.year }}] [{{ i.price }} {{ i.unit }}] {{ i.img }}</p>
          <a :href="`#${i.id}`"><img :src="`images/stamps/${i.img}`" class="img-fluid img-thumb"></a>
        </div>
      </div>
      </div>
      <hr>
      <!-- detail -->
      <div  class="row" v-if="stamps && stamps.length > 0" >
        <div class="col-12 col-md-6 p-2 mt-3" v-for="i in stamps" :key="i.id">
          <div class="container-fluid p-3" :style="i.edit? {'background-color': 'rgb(220,220,220)'}:{}" >
            <a :name="i.id"></a>
            <!-- edit -->
            <div class="col-12 mx-auto text-end" v-if="i.edit">
              <button class="btn btn-outline-dark btn-sm mb-2" @click="cancelEdit(i)">取消</button>
              <div class="row text-center">
                
                <div class="col-2 text-end">國家</div>
                <div class="col-10">
                  <select v-model.number="newCountry" class="form-control form-control-sm" value="" >
                    <option value="" selected disabled>選擇國家 Country</option>
                    <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.code }} | {{ country.name }} {{ country.c_name }}</option>
                  </select>
                </div><br>
                <div class="col-2 text-end">年份</div>
                <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newYear"></div><br>
                <div class="col-2 text-end">面額</div>
                <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newPrice"></div><br>
                
                  <div class="col-2 text-end">單位</div>
                  <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newUnit"></div><br>
                  <div class="col-2 text-end">備註</div>
                  <div class="col-10">
                    <!-- <textarea v-model="newRemark" class="form-control form-control-sm" rows="5"></textarea> -->
                    <tinycme-editor v-model="newRemark"  placeholder="輸入備註 Remark"></tinycme-editor>
                  </div><br>
                  <div class="col-2 text-end">圖檔</div>
                  <div class="col-10"><input type="file" class="form-control form-control-sm" @change="selectNewFile"></div><br>
                
              </div>
              <button class="btn btn-dark btn-sm m-2" @click="updateStamp(i)">儲存</button>
              <hr>
              <img :src="`images/stamps/${i.img}`" class="img-fluid img-display mb-3" @click=showModal(i)>
                  <!-- << modal -->
                  <Transition name="modal" >
                    <div v-if="i.display" class="modal-mask">
                      <div class="modal-container">
                        <div>
                          <button
                            class="modal-default-button btn-close"
                            @click="closeModal(i)"
                          ></button>
                          <p>{{i.img }}</p>
                        </div>
                        <img :key="i.id" :src="'images/stamps/' + i.img" class="img-modal">
                      </div>
                    </div>
                  </Transition>
                  <!-- modal >> -->
            </div>
            <!-- display -->
            <div class="col-12" v-else :key="i.id" >
              <div class="text-end">
                <button @click="editStamp(i)" class="btn btn-outline-dark btn-sm" :class="{'disabled':isEditing}">編輯</button>
              </div>
              <h5>
                {{ countries[i.country_id-1].name }}, {{ countries[i.country_id-1].c_name }} | {{ i.year }} | {{ i.price
                }} {{ i.unit }} | {{ i.img }}
              </h5>
              <p class="mx-3 mt-3" v-html="i.remark"></p><br>
              <img :src="`images/stamps/${i.img}`" class="img-fluid img-display mb-3" @click=showModal(i)>
                  <!-- << modal -->
                  <Transition name="modal" >
                    <div v-if="i.display" class="modal-mask">
                      <div class="modal-container">
                        <div>
                          <button
                            class="modal-default-button btn-close"
                            @click="closeModal(i)"
                          ></button>
                          <p>{{i.img }}</p>
                        </div>
                        <img :key="i.id" :src="'images/stamps/' + i.img" class="img-modal">
                      </div>
                    </div>
                  </Transition>
                  <!-- modal >> -->
              
            </div>
          </div>

        </div>
        <hr>

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/AuthStore.js';
import TinycmeEditor from './tinymce.vue';

const authStore = useAuthStore();

const id = ref('');
const countries = ref([]);
const selectedCountry = ref('');
const year = ref('');
const price = ref('');
const stamps = ref([]);
const unit = ref('');
const remark = ref('');
const image = ref('');
const message = ref('輸入查詢條件');
const isLoading = ref(false);
const success = ref(false);

let urlSource = 'http://localhost:8888/git-files/collector_vue/collector_vue';
// urlSource = '.';

// get country
async function getCountry() {
  try {
    const url = `${urlSource}/controllers/getCountry.php`;
    const response = await axios.get(url);
    if (response.data.success) {
      countries.value = response.data.countryData;
    } else {
      message.value = response.data.message;
    }
  } catch(error) {
    message.value = error.message;
  }
}

onMounted(() => {
  getCountry();
})

// check stamp
function checkStamp(limit) {
  isLoading.value = true;
  console.log('===== query begin =====')
  // if (selectedCountry.value !== ''){
  //   console.log('country:', countries[selectedCountry.value - 1].name, countries[selectedCountry.value - 1].c_name);
  // }
  let queryUrl = '';
  if (limit == 0) {
    queryUrl = `${urlSource}/controllers/checkStamp.php?id=${id.value}&country=${selectedCountry.value}&year=${year.value}&price=${price.value}&limit=0`;
  } else if (limit == 1){
    queryUrl = `${urlSource}/controllers/checkStamp.php?country=&year=&price=&limit=1`;
  }

  axios.get(queryUrl)
    .then((response) => {
      if (response.data.success) {
        message.value = response.data.message;
        stamps.value = response.data.stampData.map(stamp => ({
          ...stamp, edit: false
        }));
        success.value = true;
        console.log(response.data.message);
        // console.log(response.data.stampData);
      } else {
        stamps.value = [];
        message.value = response.data.message;
        success.value = false;
        console.log(response.data.message);
      }
    })
    .catch((error) => {
      message.value = error.message;
      success.value = false;
      console.error('error: ', error.message);
    })
    .finally(() => isLoading.value = false);
}

// reset 
function reset () {
  selectedCountry.value = '';
  price.value = '';
  year.value = '';
  id.value = '';
  unit.value = '';
  remark.value = '';
  image.value = '';
  
}

// add stamp
function selectFile(event) {
  image.value = event.target.files[0];
  console.log(event.target.files);
}

function addStamp() {
  isLoading.value = true;

  const formData = new FormData();
  formData.append('country', selectedCountry.value,);
  formData.append('price', price.value);
  formData.append('year', year.value);
  formData.append('unit', unit.value);
  formData.append('remark', remark.value);
  formData.append('image', image.value);
  axios.post(`${urlSource}/controllers/addStamp.php`, formData, {
    
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
    .then((response) => {
      if (response.data.success) {
        stamps.value = response.data.stampData;
        message.value = response.data.message;
        success.value = true;
        console.log(response.data.message);
        checkStamp();
      } else {
        message.value = response.data.message;
        success.value = false;
        console.log(response.data.message)

      }
    })
    .catch((error) => {
      console.error('error', error);
      success.value = false;
    })
    .finally(() => {
      isLoading.value = false;
      checkStamp(1);
    });
}

// edit stamp
const isEditing = ref(false);
const newCountry = ref('');
const newYear = ref('');
const newPrice = ref('');
const newUnit = ref('');
const newRemark = ref('');
const newImage = ref('');

function selectNewFile(event) {
  newImage.value = event.target.files[0];
}

function editStamp(stamp) {
  // console.log(stamp);
  stamp.edit = true;
  isEditing.value = true;
  newCountry.value = stamp.country_id;
  newYear.value = stamp.year;
  newPrice.value = stamp.price;
  newUnit.value = stamp.unit;
  newRemark.value = stamp.remark;
  newImage.value = stamp.img;
}

function cancelEdit(stamp) {
  stamp.edit = false;
  isEditing.value = false;
}

function updateStamp(stamp) {
  id.value = stamp.id;
  isLoading.value = true;
  stamp.edit = false;
  isEditing.value = false;

  const formData = new FormData();
  formData.append('country', newCountry.value,);
  formData.append('id', stamp.id);
  formData.append('price', newPrice.value);
  formData.append('year', newYear.value);
  formData.append('unit', newUnit.value);
  formData.append('remark', newRemark.value);
  formData.append('image', newImage.value);
  axios.post(`${urlSource}/controllers/updateStamp.php`, formData, {

    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
    .then((response) => {
      if (response.data.success) {
        // stamps.value = response.data.stampData;
        message.value = response.data.message;
        success.value = true;
        console.log(response.data.message);
      } else {
        message.value = response.data.message;
        success.value = false;
        console.log(response.data.message)
      }
    })
    .catch((error) => {
      console.error('error', error);
      success.value = false;
    })
    .finally(() => {
      isLoading.value = false
      checkStamp(0);
    });
}

// modal
function showModal (i) {
  i.display = true;
}

function closeModal (i) {
  i.display = false;
}

</script>

<style scoped>
* {
  word-wrap:break-word;
  /* border: 1px solid black; */
}

body {
  background-color: #ffffff;
}

img {
  max-width: 150px;
  max-height: 250px;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.38);
}

.img-thumb {
 max-height: 150px;
}
.img-display {
  cursor: pointer;
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

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

/* modal */
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.66);
  display: flex;
  transition: opacity 0.3s ease;
}

.modal-container {
  scale: 1.3;
  max-height: 80vh !important;
  max-width: 90%;
  margin: auto;
  padding: 20px 30px;
  background-color: rgb(247,247,244);
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  
}

.modal-default-button {
  float: right;
}

.modal-enter-from {
  opacity: 0;
}

.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.img-modal {
  max-height: 70vh;
  max-width: 70vw;
}

.container-fluid .my-4 .g-3 .p-5 .col-12 .col-md-6 {
  margin-right: 15px !important;
  border: 1px solid black;
}
</style>