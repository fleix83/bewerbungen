<template>
  <div class="contact-form">
    <h2 class="section-title">Ihre Angaben</h2>

    <div class="salutation-row">
      <label for="salutation" class="field-label">Anrede</label>
      <select id="salutation" v-model="formData.salutation" class="salutation-select">
        <option value="">Keine Angabe</option>
        <option value="Frau">Frau</option>
        <option value="Herr">Herr</option>
      </select>
    </div>

    <div class="name-row">
      <AppInput
        id="firstName"
        label="Vorname"
        v-model="formData.firstName"
        required
        placeholder="Vorname"
      />

      <AppInput
        id="lastName"
        label="Nachname"
        v-model="formData.lastName"
        required
        placeholder="Nachname"
      />
    </div>

    <div class="contact-row">
      <AppInput
        id="email"
        type="email"
        label="Email"
        v-model="formData.email"
        required
        placeholder="Email"
      />

      <AppInput
        id="phone"
        type="tel"
        label="Telefon"
        v-model="formData.phone"
        placeholder="Telefon"
      />
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import AppInput from '../common/AppInput.vue'

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      salutation: '',
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      notes: '',
      serviceType: ''
    })
  }
})

const emit = defineEmits(['update:modelValue'])

const formData = reactive({
  salutation: props.modelValue.salutation || '',
  firstName: props.modelValue.firstName || '',
  lastName: props.modelValue.lastName || '',
  email: props.modelValue.email || '',
  phone: props.modelValue.phone || ''
})

watch(formData, (newValue) => {
  // Only emit the contact fields, preserve notes and serviceType from the original
  emit('update:modelValue', {
    ...props.modelValue,
    salutation: newValue.salutation,
    firstName: newValue.firstName,
    lastName: newValue.lastName,
    email: newValue.email,
    phone: newValue.phone
  })
}, { deep: true })
</script>

<style scoped>
.contact-form {
  margin-bottom: var(--spacing-xl);
}

.salutation-row {
  margin-bottom: var(--spacing-md);
}

.field-label {
  display: block;
  font-family: var(--font-primary);
  font-size: 14px;
  font-weight: 600;
  color: var(--color-text-title);
  margin-bottom: 4px;
}

.salutation-select {
  width: 100%;
  max-width: 200px;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
  cursor: pointer;
}

.salutation-select:focus {
  outline: none;
  border-color: #002198;
}

.name-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.contact-row {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: var(--spacing-md);
}
</style>
