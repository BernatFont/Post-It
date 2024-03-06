<template>
    <div class="general">
        <div class="layout-sidebar">
            <AppSidebar/>
        </div>
        <div class="content p-5">
            <router-view></router-view>
        </div>

    </div>
</template>

<script setup>
    import AppSidebar from './AppSidebar.vue';

</script>

<style scoped>
 .general{
    width: 100vw;
    height: 100%;
    background-color: #bdbdbd;
 }
 .content{
    margin-left: 25vw;
    width: 75vw;
 }
 
</style>
