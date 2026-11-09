<template>
  <div class="screen">
    <div class="screen-header">
      <h1 class="screen-title">Übersicht</h1>
      <a href="#" class="back-link" @click.prevent="goBack">zurück</a>
    </div>

    <p class="intro">Bitte prüfen Sie Ihre Angaben und bestätigen Sie den Termin.</p>

    <BookingConfirmation
      v-if="overview"
      :confirmation="overview"
      :services="bookedServices"
      :notes="bookingStore.customerData.notes"
      review
    />

    <p v-if="bookingError" class="booking-error">{{ bookingError }}</p>

    <div class="confirm-action">
      <img :src="arrowIcon" alt="" class="confirm-arrow" width="24" height="24">
      <AppButton :disabled="submitting" @click="confirmBooking">
        {{ submitting ? 'Wird gebucht…' : 'Termin jetzt vereinbaren' }}
      </AppButton>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/bookingStore'
import { bookingsAPI } from '../../services/api'
import { loadBookedServices, bookingServiceIds } from '../../services/bookedServices'
import BookingConfirmation from '../../components/booking/BookingConfirmation.vue'
import AppButton from '../../components/common/AppButton.vue'
import arrowIcon from '../../assets/images/arrow-mehr.png'

const router = useRouter()
const bookingStore = useBookingStore()

const bookedServices = ref([])
const submitting = ref(false)
const bookingError = ref('')

const serviceIds = computed(() =>
  bookingServiceIds(bookingStore.selectedServices, bookingStore.customerData.serviceType)
)

// Same shape as the confirmation after booking, so both screens share one layout
const overview = computed(() => {
  if (!bookingStore.selectedDate || !bookingStore.selectedSlot) return null
  return {
    date: bookingStore.selectedDate,
    slot: bookingStore.selectedSlot,
    services: serviceIds.value,
    customer: { ...bookingStore.customerData }
  }
})

const confirmBooking = async () => {
  submitting.value = true
  bookingError.value = ''

  const customer = bookingStore.customerData
  const slot = bookingStore.selectedSlot
  const bookingData = {
    customer: {
      salutation: customer.salutation,
      first_name: customer.firstName,
      last_name: customer.lastName,
      email: customer.email,
      phone: customer.phone
    },
    event_date: bookingStore.selectedDate,
    start_slot: slot.start_slot,
    end_slot: slot.end_slot,
    service_ids: serviceIds.value,
    notes: customer.notes,
    service_type: customer.serviceType
  }

  try {
    const response = await bookingsAPI.create(bookingData)

    if (response.data.success) {
      bookingStore.setBookingConfirmation({
        eventId: response.data.event_id,
        cancellationToken: response.data.cancellation_token,
        date: bookingStore.selectedDate,
        slot: slot,
        services: serviceIds.value,
        customer: { ...customer }
      })

      router.push('/buchen/bestaetigung')
    } else {
      bookingError.value = 'Buchung fehlgeschlagen: ' + response.data.message
    }
  } catch (error) {
    console.error('Booking failed:', error)
    bookingError.value = 'Fehler bei der Buchung. Bitte versuchen Sie es erneut.'
  } finally {
    submitting.value = false
  }
}

const goBack = () => {
  router.push(`/buchen/zeit/${bookingStore.selectedDate}`)
}

onMounted(async () => {
  if (!overview.value) {
    router.push('/buchen')
    return
  }
  bookedServices.value = await loadBookedServices(
    serviceIds.value,
    bookingStore.customerData.serviceType,
    bookingStore.customerData.notes
  )
})
</script>

<style scoped>
.screen-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: var(--spacing-md);
}

.screen-header .screen-title {
  margin-bottom: 0;
}

.back-link {
  color: #002198;
  font-family: var(--font-primary);
  font-size: 16px;
  text-decoration: underline;
}

.intro {
  font-family: var(--font-primary);
  font-size: 16px;
  margin-bottom: var(--spacing-lg);
}

.booking-error {
  margin-top: var(--spacing-md);
  padding: 12px 16px;
  background-color: var(--color-bg-white);
  border-left: 4px solid #b00020;
  border-radius: 8px;
  color: #b00020;
  font-family: var(--font-primary);
  font-size: 16px;
}

.confirm-action {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
  margin: var(--spacing-xl) 0;
}

/* Pfeil links vom Button lenkt den Blick auf die Bestaetigung */
.confirm-arrow {
  flex-shrink: 0;
}

/* Globale Breite von .btn-primary (209px) ist fuer diesen Text zu schmal */
.confirm-action :deep(.btn-primary) {
  width: auto;
  white-space: nowrap;
}
</style>
