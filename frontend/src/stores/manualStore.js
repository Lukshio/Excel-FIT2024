import { defineStore } from 'pinia'
import gpioApi from '@/api/gpioApi'

export const useManualStore = defineStore({
  id: 'manual',
  state: () => ({
    gpioState: {
      cached: false,
      data: []
    }
  }),
  getters: {
    storeGpioState (state) {
      if (!state.gpioState.cached) this.getGpioStatus()
      return state.gpioState.data
    }
  },
  actions: {
    async getGpioStatus (pin) {
      const data = await gpioApi.getGpioStatus({ pins: pin })
        .catch(e => {
          throw new Error(e)
        })
      this.gpioState.data = data
      this.gpioState.cached = true
      return data
    },
    async switchRelay (relay) {
      const data = {
        relayId: relay.id,
        newState: +!relay.gpioState
      }
      await gpioApi.switchRelay(data)
    }
  }
})
