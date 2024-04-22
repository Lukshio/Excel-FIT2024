export default [
  {
    path: '/device/:id',
    name: 'deviceDetail',
    component: () => import('../../views/devices/DeviceDetailView.vue'),
    meta: {
      roles: ['admin']
    }
  },
  {
    path: '/device',
    name: 'deviceIndex',
    component: () => import('../../views/devices/DevicesIndexView.vue'),
    meta: {
      roles: ['admin']
    }
  }
]
