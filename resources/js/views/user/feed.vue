<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between align-items-center topbar-title col-12">
            <span class="pt-2 itty title-target">{{$t('home_name')}}</span>
            <div>
                <!-- Agregar un botón para cambiar el filtro -->
                <div @click="toggleFiltro" class="font1 btn-filtro">{{ filtroActivo ? i18n.t('show_all') : i18n.t('show_followed') }}</div>
            </div>
            <div class="notification-alert">
                <router-link :to="{name: 'publicacion.create'}" class="btn-crear-post"><div class="btnAñadir"></div></router-link>
            </div>
        </div>
    </div>
    <div class="mainPrincipal">
        <div class="content-view">
            <div v-for="(publicacion, index) in publicaciones"  class="mb-5 card-post itty" :class="color(publicacion.user.style), rotate(publicacion.rotation), position(windowWidth > 600 ? publicacion.position : 2)">
                <div v-if="!usuarioBloqueado(publicacion.user)">       
                    <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: publicacion.user.username } }">
                                <div class="contenedor-img-perfil">
                                    <img :src="publicacion.user.media[0]?.original_url ? publicacion.user.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario" class="img-perfil">
                                </div>
                            </router-link>
                            <div class="ms-3 d-flex flex-column justify-content-center">
                                <span class="itty font1">{{ publicacion.user.name }} {{ publicacion.user.surname }}</span>
                                <span class="font2">@{{ publicacion.user.username }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <img class="soporte" :src="index % 2 === 0 ? '/images/xinxeta.png' : '/images/celo.png'" :style="{transform: index % 2 === 0 ? 'rotate(0deg)' : 'rotate(175deg)'}" alt="Chincheta que sujeta el post">
                        </div>
                        <span class="pe-3 font2">{{ formatearFecha(publicacion.created_at) }}</span>
                    </div>
                    <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="textColor itty">
                    <div v-if="publicacion.texto != '‎'" class="px-3 py-2 card-post-text">
                        <span>{{ publicacion.texto }}</span>
                    </div>
                    <!-- Contenedor para mostrar imagen. Nota: Solo aplica si contiene mas de una imagen-->
                    <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                        <img src="/images/celo.png" alt="Celo que sujeta la imagen al post" class="celo">
                        <img class="img_post" :src="publicacion.media[0].original_url" alt="Imagen del post">
                    </div>
                    </router-link>
                    <div class="card-post-bottom d-flex">
                        <div class="d-flex align-items-center cursor-pointer" @click="like(publicacion)">
                            <div class="pi p-3">
                                <img v-if="comprobarLike(publicacion) == true" src="/images/like_check.svg" alt="Corazón activo" class="corazon-img">
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
        </div>
    </div>
</template>


<script setup>
import axios from "axios";
import { ref, onMounted, computed, onUnmounted } from "vue";
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';

const i18n = useI18n(); // Asegúrate de inicializar i18n correctamente
const publicaciones = ref();
const store = useStore(); // Obtenemos la instancia del store de Vuex
const usuarioActual = computed(() => store.state.auth.user);
const filtroActivo = ref(false);

onMounted(() => {
    obtenerPublicaciones();
})

// Funcion que obtiene todas las publicaciones.
const obtenerPublicaciones = () => {
    axios.get('/api/publicacions')
    .then(response => {
        const publicacionesConLiked = response.data.map(publicacion => {
            return {
                ...publicacion,
                liked: publicacion.liked_by_current_user === true,
            };
        });
        // Filtra las publicaciones segun el filtro activo
        if (filtroActivo.value) {
            publicaciones.value = publicacionesConLiked.filter(publicacion => {
                const esSeguidoPorUsuario = publicacion.user.seguidores.some(seguido => seguido.id === usuarioActual.value.id);
                const esUsuarioActual = publicacion.user.id === usuarioActual.value.id;
                return esSeguidoPorUsuario || esUsuarioActual;
            });
        } else {
            publicaciones.value = publicacionesConLiked;
        }
        console.log(publicaciones.value);
    })
}

// Funcion que es llamada por el filtro
const toggleFiltro = () => {
    filtroActivo.value = !filtroActivo.value;
    obtenerPublicaciones(); // Vuelve a obtener las publicaciones al cambiar el filtro
}

// Funcion para dar like o quitarlo de una publicacion.
const like = (publicacion) => {
    axios.post('/api/like/add/' + publicacion.id)
        .then(response => {
            console.log("Like");
            const like = comprobarLike(publicacion);
            obtenerPublicaciones();
            if(like == false) {
                console.log('Notificacion enviada')
                enviarNotificacion(publicacion, 0);
            }
            
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


// Funcion para enviar una notificacion.
const enviarNotificacion = (publicacion, tipo) => {
    const remitente = usuarioActual.value.id;
    const destinatario = publicacion.id_usuario; 
    const id = publicacion.id;
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

// Verificar si el usuario está bloqueado por alguno de los usuarios bloqueados
const usuarioBloqueado = (usuario) => {
    if (usuario && usuario.bloqueado) {
        return usuario.bloqueados.some(bloqueado => bloqueado.id === usuarioActual.value.id);
    } else {
        return false;
    }
};

// Funcion para aplicar color a las publicaciones en base al estilo elegido por el usuario que la ha creado.
function color(color) {
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

// Funcion aplicada sobre las publicaciones para aplicarles rotacion.
function rotate(rot) {
    switch(rot) {
        case 1:
            return 'inc-1';
        case 2:
            return 'inc-2';
        case 3:
            return 'inc-3';
        case 4:
            return 'inc-4';
        case 5:
            return 'inc-5';
        case 6:
            return 'inc-6';
        default:
            return ''; // Clase por defecto
    }
}

if (window.innerWidth <= 600){
    const posCent = 2;
}
// Funcion aplicada sobre las publicaciones para indicar su posicion, ya sea izquierda, centro o derecha.
function position(pos) {
    switch(pos) {
        case 1:
            return 'align-1';
        case 2:
            return 'align-2';
        case 3:
            return 'align-3';
        default:
            return ''; // Clase por defecto
    }
}

// Funcion que comprueba si se ha dado like a una publicacion y devuelve true o false, utilizada por el icono de like para mostrar si se ha dado like o no.
const comprobarLike = (publicacion) => {
    let tieneLike = false;
    publicacion.likes.forEach(like => {
        if(like.pivot.id_usuario == usuarioActual.value.id) {
            tieneLike = true;
        }
    });
    return tieneLike;
};

const windowWidth = ref(window.innerWidth);  // Propiedad reactiva para almacenar el ancho de la ventana

// Función para actualizar el ancho de la ventana
const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    window.addEventListener('resize', updateWindowWidth);  // Agrega el listener al montar
});

onUnmounted(() => {
    window.removeEventListener('resize', updateWindowWidth);  // Elimina el listener al desmontar
});
</script>

<style scoped>

.btnAñadir {
    background-image: url(/images/btn-añadir.svg);
    background-size: cover;
    background-repeat: no-repeat;
    width: 60px;
    height: 60px;
    filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.5));
}

.btnAñadir:hover {
    filter: drop-shadow(2px 4px 4px rgba(0, 0, 0, 0.5));
    /* transition: all 0.25s ease-in-out;
    transform: rotate(-3deg); */
}

.btnAñadir:target {
    outline: none;
}

.corazon-img{
    height: 20px;
}
.comment-icon{
    height: 22px;
}
</style>