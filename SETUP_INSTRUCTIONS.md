# 🔧 Instrucciones de Setup - Sistema de Calificación y Rankings

## ⚠️ IMPORTANTE: Ejecutar Migraciones

El error `Table 'hackteams.proyecto_juez' doesn't exist` significa que necesitas ejecutar las migraciones de la base de datos.

### Opción 1: CMD / Terminal (RECOMENDADO)

1. Abre **Command Prompt** o **PowerShell** como Administrador
2. Navega a tu proyecto:
   ```bash
   cd D:\xampp\htdocs\hackteams
   ```

3. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

4. Deberías ver algo como:
   ```
   Migrating: 2025_12_05_000000_clean_multiple_user_roles
   Migrated:  2025_12_05_000000_clean_multiple_user_roles (xx.xxms)
   Migrating: 2025_12_05_000001_create_proyecto_juez_table
   Migrated:  2025_12_05_000001_create_proyecto_juez_table (xx.xxms)
   ```

### Opción 2: A través de XAMPP Control Panel

1. Abre **XAMPP Control Panel**
2. Inicia Apache y MySQL
3. Abre **Command Shell** (botón "Shell" en XAMPP)
4. Navega y ejecuta:
   ```bash
   cd D:\xampp\htdocs\hackteams
   php artisan migrate
   ```

### Opción 3: Si hay errores con las migraciones

Si recibes un error como "Migration already exists" o similar, puedes:

**Opción A: Revertir todas y migrar de nuevo**
```bash
php artisan migrate:reset
php artisan migrate
```

**Opción B: Migrar solo las nuevas**
```bash
php artisan migrate --step
```

---

## ✅ Verificar que Funcionó

1. Abre tu navegador y ve a: `http://hackteams.test/juez/proyectos`
2. Deberías ver:
   - Si eres juez: "No hay proyectos asignados" (normal si es la primera vez)
   - Si no eres juez: Redirección a eventos (normal)

---

## 📋 Checklist de Setup

- [ ] Ejecuté `php artisan migrate`
- [ ] La tabla `proyecto_juez` ahora existe
- [ ] Puedo acceder a `/juez/proyectos` sin errores
- [ ] Puedo acceder a `/admin/proyectos` (si soy admin)
- [ ] Puedo acceder a `/admin/rankings` (si soy admin)

---

## 🚀 Próximos Pasos Después de Migrar

1. **Crear usuarios Juez** (si no existen)
   - Ir a: `Administración → Gestionar Usuarios → Crear Usuario`
   - Rol: Juez

2. **Crear/Registrar un Equipo y Proyecto**
   - Como Participante, crear equipo
   - Registrar un proyecto para un evento

3. **Asignar Proyecto a Jueces**
   - Como Admin, ir a: `Administración → Proyectos y Jueces`
   - Seleccionar un proyecto
   - Asignar jueces

4. **Calificar como Juez**
   - Loguearse como Juez
   - Ir a: `Evaluación`
   - Debería ver el proyecto asignado

5. **Ver Rankings**
   - Como Admin, ir a: `Administración → Rankings`
   - Ver proyectos ordenados por calificación

---

## 🐛 Troubleshooting

### Error: "Base table or view not found"
**Solución:** Ejecuta `php artisan migrate`

### Error: "SQLSTATE[HY000]: General error: 1215"
**Solución:** Las migraciones tienen orden de dependencias. Intenta:
```bash
php artisan migrate:reset
php artisan migrate
```

### Error: "Target class [JuezProyectoController] does not exist"
**Solución:** Los controladores se crearon automáticamente. Si ves esto, es un error de caché:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### El slider de calificación no funciona
**Solución:**
- Limpiar caché del navegador (Ctrl+Shift+Delete)
- Recargar la página (Ctrl+F5)
- Usar otro navegador

---

## 📞 Soporte

Si tienes problemas después de ejecutar `php artisan migrate`, verifica:

1. **MySQL está corriendo** en XAMPP
2. **Base de datos `hackteams` existe**
3. **Usuario de BD tiene permisos** para crear tablas
4. **No hay errores de sintaxis** en las migraciones

Si todo falla, puedes crear la tabla manualmente:

```sql
CREATE TABLE proyecto_juez (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proyecto_id INT UNSIGNED NOT NULL,
    juez_user_id INT UNSIGNED NOT NULL,
    asignado_en TIMESTAMP CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_proyecto_juez (proyecto_id, juez_user_id),
    FOREIGN KEY (proyecto_id) REFERENCES proyectos(id) ON DELETE CASCADE,
    FOREIGN KEY (juez_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_juez_user_id (juez_user_id),
    INDEX idx_proyecto_id (proyecto_id)
);
```

---

## 📚 Documentación Relacionada

- `JUDGING_AND_RANKINGS.md` - Sistema de calificación y rankings
- `ROLES_CONFIGURATION.md` - Configuración de roles
