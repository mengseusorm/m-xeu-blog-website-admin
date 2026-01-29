<template>
  <div>
    <h1 class="text-3xl font-bold mb-3">Blog</h1>

    <Card v-for="post in posts" :key="post.id" class="mb-3">
      <template #title>{{ post.title }}</template>
      <template #content>{{ post.body }}</template>
    </Card>
  </div>
</template>

<script setup>
import Card from 'primevue/card'

const posts = [
  { id: 1, title: 'First Post', body: 'This is my first blog post.' },
  { id: 2, title: 'Laravel 12', body: 'Laravel 12 + Vue is awesome.' },
]
</script>
