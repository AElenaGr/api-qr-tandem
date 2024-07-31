<?php
require "../config/cors.php";
require '../vendor/autoload.php';
require '../config/database.php';

try {
    // Leer la entrada JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar entrada
    if (isset($input['email']) && !empty($input['email'])) {
        $email = filter_var($input['email'], FILTER_VALIDATE_EMAIL); // Validar correo electrónico

        if ($email) {
            // Comprobar si el correo electrónico existe
            $checkEmailSql = "SELECT id FROM users WHERE email = ?";
            $checkStmt = $pdo->prepare($checkEmailSql);
            $checkStmt->execute([$email]);
            $userId = $checkStmt->fetchColumn();

            if ($userId) {
                // Iniciar una transacción
                $pdo->beginTransaction();

                try {
                    // Eliminar los QR relacionados
                    $deleteQrSql = "DELETE FROM qr_codes WHERE created_by = ?";
                    $deleteQrStmt = $pdo->prepare($deleteQrSql);
                    $deleteQrStmt->execute([$userId]);

                    // Eliminar el usuario
                    $deleteUserSql = "DELETE FROM users WHERE id = ?";
                    $deleteUserStmt = $pdo->prepare($deleteUserSql);
                    $deleteUserStmt->execute([$userId]);

                    // Confirmar la transacción
                    $pdo->commit();

                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'message' => "El usuario y sus QR relacionados han sido eliminados exitosamente",
                        'email' => $email
                    ]);
                } catch (Exception $e) {
                    // Revertir la transacción en caso de error
                    $pdo->rollBack();
                    error_log("Error deleting user: " . $e->getMessage()); // Registrar error
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['message' => 'Error al eliminar el usuario']);
                }
            } else {
                // El correo electrónico no existe en la base de datos
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['message' => 'El correo electrónico no existe', 'email' => $email]);
            }
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['message' => 'Correo electrónico inválido']);
        }
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => 'Datos incompletos']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    error_log("General error: " . $e->getMessage()); // Registrar error
    echo json_encode(['message' => 'Error inesperado']);
}
