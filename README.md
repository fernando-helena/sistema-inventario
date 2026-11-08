
# Sistema de Gestión de Inventario

## Descripción
Sistema de inventario desarrollado con Laravel, Vue.js y Axios, conectado a una base de datos MySQL.

Permite registrar, consultar, editar, eliminar y buscar productos.

## Tecnologías utilizadas

- PHP 8.3
- Laravel 13
- MySQL / MariaDB
- Vue.js 3
- Axios
- Node.js
- NPM
- Composer

## Estructura del proyecto

- inventario-api: Backend desarrollado con Laravel.
- inventario-web: Frontend desarrollado con Vue.js.

## Requisitos

- PHP 8.3 o superior.
- Composer.
- Node.js y NPM.
- MySQL o MariaDB.

## Instalación del backend

1. Entrar a la carpeta:

   cd inventario-api

2. Instalar dependencias:

   composer install

3. Crear el archivo de configuración:

   cp .env.example .env

4. Configurar la conexión MySQL en .env:

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventario
   DB_USERNAME=root
   DB_PASSWORD=

5. Generar la clave:

   php artisan key:generate

6. Ejecutar migraciones:

   php artisan migrate

7. Cargar los productos iniciales:

   php artisan db:seed --class=ProductoSeeder

8. Iniciar Laravel:

   php artisan serve

La API estará disponible en:
http://127.0.0.1:8000

## Instalación del frontend

1. Entrar a la carpeta inventario-web.

2. Instalar dependencias:

   npm install

3. Iniciar Vue:

   npm run dev

La aplicación estará disponible en:
http://localhost:5173

## Funcionalidades

- Listar productos.
- Registrar productos.
- Editar productos.
- Eliminar productos.
- Buscar productos por nombre.
- Validar datos.
- Manejar errores de conexión con la API.

## Base de datos

Motor utilizado: MySQL.

Nombre de la base de datos: inventario.

La tabla principal es productos.

## Autor

Proyecto académico - Examen Práctico III UFDS.
