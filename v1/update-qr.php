<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

// Validación de entrada
$required_fields = ['data', 'nombre_ref'];
foreach ($required_fields as $field) {
    if (empty($input[$field]) && $input[$field]==='') {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400);
        exit;
    }
}

$id = filter_var($input['id'], FILTER_SANITIZE_STRING);
$data = filter_var($input['data'], FILTER_SANITIZE_STRING);
$nombre_ref = filter_var($input['nombre_ref'], FILTER_SANITIZE_STRING);
$description = filter_var($input['description'], FILTER_SANITIZE_STRING);;

$sql = "UPDATE qr_codes SET data = ?,  description = ?, nombre_ref=? WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$data,$nombre_ref, $description, $id])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => 'Código QR actualizado exitosamente',
        'id'=> $id
    ]);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al actualizar código QR']);
}
?>