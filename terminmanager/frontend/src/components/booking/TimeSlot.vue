<template>
  <div :class="slotClass">
    <div :class="`time-slot__dot time-slot__dot--${status}`"></div>
    <span class="time-slot__status">{{ statusText }}</span>
    <span class="time-slot__time">{{ timeDisplay }}</span>
    <AppButton
      v-if="isFree"
      variant="slot"
      @click="$emit('book')"
    >
      Buchen
    </AppButton>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import AppButton from '../common/AppButton.vue'

const props = defineProps({
  startSlot: {
    type: Number,
    required: true
  },
  endSlot: {
    type: Number,
    required: true
  },
  status: {
    type: String,
    required: true,
    validator: (value) => ['free', 'occupied'].includes(value)
  }
})

defineEmits(['book'])

const slotClass = computed(() => ({
  'time-slot': true,
  'time-slot--free': props.status === 'free',
  'time-slot--occupied': props.status === 'occupied'
}))

const isFree = computed(() => props.status === 'free')

const statusText = computed(() => {
  return props.status === 'free' ? 'frei' : 'belegt'
})

const timeDisplay = computed(() => {
  const formatTime = (hour) => {
    return `${hour.toString().padStart(2, '0')}:00`
  }

  return `${formatTime(props.startSlot)} - ${formatTime(props.endSlot)}`
})
</script>

<style scoped>
.time-slot {
  display: flex;
  align-items: center;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 8px;
  gap: var(--spacing-md);
}

.time-slot--free {
  background-color: var(--color-slot-free);
}

.time-slot--occupied {
  background-color: var(--color-slot-occupied);
}

.time-slot__dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
}

.time-slot__dot--free {
  background-color: var(--color-dot-free);
}

.time-slot__dot--occupied {
  background-color: #666;
}

.time-slot__status {
  font-size: 16px;
  min-width: 60px;
  font-weight: 600;
}

.time-slot__time {
  flex: 1;
  font-size: 16px;
  font-weight: 600;
}
</style>
