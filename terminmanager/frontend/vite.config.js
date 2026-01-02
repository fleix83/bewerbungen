import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
// Use VITE_BASE_PATH env var, or default to staging path
export default defineConfig({
  plugins: [vue()],
  base: process.env.VITE_BASE_PATH || '/terminmanager/frontend/dist/',
  build: {
    rollupOptions: {
      output: {
        entryFileNames: 'assets/[name].js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name].[ext]'
      }
    }
  }
})
