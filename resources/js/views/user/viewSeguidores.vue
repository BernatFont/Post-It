<template>
    <div class="px-5">
        <div class="top-content-view w-100">
            <div v-for="usuario in dataFollowers.seguidores">
                <p>ID del seguidor: {{ usuario.username }}</p>
            </div>
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
        axios.get('/api/followers/data/' + user.value.id)
            .then(response => {
                dataFollowers.value = response.data;
                console.log(dataFollowers.value);
                numSeguidores.value = dataFollowers.value.seguidores.length;
                numSeguidos.value = dataFollowers.value.seguidos.length;
            })
    })

</script>


<style>
   
</style>
