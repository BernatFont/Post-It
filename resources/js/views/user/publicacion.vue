<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between searchContainer row topbar-title">
            <span class="pt-2 itty col-12 pl-5 title-target">{{$t('post')}}</span>
        </div>
    </div>
    <div class="mainPrincipal itty">
        <div class="content-view" v-if="publicacion">
            <div class="pt-2 card-post m-auto" :class="bgClass(publicacion.user.style)">
                <div class="px-2 card-post-top d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <router-link :to="{ name: 'usuario.mostrar', params: { username: publicacion.user.username } }">
                            <div class="contenedor-img-perfil">
                                <img :src="publicacion.user.media[0]?.original_url ? publicacion.user.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario" class="img-perfil">
                            </div>
                        </router-link>
                        <div class="d-flex flex-column justify-content-center">
                            <span class="px20">{{ publicacion.user.name }} {{ publicacion.user.surname }}</span>
                            <span class="font2">@{{ publicacion.user.username }}</span>
                        </div>
                    </div>
                    <span class="pe-4">{{ formatearFecha(publicacion.created_at) }}</span>
                </div>
                <div v-if="publicacion.texto != '‎'" class="px-2 py-2 card-post-text">
                    <span class="px20">{{ publicacion.texto }}</span>
                </div>
                <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                    <img src="/images/celo.png" alt="Celo que sujeta la imagen al post" class="celo">
                    <img :src="publicacion.media[0].original_url" alt="imagen de la publicacion" class="img_post">
                </div>
                <div class="d-flex justify-content-between">
                    <div class="card-post-bottom d-flex">
                        <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion)">
                            <div class="pi p-3">
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
                    <div class="card-post-bottom d-flex" v-if="usuarioActual.id == publicacion.user.id">
                        <router-link :to="{name: 'publicacion.update'}" class="d-flex align-items-center justify-content-center">
                            <div class="mx-3" >
                                <div class="img-btn-edit"></div>
                            </div>
                        </router-link>              
                        <div class="d-flex align-items-center justify-content-center">
                            <div @click="eliminarPublicacion()" class="mx-3" >
                                <div class="img-btn"></div>
                            </div>
                        </div>              
                    </div>
                </div>
            </div>
            <div class="">
                <div class=""> <!--Seccion donde escribir un comentario -->
                    <form class="formularioMensaje d-flex justify-content-center align-items-center"  @submit.prevent="addComentario(comentario.contenido, publicacion)"> <!-- Formulario para escribir un nuevo mensaje -->
                        <div class="contenedor-sendmsg d-flex justify-content-center align-items-center">
                            <div class="px-3 py-2 card-post-text w-100">
                                <textarea v-model="comentario.contenido"  class="form-control itty textarea" @input="checkMaxLength" maxlength="255" :placeholder="$t('write')"></textarea>
                                <div v-if="maxLenghtTexto(comentario.contenido)" class="ml-3">{{ $t('limit_characters_255') }}</div>
                            </div>
                        </div>
                        <div class="botonEnviar-contenedor">
                            <div class="sticky-btn-sticker bg-3c"></div>
                            <button type="submit" class="btnSticky sticky-btn-1 bg-3 itty">{{ $t('publish') }}</button>
                        </div>
                    </form>    
                </div>
                <!--Seccion donde mostrar los comentarios -->
                <div v-for="comentario in publicacion.comentarios" class="comments mb-3 p-5"> 
                    <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: comentario.user.username } }">
                                    <div class="contenedor-img-perfil">
                                        <img :src="comentario.user.media[0]?.original_url ? comentario.user.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario" class="img-perfil">
                                    </div>
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
                        <div @click="eliminarComentario(comentario.id)">
                            <div class="img-btn"></div>
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

    .btn-eliminar {
        width: 100%;
        text-align: center;
        font-size: 1.35rem;
        box-shadow: none;
        background-color: none;
        padding: 12px;
        transition: box-shadow 0.25s ease-in-out;
    }
    .contenedor-publicacion{
        padding-top: 50px;
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

    .textarea-1{
        resize: none;
        height: 100px;
    }

    .corazon-img{
        height: 20px;
    }
    .comment-icon{
        height: 22px;
    }

    .contenedor-sendmsg {
        background-image: url(/images/papel-comment.svg);
        background-size: cover;
        background-position: center;
        padding: 20px;
        width: 65%;
        margin-top: 20px;
        height: 200px;

    }

    .textarea{
        resize: none;
        height: 115px;
        width: 100%;
        background-color: none;
        border: 0 none;
        background: transparent;
        outline: none;
        box-shadow: none;
        font-size: 1.5rem;
    }

    .textarea:focus{
        border: 0 none;
        background: transparent;
        box-shadow: none;
    }

    .botonEnviar-contenedor {
        display: flex;
        margin-top: 20px;
        width: 25%;
        height: 70px;
    }

    .formularioMensaje {
        display: flex;
        flex-direction: column;
    }

@media (max-width: 1200px){

    .contenedor-sendmsg {
        background-image: url(/images/papel-comment.svg);
        background-size: cover;
        background-position: center;
        padding: 20px;
        width: 100%;
        margin-top: 20px;
        height: 200px;

    }

    .formularioMensaje {
        display: flex;
        flex-direction: column;
    }

    .botonEnviar-contenedor {
        display: flex;
        margin-top: 20px;
        width: 50%;
        height: auto;
        margin-bottom: 20px;
    }
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
const addComentario = (contenido, publicacion) => {
    if(contenido == undefined) { 
       alert("Error: Debes escribir el comentario antes de publicarlo")
    } else {
        axios.post('/api/comentario/add/' + id, { contenido: contenido })
        .then(response => {
            console.log("Comentario añadido correctamente");
            const comentarioId = response.data.data.id;
            obtenerPublicacion();
            enviarNotificacion(publicacion, comentarioId, 1); // Envia la publicacion, el id del comentario recien creado y el tipo de interaccion.
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
            const like = comprobarLike(publicacion);
            obtenerPublicacion();
            if(like == false) {
                enviarNotificacion(publicacion, publicacion.id, 0);
            }
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

const enviarNotificacion = (publicacion, contenido, tipo) => {
    const remitente = usuarioActual.value.id;
    const destinatario = publicacion.id_usuario; 
    const id = contenido;
    const interaccion = tipo;


    axios.post(`/api/notificacion`, {
        remitente: remitente,
        destinatario: destinatario,
        id: id,
        interaccion: interaccion,
    }).then(response => {
            console.log("Notificación enviada correctamente");
        })
        .catch(error => {
            console.error("Error al enviar la notificación:", error);
        });
}

    const maxLenghtTexto = (texto) => {
        const maxLength = 300;

        if ((typeof texto === 'undefined') || (texto.length != maxLength)) {
            return false; // Devuelve una cadena vacía si texto es undefined
        }
        if (texto.length >= maxLength) {
            return true;
        }
    }

function bgClass(color) {
    switch(color) {
    case 2:
        return 'bg-2';
    case 3:
        return 'bg-3';
    case 4:
        return 'bg-4';
    case 5:
        return 'bg-5';
    case 6:
        return 'bg-6';
    default:
        return 'bg-1'; // Clase por defecto
    }
}
</script>
