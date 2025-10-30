# Plataforma Agrícola - Backend (PHP) - Scaffold generado por ChatGPT

## Qué incluye
- Estructura MVC mínima
- Autenticación JWT (registro/login)
- Ejemplo de controlador y modelo para `cursos`
- Middleware básico y manejo de respuesta JSON
- Configuración vía `.env`

## Cómo usar
1. Copia este proyecto a `C:/xampp/htdocs/plataforma_agricola` (Windows) o tu carpeta de servidores.
2. Ejecuta `composer install`.
3. Copia `.env.example` a `.env` y completa las credenciales de DB y `JWT_SECRET`.
4. Asegúrate de que tu base de datos ya existe (tú indicaste que ya la tienes).
5. Apunta el DocumentRoot a `public/` o usa `http://localhost/plataforma_agricola/public/index.php`.

## Endpoints de ejemplo
- POST /api/auth/register  -> registrar usuario
- POST /api/auth/login     -> login y obtención de token
- GET  /api/cursos         -> listar cursos
- GET  /api/cursos/{id}    -> obtener curso por id

## Notas
- Este scaffold está pensado como punto de partida. Añade validaciones, manejo de errores y adapta a tu esquema de tablas existentes.
