<?php
require '../vendor/autoload.php'; // Cargar el autoloader de Composer
require '../config/database.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();



use Firebase\JWT\JWT;
use Firebase\JWT\Key;



function authenticate($requiredRole = null) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Authorization, Content-Type");

    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        // Algunos servidores web convierten los encabezados a minúsculas
        $authHeader = $headers['authorization'];
    } else {
        $authHeader = null;
    }

    if ($authHeader) {
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if ($jwt) {
            try {
                 //$secretKey = '142345';
                 $secretKey = $_ENV['SECREt_KEY'];
                $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

                // Verificar el rol del usuario si se requiere
                if ($requiredRole && (!isset($decoded->role) || $decoded->role != $requiredRole)) {
                    http_response_code(403);
                    echo json_encode(['message' => 'Permiso denegado']);
                    exit();
                }

                // Token es válido, devolver los datos decodificados
                return $decoded;

            } catch (Exception $e) {
                // Token inválido
                http_response_code(401);
                echo json_encode(['message' => 'Acceso no autorizado']);
                exit();
            }
        } else {
            // No se proporcionó token
            http_response_code(400);
            echo json_encode(['message' => 'Token no proporcionado']);
            exit();
        }
    } else {
        // No se proporcionó encabezado de autorización
        http_response_code(400);
        echo json_encode(['message' => 'Encabezado de autorización no proporcionado']);
        exit();
    }
}
?>
