// Composables
import { createRouter, createWebHistory } from 'vue-router'
import zone from './routes/zone'
import devices from './routes/devices'
import { globalStore, userStore } from '@/main'
import other from '@/router/routes/other'
import users from '@/router/routes/users'

const routes = other.concat(zone, devices, users)

const routesFinal = [{
  children: routes
}]

const router = createRouter({
  history: createWebHistory(),
  routes: routesFinal
})

router.beforeEach((to, from, next) => {
  globalStore.resetApp()
  if (!userStore.token && to.name !== 'login') {
    next({ name: 'login' })
  } else if (!to.meta.roles.includes(userStore.userData.role)) {
    if (to.name === 'login' && userStore.token) next({ name: 'temperatures' })
    if (to.meta.roles.length === 0) next()
  } else next()
})

export default router
