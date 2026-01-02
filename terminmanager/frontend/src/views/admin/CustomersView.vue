<template>
  <div class="screen">
    <h1 class="screen-title">Kundenliste</h1>

    <div v-if="loading" class="loading">Laden...</div>

    <CustomerList
      v-else
      :customers="customers"
      @customer-selected="handleCustomerSelected"
      @sort-changed="handleSortChanged"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { customersAPI } from '../../services/api'
import CustomerList from '../../components/admin/CustomerList.vue'

const router = useRouter()
const loading = ref(true)
const customers = ref([])
const sortBy = ref('created_at')
const sortOrder = ref('DESC')

const loadCustomers = async () => {
  loading.value = true
  try {
    const response = await customersAPI.getAll()
    customers.value = response.data
  } catch (error) {
    console.error('Failed to load customers:', error)
    customers.value = []
  } finally {
    loading.value = false
  }
}

const handleCustomerSelected = (customerId) => {
  router.push(`/admin/kunde/${customerId}`)
}

const handleSortChanged = (sortOptions) => {
  sortBy.value = sortOptions.sortBy
  sortOrder.value = sortOptions.sortOrder
  loadCustomers()
}

onMounted(() => {
  loadCustomers()
})
</script>

<style scoped>
.loading {
  text-align: center;
  padding: var(--spacing-xl);
  font-size: 18px;
  color: var(--color-text-muted);
}
</style>
