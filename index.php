<?php
session_start(); // Inicia la sesión
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>YocaPos - Sistema de Ventas</title>
  
  <!-- Material Kit 2 CSS -->
  <link href="assets/css/material-kit.min.css" rel="stylesheet">

  <!-- Material Icons CSS -->
  <link href="assets/css/material-icon.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>

<div class="container mt-4">
        <?php
        // Mostrar el mensaje si existe
        if (isset($_SESSION['msg'])) {
            $msg_type = $_SESSION['msg_type'];
            $msg = $_SESSION['msg'];
            echo "<div class='alert alert-$msg_type' role='alert'>
                    $msg
                  </div>";
            // Limpiar el mensaje después de mostrarlo
            unset($_SESSION['msg']);
            unset($_SESSION['msg_type']);
        }
        ?>
    </div>
  
  <!-- Navbar Light -->
  <div class="container position-sticky z-index-sticky top-0 bg-gradient-white">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg blur border-radius-xl position-absolute my-3 top-0 border-bottom py-3 z-index-3 shadow my-3 py-2 start-0 end-0 mx-4">
          <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="#" rel="tooltip" title="YocaPos - Sistema de ventas" data-placement="bottom">
              YocaPos
            </a>

            <!-- Mobile button -->
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
              <ul class="navbar-nav navbar-nav-hover mx-auto">
                <li class="nav-item mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" href="#">
                    Inicio
                  </a>
                </li>

                <li class="nav-item mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" href="#modulos">
                    Módulos
                  </a>
                </li>

                <li class="nav-item mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" href="#video">
                    Saber Mas
                  </a>
                </li>

                <li class="nav-item mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" href="#contacto">
                    Contacto
                  </a>
                </li>
              </ul>

              <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                  <a href="app/" class="btn btn-sm bg-gradient-info mb-0 me-1" role="button">Iniciar Sesión</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
  
  <!-- Header Section -->
  <header class="header-rounded-images mt-5">
    <div class="page-header min-vh-90">
      <img class="position-absolute fixed-top ms-auto w-50 h-100 z-index-0 d-none d-sm-none d-md-block border-radius-section border-top-end-radius-0 border-top-start-radius-0 border-bottom-end-radius-0" src="https://images.unsplash.com/photo-1538681105587-85640961bf8b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" alt="image" loading="lazy">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 d-flex">
            <div class="card card-body blur text-md-start text-center px-sm-5 shadow-lg mt-sm-5 py-sm-5">
              <h2 class="text-dark mb-4">Elige lo mejor</h2>
              <p class="lead text-dark pe-md-5 me-md-5">
                Encuentra la mejor solución para gestionar tu negocio con YocaPos.
              </p>
              <div class="buttons">
                <button type="button" class="btn bg-gradient-info mt-4">Contáctanos</button>
                <button type="button" class="btn btn-outline-secondary mt-4 ms-2">Leer Más</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- -------- START Features w/ 4 cols w/ white icon on colored background & title & text -------- -->
<section class="py-6" id='modulos'>
  <div class="container">
    <div class="row justify-content-start">
    <h2 class="text-dark mb-4">Módulos</h2>
      <div class="col-md-6">
        <!-- Stock -->
        <div class="p-3 text-start border-radius-lg">
          <div class="icon icon-shape icon-md bg-gradient-info shadow-info text-center">
            <i class="material-icons opacity-10">inventory_2</i>
          </div>
          <h5 class="mt-3">Stock</h5>
          <p class="w-75">Gestiona el inventario de productos de forma eficiente y en tiempo real.</p>
        </div>
        
        <!-- Venta -->
        <div class="p-3 text-start border-radius-lg">
          <div class="icon icon-shape icon-md bg-gradient-info shadow-info text-center">
            <i class="material-icons opacity-10">point_of_sale</i>
          </div>
          <h5 class="mt-3">Venta</h5>
          <p class="w-75">Realiza y controla las ventas de manera ágil y segura.</p>
        </div>
      </div>

      <div class="col-md-6">
        <!-- Usuarios -->
        <div class="p-3 text-start border-radius-lg">
          <div class="icon icon-shape icon-md bg-gradient-info shadow-info text-center">
            <i class="material-icons opacity-10">people</i>
          </div>
          <h5 class="mt-3">Usuarios</h5>
          <p class="w-75">Administra a los usuarios que tendrán acceso al sistema con distintos roles.</p>
        </div>

        <!-- Clientes -->
        <div class="p-3 text-start border-radius-lg">
          <div class="icon icon-shape icon-md bg-gradient-info shadow-info text-center">
            <i class="material-icons opacity-10">person</i>
          </div>
          <h5 class="mt-3">Clientes</h5>
          <p class="w-75">Lleva un registro detallado de tus clientes y su historial de compras.</p>
        </div>
        
        <!-- Proveedores -->
        <div class="p-3 text-start border-radius-lg">
          <div class="icon icon-shape icon-md bg-gradient-info shadow-info text-center">
            <i class="material-icons opacity-10">local_shipping</i>
          </div>
          <h5 class="mt-3">Proveedores</h5>
          <p class="w-75">Gestiona y controla a tus proveedores y sus productos.</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- -------- END Features w/ 4 cols w/ white icon on colored background & title & text -------- -->

<!-- -------- START Video Section -------- -->
<section class="py-6" id='video'>
  <div class="container">
    <h2 class="text-dark mb-4">Echa un Vistazo</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="position-relative">
          <video class="w-100" height="400" controls>
            <source src="assets/video/video.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
          </video>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- -------- END Video Section -------- -->

<!-- -------- START PRE-FOOTER 7 w/ TEXT AND 2 BUTTONS ------- -->
<section class="my-10 py-5 bg-gradient-dark position-relative overflow-hidden">
  <img src="assets/img/shapes/waves-white.svg" alt="pattern-lines" class="position-absolute start-0 top-0 w-100 opacity-1">
  <div class="container position-relative z-index-2">
    <div class="row">
      <div class="col-lg-5 col-md-8 m-auto text-start">
        <h5 class="text-white mb-lg-0 mb-5">
          La gestión eficiente es clave para el éxito de tu negocio. Con YocaPos, optimiza tus ventas y mejora la experiencia de tus clientes.
        </h5>
      </div>
      <div class="col-lg-6 m-auto">
        <div class="row">
          <div class="col-sm-4 col-6 ps-sm-0 ms-auto">
            <button type="button" class="btn bg-gradient-info mb-0 ms-lg-3 ms-sm-2 mb-sm-0 mb-2 me-auto w-100 d-block">Comienza Ahora</button>
          </div>
          <div class="col-sm-4 col-6 ps-sm-0 me-lg-0 me-auto">
            <button type="button" class="btn btn-white mb-0 ms-lg-3 ms-sm-2 mb-sm-0 mb-2 me-auto w-100 d-block">Nuestra Historia</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- -------- END PRE-FOOTER 7 w/ TEXT AND 2 BUTTONS ------- -->

<section class="py-7" id="contacto">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-8 mx-auto text-center">
        <div class="ms-3 mb-md-5">
          <h3>Contáctanos</h3>
          <p>
            Si tienes alguna pregunta o deseas más información sobre YocaPos, por favor envíanos un correo a <a href="mailto:hello@yocapos.com">hello@yocapos.com</a> o utiliza nuestro formulario de contacto.
          </p>
          <p>
            También puedes enviarnos un mensaje directamente por WhatsApp:
          </p>
          <a href="https://wa.me/542645457386" target="_blank" class="btn bg-gradient-success mt-3">
            <i class="material-icons">chat</i> Enviar Mensaje por WhatsApp
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="card card-plain">
          <form id="contact-form" method="post" action="send_email.php" autocomplete="off">
            <div class="card-body">
              <div class="row">
                <!-- Input Nombre Completo -->
                <div class="col-md-6">
                  <div class="input-group input-group-outline mb-4">
                    <label class="form-label">Nombre Completo</label>
                    <input class="form-control" aria-label="Full Name" type="text" name='nombre' required>
                  </div>
                </div>
                <!-- Input Número de Teléfono -->
                <div class="col-md-6 ps-md-2">
                  <div class="input-group input-group-outline mb-4">
                    <label class="form-label">Número de Teléfono</label>
                    <input class="form-control" aria-label="Phone Number" type="tel" name='telefono' required>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Input Correo Electrónico -->
                <div class="col-md-12">
                  <div class="input-group input-group-outline mb-4">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name='email' required>
                  </div>
                </div>
              </div>
              <!-- Área de Texto para el Mensaje -->
              <div class="input-group input-group-outline mb-4">
                <label class="form-label">¿En qué podemos ayudarte?</label>
                <textarea name="mensaje" class="form-control" id="message" rows="6" required></textarea>
              </div>
              <!-- Botón de Envío -->
              <div class="row">
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn bg-gradient-info mt-4">Enviar Mensaje</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- -------- START FOOTER w/ DARK BACKGROUND ------- -->
<footer class="footer py-5 my-9 bg-gradient-dark position-relative overflow-hidden">
  <img src="assets/img/shapes/waves-white.svg" alt="waves-white" class="position-absolute start-0 top-0 w-100 opacity-6">

  <div class="container position-relative z-index-1">
    <div class="row">
      <div class="col-lg-4 me-auto mb-lg-0 mb-4 text-lg-start text-center">
        <h6 class="text-white font-weight-bolder text-uppercase mb-lg-4 mb-3">YocaPos</h6>
        <p class="text-sm text-white opacity-8 mb-0">
          Copyright © <script>document.write(new Date().getFullYear())</script> YocaTech.
        </p>
      </div>

      <div class="col-lg-6 ms-auto text-lg-end text-center">
        <p class="mb-5 text-lg text-white font-weight-bold">
          Optimiza tu negocio con YocaPos. La solución que necesitas para crecer.
        </p>
        <a href="https://www.instagram.com" target="_blank" class="text-white me-xl-4 me-4 opacity-8">
          <span class="fab fa-instagram fa-3x"></span>
        </a>
        <a href="https://www.facebook.com" target="_blank" class="text-white opacity-8">
          <span class="fab fa-facebook fa-3x"></span>
        </a>
      </div>
    </div>
  </div>
</footer>
<!-- -------- END FOOTER w/ DARK BACKGROUND ------- -->



  <!-- Material Kit JS -->
  <script src="assets/js/material-kit.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleccionar el elemento de alerta
        const alert = document.querySelector('.alert');
        
        // Verificar si existe la alerta
        if (alert) {
            // Establecer un temporizador para ocultar la alerta después de 10 segundos
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                
                // Esperar un momento antes de eliminar la alerta del DOM
                setTimeout(() => {
                    alert.remove();
                }, 500); // tiempo de espera para que la transición de opacidad se complete
            }, 10000); // 10 segundos
        }
    });
</script>

</body>
</html>