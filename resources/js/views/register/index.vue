<template>
    <div class="containerLogin">
        <div class="row no-margin-row justify-content-center pt-5">
            <div class="col-sm-9 col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <div class="title-logo card inclinacion2">
                    <div></div>
                    <img class="imgLogo" src="/images/logo_principal.svg" alt="">
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body itty">
                        <form @submit.prevent="submitRegister">
                            <div class="containerForm">
                                <div class="btnContainer-volver">
                                    <router-link class="btn-href" :to="{name: 'inicio'}">< {{ $t('back')}}</router-link>
                                </div>
                                <!-- Email -->
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="name" class="label-width">{{ $t('name') }}</label>
                                    <input v-model="registerForm.name" id="name" type="text" class="input-width" autofocus>
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.name">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="email" class="label-width">{{ $t('email') }}</label>
                                    <input v-model="registerForm.email" id="email" type="email" class="input-width" autocomplete="username">
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="username" class="label-width">{{ $t('username') }}</label>
                                    <input v-model="registerForm.username" id="username" type="text" class="input-width">
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.username">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="password" class="label-width">
                                        {{ $t('password') }}
                                    </label>
                                    <input v-model="registerForm.password" id="password" type="password" class="input-width" autocomplete="current-password">
                                    <!-- Validation Errors -->
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="password_confirmation" class="label-width">
                                        {{ $t('confirm_password') }}
                                    </label>
                                    <input v-model="registerForm.password_confirmation" id="password_confirmation" type="password" class="input-width" autocomplete="current-password">
                                    <!-- Validation Errors -->
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password_confirmation">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
    
                                <!-- Buttons -->
                                <div class="container-boton">
                                    <div class="sticky-btn-sticker"></div>
                                    <button class="btnSticky sticky-btn-1 itty" :class="{ 'opacity-25': processing }" :disabled="processing">{{ $t('register') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

import useAuth from '@/composables/auth'

const { registerForm, validationErrors, processing, submitRegister } = useAuth();

</script>

<style>

    .container-boton {
        margin-top: 35px;
    }
    .btnContainer-volver {
        margin-bottom: 50px;
    }

    .btnContainer-bottom {
        margin-top: 35px;
    }
    .imgLogo {
        max-width: 300px;
        margin: auto;
    }

    .sticky-btn-1 {
        box-shadow: 12px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .sticky-btn-1:hover {
        box-shadow: 12px 2px 10px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 0px;
        box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #fff;
    }

    .card-body {
        padding: 0px;
    }

    .containerLogin {
        background-image: url(images/Corcho.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        height: 100vh;
    }
    .no-margin-row {
        margin: 0px;
    }

    .text-type-1 {
        font-size: 1.4rem;
    }

    .div-input-width {
        display: flex;
        flex-direction: row;
        width: 100%;
    }

    .label-width {
        width: 25%
    }

    .input-width {
        width: 75%;
    }

    .text-type-2 {
        font-size: 1.2rem;
    }

    @media (max-width: 500px){
    .inclinacion1 {
    transform: rotate(0deg);
    }

    .inclinacion2 {
        transform: rotate(0deg);
    }

    .div-input-width {
        flex-direction: column;
    }
    .label-width {
        width: 100%
    }

    .input-width {
        width: 100%
    }
}
</style>