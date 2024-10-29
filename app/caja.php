<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener los pedidos pendientes
$stmtPedidos = $conn->prepare("
    SELECT p.id_pedido, p.fecha, u.nombre AS vendedor, 
           SUM(d.cantidad * pr.precio_menor) AS total
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    JOIN detalle_pedido d ON p.id_pedido = d.id_pedido
    JOIN productos pr ON d.id_producto = pr.id_producto
    WHERE p.id_comercio = ? AND p.estado = 'pendiente'
    GROUP BY p.id_pedido
    ORDER BY p.fecha DESC
");
$stmtPedidos->bind_param("i", $id_comercio);
$stmtPedidos->execute();
$resultPedidos = $stmtPedidos->get_result();

// Procesar acciones sobre pedidos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cobrar_pedido'])) {
        $id_pedido = $_POST['id_pedido'];
        $monto_pagado = $_POST['monto_pagado'];
        
        // Actualizar el estado del pedido
        $stmtCobrar = $conn->prepare("UPDATE pedidos SET estado = 'cobrado', monto_pagado = ? WHERE id_pedido = ? AND id_comercio = ?");
        $stmtCobrar->bind_param("dii", $monto_pagado, $id_pedido, $id_comercio);
        $stmtCobrar->execute();
        
        header('Location: caja.php?success=cobrado');
        exit;
    } elseif (isset($_POST['anular_pedido'])) {
        $id_pedido = $_POST['id_pedido'];
        
        // Actualizar el estado del pedido
        $stmtAnular = $conn->prepare("UPDATE pedidos SET estado = 'anulado' WHERE id_pedido = ? AND id_comercio = ?");
        $stmtAnular->bind_param("ii", $id_pedido, $id_comercio);
        $stmtAnular->execute();
        
        header('Location: caja.php?success=anulado');
        exit;
    }
}

// Función para obtener el detalle de un pedido
function obtenerDetallePedido($conn, $id_pedido) {
    $stmt = $conn->prepare("
        SELECT p.producto, d.cantidad, p.precio, (d.cantidad * p.precio) AS subtotal
        FROM detalle_pedido d
        JOIN productos p ON d.id_producto = p.id_producto
        WHERE d.id_pedido = ?
    ");
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    return $stmt->get_result();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body class="g-sidenav-show bg-gray-200">
<?php include 'aside.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include 'navbar.php'; ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Caja - Pedidos Pendientes</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                Pedido <?php echo $_GET['success'] == 'cobrado' ? 'cobrado' : 'anulado'; ?> exitosamente.
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Pedido</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vendedor</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                        <th class="text-secondary opacity-7">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($pedido = $resultPedidos->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $pedido['id_pedido']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $pedido['fecha']; ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $pedido['vendedor']; ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">$<?php echo number_format($pedido['total'], 2); ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-info btn-sm" onclick="mostrarDetallePedido(<?php echo $pedido['id_pedido']; ?>)">Ver Detalle</button>
                                                <button class="btn btn-success btn-sm" onclick="mostrarFormCobro(<?php echo $pedido['id_pedido']; ?>, <?php echo $pedido['total']; ?>)">Cobrar</button>
                                                <form action="caja.php" method="POST" style="display: inline;">
                                                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                                    <button type="submit" name="anular_pedido" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de anular este pedido?')">Anular</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</main>

<!-- Modal para mostrar detalle del pedido -->
<div class="modal fade" id="modalDetallePedido" tabindex="-1" aria-labelledby="modalDetallePedidoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallePedidoLabel">Detalle del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detallePedidoContent">
                <!-- El contenido se cargará dinámicamente -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para cobrar pedido -->
<div class="modal fade" id="modalCobrarPedido" tabindex="-1" aria-labelledby="modalCobrarPedidoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCobrarPedidoLabel">Cobrar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCobrarPedido" action="caja.php" method="POST">
                    <input type="hidden" name="id_pedido" id="cobroIdPedido">
                    <div class="mb-3">
                        <label for="montoTotal" class="form-label">Monto Total</label>
                        <input type="text" class="form-control" id="montoTotal" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="montoPagado" class="form-label">Monto Pagado</label>
                        <input type="number" class="form-control" id="montoPagado" name="monto_pagado" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="cambio" class="form-label">Cambio</label>
                        <input type="text" class="form-control" id="cambio" readonly>
                    </div>
                    <button type="submit" name="cobrar_pedido" class="btn btn-primary">Confirmar Cobro</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'complementos.php'; ?>
<script>
function mostrarDetallePedido(idPedido) {
    // Hacer una petición AJAX para obtener el detalle del pedido
    fetch('obtener_detalle_pedido.php?id_pedido=' + idPedido)
        .then(response => response.text())
        .then(data => {
            document.getElementById('detallePedidoContent').innerHTML = data;
            var modal = new bootstrap.Modal(document.getElementById('modalDetallePedido'));
            modal.show();
        });
}

function mostrarFormCobro(idPedido, total) {
    document.getElementById('cobroIdPedido').value = idPedido;
    document.getElementById('montoTotal').value = total.toFixed(2);
    document.getElementById('montoPagado').value = total.toFixed(2);
    document.getElementById('cambio').value = '0.00';

    var modal = new bootstrap.Modal(document.getElementById('modalCobrarPedido'));
    modal.show();
}

document.getElementById('montoPagado').addEventListener('input', function() {
    var total = parseFloat(document.getElementById('montoTotal').value);
    var pagado = parseFloat(this.value);
    var cambio = pagado - total;
    document.getElementById('cambio').value = cambio.toFixed(2);
});

document.getElementById('formCobrarPedido').addEventListener('submit', function(e) {
    var total = parseFloat(document.getElementById('montoTotal').value);
    var pagado = parseFloat(document.getElementById('montoPagado').value);
    
    if (pagado < total) {
        e.preventDefault();
        alert('El monto pagado debe ser igual o mayor al monto total.');
    }
});
</script>
</body>
</html>