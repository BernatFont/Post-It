<template>
    <div class="py-2 d-flex justify-content-center">
        <div class="top-content-view d-flex justify-content-between">
            <h2>Home</h2>
            <div>
                <router-link :to="{name: 'publicacion.create'}" class="btn btn-postit btn-crear-post px-5">Publicar post</router-link>
            </div>
        </div>
    </div>
    <div class="content-view">
        <div v-for="publicacion in publicaciones" class="card-post mb-5">
            <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <img src="/images/placeholder.jpg" alt="" class="ms-2 img-perfil">
                    <div class="ms-3 d-flex flex-column justify-content-center">
                        <span>{{ publicacion.user.name }} {{ publicacion.user.surname }}</span>
                        <span>@{{ publicacion.user.username }}</span>
                    </div>
                </div>
                <span class="pe-3">{{ formatearFecha(publicacion.created_at) }}</span>
            </div>
            <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="textColor">
            <div class="px-3 py-2 card-post-text">
                <span>{{ publicacion.texto }}</span>
            </div>
            <div class="card-post-img d-flex justify-content-center">
                <img src="/images/prueba.jpg" alt="">
            </div>
            </router-link>
            <div class="card-post-bottom d-flex">
                <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion.id)">
                    <i class="pi p-3" :class="publicacion.liked ? 'pi-heart-fill' : 'pi-heart'"></i>
                    <span>{{ publicacion.likes_count }}</span>
                </div>
                <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="textColor">
                    <div class="d-flex align-items-center">
                        <i class="pi pi-comment p-3 contentBlack"></i><span class="contentBlack">{{publicacion.comentarios_count}}</span>
                    </div>   
                </router-link>
            </div>
        </div>
    </div>
</template>


<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";

const publicaciones = ref();

const like = (id) => {
    axios.post('/api/like/add/' + id)
    .then(response => {
        console.log("Like");
        // Actualiza la cantidad de likes en la vista
        const index = publicaciones.value.findIndex(publicacion => publicacion.id === id);
        if (index !== -1) {
            publicaciones.value[index].likes_count += response.data.liked ? 1 : -1;
            publicaciones.value[index].liked = response.data.liked;
        }
    }).catch(error => {
        console.error("Error al dar like:", error);
    });

}

// Función para calcular la diferencia en minutos, horas y días
const calcularDiferencia = (fechaPublicacion) => {
  const fechaPublicacionObjeto = new Date(fechaPublicacion);
  const fechaActual = new Date();
  const diferenciaEnMs = fechaActual - fechaPublicacionObjeto;
  const diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
  const diferenciaEnHoras = Math.floor(diferenciaEnMinutos / 60);
  const diferenciaEnDias = Math.floor(diferenciaEnHoras / 24);
  return { minutos: diferenciaEnMinutos, horas: diferenciaEnHoras, dias: diferenciaEnDias };
};

// Función para formatear la fecha de publicación
const formatearFecha = (fechaPublicacion) => {
  const { minutos, horas, dias } = calcularDiferencia(fechaPublicacion);
  if (minutos <= 60) {
    return `Hace ${minutos} minutos`;
  } else if (horas < 24) {
    return `Hace ${horas} horas`;
  } else if (dias === 1) {
    return 'Publicado hace 1 día';
  } else {
    return `Hace ${dias} días`;
  }
};

onMounted(() => {
  axios.get('/api/publicacions')
    .then(response => {
        const publicacionesConLiked = response.data.map(publicacion => ({
            ...publicacion,
            liked: publicacion.liked_by_current_user === true
        }));
        publicaciones.value = publicacionesConLiked;
        console.log(publicaciones.value);
    })
})
</script>
<style>
    .btn-crear-post{
        font-weight: 600;
    }
</style>