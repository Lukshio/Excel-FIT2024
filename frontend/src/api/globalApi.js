import api from '@/api/api'
import emitter from '@/plugins/eventBus'

const ENDPOINT = '/'
const ENDPOINT_AH = '/auto-heating-test'

export default {
  async testTempLogs () {
    try {
      await api.post(ENDPOINT + 'temperature-log/test-templog-function')
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async testAutoHeating () {
    try {
      await api.post(ENDPOINT_AH + '/autoheating')
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async testWaterSwitch () {
    try {
      await api.post(ENDPOINT_AH + '/waterswich')
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async testOther () {
    try {
      await api.post(ENDPOINT_AH + '/other')
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async testWaterHeating () {
    try {
      await api.post(ENDPOINT_AH + '/waterheating')
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getSystemLogs () {
    try {
      const response = await api.get(ENDPOINT + 'system-log')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getSettings () {
    try {
      const response = await api.get(ENDPOINT + 'settings')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED' + e)
    }
  },
  async saveSettings (data) {
    try {
      await api.put(ENDPOINT + 'settings', data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED' + e)
    }
  },
  async getGraphData (data) {
    try {
      const response = await api.post(ENDPOINT + 'graphs', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getScheduleData (data) {
    try {
      const response = await api.get(ENDPOINT + 'scheduler/' + data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateScheduleData (data) {
    try {
      const response = await api.put(ENDPOINT + 'scheduler', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
