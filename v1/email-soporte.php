<?php
require '../vendor/autoload.php';
require '../config/cors.php';

$input = json_decode(file_get_contents('php://input'), true);

$nombre= $input['nombre'];
$email = $input['email'];
$asunto = $input['asunto'];
$mensaje = $input['mensaje'];

//
$to = "canodelacuadra@gmail.com";
$subject="ticket de soporte de $nombre  con $email";
$body=$mensaje;
$headers = "From: $email";

if (mail($to, $subject, $body, $headers)) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => "El mensaje se ha enviado correctamente"]);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => "Error al enviar el correo electronico"]);
}
?>