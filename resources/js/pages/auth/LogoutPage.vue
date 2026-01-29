<template>
  <div class="min-h-screen flex items-center justify-center p-4">
    <Card class="shadow-lg w-full max-w-md rounded"> 
      <template #content>
        <h2 class="text-2xl font-bold mb-2">Logging out...</h2>
        <p class="mb-6">You are being logged out. Please wait.</p>
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
