import { defineStore } from 'pinia'
import auth from '@/api/auth'
import { setAuthHeader } from '@/api/api'
import router from '@/router'
import userApi from '@/api/userApi'
import emitter from '@/plugins/eventBus'

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    userData: {
      accessToken: null,
      role: null
    },
    users: {
      cached: false,
      data: []
    },
    userCached: false
  }),
  getters: {
    token: state => state.userData.accessToken,
    isLogged: (state) => !!state.userData.accessToken,
    storeUsers (state) {
      if (!state.users.cached && state.userData.role === 'admin') this.getUsers()
      return state.users.data
    },
    storeSingleUser: (state) => (id) => {
      // if (state.zones.length === 0) this.getAllZones()
      return state.users.data.find((user) => user.id === id)
    }
  },
  actions: {
    async login (loginForm) {
      try {
        const data = await auth.loginSubmit(loginForm)
        const newData = {
          ...data.user,
          accessToken: data.token
        }
        this.userData = newData
        this.userCached = true
        localStorage.setItem('userData', JSON.stringify(newData))
        setAuthHeader(newData.accessToken)

        await router.push({ name: 'zoneIndex' })
      } catch (error) {
        console.error(error)
      }
    },
    logout () {
      this.userData = {
        accessToken: null
      }
      localStorage.removeItem('userData')
      this.userCached = false

      // router.push({ path: '/login' })
      router.go(0)
    },
    refreshUserData (data) {
      this.userData = data
      this.userCached = true
      localStorage.setItem('userData', JSON.stringify(data))
      setAuthHeader(data.accessToken)

      // if (this.$route !== '/') router.push({ name: 'home' })
    },
    async insertUser (data) {
      await userApi.insertUserAPI(data)
        .then(() => {
          this.users.cached = false
          router.push({ name: 'usersIndex' })
          emitter.emit('saved')
        })
    },
    async updateUser (data) {
      await userApi.updateUserAPI(data)
        .then(() => {
          this.users.cached = false
          router.push({ name: 'usersIndex' })
          emitter.emit('saved')
        })
    },

    async getUsers () {
      this.users.data = await userApi.getUsersAPI()
      this.users.cached = true
    },

    async deleteUser (data) {
      await userApi.deleteUserAPI(data)
        .then(() => {
          this.users.cached = false
          router.push({ name: 'usersIndex' })
        })
    }
  }
})
