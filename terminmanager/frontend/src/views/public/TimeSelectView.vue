<template>
  <div class="screen">
    <h1 class="screen-title">{{ formattedDate }}</h1>

    <div v-if="loading" class="loading">
      Laden...
    </div>

    <TimeSlotList
      v-else
      :slots="availableSlots"
      @slot-selected="handleSlotSelected"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { availabilityAPI, bookingsAPI } from '../../services/api'
import TimeSlotList from '../../components/booking/TimeSlotList.vue'

const route = useRoute()
const router = useRouter()
const bookingStore = useBookingStore()

const loading = ref(true)
const availableSlots = ref([])
const selectedDate = ref(route.params.datum)

const formattedDate = computed(() => {
  const date = new Date(selectedDate.value)
  return date.toLocaleDateString('de-DE', {
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  }).toUpperCase()
})

const slotsNeeded = computed(() => {
  // Add 1 for "Etwas anderes" service which is always included
  return bookingStore.selectedServices.length + 1
})

const loadAvailableSlots = async () => {
  loading.value = true
  try {
    const response = await availabilityAPI.getDaySlots(
      selectedDate.value,
      slotsNeeded.value
    )
    availableSlots.value = response.data
  } catch (error) {
    console.error('Failed to load slots:', error)
    availableSlots.value = []
  } finally {
    loading.value = false
  }
}

const handleSlotSelected = async (slot) => {
  bookingStore.setSelectedSlot(slot)

  // Always include "Etwas anderes" service (ID: 3)
  const serviceIds = [...bookingStore.selectedServices]
  if (!serviceIds.includes(3)) {
    serviceIds.push(3)
  }

  const bookingData = {
    customer: {
      first_name: bookingStore.customerData.firstName,
      last_name: bookingStore.customerData.lastName,
      email: bookingStore.customerData.email,
      phone: bookingStore.customerData.phone
    },
    event_date: selectedDate.value,
    start_slot: slot.start_slot,
    end_slot: slot.end_slot,
    service_ids: serviceIds,
    notes: bookingStore.customerData.notes
  }

  try {
    const response = await bookingsAPI.create(bookingData)

    if (response.data.success) {
      bookingStore.setBookingConfirmation({
        eventId: response.data.event_id,
        date: selectedDate.value,
        slot: slot,
        services: serviceIds,
        customer: bookingStore.customerData
      })

      router.push('/buchen/bestaetigung')
    } else {
      alert('Buchung fehlgeschlagen: ' + response.data.message)
    }
  } catch (error) {
    console.error('Booking failed:', error)
    alert('Fehler bei der Buchung. Bitte versuchen Sie es erneut.')
  }
}

onMounted(() => {
  if (!bookingStore.selectedDate || bookingStore.selectedServices.length === 0) {
    router.push('/buchen')
    return
  }
  loadAvailableSlots()
})
</script>

<style scoped>
.loading {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
