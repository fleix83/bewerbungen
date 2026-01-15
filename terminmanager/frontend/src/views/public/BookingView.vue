<template>
  <div class="screen">
    <div class="screen-header">
      <h1 class="screen-title">Termin machen</h1>
      <a href="/bewerbungen/" class="back-link">zurück</a>
    </div>

    <ServiceSelector
      v-model="selectedServices"
      :services="availableServices"
      :notes="customerData.notes"
      :serviceType="customerData.serviceType"
      @update:notes="updateNotes"
      @update:serviceType="updateServiceType"
    />

    <ContactForm v-model="customerData" />

    <div class="actions">
      <AppButton @click="handleNext" :disabled="!isFormValid">
        Termin wählen
      </AppButton>

      <div class="alternative-contact">
        <a href="https://wa.me/41767576052" class="contact-link" target="_blank">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
          </svg>
          Per Whatsapp kontaktieren
        </a>
        <a href="tel:+41767576052" class="contact-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
          </svg>
          Anrufen 076 757 60 52
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { servicesAPI } from '../../services/api'
import ServiceSelector from '../../components/booking/ServiceSelector.vue'
import ContactForm from '../../components/booking/ContactForm.vue'
import AppButton from '../../components/common/AppButton.vue'

const router = useRouter()
const bookingStore = useBookingStore()

const availableServices = ref([])
const selectedServices = ref([...bookingStore.selectedServices] || [])
const customerData = ref({
  salutation: bookingStore.customerData.salutation || '',
  firstName: bookingStore.customerData.firstName || '',
  lastName: bookingStore.customerData.lastName || '',
  email: bookingStore.customerData.email || '',
  phone: bookingStore.customerData.phone || '',
  notes: bookingStore.customerData.notes || '',
  serviceType: bookingStore.customerData.serviceType || ''
})

const isFormValid = computed(() => {
  // Valid if either checkboxes selected OR dropdown has a specific value
  const hasService = selectedServices.value.length > 0 ||
    (customerData.value.serviceType && customerData.value.serviceType !== '')

  return (
    hasService &&
    customerData.value.firstName.trim() !== '' &&
    customerData.value.lastName.trim() !== '' &&
    customerData.value.email.trim() !== '' &&
    validateEmail(customerData.value.email)
  )
})

const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

const updateNotes = (val) => {
  customerData.value.notes = val
}

const updateServiceType = (val) => {
  customerData.value.serviceType = val
}

const handleNext = () => {
  if (!isFormValid.value) {
    return
  }

  bookingStore.setServices(selectedServices.value)
  bookingStore.setCustomerData(customerData.value)

  router.push('/buchen/datum')
}

onMounted(async () => {
  try {
    const response = await servicesAPI.getAll()
    availableServices.value = response.data
  } catch (error) {
    console.error('Failed to load services:', error)
    availableServices.value = [
      { id: 1, name: 'Lebenslauf', duration_slots: 1 },
      { id: 2, name: 'Bewerbungsschreiben', duration_slots: 1 },
      { id: 3, name: 'Etwas anderes', duration_slots: 1 }
    ]
  }
})
</script>

<style scoped>
.screen-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--spacing-lg);
}

.screen-header .screen-title {
  margin-bottom: 0;
  margin-top: 50px;
}

.back-link {
  color: #002198;
  font-family: var(--font-primary);
  font-size: 16px;
  text-decoration: underline;
}

.back-link:hover {
  text-decoration: underline;
}

.actions {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  margin-top: var(--spacing-xl);
  gap: var(--spacing-md);
}

.alternative-contact {
  display: flex;
  flex-direction: column;
  gap: 2px;
  align-items: flex-start;
}

.contact-link {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #002198;
  font-family: var(--font-primary);
  font-size: 16px;
  line-height: 1.2;
  text-decoration: none;
  padding: 4px 0;
  transition: opacity 0.2s;
}

.contact-link:hover {
  opacity: 0.8;
  text-decoration: none;
}

.contact-link svg {
  flex-shrink: 0;
}
</style>
