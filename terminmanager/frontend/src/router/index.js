import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      redirect: '/buchen'
    },
    {
      path: '/buchen',
      name: 'booking',
      component: () => import('../views/public/BookingView.vue')
    },
    {
      path: '/buchen/datum',
      name: 'date-select',
      component: () => import('../views/public/DateSelectView.vue')
    },
    {
      path: '/buchen/zeit/:datum',
      name: 'time-select',
      component: () => import('../views/public/TimeSelectView.vue')
    },
    {
      path: '/buchen/bestaetigung',
      name: 'confirmation',
      component: () => import('../views/public/ConfirmationView.vue')
    },
    {
      path: '/admin/login',
      name: 'admin-login',
      component: () => import('../views/admin/LoginView.vue')
    },
    {
      path: '/admin/dashboard',
      name: 'admin-dashboard',
      component: () => import('../views/admin/AdminDateSelectView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/termin/:id',
      name: 'admin-appointment',
      component: () => import('../views/admin/AppointmentView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/kunden',
      name: 'admin-customers',
      component: () => import('../views/admin/CustomersView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/kunde/:id',
      name: 'admin-customer-detail',
      component: () => import('../views/admin/CustomerDetailView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/slots',
      name: 'admin-slots',
      component: () => import('../views/admin/SlotManagerView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/admin/slots/:date',
      name: 'admin-slots-date',
      component: () => import('../views/admin/SlotManagerView.vue'),
      meta: { requiresAuth: true }
    }
  ]
})

// Navigation guard for protected routes
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    const authStore = useAuthStore()

    // Check auth status if not yet known
    if (!authStore.isAuthenticated) {
      await authStore.checkAuth()
    }

    if (!authStore.isAuthenticated) {
      next({ name: 'admin-login' })
      return
    }
  }
  next()
})

export default router
