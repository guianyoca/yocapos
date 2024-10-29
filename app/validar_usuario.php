<?php
// Iniciar sesión
session_start();

// Incluir archivo de conexión
include('conexion.php');

// Obtener datos del formulario
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Verificar si los campos no están vacíos
if (!empty($usuario) && !empty($clave)) {
    // Preparar la consulta con parámetros
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar si la contraseña es correcta (compatibilidad con md5)
        if ($row['clave'] === md5($clave)) {
            // Migrar la contraseña a password_hash()
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE usuarios SET clave = ? WHERE id_usuario = ?");
            $update_stmt->bind_param("si", $clave_encriptada, $row['id_usuario']);
            $update_stmt->execute();

            // Iniciar sesión
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['id_comercio'] = $row['id_comercio'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['imagen'] = $row['imagen'];
            $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

            // Verificar que las sesiones están configuradas
            if (isset($_SESSION['id_usuario'])) {
                // Redirigir a principal.php
                header("Location: principal.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo establecer la sesión correctamente.";
                header("Location: index.php");
                exit();
            }
        } elseif (password_verify($clave, $row['clave'])) {
            // Si la contraseña ya está en formato password_hash()
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['id_comercio'] = $row['id_comercio'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['imagen'] = $row['imagen'];
            $_SESSION['tipo_usuario'] = $row['tipo_usuario'];

            // Verificar que las sesiones están configuradas
            if (isset($_SESSION['id_usuario'])) {
                // Redirigir a principal.php
                header("Location: principal.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo establecer la sesión correctamente.";
                header("Location: index.php");
                exit();
            }
        } else {
            // Contraseña incorrecta
            $_SESSION['error'] = "Clave incorrecta.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Usuario no encontrado
        $_SESSION['error'] = "Usuario incorrecto.";
        header("Location: index.php");
        exit();
    }
} else {
    // Redirigir a index.php si los campos están vacíos
    $_SESSION['error'] = "Todos los campos son obligatorios.";
    header("Location: index.php");
    exit();
}
?>