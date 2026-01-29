<template>
    <div class="min-h-screen flex items-center justify-center p-4">
        <Toast/>
        <Card class="shadow-lg w-full max-w-md">
            <template #content>
                <div class="w-full flex flex-col items-center justify-center">
                    <div class="w-full text-center mb-4">
                        <h2 class="text-2xl font-bold">Login</h2> 
                    </div>
                    <form @submit.prevent="handleLogin" class="w-full space-y-4">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="text-sm font-semibold">Email Address</label>
                            <InputText 
                                id="email" 
                                v-model="form.email" 
                                type="email" 
                                placeholder="Enter your email"
                                class="w-full"
                                @keyup.enter="handleLogin"
                            />
                            <small v-if="errors.email" class="text-red-500">{{ errors.email[0] }}</small>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="password" class="text-sm font-semibold">Password</label>
                            <InputText 
                                id="password" 
                                v-model="form.password" 
                                placeholder="Enter your password"
                                :feedback="false" 
                                toggle-mask 
                                class="w-full"
                                type="password"
                                @keyup.enter="handleLogin"
                            />
                            <small v-if="errors.password" class="text-red-500">{{ errors.password[0] }}</small>
                        </div>
                        <div class="flex items-center">
                            <Checkbox v-model="form.remember_me" input-id="remember" binary class="mr-2" />
                            <label for="remember" class="text-sm">Remember me</label>
                        </div>
                        <Message v-if="errors.general" severity="error" class="w-full" :closable="false" text>
                            {{ errors.general }}
                        </Message>
                        <Message v-if="loading" severity="info" class="w-full" :closable="false" text>
                            Logging in...
                        </Message>
                        <Button 
                            type="submit" 
                            label="Login" 
                            icon="pi pi-user" 
                            class="w-full "
                            :loading="loading"
                            severity="primary"
                        />
                    </form>
                </div>  
            </template>
        </Card> 
    </div>
</template>

<script>
import { useRouter } from 'vue-router'
import axios from 'axios'
import Card from 'primevue/card'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Checkbox from 'primevue/checkbox'
import Message from 'primevue/message'
import Divider from 'primevue/divider' 
import Toast from 'primevue/toast'

export default {
    components: {
        Card,
        Button,
        InputText,
        Password,
        Checkbox,
        Message,
        Divider, 
        Toast
    },
    data() {
        return {
            router: useRouter(),
            loading: false,
            errors: {
                email: null,
                password: null,
                general: null
            },
            form: {
                email: 'admin@example.com',
                password: 'password',
                remember_me: false
            }
        }
    },
    methods: { 
        async handleLogin() { 
            this.errors.email = null
            this.errors.password = null
            this.errors.general = null
 
            if (!this.form.email) {
                this.errors.email = ['Email is required']
                return
            }
            if (!this.form.password) {
                this.errors.password = ['Password is required']
                return
            }

            this.loading = true

            try {
                const response = await axios.post('/api/auth/login', {
                    email: this.form.email,
                    password: this.form.password,
                    remember: this.form.remember_me
                }) 
                if (response.data.token) { 
                    localStorage.setItem('auth_token', response.data.token)
                    localStorage.setItem('user_email', response.data.user.email)
                    localStorage.setItem('user_name', response.data.user.name)
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}` 
                    console.log(response.data.message)
                    this.$toast.add({ severity: 'warn', summary: 'Login Success!', detail: 'Message Content', life: 3000 }); 
                    // Redirect to dashboard
                    this.router.push('/post')
                }
            } catch (error) {
                if (error.response?.status === 422) {
                    // Validation errors
                    if (error.response.data.errors) {
                        Object.assign(this.errors, error.response.data.errors)
                    }
                } else if (error.response?.status === 401) {
                    this.errors.general = 'Invalid email or password'
                } else {
                    this.errors.general = error.response?.data?.message || 'Login failed. Please try again.'
                }
            } finally {
                this.loading = false
            }
        },
        goToSignUp() {
            // Placeholder for sign up navigation
            this.$router.push('/signup')
        }
    }
}
</script>
