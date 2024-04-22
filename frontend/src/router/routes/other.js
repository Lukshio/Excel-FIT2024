export default [
  {
    path: '/',
    name: 'home',
    component: () => import('../../views/HomeView.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/settings',
    name: 'settings',
    component: () => import('../../views/SettingsView.vue'),
    meta: {
      roles: ['admin']
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../../views/LoginView.vue'),
    meta: {
      roles: []
    }
  },
  {
    path: '/manual',
    name: 'manual',
    component: () => import('../../views/ManualControlView.vue'),
    meta: {
      roles: ['admin']
    }
  },
  {
    path: '/graphs',
    name: 'graphs',
    component: () => import('../../views/GraphsView.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/system-logs',
    name: 'system-logs',
    component: () => import('../../views/SystemLogsView.vue'),
    meta: {
      roles: ['admin']
    }
  },
  {
    path: '/temperature-logs',
    name: 'temperature-logs',
    component: () => import('../../views/TemperatureLogView.vue'),
    meta: {
      roles: ['admin']
    }
  },
  {
    path: '/temperatures',
    name: 'temperatures',
    component: () => import('../../views/TemperaturesView.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/scheduler',
    name: 'scheduler',
    component: () => import('../../views/SchedulerView.vue'),
    meta: {
      roles: ['user', 'admin']
    }
  }
]
