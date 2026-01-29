<template>
  <div>
    <Menubar :model="items" />
    <div class="p-4">
      <RouterView />
    </div>
  </div>
</template>

<script setup>
import Menubar from 'primevue/menubar'
import { useRouter, RouterView } from 'vue-router'

const router = useRouter()

const items = [
  {
    label: 'Home',
    icon: 'pi pi-home',
    command: () => router.push('/')
  },
  {
    label: 'Blog',
    icon: 'pi pi-book',
    command: () => router.push('/blog')
  },
  {
    label: 'Post',
    icon: 'pi pi-book',
    command: () => router.push('/post')
  },
  {
    label: 'About',
    icon: 'pi pi-info-circle',
    command: () => router.push('/about')
  },
]
</script>
