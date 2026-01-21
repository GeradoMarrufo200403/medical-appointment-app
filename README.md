# medical-appointment-app

## Objetivo General
El objetivo de este proyecto es el desarrollo de un **Sistema de Citas Médicas** completo. Este sistema permitirá la gestión eficiente de citas, pacientes y doctores, sirviendo como plataforma base para aplicar conocimientos de desarrollo web y flujo de trabajo moderno.

## Tecnologías a utilizar
Este proyecto está construido utilizando las siguientes herramientas y tecnologías:

* **Framework Backend:** Laravel (PHP)
* **Base de Datos:** MySQL / MariaDB
* **Frontend & Stack:**
    * Livewire
    * Laravel Jetstream
    * Tailwind CSS
* **Entorno de Ejecución:** Node.js & NPM
* **Gestor de Paquetes:** Composer

---

## 🚀 Avances ADA 2: Ajustes Globales y Panel Administrativo

En esta etapa se configuraron los aspectos globales del proyecto y se maquetó la estructura base del panel de administración utilizando Blade y Flowbite.

### 1. Configuración General (Idioma, Timezone, MySQL, Foto)

* **Idioma:** Se modificó el archivo `config/app.php`, estableciendo `'locale' => 'es'`.
    * *Verificación:* Se comprobó que los mensajes de error por defecto y las fechas ahora se procesan en formato español.
* **Zona Horaria:** Se ajustó en `config/app.php` a `'timezone' => 'America/Merida'`.
    * *Verificación:* Se utilizó `now()` en tinker o una vista para confirmar que la hora mostrada coincide con la hora local de Mérida.
* **Base de Datos (MySQL):** Se configuraron las credenciales en el archivo `.env` y se ejecutó `php artisan migrate`.
    * *Verificación:* La conexión fue exitosa y las tablas se crearon correctamente en la base de datos local (verificable en TablePlus/phpMyAdmin).
* **Foto de Perfil:** Se habilitó la funcionalidad en `config/jetstream.php` descomentando `Features::profilePhotos()`.
    * *Verificación:* En el dashboard, el menú de navegación ahora muestra el avatar por defecto y permite subir una imagen nueva desde la configuración de perfil.

### 2. Implementación del Layout Admin (Blade + Flowbite)

Se creó un layout dedicado para el administrador que servirá de contenedor para todas las vistas del panel.

**Pasos realizados:**

1.  **Integración de Flowbite:**
    * Se ejecutó `npm install flowbite`.
    * Se agregó el plugin `require('flowbite/plugin')` dentro del archivo `tailwind.config.js` en la sección de plugins.
    * *Verificación:* Los componentes interactivos (como el dropdown del navbar y el sidebar) funcionan correctamente al hacer clic.

2.  **Creación del Layout (`layouts/admin.blade.php`):**
    * Se creó un archivo base HTML que incluye las directivas `@vite` para cargar los estilos y scripts.
    * **Includes:** Se modularizó el código separando la barra de navegación y el sidebar en archivos parciales (ej. `@include('admin.partials.navigation')`) para mantener el código limpio.
    * **Slots:** Se implementó la directiva `{{ $slot }}` dentro de la etiqueta `<main>`. Esto permite que las vistas hijas inyecten su contenido dinámico dentro de la estructura fija del admin.

3.  **Prueba de funcionamiento:**
    * Se creó una vista de prueba retornada por la ruta `/admin`.
    * Esta vista extiende el layout admin y envía contenido (ej. "Hola desde admin") que se renderiza correctamente en el lugar del `$slot`.