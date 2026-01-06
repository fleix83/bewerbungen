<template>
  <div class="screen">
    <h1 class="screen-title">Datum auswählen</h1>

    <CalendarMonth
      :year="currentYear"
      :month="currentMonth"
      :availability="monthAvailability"
      @day-selected="handleDaySelected"
      @prev-month="goToPreviousMonth"
      @next-month="goToNextMonth"
    />

    <div class="legend">
      <div class="legend-item">
        <div class="legend-color legend-color--free"></div>
        <span>Freie Termine</span>
      </div>
      <div class="legend-item">
        <div class="legend-color legend-color--blocked"></div>
        <span>belegt</span>
      </div>
    </div>

    <!-- Admin-only: Generate slots button -->
    <div v-if="isAdmin" class="admin-actions">
      <button
        class="btn-primary btn-generate"
        :disabled="generating"
        @click="generateFreeSlots"
      >
        {{ generating ? 'Generiere...' : 'Freie Slots generieren' }}
      </button>
      <p v-if="generateResult" class="generate-result">
        {{ generateResult }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { availabilityAPI, slotsAPI } from '../../services/api'
import CalendarMonth from '../../components/booking/CalendarMonth.vue'

const router = useRouter()
const route = useRoute()
const bookingStore = useBookingStore()

const currentDate = new Date()
const currentYear = ref(currentDate.getFullYear())
const currentMonth = ref(currentDate.getMonth() + 1)
const monthAvailability = ref([])

// Admin mode
const isAdmin = ref(false)
const generating = ref(false)
const generateResult = ref('')

const loadMonthAvailability = async () => {
  try {
    const response = await availabilityAPI.getMonthAvailability(
      currentYear.value,
      currentMonth.value
    )
    monthAvailability.value = response.data
  } catch (error) {
    console.error('Failed to load availability:', error)
    monthAvailability.value = []
  }
}

const handleDaySelected = (dateString) => {
  bookingStore.setSelectedDate(dateString)
  router.push(`/buchen/zeit/${dateString}`)
}

const goToPreviousMonth = () => {
  if (currentMonth.value === 1) {
    currentMonth.value = 12
    currentYear.value--
  } else {
    currentMonth.value--
  }
  loadMonthAvailability()
}

const goToNextMonth = () => {
  if (currentMonth.value === 12) {
    currentMonth.value = 1
    currentYear.value++
  } else {
    currentMonth.value++
  }
  loadMonthAvailability()
}

const generateFreeSlots = async () => {
  generating.value = true
  generateResult.value = ''
  try {
    const response = await slotsAPI.generate(3)
    generateResult.value = `${response.data.slots_created} Slots wurden für ${response.data.days_processed} Tage generiert!`
    await loadMonthAvailability()
  } catch (error) {
    console.error('Failed to generate slots:', error)
    generateResult.value = 'Fehler beim Generieren der Slots'
  } finally {
    generating.value = false
  }
}

onMounted(() => {
  // Check for admin mode
  isAdmin.value = route.query.admin === 'true'

  // Skip service check in admin mode
  // Allow either checkbox services OR dropdown service type
  const hasService = bookingStore.selectedServices.length > 0 ||
    (bookingStore.customerData.serviceType && bookingStore.customerData.serviceType !== '')
  if (!isAdmin.value && !hasService) {
    router.push('/buchen')
    return
  }
  loadMonthAvailability()
})
</script>

<style scoped>
.legend {
  display: flex;
  justify-content: center;
  gap: var(--spacing-lg);
  margin-top: var(--spacing-lg);
  background: white;
  padding: 20px 10px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  font-size: 14px;
  color: var(--color-text-body);
}

.legend-color {
  width: 24px;
  height: 24px;
  border-radius: 4px;
}

.legend-color--free {
  background-color: #fff8e7;
}

.legend-color--blocked {
  background-color: #767676;
}

.admin-actions {
  margin-top: var(--spacing-xl);
  text-align: center;
  padding: var(--spacing-lg);
  background-color: var(--color-bg-card);
  border-radius: 8px;
  border: 2px dashed var(--color-text-title);
}

.btn-generate {
  width: auto;
  padding: 14px 24px;
}

.generate-result {
  margin-top: var(--spacing-md);
  font-size: 14px;
  color: var(--color-text-title);
  font-weight: 600;
}
</style>
