<?php
require "../config/cors.php";
require '../vendor/autoload.php';
require "../config/database.php";
$sql = "SELECT
    qr_codes.id AS qr_id,
    qr_codes.data AS qr_data,
    qr_codes.nombre_ref AS qr_nombre_ref,
    qr_codes.description AS qr_description,
    qr_codes.created_at AS qr_created_at,
    users.id AS user_id,
    users.nombre AS user_nombre,
    users.delegacion AS user_delegacion,
    users.email AS user_email,
    users.role AS user_role
FROM qr_codes
    JOIN users ON qr_codes.created_by = users.id;";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $qr_codes = $stmt->fetchAll();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['qr_codes' => $qr_codes]);
    ?>
    
