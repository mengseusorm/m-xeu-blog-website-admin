
import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import App from './App.vue';
import router from './router' // ✅ correct path
import 'primeicons/primeicons.css'
import ToastService from 'primevue/toastservice';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(App);
app.use(ToastService);
app.use(PrimeVue, {
    theme: {
        preset: Aura
    }
}); 
app.use(PrimeVue);  
app.use(VueSweetalert2);

app.use(router) 
app.mount('#app');
