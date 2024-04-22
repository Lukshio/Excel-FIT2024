// Plugins
import vue from '@vitejs/plugin-vue'
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'

// Utilities
import { defineConfig } from 'vite'
import { fileURLToPath, URL } from 'node:url'
import { VitePWA } from 'vite-plugin-pwa'

// https://vitejs.dev/config/
export default defineConfig({
  /// base: '/regulace-krahulci/vuetify-project/dist/',
  // base: '/',
  // publicPath: './',
  plugins: [
    vue({
      template: { transformAssetUrls }
    }),
    // https://github.com/vuetifyjs/vuetify-loader/tree/next/packages/vite-plugin
    vuetify({
      autoImport: true
    }),
    VitePWA({
      workbox: {
        globPatterns: ['**/*.{js,css,html,ico,jpg,png,svg,gif,webmanifest}']
      },
      registerType: 'autoUpdate',
      srcDir: 'src',
      devOptions: {
        enabled: true,
        type: 'module'
      },
      includeAssets: ['*.png'],
      manifest: {
        name: 'Smart Thermostat',
        short_name: 'Regulace 2.0',
        description: 'Custom made smart thermostat',
        theme_color: '#ffffff',
        start_url: '/',
        id: '/',
        icons: [
          {
            src: 'ikona.png',
            sizes: '192x192',
            type: 'image/png'
          }
        ]
      }
    })
  ],
  define: { 'process.env': {} },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
    extensions: [
      '.js',
      '.json',
      '.jsx',
      '.mjs',
      '.ts',
      '.tsx',
      '.vue'
    ]
  },
  server: {
    port: 3000,
    host: true
  }
})
