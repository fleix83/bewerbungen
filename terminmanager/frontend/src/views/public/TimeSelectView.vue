<template>
  <div class="screen">
    <div class="screen-header">
      <h1 class="screen-title">
        <span class="title-line">{{ weekday }}</span>
        <span class="title-line">{{ dateWithoutWeekday }}</span>
      </h1>
      <a href="#" class="back-link" @click.prevent="goBack">zurück</a>
    </div>

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

const weekday = computed(() => {
  const date = new Date(selectedDate.value)
  return date.toLocaleDateString('de-DE', { weekday: 'long' }).toUpperCase()
})

const dateWithoutWeekday = computed(() => {
  const date = new Date(selectedDate.value)
  return date.toLocaleDateString('de-DE', {
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

  // Only include service ID 3 for dropdown options with price (not "Etwas anderes")
  const serviceIds = [...bookingStore.selectedServices]
  if (bookingStore.customerData.serviceType &&
      bookingStore.customerData.serviceType !== '' &&
      bookingStore.customerData.serviceType !== 'Etwas anderes') {
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
    notes: bookingStore.customerData.notes,
    service_type: bookingStore.customerData.serviceType
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

const goBack = () => {
  router.push('/buchen/datum')
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
.screen-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--spacing-lg);
}

.screen-header .screen-title {
  margin-bottom: 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.title-line {
  background-color: #fff;
  padding: 2px 8px;
  display: inline-block;
}

.back-link {
  color: #002198;
  font-family: var(--font-primary);
  font-size: 14px;
  text-decoration: none;
}

.back-link:hover {
  text-decoration: underline;
}

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
  color: #002198;
  font-size: 14px;
}

.loading {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
