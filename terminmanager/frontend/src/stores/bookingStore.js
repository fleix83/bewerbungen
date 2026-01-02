import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useBookingStore = defineStore('booking', () => {
  const selectedServices = ref([])
  const customerData = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    notes: '',
    serviceType: 'Etwas anderes'
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

  function resetBooking() {
    selectedServices.value = []
    customerData.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      notes: '',
      serviceType: 'Etwas anderes'
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
    resetBooking
  }
})
