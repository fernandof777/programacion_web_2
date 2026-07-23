# Respaldo de la base de datos del taller

Esta carpeta contiene dos formas de recuperar la base:

- `taller_automotriz.sqlite`: copia exacta y funcional de la base de datos local después de ejecutar migraciones y datos iniciales.
- `crear_base_taller.php`: copia del script de migración Laravel que crea el esquema normalizado del taller.

## Restaurar la copia SQLite

Con el servidor detenido, reemplaza `database/database.sqlite` por `taller_automotriz.sqlite` y vuelve a iniciar Laravel.

## Reconstruir mediante Laravel

El script original también está en `database/migrations`. Para reconstruir una base vacía ejecuta desde la raíz del proyecto:

```powershell
php artisan migrate:fresh --seed
```

Atención: `migrate:fresh` elimina los datos actuales antes de crear nuevamente las tablas. Utilízalo solamente cuando quieras comenzar desde cero o después de guardar una copia.

## Módulos incluidos

Usuarios, roles, permisos, sucursales, clientes, mecánicos, especialidades, marcas, modelos, vehículos, citas, categorías de servicio, servicios, órdenes de trabajo, asignaciones, repuestos, proveedores, inventario, movimientos, facturas, métodos de pago, pagos y auditoría.
