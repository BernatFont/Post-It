<template>
    <div class="menu d-flex flex-column justify-content-between">
        <div>
            <div class="top-sidebar d-flex justify-content-center align-items-center">
                <h1><div class="bg-logo"><img src="/images/logo_principal.svg" alt="logo de la red social"></div></h1>
            </div>
            <div class="mid-sidebar mt-3">
                <ul>
                    <router-link :to="{ name: 'feed'}">
                        <li class="side-them-1"><div class="logo logo-sidebar-1"></div><span class="itty">{{ $t('home_name') }}</span></li>
                    </router-link>
                    <router-link :to="{ name: 'buscar'}">
                        <li class="side-them-2"><div class="logo logo-sidebar-2"></div><span class="itty">{{$t('search')}}</span></li>
                    </router-link>
                    <router-link :to="{ name: 'notificaciones'}">
                        <li class="side-them-3"><div class="logo logo-sidebar-3"></div><span class="itty" v-if="notificaciones.numNotificaciones > 0">{{$t('notifications')}} ({{ notificaciones.numNotificaciones }})</span><span class="itty" v-else>{{$t('notifications')}}</span></li>
                    </router-link>
                    <router-link :to="{ name: 'chats'}">
                        <li class="side-them-2"><div class="logo logo-sidebar-4"></div><span class="itty">{{$t('messages')}}</span></li>
                    </router-link>
                    <router-link :to="{ name: 'usuario.mostrar', params: { username: userLogin.username } }">
                        <li class="side-them-1"><div class="logo logo-sidebar-5"></div><span class="itty">{{$t('user')}}</span></li>
                    </router-link>
                </ul>
            </div>
        </div>
        <div class="bot-sidebar">
            <ul>
                <li id="configuracion" class="side-them-3"><div class="logo logo-sidebar-6"></div><span class="itty">{{$t('settings')}}</span></li>

                <li class="side-them-2" @click="Logout"><div class="logo logo-sidebar-7"></div><span class="itty">{{$t('logout')}}</span></li>
            </ul>
        </div>
    </div>

</template>

<style>
.menu{
    position: fixed;
    width: 300px;
    height: 100vh;
    z-index: 999;
    user-select: none;
    background-image: url(/images/bg-menu.svg);
    background-repeat: no-repeat;
    background-size: cover;
    border-right: solid 2px #E1BC83;
    box-shadow: 8px 0px 10px rgba(0, 0, 0, 0.2);
}

.top-sidebar{
    height: 20%;
    min-height: 150px;
    background-image: url(/images/bg-logo.svg);
    background-repeat: no-repeat;
    background-size: cover;
}

.bg-logo img{
    height: 100px;
}

li{
    margin: 8px 0;
    padding: 12px 0px 4px 16px;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
}

li:hover{
    filter: drop-shadow(4px 4px 4px rgba(0, 0, 0, 0.5));
}

.side-them-1{
    background-image: url(/images/papel1.svg);
}
.side-them-2{
    background-image: url(/images/papel2.svg);
}
.side-them-3{
    background-image: url(/images/papel3.svg);
}

.logo{
    height: 24px;
    width: 24px;
    margin-right: 8px;
    background-repeat: no-repeat;
    background-position: center;
}

li span{
    font-size: 24px;
}
</style>

<script setup>
import { ref, onMounted, inject, computed } from 'vue';
import { useRouter } from "vue-router";
import { useStore } from 'vuex';
import useAuth from "@/composables/auth";
import { useI18n } from 'vue-i18n';
import { loadMessages } from '@/plugins/i18n'

const store = useStore();
const userLogin = computed(() => store.state.auth.user);
const { logout } = useAuth();
const router = useRouter();
const swal = inject('$swal');
const i18n = useI18n(); // Asegúrate de inicializar i18n correctamente
const locale = computed(() => store.getters["lang/locale"]);
const locales = computed(() => store.getters["lang/locales"]);
const notificaciones = ref({});

function setLocale(locale) {
    if (i18n.locale.value !== locale) {
        loadMessages(locale)
        store.dispatch('lang/setLocale', { locale })
    }
}

function Logout() {
    router.push({ name: 'welcome' });
    logout();
}

onMounted(() => {
    const config = document.getElementById('configuracion');
    if (config) {
        config.addEventListener('click', () => {
            swal({
                title: i18n.t('select_language'), // Usa i18n.t sin especificar alcance
                input: 'radio',
                inputOptions: {
                    'es': i18n.t('spanish'), // Usa i18n.t sin especificar alcance
                    'en': i18n.t('english') // Usa i18n.t sin especificar alcance
                },
                inputValidator: (value) => {
                    if (!value) {
                        return i18n.t('error_select_language'); // Usa i18n.t sin especificar alcance
                    }
                },
                showCancelButton: true,
                cancelButtonText: i18n.t('cancel'), // Usa i18n.t sin especificar alcance
                confirmButtonText: i18n.t('ok'), // Usa i18n.t sin especificar alcance
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedLanguage = result.value;
                    console.log('Seleccionaste ' + selectedLanguage);
                    setLocale(selectedLanguage);
                }
            });
        })
    }
    obtenerNotificaciones();
})

const obtenerNotificaciones = () => {
    axios.get('/api/notificaciones/contador/')
        .then(response => {
            notificaciones.value = response.data;
        })
        .catch(error => {
            console.error('Error al obtener las notificaciones:', error);
        })
};
</script>

