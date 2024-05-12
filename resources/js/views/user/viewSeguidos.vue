<template>
    <div v-if="usuario" class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <div class="itty col-8">
                <router-link class="pt-2 title-target" :to="{ name: 'usuario.mostrar', params: { username: userLogin.username } }">< </router-link>
                <span class="pt-2 title-target">{{$t('seguidos')}}</span>
            </div> 
            <div class="notification-alert target-value">
                <span class="itty">{{ usuario.seguidos_count }}</span>
            </div>
        </div>
    </div>
    <div class="mainPrincipal" v-if="usuario">
        <div class="userContainer d-flex flex-row align-items-center justify-content-between" v-for="usuario in usuario.seguidos" :key="usuario.id">
            <div class="d-flex flex-row">
                <router-link :to="{ name: 'usuario.mostrar', params: { username: usuario.username } }">
                    <div class="contenedor-img-perfil">
                        <img class="img-perfil" :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="imagen del perfil del usuario">
                    </div>
                </router-link>
                <div class="d-flex flex-column">
                    <span class="itty font1">{{usuario.name + (usuario.surname ? " " + usuario.surname : "")}}</span>
                    <span class="itty font2">@{{ usuario.username }}</span>
                </div> 
            </div>
            <div class="d-flex align-items-center">
                <span class="font2">{{ obtenerFecha(usuario.pivot.fecha_seguido) }}</span>
                <router-link class="btnIr itty" :to="{ name: 'usuario.mostrar', params: { username: usuario.username } }">{{ $t('show') }}</router-link>
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
    const username = route.params.username; // Obtiene el usuario por la ruta
    const usuario = ref(null);

    onMounted(() => {
        axios.get('/api/usuario/' + username)
            .then(response => {
                usuario.value = response.data;
                console.log(usuario.value);
            })
    })

// Obtiene la fecha en el formato almacenado y lo convierte a formato DD/MM/YYYY.
function obtenerFecha(fecha) {
    // Crear un objeto de fecha a partir de la cadena proporcionada
    const fechaObjeto = new Date(fecha);

    // Obtener los componentes de la fecha
    const dia = fechaObjeto.getDate().toString().padStart(2, '0');
    const mes = (fechaObjeto.getMonth() + 1).toString().padStart(2, '0'); // El mes es base 0, por lo que se agrega 1
    const año = fechaObjeto.getFullYear();

    // Devolver la fecha formateada en el formato deseado
    return `${dia}/${mes}/${año}`;
}

</script>



<style>
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
