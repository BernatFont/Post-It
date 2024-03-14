<template>
    <div class="py-2 px-5 d-flex justify-content-center">
        <div class="top-content-view w-100 d-flex justify-content-between">
            <h2>Publicar un post</h2>
        </div>
    </div>
    <div class="d-md-flex justify-content-between p-5 h-100">
        <form @submit.prevent="addPublicacion" class="w-100 w-md-50 pe-4">
            <h3 class="text-center">Crea tu post</h3>
            <div class="form-group mb-2">
                <textarea v-model="publicacion.texto" class="form-control textarea" @input="checkMaxLength" maxlength="300" placeholder="Escribe aquí..."></textarea>
                <div v-if="maxLenghtTexto(publicacion.texto)" class="alert">Limite de caracteres</div>
                <input type="file" class="pt-4">
            </div>

            <button type="submit" class="btn btn-postit mt-4 mb-4">Añadir Tarea</button>

        </form>
        <div class="w-100 w-md-50">
            <h3 class="text-center">Vista previa</h3>
            <div class="card-post-top p-2 d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <img src="/images/placeholder.jpg" alt="" class="ms-2">
                    <div class="ms-3 d-flex flex-column justify-content-center">
                        <span>David Valero</span>
                        <span>@Xtrimo</span>
                        <!-- <span>{{ publicacion.id }}</span>
                        <span>{{ publicacion.id_usuario }}</span> -->
                    </div>
                </div>
                <span class="pe-3">Fecha</span>
            </div>
            <div class="px-3 py-2 card-post-text w-100">
                <p class="post-text">{{ formatText(publicacion.texto) }}</p>
            </div>
            <!-- <div class="card-post-img d-flex justify-content-center">
                <img src="/images/prueba.jpg" alt="">
            </div> -->
            <div class="card-post-bottom d-flex">
                <div class="d-flex align-items-center">
                    <i class="pi pi-heart p-3"></i><span>999</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="pi pi-comment p-3"></i><span>999</span>
                </div>   
            </div>
        </div>
    </div>    
</template>

<script setup>
    import axios from "axios";
    import { inject, ref } from "vue";
    import router from "../../routes";
    
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


    const addPublicacion = async () =>{
        await axios.post('/api/publicacions',publicacion.value)
        .then(response => {
            console.log(response);
            strSuccess.value = response.data.success
            strError.value = ''
            swal({
                icon: 'success',
                title: 'Publicación creada',
                showConfirmButton: false,
                timer: 1500
            })
            // Redirige a otra página después de una tarea exitosa
            router.push('/inicio');
        }).catch(error => {
            console.log(error);
            strError.value = error.response.data.message;
            strSuccess.value = ''

        })
    } 
    

</script>
<style>
    .post-text {
        max-width: 100%;
        word-wrap: break-word; /* Permite que el texto se divida en varias líneas */
    }

    .textarea{
        resize: none;
    }
</style>