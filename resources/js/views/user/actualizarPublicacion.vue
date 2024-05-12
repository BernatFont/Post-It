<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <div class="itty col-8">
                <router-link class="pt-2 title-target" :to="{ name: 'publicacion.mostrar', params: { id: id }}">< </router-link> 
                <span class="pt-2 title-target">{{$t('modify_post')}}</span>
            </div>   
        </div>
    </div>
    <div class="mainPrincipal">
        <div v-if="publicacion">
            <div class="content-view itty">
                <form class="container-width-createpost" @submit.prevent="actualizar">
                    <div class="container-createpost" :class="bgClass(publicacion.user.style)">
                            <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                                <div v-if="usuario" class="d-flex">
                                    <div class="contenedor-img-perfil">
                                        <img :src="publicacion.user.media[0]?.original_url ? publicacion.user.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario" class="img-perfil">
                                    </div>                         
                                    <div class="d-flex flex-column justify-content-center">
                                        <span class="px20">{{ publicacion.user.name }} {{ publicacion.user.surname }}</span>
                                        <span class="font2">@{{ publicacion.user.username}}</span>
                                    </div>
                                </div>
                                <span class="mr-4 font2">{{ formatearFecha(publicacion.created_at) }}</span>
                            </div>
                            <div class="px-3 py-2 card-post-text w-100">
                                <textarea v-model="publicacion.texto" class="form-control textarea-2 itty" @input="checkMaxLength" maxlength="300" :placeholder="$t('write')"></textarea>
                                <div v-if="maxLenghtTexto(publicacion.texto)" class="alert">{{ $t('limit_characters') }}</div>
                            </div>
                            <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                                <img src="/images/celo.png" alt="Celo que sujeta la imagen al post" class="celo">
                                <img :src="publicacion.media[0].original_url" alt="imagen de la publicacion" class="img_post">
                            </div>
                            <div class="card-post-bottom d-flex">
                                <div class="pi p-3">
                                    <img v-if="comprobarLike(publicacion)" src="/images/like_check.svg" alt="Corazón activo" class="corazon-img">
                                    <img v-else src="/images/like.svg" alt="Corazón inactivo" class="corazon-img">
                                </div>
                                <div class="d-flex align-items-center justify-content-center"><span class="itty number-of">{{publicacion.likes_count}}</span>
                                    <img src="/images/comentarios.svg" alt="icono de comentarios" class="mx-3 comment-icon"><span class="itty number-of">{{publicacion.comentarios_count}}</span>
                                </div>   
                            </div>
                    </div>
                    <div class="section-options-createpost">
                        <div class="container-boton mt-3">
                            <div class="sticky-btn-sticker bg-3c"></div>
                            <button type="submit" class="btnSticky sticky-btn-1 bg-3 itty">{{ $t('apply_changes')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</template>

<script setup>
import axios from "axios";
import { inject, ref, computed, onMounted } from "vue";

import router from "../../routes";
import { useStore } from 'vuex';


const store = useStore();
const id = router.currentRoute.value.params.id;
const publicacion = ref(null);
const strSuccess = ref(null);
const strError = ref(null);
const usuario = ref(null);
let user = computed(() => store.state.auth.user)
const swal = inject('$swal');

onMounted(() => {
        obtenerUsuario();
        obtenerPublicacion();
    });

    const obtenerPublicacion = () => {
        axios.get('/api/publicacions/' + id)
        .then(response => {
            publicacion.value = response.data;
            console.log(publicacion.value);
            if((publicacion.value.id_usuario != user.value.id) && (usuario.value.roles[0].name != 'admin')) {
                swal({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: 'No tiene permiso para modificar este post.',
                }).then(() => {
                    router.push('/inicio');
                });
            }
        })
        .catch(error => {
            console.error('Error al obtener la publicación:', error);
            router.push('/inicio');
        });
    }

    // Realizamos la solicitud para obtener los datos del usuario
    const obtenerUsuario = () => {
        axios.get('/api/usuario/' + user.value.username)
        .then(response => {
                // Actualizamos los datos del usuario
                usuario.value = response.data;
                console.log(usuario.value);
        })
        .catch(error => {
                console.error('Error al cargar los datos del usuario:', error);
        }); 
    };

    // Funcion para actualizar una publicacion, envia el id de la publicacion y el texto modificado por parametro.
    function actualizar() {
    axios.put('/api/publicacions/update/' + id, { texto: publicacion.value.texto })
        .then(response => {
            strError.value = "";
            strSuccess.value = response.data.success;
            swal({
                icon: 'success',
                title: 'Publicacion modificada correctamente.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                router.push({name: 'publicacion.mostrar', params: { id: publicacion.value.id }});
            });
        })
        .catch(error => {
            swal({
                icon: 'error',
                title: 'Ha ocurrido un error al modificar la publicación',
                showConfirmButton: false,
                timer: 2000
            });
            strSuccess.value = "";
            strError.value = error.response.data.message;
        });
    }

    // Comprueba que los caracteres del post sean menos de 300, en caso de ser mas devuelve true y se muestra el mensaje de maximo numero de caracteres superados.
    const maxLenghtTexto = (texto) => {
        const maxLength = 300;

        if ((typeof texto === 'undefined') || (texto.length != maxLength)) {
            return false; // Devuelve una cadena vacía si texto es undefined
        }
        if (texto.length >= maxLength) {
            return true;
        }
    }

    // Aplica el color a una publicacion en base al estilo elegido por el usuario que lo publico.
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
        case 5:
          return 'bg-5';
        case 6:
          return 'bg-6';
        
        default:
          return 'bg-1'; // Por defecto se aplicara la clase bg-1 si no esta definida
      }
    }

    // Obtiene una fecha y la desglosa en minutos, horas o dias.
    const calcularDiferencia = (fechaPublicacion) => {
        const fechaPublicacionObjeto = new Date(fechaPublicacion);
        const fechaActual = new Date();
        const diferenciaEnMs = fechaActual - fechaPublicacionObjeto;
        const diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
        const diferenciaEnHoras = Math.floor(diferenciaEnMinutos / 60);
        const diferenciaEnDias = Math.floor(diferenciaEnHoras / 24);
        return { minutos: diferenciaEnMinutos, horas: diferenciaEnHoras, dias: diferenciaEnDias };
    };

    // obtiene los valores de calcularDiferencia con los minutos, horas y dias y formatea la fecha de una forma u otra en funcion del tiempo.
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

    // Comprueba si se ha dado like a una publicacion por parte del usuario logeado para mostrar el boton de like relleno.
    const comprobarLike = (publicacion) => {
    let tieneLike = false;
    publicacion.likes.forEach(like => {
        if(like.pivot.id_usuario == user.value.id) {
            tieneLike = true;
        }
    });
    return tieneLike;
};
</script>

<style setup>

.container-width-createpost {
        width: 65%;
        margin: auto;
    }
    .container-createpost {
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .section-options-createpost {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }
    .post-text {
        max-width: 100%;
        word-wrap: break-word; /* Permite que el texto se divida en varias líneas */
    }
    .textarea-2{
        resize: none;
        min-height: 120px;
        width: 100%;
        background-color: none;
        border: 0 none;
        background: transparent;
        outline: none;
        box-shadow: none;
        font-size: 1.5rem;
    }

    .textarea-2:focus{
        border: 0 none;
        background: transparent;
        box-shadow: none;
    }


    input[type="file"] {
        display: none;
    }

    .alert {
        --bs-alert-padding-x: 1rem;
        --bs-alert-padding-y: 1rem;
        --bs-alert-margin-bottom: 0rem;
        padding: 0;
        padding-top: 1rem;
        padding-left: 1rem;
    }

    .corazon-img{
        height: 20px;
    }
    .comment-icon{
        height: 22px;
    }
@media (max-width: 1200px){
    .container-width-createpost {
        width: 80%;
        margin: auto;
    }
    .section-options-createpost {
        flex-direction: column;
    }
}


@media (max-width: 600px){
    .container-width-createpost {
        width: 100%;
        margin: auto;
    }
}
</style>
