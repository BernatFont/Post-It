<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex align-items-center topbar-title">
            <div class="itty col-7 pl-5">
                <router-link :to="{ name: 'feed'}" class="pt-2 title-target">< </router-link>    
                <span class="pt-2 title-target">{{$t('notifications')}}</span>
            </div>
            <div class="botones-notificaciones col-5 ">
                <!-- Boton para eliminar todas las notificacione -->
                <div @click="eliminarNotificaciones(notificaciones[0]?.id_destinatario)" class="btn btn-href pr-0 itty font1 d-flex flex-row align-items-center mr-3">
                    <span class="delete-responsive">{{ $t('delete_all') }}</span>
                    <div class="img-btn"></div>
                </div>
                <div class="target-value">
                    <span class="itty">{{notificaciones.length}}</span>
                </div>
            </div>
            
        </div>
    </div>
    <div class="mainPrincipal">
        <div v-for="notificacion in notificaciones">
            <!-- Notificacion Like-->
            <div class="userContainer d-flex flex-column" v-if="notificacion.tipo_interaccion == 0">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/like.svg">
                            <span class="ml-2 mb-1 font1 itty">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="btn-href2">@{{ notificacion.user1.username }}</span>
                                </router-link>
                                {{ $t('like_notification') }}
                            </span>
                        </div>
                        <button @click="eliminarNotificacion(notificacion.id)" class="btn btn-href pr-0 itty font1">
                            <div class="img-btn"></div>
                        </button>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                <div class="contenedor-img-perfil">
                                    <img class="img-perfil" :src="notificacion.user1.media[0]?.original_url ? notificacion.user1.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil usuario 1">
                                </div>
                            </router-link>
                            <div class="d-flex flex-column">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="pl-1 itty font1 btn-href2">{{ notificacion.user1.name }} {{ notificacion.user1.surname }}</span>
                                </router-link>
                                <span class="pl-1 itty font2">@{{ notificacion.user1.username }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1">{{ formatearFecha(notificacion.created_at) }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row">  
                    <div class="notification-post">
                        <router-link :to="{ name: 'publicacion.mostrar', params: { id: notificacion.publicacion.id } }" class="textColor itty">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user2.username } }">
                                    <div class="contenedor-img-perfil">
                                        <img class="img-perfil" :src="notificacion.user2.media[0]?.original_url ? notificacion.user2.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil usuario 2">
                                    </div>
                                </router-link>
                                <div class="d-flex flex-column">
                                    <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user2.username } }">
                                        <span class="pl-1 itty font1 btn-href2">{{ notificacion.user2.name }} {{ notificacion.user2.surname }}</span>
                                    </router-link>
                                    <span class="pl-1 itty font2">@{{ notificacion.user2.username }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="itty font1 fecha">{{obtenerFecha(notificacion.publicacion.created_at)}}</span>
                            </div>
                        </div>
                            <span v-if="notificacion.publicacion.texto != '‎'" class="pt-1 pl-1 itty font1">{{ notificacion.publicacion.texto }}</span>
                        </router-link>
                    </div> 
                </div>
            </div>

            <!--Notificacion Mensaje-->
            <div class="userContainer d-flex flex-column" v-if="notificacion.tipo_interaccion == 1">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/comentarios.svg">
                            <span class="ml-2 mb-1 font1 itty">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="btn-href2">@{{ notificacion.user1.username }}</span>
                                </router-link>
                                {{ $t('comment_notification') }}
                            </span>
                        </div>
                        <button @click="eliminarNotificacion(notificacion.id)" class="btn btn-href pr-0 itty font1">
                            <div class="img-btn"></div>
                        </button>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                <div class="contenedor-img-perfil">
                                    <img class="img-perfil" :src="notificacion.user1.media[0]?.original_url ? notificacion.user1.media[0].original_url : '/images/user-default.png'" alt="">
                                </div>
                            </router-link>
                            <div class="d-flex flex-column">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="pl-1 itty font1 btn-href2">{{ notificacion.user1.name }} {{ notificacion.user1.surname }}</span>
                                </router-link>
                                <span class="pl-1 itty font2">@{{ notificacion.user1.username }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1">{{formatearFecha(notificacion.created_at)}}</span>
                        </div>
                    </div>
                    <router-link :to="{ name: 'publicacion.mostrar', params: { id: notificacion.comentario.publicacion.id } }" class="textColor itty">
                        <span class="pt-1 pl-1 itty font1">{{ notificacion.comentario.contenido }}</span>
                    </router-link>
                </div>
                <div class="d-flex flex-row">  
                    <div class="notification-post">
                        <router-link :to="{ name: 'publicacion.mostrar', params: { id: notificacion.comentario.publicacion.id } }" class="textColor itty">
                            <div class="d-flex flex-row justify-content-between">
                                <div class="d-flex flex-row">
                                    <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user2.username } }">
                                        <div class="contenedor-img-perfil">
                                            <img class="img-perfil" :src="notificacion.user2.media[0]?.original_url ? notificacion.user2.media[0].original_url : '/images/user-default.png'" alt="">
                                        </div>
                                    </router-link>
                                    <div class="d-flex flex-column">
                                        <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user2.username } }">
                                            <span class="pl-2 itty font1 btn-href2">{{ notificacion.user2.name }} {{ notificacion.user2.surname }}</span>
                                        </router-link>
                                        <span class="pl-2 itty font2">@{{ notificacion.user2.username }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="itty font1 fecha">{{obtenerFecha(notificacion.comentario.publicacion.created_at)}}</span>
                                </div>
                            </div>
                            <span class="pt-1 pl-2 itty font1">{{ notificacion.comentario.publicacion.texto }}</span>
                        </router-link>
                    </div> 
                </div>
            </div>

            <!--Notificacion nuevo seguidor-->
            <div class="userContainer d-flex flex-column" v-if="notificacion.tipo_interaccion == 2">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/perfil.svg">
                            <span class="ml-2 mb-1 font1 itty">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="btn-href2">@{{ notificacion.user1.username }}</span>
                                </router-link>
                                {{ $t('follow_notification') }}
                            </span>
                        </div>
                        <button @click="eliminarNotificacion(notificacion.id)" class="btn btn-href pr-0 itty font1">
                            <div class="img-btn"></div>
                        </button>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                <div class="contenedor-img-perfil">
                                    <img class="img-perfil" :src="notificacion.user1.media[0]?.original_url ? notificacion.user1.media[0].original_url : '/images/user-default.png'" alt="">
                                </div>
                            </router-link>
                            <div class="d-flex flex-column">
                                <router-link :to="{ name: 'usuario.mostrar', params: { username: notificacion.user1.username } }">
                                    <span class="pl-1 itty font1 btn-href2">{{ notificacion.user1.name }} {{ notificacion.user1.surname }}</span>
                                </router-link>
                                <span class="pl-1 itty font2">@{{ notificacion.user1.username }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1 fecha">{{formatearFecha(notificacion.created_at)}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import axios from "axios";
import { ref, onMounted, inject, computed } from "vue";

const notificaciones = ref({});
const swal = inject('$swal');


onMounted(() => {
    obtenerNotificaciones();
});

const obtenerNotificaciones = () => {
    axios.get('/api/notificaciones/')
        .then(response => {
            notificaciones.value = response.data;
            console.log(notificaciones.numNotificaciones);
        })
        .catch(error => {
            console.error('Error al obtener las notificaciones:', error);
        })
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
    return `${dia}/${mes}/${año}`;
}

// Elimina una notificacio
const eliminarNotificacion = (idNotificacion) => {
    swal({
        title: '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true
    }).then((result)=>{
        if (result.isConfirmed) {
            axios.delete('/api/notificacion/delete/' + idNotificacion)
            .then(response => {
                console.log("Notificacion eliminada con exito");
                obtenerNotificaciones();
            })
            .catch(error => {
                console.error("Error al eliminar la notificacion:", error);
            }); 
            swal({
                title: 'Eliminado correctamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })
        }
    })
}

// Eliminar todas las notificaciones
const eliminarNotificaciones = (idDestinatario) => {
    swal({
        title: '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true
    }).then((result)=>{
        if (result.isConfirmed) {
            axios.delete('/api/notificacion/deleteAll/' + idDestinatario)
            .then(response => {
                console.log("Todas las notificaciones han sido eliminadas con exito");
                obtenerNotificaciones();
            })
            .catch(error => {
                console.error("Error al eliminar la notificacion:", error);
            }); 
            swal({
                title: 'Notificaciones eliminadas correctamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })
        }
    })
}

</script>

<style>
    .botones-notificaciones {
        width: auto;
        margin-left: auto;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .type-notification {
        margin-bottom: 5px;
    }  
    .notification-icon {
        width: 15px;
        height: auto;
    }
    .notification-post {
        border: 1px solid #414141;
        background-color: #50e8cc;
        width: 100%; 
        display: flex;
        flex-direction: column;
        padding: 10px;
        margin-bottom: 10px;
    }

    .delete-responsive {
        display: block;
    }

    @media(max-width: 600px) {
        .delete-responsive {
            display: none;
        }
    }
</style>