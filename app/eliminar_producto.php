<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_producto = $_GET['id'];
$id_comercio = $_SESSION['id_comercio'];

// Eliminar el producto
$stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ? AND id_comercio = ?");
$stmt->bind_param("ii", $id_producto, $id_comercio);

if ($stmt->execute()) {
    header('Location: productos.php');
} else {
    echo "Error al eliminar el producto: " . $conn->error;
}
$stmt->close();
?>