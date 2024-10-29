<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Actualizar el comercio
    $stmt = $conn->prepare("UPDATE comercios SET nombre = ?, direccion = ?, telefono = ? WHERE id_comercio = ?");
    $stmt->bind_param("sssi", $nombre, $direccion, $telefono, $id_comercio);

    if ($stmt->execute()) {
        header('Location: comercios.php');
    } else {
        echo "Error al actualizar el comercio: " . $conn->error;
    }
    $stmt->close();
} else {
    // Obtener los datos del comercio a editar
    $stmtComercio = $conn->prepare("SELECT * FROM comercios WHERE id_comercio = ?");
    $stmtComercio->bind_param("i", $id_comercio);
    $stmtComercio->execute();
    $comercio = $stmtComercio->get_result()->fetch_assoc();
    $stmtComercio->close();
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
                        <h6>Editar Comercio</h6>
                    </div>
                    <div class="card-body">
                        <form action="editar_comercio.php?id=<?php echo $id_comercio; ?>" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre del Comercio</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $comercio['nombre']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $comercio['direccion']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $comercio['telefono']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-info">Actualizar Comercio</button>
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