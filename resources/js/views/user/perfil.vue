<template>
    <div class="mainPrincipal">
        <div v-if="usuario" class="py-2 px-5">
            <div class="top-content-view w-100 d-flex justify-content-between container-datos-usuario">
                <div>
                    <!-- Utilizamos el src dinámico para cargar la imagen del perfil del usuario -->
                    <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="imagen del perfil del usuario" class="img-perfil">
                </div>
                <div class="d-flex flex-column">
                    <!-- Enlaces a las vistas de seguidores y seguidos -->
                    <router-link :to="{name: 'usuario.seguidores'}">Seguidores: {{usuario.seguidores_count}} </router-link>
                    <router-link :to="{name: 'usuario.seguidos'}">Seguidos: {{usuario.seguidos_count}}</router-link>
                </div>
                <div>
                    <!--Boton para crear/mostrar chat con la persona logeada y la seleccionada-->
                    <button v-if="usuario.id !== userLogin.id" @click="chat" class="btn btn-postit">Mensaje</button>
                    

                    <!-- Botón para editar el perfil o seguir segun el usuario logeado -->
                    <router-link v-if="usuario.id === userLogin.id" class="btn btn-postit" :to="{ name: 'perfil.modificar'}">
                        <span>Editar perfil</span>
                    </router-link>
                    <button v-else-if="!seguidorUsuarioActual" @click="seguir" class="btn btn-postit">Seguir</button>
                    <button v-else-if="seguidorUsuarioActual" @click="seguir" class="btn btn-postit">Dejar de seguir</button>
                </div>
            </div>
            <div class="ms-3 mt-5 w-100 d-flex justify-content-between">
                <div class="d-flex flex-column" >
                    <!-- Mostramos el nombre y apellido del usuario -->
                    <span>{{ usuario.name + (usuario.surname ? ' ' + usuario.surname : '')}}</span>
                    <!-- Mostramos el nombre de usuario -->
                    <span class="mt-3">@{{usuario.username}}</span>
                    <!-- Mostramos descripción del usuario -->
                    <span class="mt-3">{{usuario.biography}}</span>
                </div>
                <div>
                    <select v-model="selectedStyle" @change="logSelectedStyle">
                        <option style="background-color: var(--primero);" value="1">Estilo 1</option>
                        <option style="background-color: var(--segundo);" value="2">Estilo 2</option>
                        <option style="background-color: var(--tercero);" value="3">Estilo 3</option>
                        <option style="background-color: var(--cuarto);" value="4">Estilo 4</option>
                        <option style="background-color: var(--quinto);" value="5">Estilo 5</option>
                        <option style="background-color: var(--sexto);" value="6">Estilo 6</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="usuario" class="px-5">
            <!-- <p>{{ usuario.publicaciones }}</p> -->
            <div>
                <div v-for="publicacion in usuario.publicaciones" :key="publicacion.id" class="card-post mb-5" :class="bgClass(usuario.style)">
                    <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="" class="ms-2 img-perfil">
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
import { useRoute } from 'vue-router';
import axios from 'axios';

/* Obtenemos datos del usuario */
const store = useStore();
const route = useRoute(); 
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
        comprobarSeguido();
        obtenerUsuario();
    })
    .catch(error => {
        console.error('Error al seguir al usuario:', error);
    });
}

const comprobarSeguido = () => {
    if (usuario.value && userLogin.value) {
        const seguidores = usuario.value.seguidores.map(seguidor => seguidor.id);
        seguidorUsuarioActual.value = seguidores.includes(userLogin.value.id);
    }
};

const chat = () => {
    // Verificar si ya existe un chat entre el usuario actualmente logueado y el usuario visitado
    axios.post('/api/chat/' + usuario.value.id)
    .then(response => {
        console.log("Chat");
    })
    .catch(error => {
        console.error('Error al mostrar chat:', error);
    });
}

function logSelectedStyle() {
    console.log("Estilo seleccionado:", selectedStyle.value);

    // Verificar si ya existe un chat entre el usuario actualmente logueado y el usuario visitado
    axios.post('/api/usuarios/colorPost/' + selectedStyle.value)
    .then(response => {
        console.log(response.value);

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

// Funcion para el bg de los posts
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
.container-datos-usuario img {
    width: 100px;
    height: 100px;
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
</style>
