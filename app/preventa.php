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

// Inicializar la variable de búsqueda
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Preparar la consulta para buscar productos
if (!empty($busqueda)) {
    $stmtProductos = $conn->prepare("SELECT id_producto, producto, precio_menor, precio_mayor, cantidad_mayor, cod_barra FROM productos WHERE id_comercio = ? AND (producto LIKE ? OR cod_barra LIKE ?)");
    $busquedaParam = "%$busqueda%";
    $stmtProductos->bind_param("iss", $id_comercio, $busquedaParam, $busquedaParam);
} else {
    $stmtProductos = $conn->prepare("SELECT id_producto, producto, precio_menor, precio_mayor, cantidad_mayor, cod_barra FROM productos WHERE id_comercio = ?");
    $stmtProductos->bind_param("i", $id_comercio);
}

$stmtProductos->execute();
$resultProductos = $stmtProductos->get_result();

// Procesar el formulario de preventa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_pedido'])) {
        $productos = $_POST['productos'];
        $cantidades = $_POST['cantidades'];
        
        // Crear el pedido
        $stmtPedido = $conn->prepare("INSERT INTO pedidos (id_comercio, id_usuario, estado, fecha) VALUES (?, ?, 'pendiente', NOW())");
        $stmtPedido->bind_param("ii", $id_comercio, $id_usuario);
        $stmtPedido->execute();
        $id_pedido = $conn->insert_id;
        
        // Insertar los detalles del pedido
        $stmtDetalle = $conn->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($productos); $i++) {
            if ($cantidades[$i] > 0) {
                $stmtDetalle->bind_param("iii", $id_pedido, $productos[$i], $cantidades[$i]);
                $stmtDetalle->execute();
            }
        }
        
        header('Location: preventa.php?success=1');
        exit;
    }
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
                            <h6 class="text-white text-capitalize ps-3">Crear Pedido</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                Pedido creado exitosamente.
                            </div>
                        <?php endif; ?>
                        
                        <!-- Buscador de productos -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <form action="preventa.php" method="GET" class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar por nombre o código de barra" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>">
                                    <button class="btn btn-outline-primary mb-0" type="submit">Buscar</button>
                                </form>
                            </div>
                        </div>
                        
                        <form action="preventa.php" method="POST" id="formPedido">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Código de Barra</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio Mayor</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad Mayor</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subtotal</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="productosTable">
                                        <?php while ($producto = $resultProductos->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($producto['producto']); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($producto['cod_barra']); ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        $<span class="precio-menor"><?php echo number_format($producto['precio_menor'], 2); ?></span>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">$<?php echo number_format($producto['precio_mayor'], 2); ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?php echo $producto['cantidad_mayor']; ?></p>
                                                </td>
                                                <td>
                                                    <input type="number" name="cantidades[]" class="form-control cantidad" min="0" value="0" onchange="actualizarSubtotal(this)">
                                                    <input type="hidden" name="productos[]" value="<?php echo $producto['id_producto']; ?>">
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0 subtotal">$0.00</p>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" onclick="agregarAlPedido(this)">Agregar</button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 offset-md-3 text-center">
                                    <h4>Total: $<span id="totalPedido">0.00</span></h4>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-info" onclick="mostrarDetalleFinal()">Ver detalle final</button>
                                    <button type="submit" name="crear_pedido" class="btn btn-primary">Crear Pedido</button>
                                    <button type="button" class="btn btn-secondary" onclick="imprimirPresupuesto()">Imprimir Presupuesto</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</main>
<?php include 'complementos.php'; ?>
<script>
function actualizarSubtotal(input) {
    var row = input.closest('tr');
    var cantidad = parseInt(input.value);
    var precioMenor = parseFloat(row.querySelector('.precio-menor').textContent.replace(/[^0-9.]/g, ''));
    var precioMayor = parseFloat(row.querySelector('td:nth-child(4)').textContent.replace(/[^0-9.]/g, ''));
    var cantidadMayor = parseInt(row.querySelector('td:nth-child(5)').textContent);
    
    var precio = cantidad >= cantidadMayor ? precioMayor : precioMenor;
    var subtotal = cantidad * precio;
    
    row.querySelector('.subtotal').textContent = '$' + subtotal.toFixed(2);
    
    actualizarTotal();
}

function actualizarTotal() {
    var total = 0;
    document.querySelectorAll('.subtotal').forEach(function(el) {
        total += parseFloat(el.textContent.replace(/[^0-9.]/g, '')) || 0;
    });
    document.getElementById('totalPedido').textContent = total.toFixed(2);
}

function agregarAlPedido(btn) {
    var row = btn.closest('tr');
    var cantidadInput = row.querySelector('.cantidad');
    var cantidad = parseInt(cantidadInput.value);
    
    if (cantidad > 0) {
        cantidadInput.style.backgroundColor = '#d4edda';
        btn.textContent = 'Agregado';
        btn.classList.remove('btn-info');
        btn.classList.add('btn-success');
        actualizarSubtotal(cantidadInput);
        actualizarTotal();
    } else {
        alert('Por favor, ingrese una cantidad válida.');
    }
}

document.getElementById('formPedido').addEventListener('submit', function(e) {
    var cantidades = document.getElementsByName('cantidades[]');
    var hayProductos = false;
    
    for (var i = 0; i < cantidades.length; i++) {
        if (parseInt(cantidades[i].value) > 0) {
            hayProductos = true;
            break;
        }
    }
    
    if (!hayProductos) {
        e.preventDefault();
        alert('Por favor, agregue al menos un producto al pedido.');
    }
});

function imprimirPresupuesto() {
    var contenido = '<h2>Presupuesto</h2>';
    contenido += '<table border="1"><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>';
    
    document.querySelectorAll('#productosTable tr').forEach(function(row) {
        var cantidad = parseInt(row.querySelector('.cantidad').value);
        if (cantidad > 0) {
            var producto = row.querySelector('h6').textContent;
            var subtotal = row.querySelector('.subtotal').textContent;
            var precio = parseFloat(subtotal.replace('$', '')) / cantidad;
            contenido += '<tr><td>' + producto + '</td><td>' + cantidad + '</td><td>$' + precio.toFixed(2) + '</td><td>' + subtotal + '</td></tr>';
        }
    });
    
    contenido += '</table>';
    contenido += '<p>Total: $' + document.getElementById('totalPedido').textContent + '</p>';
    
    var ventana = window.open('', '_blank');
    ventana.document.write('<html><head><title>Presupuesto</title></head><body>');
    ventana.document.write(contenido);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.print();
}

function mostrarDetalleFinal() {
    var contenido = '<table class="table"><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr></thead><tbody>';
    var total = 0;
    
    document.querySelectorAll('#productosTable tr').forEach(function(row) {
        var cantidad = parseInt(row.querySelector('.cantidad').value);
        if (cantidad > 0) {
            var producto = row.querySelector('h6').textContent;
            var subtotal = parseFloat(row.querySelector('.subtotal').textContent.replace(/[^0-9.]/g, ''));
            var precioUnitario = subtotal / cantidad;
            
            contenido += '<tr><td>' + producto + '</td><td>' + cantidad + '</td><td>$' + precioUnitario.toFixed(2) + '</td><td>$' + subtotal.toFixed(2) + '</td></tr>';
            total += subtotal;
        }
    });
    
    contenido += '</tbody></table>';
    contenido += '<h4 class="text-end">Total: $' + total.toFixed(2) + '</h4>';
    
    document.getElementById('detalleFinalContenido').innerHTML = contenido;
    
    var modal = new bootstrap.Modal(document.getElementById('detalleFinaModal'));
    modal.show();
}
</script>

<!-- Modal para mostrar el detalle final -->
<div class="modal fade" id="detalleFinaModal" tabindex="-1" aria-labelledby="detalleFinaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalleFinaModalLabel">Detalle Final del Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="detalleFinalContenido">
        <!-- El contenido se llenará dinámicamente con JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('formPedido').submit();">Confirmar Pedido</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>