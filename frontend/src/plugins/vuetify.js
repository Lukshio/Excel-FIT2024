/**
 * plugins/vuetify.js
 *
 * FramewaterZone documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Composables
import { createVuetify } from 'vuetify'
import { VDataTable } from 'vuetify/labs/VDataTable'
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n'
import { useI18n } from 'vue-i18n'
import i18n from '@/locales'
// https://vuetifyjs.com/en/introduction/why-vuetify/#feature-guides

export default createVuetify({
  locale: {
    adapter: createVueI18nAdapter({ i18n, useI18n })
  },
  components: {
    VDataTable
  },
  theme: {
    themes: {
      light: {
        colors: {
          background: '#F5F5F5',
          navigation: '#ebf3f4',
          primary: '#fc7c24',
          secondary: '#fc9c4c',
          tertiary: '#3f3434',
          primaryBgGradFirst: '#f68749',
          primaryBgGradSecond: '#ffc536',
          primaryNavbarBgGradFirst: '#FF860D',
          primaryNavbarBgGradSecond: '#E1A200'
        }
      },
      dark: {
        colors: {
          background: '#1E1E1E', // Dark gray background
          navigation: '#2C2C2C', // Darker gray for navigation
          primary: '#FFA259', // Adjusted primary color for dark mode
          secondary: '#FFC369', // Adjusted secondary color for dark mode
          tertiary: '#272727', // Light gray for text on dark background
          primaryBgGradFirst: '#fa7f20', // Adjusted primary gradient color
          primaryBgGradSecond: '#F9CB7B', // Adjusted primary gradient color
          primaryNavbarBgGradFirst: '#E67800', // Adjusted primary navbar gradient color
          primaryNavbarBgGradSecond: '#FFA259' // Adjusted primary navbar gradient color
        }
      },
      custom: {
        colors: {
          background: '#F5F5F5',
          navigation: '#2E3A44',
          primary: '#2196F3',
          secondary: '#F44336',
          tertiary: '#4CAF50'
        }
      }
    }
  },
  icons: {
    defaultSet: 'mdi'
  }
})
