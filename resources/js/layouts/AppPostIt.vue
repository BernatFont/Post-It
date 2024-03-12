<template>
    <div class="general container-fluid">
        <div class="row">
            <div class="menu-sidebar col-md-2 d-none d-md-block">
                <AppSidebar/>
            </div>
            <div class="col-10 col-md-12 content p-5">
                <router-view></router-view>
            </div>
        </div>

    </div>
</template>

<script setup>
    import AppSidebar from './AppSidebar.vue';

</script>

<style scoped>
 .general{
    height: 100%;
    background-color: #bdbdbd;
 }

 
</style>
