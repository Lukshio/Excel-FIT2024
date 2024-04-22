import api from '@/api/api'

const ENDPOINT = '/'


export default {
  async getTemperatureLogs () {
    try {
      const response = await api.get(ENDPOINT + 'temperature-log')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getOperationalMod () {
    try {
      const response = await api.get(ENDPOINT + 'heating-mode')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateOperationalMode (data) {
    try {
      const response = await api.put(ENDPOINT + 'heating-mode', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getElHeat () {
    try {
      const response = await api.get(ENDPOINT + 'el-heating')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateElHeat (data) {
    try {
      const response = await api.put(ENDPOINT + 'el-heating', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getHeatingModes () {
    try {
      const response = await api.get(ENDPOINT + 'heating-mode/all')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
}
