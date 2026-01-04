<template>
  <div class="screen">
    <h1 class="screen-title">Slot Manager</h1>

    <!-- Date Navigation -->
    <div class="date-navigation">
      <button class="nav-button" @click="goToPreviousDay">←</button>
      <div class="date-display" @click="showDatePicker = true">
        <div class="date-weekday">{{ weekday }}</div>
        <div class="date-full">{{ dayMonthYear }}</div>
      </div>
      <button class="nav-button" @click="goToNextDay">→</button>
    </div>

    <!-- Date Picker Modal -->
    <div v-if="showDatePicker" class="date-picker-modal" @click.self="showDatePicker = false">
      <div class="date-picker-content">
        <CalendarMonth
          :year="pickerYear"
          :month="pickerMonth"
          :availability="[]"
          @day-selected="handleDateSelected"
          @prev-month="prevPickerMonth"
          @next-month="nextPickerMonth"
        />
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading">Laden...</div>

    <!-- Slots List -->
    <div v-else class="slots-list">
      <AdminSlotItem
        v-for="slot in slots"
        :key="slot.slot_hour"
        :slot="slot"
        @toggle="handleToggleSlot"
      />
    </div>

    <!-- No slots message -->
    <div v-if="!loading && slots.length === 0" class="no-slots">
      Keine Slots für dieses Datum verfügbar.
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { slotsAPI } from '../../services/api'
import AdminSlotItem from '../../components/admin/AdminSlotItem.vue'
import CalendarMonth from '../../components/booking/CalendarMonth.vue'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const slots = ref([])
const selectedDate = ref(route.params.date || new Date().toISOString().split('T')[0])
const showDatePicker = ref(false)

// Picker month state (separate from selected date)
const pickerYear = ref(new Date().getFullYear())
const pickerMonth = ref(new Date().getMonth() + 1)

// German weekday and date formatting
const days = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag']
const months = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember']

const weekday = computed(() => {
  const date = new Date(selectedDate.value)
  return days[date.getDay()]
})

const dayMonthYear = computed(() => {
  const date = new Date(selectedDate.value)
  return `${date.getDate()}. ${months[date.getMonth()]} ${date.getFullYear()}`
})

const loadSlots = async () => {
  loading.value = true
  try {
    const response = await slotsAPI.getByDate(selectedDate.value)
    slots.value = response.data.slots
  } catch (error) {
    console.error('Failed to load slots:', error)
    slots.value = []
  } finally {
    loading.value = false
  }
}

const handleToggleSlot = async (slotHour) => {
  try {
    await slotsAPI.toggle(selectedDate.value, slotHour)
    await loadSlots()
  } catch (error) {
    console.error('Failed to toggle slot:', error)
    alert('Fehler beim Ändern des Slots')
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
  const dateStr = date.toISOString().split('T')[0]
  selectedDate.value = dateStr
  router.push(`/admin/slots/${dateStr}`)
  loadSlots()
}

const handleDateSelected = (dateStr) => {
  selectedDate.value = dateStr
  showDatePicker.value = false
  router.push(`/admin/slots/${dateStr}`)
  loadSlots()
}

const prevPickerMonth = () => {
  if (pickerMonth.value === 1) {
    pickerMonth.value = 12
    pickerYear.value--
  } else {
    pickerMonth.value--
  }
}

const nextPickerMonth = () => {
  if (pickerMonth.value === 12) {
    pickerMonth.value = 1
    pickerYear.value++
  } else {
    pickerMonth.value++
  }
}

// Watch for route param changes
watch(() => route.params.date, (newDate) => {
  if (newDate && newDate !== selectedDate.value) {
    selectedDate.value = newDate
    loadSlots()
  }
})

// Update picker month when date picker opens
watch(showDatePicker, (isOpen) => {
  if (isOpen) {
    const date = new Date(selectedDate.value)
    pickerYear.value = date.getFullYear()
    pickerMonth.value = date.getMonth() + 1
  }
})

onMounted(() => {
  loadSlots()
})
</script>

<style scoped>
.date-navigation {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
  gap: var(--spacing-md);
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

.date-display {
  flex: 1;
  text-align: center;
  cursor: pointer;
  padding: var(--spacing-sm);
  border-radius: 8px;
  transition: background-color 0.2s;
}

.date-display:hover {
  background-color: var(--color-bg-card);
}

.date-weekday {
  color: var(--color-text-title);
  font-size: 20px;
  font-weight: 600;
}

.date-full {
  color: var(--color-text-title);
  font-size: 24px;
  font-weight: 600;
}

.date-picker-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: var(--spacing-md);
}

.date-picker-content {
  background-color: var(--color-bg-white);
  border-radius: 8px;
  max-width: 400px;
  width: 100%;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.slots-list {
  display: flex;
  flex-direction: column;
}

.loading,
.no-slots {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
