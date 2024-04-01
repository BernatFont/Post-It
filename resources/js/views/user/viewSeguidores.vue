<template>
    <div v-if="usuario" class="px-5">
        <div class="top-content-view w-100">
            <div v-for="usuario in usuario.seguidores">
                <p>Le sigue: {{ usuario.username }}</p>
            </div>
            {{ usuario }}
        </div>    
    </div>
</template>

<script setup>
    import { onMounted, ref , computed} from "vue";
    import { useStore } from 'vuex';
    import { useRoute } from 'vue-router';
    
    /* Obtenemos datos del usuario */
    const store = useStore();
    const route = useRoute(); 
    const userLogin = computed(() => store.state.auth.user); // Usuario actual con sesion iniciada
    const username = route.params.username; // Obtiene el usuario por la ruta
    const usuario = ref(null);

    onMounted(() => {
        axios.get('/api/usuario/' + username)
            .then(response => {
                usuario.value = response.data;
                console.log(usuario.value);
            })
    })

</script>

<style>
   
</style>
