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

// Agregar un comercio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Insertar el comercio en la base de datos
    $stmt = $conn->prepare("INSERT INTO comercios (comercio, domicilio, telefono) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $direccion, $telefono);

    if ($stmt->execute()) {
        $success_message = "Comercio agregado exitosamente.";
    } else {
        $error_message = "Error al agregar el comercio: " . $conn->error;
    }
    $stmt->close();
}

// Obtener comercios
$stmtComercios = $conn->prepare("SELECT * FROM comercios");
$stmtComercios->execute();
$resultComercios = $stmtComercios->get_result();
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
                            <h6 class="text-white text-capitalize ps-3 mb-0">Gestión de Comercios</h6>
                            <button class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addBusinessModal">
                                <i class="material-icons text-sm">add</i> Agregar Comercio
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dirección</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Teléfono</th>
                                        <th class="text-secondary opacity-7">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($comercio = $resultComercios->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $comercio['comercio']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $comercio['domicilio']; ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $comercio['telefono']; ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit business" onclick="editBusiness(<?php echo $comercio['id_comercio']; ?>)">
                                                    Editar
                                                </a>
                                                <a href="javascript:;" class="text-danger font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Delete business" onclick="deleteBusiness(<?php echo $comercio['id_comercio']; ?>)">
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

<!-- Modal para agregar comercio -->
<div class="modal fade" id="addBusinessModal" tabindex="-1" role="dialog" aria-labelledby="addBusinessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBusinessModalLabel">Agregar Comercio</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="comercios.php" method="POST">
                <div class="modal-body">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Nombre del Comercio</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Teléfono</label>
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
function editBusiness(id) {
    // Implementar la lógica para editar comercio
    console.log('Editar comercio con ID:', id);
}

function deleteBusiness(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este comercio?')) {
        // Implementar la lógica para eliminar comercio
        console.log('Eliminar comercio con ID:', id);
    }
}
</script>
</body>
</html>