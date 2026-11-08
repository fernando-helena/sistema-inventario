
<script setup>
import { ref, computed, onMounted } from 'vue'
import api from './services/api'

// Variables
const productos = ref([])
const cargando = ref(false)
const error = ref('')
const mensaje = ref('')
const errores = ref({})

const editando = ref(false)
const idEditar = ref(null)
const busqueda = ref('')

// Formulario
const formulario = ref({
  nombre: '',
  descripcion: '',
  precio: '',
  stock: ''
})

// Productos filtrados por nombre
const productosFiltrados = computed(() => {
  return productos.value.filter(producto =>
    producto.nombre
      .toLowerCase()
      .includes(busqueda.value.toLowerCase())
  )
})

// Limpiar formulario
const limpiarFormulario = () => {
  formulario.value = {
    nombre: '',
    descripcion: '',
    precio: '',
    stock: ''
  }

  errores.value = {}
  editando.value = false
  idEditar.value = null
}

// Obtener productos
const obtenerProductos = async () => {
  cargando.value = true
  error.value = ''

  try {
    const respuesta = await api.get('/productos')
    productos.value = respuesta.data
  } catch (err) {
    error.value = 'No se pudieron cargar los productos.'
  } finally {
    cargando.value = false
  }
}

// Registrar o actualizar producto
const guardarProducto = async () => {
  errores.value = {}
  mensaje.value = ''
  error.value = ''

  try {
    if (editando.value) {
      await api.put(
        `/productos/${idEditar.value}`,
        formulario.value
      )

      mensaje.value = 'Producto actualizado correctamente.'
    } else {
      await api.post('/productos', formulario.value)

      mensaje.value = 'Producto registrado correctamente.'
    }

    limpiarFormulario()
    await obtenerProductos()

  } catch (err) {
    if (err.response?.status === 422) {
      errores.value = err.response.data.errors
    } else {
      error.value = 'No se pudo guardar el producto.'
    }
  }
}

// Preparar edición
const editarProducto = (producto) => {
  formulario.value = {
    nombre: producto.nombre,
    descripcion: producto.descripcion || '',
    precio: producto.precio,
    stock: producto.stock
  }

  idEditar.value = producto.id
  editando.value = true

  mensaje.value = ''
  error.value = ''
  errores.value = {}
}

// Cancelar edición
const cancelarEdicion = () => {
  limpiarFormulario()
  mensaje.value = ''
  error.value = ''
}

// Eliminar producto
const eliminarProducto = async (producto) => {
  const confirmar = window.confirm(
    `¿Estás seguro de eliminar ${producto.nombre}?`
  )

  if (!confirmar) return

  error.value = ''
  mensaje.value = ''

  try {
    await api.delete(`/productos/${producto.id}`)

    productos.value = productos.value.filter(
      p => p.id !== producto.id
    )

    mensaje.value = 'Producto eliminado correctamente.'

    // Si se elimina el producto que se estaba editando
    if (idEditar.value === producto.id) {
      limpiarFormulario()
    }

  } catch (err) {
    error.value = 'No se pudo eliminar el producto.'
  }
}

// Cargar productos al iniciar
onMounted(() => {
  obtenerProductos()
})
</script>

<template>
  <main class="contenedor">

    <h1>Inventario de productos</h1>
    <p>Sistema de gestión de inventario</p>

    <!-- FORMULARIO -->
    <section class="formulario">

      <h2>
        {{ editando ? 'Editar producto' : 'Registrar producto' }}
      </h2>

      <form @submit.prevent="guardarProducto">

        <label>Nombre</label>

        <input
          v-model="formulario.nombre"
          type="text"
          placeholder="Nombre del producto"
          required
        >

        <small v-if="errores.nombre" class="error">
          {{ errores.nombre[0] }}
        </small>

        <label>Descripción</label>

        <textarea
          v-model="formulario.descripcion"
          placeholder="Descripción del producto"
        ></textarea>

        <small v-if="errores.descripcion" class="error">
          {{ errores.descripcion[0] }}
        </small>

        <label>Precio</label>

        <input
          v-model="formulario.precio"
          type="number"
          min="0"
          step="0.01"
          placeholder="Precio"
          required
        >

        <small v-if="errores.precio" class="error">
          {{ errores.precio[0] }}
        </small>

        <label>Stock</label>

        <input
          v-model="formulario.stock"
          type="number"
          min="0"
          step="1"
          placeholder="Cantidad disponible"
          required
        >

        <small v-if="errores.stock" class="error">
          {{ errores.stock[0] }}
        </small>

        <button type="submit" class="guardar">
          {{ editando ? 'Guardar cambios' : 'Guardar producto' }}
        </button>

        <button
          v-if="editando"
          type="button"
          class="cancelar"
          @click="cancelarEdicion"
        >
          Cancelar edición
        </button>

      </form>

      <p v-if="mensaje" class="exito">
        {{ mensaje }}
      </p>

      <p v-if="error" class="error">
        {{ error }}
      </p>

    </section>

    <!-- LISTA DE PRODUCTOS -->
    <section class="lista">

      <h2>Lista de productos</h2>

      <!-- BUSCADOR -->
      <div class="buscador">

        <input
          v-model="busqueda"
          type="text"
          placeholder="Buscar producto por nombre..."
        >

      </div>

      <p v-if="cargando">
        Cargando productos...
      </p>

      <p v-else-if="productos.length === 0">
        No hay productos registrados.
      </p>

      <template v-else>

        <div class="tabla-contenedor">

          <table>

            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>

              <tr
                v-for="producto in productosFiltrados"
                :key="producto.id"
              >

                <td>{{ producto.id }}</td>

                <td>{{ producto.nombre }}</td>

                <td>
                  ${{ Number(producto.precio).toFixed(2) }}
                </td>

                <td>{{ producto.stock }}</td>

                <td class="acciones">

                  <button
                    class="editar"
                    @click="editarProducto(producto)"
                  >
                    Editar
                  </button>

                  <button
                    class="eliminar"
                    @click="eliminarProducto(producto)"
                  >
                    Eliminar
                  </button>

                </td>

              </tr>

              <tr v-if="productosFiltrados.length === 0">
                <td colspan="5" class="sin-resultados">
                  No se encontraron productos.
                </td>
              </tr>

            </tbody>

          </table>

        </div>

      </template>

    </section>

  </main>
</template>

<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f4f6f9;
  color: #263238;
}

.contenedor {
  max-width: 1100px;
  margin: 35px auto;
  padding: 25px;
  background: white;
  border-radius: 10px;
}

h1 {
  color: #263238;
}

h2 {
  margin-top: 10px;
}

.formulario {
  background: #f8fafc;
  padding: 20px;
  border-radius: 8px;
  margin: 25px 0;
}

form {
  display: grid;
  gap: 10px;
  max-width: 500px;
}

label {
  font-weight: bold;
}

input,
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-family: Arial, sans-serif;
}

textarea {
  resize: vertical;
  min-height: 60px;
}

button {
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  margin-top: 3px;
}

.guardar {
  background: #263238;
}

.cancelar {
  background: #777;
}

.editar {
  background: #2563eb;
}

.eliminar {
  background: #dc2626;
}

button:hover {
  opacity: 0.85;
}

.error {
  color: #dc2626;
  font-size: 13px;
}

.exito {
  color: #15803d;
  font-weight: bold;
}

.buscador {
  margin: 15px 0;
}

.buscador input {
  max-width: 400px;
}

.tabla-contenedor {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

th,
td {
  padding: 13px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background: #263238;
  color: white;
}

.acciones {
  white-space: nowrap;
}

.acciones button {
  margin-right: 4px;
}

.sin-resultados {
  text-align: center;
  padding: 20px;
  color: #777;
}

@media (max-width: 600px) {
  .contenedor {
    margin: 10px;
    padding: 15px;
  }
}
</style>
