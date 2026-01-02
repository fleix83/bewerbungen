<template>
  <div class="screen">
    <h1 class="screen-title">Termin Details</h1>

    <div v-if="loading" class="loading">Laden...</div>

    <AppointmentCard
      v-else-if="appointment"
      :appointment="appointment"
      @status-updated="handleStatusUpdate"
    />

    <div v-else class="error">
      <p>Termin nicht gefunden.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { appointmentsAPI } from '../../services/api'
import AppointmentCard from '../../components/admin/AppointmentCard.vue'

const route = useRoute()
const loading = ref(true)
const appointment = ref(null)

const loadAppointment = async () => {
  loading.value = true
  try {
    const response = await appointmentsAPI.getById(route.params.id)
    appointment.value = response.data
  } catch (error) {
    console.error('Failed to load appointment:', error)
    appointment.value = null
  } finally {
    loading.value = false
  }
}

const handleStatusUpdate = async (newStatus) => {
  try {
    await appointmentsAPI.updateStatus(route.params.id, newStatus)
    await loadAppointment()
    alert('Status erfolgreich aktualisiert')
  } catch (error) {
    console.error('Failed to update status:', error)
    alert('Fehler beim Aktualisieren des Status')
  }
}

onMounted(() => {
  loadAppointment()
})
</script>

<style scoped>
.loading,
.error {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
