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

// Agregar un cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];

    // Insertar el cliente en la base de datos
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, telefono, id_comercio) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nombre, $telefono, $id_comercio);
    
    if ($stmt->execute()) {
        $success_message = "Cliente agregado exitosamente.";
    } else {
        $error_message = "Error al agregar el cliente: " . $conn->error;
    }
    $stmt->close();
}

// Obtener clientes
$stmtClientes = $conn->prepare("SELECT * FROM clientes WHERE id_comercio = ?");
$stmtClientes->bind_param("i", $id_comercio);
$stmtClientes->execute();
$resultClientes = $stmtClientes->get_result();
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
                        <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3 mb-0">Gestión de Clientes</h6>
                            <button class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="material-icons text-sm">add</i> Agregar Cliente
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success mx-3" role="alert">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger mx-3" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Teléfono</th>
                                        <th class="text-secondary opacity-7">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($cliente = $resultClientes->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $cliente['nombre']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $cliente['telefono']; ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit client" onclick="editClient(<?php echo $cliente['id_cliente']; ?>)">
                                                    Editar
                                                </a>
                                                <a href="javascript:;" class="text-danger font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Delete client" onclick="deleteClient(<?php echo $cliente['id_cliente']; ?>)">
                                                    Eliminar
                                                </a>
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

<!-- Modal para agregar cliente -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Agregar Cliente</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="clientes.php" method="POST">
                <div class="modal-body">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label  class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
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

<?php include 'complementos.php'; ?>
<script>
function editClient(id) {
    // Implementar la lógica para editar cliente
    console.log('Editar cliente con ID:', id);
}

function deleteClient(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
        // Implementar la lógica para eliminar cliente
        console.log('Eliminar cliente con ID:', id);
    }
}
</script>
</body>
</html>