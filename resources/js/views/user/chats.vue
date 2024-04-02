<template>
    {{ chats }}
    <div v-for="chat in chats" :key="chat.id">
        <!--Devuelve unicamente los valores del usuario con el que no estamos logeados-->
        <div v-if="chat.user_id_1 !== userLogin.id">
            <span>Chat con {{ chat.user1.name, chat.user1.surname }}</span>
            <router-link :to="{ name: 'mostrar.chat', params: { id: chat.id } }">
                <div>
                    <button>Mostrar</button>
                </div>
            </router-link>
        </div>
        <div v-if="chat.user_id_2 !== userLogin.id">
            <span>Chat con {{ chat.user2.name, chat.user2.surname }}</span>
            <router-link :to="{ name: 'mostrar.chat', params: { id: chat.id } }">
                <div>
                    <button>Mostrar</button>
                </div>
            </router-link>
        </div>
    </div>

</template>

<script setup>
    import { onMounted, ref , computed} from "vue";
    import { useStore } from 'vuex';
    import { useRoute } from 'vue-router';
    
    /* Obtenemos datos del usuario */
    const store = useStore();
    const route = useRoute(); 
    const userLogin = computed(() => store.state.auth.user); // Usuario actual con sesion iniciada
    const chats = ref(null);

    onMounted(() => {
        axios.get('/api/chats')
            .then(response => {
                chats.value = response.data;
                console.log(chats.value);
                
            })
            .catch(error => {
            console.error('Error al cargar los datos de los chats:', error);
        }); 
    })
</script>

<style>
   
</style>
