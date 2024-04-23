<template>
    <div class="mainPrincipal">
        <div class="top-chat bg-v2 ">
            <div class="d-flex justify-content-between searchContainer">
                <span class="pt-2 itty col-8 pl-5 title-target">{{$t('notifications')}}</span>
                <div class="notification-alert">
                    <span class="itty">2</span>
                </div>
                
            </div>
        </div>
        <div>
            <!-- Notificacion Like-->
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
                            <img class="notification-imgPerfil" src="/images/user-default.png" alt="">
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
                                <img class="notification-imgPerfil" src="/images/user-default.png" alt="">
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
                            <img class="notification-imgPerfil" src="/images/user-default.png" alt="">
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
                                <img class="notification-imgPerfil" src="/images/user-default.png" alt="">
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

            <!--Notificacion nuevo seguidor-->
            <div class="userContainer d-flex flex-column">
                <div class="type-notification">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row">
                            <img class="notification-icon" src="/images/comentarios.svg">
                            <span class="ml-2 mb-1 font1 itty">@username te ha seguido</span>
                        </div>
                        <span class="itty font1">Visto</span>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-row">
                            <img class="notification-imgPerfil" src="/images/user-default.png" alt="">
                            <div class="d-flex flex-column">
                                <span class="pl-1 itty font1">Nombre Apellido</span>
                                <span class="pl-1 itty font2">@username</span>
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

    .notification-imgPerfil{
        width: 50px;
        height: 50px;
    }

    .notification-alert {
        width: 55px;
        height: 55px;
        filter: drop-shadow(0px 0px 2px rgba(0, 0, 0, 0.5));
        background-image: url(/images/papel-pequeño-cuadrado.svg);
        background-size: cover;
        background-repeat: no-repeat;
        display: flex;
        justify-content: center;
        align-items:center;
        font-size: 2rem;
    }
    .font1 {
        font-size: 1.3rem;
    }

    .font2 {
        font-size: 1.1rem;
    }

    @media (max-width: 600px){
        .font1 {
        font-size: 1.1rem;
    }

    .font2 {
        font-size: 0.9rem;
    }
    }

    .notification-imgPerfil{
        width: 44px;
        height: 44px;
        border-radius: 50px;
    }
</style>