<template>
  <editor v-model="editorValue" :init="init"></editor>
</template>

<script setup>
import { reactive, ref, watch, toRefs } from 'vue';

import tinymce from 'tinymce/tinymce.js';
import 'tinymce/models/dom'; // (TinyMCE 6)

// 外觀
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/themes/silver';

// Icon
import 'tinymce/icons/default';

// 用到的外掛
import 'tinymce/plugins/link';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/code';

// 語言包
import 'tinymce-i18n/langs5/zh_TW.js';
// import 'tinymce-i18n/langs/zh_Hans.js' (TinyMCE 6 的簡體中文)

// TinyMCE-Vue
import Editor from '@tinymce/tinymce-vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  plugins: {
    type: [String, Array],
    default: 'link autolink code placeholder',
  },
  toolbar: {
    type: [String, Array],
    default:
      ' link bold italic underline strikethrough | blockquote | undo redo | removeformat | code',
  },
});

const emit = defineEmits(['update:modelValue']);

const init = reactive({
  license_key: 'gpl',
  language: 'zh_TW',
  height: 206,
  menubar: false,
  content_css: false,
  skin: false,
  plugins: props.plugins,
  toolbar: props.toolbar,
  quickbars_insert_toolbar: false,
  branding: false,
  // placeholders: [{text: 'Base URL', value: '{{base_url}}'}],
  // placeholder: "Ask a question or post an update...",
});

const { modelValue } = toRefs(props);
const editorValue = ref(modelValue.value);

watch(modelValue, (newValue) => {
  editorValue.value = newValue;
});

watch(editorValue, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>
