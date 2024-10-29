<?php
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener las notificaciones no leídas
$query_notificaciones = "SELECT n.*, u.imagen FROM notificaciones n JOIN usuarios u ON n.id_usuario_envia = u.id_usuario WHERE n.estado = 0";
$result_notificaciones = mysqli_query($conn, $query_notificaciones);
$cantidad_no_leidas = mysqli_num_rows($result_notificaciones);

// Obtener el nombre del archivo actual
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Definir el título de la página según el archivo
$titulo_pagina = '';
switch ($pagina_actual) {
    case 'principal.php':
        $titulo_pagina = 'Dashboard';
        break;
    case 'productos.php':
        $titulo_pagina = 'Productos';
        break;
    case 'opcion2.php':
        $titulo_pagina = 'Opción 2';
        break;
    // Puedes agregar más casos según las páginas que tengas
    default:
        $titulo_pagina = 'Dashboard'; // Valor por defecto
        break;
}
?>

<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <h6 class="font-weight-bolder mb-0"><?php echo $titulo_pagina; ?></h6>
    </nav>

    <!-- Botón de hamburguesa para el despliegue del aside -->
    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
      <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
        <div class="sidenav-toggler-inner">
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
        </div>
      </a>
    </li>

    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
      <ul class="navbar-nav justify-content-end">

        <!-- Notificaciones -->
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell cursor-pointer"></i>
            <?php if ($cantidad_no_leidas > 0): ?>
              <span class="badge bg-danger rounded-circle text-white" style="position: absolute; top: 0; right: 10px;"><?php echo $cantidad_no_leidas; ?></span>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <?php if ($cantidad_no_leidas > 0): ?>
              <?php while ($notificacion = mysqli_fetch_assoc($result_notificaciones)): ?>
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="<?php echo $notificacion['imagen']; ?>" class="avatar avatar-sm me-3">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold"><?php echo $notificacion['titulo']; ?></span>
                          <?php echo $notificacion['mensaje']; ?>
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          <?php echo $notificacion['fecha']; ?>
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              <?php endwhile; ?>
            <?php else: ?>
              <li class="mb-2 text-center">No hay notificaciones</li>
            <?php endif; ?>
          </ul>
        </li>

        <!-- Perfil del Usuario y Cerrar Sesión -->
        <li class="nav-item d-flex align-items-center">
          <a href="perfil_usuario.php?id_usuario=<?php echo $id_usuario;?>" class="nav-link text-body font-weight-bold px-0">
            <div class="my-auto d-flex align-items-center">
              <img src="<?php echo $_SESSION['imagen']; ?>" class="avatar avatar-sm bg-gradient-dark me-3">
              <span><?php echo $_SESSION['nombre']; ?></span>
            </div>
          </a>
          <a href="logout.php" class="nav-link text-body font-weight-bold px-0 ms-3" style="color: red;">
            <span class="d-sm-inline d-none">Cerrar Sesión</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->