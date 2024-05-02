<template>
    <!-- {{ usuario }} -->
    <div class="mainPrincipal p-5">
        <div v-if="usuario" class="py-4 px-4 datos-perfil">
            <div class="top-content-view w-100 d-flex justify-content-between align-items-center container-datos-usuario">
                <div class="contenedor-img-perfil-grande">
                    <!-- Utilizamos el src dinámico para cargar la imagen del perfil del usuario -->
                    <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="imagen del perfil del usuario" class="img-perfil">
                </div>
                
                <div class="d-flex flex-column">
                    <!-- Enlaces a las vistas de seguidores y seguidos -->
                    <router-link class="mb-3 itty font-standard" :to="{name: 'usuario.seguidores'}">{{ $t('seguidores')}}: {{usuario.seguidores_count}} </router-link>
                    <router-link class="itty font-standard" :to="{name: 'usuario.seguidos'}">{{ $t('seguidos')}}: {{usuario.seguidos_count}}</router-link>
                </div>
                <div class="w-25">
                    <div v-if="usuario.id !== userLogin.id" @click="chat" class="container-boton w-100 mb-3">
                        <div class="sticky-btn-sticker bg-2c"></div>
                        <!--Boton para crear/mostrar chat con la persona logeada y la seleccionada-->
                        <button class="btnSticky sticky-btn-1 itty bg-2" :disabled="processing">{{ $t('messages') }}</button>
                    </div>

                    <!-- Botón para editar el perfil o seguir segun el usuario logeado -->
                    <router-link v-if="usuario.id === userLogin.id" :to="{ name: 'perfil.modificar', params: { username: usuario.username }}">
                        <div class="container-boton w-100">
                            <div class="sticky-btn-sticker bg-2c"></div>
                            <button class="btnSticky sticky-btn-1 itty bg-2" :disabled="processing">{{ $t('modify_profile') }}</button>
                        </div>
                        <!-- <span>Editar perfil</span> -->
                    </router-link>
                    <div v-else-if="!seguidorUsuarioActual" @click="seguir" class="container-boton w-100">
                        <div class="sticky-btn-sticker"></div>
                        <button class="btnSticky sticky-btn-1 itty" :disabled="processing">{{ $t('seguir') }}</button>
                    </div>
                    <div v-else-if="seguidorUsuarioActual" @click="seguir" class="container-boton w-100">
                        <div class="sticky-btn-sticker bg-1c"></div>
                        <button class="btnSticky sticky-btn-1 itty bg-1" :disabled="processing">{{ $t('dejar_seguir') }}</button>
                    </div>
                </div>
               
            </div>
            <div class="px-5 pb-4 pt-3 w-100 d-flex justify-content-between">
                <div class="d-flex flex-column" >
                    <!-- Mostramos el nombre y apellido del usuario -->
                    <span class="itty font-standard">{{ usuario.name + (usuario.surname ? ' ' + usuario.surname : '')}}</span>
                    <!-- Mostramos el nombre de usuario -->
                    <span class="mt-3 itty font-standard">@{{usuario.username}}</span>
                    <!-- Mostramos descripción del usuario -->
                    <span class="mt-3 itty font-low">{{usuario.biography}}</span>
                </div>
                <div>
                    <div v-if="usuario.id === userLogin.id" class="d-flex align-items-center">
                        <p class="mt-3 me-3 px-3 py-2 itty px20" :class="bgClass(usuario.style)">{{ $t('color_post')}}</p>
                        <select class="itty px-4 py-2" v-model="selectedStyle" @change="logSelectedStyle">
                            <option style="background-color: var(--primero);" value="1"><span>1</span></option>
                            <option style="background-color: var(--segundo);" value="2"><span>2</span></option>
                            <option style="background-color: var(--tercero);" value="3"><span>3</span></option>
                            <option style="background-color: var(--cuarto);" value="4"><span>4</span></option>
                            <option style="background-color: var(--quinto);" value="5"><span>5</span></option>
                            <option style="background-color: var(--sexto);" value="6"><span>6</span></option>
                        </select>
                    </div>
                    <div>
                        <router-link :to="{ name: 'panelControl'}">
                            <div class="container-boton w-100">
                                <div class="sticky-btn-sticker bg-4c"></div>
                                <button class="btnSticky sticky-btn-1 itty bg-4" :disabled="processing">Panel control</button>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="usuario" class="px-5">
            <!-- <p>{{ usuario.publicaciones }}</p> -->
            <div>
                <div v-for="publicacion in usuario.publicaciones" :key="publicacion.id" class="card-post mb-5" :class="bgClass(usuario.style)">
                    <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="contenedor-img-perfil">
                                <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="" class="img-perfil">
                            </div>
                            <div class="ms-3 d-flex flex-column justify-content-center">
                                <span>@{{ usuario.username }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <img class="soporte" :src="index % 2 === 0 ? '/images/xinxeta.png' : '/images/celo.png'" :style="{transform: index % 2 === 0 ? 'rotate(0deg)' : 'rotate(175deg)'}" alt="">
                        </div>
                        <span class="pe-3">{{ formatearFecha(publicacion.created_at) }}</span>
                    </div>

                    <div class="px-3 py-2 card-post-text">
                        <span>{{ formatText(publicacion.texto) }}</span>
                    </div>

                    <div class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center" v-if="publicacion.media.length > 0" style="position: relative; z-index: 0;">
                        <img src="/images/celo.png" alt="" class="celo">
                        <!-- El interrogante se usa por si no hay imagen en el post, que no pete -->
                        <img class="img_post" :src="publicacion.media[0]?.original_url" alt="">
                    </div>
                    <p><strong>N. likes:</strong> {{ publicacion.likes.length }}</p>
                    <p><strong>N. coments:</strong> {{ publicacion.comentarios.length }}</p>
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

//Funcion para canviar color de los posts reactivamente
function logSelectedStyle() {
    console.log("Estilo seleccionado:", selectedStyle.value);

    // Verificar si ya existe un chat entre el usuario actualmente logueado y el usuario visitado
    axios.post('/api/usuarios/colorPost/' + selectedStyle.value)
    .then(response => {
        const styleNumber = Number(selectedStyle.value);
        let classColor = bgClass(styleNumber);
        usuario.value.style = styleNumber;

        console.log(selectedStyle.value);
        console.log(format(classColor));
        console.log(usuario.value.style);

    })
    .catch(error => {
        console.error('Error al canviar color del post:', error);
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
    background-color: #fff;
    margin-bottom: 48px;
}

.container-datos-usuario div span {
    font-size: 20px;
}
.color-indicator {
  width: 15px;
  height: 15px;
  margin-right: 5px;
  border: 1px solid #000; /* Opcional: para hacerlo más visible */
}
.celo{
    transform: rotate(105deg);
    height: 50px;
    position: absolute;
    box-shadow: none;
    z-index: 5;
    top: 2%;
}

.font-standard{
    font-size: 20px;
}
.font-low{
    font-size: 18px;
}

.option span{
    padding: 8px 0;
}
</style>
