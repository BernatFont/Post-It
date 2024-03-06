
<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div class="top-menu">
            <div class="logo d-flex justify-content-evenly p-2">
                <img src="/images/post-it.png" alt="imagen del logo">
                <h1>POST-IT</h1>
            </div>
            <ul class="bottom-top-menu">
                <template v-for="(item, i) in model" :key="item">
                    <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
                    <li v-if="item.separator" class="menu-separator"></li>
                </template>
            </ul>
        </div>
        <ul class="">
            <template v-for="(item, i) in model2" :key="item">
                <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
                <li v-if="item.separator" class="menu-separator"></li>
            </template>
        </ul>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import {useAbility} from '@casl/vue'

const {can} = useAbility();

const vela = "pepe";


const model = ref([
    {
        items: [
            { label:  '$t("home_name")', icon: 'pi pi-fw pi-home', to: '/feed', permision: 'all'},
            { label: 'Buscar', icon: 'pi pi-fw pi-id-card', to: '#', permision: 'user-list' },
            { label: 'Notificaciones', icon: 'pi pi-fw pi-check-square', to: '#', permision:'role-list' },
            { label: 'Mensajes', icon: 'pi pi-fw pi-bookmark', to: '#', permision:'permission-list' },
            { label: 'Perfil', icon: 'pi pi-fw pi-bookmark', to: '#', permision:'permission-list' }
        ]
    },
]);
const model2 = ref([
    {
        // label: 'Ejercicios',
        items: [
            { label: 'Configuración', icon: 'pi pi-fw pi-id-card', to: '/admin/exercises', permision: 'exercise-list' },
            { label: 'Cerrar sesión', icon: 'pi pi-fw pi-id-card', to: '/admin/categories', permision: 'category-list' }
        ]
    },
]);
</script>


<style lang="scss" scoped></style>
<style>
    .logo{
        background-color: #fde756;
    }
    .logo img{
        width: 75px;
        height: auto;
    }
    .menu{
        position: fixed;
        width: 25vw;
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
    .bottom-top-menu{
        margin-top: 20px;
    }
    .menu ul{
        list-style-type: none;
    }
    
</style>