<template>
    <div v-if="usuario" class="mainPrincipal">
        <div class="content-view">
            <div class="bg-img-perfil d-flex flex-column align-items-center pt-6">
                <img 
                    style="min-height: 120px; max-width: 150px; transform: rotate(5deg)" 
                    :src="(imagenSeleccionada || (usuario && usuario.image)) ? (imagenSeleccionada || usuario.image) : '/images/user-default.png'"
                    alt="User Image">
                <label class="custum-file-upload mt-3" for="file">
                    <i class="pi pi-plus"></i>
                    <input type="file" id="file" @change="seleccionarImagen">
                </label>
            </div>
             <!-- Campos para editar los datos del usuario -->
            <div class="form-group">
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" v-model="usuario.username" readonly>
            </div>
            <div class="form-group">
                <label for="email">Correo electronico:</label>
                <input type="text" id="email" v-model="usuario.email" readonly>
            </div>
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" v-model="usuario.name">
            </div>
            <div class="form-group">
                <label for="surname">Apellido:</label>
                <input type="text" id="surname" v-model="usuario.surname">
            </div>
            <div class="form-group">
                <label for="biography">Descripción:</label>
                <input type="text" id="biography" v-model="usuario.biography">
            </div>
            <div class="form-group">
                <label for="birth">Cumple:</label>
                <input type="date" id="birth" v-model="usuario.birth_date">
            </div>
            <button class="btn-guardar" @click="guardarCambios">Guardar Cambios</button>
        </div>
    </div>
</template>

<style>
.custum-file-upload {
    height: 20px;
    width: 20px;
    background-color: var(--primero);
    display: flex;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    padding: 1.2rem;
    border-radius: 50%;
}

.bg-img-perfil{
    height: 200px;
    width: 200px;
    background-image: url(/images/papel-responsive1.svg);
    background-position: center;
    background-repeat: no-repeat;
}

.custum-file-upload input {
  display: none;
}
</style>

<script setup>
import { onMounted, ref } from "vue";
import axios from "axios";
import { useRoute } from 'vue-router';

const route = useRoute(); 
const username = route.params.username;
const usuario = ref(null);
const imagenSeleccionada = ref(null);
const strSuccess = ref(null);
const strError = ref(null);

onMounted(() => {
    obtenerUsuario();
});

const obtenerUsuario = () => {
    axios.get('/api/usuario/' + username)
    .then(response => {
            usuario.value = response.data;
    })
    .catch(error => {
            console.error('Error al cargar los datos del usuario:', error);
    }); 
};

const seleccionarImagen = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagenSeleccionada.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const guardarCambios = () => {
    console.log(usuario.value)
    console.log(usuario.value.birth_date)

    axios.post('/api/usuarios/modificar', usuario.value, {
        headers: {
            "content-type": "multipart/form-data"
        }
    })
    .then(response => {
        console.log('dentro then');
        strError.value = "";
        strSuccess.value = response.data.success;
    })
    .catch(error => {
        strSuccess.value = "";
        strError.value = error.response.data.message;
    });
}
</script>