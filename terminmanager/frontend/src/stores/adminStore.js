import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAdminStore = defineStore('admin', () => {
  const customers = ref([])
  const currentCustomer = ref(null)
  const currentAppointment = ref(null)

  function setCustomers(data) {
    customers.value = data
  }

  function setCurrentCustomer(customer) {
    currentCustomer.value = customer
  }

  function setCurrentAppointment(appointment) {
    currentAppointment.value = appointment
  }

  return {
    customers,
    currentCustomer,
    currentAppointment,
    setCustomers,
    setCurrentCustomer,
    setCurrentAppointment
  }
})
