<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';
$input = json_decode(file_get_contents('php://input'), true);
// Validación de entrada
$required_fields = ['data', 'nombre_ref'];
foreach ($required_fields as $field) {
    if (empty($input[$field]) && $input[$field] != "") {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400);
        exit;
    }
}
// Saneamiento de entrada
$data = filter_var($input['data'], FILTER_SANITIZE_STRING);
$nombre_ref = filter_var($input['nombre_ref'], FILTER_SANITIZE_STRING);
$description = filter_var($input['description'], FILTER_SANITIZE_STRING);
$created_by = $input['created_by'];
// Comprobar duplicados
$sql_check = "SELECT COUNT(*) FROM qr_codes WHERE data = ? OR nombre_ref = ?";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->execute([$data, $nombre_ref]);
$existing_count = $stmt_check->fetchColumn();
if ($existing_count > 0) {
    echo json_encode(['message' => 'Error: Ya existe un registro con el mismo data o nombre_ref']);
    http_response_code(409); // 409 Conflict
    exit;
}
// Insertar datos
$sql = "INSERT INTO qr_codes (data, nombre_ref, description, created_by) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$data, $nombre_ref, $description, $created_by])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => 'Código QR creado exitosamente'
    ]);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al crear código QR']);
}
?>