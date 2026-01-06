<template>
  <div class="calendar-month">
    <div class="calendar-header">
      <button class="nav-button" @click="$emit('prev-month')">←</button>
      <h2 class="month-title">{{ monthTitle }}</h2>
      <button class="nav-button" @click="$emit('next-month')">→</button>
    </div>

    <div class="weekday-labels">
      <div v-for="day in weekDays" :key="day" class="weekday-label">
        {{ day }}
      </div>
    </div>

    <div class="calendar-grid">
      <CalendarDay
        v-for="(dayData, index) in calendarDays"
        :key="index"
        :day="dayData.day"
        :has-slots="dayData.hasSlots"
        :is-disabled="dayData.isDisabled"
        :is-empty="dayData.isEmpty"
        @click="handleDayClick"
      />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import CalendarDay from './CalendarDay.vue'

const props = defineProps({
  year: {
    type: Number,
    required: true
  },
  month: {
    type: Number,
    required: true
  },
  availability: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['day-selected', 'prev-month', 'next-month'])

const weekDays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa']

const monthTitle = computed(() => {
  const date = new Date(props.year, props.month - 1)
  return date.toLocaleDateString('de-DE', { month: 'long', year: 'numeric' })
})

const calendarDays = computed(() => {
  const days = []
  const firstDay = new Date(props.year, props.month - 1, 1)
  const lastDay = new Date(props.year, props.month, 0)
  const daysInMonth = lastDay.getDate()

  // Find the first non-Sunday day to calculate padding
  let firstShownDay = 1
  let firstDayOfWeek = firstDay.getDay()

  // If first day is Sunday, start from day 2
  if (firstDayOfWeek === 0) {
    firstShownDay = 2
    firstDayOfWeek = 1 // Monday
  }

  // Convert to Mon=0, Tue=1, ..., Sat=5 (no Sunday)
  const padding = firstDayOfWeek - 1

  for (let i = 0; i < padding; i++) {
    days.push({ day: '', isEmpty: true, hasSlots: false, isDisabled: true })
  }

  const today = new Date()
  today.setHours(0, 0, 0, 0)

  for (let day = 1; day <= daysInMonth; day++) {
    const currentDate = new Date(props.year, props.month - 1, day)

    // Skip Sundays
    if (currentDate.getDay() === 0) {
      continue
    }

    // Format as YYYY-MM-DD without timezone conversion
    const dateString = `${props.year}-${String(props.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`

    const availabilityData = props.availability.find(a => a.date === dateString)
    const hasSlots = availabilityData?.has_free_slots || false
    const isPast = currentDate < today

    days.push({
      day,
      hasSlots,
      isDisabled: isPast,
      isEmpty: false
    })
  }

  return days
})

const handleDayClick = (day) => {
  if (day) {
    // Format as YYYY-MM-DD without timezone conversion
    const dateString = `${props.year}-${String(props.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`
    emit('day-selected', dateString)
  }
}
</script>

<style scoped>
.calendar-month {
  background-color: var(--color-bg-white);
  border-radius: 8px;
  padding: var(--spacing-lg);
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-lg);
}

.month-title {
  color: var(--color-text-title);
  font-size: 20px;
  font-weight: 600;
  text-transform: capitalize;
}

.nav-button {
  background: none;
  border: none;
  color: var(--color-text-title);
  font-size: 24px;
  cursor: pointer;
  padding: var(--spacing-sm);
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.nav-button:hover {
  background-color: var(--color-bg-card);
}

.weekday-labels {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: var(--spacing-xs);
  margin-bottom: var(--spacing-sm);
}

.weekday-label {
  text-align: center;
  font-size: 12px;
  font-weight: 600;
  color: var(--color-text-muted);
  text-transform: uppercase;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: var(--spacing-xs);
}
</style>
