<template>
  <div class="customer-list">
    <div class="list-controls">
      <select v-model="sortBy" class="sort-select" @change="$emit('sort-changed', { sortBy, sortOrder })">
        <option value="last_name">Nach Nachname</option>
        <option value="first_name">Nach Vorname</option>
        <option value="created_at">Nach Datum</option>
      </select>
      <button @click="toggleSortOrder" class="sort-button">
        {{ sortOrder === 'ASC' ? '↑ A-Z' : '↓ Z-A' }}
      </button>
    </div>

    <div class="table-wrapper">
      <table class="customers-table">
        <thead>
          <tr>
            <th>Vorname</th>
            <th>Nachname</th>
            <th>Email</th>
            <th>Erstellt am</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="customer in customers"
            :key="customer.id"
            @click="$emit('customer-selected', customer.id)"
            class="customer-row"
          >
            <td>{{ customer.first_name }}</td>
            <td>{{ customer.last_name }}</td>
            <td>{{ customer.email }}</td>
            <td>{{ formatDate(customer.created_at) }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="customers.length === 0" class="no-customers">
        <p>Keine Kunden gefunden.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  customers: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['customer-selected', 'sort-changed'])

const sortBy = ref('created_at')
const sortOrder = ref('DESC')

const toggleSortOrder = () => {
  sortOrder.value = sortOrder.value === 'ASC' ? 'DESC' : 'ASC'
  emit('sort-changed', { sortBy: sortBy.value, sortOrder: sortOrder.value })
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('de-DE')
}
</script>

<style scoped>
.customer-list {
  background-color: var(--color-bg-white);
  border-radius: 8px;
  padding: var(--spacing-lg);
}

.list-controls {
  display: flex;
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-lg);
}

.sort-select {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
}

.sort-button {
  padding: 12px 20px;
  background-color: var(--color-primary);
  color: var(--color-text-title);
  border: none;
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
}

.sort-button:hover {
  background-color: var(--color-primary-dark);
}

.table-wrapper {
  overflow-x: auto;
}

.customers-table {
  width: 100%;
  border-collapse: collapse;
}

.customers-table thead {
  background-color: var(--color-bg-card);
}

.customers-table th {
  padding: 12px;
  text-align: left;
  font-weight: 600;
  color: var(--color-text-title);
  border-bottom: 2px solid var(--color-border);
}

.customer-row {
  cursor: pointer;
  transition: background-color 0.2s;
}

.customer-row:hover {
  background-color: var(--color-bg-card);
}

.customers-table td {
  padding: 12px;
  border-bottom: 1px solid var(--color-border);
}

.no-customers {
  text-align: center;
  padding: var(--spacing-xl);
  color: var(--color-text-muted);
}
</style>
