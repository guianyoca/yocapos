<?php
session_start();
include('conexion.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) { // Cambié 'usuario' por 'id_usuario'
    header('Location: index.php');
    exit;
}

$id_comercio = $_SESSION['id_comercio']; // Id del comercio del usuario logueado
$nombre_usuario = $_SESSION['nombre'];

// Consultas a la base de datos con prepared statements
// Ventas Totales
$stmtVentasTotales = $conn->prepare("SELECT SUM(precio) AS total_ventas FROM ventas WHERE id_comercio = ?");
$stmtVentasTotales->bind_param("i", $id_comercio);
$stmtVentasTotales->execute();
$resultVentasTotales = $stmtVentasTotales->get_result();
$total_ventas = $resultVentasTotales->fetch_assoc()['total_ventas'] ?: '0';
$stmtVentasTotales->close();

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