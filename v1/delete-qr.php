<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

// Validación de entrada
$required_fields = ['nombre_ref'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400);
        exit;
    }
}

// Saneamiento de entrada
$nombre_ref = filter_var($input['nombre_ref'], FILTER_SANITIZE_STRING);

$sql = "DELETE FROM qr_codes WHERE nombre_ref = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre_ref]);

// Verificar el número de filas afectadas
if ($stmt->rowCount() > 0) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Código QR eliminado exitosamente']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error: No se encontró ningún código QR con el nombre_ref especificado']);
    http_response_code(404); // Not Found
}
?>