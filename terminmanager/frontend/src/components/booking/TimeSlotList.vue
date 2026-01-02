<template>
  <div class="time-slot-list">
    <TimeSlot
      v-for="slot in slots"
      :key="`${slot.start_slot}-${slot.end_slot}`"
      :start-slot="slot.start_slot"
      :end-slot="slot.end_slot"
      :status="slot.status"
      @book="$emit('slot-selected', slot)"
    />
    <div v-if="slots.length === 0" class="no-slots">
      <p>Keine freien Slots für diesen Tag verfügbar.</p>
      <p class="contact-hint">Bitte kontaktieren Sie mich direkt.</p>
    </div>
  </div>
</template>

<script setup>
import TimeSlot from './TimeSlot.vue'

defineProps({
  slots: {
    type: Array,
    required: true
  }
})

defineEmits(['slot-selected'])
</script>

<style scoped>
.time-slot-list {
  margin-top: var(--spacing-lg);
}

.no-slots {
  text-align: center;
  padding: var(--spacing-xl);
  background-color: var(--color-bg-white);
  border-radius: 8px;
}

.no-slots p {
  margin-bottom: var(--spacing-sm);
}

.contact-hint {
  color: var(--color-text-title);
  font-weight: 600;
}
</style>
