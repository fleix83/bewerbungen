import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost/api'

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
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

export default api
