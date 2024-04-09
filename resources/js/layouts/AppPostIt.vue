<template>
    <div class="general">
        <div class="menu_sidebar">
            <AppSidebar/>
        </div>
        <div class="sidebar_responsive">
            <AppSidebarResponsive/>
        </div>
        <div class="contenido-view ">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
    // import AppSidebar from './AppSidebar.vue';
    import AppSidebar from './AppSidebarPostit.vue';
    
    import AppSidebarResponsive from './AppSidebarPostitResponsive.vue';

</script>

<style scoped>
 .general{
    height: 100%;
    background-image: url(/public/images/Corcho.jpg); 
    /* background-size: cover;  */
    /* background-repeat: no-repeat; */
    /* background-attachment: fixed; */
 }

 .contenido-view{
    padding-left: 20vw;
 }

.sidebar_responsive{
    display: none;
}

 @media (max-width: 1200px){
    .menu_sidebar{
        display: none;
    }

    .sidebar_responsive{
        display: block;
    }
    
    .contenido-view{
        padding: 12px;
    }    
}


 
</style>
