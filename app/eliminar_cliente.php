<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio'];

// Obtener el ID del cliente a eliminar
if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Eliminar el cliente de la base de datos
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ? AND id_comercio = ?");
    $stmt->bind_param("ii", $id_cliente, $id_comercio);

    if ($stmt->execute()) {
        header('Location: clientes.php');
    } else {
        echo "Error al eliminar el cliente: " . $conn->error;
    }

    $stmt->close();
} else {
    header('Location: clientes.php');
    exit;
}
?>