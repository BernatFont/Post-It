<template>
    <div class="containerLogin">
        <div class="row no-margin-row justify-content-center pt-5">
            <div class="col-sm-9 col-md-8 col-lg-7 col-xl-6 col-xxl-5">
                <div class="title-logo card inclinacion1">
                    <div></div>
                    <img class="imgLogo" src="/images/logo_principal.svg" alt="">
                </div>
                <div class="card border-0 shadow-sm inclinacion2">
                    <div class="card-body itty">
                        <form @submit.prevent="submitLogin">
                            <div class="containerForm">
                                <div class="btnContainer-volver">
                                    <router-link class="btn-href" :to="{name: 'inicio'}">< {{ $t('back')}}</router-link>
                                </div>
                                <!-- Email -->
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="email" class="label-width">{{ $t('email') }}</label>
                                    <input v-model="loginForm.email" id="email" type="email" class="input-width" required autofocus autocomplete="username">
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="mb-3 text-type-1 div-input-width">
                                    <label for="password" class="label-width">
                                        {{ $t('password') }}
                                    </label>
                                    <input v-model="loginForm.password" id="password" type="password" class="input-width" required autocomplete="current-password">
                                    <!-- Validation Errors -->
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between itty btnContainer-bottom">
                                   <!-- Remember me -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" v-model="loginForm.remember" id="flexCheckIndeterminate">
                                        <label class="form-check-label btn-href" for="flexCheckIndeterminate">
                                            {{ $t('remember_me') }}
                                        </label>
                                    </div>
                                    <router-link class="btn-href" :to="{name: 'auth.forgot-password'}">{{ $t('forgot_password')}}</router-link>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="mt-3 text-type-2">
                                    <button class="sticky-btn" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        <span class="font-type-1">{{ $t('login') }}</span>
                                    </button>
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

const { loginForm, validationErrors, processing, submitLogin } = useAuth();

</script>

<style>

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
.inclinacion1 {
    transform: rotate(1deg);
}

.inclinacion2 {
    transform: rotate(-2deg);
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