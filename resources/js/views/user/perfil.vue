<template>
    <div class="py-2 px-5">
        <div class="top-content-view w-100 d-flex  justify-content-between container-datos-usuario">
            <div>
                <img src="/images/prueba.jpg" alt="imagen del perfil del usuario" class="img-perfil">
                <div class="ms-3 mt-5 d-flex flex-column">
                    <span>{{ user.name }} {{ user.surname }}</span>
                    <span class="mt-3">@{{ user.username }}</span>
                </div>
            </div>
            <div class="d-flex flex-column">
                <a href=""><span>Seguidores: {{ followers.get_usuarios_que_le_siguen_count }}</span></a>
                <span>Sigues: {{ followers.get_usuario_seguidos_count }}</span>
            </div>
            <div>
                <button class="btn btn-warning">Editar perfil</button>
            </div>
        </div>
    </div>
    <div class="px-5">
        <div class="top-content-view w-100">
            <div v-for="(seguidor, index) in dataFollowers.get_usuarios_que_le_siguen" :key="index">
                <p>ID del seguidor: {{ seguidor.id }}</p>
                <p>ID del usuario seguido: {{ seguidor.id_usuario_seguido }}</p>
            </div>
            <!-- {{ dataFollowers }} -->
        </div>    
    </div>
</template>


<script setup>
    import { inject, onMounted, ref , computed} from "vue";
    import { useStore } from 'vuex';


    /* Obtenemos datos del usuario */
    const store = useStore();
    const user = computed(() => store.state.auth.user)
    const followers = ref(0);
    const dataFollowers = ref(0);


    const num = 0;
    onMounted(() => {
        axios.get('/api/followers/' + user.value.id)
            .then(response => {
                followers.value = response.data;
                // console.log(followers.value);
            })
    })


    onMounted(() => {
        axios.get('/api/followers/data/' + user.value.id)
            .then(response => {
                dataFollowers.value = response.data;
                console.log(dataFollowers.value);
            })
    })
</script>


<style>
    .container-datos-usuario img{
        width: 100px;
        height: 100px;
    }
    .container-datos-usuario div span{
        font-size: 20px;
    }
   
</style>
