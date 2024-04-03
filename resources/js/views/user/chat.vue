<template>
    <div>
        <h2>Chat privado</h2>
        <div v-if="mensajes.length === 0">
            <p>No se ha enviado ningun mensaje</p>
        </div>
        <div v-else>
            <div v-for="mensaje in mensajes" :key="mensaje.id" :class="{'usuarioActual': mensaje.user.id === userLogin.id, 'otroUsuario': mensaje.user.id !== userLogin.id}">

                <div>
                    <span>{{ mensaje.user.name }}: </span>
                    <span>{{ mensaje.contenido }}</span>
                    <br>
                </div>
            </div>
        </div>
        <div>
            <!-- Formulario para escribir un mensaje -->
            <form @submit.prevent="añadirMensaje">
                <textarea v-model="nuevoMensaje" placeholder="Escribe tu mensaje"></textarea>
                <button type="submit">Enviar</button>
            </form>
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

    onMounted(() => {
        cargarMensajes();
    });

    // Devuelve un array con todos los mensajes del chat
    const cargarMensajes = () => {
        axios.get(`/api/chat/mensajes/${chatId}`)
            .then(response => {
                mensajes.value = response.data;
                console.log(mensajes.value)
            })
            .catch(error => {
                console.error('Error al cargar los mensajes del chat:', error);
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
        text-align: left;
    }
    
    .otroUsuario {
        text-align: right;
    }
</style>
