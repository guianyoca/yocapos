<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio'];

// Agregar un usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $clave = password_hash($_POST['clave'], PASSWORD_BCRYPT);
    $imagen = $_POST['imagen'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $fecha = date('Y-m-d');

    // Insertar el usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (id_comercio, nombre, usuario, clave, imagen, tipo_usuario, fecha) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssis", $id_comercio, $nombre, $usuario, $clave, $imagen, $tipo_usuario, $fecha);

    if ($stmt->execute()) {
        $success_message = "Usuario agregado exitosamente.";
    } else {
        $error_message = "Error al agregar el usuario: " . $conn->error;
    }
    $stmt->close();
}

// Obtener usuarios
$stmtUsuarios = $conn->prepare("SELECT * FROM usuarios WHERE id_comercio = ?");
$stmtUsuarios->bind_param("i", $id_comercio);
$stmtUsuarios->execute();
$resultUsuarios = $stmtUsuarios->get_result();
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
                            <h6 class="text-white text-capitalize ps-3 mb-0">Gestión de Usuarios</h6>
                            <button class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="material-icons text-sm">add</i> Agregar Usuario
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Usuario</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipo</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                                        <th class="text-secondary opacity-7">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="<?php echo $usuario['imagen']; ?>" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo $usuario['nombre']; ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo $usuario['usuario']; ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-<?php echo $usuario['tipo_usuario'] == 1 ? 'success' : 'info'; ?>">
                                                    <?php echo $usuario['tipo_usuario'] == 1 ? 'Administrador' : 'Empleado'; ?>
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo $usuario['fecha']; ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user" onclick="editUser(<?php echo $usuario['id_usuario']; ?>)">
                                                    Editar
                                                </a>
                                                <a href="javascript:;" class="text-danger font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Delete user" onclick="deleteUser(<?php echo $usuario['id_usuario']; ?>)">
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

<!-- Modal para agregar usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="usuarios.php" method="POST">
                <div class="modal-body">
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">Clave</label>
                        <input type="password" class="form-control" name="clave" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <label class="form-label">URL Imagen</label>
                        <input type="text" class="form-control" name="imagen" required>
                    </div>
                    <div class="input-group input-group-outline my-3">
                        <select class="form-control" name="tipo_usuario" required>
                            <option value="">Seleccione el tipo de usuario</option>
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                        </select>
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
function editUser(id) {
    // Implementar la lógica para editar usuario
    console.log('Editar usuario con ID:', id);
}

function deleteUser(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        // Implementar la lógica para eliminar usuario
        console.log('Eliminar usuario con ID:', id);
    }
}
</script>
</body>
</html>