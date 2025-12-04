# Sistema de Calificación de Proyectos y Rankings

## 📋 Descripción General

Este sistema permite que los administradores asignen proyectos a jueces específicos para que los evalúen. Los jueces solo pueden acceder a los proyectos que les han sido asignados y pueden asignar calificaciones (0-100). El sistema calcula automáticamente:

- **Promedio de calificaciones** por proyecto (promedio de todos los jueces)
- **Puestos/Rankings** dentro de cada evento (basado en promedios)
- **Medallas** para los 3 primeros lugares (🥇 1º, 🥈 2º, 🥉 3º)

---

## 🎯 Flujo de Trabajo

### 1️⃣ **Admin: Asignar Proyectos a Jueces**

**Ruta:** `Administración → Proyectos y Jueces`

1. Ver la lista de todos los proyectos organizados por evento
2. Hacer clic en el botón **"Asignar Jueces"** de un proyecto
3. Seleccionar los jueces que evaluarán ese proyecto
4. Guardar la asignación
5. Los jueces asignados recibirán el proyecto en su lista

**Datos Mostrados:**
- Título del proyecto y equipo
- Evento
- Promedio actual
- Número de jueces asignados
- Calificaciones ya registradas

---

### 2️⃣ **Juez: Ver Proyectos Asignados**

**Ruta:** `Evaluación` (redirecciona a `/juez/proyectos`)

El juez verá:
- Lista de todos los proyectos asignados para calificar
- Promedio actual de cada proyecto
- Su propia calificación (si ya ha calificado)
- Puesto del proyecto en el evento

**Tarjetas por Proyecto:**
- Título y equipo
- Evento
- Promedio general (con color verde si tiene calificaciones)
- Calificaciones registradas
- Tu calificación (Pendiente/Número)
- Botón "Calificar Proyecto"

---

### 3️⃣ **Juez: Calificar un Proyecto**

**Ruta:** `Evaluación → Calificar Proyecto` (o `/juez/proyectos/{id}`)

**Interfaz de Calificación:**

1. **Visualizar Información del Proyecto**
   - Descripción completa
   - Avances registrados
   - Enlace al repositorio (si existe)

2. **Asignar Calificación**
   - Slider de 0 a 100
   - Campo numérico para entrada precisa
   - Vista previa de estrellas (⭐)
   - Guardarse automáticamente

3. **Ver Estadísticas**
   - Promedio general
   - Puesto en el evento (con medalla)
   - Todas las calificaciones de otros jueces

**Puntuaciones:**
- 0-20: ⭐ (1 estrella)
- 20-40: ⭐⭐ (2 estrellas)
- 40-60: ⭐⭐⭐ (3 estrellas)
- 60-80: ⭐⭐⭐⭐ (4 estrellas)
- 80-100: ⭐⭐⭐⭐⭐ (5 estrellas)

---

### 4️⃣ **Juez: Ver Sus Calificaciones**

**Ruta:** `Evaluación → Ver Mis Calificaciones` (o `/juez/mis-calificaciones`)

**Vista por Evento:**
- Proyectos organizados por evento
- Puesto de cada proyecto (con medalla)
- Promedio general
- Tu calificación registrada
- Indica si aún falta calificar

---

### 5️⃣ **Admin: Ver Rankings**

**Ruta:** `Administración → Rankings`

**Visualización:**
- Un card por evento
- Proyectos ordenados por promedio de mayor a menor
- Medallas para los 3 primeros:
  - 🥇 1º Lugar (badge dorado)
  - 🥈 2º Lugar (badge plateado)
  - 🥉 3º Lugar (badge bronce)
- Información: título, equipo, promedio, estrellas, cantidad de jueces
- Enlace para ver detalles del proyecto

---

## 📊 Cálculo de Ranking

**Fórmula:**
```
Promedio = Suma de todas las calificaciones / Cantidad de jueces
Puesto = Cantidad de proyectos con promedio mayor + 1
```

**Ejemplo:**
```
Evento: Hackathon 2025

Proyecto A: Calificaciones = [85, 90, 88] → Promedio = 87.67 → 1º Lugar
Proyecto B: Calificaciones = [75, 78, 80] → Promedio = 77.67 → 2º Lugar
Proyecto C: Calificaciones = [70, 72, 68] → Promedio = 70.00 → 3º Lugar
```

---

## 🔐 Restricciones de Acceso

| Acción | Admin | Juez | Participante |
|--------|-------|------|--------------|
| Ver todos los proyectos | ✅ | ❌ Solo asignados | ❌ |
| Asignar jueces a proyectos | ✅ | ❌ | ❌ |
| Calificar proyectos | ❌ | ✅ Solo asignados | ❌ |
| Ver rankings | ✅ | ❌ | ❌ |
| Ver mis calificaciones | ❌ | ✅ | ❌ |

---

## 📱 Vistas Principales

### Para Administradores

1. **`/admin/proyectos`**
   - Lista de proyectos por evento
   - Estado de asignación de jueces
   - Promedio y estado de evaluación

2. **`/admin/proyectos/{id}/asignar-jueces`**
   - Checkbox para seleccionar jueces
   - Lista de jueces actualmente asignados
   - Calificaciones registradas hasta el momento

3. **`/admin/rankings`**
   - Rankings finales por evento
   - Medallas visuales
   - Información de puntuaciones

### Para Jueces

1. **`/juez/proyectos`**
   - Grid de proyectos asignados
   - Puesto y promedio de cada uno
   - Estado de tu calificación

2. **`/juez/proyectos/{id}`**
   - Interfaz completa de calificación
   - Slider interactivo
   - Vista de estrellas
   - Información del proyecto

3. **`/juez/mis-calificaciones`**
   - Ranking de proyectos por evento
   - Solo los que has calificado o que te fueron asignados
   - Tu calificación vs. promedio

---

## 🗄️ Base de Datos

### Tabla: `proyecto_juez` (Pivot)
```sql
- id (PK)
- proyecto_id (FK → proyectos)
- juez_user_id (FK → users)
- asignado_en (timestamp)
- created_at, updated_at
- UNIQUE(proyecto_id, juez_user_id) — Evita duplicados
```

### Tabla: `calificaciones`
```sql
- id (PK)
- proyecto_id (FK → proyectos)
- juez_user_id (FK → users)
- puntuacion (numeric 0-100)
- created_at, updated_at
```

---

## 🔧 Modelos Importantes

### Proyecto
```php
// Obtener promedio
$promedio = $proyecto->obtenerPromedio(); // 0-100

// Obtener puesto en su evento
$puesto = $proyecto->obtenerPuesto(); // 1, 2, 3, etc.

// Obtener jueces asignados
$jueces = $proyecto->jueces(); // Collection of users

// Obtener calificaciones
$calificaciones = $proyecto->calificaciones(); // Collection
```

### User
```php
// Proyectos asignados (para jueces)
$proyectos = $user->proyectos()
    ->whereHas('jueces', function($q) use ($user) {
        $q->where('users.id', $user->id);
    })
    ->get();

// Calificaciones del juez
$calificaciones = $user->calificaciones();
```

---

## ⭐ Campos Destacados

Todas las vistas muestran el **puesto/ranking** del proyecto:

- **Vista de Proyectos para Juez:** Medalla en la tarjeta
- **Página de Calificación:** Medalla prominente + estadísticas
- **Mis Calificaciones:** Medalla + puesto
- **Rankings Admin:** Puesto grande + medalla + estrellas
- **Detalle del Proyecto:** Medalla en sidebar de evaluación

Los tres primeros lugares tienen:
- 🥇 **1º Lugar** (badge dorado)
- 🥈 **2º Lugar** (badge plateado)
- 🥉 **3º Lugar** (badge bronce)
- Otros: Número del puesto (4º, 5º, etc.)

---

## 🚀 Próximos Pasos

- [ ] Ejecutar migraciones: `php artisan migrate`
- [ ] Crear usuarios Juez desde admin panel
- [ ] Asignar proyectos a jueces
- [ ] Los jueces pueden comenzar a calificar
- [ ] Ver rankings en tiempo real

---

## 📝 Notas Importantes

1. **Un juez solo ve proyectos asignados** - No puede ver otros proyectos
2. **El promedio se calcula automáticamente** - Actualizado en tiempo real
3. **El puesto se recalcula dinámicamente** - Basado en promedios actuales
4. **Los datos se preservan** - Historial completo de calificaciones
5. **Dark mode soportado** - Todas las vistas funcionan en modo oscuro

---

## 🆘 Troubleshooting

### Un juez no ve sus proyectos asignados
1. Verificar que tenga el rol "Juez"
2. Verificar en Admin Panel que el proyecto esté asignado a este juez
3. Limpiar caché del navegador

### El promedio no actualiza
1. Asegurarse de guardar la calificación (botón "Guardar Calificación")
2. Recargar la página
3. Los promedios se actualizan automáticamente

### Editar una calificación
1. El juez puede ir a `/juez/proyectos/{id}`
2. Cambiar el valor del slider
3. Guardar nuevamente
4. El sistema actualiza automáticamente (updateOrCreate)
