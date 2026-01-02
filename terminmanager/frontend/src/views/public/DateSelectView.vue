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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { availabilityAPI } from '../../services/api'
import CalendarMonth from '../../components/booking/CalendarMonth.vue'

const router = useRouter()
const bookingStore = useBookingStore()

const currentDate = new Date()
const currentYear = ref(currentDate.getFullYear())
const currentMonth = ref(currentDate.getMonth() + 1)
const monthAvailability = ref([])

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

onMounted(() => {
  if (bookingStore.selectedServices.length === 0) {
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
  background-color: #fff34f;
}

.legend-color--blocked {
  background-color: #767676;
}
</style>
