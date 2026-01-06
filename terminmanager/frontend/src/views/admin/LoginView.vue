<template>
  <div class="screen">
    <h1 class="screen-title">Admin Login</h1>

    <form @submit.prevent="handleLogin" class="login-form">
      <div class="form-group">
        <label for="username">Benutzername</label>
        <input
          id="username"
          v-model="username"
          type="text"
          required
          autocomplete="username"
        />
      </div>

      <div class="form-group">
        <label for="password">Passwort</label>
        <input
          id="password"
          v-model="password"
          type="password"
          required
          autocomplete="current-password"
        />
      </div>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <button type="submit" class="btn-primary" :disabled="authStore.loading">
        {{ authStore.loading ? 'Anmelden...' : 'Anmelden' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const username = ref('')
const password = ref('')
const error = ref('')

const handleLogin = async () => {
  error.value = ''

  const result = await authStore.login(username.value, password.value)

  if (result.success) {
    router.push('/admin/slots')
  } else {
    error.value = result.message
  }
}
</script>

<style scoped>
.login-form {
  max-width: 320px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: var(--spacing-md);
}

.form-group label {
  display: block;
  margin-bottom: var(--spacing-xs);
  color: var(--color-text-title);
  font-weight: 600;
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  color: #000;
}

.form-group input:focus {
  outline: none;
  border-color: #002198;
}

.error-message {
  color: #c00;
  margin-bottom: var(--spacing-md);
  font-size: 14px;
}

.btn-primary {
  width: 100%;
  padding: 14px 24px;
  background-color: var(--color-primary);
  color: var(--color-text-title);
  border: none;
  border-radius: 4px;
  font-family: var(--font-primary);
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  cursor: pointer;
  transition: opacity 0.2s;
}

.btn-primary:hover {
  opacity: 0.9;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
