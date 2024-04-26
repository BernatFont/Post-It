<template>
    
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <span class="pt-2 itty col-8 pl-5 title-target">{{$t('chat')}}</span>    
        </div>
    </div>
    <div class="mainPrincipal">
        <div v-for="chat in chats" :key="chat.id">
            <div v-if="chats.length === 0">
                <p>{{$t('no_show_chats')}}</p>
            </div>
            <div v-else>
                <div class="userContainer d-flex flex-row align-items-center justify-content-between" v-if="chat.user_id_1 !== userLogin.id">
                    <div class="d-flex flex-row">
                        <router-link class="d-flex" :to="{ name: 'usuario.mostrar', params: { username: chat.user1.username } }">
                            <div class="contenedor-img-perfil">
                                <img class="img-perfil" :src="chat.user1.media[0]?.original_url ? chat.user1.media[0].original_url : '/images/user-default.png'" alt="foto de perfil">
                            </div>    
                            <div class="d-flex flex-column">
                                <span class="itty font1">{{chat.user1.name + (chat.user1.surname ? " " + chat.user1.surname : "")}}</span>
                                <span class="itty font2">@{{ chat.user1.username }}</span>
                            </div> 
                        </router-link>
                    </div>
                    <div>
                        <router-link class="btnIr itty" :to="{ name: 'mostrar.chat', params: { id: chat.id } }">{{ $t('show_chat') }}</router-link>
                    </div>
                </div>
                <div class="userContainer d-flex flex-row align-items-center justify-content-between" v-if="chat.user_id_2 !== userLogin.id">
                    <div class="d-flex flex-row">
                        <router-link class="d-flex" :to="{ name: 'usuario.mostrar', params: { username: chat.user2.username } }">
                            <div class="contenedor-img-perfil">
                                <img class="img-perfil" :src="chat.user2.media[0]?.original_url ? chat.user2.media[0].original_url : '/images/user-default.png'" alt="foto de perfil">
                            </div>        
                            <div class="d-flex flex-column">
                                <span class="itty font1">{{chat.user2.name + (chat.user2.surname ? " " + chat.user2.surname : "")}}</span>
                                <span class="itty font2">@{{ chat.user2.username }}</span>
                            </div> 
                        </router-link>
                    </div>
                    <div>
                        <router-link class="btnIr itty" :to="{ name: 'mostrar.chat', params: { id: chat.id } }">{{ $t('show_chat') }}</router-link>
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

<style scoped>

    .textName {
        font-size: 1.5rem;
    }  

    .chatContainer {
        background-image: url(/images/papel-comment.svg);
        background-size: cover;
        background-position: center;
        padding: 35px;
        width: 50%;
    }

</style>
