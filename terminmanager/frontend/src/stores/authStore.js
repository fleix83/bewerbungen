import { defineStore } from 'pinia'
import { ref } from 'vue'
import { authAPI } from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false)
  const username = ref(null)
  const loading = ref(false)

  async function checkAuth() {
    try {
      const response = await authAPI.check()
      isAuthenticated.value = response.data.authenticated
      username.value = response.data.username
    } catch (error) {
      isAuthenticated.value = false
      username.value = null
    }
  }

  async function login(user, password) {
    loading.value = true
    try {
      const response = await authAPI.login(user, password)
      if (response.data.success) {
        isAuthenticated.value = true
        username.value = user
        return { success: true }
      }
      return { success: false, message: response.data.message }
    } catch (error) {
      const message = error.response?.data?.message || 'Login fehlgeschlagen'
      return { success: false, message }
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await authAPI.logout()
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      isAuthenticated.value = false
      username.value = null
    }
  }

  return {
    isAuthenticated,
    username,
    loading,
    checkAuth,
    login,
    logout
  }
})
