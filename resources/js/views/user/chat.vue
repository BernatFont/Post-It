<!--Chat individual con mensajes-->
<template>
    {{ mensajes }}
</template>

<script setup>
    import { onMounted, ref , computed} from "vue";
    import { useStore } from 'vuex';
    import { useRoute } from 'vue-router';
    
    /* Obtenemos datos del usuario */
    const store = useStore();
    const route = useRoute(); 
    const userLogin = computed(() => store.state.auth.user); // Usuario actual con sesion iniciada
    const id = route.params.id; // Obtiene el chat por la ruta
    const mensajes = ref(null);

    onMounted(() => {
        axios.get('/api/chat/mensajes' + id)
            .then(response => {
                mensajes.value = response.data;
                console.log(mensajes.value);
                
            })
            .catch(error => {
            console.error('Error al obtener los mensajes del chat:', error);
        }); 
    })
</script>

<style>
   
</style>
