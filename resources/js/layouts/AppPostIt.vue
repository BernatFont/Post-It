<template>
    <div class="general">
        <div class="menu-sidebar">
            <AppSidebar/>
        </div>
        <div class="contenido-view">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
    import AppSidebar from './AppSidebar.vue';

</script>

<style scoped>
 .general{
    height: 100%;
    background-color:#e0ffd0;
 }

 .contenido-view{
    padding-left: 20vw;

 }

 @media (max-width: 1200px){
    .menu-sidebar{
        display: none;
    }
    
    .contenido-view{
        padding: 12px;
    }    
}


 
</style>
