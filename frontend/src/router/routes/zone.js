export default [
  {
    path: '/zone/:id',
    name: 'zoneDetail',
    component: () => import('../../views/zone/ZoneDetailView.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  },
  {
    path: '/zone',
    name: 'zoneIndex',
    component: () => import('../../views/zone/ZoneIndexView.vue'),
    meta: {
      roles: ['admin', 'user']
    }
  }
]
