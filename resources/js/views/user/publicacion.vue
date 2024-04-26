<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between searchContainer row topbar-title">
            <span class="pt-2 itty col-12 pl-5 title-target">{{$t('post')}}</span>
        </div>
    </div>
    <div class="mainPrincipal">
        <div class="content-view" v-if="publicacion">
            <div class="card-post m-auto pt-3 px-2">
                <div class="card-post-top d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <router-link :to="{ name: 'usuario.mostrar', params: { username: publicacion.user.username } }">
                            <img src="/images/placeholder.jpg" alt="" class="ms-2 img-perfil">
                        </router-link>
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
                <div class="card-post-img d-flex justify-content-center" v-if="publicacion.media.length > 0">
                    <img :src="publicacion.media[0].original_url" alt="">
                </div>
                <div class=" d-flex justify-content-between">
                    <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion)" >
                        <i class="pi p-3" :class="comprobarLike(publicacion) ? 'pi-heart-fill' : 'pi-heart'"></i><span>{{ publicacion.likes_count }}</span>
                        <i class="pi pi-comment p-3"></i><span>{{publicacion.comentarios_count}}</span>
                    </div>
                    <div class="d-flex align-items-center mr-4">
                        <span>{{ obtenerFecha(publicacion.created_at) }}</span>
                    </div>
                </div>
            </div>
            <div class="w-75 m-auto">
                <div class="d-flex py-5">
                    <router-link :to="{name: 'publicacion.update'}" class="postit-btn px-5">{{$t('modify')}}</router-link>
                    <button @click="eliminarPublicacion()" class="delete-btn ms-3">
                        <div class="img-btn"></div>
                    </button>
                </div>
                
                <div>
                    <div class="px-3 pb-2">
                        <div class=""> <!--Seccion donde escribir un comentario -->
                            <!-- <span>Comentario:</span> -->
                            <form @submit.prevent="addComentario(comentario.contenido)" class="create-post-form">
                                <div class="form-group mb-1">
                                    <textarea v-model="comentario.contenido" class="form-control textarea" @input="checkMaxLength" maxlength="300" placeholder="Publica un comentario..."></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="my-3 postit-btn">Publicar comentario</button>
                                </div>
                            </form>
                        </div>
                        <!--Seccion donde mostrar los comentarios -->
                        <div v-for="comentario in publicacion.comentarios" class="comments mb-3 p-5"> 
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <router-link :to="{ name: 'usuario.mostrar', params: { username: comentario.user.username } }">
                                            <img src="/images/placeholder.jpg" alt="" class="img-perfil">
                                        </router-link>
                                        <div class="ms-3 d-flex flex-column">
                                            <span class="itty">{{ comentario.user.name }}{{ comentario.user.surname }}</span>
                                            <span>@{{ comentario.user.username }}</span>
                                        </div>
                                    </div>
                                <span>{{ formatearFecha(comentario.created_at) }}</span>
                            </div>
                            <div class="mb-2">{{ comentario.contenido }}</div>
                            <div class="d-flex justify-content-end">
                                <button @click="eliminarComentario(comentario.id)" class="delete-btn ms-3">
                                    <div class="img-btn"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <p>Cargando...</p>
        </div>
    </div>
        
</template>

<style>

.contenedor-publicacion{
    padding-top: 50px;
}

.card-post{
    background-color: var(--cuarto);
}
.card-post-img img {
    transform: rotate(0deg);
}
.comments{
    background-image: url(/public/images/papel-comment.svg);
    background-size: cover;
    background-repeat: no-repeat;
}
.post-text {
    max-width: 100%;
    word-wrap: break-word; /* Permite que el texto se divida en varias líneas */
}

.textarea{
    resize: none;
    height: 100px;
}

</style>

<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const comentarios = ref([]);
const comentario = ref({});
const router = useRouter();
const id = router.currentRoute.value.params.id; // Obtener el ID de la URL
const store = useStore(); // Obtenemos la instancia del store de Vuex
const usuarioActual = computed(() => store.state.auth.user);

const publicacion = ref(null);

onMounted(() => {
    obtenerPublicacion();
});

const obtenerPublicacion = (contenido) => {
    axios.get('/api/publicacions/' + id)
        .then(response => {
            publicacion.value = response.data;
            console.log(publicacion.value);
            comprobarLike(publicacion); // Llama a funcion para verificar like
        })
        .catch(error => {
            console.error('Error al obtener la publicación:', error);
        })
};

// Funcion que publica un comentario en una publicacion, si el contenido del comentario esta vacio muestra mensaje de error
const addComentario = (contenido) => {
    if(contenido == undefined) { 
       alert("Error: Debes escribir el comentario antes de publicarlo")
    } else {
        axios.post('/api/comentario/add/' + id, { contenido: contenido })
        .then(response => {
            console.log("Comentario añadido correctamente");
            obtenerPublicacion();
        }).catch(error => {
            console.error("Error al publicar el comentario:", error);
        });
    }
};

const eliminarComentario = (idComentario) => {
    if (confirm("¿Estás seguro de que quieres eliminar este comentario?")) {
        axios.delete('/api/comentario/delete/' + idComentario)
            .then(response => {
                console.log("Comentario eliminado exitosamente");
                obtenerPublicacion();
            })
            .catch(error => {
                console.error("Error al eliminar el comentario:", error);
            });
    }
}
const eliminarPublicacion = () => {
    if (confirm("¿Estás seguro de que quieres eliminar esta publicación?")) {
        axios.delete('/api/publicacions/delete/' + id)
            .then(response => {
                console.log("Publicación eliminada exitosamente");
                router.push({ name: 'feed' });
            })
            .catch(error => {
                console.error("Error al eliminar la publicación:", error);
            });
    }
};


const like = (publicacion) => {
    axios.post('/api/like/add/' + publicacion.id)
        .then(response => {
            console.log("Like");
            comprobarLike(publicacion);
            obtenerPublicacion();
        })
        .catch(error => {
            console.error("Error al dar like:", error);
        });
};

const comprobarLike = (publicacion) => {
    if (publicacion && publicacion.likes && usuarioActual.value) {
        const tieneLike = publicacion.likes.some(like => like.id_usuario === usuarioActual.value.id);
        publicacion.liked = tieneLike; // Actualiza el estado de liked en la publicación
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
function obtenerFecha(fecha) {
    // Crear un objeto de fecha a partir de la cadena proporcionada
    const fechaObjeto = new Date(fecha);
    
    // Obtener los componentes de la fecha
    const horas = fechaObjeto.getHours().toString().padStart(2, '0'); // Añadir ceros a la izquierda si es necesario
    const minutos = fechaObjeto.getMinutes().toString().padStart(2, '0');
    const dia = fechaObjeto.getDate().toString().padStart(2, '0');
    const mes = (fechaObjeto.getMonth() + 1).toString().padStart(2, '0'); // El mes es base 0, por lo que se agrega 1
    const año = fechaObjeto.getFullYear();

    // Devolver la fecha formateada en el formato deseado
    return `${horas}:${minutos} ${dia}/${mes}/${año}`;
}

</script>