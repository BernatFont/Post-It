<template>
    <div class="topbar-container bg-v2 ">
        <div class="d-flex justify-content-between topbar-title">
            <span class="pt-2 itty col-8 pl-5 title-target">{{$t('notifications')}}</span>
            <div class="target-value">
                <span class="itty">2</span>
            </div>
            
        </div>
    </div>
    <div class="mainPrincipal">
        <div>
            <!-- Notificacion Like-->
            <div class="userContainer d-flex flex-column">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/like.svg">
                            <span class="ml-2 mb-1 font1 itty">@username le gusta tu publicación</span>
                        </div>
                        <button class="btn btn-href pr-0 itty font1">Marcar como Leido</button>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <img class="img-perfil" src="/images/user-default.png" alt="">
                            <div class="d-flex flex-column">
                                <span class="pl-1 itty font1">Nombre Apellido</span>
                                <span class="pl-1 itty font2">@username</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1">Hace 1 hora</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row">  
                    <div class="notification-post">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <img class="img-perfil" src="/images/user-default.png" alt="">
                                <div class="d-flex flex-column">
                                    <span class="pl-1 itty font1">Nombre Apellido</span>
                                    <span class="pl-1 itty font2">@username</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="itty font1 fecha">DD/MM/YYYY</span>
                            </div>
                        </div>
                        <span class="pt-1 pl-1 itty font1">Contenido del post</span>
                    </div> 
                </div>
            </div>

            <!--Notificacion Mensaje-->
            <div class="userContainer d-flex flex-column">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/comentarios.svg">
                            <span class="ml-2 mb-1 font1 itty">@username ha comentado en tu publicación</span>
                        </div>
                        <button class="btn btn-href pr-0 itty font1">Marcar como Leido</button>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <img class="img-perfil" src="/images/user-default.png" alt="">
                            <div class="d-flex flex-column">
                                <span class="pl-1 itty font1">Nombre Apellido</span>
                                <span class="pl-1 itty font2">@username</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1">Hace 2 horas</span>
                        </div>
                    </div>
                    <span class="pt-1 pl-1 itty font1">Contenido del comentario</span>
                </div>
                <div class="d-flex flex-row">  
                    <div class="notification-post">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row">
                                <img class="img-perfil" src="/images/user-default.png" alt="">
                                <div class="d-flex flex-column">
                                    <span class="pl-2 itty font1">Nombre Apellido</span>
                                    <span class="pl-2 itty font2">@username</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="itty font1 fecha">DD/MM/YYYY</span>
                            </div>
                        </div>
                        <span class="pt-1 pl-2 itty font1">Contenido del post</span>
                    </div> 
                </div>
            </div>

            <!--Notificacion nuevo seguidor-->
            <div class="userContainer d-flex flex-column">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/perfil.svg">
                            <span class="ml-2 mb-1 font1 itty">@username te ha seguido</span>
                        </div>
                        <span class="itty font1">Visto</span>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <img class="img-perfil" src="/images/user-default.png" alt="">
                            <div class="d-flex flex-column">
                                <span class="pl-2 itty font1">Nombre Apellido</span>
                                <span class="pl-2 itty font2">@username</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="itty font1 fecha">Hace 5 dias</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<style>
    .type-notification {
        margin-bottom: 5px;
    }  
    .notification-icon {
        width: 15px;
        height: auto;
    }
    .notification-post {
        border: 1px solid #414141;
        background-color: #50e8cc;
        width: 100%; 
        display: flex;
        flex-direction: column;
        padding: 10px;
        margin-bottom: 10px;
    }

</style>