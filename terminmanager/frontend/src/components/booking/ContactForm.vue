<template>
  <div class="contact-form">
    <h2 class="section-title">Kontakt</h2>

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
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      notes: '',
      serviceType: 'Etwas anderes'
    })
  }
})

const emit = defineEmits(['update:modelValue'])

const formData = reactive({
  firstName: props.modelValue.firstName || '',
  lastName: props.modelValue.lastName || '',
  email: props.modelValue.email || '',
  phone: props.modelValue.phone || ''
})

watch(formData, (newValue) => {
  // Only emit the contact fields, preserve notes and serviceType from the original
  emit('update:modelValue', {
    ...props.modelValue,
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

.name-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.contact-row {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: var(--spacing-md);
}
</style>
