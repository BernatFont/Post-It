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
                <router-link :to="{name: 'view.seguidores'}">Seguidores: {{ numSeguidores }}</router-link>
                <router-link :to="{name: 'view.seguidos'}">Seguidos: {{ numSeguidos }}</router-link>
            </div>
            <div>
                <button class="btn btn-postit">Editar perfil</button>
            </div>
        </div>
    </div>
    <div class="px-5">
        <div class="top-content-view w-100">
            {{ dataFollowers }}
        </div>    
    </div>
</template>


<script setup>
    import { onMounted, ref , computed} from "vue";
    import { useStore } from 'vuex';
    
    /* Obtenemos datos del usuario */
    const store = useStore();
    const user = computed(() => store.state.auth.user)
    const dataFollowers = ref(0);

    const numSeguidores = ref(0);
    const numSeguidos = ref(0);

    onMounted(() => {
        axios.get('/api/followers/' + user.value.id)
            .then(response => {
                dataFollowers.value = response.data;
                console.log(dataFollowers.value);
                numSeguidores.value = dataFollowers.value.seguidores_count;
                numSeguidos.value = dataFollowers.value.seguidos_count;
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
