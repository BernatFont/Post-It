<template>
    <!-- {{ usuario }} -->
    <div class="topbar-container bg-v2 ">
        <div class="d-flex flex-row justify-content-between topbar-title">
            <div class="itty col-8 pl-5 ">
                <router-link :to="{ name: 'feed'}" class="pt-2 title-target">< </router-link>    
                <span class="pt-2 title-target">{{$t('profile')}}</span>
            </div>
            <div v-if="usuario">
                <div v-if="usuario.roles[0]?.name == 'admin' && usuario.username == userLogin.username">
                    <!-- Botón Panel de admin -->
                    <router-link :to="{ name: 'panelControl'}">
                        <div class="container-boton w-100 mr-5">
                            <div class="sticky-btn-sticker bg-3c"></div>
                            <button class="btnSticky sticky-btn-1 itty bg-3">{{ $t('admin_panel') }}</button>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
        
    </div>
    <div class="mainPrincipal">
        <div class="content-view" v-if="usuario">
            <!--Panel de usuario-->
            <div class="datos-perfil itty">
                <div class="contenido-perfil">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <div class="contenedor-img-perfil-grande rotate-img">
                                <!-- Utilizamos el src dinámico para cargar la imagen del perfil del usuario -->
                                <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="imagen del perfil del usuario" class="img-perfil">
                            </div>
                            <div class="d-flex flex-column justify-content-center ml-5">
                                <span class="font-title">{{ usuario.name + (usuario.surname ? ' ' + usuario.surname : '')}}</span> <!--Nombre y Apellido-->
                                <span class="font-info">@{{usuario.username}}</span> <!--Username-->
                            </div>
                        </div>
                        <div class="botones-perfil">
                            <div v-if="usuario.id !== userLogin.id" @click="chat">
                                <!--Contenedor de boton del chat-->
                                <div class="mt-2 mx-3" >
                                    <div class="img-btn-chat-profile"></div>
                                </div>
                            </div>
                            <div>
                                <!--Contenedor de modificar perfil de usuario-->
                                <router-link v-if="usuario.id === userLogin.id" :to="{ name: 'perfil.modificar', params: { username: usuario.username }}">
                                    <div class="mt-2 mx-3" >
                                        <div class="img-btn-edit-profile"></div>
                                    </div>
                                </router-link>
                            </div> 
                            <div class="mt-2" v-if="usuario.id !== userLogin.id">
                                <!--Contenedor Boton seguir y dejar de seguir-->
                                <div v-if="!seguidorUsuarioActual" @click="seguir">
                                    <div class="mx-3" >
                                        <div class="img-btn-seguir-profile"></div>
                                    </div>     
                                </div>
                                <div v-else-if="seguidorUsuarioActual" @click="seguir">
                                    <div class="mx-3" >
                                        <div class="img-btn-dejarseguir-profile"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2" v-if="usuario.id !== userLogin.id">
                                <!--Contenedor Boton bloquar y desbloquar-->
                                <div v-if="!bloquearUsuarioActual" @click="bloquear">
                                    <div class="mx-3" >
                                        <div class="img-btn-seguir-profile"></div>
                                    </div>     
                                </div>
                                <div v-else-if="bloquearUsuarioActual" @click="bloquear">
                                    <div class="mx-3" >
                                        <div class="img-btn-dejarseguir-profile"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex flex-column font-standard" >
                        <span v-if="usuario.biography !== null"> {{usuario.biography}}</span>
                        <div class="mt-3 d-flex flex-column">
                            <span v-if="usuario.birth_date !== null" class="opacity-75">{{$t('birth_date')}}: {{obtenerFecha(usuario.birth_date)}}</span>
                            <span class="opacity-75">{{$t('join_date')}} {{obtenerFecha(usuario.created_at)}}</span>
                            <div class="mt-3">
                                <router-link class="btn-href2" :to="{name: 'usuario.seguidos'}">{{usuario.seguidos_count}} {{ $t('seguidos')}} </router-link> <!--Seguidos-->
                                <router-link class="btn-href2 ml-3" :to="{name: 'usuario.seguidores'}">{{usuario.seguidores_count}} {{ $t('seguidores')}}  </router-link> <!--Seguidores-->
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
            <!-- Publicaciones del usuario *Si el usuario esta bloqueado no se muestran*-->
            <div v-if="usuario.publicaciones && usuario.bloqueado[0]?.id !== userLogin.id" class="d-flex flex-column">
                <!-- Contenedor de una publicacion-->
                <div v-for="(publicacion, index) in usuario.publicaciones" :key="publicacion.id" class="publicacion-perfil-container mb-5" :class="[bgClass(usuario.style), rotate(publicacion.rotation), position(2)]">
                    <router-link :to="{ name: 'publicacion.mostrar', params: { id: publicacion.id } }" class="textColor itty">
                        <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <div class="contenedor-img-perfil">
                                    <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="" class="img-perfil">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <span class="pl-1 itty font1">{{ usuario.name }} {{ usuario.surname }}</span>
                                    <span class="itty px20">@{{ usuario.username }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <img class="soporte" :src="index % 2 === 0 ? '/images/xinxeta.png' : '/images/celo.png'" :style="{transform: index % 2 === 0 ? 'rotate(0deg)' : 'rotate(175deg)'}" alt="">
                            </div>
                            <span class="pe-3">{{ formatearFecha(publicacion.created_at) }}</span>
                        </div> 
                        <div v-if="publicacion.texto.length > 1" class="px-3 py-2 card-post-text">
                            <span class="itty px20">{{publicacion.texto}}</span>
                        </div>    
                        <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                            <img src="/images/celo.png" alt="" class="celo">
                            <!-- El interrogante se usa por si no hay imagen en el post, que no pete -->
                            <img class="img_post" :src="publicacion.media[0]?.original_url" alt="">
                        </div>
                        <!-- Boton Like y comentario -->
                        <div class="card-post-bottom d-flex">
                            <div class="d-flex align-items-center cursor-pointer" @click.prevent="like(publicacion)">
                                <div class="pi p-3">
                                    <img v-if="comprobarLike(publicacion.id)" src="/images/like_check.svg" alt="Corazón activo" class="corazon-img">
                                    <img v-else src="/images/like.svg" alt="Corazón inactivo" class="corazon-img">
                                </div>
                                <span class="itty number-of">{{ publicacion.likes.length }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="/images/comentarios.svg" alt="icono de comentarios" class="mx-3 comment-icon"><span class="itty number-of">{{publicacion.comentarios.length}}</span>
                            </div>   
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else>
                <!-- En caso de que el usuario le ha bloqueado -->
                <div class="d-flex justify-content-center align-items-center">
                    <div class="textContent chatContainer d-flex justify-content-center">
                        <span>{{$t('block_message')}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

/* Obtenemos datos del usuario */
const store = useStore();
const route = useRoute(); 
const router = useRouter()
const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const username = route.params.username; // Obtiene el usuario por la ruta
const usuario = ref(null);
const seguidorUsuarioActual = ref(false);
const bloquearUsuarioActual = ref(false);
const selectedStyle = ref(1);

onMounted(() => {
    obtenerUsuario();
});

// Realizamos la solicitud para obtener los datos del usuario
const obtenerUsuario = () => {
    axios.get('/api/usuario/' + username)
    .then(response => {
            // Actualizamos los datos del usuario
            usuario.value = response.data;
            selectedStyle.value = usuario.value.style;
            console.log(usuario.value);
            comprobarSeguido();
            comprobarBloqueado();
    })
    .catch(error => {
            console.error('Error al cargar los datos del usuario:', error);
    }); 
};

const seguir = () => {
    axios.post('/api/follow/' + usuario.value.id)
    
    .then(response => {
        console.log("Seguir");
        const seguido = comprobarSeguido();
        obtenerUsuario();
        if (usuario.value) {
            console.log(seguido);
            if(seguido == false) {
                enviarNotificacion(usuario.value, 2); // Asegúrate de pasar usuario.value en lugar de usuario
            }
        } else {
            console.error("El usuario no está definido");
        }
    })
    .catch(error => {
        console.error('Error al seguir al usuario:', error);
    });
}

const comprobarSeguido = () => {
    if (usuario.value && userLogin.value) {
        const seguidores = usuario.value.seguidores.map(seguidor => seguidor.id);
        return seguidorUsuarioActual.value = seguidores.includes(userLogin.value.id);
    } else {
        return false;
    }
};

const bloquear = () => {
    axios.post('/api/block/' + usuario.value.id)
    
    .then(response => {
        console.log("Bloquear");
        comprobarBloqueado();
        obtenerUsuario();
    })
    .catch(error => {
        console.error('Error al bloquear al usuario:', error);
    });
}

const comprobarBloqueado = () => {
    if (usuario.value && userLogin.value) {
        const bloqueados = usuario.value.bloqueados.map(bloqueado => bloqueado.id);
        return bloquearUsuarioActual.value = bloqueados.includes(userLogin.value.id);
    } else {
        return false;
    }
};

const chat = () => {
    // Verificar si ya existe un chat entre el usuario actualmente logueado y el usuario visitado
    axios.post('/api/chat/' + usuario.value.id)
    .then(response => {
        const chatId = response.data.chat_id; // Obtiene el id del chat
        if (chatId) {
            router.push({ name: 'mostrar.chat', params: { id: chatId } }); // Redirige al chat
        } else {
            console.log('El chat no existe'); // En caso de error al encontrar el chat
        }
    })
    .catch(error => {
        console.error('Error al mostrar chat:', error);
    });
}

// Funcion para dar like a una publicacion
const like = (publicacion) => {
    axios.post('/api/like/add/' + publicacion.id)
        .then(response => {
            console.log("Like");
            const like = comprobarLike(publicacion.id);
            obtenerUsuario()
            if(like == false) {
                enviarNotificacion(publicacion, 0);
            }
            
        })
        .catch(error => {
            console.error("Error al dar like:", error);
        });
};

// Comprueba si una publicacion ya tenia like o no.
const comprobarLike = (id) => {
    if (usuario.value && usuario.value.publicaciones) {
        const publicacion = usuario.value.publicaciones.find(pub => pub.id === id);
        if (publicacion && publicacion.likes && userLogin.value) {
            const tieneLike = publicacion.likes.some(like => like.id_usuario === userLogin.value.id);
            return tieneLike;
        }
    }
    return false;
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
    // Definidos los meses
    const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    // Crear un objeto de fecha a partir de la cadena proporcionada
    const fechaObjeto = new Date(fecha);
    
    // Obtener los componentes de la fecha
    const horas = fechaObjeto.getHours().toString().padStart(2, '0'); // Añadir ceros a la izquierda si es necesario
    const minutos = fechaObjeto.getMinutes().toString().padStart(2, '0');
    const dia = fechaObjeto.getDate().toString().padStart(2, '0');
    const mes = fechaObjeto.getMonth(); 
    const año = fechaObjeto.getFullYear();

    // Obtener nombre del mes
    const nombreMes = nombresMeses[mes];
    // Devolver la fecha formateada en el formato deseado
    return `${dia} de ${nombreMes} de ${año}`;
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

function position(pos) {
    switch(Number(pos)) {
        case 1:
        return 'align-1';
        case 2:
        return 'align-2';
        case 3:
        return 'align-3';
        default:
        return 'align-2'; // Clase por defecto
    }  
}

const enviarNotificacion = (usuario, tipo) => {
    const remitente = userLogin.value.id;
    const destinatario = usuario.id; 
    const id = usuario.id;
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


</script>

<style>
.datos-perfil{
    background-image: url(/images/papel-comment.svg);
    background-size: cover;
    background-position: center;
    margin-top: 24px;
    margin-bottom: 24px;
    padding: 2rem;
}
.contenido-perfil {
    padding: 36px;
    display: flex;
    flex-direction: column;
}

.celo{
    transform: rotate(115deg);
    height: 50px;
    position: absolute;
    box-shadow: none;
    z-index: 5;
    top: 4%;
}

.corazon-img{
    height: 20px;
}
.comment-icon{
    height: 22px;
}
.rotate-img {
    transform: rotate(-3deg);
}
.btnChat {
    width: 30px;
    height: 30px;
}

.publicacion-perfil-container{
    box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
    width: 65%;
    max-width: 900px;
}

.botones-perfil {
    display: flex;
    flex-direction: row;
}
@media (max-width: 1000px){
    .publicacion-perfil-container{
        width: 80%;
    }
    .celo {
        top: 3%;
    }
    .botones-perfil {
        display: flex;
        flex-direction: column;
    }
}

@media (max-width: 800px){
    .publicacion-perfil-container{
        width: 100%;
    }
    
}
@media (max-width: 600px){
    .celo {
        height: 40px;
        top: 2%;
    }
    .datos-perfil{
        padding: 0rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

}
</style>
