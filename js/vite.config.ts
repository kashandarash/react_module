import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  build: {
    rollupOptions: {
      output: {
        // Customize JS file names
        entryFileNames: 'main.js',
        assetFileNames: 'main.css',
      },
    },
  },
})
