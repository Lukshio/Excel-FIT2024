import api from '@/api/api'

const END_POINT = '/user'

export default {
  async insertUserAPI (data) {
    try {
      await api.post(END_POINT, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },
  async updateUserAPI (data) {
    try {
      // console.log(data);
      await api.put(END_POINT + '/' + data.id, data)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },

  async getUsersAPI () {
    try {
      const response = await api.get(END_POINT)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  },

  async deleteUserAPI (data) {
    try {
      await api.delete(END_POINT + '/' + data.id)
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
