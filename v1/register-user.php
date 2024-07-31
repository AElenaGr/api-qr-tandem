<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods:  POST ");
// header("Access-Control-Allow-Headers: Content-Type");
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

// variables para el registro
$nombre = $input['nombre'];
$delegacion = $input['delegacion'];
$email = $input['email'];
$password = password_hash($input['password'], PASSWORD_DEFAULT);

// variables para el email
$admin_email ='tandem.cristianvp@gmail.com';
$subject = "Se ha registrado $nombre en la app qrcode";
$message = "El usuario $nombre con email $email  de la delegación $delegacion solicita permisos para utilizar la aplicación" ;
$headers = "From: $email";

$sql = "INSERT INTO users (nombre,delegacion, email, password) VALUES (?, ?, ?,?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$nombre, $delegacion, $email, $password])) {
      // enviamos un mensaje al admin del registro
    if(mail($admin_email,$subject,$message,$headers)){
        $sendemail= "el mensaje a $email ha sido enviado correctamente";
    }else{
        $sendemail= "error al enviar en mensaje";
    }
    // contestamos mediante json
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => "$nombre registrado exitosamente ",
        'email'=> $email,
        'delegacion'=>$delegacion,
        'sendemailadmin'=>$sendemail
    ]);
} else {
    echo json_encode(['message' => "Error al registrar $nombre"]);
}
?>