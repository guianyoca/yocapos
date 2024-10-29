<?php
// Obtener id_comercio de la sesión
$id_comercio = $_SESSION['id_comercio'];

// Consulta para obtener logo y nombre del comercio
$query_comercio = "SELECT logo, comercio FROM comercios WHERE id_comercio = ?";
$stmt = $conn->prepare($query_comercio);
$stmt->bind_param("i", $id_comercio);
$stmt->execute();
$result_comercio = $stmt->get_result();

if ($result_comercio->num_rows > 0) {
    $comercio = $result_comercio->fetch_assoc();
    $logo = $comercio['logo'];
    $nombre_comercio = $comercio['comercio'];
} else {
    $logo = 'assets/img/default-logo.png'; // Logo por defecto si no se encuentra
    $nombre_comercio = 'YOCAPOS'; // Nombre por defecto
}

// Obtener la página actual
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="#" target="_blank">
            <img src="<?php echo $logo; ?>" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white"><?php echo $nombre_comercio; ?></span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($pagina_actual == 'principal.php') ? 'active bg-gradient-info' : ''; ?>" href="principal.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Productos, solo visible para usuarios con tipo_usuario 1 -->
            <?php if ($_SESSION['tipo_usuario'] == 1): ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($pagina_actual == 'productos.php') ? 'active bg-gradient-info' : ''; ?>" href="productos.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">view_in_ar</i>
                        </div>
                        <span class="nav-link-text ms-1">Productos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($pagina_actual == 'preventa.php') ? 'active bg-gradient-info' : ''; ?>" href="preventa.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">shopping_cart</i>
                        </div>
                        <span class="nav-link-text ms-1">Preventa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($pagina_actual == 'caja.php') ? 'active bg-gradient-info' : ''; ?>" href="caja.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">point_of_sale</i>
                        </div>
                        <span class="nav-link-text ms-1">Caja</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Opción 2 -->
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($pagina_actual == 'clientes.php') ? 'active bg-gradient-info' : ''; ?>" href="clientes.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">groups_2</i>
                    </div>
                    <span class="nav-link-text ms-1">Clientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($pagina_actual == 'comercios.php') ? 'active bg-gradient-info' : ''; ?>" href="comercios.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Comercios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($pagina_actual == 'proveedores.php') ? 'active bg-gradient-info' : ''; ?>" href="proveedores.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">local_shipping</i>
                    </div>
                    <span class="nav-link-text ms-1">Proveedores</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo ($pagina_actual == 'usuarios.php') ? 'active bg-gradient-info' : ''; ?>" href="usuarios.php">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">contacts</i>
                    </div>
                    <span class="nav-link-text ms-1">Usuarios</span>
                </a>
            </li>
        </ul>
    </div>
</aside>