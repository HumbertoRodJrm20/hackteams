-- Ver todos los usuarios
SELECT id, name, email FROM users;

-- Ver todos los roles
SELECT id, nombre FROM roles;

-- Ver relaciones user-rol
SELECT ur.user_id, u.name, u.email, r.nombre as rol
FROM user_rol ur
JOIN users u ON ur.user_id = u.id
JOIN roles r ON ur.rol_id = r.id
ORDER BY ur.user_id;
