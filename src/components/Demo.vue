<template>
  <tinycme-editor v-model="editorData"></tinycme-editor>
</template>

<script setup>
import TinycmeEditor from './tinymce.vue';
import { reactive, ref, watch } from 'vue';

const editorData = ref('<p>Content of the editor.</p>');

watch(editorData, (newValue) => {
  console.log(newValue);
});
</script>
