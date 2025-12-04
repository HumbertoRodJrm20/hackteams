# Configuración de Roles del Sistema

## Descripción General

Este sistema tiene un **control de acceso basado en roles (RBAC)** donde cada usuario tiene **UN SOLO ROL** que determina qué funcionalidades puede acceder.

### Roles Disponibles

| Rol | Descripción | Acceso |
|-----|-------------|--------|
| **Admin** | Administrador del sistema | Crear eventos, gestionar usuarios, gestionar equipos, ver todo |
| **Juez** | Encargado de evaluar proyectos | Evaluar proyectos, ver constancias de juez |
| **Participante** | Miembro de equipos | Crear equipos, registrar proyectos, ver progreso, ver constancias |

## Cómo se Asignan los Roles

### Registro Público (auto-registro)
- **Solo para Participantes**
- Cuando un usuario se registra públicamente en `/register`, automáticamente recibe el rol "Participante"
- Se crea un registro en la tabla `participantes`

### Creación Administrativa
- Los **Admin** crean usuarios en `/admin/usuarios`
- Pueden asignar cualquier rol: Admin, Juez o Participante
- Cada usuario tiene **UN SOLO ROL** (no múltiples)

## Rutas Protegidas

### Participantes (middleware: `participante`)
```
/equipos              - Ver mis equipos
/equipos/registrar    - Crear nuevo equipo
/equipos/{id}         - Ver detalles del equipo
/proyectos/registrar  - Registrar proyecto
/progreso             - Ver progreso de proyectos
/constancia           - Ver mis constancias
```

### Jueces (middleware: `juez`)
```
/evaluacion           - Página de evaluación
/seguimiento-proyectos - Seguimiento de proyectos
/juez/constancias     - Ver constancias de jueces
```

### Admins (middleware: `admin`)
```
/eventos/crear        - Crear nuevos eventos
/admin/usuarios       - Gestionar usuarios (CRUD)
/admin/equipos        - Gestionar equipos (CRUD)
```

### Accesibles para Todos (autenticados)
```
/eventos              - Ver catálogo de eventos
/eventos/{id}         - Ver detalles de evento
/perfil               - Ver/editar perfil
/proyectos/{id}       - Ver detalles de proyecto
```

## Verificación de Roles en Vistas

Usa `Auth::user()->hasRole('NombreDelRol')` para verificar:

```blade
@if(Auth::user() && Auth::user()->hasRole('Juez'))
    {{-- Solo jueces ven esto --}}
@endif
```

## Base de Datos

### Tabla: `roles`
```sql
- id (int, primary key)
- nombre (string, unique): "Admin", "Juez", "Participante"
- descripcion (string, nullable)
- created_at, updated_at
```

### Tabla: `user_rol` (Pivot)
```sql
- user_id (FK -> users)
- rol_id (FK -> roles)
- created_at, updated_at
```

Nota: **Cada usuario debe tener exactamente UN rol** en la tabla `user_rol`.

### Tabla: `participantes`
```sql
- user_id (PK, FK -> users): Solo para usuarios con rol "Participante"
- carrera_id (FK -> carreras, nullable)
- matricula (string, unique, nullable)
- created_at, updated_at
```

## Problemas Comunes y Soluciones

### 1. Un usuario tiene múltiples roles
**Síntoma**: Un juez ve opciones de participante en el menú.

**Solución**:
```bash
# Ejecutar comando de limpieza
php artisan users:clean-roles

# O correr la migración
php artisan migrate
```

Esto elimina roles duplicados, manteniendo el rol primario (Admin > Juez > Participante).

### 2. Participante no ve opciones de participante
**Síntoma**: Usuario logueado pero no ve "Equipos", "Progreso", etc.

**Verificar**:
1. ¿El usuario tiene el rol "Participante"?
   ```sql
   SELECT u.nombre, r.nombre as rol FROM users u
   JOIN user_rol ur ON u.id = ur.user_id
   JOIN roles r ON ur.rol_id = r.id
   WHERE u.email = 'usuario@email.com';
   ```

2. ¿Existe registro en `participantes`?
   ```sql
   SELECT * FROM participantes WHERE user_id = USER_ID;
   ```

3. Si falta, crear manualmente:
   ```sql
   INSERT INTO participantes (user_id, created_at, updated_at)
   VALUES (USER_ID, NOW(), NOW());
   ```

### 3. Cambiar rol de un usuario
**En el panel admin**: `/admin/usuarios/{id}/editar`
- Seleccionar nuevo rol y guardar
- El sistema automáticamente:
  - Elimina roles anteriores
  - Asigna el nuevo rol
  - Crea/elimina registro de participante según corresponda

## Middleware

### ParticipanteMiddleware (`auth`, `participante`)
- Verifica que el usuario esté autenticado
- Verifica que tenga el rol "Participante"
- Rechaza acceso a Jueces y Admins

### JuezMiddleware (`auth`, `juez`)
- Verifica que el usuario esté autenticado
- Verifica que tenga el rol "Juez"
- Rechaza acceso a Participantes y Admins

### AdminMiddleware (`auth`, `admin`)
- Verifica que el usuario esté autenticado
- Verifica que tenga el rol "Admin"
- Rechaza acceso a Participantes y Jueces

## Resumen de Seguridad

✓ Los Jueces **NO tienen acceso** a:
  - Crear/ver equipos
  - Registrar proyectos
  - Ver progreso
  - Ver constancias de participante

✓ Los Participantes **NO tienen acceso** a:
  - Evaluar proyectos
  - Crear eventos
  - Gestionar usuarios
  - Gestionar equipos

✓ Los Admins **tienen acceso** a todo

✓ Cada usuario tiene **UN SOLO ROL** - no hay superposición
