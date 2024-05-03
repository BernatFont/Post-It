<template>
    <div v-if="usuario">
        <div v-if="usuario.roles[0]?.name == 'admin'" class="mainPrincipal">
            <div class="topbar-container bg-v2 ">
                <div class="d-flex justify-content-between topbar-title">
                    <span class="pt-2 itty col-8 pl-5 title-target">Panel de control</span>    
                </div>
            </div>
            <div v-if="usuarios" class="w-100">
                <div class="datosUsuarios">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>IMG</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Birth date</th>
                                <th>Acctions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuario in usuarios">
                                <td>{{ usuario.id }}</td>
                                <td class="d-flex justify-content-center">
                                    <div class="contenedor-img-perfil">
                                        <img class="img-perfil" :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="imagen del usuario">
                                    </div>
                                </td>
                                <td>{{ usuario.name }}</td>
                                <td>{{ usuario.username }}</td>
                                <td>{{ usuario.birth_date }}</td>
                                <td>
                                    <router-link :to="{ name: 'perfil.modificar', params: { username: usuario.username }}">
                                        <button class="btn btn-warning me-3">Modificar</button>
                                    </router-link>
                                    <button class="btn btn-danger" @click="eliminarUsuario(usuario.id)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const usuarios = ref([])
const usuario = ref(null)
const router = useRouter()
const store = useStore();


// Llamar a la función obtenerUsuarios cuando el componente se monte
onMounted(() => {
obtenerUsuarios();
obtenerUsuario();
})


// Realizamos la solicitud para obtener los datos del usuario
const obtenerUsuario = () => {
    axios.get('/api/usuario/' + userLogin.value.username)
    .then(response => {
        // Actualizamos los datos del usuario
        usuario.value = response.data;
        console.log(usuario.value);
        if(usuario.value.roles[0]?.name != 'admin'){
            router.push({path: '/inicio'})
        }
    })
    .catch(error => {
            console.error('Error al cargar los datos del usuario:', error);
    }); 
};

// Función para obtener la lista de usuarios desde la API
const obtenerUsuarios = () => {
    axios.get('/api/usuarios')
    .then(response => {
        usuarios.value = response.data;
        console.log(usuarios.value);
    })
    .catch(error => {
        console.error('Error al obtener usuarios:', error);
    });
}  

const eliminarUsuario = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        axios.delete(`/api/usuarios/${id}`)
        .then(() => {
            // Actualiza la lista de usuarios o notifica al usuario
            console.log('Usuario eliminado correctamente');
            //Obtenemos los usuarios
            obtenerUsuarios();
        })
        .catch(error => {
            console.error('Error al eliminar el usuario:', error);
        });
    }
}

</script>

<style scoped>

/* Estilos generales para la tabla */
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    font-family: 'Arial', sans-serif;
}

th, td {
    padding: 8px 12px;
    border: 1px solid #ccc;
}

/* Estilos para la cabecera de la tabla */
thead th {
    background-color: #f4f4f4;
    color: #333;
}

/* Estilos para las filas */
tbody tr {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

/* Mejorando la visualización del texto */
th, td {
    font-size: 16px;
}

</style>