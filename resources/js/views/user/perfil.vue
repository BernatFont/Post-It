<template>
    <div class="mainPrincipal">
        <div v-if="usuario" class="py-2 px-5">
            <div class="top-content-view w-100 d-flex justify-content-between container-datos-usuario">
                <div>
                    <!-- Utilizamos el src dinámico para cargar la imagen del perfil del usuario -->
                    <img :src="usuario.imagen ? usuario.imagen : '/images/user-default.png'" alt="imagen del perfil del usuario" class="img-perfil">
                    <div class="ms-3 mt-5 d-flex flex-column">
                        <!-- Mostramos el nombre y apellido del usuario -->
                        <span>{{ usuario.name + (usuario.surname ? ' ' + usuario.surname : '')}}</span>
                        <!-- Mostramos el nombre de usuario -->
                        <span class="mt-3">@{{usuario.username}}</span>
                        <!-- Mostramos descripción del usuario -->
                        <span class="mt-3">{{usuario.biography}}</span>
                    </div>
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
        </div>
        <div>
            <select v-model="selectedStyle" @change="logSelectedStyle">
                <option style="background-color: var(--primero);" value="1">Estilo 1</option>
                <option style="background-color: var(--segundo);" value="2">Estilo 2</option>
                <option style="background-color: var(--tercero);" value="3">Estilo 3</option>
                <option style="background-color: var(--cuarto);" value="4">Estilo 4</option>
            </select>
        </div>
{}
        <div v-if="usuario" class="px-5">
            <p>{{ usuario.publicaciones }}</p>
            <div>
                <h2>Publicaciones de {{ usuario.name }} {{ usuario.surname }}</h2>
                <div v-for="publicacion in usuario.publicaciones" :key="publicacion.id" class="publicacion">
                    <p><strong>Texto:</strong> {{ publicacion.texto }}</p>
                    <p><strong>Fecha de creación:</strong> {{ new Date(publicacion.created_at).toLocaleDateString() }}</p>
                    <p><strong>Última actualización:</strong> {{ new Date(publicacion.updated_at).toLocaleDateString() }}</p>
                    <p><strong>N. likes:</strong> {{ publicacion.likes.length }}</p>
                    <p><strong>N. coments:</strong> {{ publicacion.comentarios.length }}</p>
                    <img :src="publicacion.media[0]?.original_url" alt="">
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
</style>
