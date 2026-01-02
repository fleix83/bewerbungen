import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
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
      path: '/admin/termin/:id',
      name: 'admin-appointment',
      component: () => import('../views/admin/AppointmentView.vue')
    },
    {
      path: '/admin/kunden',
      name: 'admin-customers',
      component: () => import('../views/admin/CustomersView.vue')
    },
    {
      path: '/admin/kunde/:id',
      name: 'admin-customer-detail',
      component: () => import('../views/admin/CustomerDetailView.vue')
    }
  ]
})

export default router
