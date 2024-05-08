<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between align-items-center topbar-title col-12">
            <span class="pt-2 itty pl-5 title-target">{{$t('admin_panel')}}</span>
            <div>
                <div class="font1 btn-filtro" @click="pageFilter()">{{ filtroActivo ? 'Mostrar Posts' : 'Mostrar Usuarios' }}</div>
            </div>
            <div class="notification-alert">
            </div>
        </div>
    </div>
    <div v-if="mostrarUsuarios" class="mainPrincipal">

        <div id="lista_usuarios" v-if="usuario">
            <div v-if="usuario.roles[0]?.name == 'admin'">
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
                                    <th>Actions</th>
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
    </div>
    <div v-else id="lista_posts">
        <div class="mainPrincipal">
            <div class="d-flex justify-content-center">
                <input v-model="search" type="text" placeholder="Contenido del post..." class="p-2 px20">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IMG</th>
                        <th>Username</th>
                        <th>Content</th>
                        <th>Likes</th>
                        <th>Comments</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="publicacion in publicaciones">
                        <td>{{ publicacion.id }}</td>
                        <td class="d-flex justify-content-center">
                            <div class="contenedor-img-perfil">
                                <img class="img-perfil" :src="publicacion.media[0]?.original_url ? publicacion.media[0].original_url : '/images/user-default.png'" alt="imagen del usuario">
                            </div>
                        </td>
                        <td>{{ publicacion.user.username }}</td>
                        <td>{{ publicacion.texto }}</td>
                        <td>{{ publicacion.likes_count }}</td>
                        <td>{{ publicacion.comentarios_count }}</td>
                        <td>
                            <router-link :to="{ name: 'publicacion.update', params: { id: publicacion.id } }">
                                <button class="btn btn-warning me-3">Modificar</button>
                            </router-link>
                            <button class="btn btn-danger" @click="deletePost(publicacion.id)">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { watch } from "vue";

const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const usuarios = ref([])
const usuario = ref(null)
const router = useRouter()
const store = useStore();
let mostrarUsuarios = ref(true);
const publicaciones = ref([]);
const search = ref('')
const filtroActivo = ref(true);

// Llamar a la función obtenerUsuarios cuando el componente se monte
onMounted(() => {
obtenerUsuarios();
obtenerUsuario();
getAllPosts();
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

function pageFilter(){
    mostrarUsuarios.value = !mostrarUsuarios.value;
    filtroActivo.value = !filtroActivo.value;
}

watch(search, (newSearch, oldSearch) => {
    console.log('New: '+ newSearch);
    console.log('Old: '+ oldSearch);

    if (newSearch) {
        getFilteredPosts(newSearch); // Al usar 'search' se aplica el filtro
    }else{
        getAllPosts();
    }
})

function getFilteredPosts(filter){
    axios.get('/api/publicacions/filter/' + filter)
    .then(response => {
        publicaciones.value = response.data;
        console.log(publicaciones.value)
    })
}

function getAllPosts(){
    axios.get('/api/publicacions')
    .then(response => {
        publicaciones.value = response.data;
    })
}

function deletePost(id){
    if (confirm('¿Estás seguro de que deseas eliminar este post?')) {
        axios.delete(`/api/publicacions/delete/${id}`)
        .then(() => {
            // Actualiza la lista de usuarios o notifica al usuario
            console.log('Post eliminado correctamente');
            //Obtenemos los usuarios
            getAllPosts();
        })
        .catch(error => {
            console.error('Error al eliminar el post:', error);
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
    font-size: 20px;
}

</style>