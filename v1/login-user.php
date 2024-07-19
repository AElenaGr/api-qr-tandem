<?php
require '../config/cors.php';
require '../config/database.php';
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

// Clave secreta para firmar el token (debería ser segura y no compartida)
//$secretKey = '142345';
$secretKey = $_ENV['SECRET_KEY'];


$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'];
$password = $input['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {

      // Datos para el token
    $tokenData = [
        'iat' => time(), // Tiempo en que se emite el token
        'exp' => time() + 3600*24*365, // Tiempo de expiración (1 hora)
        'userId' => $user['id'], // Información del usuario
        'email' => $user['email'],
        'role' => $user['role']
    ];

    // Generar el token
    $jwt = JWT::encode($tokenData, $secretKey, 'HS256');
// devolver mensaje a usuario
    echo json_encode([
        'message' => 'Login exitoso',
        'token' => $jwt,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'nombre' => $user['nombre'],

        ]
    ]);
} else {
    echo json_encode(['message' => 'Credenciales incorrectas']);
}
?>