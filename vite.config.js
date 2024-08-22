import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { createHtmlPlugin } from 'vite-plugin-html';

export default defineConfig({
  plugins: [
    vue(),
    createHtmlPlugin({
      minify: true,
      filename: 'admin.html', // 設置輸出的 HTML 文件名
    }),
  ],
  build: {
    rollupOptions: {
      input: 'index.html', // 輸入 HTML 文件
    },
  },
});
