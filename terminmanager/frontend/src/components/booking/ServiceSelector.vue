<template>
  <div class="service-selector">
    <h2 class="section-title">Was brauchen Sie?</h2>
    <div class="services-list">
      <AppCheckbox
        v-for="service in filteredServices"
        :key="service.id"
        :id="`service-${service.id}`"
        :label="service.name"
        :duration="formatDuration(service.duration_slots)"
        :model-value="isServiceSelected(service.id)"
        @update:model-value="toggleService(service.id)"
      />
    </div>

    <h3 class="other-title">Etwas anderes</h3>
    <div class="notes-field">
      <textarea
        id="notes-input"
        :value="notesText"
        @input="handleNotesInput"
        placeholder="Ich brauche folgendes..."
        rows="3"
        class="notes-textarea"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppCheckbox from '../common/AppCheckbox.vue'

const props = defineProps({
  services: {
    type: Array,
    required: true
  },
  modelValue: {
    type: Array,
    default: () => []
  },
  notes: {
    type: String,
    default: ''
  },
  serviceType: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue', 'update:notes', 'update:serviceType'])

const additionalNotes = ref(props.notes)

// Filter out "Etwas anderes" (id: 3) from checkbox list
const filteredServices = computed(() =>
  props.services.filter(s => s.id !== 3)
)

const isServiceSelected = (serviceId) => {
  return props.modelValue.includes(serviceId)
}

const toggleService = (serviceId) => {
  const selectedServices = [...props.modelValue]
  const index = selectedServices.indexOf(serviceId)

  if (index > -1) {
    selectedServices.splice(index, 1)
  } else {
    selectedServices.push(serviceId)
  }

  emit('update:modelValue', selectedServices)
}

const formatDuration = (slots) => {
  return `max ${slots}h`
}

const notesText = computed(() => {
  return additionalNotes.value
})

const handleNotesInput = (event) => {
  additionalNotes.value = event.target.value
  emit('update:notes', additionalNotes.value)
  emit('update:serviceType', additionalNotes.value.trim() ? 'Etwas anderes' : '')
}
</script>

<style scoped>
.service-selector {
  margin-bottom: var(--spacing-xl);
}

.services-list {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-sm);
}

.other-title {
  margin-top: var(--spacing-lg);
  margin-bottom: var(--spacing-sm);
  font-family: var(--font-primary);
  font-size: 23px;
  font-weight: 600;
  color: var(--color-text-title, #002198);
}

.notes-field {
  margin-top: var(--spacing-sm);
}

.notes-textarea {
  width: 100%;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
  resize: vertical;
}

.notes-textarea:focus {
  outline: none;
  border-color: #002198;
}

.notes-textarea::placeholder {
  color: #999;
}
</style>
