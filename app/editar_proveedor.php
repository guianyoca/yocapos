<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_proveedor = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Actualizar el proveedor
    $stmt = $conn->prepare("UPDATE proveedores SET nombre = ?, telefono = ?, direccion = ? WHERE id_proveedor = ?");
    $stmt->bind_param("sssi", $nombre, $telefono, $direccion, $id_proveedor);

    if ($stmt->execute()) {
        header('Location: proveedores.php');
    } else {
        echo "Error al actualizar el proveedor: " . $conn->error;
    }
    $stmt->close();
} else {
    // Obtener los datos del proveedor a editar
    $stmtProveedor = $conn->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
    $stmtProveedor->bind_param("i", $id_proveedor);
    $stmtProveedor->execute();
    $proveedor = $stmtProveedor->get_result()->fetch_assoc();
    $stmtProveedor->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
    include 'head.php';
?>
<body class="g-sidenav-show bg-gray-200">
<?php
    include 'aside.php';
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <?php
        include 'navbar.php';
    ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Editar Proveedor</h6>
                    </div>
                    <div class="card-body">
                        <form action="editar_proveedor.php?id=<?php echo $id_proveedor; ?>" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre del Proveedor</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $proveedor['nombre']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $proveedor['telefono']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $proveedor['direccion']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-info">Actualizar Proveedor</button>
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
<?php
    include 'complementos.php';
?>
</body>
</html>