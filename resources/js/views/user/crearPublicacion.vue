<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <div class="itty col-8 pl-5 ">
                <router-link :to="{ name: 'feed'}" class="pt-2 title-target">< </router-link>    
                <span class="pt-2 title-target">{{$t('create_post')}}</span>
            </div>
        </div>
    </div>
    <div class="mainPrincipal">
        <div v-if="usuario">
            <div class="content-view">
                <form class="container-width-createpost" @submit.prevent="addPublicacion">
                    <div class="container-createpost" :class="bgClass(usuario.style)">
                        <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                            <div v-if="usuario" class="d-flex">
                                <div class="contenedor-img-perfil">
                                    <img :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario" class="img-perfil">
                                </div>                         
                                <div class="d-flex flex-column justify-content-center">
                                    <span class="font1">{{ user.name }} {{ user.surname }}</span>
                                    <span class="font2">@{{ user.username}}</span>
                                </div>
                            </div>
                            <span class="mr-4 font2">{{ $t('no_published')}}</span>
                        </div>
                        <div class="px-3 py-2 card-post-text w-100">
                            <textarea v-model="publicacion.texto" class="form-control textarea-2 itty" @input="checkMaxLength" maxlength="300" :placeholder="$t('write')"></textarea>
                            <div v-if="maxLenghtTexto(publicacion.texto)" class="alert">{{ $t('limit_characters') }}</div>
                        </div>
                        <div v-if="imageUrl" class="px-5 card-post-img d-flex flex-column justify-content-center align-items-center">
                            <img src="/images/celo.png" alt="Celo que sujeta la imagen al post" class="celo">
                            <img :src="imageUrl" alt="imagen de la publicacion" class="img_post">
                        </div>
                        <div class="card-post-bottom d-flex">
                            <div class="d-flex align-items-center justify-content-center pi pb-3">
                                <img src="/images/like.svg" alt="Boton de Like" class="mx-3 corazon-img"><span class="itty number-of"> 0</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-center pi pb-3">
                                <img src="/images/comentarios.svg" alt="Boton de Comentarios" class="mx-3 comment-icon"><span class="itty number-of"> 0</span>
                            </div>   
                        </div>
                    </div>
                    <div class="section-options-createpost"> 
                        <div class="container-boton mb-1" v-if="!imageSelected">
                            <div class="sticky-btn-sticker bg-2c"></div>
                            <label for="file-upload" class="btnSticky sticky-btn-1 bg-2">
                                <div class="itty cursor-pointer">
                                    {{ $t('upload_image')}}
                                    <input type="file" id="file-upload" @change="onFileChange">
                                </div> 
                            </label>
                        </div>
                        <div class="container-boton mb-1" v-if="imageSelected">
                            <div class="sticky-btn-sticker bg-2c"></div>
                            <button @click="discardImage" class="btnSticky sticky-btn-1 bg-2 itty">{{ $t('discard_image')}}</button>
                        </div>
                        <div class="container-boton">
                            <div class="sticky-btn-sticker bg-3c"></div>
                            <button type="submit" class="btnSticky sticky-btn-1 bg-3 itty">{{ $t('publish')}}</button>
                        </div>
                    </div>
                </form>
            </div>    
        </div>
    </div>
</template>

<style scoped>

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
        height: 90px;
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

<script setup>
    import axios from "axios";
    import { inject, onMounted, ref , computed} from "vue";
    import router from "../../routes";
    import { useStore } from 'vuex';

    const usuario = ref(null);
    /* Obtenemos datos del usuario */
    const store = useStore();
    let user = computed(() => store.state.auth.user)

    onMounted(() => {
        obtenerUsuario();
    });

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

        // Verifica si hay al menos un campo presente (texto o imagen)
        if (!publicacion.value.texto && !publicacion.value.imagen) {
        // Muestra un mensaje de error o realiza alguna acción apropiada
        console.error('Debes ingresar al menos texto o una imagen para publicar.');
        swal({
            icon: 'error',
            title: 'Debes ingresar al menos texto o una imagen para publicar.',
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }
        // Agrega la lógica para enviar la imagen junto con el texto
        let PublicacionFormateada = new FormData()
        PublicacionFormateada.append('texto', publicacion.value.texto || '‎'); // Si hay texto lo envia en caso contrario se envia texto con un caracter vacio
        if (publicacion.value.imagen) {
            PublicacionFormateada.append('imagen', publicacion.value.imagen);
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
    
</script>



