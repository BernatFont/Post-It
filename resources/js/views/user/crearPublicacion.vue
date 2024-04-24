<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between searchContainer row topbar-title">
            <span class="pt-2 itty col-3 pl-5 title-target">{{$t('create_post')}}</span>
        </div>
    </div>
    <div class="mainPrincipal">
        <div class="content-view">
            <!-- <div class="w-100 w-md-50">
                <h3 class="text-center">Crea tu post</h3>
                <form @submit.prevent="addPublicacion" class="create-post-form me-4">
                    <div class="form-group mb-2">
                        <textarea v-model="publicacion.texto" class="form-control textarea" @input="checkMaxLength" maxlength="300" placeholder="Escribe aquí..."></textarea>
                        <div v-if="maxLenghtTexto(publicacion.texto)" class="alert">Limite de caracteres</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="pt-4">
                            <div v-if="!imageSelected">
                                <label for="file-upload" class="btn btn-primary">Subir imagen</label>
                                <input type="file" id="file-upload" @change="onFileChange">
                            </div>
                            <div v-if="imageSelected">
                                <button @click="discardImage" class="btn btn-discard">Descartar imagen</button>
                            </div>
                        </div>
                        <div class="pt-4">
                            <div>
                                <button type="submit" class="btn btn-postit">Subir publicación</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> -->
            <form @submit.prevent="addPublicacion">
                <div class="container-createpost" :class="bgClass()">
                        <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <img src="/images/placeholder.jpg" alt="" class="ms-2 img-perfil">
                                <div class="ms-3 d-flex flex-column justify-content-center">
                                    <span>{{ user.name }} {{ user.surname }}</span>
                                    <span>@{{ user.username}}</span>
                                </div>
                            </div>
                            <div class="btn-container">
                                <div class="sticky-btn-sticker"></div>
                                <button type="submit" class="btnSticky sticky-btn-1 itty">Publicar</button>
                            </div>
                        </div>
                        <div class="px-3 py-2 card-post-text w-100">
                            <textarea v-model="publicacion.texto" class="form-control textarea" @input="checkMaxLength" maxlength="300" placeholder="Escribe aquí..."></textarea>
                            <div v-if="maxLenghtTexto(publicacion.texto)" class="alert">Limite de caracteres</div>
                        </div>
                        <div class="card-post-img d-flex flex-column justify-content-center" v-if="imageSelected">
                            <img class="p-3" :src="imageUrl" alt="">
                        </div>
                        <div class="card-post-bottom d-flex">
                            <div class="d-flex align-items-center">
                                <i class="pi pi-heart p-3"></i><span>0</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="pi pi-comment p-3"></i><span>0</span>
                            </div>  
                        </div>
                </div>
                <div class="card mt-3">
                    
                    <div v-if="!imageSelected">
                        <label for="file-upload" class="btn btn-primary">Subir imagen</label>
                        <input type="file" id="file-upload" @change="onFileChange">
                    </div>
                    <div v-if="imageSelected">
                        <div>
                            <button @click="discardImage" class="btn">Descartar imagen</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>    
    </div>
    
</template>

<style scoped>

    .container-createpost {
        width: 100%;
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .post-text {
        max-width: 100%;
        word-wrap: break-word; /* Permite que el texto se divida en varias líneas */
    }
    .textarea{
        resize: none;
        height: 90px;
        background-color: none;
        border: 0 none;
        background: transparent;
        outline: none;
    }


    input[type="file"] {
        display: none;
    }

.btn-container {
    width: 125px;
    display: flex;
}
</style>

<script setup>
    import axios from "axios";
    import { inject, onMounted, ref , computed} from "vue";
    import router from "../../routes";
    import { useStore } from 'vuex';


    /* Obtenemos datos del usuario */
    const store = useStore();
    let user = computed(() => store.state.auth.user)

    // // Verifica si ya hemos recargado una vez
    // if (!localStorage.getItem('reloaded')) {
    //     // Establece la bandera de recargado
    //     localStorage.setItem('reloaded', 'true');
        
    //     // Recarga la página
    //     window.location.reload();
    // } else {
    //     // Limpia la bandera después de la recarga
    //     localStorage.removeItem('reloaded');
    // }
     

    const publicacion = ref({});
    const strError = ref();
    const strSuccess = ref();
    const swal = inject('$swal');


    // Esta función formatea el texto para hacer saltos de línea cuando es demasiado largo
    const formatText = (texto) => {
        if (typeof texto === 'undefined') {
            return ''; // Devuelve una cadena vacía si texto es undefined
        }
        // Definir la longitud máxima antes de hacer un salto de línea
        const maxLength = 50;
        if (texto.length > maxLength) {
            return texto.match(new RegExp('.{1,' + maxLength + '}', 'g')).join('\n');
        }
        return texto;
    };


    const maxLenghtTexto = (texto) => {
        const maxLength = 300;


        if ((typeof texto === 'undefined') || (texto.length != maxLength)) {
            return false; // Devuelve una cadena vacía si texto es undefined
        }
        if (texto.length >= maxLength) {
            return true;
        }
    }


    let imageSelected = ref(false);
    let imageUrl = ref('');


    const onFileChange = (event) => {
        const file = event.target.files[0];
        const filePath =event.target.value; // Obtiene la ruta completa del archivo seleccionado
        console.log(filePath);
        const reader = new FileReader();
        publicacion.value.imagen = file;
        console.log(publicacion.value.imagen);

        reader.onload = () => {
            imageUrl.value = reader.result;
            imageSelected.value = true;
        };


        reader.readAsDataURL(file);
    };


    const discardImage = () => {
        // Reinicia las variables relacionadas con la imagen seleccionada
        imageUrl.value = '';
        imageSelected.value = false;
    };


    const addPublicacion = async () => {
        // Agrega la lógica para enviar la imagen junto con el texto
        let PublicacionFormateada = new FormData()
        console.log(publicacion.value);
        for (let item in publicacion.value) {
            console.log(item);
            console.log(publicacion.value.hasOwnProperty(item));
            if (publicacion.value.hasOwnProperty(item)) {

                PublicacionFormateada.append(item, publicacion.value[item])
            }
        }   
        console.log(PublicacionFormateada);
        axios.post('/api/publicacions', PublicacionFormateada, {
            headers: {
                "content-type": "multipart/form-data"
            }
        })
            .then(response => {
                console.log(response);
                strSuccess.value = response.data.success;
                strError.value = '';
                swal({
                    icon: 'success',
                    title: 'Publicación creada',
                    showConfirmButton: false,
                    timer: 1500
                });
                // Redirige a otra página después de una tarea exitosa
                router.push('/inicio');
            }).catch(error => {
                console.log("Error al publicar")
                console.log(error);
                strError.value = error.response.data.message;
                strSuccess.value = '';
            });
    };

    function bgClass() {
      switch(user.value.style) {
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



