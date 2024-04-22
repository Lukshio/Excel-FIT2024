import deviceApi from '@/api/deviceApi'
import { defineStore } from 'pinia'
import router from '@/router'
import { zoneStore } from '@/main'
import emitter from '@/plugins/eventBus'

const typeMap = {
  relay: 'relay',
  temperature_sensor: 'sensor'
}

export const useDeviceStore = defineStore({
  id: 'device',
  state: () => ({
    deviceTypes: {
      loading: false,
      cached: false,
      data: []
    },
    devices: {
      loading: false,
      cached: false,
      data: []
    },
    protocols: {
      loading: false,
      cached: false,
      data: []
    },
    activeRelays: {
      loading: false,
      cached: false,
      data: []
    }
  }),
  getters: {
    storeFindDeviceType: (state) => (id) => {
      return state.deviceTypes.data.find((device) => device.id === id)
    },
    storeFindProtocolType: (state) => (id) => {
      return state.protocols.data.find((device) => device.id === id)
    },
    storeSingleDevice: (state) => (id) => {
      return state.devices.data.find((device) => device.id === id)
    },
    storeSensors (state) {
      if (!state.devices.cached) this.getDevices()
      return state.devices.data.filter(device => device.deviceType.name === 'temperature_sensor')
    },
    storeRelays (state) {
      if (!state.devices.cached) this.getDevices()
      return state.devices.data.filter(device => device.deviceType.name === 'relay')
    },
    storeDeviceTypes (state) {
      if (!state.deviceTypes.cached) this.getDeviceTypes()
      return state.deviceTypes.data
    },
    storeDevices (state) {
      if (!state.devices.cached) this.getDevices()
      return state.devices.data.map(Device => ({
        ...Device,
        color: typeMap[Device.deviceType.name] || 'gray'
      }))
    },
    storeProtocols (state) {
      if (!state.protocols.cached) this.getProtocols()
      return state.protocols.data
    },
    storeActiveRelays (state) {
      if (!state.activeRelays.cached) this.getActiveRelays()
      return state.activeRelays.data
    },
    storeFreeSensors (state) {
      if (!state.devices.cached) this.getDevices()
      const used = zoneStore.storeAllZones.map(zone => zone.mainSensorUuid)
      return state.devices.data.filter(device =>
        device.deviceType.name === 'temperature_sensor' &&
        !used.includes(device.uuid) &&
        device.heatingWaterSensor === 0
      )
    },
    storeFreeRelays (state) {
      if (!state.devices.cached) this.getDevices()
      const used = zoneStore.storeAllZones.map(zone => +zone.relayDeviceId)
      return state.devices.data.filter(device =>
        device.deviceType.name === 'relay' &&
        !used.includes(device.id) &&
        device.heatingSource === 0
      )
    },
    storeHeatingWaterSensorPresent (state) {
      if (!state.devices.cached) this.getDevices()
      const tempS = state.devices.data.filter(device =>
        device.deviceType.name === 'temperature_sensor' && device.heatingWaterSensor === 1
      )
      return !!tempS.length
    },
    storeOverHeatPresent (state) {
      if (!state.devices.cached) this.getDevices()
      const tempS = state.devices.data.filter(device =>
        device.deviceType.name === 'temperature_sensor' && device.overheatProtection === 1
      )
      return !!tempS.length
    },
    storeBoilerRelayPresent (state) {
      if (!state.devices.cached) this.getDevices()
      const tempS = state.devices.data.filter(device =>
        device.deviceType.name === 'relay' && device.heatingSource === 1
      )
      return !!tempS.length
    }
  },
  actions: {
    resetActiveRelays () {
      this.activeRelays.cached = false
    },
    async insertDevice (data) {
      await deviceApi.insertDevice(data)
        .then(() => {
          this.devices.cached = false
          router.push({ name: 'deviceIndex' })
        })
    },
    async getDevices () {
      let data = await deviceApi.getDevices()
      if (!this.deviceTypes.cached) await this.getDeviceTypes()
      data = data.map(device => ({
        ...device,
        type: this.deviceTypes.data.find(type => type.id === device.deviceTypeId),
        heatingSource: +device.heatingSource,
        heatingWaterSensor: +device.heatingWaterSensor,
        overheatProtection: +device.overheatProtection,
        fireplaceOpen: +device.fireplaceOpen
      }))
      this.devices.data = data
      this.devices.cached = true
    },
    async getDeviceTypes () {
      this.deviceTypes.data = await deviceApi.getDeviceTypes()
      this.deviceTypes.cached = true
    },
    async getProtocols () {
      this.protocols.data = await deviceApi.getProtocols()
      this.protocols.cached = true
    },
    async deleteDevice (id) {
      await deviceApi.deleteDevice(id)
        .catch((e) => {
          emitter.emit('general-error', 'Error deleting device, maybe in use')
          throw new Error()
        })
      this.devices.cached = false
      router.go(0)
    },
    async updateDevice (data) {
      await deviceApi.updateDevice(data)
        .catch(() => {
          emitter.emit('general-error', 'Chyba')
          throw new Error()
        })
      this.devices.cached = false
    },
    async getActiveRelays () {
      const data = await deviceApi.getActiveRelays()
      data.map(relay => {
        return {
          ...relay,
          disabled: !!relay.zoneRelay?.autoHeating || false
        }
      })
      this.activeRelays.data = data
      this.activeRelays.cached = true
      return data
    }
  }
})
