<template>
    <div class="menu_sidebar_responsive d-flex align-items-center">
        <div class="w-100">
            <ul class="d-flex justify-content-around ul_responsive">
                <router-link :to="{ name: 'feed'}">
                    <li class="respo-1"><div class="logo_respo logo-sidebar-1"></div></li>
                </router-link>
                <router-link :to="{ name: 'feed'}">
                    <li class="respo-2"><div class="logo_respo logo-sidebar-2"></div></li>
                </router-link>
                <router-link :to="{ name: 'notificaciones'}">
                    <li class="respo-3"><div class="logo_respo logo-sidebar-3"></div></li>
                </router-link>
                <router-link :to="{ name: 'chats'}">
                    <li class="respo-2"><div class="logo_respo logo-sidebar-4"></div></li>
                </router-link>
                <router-link :to="{ name: 'usuario.mostrar', params: { username: userLogin.username } }">
                    <li class="respo-1"><div class="logo_respo logo-sidebar-5"></div></li>
                </router-link>
                <router-link :to="{ name: 'feed'}">
                    <li class="respo-3"><div class="logo_respo logo-sidebar-6"></div></li>
                </router-link>

                <li class="respo-2" @click="Logout"><div class="logo_respo logo-sidebar-7"></div></li>
            </ul>
        </div>
    </div>
</template>

<style>
    .menu_sidebar_responsive{
        position: fixed;
        top: 90%;
        z-index: 9999;
        width: 100%;
        height: 10%;
        background: url(images/fondo_sidebar_responsive.svg);
        background-repeat: no-repeat;
        background-size: cover;
        border-top: solid 2px #AC5708;
        box-shadow: 0px -8px 10px rgba(0, 0, 0, 0.5);
    }

    .ul_responsive{
        margin: 0;
        padding: 0;
    }

    .ul_responsive li{
        padding: 14px 12px 12px 10px;
        margin: 0;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
    }

.ul_responsive li:hover{
}

.respo-1{
    background-image: url(images/papel-responsive1.svg);
}
.respo-2{
    background-image: url(images/papel-responsive2.svg);
}
.respo-3{
    background-image: url(images/papel-responsive3.svg);
}

.logo_respo{
    height: 30px;
    width: 30px;
    background-repeat: no-repeat;
    background-position: center;
}

.logo-sidebar-1{
    background-image: url(images/casa.svg);    
}
li:hover .logo-sidebar-1{
    background-image: url(images/casa_hover.svg);    
}

.logo-sidebar-2{
    background-image: url(images/lupa.svg);    
}
li:hover .logo-sidebar-2{
    background-image: url(images/lupa_hover.svg);    
}

.logo-sidebar-3{
    background-image: url(images/campana.svg);    
}
li:hover .logo-sidebar-3{
    background-image: url(images/campana_hover.svg);    
}

.logo-sidebar-4{
    background-image: url(images/mensajes.svg);    
}
li:hover .logo-sidebar-4{
    background-image: url(images/mensajes_hover.svg);    
}

.logo-sidebar-5{
    background-image: url(images/perfil.svg);    
}
li:hover .logo-sidebar-5{
    background-image: url(images/perfil_hover.svg);    
}

.logo-sidebar-6{
    background-image: url(images/configuracion.svg);    
}
li:hover .logo-sidebar-6{
    background-image: url(images/configuracion_hover.svg);    
}

.logo-sidebar-7{
    background-image: url(images/cerrarSesion.svg);    
}
li:hover .logo-sidebar-7{
    background-image: url(images/cerrarSesion_hover.svg);    
}

li span{
    font-size: 24px;
}

</style>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from "vue-router";
import { useStore } from 'vuex'; // siempre hay que usarlo dentro de un script 'setup', sino no va
import useAuth from "@/composables/auth";

const store = useStore() // gancho que permite acceder al store de Vuex dentro de los componentes de Vue sin necesidad de importarlo manualmente en cada componente.
const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const { logout } = useAuth();
const router = useRouter();


function Logout() {
  // Realizar acciones adicionales antes del cierre de sesión si es necesario
  logout();
  // Redireccionar al usuario a la página de inicio o donde prefieras
  router.push('/');
}
</script>