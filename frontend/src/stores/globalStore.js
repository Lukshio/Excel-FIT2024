import { defineStore } from 'pinia'
import globalApi from '@/api/globalApi'
import emitter from '@/plugins/eventBus'
import { useTheme } from 'vuetify'

export const useGlobalStore = defineStore({
  setup () {
    const theme = useTheme()
    return { theme }
  },
  id: 'global',
  state: () => ({
    systemLogs: {
      loading: false,
      cached: false,
      data: null
    },
    settings: {
      cached: false,
      loading: false,
      data: null
    },
    app: {
      appbarHeading: '',
      button: {
        visible: false,
        routeName: '',
        text: ''
      }
    },
    ui: {
      zoneDetails: false,
      darkTheme: false
    }
  }),
  getters: {
    getAppbarHeading: state => state.app.appbarHeading,
    getIsVisibleButton: state => state.app.button.visible,
    storeSystemLogs (state) {
      if (!state.systemLogs.cached) this.getSystemLogs()
      return state.systemLogs.data
    },
    storeSettings (state) {
      if (!state.settings.cached) this.getSettings()
      return state.settings.data
    },
    getDarkTheme: state => state.ui.darkTheme,
    getVisibleZoneDetails: state => state.ui.zoneDetails
  },
  actions: {
    saveUILocalStorage () {
      localStorage.setItem('uiData', JSON.stringify(this.ui))
    },
    switchZoneDetails () {
      this.ui.zoneDetails = !this.ui.zoneDetails
      this.saveUILocalStorage()
    },
    changeTheme () {
      this.ui.darkTheme = !this.ui.darkTheme
      this.saveUILocalStorage()
    },
    refreshUIData (data) {
      localStorage.setItem('uiData', JSON.stringify(data))
      this.ui = data
    },
    async getSettings () {
      this.settings.data = await globalApi.getSettings()
      this.settings.cached = true
    },
    async saveSettings (data) {
      await globalApi.saveSettings(data)
        .then(() => { emitter.emit('saved') })
    },
    setAppbarHeading (newHeading) {
      this.app.appbarHeading = newHeading
    },
    setAppButton (data) {
      this.app.button = data
    },
    async getSystemLogs () {
      this.systemLogs.data = await globalApi.getSystemLogs()
      this.systemLogs.cached = true
    },
    resetApp () {
      this.app = {
        appbarHeading: '',
        button: {
          visible: false,
          routeName: '',
          text: ''
        }
      }
    }
  }
})
