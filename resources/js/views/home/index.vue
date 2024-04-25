<template>
    <div class="mainPrincipal landing-container ">
        <div class="contenedor">
            <div class="imagen-container">
                <img class="imagen" src="/images/logo_principal.svg" alt="Post-It logo">
            </div>
            <div class="d-flex flex-column">
                <div class="descripcion">
                    <span class="font-descripcion itty">{{ $t('welcome_starter_description') }}</span>
                </div>
                <div class="boton-contenedor d-flex justify-content-around"> 
                    <div class="container-boton inclinacion1">
                        <div class="sticky-btn-sticker bg-3c"></div>
                        <router-link class="sticky-btn-1 itty bg-3 " to="/login">{{ $t('login') }}</router-link>
                    </div>
                    
                    <div class="container-boton inclinacion2">
                        <div class="sticky-btn-sticker bg-2c"></div>
                        <router-link class="sticky-btn-1 itty bg-2" to="/register">{{ $t('register') }}</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

.landing-container {
    background-image: url(/images/Corcho.jpg); 
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}

.contenedor {
    width: 75%;
    max-width: 1440px;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin: auto;
}

.boton-contenedor {
    margin-top: 30px;
}

.descripcion {
    background-image: url(/images/papel-comment.svg);
    background-size: cover;
    background-position: center;
    padding: 50px;
    
}

.imagen-container {
    width: 100%;
    height: auto;
    display: flex;
    justify-content: center;
    margin-right: 40px;

}

.imagen {
    width: 500px;
}

.font-descripcion {
    font-size: 1.5rem;
}

.inclinacion1 {
    transform: rotate(1deg);
}

.inclinacion2 {
    transform: rotate(-2deg);
}
@media (max-width: 1200px){
    .imagen-container {
        margin-bottom: 40px;
        margin-right: 0px;
    }

    .imagen {
    max-width: 400px;
    }

    .contenedor {
    width: 80%;
    flex-direction: column;
    }

}

@media (max-width: 500px){
    .contenedor {
    width: 100%;
    }

    .imagen {
    width: 300px;
    }

    .font-descripcion {
    font-size: 1.3rem;
    }
}
</style>