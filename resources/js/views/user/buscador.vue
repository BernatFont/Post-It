<template>
  <div class="topbar-container bg-v2 ">
    <div class="d-flex justify-content-between searchContainer row topbar-title">
        <span class="pt-2 itty col-3 pl-5 title-target">{{$t('search')}}</span>
          <!-- Input para buscar -->
        <input class="inputSearch searchBar col-6" type="text" v-model="busqueda" placeholder="Buscar por nombre">
        <div class="col-3"></div>
    </div>
  </div>
<div class="mainPrincipal">
  <!-- Mostrar usuarios filtrados -->
  <div class="userContainer d-flex flex-row align-items-center justify-content-between" v-for="usuario in usuariosFiltrados" :key="usuario.id">
    <div class="d-flex flex-row">
      <img class="imgPerfil" src="/images/user-default.png" alt="">
      <div class="d-flex flex-column">
          <span class="itty textContent">{{ usuario.name + " " + (usuario.surname ? " " + usuario.surname : "") }}</span>
          <span class="itty textUsername">@{{ usuario.username }}</span>
      </div> 
    </div>
    <div>
        <router-link class="btnIr itty" :to="{ name: 'usuario.mostrar', params: { username: usuario.username } }">{{$t('show') + " " +  $t('user')}}</router-link>
    </div>
  </div>
</div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

export default {
  setup() {
    // Ref para almacenar el término de búsqueda
    const busqueda = ref('');

    // Ref para almacenar la lista de usuarios
    const usuarios = ref([]);

    // Función para obtener la lista de usuarios desde la API
    const obtenerUsuarios = () => {
      axios.get('/api/usuarios')
        .then(response => {
          usuarios.value = response.data;
        })
        .catch(error => {
          console.error('Error al obtener usuarios:', error);
        });
    }

    // Llamar a la función obtenerUsuarios cuando el componente se monte
    onMounted(() => {
      obtenerUsuarios();
    })

    // Función computada para filtrar los usuarios según el término de búsqueda
    const usuariosFiltrados = computed(() => {
      return usuarios.value.filter(usuario => {
        return usuario.name.toLowerCase().includes(busqueda.value.toLowerCase());
      });
    });

    // Devolver las referencias y la función computada para usar en el template
    return { busqueda, usuariosFiltrados };
  }
};
</script>

<style>

  .searchContainer {
    margin-left: 20px;
    margin-right: 20px;
  }

.searchBar {
  background-image: url(/images/search-bar-4.svg);
  background-size: cover;
  background-position: center;
  width: 300px;
  height: 60px;
  padding-left: 15px;
  display: flex;
  justify-content: center;
  color: #3a3a3a;
}

.searchBar:hover .searchBar:focus {
  box-shadow: 20px 20px 20px 20px;
}
.inputSearch {
    text-decoration: none;
    border: 0;
    background-color: #00000000;
    margin: auto;
    outline: none;
  }


  h2 {
    margin-right: 25px;
  }

  .imgPerfil {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: auto 15px auto 0;
    }

    .textContent {
        font-size: 1.5rem
    }  

    .textUsername {
        font-size: 1.2rem
    }
</style>
