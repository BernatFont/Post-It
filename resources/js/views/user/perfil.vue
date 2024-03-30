<template>
    <div class="py-2 px-5">
        <div class="top-content-view w-100 d-flex justify-content-between container-datos-usuario">
            <div>
                <!-- Utilizamos el src dinámico para cargar la imagen del perfil del usuario -->
                <img :src="usuario && usuario.imagen ? usuario.imagen : '/images/placeholder.jpg'" alt="imagen del perfil del usuario" class="img-perfil">
                <div class="ms-3 mt-5 d-flex flex-column">
                    <!-- Mostramos el nombre y apellido del usuario -->
                    <span>{{ usuario ? usuario.name + (usuario.surname ? ' ' + usuario.surname : '') : 'Cargando...' }}</span>

                    <!-- Mostramos el nombre de usuario -->
                    <span class="mt-3">@{{ usuario ? usuario.username : 'Cargando...' }}</span>
                </div>
            </div>
            <div class="d-flex flex-column">
                <!-- Enlaces a las vistas de seguidores y seguidos -->
                <router-link :to="{name: 'view.seguidores'}">Seguidores: {{ usuario ? usuario.seguidores_count : 'Cargando...' }} </router-link>
                <router-link :to="{name: 'view.seguidos'}">Seguidos: {{ usuario ? usuario.seguidos_count : 'Cargando...' }}</router-link>
            </div>
            <div>
                <!-- Botón para editar el perfil o seguir segun el usuario logeado -->
                <button v-if="usuario && userLogin && usuario.id === userLogin.id" class="btn btn-postit">Editar perfil</button>
                <button v-else-if="usuario && userLogin && !seguidorUsuarioActual" @click="seguir" class="btn btn-postit">Seguir</button>
                <button v-else-if="usuario && userLogin && seguidorUsuarioActual" @click="seguir" class="btn btn-postit">Dejar de seguir</button>
            </div>
        </div>
    </div>
    <div class="px-5">
        <div class="top-content-view w-100">
            <!-- Contenido adicional -->
            {{ usuario }}
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
const route = useRoute(); // Importar $route utilizando useRoute()
const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const username = route.params.username; // Obtiene el usuario por la ruta
const usuario = ref(null);
const seguidorUsuarioActual = ref(false);

onMounted(() => {
obtenerUsuario();
});

// Realizamos la solicitud para obtener los datos del usuario
const obtenerUsuario = () => {
    axios.get('/api/usuario/' + username)
    .then(response => {
            // Actualizamos los datos del usuario
            usuario.value = response.data;
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
</script>

<style>
.container-datos-usuario img {
    width: 100px;
    height: 100px;
}
.container-datos-usuario div span {
    font-size: 20px;
}
</style>
