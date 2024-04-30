<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <span class="pt-2 itty col-8 pl-5 title-target">{{$t('modify_profile')}}</span>
        </div>
    </div>
    <div class="mainPrincipal" v-if="usuario">
        <div class="content-view">
            <div class="d-flex justify-content-center align-items-center card">
                <div class="bg-img-perfil d-flex flex-column align-items-center pt-6">
                    <img 
                        style="min-height: 120px; max-width: 150px; transform: rotate(5deg)" 
                        :src="imagenSeleccionada || (usuario && usuario.image) || (usuario && usuario.media && usuario.media[0] && usuario.media[0].original_url) || '/images/user-default.png'"
                        alt="User Image">
                    <label class="custum-file-upload mt-3" for="file">
                        <i class="pi pi-plus"></i>
                        <input type="file" id="file" @change="seleccionarImagen">
                    </label>
                </div>
            </div>
             <!-- Campos para editar los datos del usuario -->
             <div class="formContainer card border-0 shadow-sm">
                 <div class="form-group">
                     <label for="username">{{$t('username')}}</label>
                     <input type="text" id="username" v-model="usuario.username" readonly>
                 </div>
                 <div class="form-group">
                     <label for="email">{{$t('email')}}</label>
                     <input type="text" id="email" v-model="usuario.email" readonly>
                 </div>
                 <div class="form-group">
                     <label for="name">{{$t('name')}}*</label>
                     <input type="text" id="name" maxlength="20" v-model="usuario.name" :class="{'error-border': errorMessageName}">
                     <small v-if="errorMessageName" class="form-text text-danger">{{$t('not_empty')}}</small>
                 </div>
                 <div class="form-group">
                     <label for="surname">{{$t('surname')}}</label>
                     <input type="text" id="surname" maxlength="20" v-model="usuario.surname">
                 </div>
                 <div class="form-group">
                     <label for="biography">{{$t('biography')}}</label>
                     <input type="text" id="biography" maxlength="200" v-model="usuario.biography">
                 </div>
                 <div class="form-group">
                     <label for="birth">{{$t('birth_date')}}</label>
                     <input type="date" id="birth" v-model="usuario.birth_date">
                 </div>
                 <div class="container-boton mt-5">
                     <div class="sticky-btn-sticker bg-3c"></div>
                     <button class="btnSticky sticky-btn-1 bg-3" @click="guardarCambios">{{$t('save_changes')}}</button>
                 </div>
             </div>
        </div>
    </div>
</template>

<style>

    .card {
        border-radius: 0px;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #fff;
    }

    .modificar_perfil{
        width: 600px;
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

.form-group{
    display: flex;
    flex-direction: column;
    margin-top: 12px;
}
.form-group input{
    padding: 8px;
}

@media (max-width: 600px){
    .modificar_perfil{
        width: 100%;
    }   
}

.error-border {
    border: 1px solid red;
}

</style>

<script setup>
import { onMounted, ref, inject, shallowReadonly } from "vue";
import axios from "axios";
import { useRoute } from 'vue-router';

const route = useRoute(); 
const username = route.params.username;
const usuario = ref(null);
const imagenSeleccionada = ref(null);
const strSuccess = ref(null);
const strError = ref(null);
const errorMessageName = ref(null);
const swal = inject('$swal');


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
            // Actualiza la imagen seleccionada en tu componente Vue
            imagenSeleccionada.value = e.target.result;

            // Crea un FormData para enviar la imagen al servidor
            const formData = new FormData();
            formData.append('imagen', file);
            console.log(file);

            // Envía la imagen al servidor usando Axios
            axios.post('/api/usuarios/modificarImagen', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                // Maneja la respuesta del servidor si es necesario
                console.log(response.data);
                console.log('Imagen enviada');
            }).catch(error => {
                // Maneja cualquier error
                console.error(error);
            });
        };
        reader.readAsDataURL(file);
    }
};


const guardarCambios = () => {
    const name = document.getElementById('name')
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
        errorMessageName.value = false;
        swal({
            icon: 'success',
            title: 'Datos modificados',
            showConfirmButton: false,
            timer: 1500
        });
    })
    .catch(error => {
        console.log('Nombre obligatorio');
        strSuccess.value = "";
        strError.value = error.response.data.message;
        errorMessageName.value = true;
    });
}
</script>