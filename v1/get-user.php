<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';


$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'];
// Obtener el ID del usuario desde la solicitud
if (isset($input['id'])) {
    $userId = $input['id'];

    // Consulta SQL para obtener el usuario por ID
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['user' => $user]);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['message' => 'Usuario no encontrado']);
    }
} else {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(400);
    echo json_encode(['message' => 'ID de usuario no proporcionado']);
}
?>