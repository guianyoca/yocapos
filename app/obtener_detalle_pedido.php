<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    exit('No autorizado');
}

$id_comercio = $_SESSION['id_comercio'];

if (!isset($_GET['id_pedido'])) {
    exit('ID de pedido no proporcionado');
}

$id_pedido = $_GET['id_pedido'];

// Obtener el detalle del pedido
$stmt = $conn->prepare("
    SELECT p.producto, d.cantidad, p.precio_menor, (d.cantidad * p.precio_menor) AS subtotal
    FROM detalle_pedido d
    JOIN productos p ON d.id_producto = p.id_producto
    WHERE d.id_pedido = ? AND p.id_comercio = ?
");
$stmt->bind_param("ii", $id_pedido, $id_comercio);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<table class="table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['producto']); ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td>$<?php echo number_format($row['precio_menor'], 2); ?></td>
                <td>$<?php echo number_format($row['subtotal'], 2); ?></td>
            </tr>
            <?php $total += $row['subtotal']; ?>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-right">Total:</th>
            <th>$<?php echo number_format($total, 2); ?></th>
        </tr>
    </tfoot>
</table>