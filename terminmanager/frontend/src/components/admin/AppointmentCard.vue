<template>
  <div class="appointment-card">
    <div class="card-section">
      <h3 class="card-title">Kundendaten</h3>
      <div class="info-group">
        <p><strong>Kundennummer:</strong> {{ appointment.customer_number }}</p>
        <p><strong>Name:</strong> {{ appointment.customer_name }}</p>
        <p><strong>Email:</strong> {{ appointment.customer_email }}</p>
        <p v-if="appointment.customer_phone"><strong>Telefon:</strong> {{ appointment.customer_phone }}</p>
      </div>
    </div>

    <div class="card-section">
      <h3 class="card-title">Termindaten</h3>
      <div class="info-group">
        <p><strong>Datum:</strong> {{ formattedDate }}</p>
        <p><strong>Uhrzeit:</strong> {{ timeRange }}</p>
        <p><strong>Status:</strong>
          <span :class="`status-badge status-${appointment.status}`">
            {{ statusText }}
          </span>
        </p>
      </div>
    </div>

    <div class="card-section">
      <h3 class="card-title">Buchungsdetails</h3>
      <div class="info-group">
        <p><strong>Services:</strong> {{ appointment.services }}</p>
        <p v-if="appointment.notes"><strong>Notizen:</strong> {{ appointment.notes }}</p>
        <p><strong>Total:</strong> CHF {{ appointment.total }}</p>
      </div>
    </div>

    <div class="card-section">
      <h3 class="card-title">Aktionen</h3>
      <div class="actions">
        <select v-model="selectedStatus" class="status-select">
          <option value="pending">Ausstehend</option>
          <option value="confirmed">Bestätigt</option>
          <option value="completed">Abgeschlossen</option>
          <option value="cancelled">Storniert</option>
        </select>
        <AppButton @click="updateStatus">
          Status ändern
        </AppButton>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppButton from '../common/AppButton.vue'

const props = defineProps({
  appointment: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['status-updated'])

const selectedStatus = ref(props.appointment.status)

const statusText = computed(() => {
  const statuses = {
    pending: 'Ausstehend',
    confirmed: 'Bestätigt',
    completed: 'Abgeschlossen',
    cancelled: 'Storniert'
  }
  return statuses[props.appointment.status] || props.appointment.status
})

const formattedDate = computed(() => {
  const date = new Date(props.appointment.event_date)
  return date.toLocaleDateString('de-DE', {
    weekday: 'long',
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
})

const timeRange = computed(() => {
  return `${props.appointment.start_slot.toString().padStart(2, '0')}:00 - ${props.appointment.end_slot.toString().padStart(2, '0')}:00`
})

const updateStatus = () => {
  emit('status-updated', selectedStatus.value)
}
</script>

<style scoped>
.appointment-card {
  background-color: var(--color-bg-white);
  border-radius: 8px;
  padding: var(--spacing-lg);
}

.card-section {
  margin-bottom: var(--spacing-xl);
  padding-bottom: var(--spacing-lg);
  border-bottom: 1px solid var(--color-border);
}

.card-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
}

.card-title {
  color: var(--color-text-title);
  font-size: 18px;
  font-weight: 600;
  margin-bottom: var(--spacing-md);
}

.info-group p {
  margin: 8px 0;
  font-size: 16px;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 600;
}

.status-pending {
  background-color: #FFF3CD;
  color: #856404;
}

.status-confirmed {
  background-color: #D1ECF1;
  color: #0C5460;
}

.status-completed {
  background-color: #D4EDDA;
  color: #155724;
}

.status-cancelled {
  background-color: #F8D7DA;
  color: #721C24;
}

.actions {
  display: flex;
  gap: var(--spacing-md);
  align-items: center;
}

.status-select {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
}
</style>
