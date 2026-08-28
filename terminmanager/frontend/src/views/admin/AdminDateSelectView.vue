<template>
  <div class="screen">
    <div class="screen-header">
      <h1 class="screen-title">Admin Dashboard</h1>
      <a href="#" class="logout-link" @click.prevent="handleLogout">Abmelden</a>
    </div>

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
import { useAuthStore } from '../../stores/authStore'
import { availabilityAPI } from '../../services/api'
import CalendarMonth from '../../components/booking/CalendarMonth.vue'

const router = useRouter()
const authStore = useAuthStore()

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
  router.push(`/admin/slots/${dateString}`)
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

const handleLogout = async () => {
  await authStore.logout()
  router.push('/admin/login')
}

onMounted(() => {
  loadMonthAvailability()
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
  margin-top: 50px;
}

.logout-link {
  color: #002198;
  font-family: var(--font-primary);
  font-size: 16px;
  text-decoration: underline;
}

.logout-link:hover {
  text-decoration: underline;
}

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

</style>
