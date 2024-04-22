export default [
  {
    path: '/users',
    name: 'usersIndex',
    component: () => import('../../views/users/UsersIndexView.vue'),
    meta: {
      roles: ['admin']
    }
  }
]
