<template>
  <div class="screen">
    <h1 class="screen-title">Buchung bestätigt!</h1>

    <div v-if="confirmation" class="success-message">
      <div class="success-icon">✓</div>
      <p>Ihre Buchung wurde erfolgreich erstellt.</p>
    </div>

    <BookingConfirmation
      v-if="confirmation"
      :confirmation="confirmation"
      :services="bookedServices"
      :notes="customerNotes"
    />

    <div v-else class="error-message">
      <p>Keine Buchungsdetails verfügbar.</p>
      <AppButton @click="$router.push('/buchen')">
        Neuen Termin buchen
      </AppButton>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { servicesAPI } from '../../services/api'
import BookingConfirmation from '../../components/booking/BookingConfirmation.vue'
import AppButton from '../../components/common/AppButton.vue'

const router = useRouter()
const bookingStore = useBookingStore()

const confirmation = ref(null)
const bookedServices = ref([])
const customerNotes = ref('')

onMounted(async () => {
  confirmation.value = bookingStore.bookingConfirmation

  if (!confirmation.value) {
    return
  }

  // Get serviceType and notes from customerData
  let serviceTypeName = bookingStore.customerData.serviceType || 'Etwas anderes'
  customerNotes.value = bookingStore.customerData.notes || ''

  // If "Etwas anderes" selected, show truncated notes instead
  if (serviceTypeName === 'Etwas anderes' && customerNotes.value) {
    const truncatedNotes = customerNotes.value.length > 50
      ? customerNotes.value.substring(0, 50) + '...'
      : customerNotes.value
    serviceTypeName = truncatedNotes
  }

  try {
    const response = await servicesAPI.getAll()
    let allServices = response.data

    // Add the dropdown selection as a service with the actual selected type name
    if (confirmation.value.services.includes(3)) {
      allServices.push({ id: 3, name: serviceTypeName, price: 30 })
    }

    bookedServices.value = allServices.filter(service =>
      confirmation.value.services.includes(service.id)
    )
  } catch (error) {
    console.error('Failed to load services:', error)
    const fallbackServices = [
      { id: 1, name: 'Lebenslauf', price: 30 },
      { id: 2, name: 'Bewerbungsschreiben', price: 30 }
    ]
    if (confirmation.value.services.includes(3)) {
      fallbackServices.push({ id: 3, name: serviceTypeName, price: 30 })
    }
    bookedServices.value = fallbackServices.filter(service =>
      confirmation.value.services.includes(service.id)
    )
  }
})
</script>

<style scoped>
.success-message {
  text-align: center;
  padding: var(--spacing-xl);
  background-color: #E8F5E9;
  border-radius: 8px;
  margin-bottom: var(--spacing-xl);
}

.success-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background-color: #4CAF50;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 36px;
  font-weight: bold;
  margin: 0 auto var(--spacing-md);
}

.success-message p {
  font-size: 18px;
  font-weight: 600;
  color: #2E7D32;
}

.error-message {
  text-align: center;
  padding: var(--spacing-xl);
}

.error-message p {
  margin-bottom: var(--spacing-lg);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
