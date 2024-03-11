<template>
    <form @submit.prevent="addPublicacion">
        <div class="form-group mb-2">
            <label>ID</label><span class="text-danger"> *</span>
            <input v-model="publicacion.id_usuario" type="text" class="form-control">
        </div>
        <div class="form-group mb-2">
            <label>Descripción</label><span class="text-danger"> *</span>
            <textarea v-model="publicacion.texto" class="form-control" rows="3" placeholder="Escribe aquí..."></textarea>
        </div>

        <button type="submit" class="btn btn-postit mt-4 mb-4">Añadir Tarea</button>

    </form>
</template>

<script setup>
    import axios from "axios";
    import { ref } from "vue";

    
    const publicacion = ref({});
    const strError = ref();
    const strSuccess = ref();

    function addPublicacion(){
        axios.post('/api/publicacions',publicacion.value)
        .then(response => {
            console.log(response);
            strSuccess.value = response.data.success
            strError.value = ''
            // Redirige a otra página después de una tarea exitosa
            router.push('/inicio');
        }).catch(error => {
            console.log(error);
            strError.value = error.response.data.message;
            strSuccess.value = ''

        })
    }  

</script>
<style>

</style>