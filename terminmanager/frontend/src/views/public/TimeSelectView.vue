<template>
  <div class="screen">
    <h1 class="screen-title">{{ formattedDate }}</h1>

    <!-- Date Navigation -->
    <div class="date-navigation">
      <button class="nav-button" @click="goToPreviousDay">←</button>
      <span class="nav-label">Datum wechseln</span>
      <button class="nav-button" @click="goToNextDay">→</button>
    </div>

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
  // Count checkbox services + 1 if dropdown has a value
  let count = bookingStore.selectedServices.length
  if (bookingStore.customerData.serviceType && bookingStore.customerData.serviceType !== '') {
    count += 1
  }
  return Math.max(count, 1) // At least 1 slot needed
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

  // Only include "Etwas anderes" service (ID: 3) if dropdown has a specific value
  const serviceIds = [...bookingStore.selectedServices]
  if (bookingStore.customerData.serviceType && bookingStore.customerData.serviceType !== '') {
    if (!serviceIds.includes(3)) {
      serviceIds.push(3)
    }
  }

  const bookingData = {
    customer: {
      salutation: bookingStore.customerData.salutation,
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

const goToPreviousDay = () => {
  const date = new Date(selectedDate.value)
  date.setDate(date.getDate() - 1)
  navigateToDate(date)
}

const goToNextDay = () => {
  const date = new Date(selectedDate.value)
  date.setDate(date.getDate() + 1)
  navigateToDate(date)
}

const navigateToDate = (date) => {
  // Format as YYYY-MM-DD without timezone conversion
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  const dateStr = `${year}-${month}-${day}`

  selectedDate.value = dateStr
  bookingStore.setSelectedDate(dateStr)
  router.push(`/buchen/zeit/${dateStr}`)
  loadAvailableSlots()
}

onMounted(() => {
  // Allow if checkboxes selected OR dropdown has a value
  const hasService = bookingStore.selectedServices.length > 0 ||
    (bookingStore.customerData.serviceType && bookingStore.customerData.serviceType !== '')

  if (!bookingStore.selectedDate || !hasService) {
    router.push('/buchen')
    return
  }
  loadAvailableSlots()
})
</script>

<style scoped>
.date-navigation {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-lg);
}

.nav-button {
  background: none;
  border: none;
  color: var(--color-text-title);
  font-size: 24px;
  cursor: pointer;
  padding: var(--spacing-sm);
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.nav-button:hover {
  background-color: var(--color-bg-card);
}

.nav-label {
  color: var(--color-text-muted);
  font-size: 14px;
}

.loading {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
