<?php
session_start();
include('conexion.php');

if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio'];
$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_GET['id'];

// Consultar los datos del producto
$stmtProducto = $conn->prepare("SELECT * FROM productos WHERE id_producto = ? AND id_comercio = ?");
$stmtProducto->bind_param("ii", $id_producto, $id_comercio);
$stmtProducto->execute();
$producto = $stmtProducto->get_result()->fetch_assoc();
$stmtProducto->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_nombre = $_POST['producto'];
    $cod_barra = $_POST['cod_barra'];
    $cantidad = $_POST['cantidad'];
    $medida = $_POST['medida'];
    $precio_costo = $_POST['precio_costo'];
    $precio_menor = $_POST['precio_menor'];
    $precio_mayor = $_POST['precio_mayor'];
    $cantidad_mayor = $_POST['cantidad_mayor'];

    // Actualizar el producto
    $stmt = $conn->prepare("UPDATE productos SET producto = ?, cod_barra = ?, cantidad = ?, medida = ?, precio_costo = ?, precio_menor = ?, precio_mayor = ?, cantidad_mayor = ? WHERE id_producto = ? AND id_comercio = ?");
    $stmt->bind_param("ssisdddiii", $producto_nombre, $cod_barra, $cantidad, $medida, $precio_costo, $precio_menor, $precio_mayor, $cantidad_mayor, $id_producto, $id_comercio);
    
    if ($stmt->execute()) {
        header('Location: productos.php');
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
    include 'head.php';
?>
<body class="g-sidenav-show  bg-gray-200">
<?php
    include 'aside.php';
?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php
        include 'navbar.php';
    ?>
    <div class="container-fluid py-4">
      <div class="row">
        
               <!-- Formulario para agregar productos -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header pb-0">
          <h6>Editar Producto</h6>
        </div>
        <div class="card-body">
        <form action="editar_producto.php?id=<?php echo $id_producto; ?>" method="POST">
  <div class="form-group">
    <label for="producto">Nombre del Producto</label>
    <input type="text" class="form-control" id="producto" name="producto" value="<?php echo $producto['producto']; ?>" required>
  </div>
  <div class="form-group">
    <label for="cod_barra">Código de Barra</label>
    <input type="text" class="form-control" id="cod_barra" name="cod_barra" value="<?php echo $producto['cod_barra']; ?>" required>
  </div>
  <div class="form-group">
    <label for="cantidad">Cantidad</label>
    <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo $producto['cantidad']; ?>" required>
  </div>
  <div class="form-group">
    <label for="medida">Medida</label>
                                <select class="form-control" name='medida'>
                                    <option value='Litros'>Litros</option>
                                    <option value='Unidad'>Unidad</option>
                                    <option value='Gramos'>Gramos</option>
                                    <option value='Unidad'>Unidad</option>
                                </select>
  </div>
  <div class="form-group">
    <label for="precio_costo">Precio Costo</label>
    <input type="number" class="form-control" id="precio_costo" name="precio_costo" value="<?php echo $producto['precio_costo']; ?>" required>
  </div>
  <div class="form-group">
    <label for="precio_menor">Precio Menor</label>
    <input type="number" class="form-control" id="precio_menor" name="precio_menor" value="<?php echo $producto['precio_menor']; ?>" required>
  </div>
  <div class="form-group">
    <label for="precio_mayor">Precio Mayor</label>
    <input type="number" class="form-control" id="precio_mayor" name="precio_mayor" value="<?php echo $producto['precio_mayor']; ?>" required>
  </div>
  <div class="form-group">
    <label for="cantidad_mayor">Cantidad Mayor</label>
    <input type="number" class="form-control" id="cantidad_mayor" name="cantidad_mayor" value="<?php echo $producto['cantidad_mayor']; ?>" required>
  </div>
  <button type="submit" class="btn btn-info">Actualizar Producto</button>
</form>

        </div>
      </div>
    </div>

      </div>
        <?php
            include 'footer.php';
        ?>
    </div>
  </main>
      </div>
    </div>
  </div>
  <?php
            include 'complementos.php';
        ?>
</body>
</html>