<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_GET['id'];

// Eliminar el comercio
$stmt = $conn->prepare("DELETE FROM comercios WHERE id_comercio = ?");
$stmt->bind_param("i", $id_comercio);

if ($stmt->execute()) {
    header('Location: comercios.php');
} else {
    echo "Error al eliminar el comercio: " . $conn->error;
}
$stmt->close();
?>