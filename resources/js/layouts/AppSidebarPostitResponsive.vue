<template>
    <div class="menu_sidebar_responsive d-flex align-items-center">
        <div class="w-100">
            <ul class="d-flex justify-content-around ul_responsive">
                <router-link :to="{ name: 'feed'}">
                    <li class="respo-1"><div class="logo_respo logo-sidebar-1"></div></li>
                </router-link>
                <router-link :to="{ name: 'buscar'}">
                    <li class="respo-2"><div class="logo_respo logo-sidebar-2"></div></li>
                </router-link>
                <router-link :to="{ name: 'notificaciones'}">
                    <li class="respo-3"><div class="logo_respo logo-sidebar-3"></div></li>
                </router-link>
                <router-link :to="{ name: 'chats'}">
                    <li class="respo-2"><div class="logo_respo logo-sidebar-4"></div></li>
                </router-link>
                <router-link :to="{ name: 'usuario.mostrar', params: { username: userLogin.username } }">
                    <li class="respo-1"><div class="logo_respo logo-sidebar-5"></div></li>
                </router-link>

                <li id="configuracion_resp" class="respo-3"><div class="logo_respo logo-sidebar-6"></div></li>

                <li class="respo-2" @click="Logout"><div class="logo_respo logo-sidebar-7"></div></li>

            </ul>
        </div>
    </div>
</template>

<style>
.menu_sidebar_responsive{
    position: fixed;
    top: 90%;
    z-index: 9999;
    width: 100%;
    height: 10%;
    background: url(/images/bg-menu-responsive.svg);
    background-repeat: no-repeat;
    background-size: cover;
    border-top: solid 2px #E1BC83;
    box-shadow: 0px -8px 10px rgba(0, 0, 0, 0.5);
}

.ul_responsive{
    margin: 0;
    padding: 0;
}

.ul_responsive li{
    padding: 14px 12px 12px 10px;
    margin: 0;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
}

.respo-1{
    background-image: url(/images/papel-responsive1.svg);
}
.respo-2{
    background-image: url(/images/papel-responsive2.svg);
}
.respo-3{
    background-image: url(/images/papel-responsive3.svg);
}

.logo_respo{
    height: 30px;
    width: 30px;
    background-repeat: no-repeat;
    background-position: center;
}

li span{
    font-size: 24px;
}

</style>

<script setup>
import { onMounted, inject, ref, computed } from 'vue';
import { useRouter } from "vue-router";
import { useStore } from 'vuex'; // siempre hay que usarlo dentro de un script 'setup', sino no va
import useAuth from "@/composables/auth";
// Multidioma
import { useI18n } from 'vue-i18n'
import { loadMessages } from '@/plugins/i18n'

const store = useStore() // gancho que permite acceder al store de Vuex dentro de los componentes de Vue sin necesidad de importarlo manualmente en cada componente.
const userLogin = computed(() => store.state.auth.user); // Usuario que tiene iniciado sesion
const { logout } = useAuth();
const router = useRouter();
const swal = inject('$swal');
// Multidioma
const i18n = useI18n({useScope: "global"});
const locale = computed(() => store.getters["lang/locale"])
const locales = computed(() => store.getters["lang/locales"])

function setLocale(locale) {
    if (i18n.locale !== locale) {
        loadMessages(locale)
        store.dispatch('lang/setLocale', { locale })
    }
}

function Logout() {
  // Realizar acciones adicionales antes del cierre de sesión si es necesario
  logout();
  // Redireccionar al usuario a la página de inicio o donde prefieras
  router.push('/');
}

onMounted(() => {
    const config = document.getElementById('configuracion_resp');
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
})

</script>