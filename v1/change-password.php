<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'];
//$password = $input['password'];
$password = password_hash($input['password'], PASSWORD_DEFAULT);



$sql_check = "SELECT email FROM users WHERE email = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$email]);

if ($stmt_check->rowCount() > 0) {
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$password, $email])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'message' => 'Contraseña actualizada exitosamente',
            'email' => $email,
        ]);
    } else {
        echo json_encode(['message' => 'Error al cambiar contraseña']);
    }
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error: Email no encontrado']);
}
?>