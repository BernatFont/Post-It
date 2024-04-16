<template>
    <div class="mainPrincipal ">
        <div class="">
            <div class="">
                <div class="d-flex flex-row align-items-center contenedor">
                    <div class="imagen-container">
                        <img class="imagen" src="/images/logo_principal.svg" alt="Post-It logo">
                        <!--<h1 class="titulo itty"><b>{{ $t('welcome_starter_title') }}</b></h1> -->
                    </div>
                    <div class="card">
                        <h3 class="descripcion itty">{{ $t('welcome_starter_description') }}</h3>
                        <div class="d-flex flex-direction-column justify-content-around ">   
                            <router-link class="sticky-btn itty" to="/login">{{ $t('login') }}</router-link>
                            <router-link class="sticky-btn itty" to="/register">{{ $t('register') }}</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.mainPrincipal {
    background-image: url(/images/Corcho.jpg); 
    display: flex;
    justify-content: center;
    align-items: center;
}

.contenedor {
    width: 75%;
    margin: auto;
}
.descripcion {
    background-image: url();
    background-repeat: no-repeat;
    background-position: center;
}

.card {
    border-radius: 0px;
}

.imagen-container {
    width: 100%;
}

@media (max-width: 500px){

}
</style>