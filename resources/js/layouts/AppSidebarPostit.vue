<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div>
            <div class="top-sidebar d-flex justify-content-center align-items-center">
                <h1><div class="bg-logo"><img src="/images/logo_principal.svg" alt="logo de la red social"></div></h1>
            </div>
            <div class="mid-sidebar mt-3">
                <ul>
                    <router-link :to="{ name: 'feed'}">
                        <li class="side-them-1"><div class="logo logo-sidebar-1"></div><span class="itty">Inicio</span></li>
                    </router-link>
                    <router-link :to="{ name: 'buscar'}">
                        <li class="side-them-2"><div class="logo logo-sidebar-2"></div><span class="itty">Buscar</span></li>
                    </router-link>
                    <router-link :to="{ name: 'notificaciones'}">
                        <li class="side-them-3"><div class="logo logo-sidebar-3"></div><span class="itty">Notificaciones</span></li>
                    </router-link>
                    <router-link :to="{ name: 'chats'}">
                        <li class="side-them-2"><div class="logo logo-sidebar-4"></div><span class="itty">Mensajes</span></li>
                    </router-link>
                    <router-link :to="{ name: 'usuario.mostrar', params: { username: userLogin.username } }">
                        <li class="side-them-1"><div class="logo logo-sidebar-5"></div><span class="itty">Perfil</span></li>
                    </router-link>
                </ul>
            </div>
        </div>
        <div class="bot-sidebar">
            <ul>
                <li id="configuracion" class="side-them-3"><div class="logo logo-sidebar-6"></div><span class="itty">Configuración</span></li>

                <li class="side-them-2" @click="Logout"><div class="logo logo-sidebar-7"></div><span class="itty">Cerrar Sesión</span></li>
            </ul>
        </div>
    </div>

</template>

<style>
.menu{
    position: fixed;
    width: 20vw;
    height: 100vh;
    z-index: 999;
    user-select: none;
    background-image: url(/images/bg-menu.svg);
    background-repeat: no-repeat;
    background-size: cover;
    border-right: solid 2px #E1BC83;
    box-shadow: 8px 0px 10px rgba(0, 0, 0, 0.5);
}

.top-sidebar{
    height: 20%;
    min-height: 150px;
    background-image: url(/images/bg-logo.svg);
    background-repeat: no-repeat;
    background-size: cover;
}

.bg-logo img{
    height: 100px;
}

li{
    margin: 8px 0;
    padding: 12px 0px 4px 16px;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
}

li:hover{
    filter: drop-shadow(4px 4px 4px rgba(0, 0, 0, 0.5));
}

.side-them-1{
    background-image: url(/images/papel1.svg);
}
.side-them-2{
    background-image: url(/images/papel2.svg);
}
.side-them-3{
    background-image: url(/images/papel3.svg);
}

.logo{
    height: 24px;
    width: 24px;
    margin-right: 8px;
    background-repeat: no-repeat;
    background-position: center;
}

li span{
    font-size: 24px;
}
</style>

<script setup>
import { onMounted, inject, ref, computed } from 'vue';
import { useRouter } from "vue-router";
import { useStore } from 'vuex'; // siempre hay que usarlo dentro de un script 'setup', sino no va
import useAuth from "@/composables/auth";

const store = useStore() // gancho que permite acceder al store de Vuex dentro de los componentes de Vue sin necesidad de importarlo manualmente en cada componente.
const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const { logout } = useAuth();
const router = useRouter();
const swal = inject('$swal');

function Logout() {
    router.push({name: 'welcome'});
    logout();
}

onMounted(()=>{

    const config = document.getElementById('configuracion');
    if (config){
        config.addEventListener('click', () =>{
            swal({
                title: 'Selecciona tu idioma',
                        input: 'radio',
                        inputOptions: {
                            'espanol': 'Español',
                            'ingles': 'Inglés'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Debes seleccionar un idioma';
                            }
                        },
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false
            }).then((result) => {
                // Si se selecciona un idioma y se hace clic en "Aceptar"
                if (result.isConfirmed) {
                    const selectedLanguage = result.value;
                    // Aquí puedes agregar la lógica para cambiar al idioma seleccionado
                    console.log('Seleccionaste ' + selectedLanguage);
                }
            });
        })
    }
})
</script>