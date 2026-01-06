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

    <div class="service-type-field">
      <select
        id="service-type"
        :value="selectedServiceType"
        @change="handleServiceTypeChange"
        class="service-type-select"
      >
        <option value="" disabled>Weitere Angebote</option>
        <option value="Brief">Brief CHF 30.00</option>
        <option value="Rekurs">Rekurs CHF 30.00</option>
        <option value="Formular ausfüllen">Formular ausfüllen CHF 20.00</option>
        <option value="Steuererklärung ausfüllen">Steuererklärung ausfüllen CHF 20.00</option>
        <option value="Anderes Dokument">Anderes Dokument CHF 30.00</option>
        <option value="Etwas anderes">Etwas anderes</option>
      </select>
    </div>

    <div v-if="selectedServiceType === 'Etwas anderes'" class="notes-field">
      <textarea
        id="notes-input"
        :value="notesText"
        @input="handleNotesInput"
        placeholder="Bitte schreiben Sie mir, was Sie brauchen. Preis nach Absprache. "
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

const selectedServiceType = computed({
  get: () => props.serviceType,
  set: (newValue) => {
    emit('update:serviceType', newValue)
  }
})
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
}

const handleServiceTypeChange = (event) => {
  selectedServiceType.value = event.target.value
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

.service-type-field {
  margin-top: var(--spacing-sm);
}

.service-type-select {
  width: 100%;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
  cursor: pointer;
}

.service-type-select:focus {
  outline: none;
  border-color: #002198;
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
