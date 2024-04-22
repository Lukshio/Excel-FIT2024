import api from '@/api/api'

const END_POINT = '/zone'
const TEMPERATURE_ENDPOINT = '/temperature'
const ZONE_TYPE_ENDPOINT = '/zone-types'

export default {
  async insertZone (data) {
    try {
      await api.post(END_POINT, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getZonesNew () {
    try {
      const response = await api.get(END_POINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getCurrentTemps () {
    try {
      const response = await api.get(TEMPERATURE_ENDPOINT + '/rt')
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async deleteZone (id) {
    try {
      const response = await api.delete(END_POINT + '/' + id)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateZone (data) {
    try {
      await api.put(END_POINT, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async getZoneTypes () {
    try {
      const response = await api.get(ZONE_TYPE_ENDPOINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
