<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_proveedor = $_GET['id'];

// Eliminar el proveedor
$stmt = $conn->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
$stmt->bind_param("i", $id_proveedor);

if ($stmt->execute()) {
    header('Location: proveedores.php');
} else {
    echo "Error al eliminar el proveedor: " . $conn->error;
}
$stmt->close();
?>