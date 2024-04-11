<template>
    <div class="div-form-msg">
        <form class="form-msg" @submit.prevent="añadirMensaje"> <!-- Formulario para escribir un nuevo mensaje -->
            <textarea class="textarea" v-model="nuevoMensaje" placeholder="Escribe tu mensaje"></textarea>
            <button class="enviar" type="submit">Enviar</button>
        </form>
    </div>
    <div class="content-view mainPrincipal">
        <div class="" v-if="chat">
            <div class="d-flex flex-row" v-if="userLogin.id === chat.user_id_2">
                <img class="imgPerfil" src="/images/user-default.png" alt="">
                <div class="d-flex flex-column">
                    <span class="itty textContent">{{ chat.user1.name + " " + chat.user1.surname }}</span>
                    <span class="itty textUsername">@{{ chat.user1.username }}</span>
                </div>
            </div>
            <div class="d-flex flex-row" v-else-if="userLogin.id === chat.user_id_1">
                <img class="imgPerfil" src="/images/user-default.png" alt="">
                <div class="d-flex flex-column">
                    <span class="itty textContent">{{ chat.user2.name + " " + chat.user2.surname }}</span>
                    <span class="itty textUsername">@{{ chat.user2.username }}</span>
                </div>
                
            </div>
        </div>
    <div v-if="mensajes.length === 0">
            <p>No se ha enviado ningun mensaje</p>
        </div>
        <div v-else>
            <div class="d-flex" v-for="mensaje in mensajes" :key="mensaje.id" :class="{'usuarioActual': mensaje.user.id === userLogin.id, 'otroUsuario': mensaje.user.id !== userLogin.id}">
                <div class="container-msg" :class="{'bg-v1': mensaje.user.id === userLogin.id, 'bg-v2': mensaje.user.id !== userLogin.id}">
                    <span class="itty textContent">{{ mensaje.contenido}}</span>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { onMounted, ref, computed } from "vue";
    import { useStore } from 'vuex';
    import { useRoute } from 'vue-router';
    
    const store = useStore();
    const route = useRoute();
    const mensajes = ref([]);
    const nuevoMensaje = ref('');
    const userLogin = computed(() => store.state.auth.user);
    const chatId = route.params.id;
    const chat = ref();

    onMounted(() => {
        cargarMensajes();
        obtenerChat();
    });
    // Devuelve un array con todos los mensajes del chat
    const cargarMensajes = () => {
        axios.get(`/api/chat/mensajes/${chatId}`)
            .then(response => {
                mensajes.value = response.data;
            })
            .catch(error => {
                console.error('Error al cargar los mensajes del chat:', error);
            });
    }

    const obtenerChat = () => {
        axios.get('/api/chats/' + chatId)
            .then(response => {
                chat.value = response.data[0];
            }).catch(error => {
                console.error('Error al cargar chat:', error);
            });
    }

    

    const añadirMensaje = () => {
        const chatId = route.params.id;
        axios.post(`/api/chat/mensajes/${chatId}`, {
            user_id: userLogin.value.id,
            contenido: nuevoMensaje.value
        })
        .then(response => {
            // Recarga los mensajes al agregar uno nuevo para mostrarlo
            cargarMensajes();
            // Se vacia nuevoMensaje para evitar problemas al crear otro mensaje
            nuevoMensaje.value = '';
        })
        .catch(error => {
            console.error('Error al añadir el mensaje:', error);
        });
    }
    
</script>

<style>
    .usuarioActual {
        justify-content: left;
    }
    
    .otroUsuario {
        justify-content: right;
    }
    
    .bg-v1 {
        background-image: url(/public/images/papel-comment.svg);
        background-size: cover;
        background-repeat: no-repeat;
    }
    .bg-v2 {
        background-image: url(/public/images/papel-comment-2.svg);
        background-size: cover;
        background-repeat: no-repeat;
    }
    

    .container-msg {
        padding: 24px;
        margin-top: 5px;
        margin-bottom: 5px;
        min-width: 250px;
    }
    .div-form-msg {
        padding: 14px;
        width: 100%;
        background-color: #fff;
        position: fixed;
        top: 89%;
    }

    .imgPerfil {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: auto 0 auto 0;
    }

    .textContent {
        font-size: 1.5rem
    }  

    .textUsername {
        font-size: 1.3rem
    }

    .div-form-msg {
    width: 100%;
    }

    .form-msg {
        display: flex;
        flex: row;
    }

    .textarea {
        width: 60%;
        resize: none; /* Para evitar que el usuario redimensione el textarea */
    }

    .enviar {
        width: 20%;
    }
    
</style>
