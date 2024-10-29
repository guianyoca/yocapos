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

// Variables para paginación
$limit = 10; // Número de productos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$search = isset($_GET['search']) ? $_GET['search'] : "";

// Búsqueda de productos
if ($search) {
    $stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM productos WHERE id_comercio = ? AND (producto LIKE ? OR cod_barra LIKE ?)");
    $searchParam = '%' . $search . '%';
    $stmtCount->bind_param("iss", $id_comercio, $searchParam, $searchParam);
} else {
    $stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM productos WHERE id_comercio = ?");
    $stmtCount->bind_param("i", $id_comercio);
}
$stmtCount->execute();
$totalResults = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $limit);

$stmtCount->close();

// Obtener los productos de la base de datos con paginación y búsqueda
if ($search) {
    $searchParam = '%' . $search . '%';
    $stmtProductos = $conn->prepare("SELECT * FROM productos WHERE id_comercio = ? AND (producto LIKE ? OR cod_barra LIKE ?) LIMIT ?, ?");
    $stmtProductos->bind_param("issii", $id_comercio, $searchParam, $searchParam, $start, $limit);
} else {
    $stmtProductos = $conn->prepare("SELECT * FROM productos WHERE id_comercio = ? LIMIT ?, ?");
    $stmtProductos->bind_param("iii", $id_comercio, $start, $limit);
}
$stmtProductos->execute();
$resultProductos = $stmtProductos->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['producto'])) {
        $producto = $_POST['producto'];
        $cod_barra = $_POST['cod_barra'];
        $cantidad = $_POST['cantidad'];
        $medida = $_POST['medida'];
        $precio_costo = $_POST['precio_costo'];
        $precio_menor = $_POST['precio_menor'];
        $precio_mayor = $_POST['precio_mayor'];
        $cantidad_mayor = $_POST['cantidad_mayor'];

        // Insertar el producto en la base de datos
        $stmt = $conn->prepare("INSERT INTO productos (producto, cod_barra, cantidad, medida, precio_costo, precio_menor, precio_mayor, cantidad_mayor, id_comercio, id_usuario, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssisdddiii", $producto, $cod_barra, $cantidad, $medida, $precio_costo, $precio_menor, $precio_mayor, $cantidad_mayor, $id_comercio, $id_usuario);
        
        if ($stmt->execute()) {
            // Redirigir después de agregar el producto
            header('Location: productos.php?success=1');
        } else {
            echo "Error al agregar el producto: " . $conn->error;
        }
        $stmt->close();
    }

    if (isset($_POST['agregar_stock'])) {
        $id_producto = $_POST['id_producto'];
        $cantidad_agregar = $_POST['cantidad_agregar'];
        
        // Actualizar el stock del producto
        $stmtUpdate = $conn->prepare("UPDATE productos SET cantidad = cantidad + ? WHERE id_producto = ? AND id_comercio = ?");
        $stmtUpdate->bind_param("iii", $cantidad_agregar, $id_producto, $id_comercio);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Redirigir después de agregar el stock
        header('Location: productos.php?success=2');
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
                            <h6 class="text-white text-capitalize ps-3">Gestión de Productos</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_GET['success'] == 1 ? "Producto agregado exitosamente." : "Stock actualizado exitosamente."; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Buscador de productos -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <form action="productos.php" method="GET" class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar por nombre o código de barra" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-outline-primary mb-0" type="submit">Buscar</button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Tabla de productos -->
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Código de Barra</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Medida</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio Costo</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio Menor</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio Mayor</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad Mayor</th>
                                        <th class="text-secondary opacity-7">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success"><?php echo $producto['cantidad']; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo $producto['medida']; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">$<?php echo number_format($producto['precio_costo'], 2); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">$<?php echo number_format($producto['precio_menor'], 2); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">$<?php echo number_format($producto['precio_mayor'], 2); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo $producto['cantidad_mayor']; ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-link text-secondary mb-0" onclick="editarProducto(<?php echo $producto['id_producto']; ?>)">
                                                    <i class="material-icons text-sm me-2">edit</i>
                                                    Editar
                                                </button>
                                                <button class="btn btn-link text-danger mb-0" onclick="eliminarProducto(<?php echo $producto['id_producto']; ?>)">
                                                    <i class="material-icons text-sm me-2">delete</i>
                                                    Eliminar
                                                </button>
                                                <button class="btn btn-link text-info mb-0" onclick="agregarStock(<?php echo $producto['id_producto']; ?>)">
                                                    <i class="material-icons text-sm me-2">add</i>
                                                    Agregar Stock
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center">
                                <nav>
                                    <ul class="pagination">
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                                <a class="page-link" href="productos.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal para agregar/editar producto -->
        <div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productoModalLabel">Agregar Producto</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="productos.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="id_producto" name="id_producto">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Nombre del Producto</label>
                                <input type="text" class="form-control" id="producto" name="producto" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Código de Barra</label>
                                <input type="text" class="form-control" id="cod_barra" name="cod_barra" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <select class="form-control" id="medida" name="medida" required>
                                    <option value="">Seleccione una medida</option>
                                    <option value="Litros">Litros</option>
                                    <option value="Unidad">Unidad</option>
                                    <option value="Gramos">Gramos</option>
                                </select>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Precio Costo</label>
                                <input type="number" step="0.01" class="form-control" id="precio_costo" name="precio_costo" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Precio Menor</label>
                                <input  type="number" step="0.01" class="form-control" id="precio_menor" name="precio_menor" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Precio Mayor</label>
                                <input type="number" step="0.01" class="form-control" id="precio_mayor" name="precio_mayor" required>
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cantidad Mayor</label>
                                <input type="number" class="form-control" id="cantidad_mayor" name="cantidad_mayor" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn bg-gradient-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal para agregar stock -->
        <div class="modal fade" id="stockModal" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stockModalLabel">Agregar Stock</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="productos.php" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="stock_id_producto" name="id_producto">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cantidad a agregar</label>
                                <input type="number" class="form-control" id="cantidad_agregar" name="cantidad_agregar" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" name="agregar_stock" class="btn bg-gradient-primary">Agregar Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php include 'footer.php'; ?>
    </div>
</main>
<?php include 'complementos.php'; ?>
<script>
function editarProducto(id) {
    // Aquí deberías cargar los datos del producto y mostrarlos en el modal
    $('#productoModalLabel').text('Editar Producto');
    $('#id_producto').val(id);
    $('#productoModal').modal('show');
}

function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        // Aquí deberías enviar una solicitud para eliminar el producto
        window.location.href = 'eliminar_producto.php?id=' + id;
    }
}

function agregarStock(id) {
    $('#stock_id_producto').val(id);
    $('#stockModal').modal('show');
}

// Mostrar el modal de agregar producto
document.addEventListener('DOMContentLoaded', function() {
    var addProductBtn = document.createElement('button');
    addProductBtn.className = 'btn bg-gradient-primary';
    addProductBtn.innerHTML = '<i class="material-icons">add</i> Agregar Producto';
    addProductBtn.onclick = function() {
        $('#productoModalLabel').text('Agregar Producto');
        $('#id_producto').val('');
        $('#productoModal').modal('show');
    };
    document.querySelector('.card-header').appendChild(addProductBtn);
});
</script>
</body>
</html>