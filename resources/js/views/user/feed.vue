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
        <div v-for="publicacion in publicaciones" class="card-post mb-5">
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
                <span>{{ publicacion.created_at }}</span>
            </div>
            <div class="card-post-text">
                <span>{{ publicacion.texto }}</span><br>
                <span>{{ publicacion.texto }}</span><br>
                <span>{{ publicacion.texto }}</span>
            </div>
            <div class="card-post-img">
                <img src="" alt="">
            </div>
            <div class="card-post-bottom">
                <i class="pi pi-heart p-3"></i><span>999</span>
                <i class="pi pi-comment p-3"></i><span>999</span>
            </div>
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
    }

    .card-post{

    }

    .card-post-top{
        background-color: #ffffff;
    }
    .card-post-top img{
        height: 50px;
        width: 50px;
        border-radius: 50%;
    }

    .card-post-text{
        background-color: #b4b4b8;
        border: solid 1px #000;
    }

    .card-post-img{
        background-color: rgb(255, 255, 255);
        width: 100%;
        aspect-ratio: 16/9;
    }

    .card-post-bottom{
        background-color: #afafaf;
        border: solid 1px #000;
        clip-path: polygon(0 0, 100% 0, 100% 50%, 98% 100%, 0 100%);
    }
</style>