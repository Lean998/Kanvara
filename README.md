# 🚀 Kanvara

Proyecto integrador de la materia **Técnicas y Herramientas para el Desarrollo Web con Calidad**.

Desarrollado con [CodeIgniter 4](https://codeigniter.com/), un framework PHP ligero, rápido y seguro.

---

## 📦 Requisitos

- PHP 8.1 o superior
- Composer instalado ([https://getcomposer.org/](https://getcomposer.org/))
- Servidor web (Apache, Nginx o el servidor embebido de PHP)

---

## 🔧 Instalación del Proyecto

1. **Clonar el repositorio o copiar los archivos del proyecto**

   No es necesario incluir las carpetas `vendor/` ni `system/`, ya que se generan automáticamente con Composer.

2. **Instalar las dependencias del proyecto**

   Desde la terminal, en la raíz del proyecto, ejecutar:

   ```bash
   composer install
   ```

   > Esto descargará todas las librerías necesarias, incluyendo el core de CodeIgniter en `vendor/codeigniter4/framework/system`.

3. **Copiar el archivo de entorno**

   copiar el archivo `.env`:

4. **Configurar variables de entorno**

   Editar el archivo `.env` y modificar al menos las siguientes variables:

   ```dotenv
   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = localhost
   database.default.database = nombre_de_tu_base_de_datos
   database.default.username = tu_usuario
   database.default.password = tu_contraseña
   ```

---

## 📁 Estructura del Proyecto

- `app/` → Lógica de la aplicación (controladores, modelos, vistas, etc.)
- `public/` → Carpeta raíz pública del servidor
- `writable/` → Archivos generados en tiempo de ejecución (logs, caché, etc.)
- `vendor/` → **No viene incluida, se genera con `composer install`**
- `system/` → Incluida automáticamente desde `vendor/codeigniter4/framework/system`

---

## 🛠️ Herramientas usadas

- PHP 8.1+
- CodeIgniter 4.x
- Composer
- Bootstrap (si aplica)

---

## 🧠 Notas importantes

- **No edites directamente archivos dentro de `vendor/` o `system/`**. Si necesitás extender funcionalidad, usá la carpeta `app/`.

---

## 👤 Autor

Leandro Agüero – Proyecto académico – Universidad Nacional de San Luis
