<template>
    <div class="py-2 d-flex justify-content-center ">
        <div class="top-content-view d-flex justify-content-between ">
            <div class="title_page d-flex justify-content-center align-items-center">
                <h2 class="pt-3 itty">Inicio</h2>
            </div>
                <router-link :to="{name: 'publicacion.create'}" class="btn-crear-post"><div class="btnAñadir"></div></router-link>
        </div>
    </div>
    <div class="content-view">
        <div v-for="(publicacion, index) in publicaciones" class="mb-5 card-post" :class="bgClass(publicacion.user.style)">
            <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <router-link :to="{ name: 'usuario.mostrar', params: { username: publicacion.user.username } }">
                        <img :src="publicacion.user.image ? publicacion.user.image : '/images/user-default.png'" alt="" class="ms-2 img-perfil">
                    </router-link>
                    <div class="ms-3 d-flex flex-column justify-content-center">
                        <span class="itty username">{{ publicacion.user.name }} {{ publicacion.user.surname }}</span>
                        <span>@{{ publicacion.user.username }}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <img class="soporte" :src="index % 2 === 0 ? '/images/xinxeta.png' : '/images/celo.png'" :style="{transform: index % 2 === 0 ? 'rotate(0deg)' : 'rotate(175deg)'}" alt="">
                </div>
                <span class="pe-3">{{ formatearFecha(publicacion.created_at) }}</span>
            </div>
            <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="textColor itty">
            <div class="px-3 py-2 card-post-text">
                <span>{{ formatText(publicacion.texto) }}</span>
            </div>
            <!-- Contenedor para mostrar imagen. Nota: Solo aplica si contiene mas de una imagen-->
            <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                <img src="/images/celo.png" alt="" class="celo">
                <img class="img_post" :src="publicacion.media[0].original_url" alt="">
            </div>
            </router-link>
            <div class="card-post-bottom d-flex">
                <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion.id)">
                    <div class="pi p-3" @click="toggleLike(publicacion.id)">
                        <img v-if="comprobarLike(publicacion.id)" src="/images/like_check.svg" alt="Corazón activo" class="corazon-img">
                        <img v-else src="/images/like.svg" alt="Corazón inactivo" class="corazon-img">
                    </div>
                    <span class="itty number-of">{{ publicacion.likes_count }}</span>
                </div>
                <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="d-flex align-items-center textColor">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="/images/comentarios.svg" alt="icono de comentarios" class="mx-3 comment-icon"><span class="itty number-of">{{publicacion.comentarios_count}}</span>
                    </div>   
                </router-link>
            </div>
        </div>
    </div>
</template>


<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import { useStore } from 'vuex';

const publicaciones = ref();
const store = useStore(); // Obtenemos la instancia del store de Vuex
const usuarioActual = computed(() => store.state.auth.user);
const coloresAsignados = {}; // Objeto para mantener un registro de las clases de fondo asignadas

onMounted(() => {
    obtenerPublicaciones();
})

const obtenerPublicaciones = () => {
    axios.get('/api/publicacions')
    .then(response => {
        const publicacionesConLiked = response.data.map(publicacion => {
            return {
                ...publicacion,
                liked: publicacion.liked_by_current_user === true,
            };
        });
        publicaciones.value = publicacionesConLiked;
        console.log(publicaciones.value);
    })
}

const like = (id) => {
    axios.post('/api/like/add/' + id)
        .then(response => {
            console.log("Like");
            comprobarLike(id);
            obtenerPublicaciones();
        })
        .catch(error => {
            console.error("Error al dar like:", error);
        });
};

const comprobarLike = (id) => {
    const index = publicaciones.value.findIndex(publicacion => publicacion.id === id);
    if (index !== -1 && publicaciones.value[index].likes && usuarioActual.value) {
        const tieneLike = publicaciones.value[index].likes.some(like => like.id_usuario === usuarioActual.value.id);
        publicaciones.value[index].liked = tieneLike; // Actualiza el estado de liked en la publicación
        return tieneLike;
    } else {
        return false;
    }
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

// Esta función formatea el texto para hacer saltos de línea cuando es demasiado largo
const formatText = (texto) => {
    if (typeof texto === 'undefined') {
        return ''; // Devuelve una cadena vacía si texto es undefined
    }
    // Definir la longitud máxima antes de hacer un salto de línea
    const maxLength = 30;
    if (texto.length > maxLength) {
        return texto.match(new RegExp('.{1,' + maxLength + '}', 'g')).join('\n');
    }
    return texto;
};

function bgClass(color) {
      switch(color) {
        case 1:
          return 'bg-1';
        case 2:
          return 'bg-2';
        case 3:
          return 'bg-3';
        case 4:
          return 'bg-4';
        default:
          return ''; // Ninguna clase aplicada si no coincide con los casos anteriores
      }
    }



</script>
<style>

.btnAñadir {
    background-image: url(/images/btn-añadir.svg);
    background-size: cover;
    background-repeat: no-repeat;
    width: 60px;
    height: 60px;
}

.btnAñadir:hover {
    filter: drop-shadow(4px 4px 4px rgba(0, 0, 0, 0.5));
    /* transition: all 0.25s ease-in-out;
    transform: rotate(-3deg); */
}

.btnAñadir:target {
    outline: none;
}
.celo{
    transform: rotate(105deg);
    height: 50px;
    position: absolute;
    box-shadow: none;
    z-index: 5;
    top: 2%;
}

.corazon-img{
    height: 20px;
}
.comment-icon{
    height: 22px;
}
</style>