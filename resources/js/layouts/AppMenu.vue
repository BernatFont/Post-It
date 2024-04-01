<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div class="logo mt-2 d-flex justify-content-center align-items-center">
            <h1 class="titulo-sidebar">POST-IT</h1>
        </div>
        <div class="menu-container">
            <Menu :model="model">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" :to="item.route" custom></router-link>
                    <!--<a class="" v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">-->
                    <a class="item-container ps-3" v-else v-ripple :href="item.url" :style="{ 'border-left': $route.path === item.route ? '5px solid #000' : 'none' }" >
                        <span :class="item.icon" />
                        <span>{{ $t(item.label) }}</span>
                    </a>
                </template>
            </Menu>
            <Menu :model="model2">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" :to="item.route" custom></router-link>
                    <a class="item-container" v-else v-ripple :href="item.url" >
                        <span :class="item.icon" />
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

const { processing, logout } = useAuth();
const { can } = useAbility();


/*Funcion*/


const model = ref([
  {
    items: [
      { label: 'home_name', icon: 'pi pi-fw pi-home', url: '/inicio', permision: 'all'},
      { label: 'search', icon: 'pi pi-fw pi-search', url: '/buscar', permision: 'user-list' },
      { label: 'notifications', icon: 'pi pi-fw pi-bell', url: '/notificaciones', permision:'role-list' },
      { label: 'messages', icon: 'pi pi-fw pi-inbox', url: '/mensajes', permision:'permission-list' },
      { label: 'user', icon: 'pi pi-fw pi-user', url: '/usuario', permision:'permission-list' }
    ]
  },
]);

const model2 = ref([
  {
    items: [
      { label: 'settings', icon: 'pi pi-fw pi-cog', url: '/configuracion', permision: 'exercise-list' },
      { label: 'logout', icon: 'pi pi-fw pi-sign-out', url: '#', permision: 'category-list', command: () => Logout() } // Llama a la funcion logout
    ]
  },
]);

function Logout() {
  // Realizar acciones adicionales antes del cierre de sesión si es necesario
  logout();
  // Redireccionar al usuario a la página de inicio o donde prefieras
  router.push('/');
}
</script>

<style>
    .logo {
        height: 250px;
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
    .item-container {
        color: black;
        font-weight: 600;
        font-size: 20px;
        line-height: 50px; 
    }

    .item-container span:first-child{
        margin-right: 12px;
    }
    
</style>