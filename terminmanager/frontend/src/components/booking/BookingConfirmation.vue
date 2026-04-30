<template>
  <div class="booking-confirmation">
    <div class="confirmation-section">
      <h2 class="section-title">Ihr Termin</h2>
      <div v-if="!cancelled" class="termin-box termin-box--row">
        <div class="termin-info">
          <p><strong>{{ formattedDate }}</strong></p>
          <p>{{ timeDisplay }}</p>
        </div>
        <div class="termin-actions">
          <button
            v-if="!confirming && canCancel"
            type="button"
            class="btn-cancel"
            @click="confirming = true"
          >Stornieren</button>
        </div>
      </div>

      <div v-if="!cancelled && !canCancel" class="termin-note">
        Stornierung nur bis 24 Stunden vorher möglich. Bitte telefonisch absagen.
      </div>

      <div v-if="!cancelled && confirming" class="cancel-confirm">
        <p>Möchten Sie den Termin wirklich stornieren?</p>
        <div class="cancel-confirm-actions">
          <button
            type="button"
            class="btn-cancel-confirm"
            :disabled="submitting"
            @click="onCancelConfirm"
          >{{ submitting ? 'Wird storniert…' : 'Bestätigen' }}</button>
          <button
            type="button"
            class="btn-cancel-abort"
            :disabled="submitting"
            @click="confirming = false"
          >Abbrechen</button>
        </div>
        <p v-if="cancelError" class="cancel-error">{{ cancelError }}</p>
      </div>

      <div v-if="cancelled" class="cancel-success">
        <p><strong>Ihr Termin wurde storniert.</strong></p>
        <p>Eine Bestätigung wurde an Ihre E-Mail-Adresse gesendet.</p>
      </div>
    </div>

    <div class="confirmation-section">
      <h2 class="section-title">Adresse</h2>
      <div class="termin-box">
        <p>Luftgässlein 3</p>
        <p>Basel</p>
        <p>1. Stock</p>
      </div>
    </div>

    <div class="confirmation-section">
      <h2 class="section-title">Ihre Buchung</h2>
      <div class="termin-box">
        <div v-for="service in services" :key="service.id" class="service-item">
          <span>{{ service.name }}</span>
          <span>CHF {{ formatPrice(service.price) }}</span>
        </div>
        <div class="total">
          <strong>Total</strong>
          <strong>CHF {{ formatPrice(totalPrice) }}</strong>
        </div>
      </div>
    </div>

    <div class="confirmation-section" v-if="notes && notes.trim()">
      <h2 class="section-title">Ihre Anmerkung</h2>
      <div class="termin-box">
        <p style="white-space: pre-line;">{{ notes }}</p>
      </div>
    </div>

    <div class="confirmation-section">
      <h2 class="section-title">Wichtig</h2>
      <div class="termin-box hints">
        <p>Bitte bringen Sie alle vorhandenen Unterlagen mit: Zeugnisse, Lebenslauf (falls vorhanden), bereits vorhandene Dokumente...</p>
        <p>Bei Fragen, kontaktieren Sie mich. Ich freue mich auf Sie.</p>
      </div>
    </div>

    <div class="confirmation-section">
      <h2 class="section-title">Zahlungsmöglichkeiten</h2>
      <div class="termin-box hints">
        <p>Sie können vor Ort in bar oder mit TWINT bezahlen.</p>
      </div>
    </div>

    <div class="confirmation-section">
      <h2 class="section-title">Kontakt</h2>
      <div class="contact-links">
        <a href="https://wa.me/41767576052" class="contact-link" target="_blank">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
          </svg>
          WhatsApp
        </a>
        <a href="tel:+41767576052" class="contact-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/>
          </svg>
          +41 76 757 60 52
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useBookingStore } from '../../stores/bookingStore'
import { cancellationsAPI } from '../../services/api'

const props = defineProps({
  confirmation: {
    type: Object,
    required: true
  },
  services: {
    type: Array,
    required: true
  },
  notes: {
    type: String,
    default: ''
  }
})

const bookingStore = useBookingStore()

const confirming = ref(false)
const submitting = ref(false)
const cancelled = ref(Boolean(props.confirmation?.cancelled))
const cancelError = ref('')

const canCancel = computed(() => {
  const slot = props.confirmation?.slot
  if (!slot || slot.start_slot == null) return false
  const d = new Date(props.confirmation.date)
  const start = new Date(d.getFullYear(), d.getMonth(), d.getDate(), slot.start_slot, 0, 0)
  return (start.getTime() - Date.now()) > 24 * 60 * 60 * 1000
})

async function onCancelConfirm() {
  submitting.value = true
  cancelError.value = ''
  try {
    await cancellationsAPI.cancel({
      token: props.confirmation.cancellationToken,
      eventId: props.confirmation.eventId,
      email: props.confirmation.customer?.email
    })
    cancelled.value = true
    confirming.value = false
    bookingStore.markCancelled()
  } catch (e) {
    cancelError.value = e?.response?.data?.error
      || 'Stornierung fehlgeschlagen. Bitte versuchen Sie es erneut.'
  } finally {
    submitting.value = false
  }
}

const formattedDate = computed(() => {
  const date = new Date(props.confirmation.date)
  return date.toLocaleDateString('de-DE', {
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
})

const timeDisplay = computed(() => {
  const slot = props.confirmation.slot
  return `${slot.start_slot.toString().padStart(2, '0')}:00 - ${slot.end_slot.toString().padStart(2, '0')}:00 Uhr`
})

const totalPrice = computed(() => {
  return props.services.reduce((sum, service) => sum + parseFloat(service.price || 0), 0)
})

const formatPrice = (price) => {
  return parseFloat(price || 0).toFixed(2)
}
</script>

<style scoped>
.booking-confirmation {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-xl);
}

.confirmation-section {
  margin-bottom: var(--spacing-md);
}

.termin-box {
  background-color: var(--color-bg-white);
  border-radius: 8px;
  padding: 16px 20px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
}

.termin-box p {
  margin: 8px 0;
}

.hints p {
  margin: 12px 0;
  line-height: 1.6;
}

.service-item {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid var(--color-border);
}

.total {
  display: flex;
  justify-content: space-between;
  padding-top: 12px;
  margin-top: 8px;
  font-size: 18px;
}

.contact-links {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.contact-link {
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--color-text-title);
  font-family: var(--font-primary);
  font-size: 16px;
  text-decoration: none;
  padding: var(--spacing-md);
  background-color: var(--color-bg-white);
  border-radius: 4px;
  transition: background-color 0.2s;
}

.contact-link:hover {
  background-color: var(--color-bg-card);
  text-decoration: none;
}

.termin-box--row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--spacing-md);
}

.termin-info p {
  margin: 8px 0;
}

.termin-actions {
  flex-shrink: 0;
}

.btn-cancel {
  background-color: #e7e6ff;
  color: var(--color-text-title);
  border: none;
  border-radius: 4px;
  padding: 8px 14px;
  font-family: var(--font-primary);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: filter 0.15s;
}

.btn-cancel:hover {
  filter: brightness(0.95);
}

.termin-note {
  margin-top: var(--spacing-sm);
  font-family: var(--font-primary);
  font-size: 14px;
  color: var(--color-text-muted);
}

.cancel-confirm {
  margin-top: var(--spacing-md);
  padding: 16px 20px;
  background-color: var(--color-bg-white);
  border-radius: 8px;
  font-family: var(--font-primary);
  font-size: 16px;
}

.cancel-confirm p {
  margin: 0 0 var(--spacing-md) 0;
}

.cancel-confirm-actions {
  display: flex;
  gap: var(--spacing-sm);
  flex-wrap: wrap;
}

.btn-cancel-confirm {
  background-color: var(--color-text-title);
  color: #FFFFFF;
  border: none;
  border-radius: 4px;
  padding: 10px 16px;
  font-family: var(--font-primary);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel-confirm:hover:not(:disabled) {
  background-color: #001470;
}

.btn-cancel-confirm:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel-abort {
  background-color: #e7e6ff;
  color: var(--color-text-title);
  border: none;
  border-radius: 4px;
  padding: 10px 16px;
  font-family: var(--font-primary);
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel-abort:hover:not(:disabled) {
  filter: brightness(0.95);
}

.btn-cancel-abort:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.cancel-error {
  margin-top: var(--spacing-sm) !important;
  color: #b00020;
  font-size: 14px;
}

.cancel-success {
  background-color: var(--color-bg-white);
  border-left: 4px solid #4CAF50;
  border-radius: 8px;
  padding: 16px 20px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
}

.cancel-success p {
  margin: 8px 0;
}
</style>
