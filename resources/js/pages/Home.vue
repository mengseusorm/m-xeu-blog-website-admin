<template>
  <div>
    <h1 class="text-3xl font-bold mb-3">Dashboard</h1>
    <p>Welcome to my Laravel + Vue + PrimeVue website 🚀</p>
    <Button label="Get Started" class="mt-3" />
  </div>
   <div class="card"> 
        <CreatePostModal/>
    </div>
</template>

<script>
import Button from 'primevue/button' 
import CreatePostModal from './post/CreatePostModal.vue';
export default {
    components:{
        Button,
        CreatePostModal
    },  
    data() {
        return {
            products: null
        };
    },
    mounted() { 
    }
};
</script>
