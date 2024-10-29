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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['finalizar_venta'])) {
    $id_cliente = $_POST['id_cliente'];
    $productos = $_POST['productos']; // Array de productos con id_producto, cantidad y precio

    foreach ($productos as $producto) {
        $id_producto = $producto['id_producto'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        $stmt = $conn->prepare("INSERT INTO ventas (id_cliente, id_comercio, id_usuario, id_producto, precio, cantidad, fecha, hora) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), CURTIME())");
        $stmt->bind_param("iiiiii", $id_cliente, $id_comercio, $id_usuario, $id_producto, $precio, $cantidad);
        
        if (!$stmt->execute()) {
            echo "Error al registrar la venta: " . $conn->error;
        }
        $stmt->close();
    }

    // Redirigir después de finalizar la venta
    header('Location: vendedor.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<?php include 'head.php'; ?>
<body class="g-sidenav-show bg-gray-200">
<?php include 'aside.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Formulario de búsqueda de productos -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Buscar Producto</h6>
                    </div>
                    <div class="card-body">
                        <form id="form-buscar-producto">
                            <div class="form-group">
                                <label for="buscar_producto">Buscar por Nombre o Código de Barra</label>
                                <input type="text" class="form-control" id="buscar_producto" name="buscar_producto" onkeyup="buscarProducto()" placeholder="Nombre o Código de Barra">
                            </div>
                        </form>
                        <div id="resultado-busqueda"></div> <!-- Aquí se mostrarán los resultados -->
                    </div>
                </div>
            </div>

            <!-- Carrito de productos -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Carrito de Productos</h6>
                    </div>
                    <div class="card-body">
                        <form action="vendedor.php" method="POST">
                            <div class="form-group">
                                <label for="id_cliente">Cliente</label>
                                <select class="form-control" id="id_cliente" name="id_cliente">
                                    <?php
                                    $clientes = $conn->query("SELECT * FROM clientes WHERE id_comercio = $id_comercio");
                                    while ($cliente = $clientes->fetch_assoc()) {
                                        echo "<option value='{$cliente['id_cliente']}'>{$cliente['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="carrito-productos">
                                    <!-- Productos agregados aparecerán aquí -->
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success" name="finalizar_venta">Finalizar Venta</button>
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
function buscarProducto() {
    var query = document.getElementById('buscar_producto').value;
    
    // Asegurarse de que la búsqueda solo se active con más de 2 caracteres
    if (query.length > 2) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "buscar_producto.php?q=" + encodeURIComponent(query), true);
        xhr.onload = function () {
            if (this.status == 200) {
                // Verificar si hay un error en la respuesta
                document.getElementById('resultado-busqueda').innerHTML = this.responseText;
            }
        }
        xhr.send();
    } else {
        // Limpiar los resultados si no hay suficiente texto
        document.getElementById('resultado-busqueda').innerHTML = "";
    }
}


function agregarAlCarrito(id_producto, nombre, precio) {
    var carrito = document.getElementById('carrito-productos');
    var fila = document.createElement('tr');
    fila.innerHTML = `<td>${nombre}</td>
                      <td><input type="number" name="productos[${id_producto}][cantidad]" value="1" min="1" class="form-control"></td>
                      <td>${precio}</td>
                      <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(this)">Eliminar</button></td>
                      <input type="hidden" name="productos[${id_producto}][id_producto]" value="${id_producto}">
                      <input type="hidden" name="productos[${id_producto}][precio]" value="${precio}">`;
    carrito.appendChild(fila);
}

function eliminarProducto(elemento) {
    elemento.parentElement.parentElement.remove();
}
</script>
</body>
</html>