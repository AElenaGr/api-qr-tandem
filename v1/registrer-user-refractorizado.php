<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
require '../config/database.php';
$input = json_decode(file_get_contents('php://input'), true);
// Validación de entrada
$required_fields = ['nombre', 'delegacion', 'email', 'password'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400);
        exit;
    }
}
// Saneamiento de entrada
$nombre = filter_var($input['nombre'], FILTER_SANITIZE_STRING);
$delegacion = filter_var($input['delegacion'], FILTER_SANITIZE_STRING);
$email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
$password = $input['password'];
// Validación de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['message' => "Error: El email '$email' no es válido"]);
    http_response_code(400);
    exit;
}
// Verificación de email duplicado
$sql = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$emailCount = $stmt->fetchColumn();
if ($emailCount > 0) {
    echo json_encode(['message' => "Error: El email '$email' ya está registrado"]);
    http_response_code(409);
    exit;
}
// Hash de la contraseña
$passwordHashed = password_hash($password, PASSWORD_DEFAULT);
// variables para el email
// variables para el email
$admin_email ='canodelacuadra@gmail.com';
$subject = "Se ha registrado $nombre en la app qrcode";
$message = "El usuario $nombre con email $email  de la delegación $delegacion solicita permisos para utilizar la aplicación" ;
$headers = "From: $email";
// Inserción en la base de datos
$sql = "INSERT INTO users (nombre, delegacion, email, password) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$nombre, $delegacion, $email, $passwordHashed])) {
    if(mail($admin_email,$subject,$message,$headers)){
    $sendemail= "el mensaje a $email ha sido enviado correctamente";
 }else{
    $sendemail= "error al enviar en mensaje";
 }
  // contestamos mediante json
  header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => "$nombre registrado exitosamente",
        'email' => $email,
        'delegacion'=>$delegacion,
        'sendemailadmin'=>$sendemail
    ]);
    http_response_code(201);
} else {
    echo json_encode(['message' => "Error al registrar $nombre"]);
    http_response_code(500);
}
?>