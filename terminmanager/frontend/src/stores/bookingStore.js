import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useBookingStore = defineStore('booking', () => {
  const selectedServices = ref([])
  const customerData = ref({
    salutation: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    notes: '',
    serviceType: ''
  })
  const selectedDate = ref(null)
  const selectedSlot = ref(null)
  const bookingConfirmation = ref(null)

  function setServices(services) {
    selectedServices.value = services
  }

  function setCustomerData(data) {
    customerData.value = { ...customerData.value, ...data }
  }

  function setSelectedDate(date) {
    selectedDate.value = date
  }

  function setSelectedSlot(slot) {
    selectedSlot.value = slot
  }

  function setBookingConfirmation(confirmation) {
    bookingConfirmation.value = confirmation
  }

  function markCancelled() {
    if (bookingConfirmation.value) {
      bookingConfirmation.value.cancelled = true
    }
  }

  function resetBooking() {
    selectedServices.value = []
    customerData.value = {
      salutation: '',
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      notes: '',
      serviceType: ''
    }
    selectedDate.value = null
    selectedSlot.value = null
    bookingConfirmation.value = null
  }

  return {
    selectedServices,
    customerData,
    selectedDate,
    selectedSlot,
    bookingConfirmation,
    setServices,
    setCustomerData,
    setSelectedDate,
    setSelectedSlot,
    setBookingConfirmation,
    markCancelled,
    resetBooking
  }
})
