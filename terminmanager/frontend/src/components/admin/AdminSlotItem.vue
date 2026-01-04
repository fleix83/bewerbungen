<template>
  <div :class="slotClass">
    <div :class="dotClass"></div>
    <span class="admin-slot__status">{{ statusText }}</span>
    <span class="admin-slot__time">{{ slot.time_display }}</span>
    <AppButton
      v-if="canToggle"
      variant="slot"
      @click="$emit('toggle', slot.slot_hour)"
    >
      {{ buttonText }}
    </AppButton>
    <span v-else-if="slot.customer_name" class="admin-slot__customer">
      {{ slot.customer_name }}
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import AppButton from '../common/AppButton.vue'

const props = defineProps({
  slot: {
    type: Object,
    required: true
  }
})

defineEmits(['toggle'])

const isFree = computed(() => props.slot.status === 'free')
const isBooked = computed(() => props.slot.status === 'booked')
const isHoliday = computed(() => props.slot.status === 'blocked_holiday')

const canToggle = computed(() =>
  props.slot.status === 'free' || props.slot.status === 'not_released'
)

const slotClass = computed(() => ({
  'admin-slot': true,
  'admin-slot--free': isFree.value,
  'admin-slot--blocked': !isFree.value
}))

const dotClass = computed(() => ({
  'admin-slot__dot': true,
  'admin-slot__dot--free': isFree.value,
  'admin-slot__dot--blocked': !isFree.value
}))

const statusText = computed(() => {
  if (isFree.value) return 'frei'
  if (isBooked.value) return 'belegt'
  if (isHoliday.value) return 'Feiertag'
  return 'belegt'
})

const buttonText = computed(() => {
  return isFree.value ? 'blockieren' : 'freigeben'
})
</script>

<style scoped>
.admin-slot {
  display: flex;
  align-items: center;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 8px;
  gap: var(--spacing-md);
}

.admin-slot--free {
  background-color: var(--color-slot-free);
}

.admin-slot--blocked {
  background-color: var(--color-slot-occupied);
}

.admin-slot__dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
}

.admin-slot__dot--free {
  background-color: var(--color-dot-free);
}

.admin-slot__dot--blocked {
  background-color: #666;
}

.admin-slot__status {
  font-size: 16px;
  font-weight: 600;
  min-width: 60px;
}

.admin-slot__time {
  flex: 1;
  font-size: 16px;
  font-weight: 600;
}

.admin-slot__customer {
  font-size: 14px;
  color: #333;
  font-style: italic;
}
</style>
