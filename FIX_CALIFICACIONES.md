# Fix: Guardar Calificaciones de Jueces

Se identificó un problema con la constrainta UNIQUE en la tabla `calificaciones` que impedía guardar las calificaciones correctamente.

## Problema Identificado

La tabla `calificaciones` tenía una constrainta UNIQUE sobre `(proyecto_id, juez_user_id, criterio_id)`, pero el sistema intenta guardar calificaciones sin especificar `criterio_id` (NULL), lo que causaba conflictos.

## Solución Implementada

Se han creado dos migraciones:

1. **create_calificaciones_table.php** (Actualizada)
   - Cambio: Constrainta única ahora es solo en `(proyecto_id, juez_user_id)`
   - Cambio: `criterio_id` ahora es nullable

2. **fix_calificaciones_constraint.php** (Nueva)
   - Elimina la constrainta única antigua
   - Hace `criterio_id` nullable si no lo es
   - Agrega la nueva constrainta única

## Cómo Ejecutar

### Opción 1: Resetear base de datos (Recomendado si no tienes datos importantes)
```bash
php artisan migrate:fresh
```

### Opción 2: Solo ejecutar las nuevas migraciones
```bash
php artisan migrate
```

## Verificación

Después de ejecutar las migraciones, el juez debería poder:
1. Navegar a un proyecto asignado
2. Mover el slider de calificación
3. Hacer clic en "Guardar Calificación"
4. Ver el mensaje de éxito
5. La calificación debería aparecer en la lista de "Todas las Calificaciones"

## Datos de Prueba

Para probar:
1. Inicia sesión como Admin
2. Ve a Admin > Gestión de Proyectos
3. Asigna algunos proyectos a un juez
4. Inicia sesión como juez
5. Ve a "Mis Proyectos"
6. Intenta calificar un proyecto

Si aún hay problemas, revisa:
- `storage/logs/laravel.log` para errores
- Que el juez tenga la relación correcta con los proyectos
