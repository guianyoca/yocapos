<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$id_usuario = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $imagen = $_POST['imagen'];

    // Verificar si se cambió la clave
    if (!empty($_POST['clave'])) {
        $clave = password_hash($_POST['clave'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, clave = ?, tipo_usuario = ?, imagen = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssssi", $nombre, $usuario, $clave, $tipo_usuario, $imagen, $id_usuario);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, usuario = ?, tipo_usuario = ?, imagen = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssssi", $nombre, $usuario, $tipo_usuario, $imagen, $id_usuario);
    }

    if ($stmt->execute()) {
        header('Location: usuarios.php');
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }
    $stmt->close();
} else {
    // Obtener los datos del usuario a editar
    $stmtUsuario = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmtUsuario->bind_param("i", $id_usuario);
    $stmtUsuario->execute();
    $usuario = $stmtUsuario->get_result()->fetch_assoc();
    $stmtUsuario->close();
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
                        <h6>Editar Usuario</h6>
                    </div>
                    <div class="card-body">
                        <form action="editar_usuario.php?id=<?php echo $id_usuario; ?>" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="usuario">Usuario</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $usuario['usuario']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="clave">Clave</label>
                                <input type="password" class="form-control" id="clave" name="clave" placeholder="Dejar vacío para no cambiar">
                            </div>
                            <div class="form-group">
                                <label for="imagen">URL Imagen</label>
                                <input type="text" class="form-control" id="imagen" name="imagen" value="<?php echo $usuario['imagen']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo_usuario">Tipo de Usuario</label>
                                <select class="form-control" id="tipo_usuario" name="tipo_usuario" required>
                                    <option value="1" <?php echo $usuario['tipo_usuario'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                                    <option value="2" <?php echo $usuario['tipo_usuario'] == 2 ? 'selected' : ''; ?>>Empleado</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Actualizar Usuario</button>
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