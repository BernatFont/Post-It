<template>
    <div class="d-flex justify-content-between py-2 top-content">
        <h2>Home</h2>
        <!-- <button class="btn btn-secondary">
            Publicar post
        </button> -->
        <div>
            <router-link :to="{name: 'publicacion.create'}" class="btn btn-postit">Publicar post</router-link>
        </div>
    </div>
    <div class="content-feed">
        <div v-for="publicacion in publicaciones">
            <span>{{ publicacion.id }}</span><br>
            <span>{{ publicacion.id_usuario }}</span><br>
            <span>{{ publicacion.texto }}</span><br>
            <span>{{ publicacion.created_at }}</span><br>
            <span>{{ publicacion.updated_at }}</span>
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
<style>
    .top-content{
        border-bottom: 2px solid #000;
    }
    .content-feed{
        width: 70%;
        margin: 20px auto;
        background-color: #fff;
    }
</style>