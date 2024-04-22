import zoneApi from '@/api/zoneApi'
import { defineStore } from 'pinia'
import router from '@/router'
import { deviceStore } from '@/main'
import emitter from '@/plugins/eventBus'

const typeMap = {
  empty_zone: 'blue-grey-lighten-4',
  heating_zone: 'heatingZone',
  boiler: 'boilerZone',
  water_switch: 'waterZone'
}

const iconMap = {
  empty_zone: 'mdi-border-none-variant',
  heating_zone: 'mdi-radiator',
  boiler: 'mdi-heating-coil',
  water_switch: 'mdi-water'
}

export const useZoneStore = defineStore({
  id: 'zone',
  state: () => ({
    zones: {
      loading: false,
      cached: false,
      data: []
    },
    currentTemps: {
      loading: false,
      cached: false,
      data: []
    },
    zoneTypes: {
      loading: false,
      cached: false,
      data: []
    }
  }),
  getters: {
    storeZoneTypes (state) {
      if (!state.zoneTypes.cached) this.getZoneTypes()
      return state.zoneTypes.data
    },
    storeAllZones (state) {
      if (!state.zoneTypes.cached) this.getZoneTypes()
      if (!state.zones.cached) this.getAllZones()
      return state.zones.data.map(zone => ({
        ...zone,
        color: typeMap[zone.zoneType.name] || 'gray',
        icon: iconMap[zone.zoneType.name] || 'gray',
        relayName: zone.relay?.name || ''
      }))
    },
    storeSingleZone: (state) => (id) => {
      // if (state.zones.length === 0) this.getAllZones()
      return state.zones.data.find((zone) => zone.id === id)
    },
    storeCurrentTemps (state) {
      if (!state.currentTemps.cached) this.getCurrentTemps()
      return state.currentTemps.data.reduce((obj, item) => {
        obj[item.uuid] = item
        return obj
      }, {})
    }
  },
  actions: {
    resetZones () {
      this.zones.cached = false
      deviceStore.resetActiveRelays()
    },
    async insertNewZone (data) {
      await zoneApi.insertZone(data)
        .then(() => {
          this.resetZones()
          this.currentTemps.data = []
          router.push({ name: 'zoneIndex' })
        })
    },
    async getZoneTypes () {
      this.zoneTypes.data = await zoneApi.getZoneTypes()
      this.zoneTypes.cached = true
    },
    switchAutoHeating (data) {
      data = {
        ...data,
        autoHeating: +!data.autoHeating
      }
      this.updateZone(data).then(() => {
        this.resetZones()
        emitter.emit('saved')
      })
    },
    async updateZone (data) {
      if (typeof data.relayDeviceId === 'object' && data.relayDeviceId !== null) {
        data = {
          ...data,
          relayDeviceId: data.relayDeviceId.id
        }
      }
      await zoneApi.updateZone(data)
        .then(() => {
          this.resetZones()
          // this.currentTemps.cached = false
        })
    },
    async getAllZones () {
      this.zones.data = await zoneApi.getZonesNew()
      this.zones.cached = true
    },
    async getCurrentTemps () {
      this.currentTemps.data = []
      this.currentTemps.data = await zoneApi.getCurrentTemps()
      console.log(this.currentTemps.data)
      this.currentTemps.cached = true
    },
    async deleteZone (id) {
      await zoneApi.deleteZone(id)
      this.resetZones()
    }
  }
})
