<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
    <Card class="w-full max-w-md text-center shadow-lg">
      <template #header>
        <div class="bg-gradient-to-r from-red-500 to-pink-600 p-6">
          <i class="pi pi-sign-out text-4xl text-white"></i>
        </div>
      </template>
      <template #content>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Logging out...</h2>
        <p class="text-gray-600 mb-6">You are being logged out. Please wait.</p>
        <ProgressSpinner
          style="width: 50px; height: 50px"
          aria-label="Loading"
          class="mx-auto"
        />
      </template>
    </Card>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Card from 'primevue/card'
import ProgressSpinner from 'primevue/progressspinner'

const router = useRouter()

onMounted(async () => {
  try {
    // Call logout endpoint
    await axios.post('/logout')
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    // Clear auth token
    localStorage.removeItem('auth_token')
    delete axios.defaults.headers.common['Authorization']

    // Redirect to login
    setTimeout(() => {
      router.push('/login')
    }, 1500)
  }
})
</script>
