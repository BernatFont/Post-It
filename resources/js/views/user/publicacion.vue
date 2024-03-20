<template>
    <div v-if="publicacion" class="card-post mb-5 content-view ">
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
        <div class="px-3 py-2 card-post-text">
            <span>{{ publicacion.texto }}</span>
        </div>
        <div class="card-post-img d-flex justify-content-center">
            <img src="/images/prueba.jpg" alt="">
        </div>
        <div class="card-post-bottom d-flex">
            <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion)" >
                <i class="pi p-3" :class="publicacion.liked ? 'pi-heart-fill' : 'pi-heart'"></i>
                <span>{{ publicacion.likes_count }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="pi pi-comment p-3"></i><span>{{publicacion.comentarios_count}}</span>
            </div>
        </div>
        <div class="card-post-comentarios">
            <div class="px-3 pb-2">
                <div class=""> <!--Seccion donde escribir un comentario -->
                    <!-- <span>Comentario:</span> -->
                    <form @submit.prevent="addComentario(comentario.contenido)" class="create-post-form">
                        <div class="form-group mb-1">
                            <textarea v-model="comentario.contenido" class="form-control textarea" @input="checkMaxLength" maxlength="300" placeholder="Publica un comentario..."></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-postit">Publicar comentario</button>
                        </div>
                    </form>
                </div>
                <!--Seccion donde mostrar los comentarios -->
                <div v-for="comentario in publicacion.comentarios" class="card-post mb-5"> 
                    <div class="d-flex flex-row align-items-center">
                        <img src="/images/placeholder.jpg" alt="" class="ms-2 img-perfil">
                        <div class="d-flex flex-column m-auto">
                            <div class="d-flex flex-row justify-content-between">
                                <span>{{ comentario.user.name }}{{ comentario.user.surname }}</span>
                                <span>{{ formatearFecha(comentario.created_at) }}</span>
                            </div>
                            
                            <span>@{{ comentario.user.username }}</span>
                        </div> 
                    </div>
                    <div>{{ comentario.contenido }}</div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <p>Cargando...</p>
    </div>
        
</template>
<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from 'vue-router';

const comentarios = ref();
const comentario = ref({});
const router = useRouter();
const id = router.currentRoute.value.params.id; // Obtener el ID de la URL
// console.log(id);
const publicacion = ref(null);

// Funcion que publica un comentario en una publicacion, si el contenido del comentario esta vacio muestra mensaje de error
const addComentario = (contenido) => {
    if(contenido == undefined) { 
        alert("Falta el texto")
    } else {
        axios.post('/api/comentario/add/' + id, { contenido: contenido })
        .then(response => {
            console.log("Comentario");
        }).catch(error => {
            console.error("Error al publicar el comentario:", error);
        });
    }
}
onMounted(() => {
    axios.get('/api/publicacions/' + id)
        .then(response => {
            publicacion.value = response.data;
            console.log(publicacion.value);
        })
        .catch(error => {
            console.error('Error al obtener la publicación:', error);
        });
});

const like = (publicacion) => {
    axios.post('/api/like/add/' + publicacion.id)
        .then(response => {
            console.log("Like");
            // Actualiza la cantidad de likes en la vista
            publicacion.likes_count += response.data.liked ? 1 : -1;
            publicacion.liked = response.data.liked;
        })
        .catch(error => {
            console.error("Error al dar like:", error);
        });
};

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

</script>

<style>
.card-post-bottom{
    border-radius: 0px;
    background-color: #ffffff;
}

.post-text {
    max-width: 100%;
    word-wrap: break-word; /* Permite que el texto se divida en varias líneas */
}

.textarea{
    resize: none;
    height: 100px;
    background-color: var(--bs-gray-100)
}

</style>