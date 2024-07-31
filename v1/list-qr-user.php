<?php
require "../config/cors.php";
require '../vendor/autoload.php';
require "../config/database.php";

try {
    // Conexión a la base de datos
    $pdo = new PDO($dsn, $user);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Capturar datos JSON del cuerpo de la solicitud POST
    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);
    
    $created_by = isset($data['created_by']) ? intval($data['created_by']) : null;
    
    if ($created_by !== null) {
        // Consulta SQL para seleccionar los códigos QR creados por el usuario especificado
        $sql = "SELECT
            qr_codes.id AS qr_id,
            qr_codes.data AS qr_data,
            qr_codes.nombre_ref AS qr_nombre_ref,
            qr_codes.description AS qr_description,
            qr_codes.created_at AS qr_created_at,
            qr_codes.created_by AS created_by
        FROM qr_codes
        WHERE qr_codes.created_by = :created_by";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':created_by', $created_by, PDO::PARAM_INT);
        $stmt->execute();
        $qr_codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['qr_codes' => $qr_codes]);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Parámetro created_by faltante o inválido.']);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $e->getMessage()]);
}
?>