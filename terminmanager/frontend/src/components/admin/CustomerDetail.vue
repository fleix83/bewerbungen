<template>
  <div class="customer-detail">
    <div class="card-section">
      <h3 class="card-title">Kundenstammdaten</h3>
      <div class="info-group">
        <p><strong>Kundennummer:</strong> {{ customer.customer_number }}</p>
        <p><strong>Name:</strong> {{ customer.first_name }} {{ customer.last_name }}</p>
        <p><strong>Email:</strong> {{ customer.email }}</p>
        <p v-if="customer.phone"><strong>Telefon:</strong> {{ customer.phone }}</p>
        <p><strong>Erstellt am:</strong> {{ formatDate(customer.created_at) }}</p>
      </div>
    </div>

    <div class="card-section">
      <h3 class="card-title">Buchungshistorie</h3>
      <div v-if="customer.bookings && customer.bookings.length > 0" class="bookings-list">
        <div
          v-for="booking in customer.bookings"
          :key="booking.event_id"
          class="booking-item"
          @click="$emit('booking-selected', booking.event_id)"
        >
          <div class="booking-date">
            {{ formatDateTime(booking.event_date) }}
          </div>
          <div class="booking-time">
            {{ formatTimeRange(booking.start_slot, booking.end_slot) }}
          </div>
          <div class="booking-services">
            {{ booking.services }}
          </div>
          <div :class="`status-badge status-${booking.status}`">
            {{ getStatusText(booking.status) }}
          </div>
        </div>
      </div>
      <div v-else class="no-bookings">
        <p>Keine Buchungen vorhanden.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  customer: {
    type: Object,
    required: true
  }
})

defineEmits(['booking-selected'])

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('de-DE', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

const formatDateTime = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('de-DE', {
    weekday: 'short',
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

const formatTimeRange = (startSlot, endSlot) => {
  return `${startSlot.toString().padStart(2, '0')}:00 - ${endSlot.toString().padStart(2, '0')}:00`
}

const getStatusText = (status) => {
  const statuses = {
    pending: 'Ausstehend',
    confirmed: 'Bestätigt',
    completed: 'Abgeschlossen',
    cancelled: 'Storniert'
  }
  return statuses[status] || status
}
</script>

<style scoped>
.customer-detail {
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

.bookings-list {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.booking-item {
  padding: var(--spacing-md);
  background-color: var(--color-bg-card);
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.booking-item:hover {
  background-color: #FFED4E;
}

.booking-date {
  font-weight: 600;
  margin-bottom: 4px;
}

.booking-time {
  font-size: 14px;
  color: var(--color-text-muted);
  margin-bottom: 4px;
}

.booking-services {
  margin-bottom: 8px;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
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

.no-bookings {
  text-align: center;
  padding: var(--spacing-xl);
  color: var(--color-text-muted);
}
</style>
