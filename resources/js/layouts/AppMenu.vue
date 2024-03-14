
<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div class="top-menu">
            <div class="logo d-flex p-2">
                <!-- <img src="/images/post-it.png" alt="imagen del logo"> -->
                <h1 class="titulo-sidebar p-4">POST-IT</h1>
            </div>
        </div>
        <div class="menu-container">
            <Menu :model="model">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" :to="item.route" custom></router-link>
                    <!--<a class="" v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">-->
                    <a class="item-container" v-else v-ripple :href="item.url" :style="{ 'border-left': $route.path === item.route ? '5px solid #000' : 'none' }" >
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
    .logo img{
        width: 75px;
        height: auto;
    }
    .titulo-sidebar {
        font-weight: 700;
    }
    .menu{
        position: fixed;
        width: 20vw;
        height: 100vh;
        /* height: calc(100vh - 9rem); */
        z-index: 999;
        /* overflow-y: auto; */
        user-select: none;
        /* left: 0rem; */
        /* top: 0rem; */
        /* transition: transform $transitionDuration, left $transitionDuration; */
        background-color: #fff;
        /* border-radius: $borderRadius; */
        /* padding: 0.5rem 1.5rem; */
        box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.22), 0px 0px 2px rgba(0, 0, 0, 0.05), 0px 1px 4px rgba(0, 0, 0, 0.08);
    }
    .menu-container{
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-top: 20px;
        margin-bottom: 40px;
        
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