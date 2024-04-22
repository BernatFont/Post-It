<template>
    <div class="mainPrincipal content-view">
        <div class="title_page d-flex justify-content-center align-items-center mb-10">
            <h2 class="pt-3 itty">Chat</h2>
        </div>
        <div v-for="chat in chats" :key="chat.id">
            <div v-if="chats.length === 0">
                <p>No hay chats que mostrar</p>
            </div>
            <div v-else>
                <!--Devuelve unicamente los valores del usuario con el que no estamos logeados-->
                <div class="chatMain" v-if="chat.user_id_1 !== userLogin.id">
                    <div class="d-flex flex-row justify-content-between m-auto chatContainer">
                        <div class="d-flex flex-row">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: chat.user1.username } }">
                                <img class="imgPerfil" src="/images/user-default.png" alt="foto de perfil">
                            </router-link>
                            <div class="d-flex flex-column ml-3">
                                <span class="itty textName">{{chat.user1.name + (chat.user1.surname ? " " + chat.user1.surname : "")}}</span>
                                <span class="textContent">@{{ chat.user1.username }}</span>
                            </div>
                        </div>
                        <router-link class="d-flex justify-content-right align-items-center pl-3 pr-3" :to="{ name: 'mostrar.chat', params: { id: chat.id } }">{{ $t('show_chat') }}</router-link>
                    </div>
                </div>
                <div class="chatMain" v-if="chat.user_id_2 !== userLogin.id">    
                    <div class="d-flex flex-row justify-content-between m-auto chatContainer">
                        <div class="d-flex flex-row">
                            <router-link :to="{ name: 'usuario.mostrar', params: { username: chat.user2.username } }">
                                <img class="imgPerfil" src="/images/user-default.png" alt="foto de perfil">
                            </router-link>
                            <div class="d-flex flex-column ml-3">
                                <span class="itty textName">{{chat.user2.name + (chat.user2.surname ? " " + chat.user1.surname : "")}}</span>
                                <span class="textContent">@{{ chat.user2.username }}</span>
                            </div>
                        </div>
                        <router-link class="d-flex justify-content-right align-items-center pl-3 pr-3" :to="{ name: 'mostrar.chat', params: { id: chat.id } }">{{ $t('show_chat') }}</router-link>
                    </div>          
                </div>
            </div>
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

    .textName {
        font-size: 1.5rem;
    }  

    .chatMain {
        margin-top: 10px;
    }
    .chatContainer {
        background-image: url(/images/papel-comment.svg);
        background-size: cover;
        background-position: center;
        padding: 35px;
    }

    .imgPerfil {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            margin: auto;
        }
</style>
