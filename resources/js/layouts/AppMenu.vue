<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div class="logo mt-2 d-flex justify-content-center align-items-center">
            <h1 class="titulo-sidebar pt-5">POST-IT</h1>
        </div>
        <div class="menu-container">
          <Menu :model="model">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" :to="item.route" custom></router-link>
                    <a @mouseover="item.currentIcon = item.hoverIcon" @mouseout="item.currentIcon = item.icon" class="m-auto item-container ps-3" v-else v-ripple :href="item.url" :style="{ 'border-left': $route.path === item.route ? '5px solid #000' : 'none' }" >
                        <img v-if="item.icon" class="icon me-3" :src="item.currentIcon" alt="icono" />
                        <span>{{ $t(item.label) }}</span>
                    </a>
                </template>
            </Menu>
            <Menu :model="model2">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" :to="item.route" custom></router-link>
                    <a @mouseover="item.currentIcon = item.hoverIcon" @mouseleave="item.currentIcon = item.icon" class="item-container ps-3" v-else v-ripple :href="item.url" >
                        <img v-if="item.icon" class="icon me-3" :src="item.currentIcon" alt="icono" />
                        <span>{{ $t(item.label) }}</span>
                    </a>
                </template>
            </Menu>
        </div>
    </div>
    
</template>

<script setup>
import { ref } from 'vue';
import { useAbility } from '@casl/vue';
import useAuth from "@/composables/auth";
import { useRouter } from "vue-router";

/* Importamos SVG */
import casa from '/public/images/casa.svg'; 
import campana from '/public/images/campana.svg';
import mensajes from '/public/images/mensajes.svg';
import configuracion from '/public/images/configuracion.svg';
import cerrarSesion from '/public/images/cerrarSesion.svg';
/* Importamos SVG hover */
import casa_hover from '/public/images/casa_hover.svg'; 
import campana_hover from '/public/images/campana_hover.svg';
import mensajes_hover from '/public/images/mensajes_hover.svg';
import configuracion_hover from '/public/images/configuracion_hover.svg';
import cerrarSesion_hover from '/public/images/cerrarSesion_hover.svg';

const { processing, logout } = useAuth();
const { can } = useAbility();
const router = useRouter();

/*Funcion*/
console.log(casa)

const model = ref([
  {
    items: [
      { label: 'home_name', icon: casa, hoverIcon: casa_hover, url: '/inicio', permision: 'all'},
      { label: 'search', icon: 'pi pi-fw pi-search', url: '/buscar', permision: 'user-list' },
      { label: 'notifications', icon: campana, hoverIcon: campana_hover, url: '/notificaciones', permision:'role-list' },
      { label: 'messages', icon: mensajes, hoverIcon: mensajes_hover, url: '/mensajes', permision:'permission-list' },
      { label: 'user', icon: 'pi pi-fw pi-user', url: '/usuario', permision:'permission-list' }
    ]
  },
]);

const model2 = ref([
  {
    items: [
      { label: 'settings', icon: configuracion, hoverIcon: configuracion_hover, url: '/configuracion', permision: 'exercise-list' },
      { label: 'logout', icon: cerrarSesion, hoverIcon: cerrarSesion_hover, url: '#', permision: 'category-list', command: () => Logout() } // Llama a la funcion logout
    ]
  },
]);

function Logout() {
  // Realizar acciones adicionales antes del cierre de sesión si es necesario
  logout();
  // Redireccionar al usuario a la página de inicio o donde prefieras
  router.push('/');
}

model.value[0].items.forEach(item => {
  item.currentIcon = item.icon; // Inicializar currentIcon con el icono normal
});

model2.value[0].items.forEach(item => {
  item.currentIcon = item.icon; // Inicializar currentIcon con el icono normal
});

</script>

<style>
    a{
      margin: 0;
    }
    .logo {
        height: 20%;
        background-image: url(/public/images/papel_logo.svg);
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .titulo-sidebar {
        font-weight: 700;
    }
    .menu{
        position: fixed;
        width: 20vw;
        height: 100vh;
        z-index: 999;
        user-select: none;
        background-image: url(/public/images/fondo_sidebar.jpg);
        border-right: solid 2px #AC5708;
        box-shadow: 8px 0px 10px rgba(0, 0, 0, 0.5);
    }
    .menu-container{
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-top: 10px;
        margin-bottom: 20px;
        
    }
    .menu ul{
        list-style-type: none;
    }

    .icon{
        height: 24px;
    }
    .item-container {
        color: black;
        font-weight: 600;
        font-size: 20px;
        line-height: 50px;
    }

    .item-container span, h1{
      font-family: 'Itty',sans-serif;
    }

    h1{
      font-size: 50px;
    }

    .item-container span{
      font-size: 24px;
    }
    
</style>