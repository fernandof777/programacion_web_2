# TallerPro

Aplicación web para la gestión integral de un taller automotriz. Este proyecto
unifica la autenticación y el módulo de servicios con la dirección visual,
wireframes y mockups elaborados para el proyecto académico.

## Estado actual

- Autenticación manual con inicio y cierre de sesión.
- Dashboard con métricas obtenidas de la base de datos.
- Catálogo de servicios con listado, búsqueda, filtros, paginación, registro,
  edición y eliminación.
- Asociación automática entre servicios y usuarios.
- Autorización por propietario para editar y eliminar servicios.
- Validaciones reutilizables con mensajes en español.
- Navegación responsiva preparada para módulos futuros.
- Wireframes y mockups disponibles en `public/img`.

Los módulos de clientes, vehículos, órdenes de trabajo, repuestos, pagos,
roles y reportes se incorporarán progresivamente. Las opciones todavía no
implementadas aparecen deshabilitadas en la navegación.

## Requisitos

- PHP 8.3 o superior.
- Composer.
- MySQL o MariaDB.
- Node.js y NPM.

## Instalación

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Crear una base de datos llamada `tallerpro` y configurar sus credenciales en
`.env`. En la instalación de desarrollo actual, XAMPP utiliza `::1:3307`
porque el puerto 3306 pertenece a otra instancia de MySQL.

```bash
php artisan migrate:fresh --seed
php artisan serve
```

La aplicación estará disponible normalmente en `http://127.0.0.1:8000`.

## Usuarios iniciales

| Usuario | Correo | Contraseña |
|---|---|---|
| Luis Fernando | `luis@taller.com` | `password123` |
| Maria Garcia | `maria@taller.com` | `password123` |

Estas cuentas son únicamente para desarrollo y deben cambiarse antes de una
publicación real.

## Pruebas

```bash
php artisan test
```

Las pruebas cubren autenticación, protección de rutas, validación de servicios,
asignación segura del propietario y autorización para actualizar o eliminar.

## Próxima etapa sugerida

Implementar roles y permisos, y después el módulo de clientes y vehículos. Así
se podrá construir posteriormente el flujo completo de órdenes de trabajo.
