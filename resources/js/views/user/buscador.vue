<template>
  <div class="py-2 d-flex justify-content-center">
    <div class="top-content-view d-flex justify-content-between">
      <div class="title_page d-flex justify-content-center align-items-center">
        <h2 class="pt-3 itty">Buscar</h2>
      </div>
    </div>
  </div>
  <div>
    <!-- Input para buscar -->
    <input type="text" v-model="busqueda" placeholder="Buscar por nombre">
    <!-- Mostrar usuarios filtrados -->
    <div v-for="usuario in usuariosFiltrados" :key="usuario.id">
      <p>Nombre: {{ usuario.name }}</p>
      <p>Username: {{ usuario.username }}</p>
      <p>Email: {{ usuario.email }}</p>
      <p>---------------------------------------------</p>
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
/* Estilos aquí */
</style>
