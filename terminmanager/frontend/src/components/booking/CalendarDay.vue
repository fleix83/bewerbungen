<template>
  <div
    :class="dayClass"
    @click="handleClick"
  >
    <span class="day-number">{{ day }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  day: {
    type: Number,
    required: true
  },
  hasSlots: {
    type: Boolean,
    default: false
  },
  isDisabled: {
    type: Boolean,
    default: false
  },
  isEmpty: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

const dayClass = computed(() => ({
  'calendar-day': true,
  'calendar-day--free': !props.isDisabled && props.hasSlots && !props.isEmpty,
  'calendar-day--blocked': props.isDisabled || !props.hasSlots,
  'calendar-day--empty': props.isEmpty
}))

const handleClick = () => {
  if (!props.isDisabled && props.hasSlots && !props.isEmpty) {
    emit('click', props.day)
  }
}
</script>

<style scoped>
.calendar-day {
  aspect-ratio: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.calendar-day--empty {
  cursor: default;
  background: transparent;
  visibility: hidden;
}

.calendar-day--free {
  background-color: #d7d5f5;
  color: var(--color-text-title);
}

.calendar-day--free:hover {
  transform: scale(1.05);
}

.calendar-day--blocked {
  background-color: #767676;
  color: #FFFFFF;
  cursor: not-allowed;
}

.day-number {
  font-family: var(--font-primary);
}
</style>
