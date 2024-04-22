import api from '@/api/api'

const ENDPOINT = '/gpio'
export default {
  async getGpioStatus (data) {
    try {
      const response = await api.post(ENDPOINT + '/status', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async switchRelay (relay) {
    try {
      await api.post(ENDPOINT + '/relay/switch', relay)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
