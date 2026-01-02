<template>
  <div class="screen">
    <div class="header-with-back">
      <button @click="$router.push('/admin/kunden')" class="back-button">
        ← Zurück
      </button>
      <h1 class="screen-title">Kundendetail</h1>
    </div>

    <div v-if="loading" class="loading">Laden...</div>

    <CustomerDetail
      v-else-if="customer"
      :customer="customer"
      @booking-selected="handleBookingSelected"
    />

    <div v-else class="error">
      <p>Kunde nicht gefunden.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { customersAPI } from '../../services/api'
import CustomerDetail from '../../components/admin/CustomerDetail.vue'

const route = useRoute()
const router = useRouter()
const loading = ref(true)
const customer = ref(null)

const loadCustomer = async () => {
  loading.value = true
  try {
    const response = await customersAPI.getById(route.params.id)
    customer.value = response.data
  } catch (error) {
    console.error('Failed to load customer:', error)
    customer.value = null
  } finally {
    loading.value = false
  }
}

const handleBookingSelected = (eventId) => {
  router.push(`/admin/termin/${eventId}`)
}

onMounted(() => {
  loadCustomer()
})
</script>

<style scoped>
.header-with-back {
  margin-bottom: var(--spacing-lg);
}

.back-button {
  background: none;
  border: none;
  color: var(--color-text-title);
  font-size: 16px;
  cursor: pointer;
  padding: var(--spacing-sm);
  margin-bottom: var(--spacing-md);
  font-family: var(--font-primary);
  font-weight: 600;
}

.back-button:hover {
  text-decoration: underline;
}

.loading,
.error {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
