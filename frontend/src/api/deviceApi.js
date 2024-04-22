import api from '@/api/api'

const DEVICE_ENDPOINT = '/device'
const DEVICE_TYPES_ENDPOINT = '/device-types'
const PROTOCOLS_ENDPOINT = '/protocols'

export default {
  async getDevices () {
    try {
      const response = await api.get(DEVICE_ENDPOINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getDeviceTypes () {
    try {
      const response = await api.get(DEVICE_TYPES_ENDPOINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async findDS18B20 () {
    try {
      const response = await api.get(DEVICE_ENDPOINT + '/find/DS18B20')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async deleteDevice (id) {
    try {
      await api.delete(DEVICE_ENDPOINT + '/' + id)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateDevice (data) {
    try {
      await api.put(DEVICE_ENDPOINT, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getProtocols () {
    try {
      const response = await api.get(PROTOCOLS_ENDPOINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async insertDevice (data) {
    try {
      await api.post(DEVICE_ENDPOINT, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getActiveRelays () {
    try {
      const response = await api.get(DEVICE_ENDPOINT + '/relays')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
