<?php
// Permitir solo orígenes específicos
$allowed_origins = ['http://localhost:8000', 'https://cristian.tandempatrimonionacional.eu','https://andra.tandempatrimonionacional.eu','https://cristina.tandempatrimonionacional.eu'];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
} else {
    header("Access-Control-Allow-Origin: *"); // Como último recurso, pero no es lo ideal
}

// Permitir métodos HTTP específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir encabezados específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Permitir el envío de cookies o encabezados de autenticación
header("Access-Control-Allow-Credentials: true");

// Especificar el tipo de contenido de la respuesta
header('Content-Type: application/json; charset=utf-8');

// Manejar solicitudes preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Las respuestas a solicitudes preflight no deben incluir el cuerpo de la respuesta.
    header('HTTP/1.1 200 OK');
    exit;
}