<template>
  <div>
    <Menubar v-if="!isAuthPage" :model="items">
      <template #end>
        <div class="flex items-center gap-2">
          <!-- <span v-if="isAuthenticated" class="text-sm text-gray-700 mr-3">
            <i class="pi pi-user mr-1"></i>{{ userEmail }}
          </span> -->
          <Button icon="pi pi-user" rounded variant="text" @click="visible = true" />
          <Button v-if="!isAuthenticated" label="Login" icon="pi pi-sign-in" class="p-button-rounded p-button-text"
            @click="router.push('/login')" />
          <Button v-else label="Logout" icon="pi pi-power-off" class="p-button-rounded p-button-text"
            @click="handleLogout" />
        </div>
      </template>
    </Menubar>
    <div :class="{ 'p-4': !isAuthPage }">
      <RouterView />
    </div>
  </div>
  <Dialog v-model:visible="visible" header="Edit Profile" :style="{ width: '25rem' }">
    <form @submit.prevent="updateProfile">
      <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
      <div class="flex items-center gap-4 mb-4">
        <label for="username" class="font-semibold w-24">Username</label>
        <InputText v-model="userName" class="flex-auto" autocomplete="off" />
      </div>
      <div class="flex items-center gap-4 mb-8">
        <label for="email" class="font-semibold w-24">Email</label>
        <InputText v-model="userEmail" class="flex-auto" autocomplete="off" />
      </div>
      <div class="flex justify-end gap-2">
        <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
        <Button type="submit" label="Save" @click="visible = false"></Button>
      </div>
    </form>
  </Dialog>
</template>

<script>
import Menubar from 'primevue/menubar'
import Button from 'primevue/button'
import { useRouter, RouterView, useRoute } from 'vue-router'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'

export default {
  components: {
    Menubar,
    Button,
    RouterView,
    Dialog,
    InputText
  },
  data() {
    return {
      visible: false,
      router: useRouter(),
      route: useRoute(),
      isAuthenticated: false,
      userName: '',
      userEmail: '',
      items: [
        {
          label: 'Home',
          icon: 'pi pi-home',
          command: () => this.router.push('/')
        },
        {
          label: 'Blog',
          icon: 'pi pi-book',
          command: () => this.router.push('/blog')
        },
        {
          label: 'Post',
          icon: 'pi pi-th-large',
          command: () => this.router.push('/post')
        },
        {
          label: 'About',
          icon: 'pi pi-info-circle',
          command: () => this.router.push('/about')
        },
      ]
    }
  },
  computed: {
    isAuthPage() {
      return this.route.name === 'Login' || this.route.name === 'Logout'
    }
  },
  mounted() {
    this.checkAuthStatus()
  },
  watch: {
    'route.name': {
      handler() {
        this.checkAuthStatus()
      }
    }
  },
  methods: {
    checkAuthStatus() {
      const token = localStorage.getItem('auth_token')
      this.isAuthenticated = !!token
      const userInfo = localStorage.getItem('user_email')
      const userName = localStorage.getItem('user_name')

      console.log('User Info:', userInfo);
      console.log('User Name:', userName);

      if (userInfo) {
        this.userEmail = userInfo,
          this.userName = userName
      }
    },
    handleLogout() {
      this.router.push('/logout')
    },
    updateProfile() {
      // Implement profile update logic here
      console.log('Profile updated:', this.userName, this.userEmail)
    }
  }
}
</script>
