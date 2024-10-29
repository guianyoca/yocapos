<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio'];

// Obtener el ID del cliente a editar
if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Consultar el cliente de la base de datos
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ? AND id_comercio = ?");
    $stmt->bind_param("ii", $id_cliente, $id_comercio);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();

    // Si no se encuentra el cliente, redirigir
    if (!$cliente) {
        header('Location: clientes.php');
        exit;
    }
} else {
    header('Location: clientes.php');
    exit;
}

// Actualizar los datos del cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];

    // Actualizar en la base de datos
    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, telefono = ? WHERE id_cliente = ? AND id_comercio = ?");
    $stmt->bind_param("ssii", $nombre, $telefono, $id_cliente, $id_comercio);

    if ($stmt->execute()) {
        header('Location: clientes.php');
    } else {
        echo "Error al actualizar el cliente: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body class="g-sidenav-show bg-gray-200">
<?php include 'aside.php'; ?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <?php include 'navbar.php'; ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Editar Cliente</h6>
                    </div>
                    <div class="card-body">
                        <form action="editar_cliente.php?id=<?php echo $id_cliente; ?>" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $cliente['telefono']; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-info">Actualizar Cliente</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</main>
<?php include 'complementos.php'; ?>
</body>
</html>