<template>
  <div class="topbar-container bg-v2 ">
    <div class="d-flex justify-content-between row topbar-title">
        <div class="itty col-3 pl-5 ">
            <router-link :to="{ name: 'chats'}" class="pt-2 title-target">< </router-link>    
            <span class="pt-2 title-target">{{$t('search')}}</span>
        </div>
          <!-- Input para buscar -->
        <input class="inputSearch searchBar col-6 itty px20" type="text" v-model="busqueda" :placeholder="$t('search_by_name')">
        <div class="col-3"></div>
    </div>
  </div>
<div class="mainPrincipal">
  <!-- Mostrar usuarios filtrados -->
  <div class="userContainer d-flex flex-row align-items-center justify-content-between" v-for="usuario in usuariosFiltrados" :key="usuario.id">
    <div class="d-flex flex-row">
      <div class="contenedor-img-perfil">
        <img class="img-perfil" :src="usuario.media[0]?.original_url ? usuario.media[0].original_url : '/images/user-default.png'" alt="">
      </div>
      <div class="d-flex flex-column">
          <span class="itty font1">{{ usuario.name + " " + (usuario.surname ? " " + usuario.surname : "") }}</span>
          <span class="itty font2">@{{ usuario.username }}</span>
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
          console.log(usuarios.value);
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

    .textContent {
        font-size: 1.5rem
    }  

    .textUsername {
        font-size: 1.2rem
    }
</style>
