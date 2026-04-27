import axios from 'axios'

// Path-absolute URL works from any SPA route (e.g. /buchen) without depending on URL depth.
// Local XAMPP under /bewerbungen/ should use the Vite dev server (which proxies /terminmanager/api).
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || '/terminmanager/api'

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
  },
  generate(months = 3) {
    return api.post('/slots.php', {
      action: 'generate',
      months: months
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
