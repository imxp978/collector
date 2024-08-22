<template>
  <div>
    <div v-if="!authStore.isLogin" class="container-fluid">
      <h3 class="text-center pt-3">
        請登入
      </h3>
      <hr>
      <div class="mx-auto text-center p-3">
        <RouterLink class="mx-auto " to="/">登入</RouterLink>
      </div>
    </div>
    <!-- query & add -->
    <div v-else class="">
      <div class="container-fluid mb-3 p-3">
        <h3 class="text-center pt-3">郵封工作區</h3>
        <hr>
        <div class="row d-flex justify-content-center">
          <div class="col-12 row">
            <div class="col-12 col-md-4">
              <input class="form-control mb-3" type="text" v-model.number="sendCountry" list="sendCountry" placeholder="寄出國家 Country From">
              <datalist id="sendCountry" autofocus required>
                <!-- <option value="" selected disabled>寄出國家 Country From</option> -->
                <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }} | {{
                  country.code }} {{ country.c_name }}</option>
              </datalist>
            </div>
            <div class="col-12 col-md-2">
              <div class="text-end">
                <input type="text" class="form-control" v-model="sendTime" placeholder="郵戳年份 Year (YYYY)" />
              </div>
            </div>  
            <div class="col-12 col-md-2">
              <div class="text-end">
                <input type="text" class="form-control" v-model="id" placeholder="郵封ID Envelope ID" />
              </div>
            </div>  
            <div class="col-12 col-md-4 text-end">
              <button @click.prevent="checkEnvelope(1)" class="btn btn-dark my-3 mx-3">最新郵封</button>
              <button @click.prevent="checkEnvelope(0)" class="btn btn-dark my-3">查閱郵封</button><br>
            </div>
          </div>
          <hr>
          <div class="col-12 row">
            <div class="col-12 col-md-4">
              <input v-model.number="toCountry" list="toCountry" type="text" class="form-control mb-3" placeholder="寄達國家 Country To">
              <datalist id="toCountry" value="">
                <!-- <option value="" selected disabled>寄往國家 Country To</option> -->
                <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }} | {{
                  country.code }} {{ country.c_name }}</option>
              </datalist>
              <select v-model.number="type" class="form-control">
                <option value="" selected disabled>郵封類型 Type</option>
                <option v-for="type in types" :key="type.id" :value="type.id">{{ type.type }}</option>
              </select>
              <input type="text" class="form-control" v-model="theme" placeholder="主題 Theme" />
              <div class="row">
                <div class="col-6 ">
                  <input type="text" class="form-control " v-model="sendCity" placeholder="寄出城市 City From" />
                  <input type="text" class="form-control " v-model="sendZip" placeholder="寄出郵遞區號 Zip From" />
                </div>
                <div class="col-6">
                  <input type="text" class="form-control " v-model="toCity" placeholder="寄往城市 City To" />
                  <input type="text" class="form-control " v-model="toZip" placeholder="寄往郵遞區號 Zip To" />
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <!-- <textarea id="remark" v-model="remark" placeholder="輸入備註 Remark" class="textarea form-control mb-1" rows="7"></textarea> -->
              <!-- TinyMCE -->
              <tinycme-editor v-model="remark" ></tinycme-editor>
        

            </div>
            <div class="col-12 col-md-4">
              <input type="file" ref="file1" class="form-control " accept="image/*" />
              <input type="file" ref="file2" class="form-control " accept="image/*" />
              <input type="file" ref="file3" class="form-control " accept="image/*" />
              
              <div class="text-end col-12 d-flex justify-content-end">
                <button @click.prevent="reset" class="btn btn-secondary my-3 mx-3">RESET </button><br>
                <button @click.prevent="addEnvelope" class="btn btn-dark my-3">新增郵封 </button><br>
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
          <div class="col-12 row">
            <div class="col-6 col-md-2 px-4 mb-5 d-flex flex-column" v-for="i in envelopes" :key="i.id">
              <hr>
              <p>[{{ (i.country_from && i.country_from !== null) ? countries[i.country_from-1].c_name : '' }}] >> [{{ (i.country_to && i.country_to !== null)? countries[i.country_to-1].c_name : '' }}] | {{ i.img.length ? i.img[0].img : '' }}</p>

                <a :href="'#' + i.id" class="flex justify-content-center"><img v-if="i.img.length" :src="'images/envelopes/' + i.img[0].img" class="img-fluid img-thumb"></a>

            </div>
          </div>
        </div>
      </div>
      <hr>
      <!-- detail -->
      <div class="row"  v-if="envelopes && envelopes.length > 0" >
        <div class="col-12 col-md-6 p-2 mt-3" v-for="i in envelopes" :key="i.id">
          <div class="container-fluid p-3 mb-5" :style="i.edit? {'background-color': 'rgb(210,210,210)'}:{}">
              <a :name="i.id"></a>         
              <!-- edit -->
              <div class="col-12 mx-auto text-end" v-if="i.edit">
                <button class="btn btn-outline-dark btn-sm" @click.prevent="cancelEdit(i)">取消</button>
                <div class="row">
                  <div class="col-12 col-md-6 row">
                    <div class="col-12 text-start"><h5>寄出：</h5><hr></div>
                    <div class="col-2 text-end">國家</div>
                    <div class="col-10">
                      <input list="newSendCountry" v-model.number="newSendCountry" type="text" class="form-control" placeholder="選擇國家 Country">
                      <datalist id="newSendCountry" >
                        <!-- <option value="" selected disabled>選擇國家 Country</option> -->
                        <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.code }} | {{ country.name }} {{ country.c_name }}</option>
                      </datalist>
                    </div><br>

                    <div class="col-2 text-end">城市</div>
                    <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newSendCity"></div><br>
                    <div class="col-2 text-end">Zip</div>
                    <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newSendZip"></div><br>
                    <hr class="mt-3">
                  </div>
                  <div class="col-12 col-md-6 row">
                    <div class="col-12 text-start"><h5>寄達：</h5><hr></div>
                    <div class="col-2 text-end">國家</div>
                    <div class="col-10">
                      <input list="newToCountry" v-model.number="newToCountry" class="form-control" placeholder="選擇國家 Country">
                      <datalist id="newToCountry">
                        <!-- <option value="" selected disabled>選擇國家 Country</option> -->
                        <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.code }} | {{ country.name }} {{ country.c_name }}</option>
                      </datalist> 
                    </div><br>
                    <div class="col-2 text-end">城市</div>
                    <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newToCity"></div><br>
                    <div class="col-2 text-end">Zip</div>
                    <div class="col-10"><input type="text" class="form-control form-control-sm" v-model="newToZip"></div><br>
                    <hr class="mt-3">
                  </div>
                  <div class="col-12 col-md-6 row">

                    <div class="col-2 text-end">備註</div>
                    <div class="col-10">
                      <!-- <textarea id="newremark" v-model="newRemark" class="form-control form-control-sm textarea" rows="4"></textarea> -->
                      <tinycme-editor v-model="newRemark" ></tinycme-editor>
                    </div>
                  </div>
                  <div class="col-12 col-md-6 row">
                    <div class="col-2 text-end">年份</div>
                    <div class="col-10">
                      <input type="text" class="form-control form-control-sm" v-model="newSendTime">
                    </div>
                    <div class="col-2 text-end">主題</div>
                    <div class="col-10">
                      <input type="text" class="form-control form-control-sm" v-model="newTheme">
                    </div>
                    <div class="col-2 text-end">類型</div>
                    <div class="col-10">
                      <select v-model="newType" class="form-control form-control-sm" required>
                        <option value="" selected disabled>郵封類型 Type</option>
                        <option v-for="type in types" :key=type.id :value="type.id">{{ type.type }}</option>
                      </select>
                    </div>
                    <div class="col-2 text-end">圖檔</div>
                    <div class="col-10">
                      <input type="file" class="form-control form-control-sm" @change="selectNewFile1">
                      <input type="file" class="form-control form-control-sm" @change="selectNewFile2">
                      <input type="file" class="form-control form-control-sm" @change="selectNewFile3">
                    </div>
                  </div>
                </div> 
                <button class="btn btn-dark btn-sm" @click.prevent="updateEnvelope(i)">儲存</button> 
                <hr>
                <div class="row">
                  <div v-for="j in i.img" :key="j.id" class="col-4 position-relative">
                    <img :src="'images/envelopes/' + j.img" @click=showModal(j) class="img-fluid mx-3 img-display">
                    <span @click="delImage(i, j)" class="h-auto btn position-absolute top-0 start-100 translate-middle badge border border-light rounded-pill bg-danger p-2">
                      X
                    </span>
                    <!-- modal -->
                    <Transition name="modal" >
                      <div v-if="j.display" class="modal-mask">
                        <div class="modal-container">
                          <div>
                            <button
                              class="modal-default-button btn-close"
                              @click="closeModal(j)"
                            ></button>
                            <p>{{j.img }}</p>
                          </div>
                          <img :key="j.id" :src="'images/envelopes/' + j.img" class="img-modal">
                        </div>
                      </div>
                    </Transition>
                    <!-- modal -->
                  </div>
                </div>
              </div>

              <!-- display -->
              <div class="col-12" v-else :key="i.id" >
                <div class="text-end">
                  <button @click.prevent="editEnvelope(i)" class="btn btn-outline-dark btn-sm" :class="{'disabled':isEditing}">編輯</button>
                </div>
                <h5>
                  【{{ i.country_from ? countries[i.country_from-1].name : '' }}, {{ i.country_from ? countries[i.country_from-1].c_name : '' }} >> {{ i.country_to ? countries[i.country_to-1].name : '未寄出' }} {{ i.country_to ? `, ${countries[i.country_to-1].c_name}` : '' }}】 
                </h5>
                <p class="mx-2">【{{ i.city_from}} ({{i.zip_from }}) >> {{ i.city_to }} ({{ i.zip_to }})】<br>
                  【{{i.id}}】【{{ i.theme }}】【{{ i.type ? types[i.type-1].type : '' }}】【郵戳年份: {{ i.time }}】 
                </p>
                <!-- 
                  <p class="mx-3">{{ i.remark }}</p>
                -->
                <p class="mx-3" v-html="i.remark"></p>
                <!-- <a v-for="j in i.img" :href="'images/envelopes/' + j.img" target="blank">
                  <img :key="j.id" :src="'images/envelopes/' + j.img" class="img-fluid mx-3">
                </a> -->
                <div class="row">
                  <div v-for="(j, index) in i.img" :key="j.id" class="col-4">
                    <div>        
                      <img :src="'images/envelopes/' + j.img" @click="showModal(j, index)" class="img-fluid mx-3 img-display">
                    </div>
                    <!-- << modal -->
                    <Transition name="modal" >
                      <div v-if="j.display" :key=index class="modal-mask" @click="closeModal(j)">
                        <div class="modal-container" @click.stop>
                          <div>
                            <button
                              class="modal-default-button btn-close"
                              @click="closeModal(j)"
                            ></button>
                            <p>{{ currentImage.img }}</p>
                          </div>
                          <button @click="pre(i)" class="btn btn-sm bg-secondary text-white"><</button>
                          <img :key="j.id" :src="'images/envelopes/' + currentImage.img" class="img-modal">
                          <button @click="next(i)" class="btn btn-sm bg-secondary text-white">></button>
                        </div>
                      </div>
                    </Transition>
                    <!-- modal >> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>


</template>

<script setup>
import { ref, onMounted, watch, reactive } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/AuthStore.js';
import TinycmeEditor from './tinymce.vue';

const authStore = useAuthStore();

const envelopes = ref([]);
const countries = ref([]);
const types = ref([]);
const id = ref('');
const sendCountry = ref('');
const sendCity = ref('');
const sendZip = ref('');
const toCountry = ref('');
const toCity = ref('');
const toZip = ref('');
const sendTime = ref('');
const type = ref('');
const remark = ref('');
const theme = ref('');
const file1 = ref(null);
const file2 = ref(null);
const file3 = ref(null);

const message = ref('輸入查詢條件');
const isLoading = ref(false);
const success = ref(false);

let urlSource = 'http://localhost:8888/git-files/collector_vue/collector_vue';
urlSource = '.';

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

// get type
async function getType() {
  try{
    const url = `${urlSource}/controllers/getType.php`;
    const response = await axios.get(url);
    if (response.data.success) {
      types.value = response.data.typeData;
    } else {
      message.value = response.data.message;
    }
  } catch(error){
    message.value = error.message;
  }
}

onMounted(() => {
  getCountry();
  getType();
})

// check envelope
async function checkEnvelope(limit) {
  let queryUrl = '';
  // console.log('===== query begin =====')
  try {
    isLoading.value = true;

    if (limit == 0) {
      queryUrl = `${urlSource}/controllers/checkEnvelope.php?id=${id.value}&country=${sendCountry.value}&year=${sendTime.value}&limit=0`;
    } else if (limit == 1) {
      queryUrl = `${urlSource}/controllers/checkEnvelope.php?id=&country=&year=&limit=1`;
    }

    const response = await axios.get(queryUrl);
  
    if (response.data.success) {
      // console.log('success')
      message.value = response.data.message;
      envelopes.value = response.data.envelopeData.map(envelope => ({
          ...envelope, edit: false,
        }));
      success.value = true;
    } else {
      // console.log('failed');
      envelopes.value = [];
      message.value = response.data.message;
      success.value = false;
    }
      
  } catch(error) {
      message.value = error.message + ': ' + error.response.data.message;
      success.value = false;
      console.error('error: ', error.message + ': ' + error.response.data.message);
  } finally {
    isLoading.value = false;
    isEditing.value = false;
  }
};

// reset 
function reset () {
  // isLoading.value = false;
  // isEditing.value = false;
  sendCountry.value = '';
  sendTime.value = '';
  id.value = '';
  sendCity.value = '';
  sendZip.value = '';
  toCountry.value = '';
  toCity.value = '';
  toZip.value = '';
  type.value = '';
  remark.value = '';
  theme.value = '';
  file1.value.value = '';
  file2.value.value = '';
  file3.value.value = '';
}





// add envelope

async function addEnvelope() {
  if (sendCountry.value !== '' && type.value !== '') {
    isLoading.value = true;
  
    const formData = new FormData();
    formData.append('sendCountry', sendCountry.value,);
    formData.append('sendCity', sendCity.value);
    formData.append('sendZip', sendZip.value);
    formData.append('toCountry', toCountry.value,);
    formData.append('toCity', toCity.value);
    formData.append('toZip', toZip.value);
    formData.append('remark', remark.value);
    formData.append('sendTime', sendTime.value);
    formData.append('theme', theme.value);
    formData.append('type', type.value);
    formData.append('image1', file1.value.files[0]);
    formData.append('image2', file2.value.files[0]);
    formData.append('image3', file3.value.files[0]);
    // console.log('added formData: ', formData);
    try {
      const response = await axios.post(`${urlSource}/controllers/addEnvelope.php`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      if (response.data.success) {
        message.value = response.data.message;

        if (response.data.envelopeData) {
          envelopes.value = response.data.envelopeData.map(envelope => ({
              ...envelope, edit: false
            }));
        } else {
          envelopes.value = [];
        }
        success.value = true;
        console.log('success');
      } else {
        success.value = false;
        message.value = response.data.message;
        console.log('failed')
      }
    } catch(error) {
      message.value = error.message;
      success.value = false;
      console.error('error: ', error.message);
    } finally {
      checkEnvelope(1);
    }
  } else {
    success.value = false,
    message.value = '請輸入寄出國家及郵封類型'
  }
}

// edit envelope
const isEditing = ref(false);
const newSendCountry = ref('');
const newSendCity = ref('');
const newSendZip = ref('');
const newToCountry = ref('');
const newToCity = ref('');
const newToZip = ref('');
const newSendTime = ref('');
const newTheme = ref('');
const newType = ref('');
const newRemark = ref('');
const newImage1 = ref('');
const newImage2 = ref('');
const newImage3 = ref('');

// const newFile1 = ref(null);
// const newFile2 = ref(null);
// const newFile3 = ref(null);

function selectNewFile1(event) {
  newImage1.value = event.target.files[0];
}
function selectNewFile2(event) {
  newImage2.value = event.target.files[0];
}
function selectNewFile3(event) {
  newImage3.value = event.target.files[0];
}

function editEnvelope(envelope) {
  // console.log(stamp);

  envelope.edit = true;
  isEditing.value = true;
  newSendCountry.value = envelope.country_from;
  newSendCity.value = envelope.city_from;
  newSendZip.value = envelope.zip_from;
  newToCountry.value = envelope.country_to;
  newToCity.value = envelope.city_to;
  newToZip.value = envelope.zip_to;
  newSendTime.value = envelope.time;
  newTheme.value = envelope.theme;
  newType.value = envelope.type;
  newRemark.value = envelope.remark;
}

function cancelEdit(envelope) {
  envelope.edit = false;
  isEditing.value = false;
}

async function delImage(envelope, image) {
  if(confirm('刪除圖片？')) {

    try {
      const response = await axios.delete(`${urlSource}/controllers/delImage.php/img/${image.id}` )
      if (response.data.success) {
        
        message.value = '圖片已刪除';
        isEditing.value = false;
        envelope.edit = true;
      }
    } catch (error) {
      console.error(error)
    } finally {
      checkEnvelope(0)
    }
  }
}

async function updateEnvelope(envelope) {
  isLoading.value = true;
  envelope.edit = false;
  isEditing.value = false;
  id.value = envelope.id;
  sendCountry.value = '';
  sendTime.value = '';

  const formData = new FormData();
  formData.append('id', envelope.id);
  formData.append('newSendCountry', newSendCountry.value,);
  formData.append('newSendCity', newSendCity.value,);
  formData.append('newSendZip', newSendZip.value,);
  formData.append('newToCountry', newToCountry.value,);
  formData.append('newToCity', newToCity.value,);
  formData.append('newToZip', newToZip.value,);
  formData.append('newSendTime', newSendTime.value);
  formData.append('newType', newType.value);
  formData.append('newTheme', newTheme.value);
  formData.append('newRemark', newRemark.value);
  formData.append('newImage1', newImage1.value);
  formData.append('newImage2', newImage2.value);
  formData.append('newImage3', newImage3.value);
  // console.log('updated formData: ', formData);

  try {
    const response = await axios.post(`${urlSource}/controllers/updateEnvelope.php`, formData, {

      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    if (response.data.success) {
      // envelopes.value = response.data.envelopeData;
      message.value = response.data.message;
      success.value = true;
      console.log(response.data.message);
    } else {
      message.value = response.data.message;
      success.value = false;
      console.log(response.data.message)
    }
    
  } catch (error) {
    console.error('error: ', error);
    success.value = false;
  } finally {
    isLoading.value = false;
    resetUpdate();
    checkEnvelope(0);
  }
}

function resetUpdate () {
  isLoading.value = false;
  isEditing.value = false;
  newSendCountry.value = '';
  newType.value = '';
  newSendCountry.value = '';
  newSendCity.value = '';
  newSendZip.value = '';
  newToCountry.value = '';
  newToCity.value = '';
  newToZip.value = '';
  newRemark.value = '';
  newSendTime.value = '';
  newTheme.value = '';
  newImage1.value = '';
  newImage2.value = '';
  newImage3.value = '';
  // newFile1.value.value = '';
  // newFile2.value.value = '';
  // newFile3.value.value = '';
}

// Modal
const currentIndex = ref('');
const currentImage = ref(null);

function showModal(image, index) {
  currentIndex.value = index;
  currentImage.value = image;
  image.display = true;
}

function closeModal(image) {
  image.display = false;
}

function pre(image) {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  } else {
    currentIndex.value = image.img.length - 1;
  }
  currentImage.value = image.img[currentIndex.value];
}

function next(image) {
  if (currentIndex.value < image.img.length - 1) {
    currentIndex.value++;
  } else {
    currentIndex.value = 0;
  }
  currentImage.value = image.img[currentIndex.value];
}



</script>

<style scoped>
* {
  /* border: 1px solid black; */
  word-wrap:break-word;
}

body {
  background-color: #ffffff;
}

img {
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.38);
  transition: 0.2s;
}

img:hover {
  scale: 1.05;
  transition: 0.3s;
}

.img-thumb {
  max-height: 200px;
}

.img-display {
  cursor: pointer;
  max-height: 300px;
}

.img-modal {
  max-height: 90vh;
  max-width: 95%;
}

.img-modal:hover {
  scale: 1;
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
  max-height: 99vh;
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

/* mce */
.tox-notifications-container{
    display:none !important;
}

</style>