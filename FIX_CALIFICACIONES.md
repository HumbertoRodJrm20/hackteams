# Sistema de Calificación con Criterios Ponderados

Se implementó un sistema completo de evaluación basado en criterios con ponderación.

## Cómo Funciona

### Para Administradores:
1. Crear un Evento
2. Definir Criterios de Evaluación (Ej: Innovación 30%, Implementación 40%, Diseño 30%)
3. Crear Proyectos para el evento
4. Asignar Jueces a los proyectos

### Para Jueces:
1. Ver los Proyectos Asignados
2. Para cada proyecto, calificar CADA criterio de 0-100
3. El sistema automáticamente calcula: (calificación × ponderación) de cada criterio
4. El promedio final se calcula como el promedio ponderado de todos los jueces

### Cálculo de Puntuación:

**Para cada juez:**
```
Puntuación = (Criterio1_Score × Criterio1_Weight) + (Criterio2_Score × Criterio2_Weight) + ...
```

**Puntuación final del proyecto:**
```
Promedio Final = (Puntuación_Juez1 + Puntuación_Juez2 + ...) / Número_Jueces
```

## Ejemplo

Si tienes:
- Criterio "Innovación" con ponderación 40%
- Criterio "Implementación" con ponderación 35%
- Criterio "Presentación" con ponderación 25%

Y un juez califica:
- Innovación: 85/100
- Implementación: 75/100
- Presentación: 90/100

La puntuación de ese juez será:
```
(85 × 0.40) + (75 × 0.35) + (90 × 0.25) = 34 + 26.25 + 22.5 = 82.75/100
```

## Base de Datos

La tabla `calificaciones` mantiene:
- `proyecto_id`: ID del proyecto
- `juez_user_id`: ID del juez
- `criterio_id`: ID del criterio (IMPORTANTE: debe existir)
- `puntuacion`: Puntuación 0-100
- Constraint UNIQUE: (proyecto_id, juez_user_id, criterio_id)

## Migraciones

No hay nuevas migraciones para ejecutar. El sistema usa la estructura existente.

Si necesitas limpiar datos:
```bash
php artisan migrate:fresh
```

## Verificación

1. **Crear Criterios**: Ve a Admin > Crear Evento > Agregar Criterios
2. **Asignar Jueces**: Admin > Gestión Proyectos > Asignar Jueces
3. **Calificar**: Juez > Mis Proyectos > Ver Proyecto > Calificar cada criterio
4. **Ver Resultados**: Admin > Rankings (verá puntuaciones ponderadas)

## Troubleshooting

Si los criterios no aparecen:
- Verifica que el evento tiene criterios definidos
- Revisa `storage/logs/laravel.log`

Si la puntuación es incorrecta:
- Verifica que todos los criterios tienen ponderaciones que suman 100%
- Asegúrate que el juez ha calificado TODOS los criterios
