<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 align-items-center">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 main_container">
            <div class="flex justify-center pt-8 sm:text-gray-600 dark:text-gray-400 text-sm responsive_container">
                <div class="title_description d-flex flex-column ">
                    <h1><b>{{ $t('welcome_starter_title') }}</b></h1>
                    <h3>{{ $t('welcome_starter_description') }}</h3>
                </div>
                <div class="login_buttons d-flex flex-column justify-content-center">   
                    <router-link class="btn btn-success mb-4 py-2" to="/login">{{ $t('login') }}</router-link>
                    <router-link class="btn btn-success py-2" to="/register">{{ $t('register') }}</router-link>
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
    .main_container {
        max-width: 1200px;
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
    h1 {
        font-size: 8rem;
    }
    @media (max-width: 920px) {
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
    }
</style>