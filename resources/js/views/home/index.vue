<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 principal">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 main_container">
            <div class="flex justify-center pt-8 sm:text-gray-600 dark:text-gray-400 text-sm responsive_container">
                <div class="title_description d-flex flex-column ">
                    <div class="flex">
                        <img class="imagen" src="/images/post-it.png" alt="Descripción de la imagen">
                        <h1 class="titulo"><b>{{ $t('welcome_starter_title') }}</b></h1> 
                    </div>
                    <h3 class="descripcion"><span>📌</span>{{ $t('welcome_starter_description') }}</h3>
                </div>
                <div class="login_buttons d-flex flex-column justify-content-center">   
                    <router-link class="btn btn-postit mb-4 py-2" to="/login">{{ $t('login') }}</router-link>
                    <router-link class="btn btn-postit py-2" to="/register">{{ $t('register') }}</router-link>
                </div>
            </div>
            <!-- <div v-for="publicacion in publicaciones">
                <span>{{ publicacion.id }}</span><br>
                <span>{{ publicacion.id_usuario }}</span><br>
                <span>{{ publicacion.texto }}</span><br>
                <span>{{ publicacion.created_at }}</span><br>
                <span>{{ publicacion.updated_at }}</span>
            </div> -->
        </div>
    </div>
</template>

<script setup>

    import axios from "axios";
    import {ref,inject,onMounted} from "vue";

    const publicaciones = ref();

    onMounted(() => {
        axios.get('/api/publicacions')
        .then(response => {
            publicaciones.value = response.data;
            console.log(response.data);
        })
    })

</script>

<style scoped>
body {
    font-family: 'Nunito', sans-serif;
}
</style>

<style>
    .principal {
        align-items: center;
        background-image: url(/public/images/landing-bg.jpg);
        background-size: cover;
    }
    .main_container {
        max-width: 80%;
    }
    .title_description {
        width: 60%;    
        /* background: red; */
    }
    .login_buttons {
        width: 40%;
        align-items: end;
        /* background: blue; */

    }
    .login_buttons a {
        width: 65%;
        font-size: 20px;
    }
    .titulo {
        font-size: 75px;
        margin: 0;
        background: linear-gradient(to top left, #B5FF90 20%, #76FD31 80%);
        -webkit-background-clip: text;
        color: transparent; /* Make the text color transparent */
    }

    .descripcion {
        font-size: 30px;
    }

    .imagen {
        height: 100px;
        width: auto;
    }

    @media (max-width: 920px) {
        .principal {
            align-items:flex-start;
        }
        .title_description, .login_buttons a {
            width: 100%;    
        }
        .responsive_container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .login_buttons {
            align-items: center;
            margin-top: 40px;
            width: 100%;
        }
        .titulo {
            font-size: 55px;
        }
        .descripcion {
            font-size: 20px;
        }
        .imagen {
            height: 72.8px;
        }
    }
</style>