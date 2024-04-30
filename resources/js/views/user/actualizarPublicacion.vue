<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <span class="pt-2 itty col-8 pl-5 title-target">{{$t('modify_post')}}</span>
        </div>
    </div>
    <div class="mainPrincipal" v-if="publicacion">
        <div class="content-view card">
            <div class="card-body">
                <div class="d-flex justify-content-between pb-2 mb-2">
                    <h5 class="card-title">Actualizar publicacion</h5>
                </div>

                <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ strSuccess }}</strong>
                </div>

                <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>{{ strError }}</strong>
                </div>

                <form @submit.prevent="actualizar">
                    <div class="form-group mb-2">
                        <label>Texto</label><span class="text-danger"> *</span>
                        <textarea class="form-control" rows="3" v-model="publicacion.texto" placeholder="Texto"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-4 mb-4">Aplicar cambios</button>
                </form>
            </div>
        </div>
    </div>
    <div v-else class="mainPrincipal">
        <div class="content-view card">
            <p>Cargando...</p>
        </div>
    </div>
    
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const id = router.currentRoute.value.params.id;
const publicacion = ref(null);
const strSuccess = ref(null);
const strError = ref(null);

onMounted(() => {
    axios.get('/api/publicacions/' + id)
        .then(response => {
            publicacion.value = response.data;
        })
        .catch(error => {
            console.error('Error al obtener la publicación:', error);
        });
});

function actualizar() {
    axios.put('/api/publicacions/update/' + id, { texto: publicacion.value.texto })
        .then(response => {
            strError.value = "";
            strSuccess.value = response.data.success;
        })
        .catch(error => {
            strSuccess.value = "";
            strError.value = error.response.data.message;
        });
}
</script>

<style>

</style>
