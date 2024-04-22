import { defineStore } from 'pinia'
import emitter from '@/plugins/eventBus'
import heatingApi from '@/api/heatingApi'
import { globalStore } from '@/main'

export const useHeatingStore = defineStore({
  id: 'heating',
  state: () => ({
    tempLogs: {
      loading: false,
      cached: false,
      data: null
    },
    heatingModes: {
      loading: false,
      cached: false,
      data: []
    },
    operationalMode: {
      loading: false,
      cached: false,
      data: null
    },
    elHeating: {
      loading: false,
      cached: false,
      data: null
    }
  }),
  getters: {
    storeHeatingModes (state) {
      if (!state.heatingModes.cached) this.getHeatingModes()
      return state.heatingModes.data
    },
    storeOperationalMode (state) {
      if (!state.operationalMode.cached) this.getOperationalMode()
      return state.operationalMode.data
    },
    storeElHeating (state) {
      if (!state.elHeating.cached) this.getElHeating()
      return state.elHeating.data
    },
    storeTemperatureLogs (state) {
      if (!state.tempLogs.cached) this.getTemperatureLogs()
      return state.tempLogs.data
    }
  },
  actions: {
    async getHeatingModes () {
      this.heatingModes.data = await heatingApi.getHeatingModes()
      this.heatingModes.cached = true
    },
    async getTemperatureLogs () {
      this.tempLogs.data = await heatingApi.getTemperatureLogs()
      this.tempLogs.cached = true
    },
    async getOperationalMode () {
      this.operationalMode.data = [await heatingApi.getOperationalMod()]
      this.operationalMode.cached = true
    },
    async updateOperationalMode (data) {
      await heatingApi.updateOperationalMode(data)
        .catch((e) => {
          emitter.emit('general-error', 'Error')
          throw new Error(e)
        })
      await globalStore.getSettings()
      this.operationalMode.cached = false
    },
    async getElHeating () {
      this.elHeating.data = await heatingApi.getElHeat()
      this.elHeating.cached = true
    },
    async updateElHeating (data) {
      await heatingApi.updateElHeat(data)
        .catch((e) => {
          emitter.emit('general-error', 'Error')
          throw new Error(e)
        })
      await globalStore.getSettings()
      this.elHeating.cached = false
    }
  }
})
