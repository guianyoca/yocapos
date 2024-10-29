<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $email = htmlspecialchars(trim($_POST['email']));
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    // Validar el correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg_type'] = 'danger';
        $_SESSION['msg'] = 'Correo electrónico no válido.';
        header('Location: index.php');
        exit();
    }

    // Asunto y cuerpo del correo
    $to = "guianyoca@gmail.com";
    $subject = "Nuevo mensaje de contacto";
    $body = "Nombre: $nombre\nTeléfono: $telefono\nCorreo: $email\nMensaje:\n$mensaje";
    $headers = "From: $email\r\n";

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        $_SESSION['msg_type'] = 'success';
        $_SESSION['msg'] = 'Mensaje enviado exitosamente.';
    } else {
        $_SESSION['msg_type'] = 'danger';
        $_SESSION['msg'] = 'Hubo un error al enviar el mensaje. Intenta de nuevo.';
    }
    header('Location: index.php');
    exit();
} else {
    $_SESSION['msg_type'] = 'danger';
    $_SESSION['msg'] = 'Método de solicitud no válido.';
    header('Location: index.php');
    exit();
}
?>