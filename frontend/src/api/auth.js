import api from '@/api/api'

const END_POINT = '/'

export default {
  async loginSubmit (data) {
    try {
      api.get('/sanctum/csrf-cookie').then(response => {
        // Login...
      })
      const response = await api.post(END_POINT + 'login', data)
      return response.data
    } catch (e) {
      console.error(e)
      throw new Error('FAILED ' + e)
    }
  }
}
