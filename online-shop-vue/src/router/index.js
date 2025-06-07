import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const routes = [
  {
    path: '/admin',
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: '',
        redirect: '/admin/dashboard'
      },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: () => import('@/admin/views/DashboardView.vue'),
      }
    ]
  },
  //Admin Login
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: () => import('@/admin/views/LoginView.vue'),
    meta: { guestOnly: true }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  const needsAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiredRole = to.matched.some(record => record.meta.role)
  const guestOnly = to.matched.some(record => record.meta.guestOnly)
  const isDestinationAdmin = to.path.startsWith('/admin')

  if(guestOnly && authStore.isLoggedIn) {
    return next({ name: 'AdminDashboard' })
  }

  if(!needsAuth) {
    return next()
  }

  if(!authStore.isLoggedIn) {
    if(isDestinationAdmin) {
      return next({ name: 'AdminLogin' })
    }
    //TODO
  }

  if(requiredRole && authStore.user.role !== requiredRole) {
    alert('u are not authorized to access this page')
    return next({ name: 'AdminLogin' })
  }

  next()
})

export default router
