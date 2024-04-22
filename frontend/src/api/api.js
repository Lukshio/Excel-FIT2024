import axios from 'axios'
import { keysToCamelCase, keysToSnakeCase } from '@/composable/keys'
import emitter from '@/plugins/eventBus'
import { userStore } from '@/main'

const api = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL
})
api.defaults.withCredentials = true
api.defaults.withXSRFToken = true

const debugMode = !import.meta.env.VITE_DEBUG_MODE
export function setAuthHeader (token) {
  api.defaults.headers.common.Authorization = `Bearer ${token}`
}

// Outgoing requests
api.interceptors.request.use(
  (request) => {
    if (!userStore.isLogged && request.url !== '/login') return
    if (request.data) request.data = keysToSnakeCase(request.data)
    return request
  },
  (error) => {
    if (debugMode) emitter.emit('api-error', error, 'error')

    if (error.response.status === 404) console.log('test')
    return Promise.reject(error)
  }
)

// Incoming requests
api.interceptors.response.use(
  (response) => {
    if (response.data) response.data = keysToCamelCase(response.data)
    return response
  },
  (error) => {
    console.error(error)

    if (debugMode) emitter.emit('api-error', error, 'error')

    if (error.response.status === 401 && error.config.url !== '/login') {
      userStore.logout()
    }
    // router.push({ name: 'login' })
    return Promise.reject(error)
  }
)

export default api
