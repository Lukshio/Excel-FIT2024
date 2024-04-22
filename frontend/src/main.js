/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Components
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueApexCharts from 'vue3-apexcharts'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

// Plugins
import { registerPlugins } from '@/plugins'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import { useUserStore } from '@/stores/userStore'
import emitter from '@/plugins/eventBus'
import { useZoneStore } from '@/stores/zoneStore'
import { useDeviceStore } from '@/stores/deviceStore'
import { useManualStore } from '@/stores/manualStore'
import { useGlobalStore } from '@/stores/globalStore'
import { useHeatingStore } from '@/stores/heatingStore'

// Composables
const app = createApp(App)
const pinia = createPinia()
app.use(pinia)

// event bus
app.config.globalProperties.emitter = emitter

// Init stores
export const userStore = useUserStore()
export const deviceStore = useDeviceStore()
export const zoneStore = useZoneStore()
export const manualStore = useManualStore()
export const globalStore = useGlobalStore()
export const heatingStore = useHeatingStore()

// Local storage acces tokens
const userData = JSON.parse(localStorage.getItem('userData'))
if (!userStore.token && userData) userStore.refreshUserData(userData)

const uiData = JSON.parse(localStorage.getItem('uiData'))
console.log(uiData)
if (uiData !== null) globalStore.refreshUIData(uiData)

app.component('VueDatePicker', VueDatePicker)
app.use(VueApexCharts)
// eslint-disable-next-line vue/multi-word-component-names
app.component('apexchart', VueApexCharts)
app.use(VueAxios, axios)
app.use(router)
app.use(VueApexCharts)
registerPlugins(app)

app.mount('#app')
