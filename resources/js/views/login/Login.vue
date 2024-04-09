<template>
    <div class="containerLogin">
        <div class="row no-margin-row justify-content-center pt-5">
            <div class="col-md-6">
                <div class="title-logo card">
                    <img class="imgLogo" src="/images/logo_principal.svg" alt="">
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body itty">
                        <form @submit.prevent="submitLogin">
                            <div class="">
                                <div class="mb-3">
                                    <router-link :to="{name: 'inicio'}">< {{ $t('back')}}</router-link>
                                </div>
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ $t('email') }}</label>
                                    <input v-model="loginForm.email" id="email" type="email" class="form-control" required autofocus autocomplete="username">
                                    <!-- Validation Errors -->
                                    <div class="text-danger mt-1">
                                        <div v-for="message in validationErrors?.email">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="form-label">
                                        {{ $t('password') }}
                                    </label>
                                    <input v-model="loginForm.password" id="password" type="password" class="form-control" required autocomplete="current-password">
                                    <!-- Validation Errors -->
                                    <div class="text-danger-600 mt-1">
                                        <div v-for="message in validationErrors?.password">
                                            {{ message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between itty">
                                   <!-- Remember me -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" v-model="loginForm.remember" id="flexCheckIndeterminate">
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            {{ $t('remember_me') }}
                                        </label>
                                    </div>
                                    <router-link :to="{name: 'auth.forgot-password'}">{{ $t('forgot_password')}}</router-link>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="mt-4">
                                    <button class="btn btn-postit mb-3" :class="{ 'opacity-25': processing }" :disabled="processing">
                                        {{ $t('login') }}
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
.imgLogo {
    max-width: 300px;
    margin: auto;
}

.card {
    border-radius: 0px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
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
</style>