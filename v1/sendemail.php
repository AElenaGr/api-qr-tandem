<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
// Configuración del correo del webmaster
$webmaster_email = 'tandem.cristianvp@gmail.com';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos de la solicitud JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $name = isset($input['name']) ? $input['name'] : '';
    $email = isset($input['email']) ? $input['email'] : '';
    $message = isset($input['message']) ? $input['message'] : '';
    // Validación de datos
    if ( empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
        exit;
    }
    // Enviar correo
    $subject = "Mensaje de $name";
    $body = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";
    $headers = 'From: ' . $webmaster_email . "\r\n" .
               'Reply-To: ' . $email . "\r\n" .
               'X-Mailer: PHP/' . phpversion() . "\r\n" .
               'Return-Path: ' . $webmaster_email;
    // Respuesta del servidor en json
    if (mail($webmaster_email, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Correo enviado correctamente.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al enviar el correo.'
        ]);
    }
}