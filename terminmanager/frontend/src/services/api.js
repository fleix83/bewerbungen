import axios from 'axios'

// Derive the API base from the current page path so the same dist/ works at
// both production root (https://site/buchen/...) and local XAMPP under a
// subdir (http://localhost/bewerbungen/buchen/...). The SPA is always mounted
// at /<prefix>/buchen and the API at /<prefix>/terminmanager/api, where
// <prefix> is empty in production. An explicit VITE_API_BASE_URL env still
// wins (handy for the Vite dev server's proxy).
function deriveApiBase() {
  if (typeof window === 'undefined') return '/terminmanager/api'
  const idx = window.location.pathname.indexOf('/buchen')
  const prefix = idx > 0 ? window.location.pathname.slice(0, idx) : ''
  return prefix + '/terminmanager/api'
}
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || deriveApiBase()

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  },
  withCredentials: true
})

export const servicesAPI = {
  getAll() {
    return api.get('/services.php')
  }
}

export const availabilityAPI = {
  getMonthAvailability(year, month) {
    return api.get(`/availability.php?type=month&year=${year}&month=${month}`)
  },
  getDaySlots(date, slotsNeeded = 1) {
    return api.get(`/availability.php?type=day&date=${date}&slots=${slotsNeeded}`)
  }
}

export const bookingsAPI = {
  create(bookingData) {
    return api.post('/bookings.php', bookingData)
  },
  getById(id) {
    return api.get(`/bookings.php?id=${id}`)
  },
  getByToken(token) {
    return api.get(`/bookings.php?token=${encodeURIComponent(token)}`)
  }
}

export const customersAPI = {
  getAll() {
    return api.get('/customers.php')
  },
  getById(id) {
    return api.get(`/customers.php?id=${id}`)
  }
}

export const appointmentsAPI = {
  getById(id) {
    return api.get(`/appointments.php?id=${id}`)
  },
  updateStatus(id, status) {
    return api.put(`/appointments.php?id=${id}`, { status })
  }
}

export const cancellationsAPI = {
  cancel({ token, eventId, email }) {
    const payload = token
      ? { cancellation_token: token }
      : { event_id: eventId, customer_email: email }
    return api.post('/cancellations.php', payload)
  }
}

export const slotsAPI = {
  getByDate(date) {
    return api.get(`/slots.php?date=${date}`)
  },
  toggle(date, slotHour) {
    return api.post('/slots.php', {
      action: 'toggle',
      date: date,
      slot_hour: slotHour
    })
  }
}

export const authAPI = {
  login(username, password) {
    return api.post('/auth.php', { username, password })
  },
  check() {
    return api.get('/auth.php')
  },
  logout() {
    return api.delete('/auth.php')
  }
}

export default api
