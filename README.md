# API QR TANDEM
Una API  escita en PHP y MySQL que permita a los empleados de una institución registrar, consultar,modificar y eliminar códigos QR
## Endpoints
El número de endpoints dependerá de las necesidades de la aplicación: 
### Endpoints para Manejo de Usuarios
-- Registro de Usuarios
````
POST /api/register
````
--  Inicio de Sesión
````
POST /api/login
````
--  Listar Usuarios
````
GET /api/users
````
--  Obtener Información de un Usuario
````
POST /api/get_user 
````
--  Actualizar Usuario
````
PUT /api/update_user
````
--  Eliminar Usuario
````
DELETE /api/delete_user
````
--   Cambiar Contraseña
````
PUT /api/change_password
````
###  Endpoints para Manejo de Códigos QR
--  Crear Código QR
````
POST /api/create_qr
````
--  Listar Códigos QR
````
GET /api/qrs
````
--  Obtener Información de un Código QR
POST /api/get_qr (o GET /api/qrs/{id})
--  Listar Códigos QR
````
--  Actualizar Código QR
--  Listar Códigos QR
````
PUT /api/update_qr
--  Listar Códigos QR
````
--  Eliminar Código QR
DELETE /api/delete_qr
--  Listar Códigos QR
````
###  Endpoints para Autorización y Permisos
-- Asignar Rol a Usuario
````
POST /api/assign_role
````
-- Verificar Permisos
````
POST /api/check_permissions
````