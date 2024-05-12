<template>
    <div v-if="chat">
        <div v-if="userLogin.id === chat.user_id_2 || userLogin.id === chat.user_id_1">
            <div class="topbar-container d-flex align-items-center bg-v2 ">
                <div class="d-flex justify-content-between topbar-title">
                    <div class="itty d-flex col-8">
                        <router-link :to="{ name: 'chats'}" class="me-2 pt-2 title-target">< </router-link>    
                    </div>
                </div>
                <div>
                    <div class="topbar-title d-flex" v-if="userLogin.id === chat.user_id_2">
                        <router-link class="d-flex" :to="{ name: 'usuario.mostrar', params: { username: chat.user1.username } }">
                            <div class="contenedor-img-perfil">
                                <img class="img-perfil" :src="chat.user1.media[0]?.original_url ? chat.user1.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario">
                            </div>    
                            <div class="chat-topbar-user">
                                <span class="itty textContent">{{chat.user1.name + (chat.user1.surname ? " " + chat.user1.surname : "")}}</span>
                                <span class="itty textUsername">@{{ chat.user1.username }}</span>
                            </div>
                        </router-link>
                    </div>
                    <div class="topbar-title d-flex" v-else-if="userLogin.id === chat.user_id_1">
                        <router-link class="d-flex" :to="{ name: 'usuario.mostrar', params: { username: chat.user2.username } }">
                            <div class="contenedor-img-perfil">
                                <img class="img-perfil" :src="chat.user2.media[0]?.original_url ? chat.user2.media[0].original_url : '/images/user-default.png'" alt="Foto de perfil del usuario">
                            </div>             
                            <div class="chat-topbar-user">
                                <span class="itty textContent">{{chat.user2.name + (chat.user2.surname ? " " + chat.user2.surname : "")}}</span>
                                <span class="itty textUsername">@{{ chat.user2.username }}</span>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="mainPrincipal">
                <div class="contenedorChat">
                    <div class="content-view">
                        <div class="container-msg textContent itty chatContainer d-flex justify-content-center" v-if="mensajes.length === 0">
                            <p class="itty textContent">{{ $t('chat_no_message') }}</p>
                        </div>
                        <div v-else>
                            <div class="d-flex" v-for="mensaje in mensajes" :key="mensaje.id" :class="{'usuarioActual': mensaje.user.id === userLogin.id, 'otroUsuario': mensaje.user.id !== userLogin.id}">
                                <div class="container-msg d-flex justify-content-between" :class="{'bg-v1': mensaje.user.id === userLogin.id, 'bg-v2': mensaje.user.id !== userLogin.id}">
                                    <span class="itty textContent">{{ mensaje.contenido}}</span>
                                    <span class="itty fecha">{{ formatearFecha(mensaje.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contenedorFormulario">
                <form class="formularioMensaje" @submit.prevent="añadirMensaje">
                    <div class="d-flex w-100">
                        <textarea v-model="nuevoMensaje" class="form-control itty textarea" @input="checkMaxLength" maxlength="255" :placeholder="$t('write')"></textarea>
                        <button type="submit" class="btnEnviar itty pi pi-send"></button>
                    </div>
                    <div v-if="maxLenghtTexto(nuevoMensaje)" class="ml-3">{{ $t('limit_characters_255') }}</div>
                </form>      
            </div>

        </div>
    </div>
    <div v-else>
        <div class="topbar-container bg-v2">
            <div class="d-flex justify-content-between topbar-title">
                <span class="pt-2 itty col-8 pl-5 title-target">{{$t('chat')}}</span>   
            </div>
        </div>
        <div class="mainPrincipal">
            <div class="userContainer d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row">
                    <div class="d-flex flex-column">
                        <router-link :to="{ name: 'chats'}"><span class="itty">< {{$t('back')}}</span></router-link>
                        <span class="">{{ $t('error_chat')}}</span>
                    </div> 
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { onMounted, ref, computed } from "vue";
    import { useStore } from 'vuex';
    import { useRouter, useRoute } from 'vue-router';
    import router from "../../routes";
    
    const store = useStore();
    const route = useRoute();
    const router2 = useRouter();
    const mensajes = ref([]);
    const nuevoMensaje = ref('');
    const userLogin = computed(() => store.state.auth.user);
    const chatId = route.params.id;
    const chat = ref();

    onMounted(() => {
        console.log(chatId);
        cargarMensajes();
        obtenerChat();
    });
    // Devuelve un array con todos los mensajes del chat
    const cargarMensajes = () => {
        axios.get(`/api/chat/mensajes/` + chatId)
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
                console.log(userLogin.value.id);
                if(chat.value.user_id_1 != userLogin.value.id && chat.value.user_id_2 != userLogin.value.id) {
                    router2.push({ path: '/chats' });
                }
            }).catch(error => {
                console.error('Error al cargar chat:', error);
                router2.push({ path: '/chats' });
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

    // Función para calcular la diferencia en minutos, horas y días
    const calcularDiferencia = (fechaPublicacion) => {
    const fechaPublicacionObjeto = new Date(fechaPublicacion);
    const fechaActual = new Date();
    const diferenciaEnMs = fechaActual - fechaPublicacionObjeto;
    const diferenciaEnMinutos = Math.floor(diferenciaEnMs / (1000 * 60));
    const diferenciaEnHoras = Math.floor(diferenciaEnMinutos / 60);
    const diferenciaEnDias = Math.floor(diferenciaEnHoras / 24);
    return { minutos: diferenciaEnMinutos, horas: diferenciaEnHoras, dias: diferenciaEnDias };
    };

    // Función para formatear la fecha de publicación
    const formatearFecha = (fechaPublicacion) => {
    const { minutos, horas, dias } = calcularDiferencia(fechaPublicacion);
    if (minutos <= 60) {
        return `${minutos} m`;
    } else if (horas < 24) {
        return `${horas} h`;
    } else {
        return `${dias} d`;
    }
    };
    
    const maxLenghtTexto = (texto) => {
        const maxLength = 255;


        if ((typeof texto === 'undefined') || (texto.length != maxLength)) {
            return false; // Devuelve una cadena vacía si texto es undefined
        }
        if (texto.length >= maxLength) {
            return true;
        }
    }
</script>

<style setup>

.contenedorFormulario {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding-left:  310px;
    background-color: #fff; /* O el color que desees */
    z-index: 1; /* Asegura que el formulario esté sobre otros elementos */
}

.formularioMensaje {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Esto distribuye el espacio entre los elementos */
    width: 100%;
}

.btnEnviar {
    font-size: 20px;
    width: 10%;
    background-color: var(--tercero); /* Color de ejemplo para el botón */
    border: none;
    cursor: pointer;
}

.btnEnviar:hover{
    background-color: var(--tercero-contraste);
}
    
    .botonEnviar-contenedor button{
        border: none;
        padding: 24px;
    }

    .chat-topbar-user {
        display: flex;
        flex-direction: column;
        justify-content: start;
        margin-left: 10px;
    }

    .fecha {
        min-width: 25px;
        display: flex;
        justify-content: end;
    }
    .usuarioActual {
        justify-content: left;
    }
    
    .otroUsuario {
        justify-content: right;
    }

    .textarea{
        resize: none;
        border: 0 none;
        outline: none;
        box-shadow: none;
        border-radius: 0;
        font-size: 20px;
        overflow: hidden;
        height: 70px;
        padding: 8px;
    }

    .container-msg {
        padding: 12px;
        margin-top: 5px;
        margin-bottom: 5px;
        min-width: 250px;
        word-break: break-all;
    }
    .div-form-msg {
        width: 100%;
        height: auto;
        background-color: #fff;
    }

    .textContent {
        font-size: 1.5rem
    }  

    .textUsername {
        font-size: 1.3rem
    }

    .enviar {
        width: 20%;
    }

    .chatContainer {
        background-image: url(/images/papel-comment.svg);
        background-size: cover;
        background-position: center;
        padding: 35px;
        width: 50%;
        margin: auto;
    }

@media (max-width: 1200px){

    .contenedorFormulario{
        margin-bottom: 79px;
        padding: 0;
    }

    .contenedor-sendmsg{
        width: 100%;
    }

    .botonEnviar-contenedor {
        display: flex;
        margin-top: 20px;
        margin-bottom: 20px;
    }
}

</style>