<template>
    <div class="py-2 d-flex justify-content-center ">
      <div class="top-content-view d-flex justify-content-between ">
        <div class="title_page d-flex justify-content-center align-items-center">
          <h2 class="pt-3 itty">Buscar</h2>
        </div>
      </div>
    </div>
    <div>
      <div v-for="usuario in usuarios" :key="usuario.id">
        <p>Nombre: {{ usuario.name }}</p>
        <p>Username: {{ usuario.username }}</p>
        <p>Email: {{ usuario.email }}</p>
        <p>---------------------------------------------</p>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  
  export default {
    setup() {
      const usuarios = ref([]);
  
      const obtenerUsuarios = () => {
        axios.get('/api/usuarios')
          .then(response => {
            usuarios.value = response.data;
            console.log(usuarios.value)
          })
          .catch(error => {
            console.error('Error al obtener usuarios:', error);
          });
      }
  
      onMounted(() => {
        obtenerUsuarios();
      })
  
      return { usuarios };
    }
  };
  </script>
  
  <style>
  /* Estilos aquí */
  </style>
  