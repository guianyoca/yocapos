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
$stmtVentasTotales = $conn->prepare("SELECT SUM(monto_total) AS total_ventas FROM ventas WHERE id_comercio = ?");
$stmtVentasTotales->bind_param("i", $id_comercio);
$stmtVentasTotales->execute();
$resultVentasTotales = $stmtVentasTotales->get_result();
$total_ventas = $resultVentasTotales->fetch_assoc()['total_ventas'] ?: '0';
$stmtVentasTotales->close();

// Ventas del Día
$stmtVentasDia = $conn->prepare("SELECT SUM(monto_total) AS ventas_dia FROM ventas WHERE DATE(fecha) = CURDATE() AND id_comercio = ?");
$stmtVentasDia->bind_param("i", $id_comercio);
$stmtVentasDia->execute();
$resultVentasDia = $stmtVentasDia->get_result();
$ventas_dia = $resultVentasDia->fetch_assoc()['ventas_dia'] ?: '0';
$stmtVentasDia->close();

// Stock Disponible
$stmtStock = $conn->prepare("SELECT COUNT(*) AS total_productos FROM productos WHERE id_comercio = ?");
$stmtStock->bind_param("i", $id_comercio);
$stmtStock->execute();
$resultStock = $stmtStock->get_result();
$total_productos = $resultStock->fetch_assoc()['total_productos'] ?: '0';
$stmtStock->close();

// Clientes
$stmtClientes = $conn->prepare("SELECT COUNT(*) AS total_clientes FROM clientes WHERE id_comercio = ?");
$stmtClientes->bind_param("i", $id_comercio);
$stmtClientes->execute();
$resultClientes = $stmtClientes->get_result();
$total_clientes = $resultClientes->fetch_assoc()['total_clientes'] ?: '0';
$stmtClientes->close();
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
                <!-- Ventas Totales -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-header bg-gradient-info text-white text-center">
                            Ventas Totales
                        </div>
                        <div class="card-body">
                            <h3 class="text-center"><?php echo $total_ventas; ?> ARS</h3>
                        </div>
                    </div>
                </div>
                <!-- Ventas del Día -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-header bg-gradient-success text-white text-center">
                            Ventas del Día
                        </div>
                        <div class="card-body">
                            <h3 class="text-center"><?php echo $ventas_dia; ?> ARS</h3>
                        </div>
                    </div>
                </div>
                <!-- Stock Disponible -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-header bg-gradient-warning text-white text-center">
                            Productos en Stock
                        </div>
                        <div class="card-body">
                            <h3 class="text-center"><?php echo $total_productos; ?> productos</h3>
                        </div>
                    </div>
                </div>
                <!-- Clientes -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-header bg-gradient-primary text-white text-center">
                            Total de Clientes
                        </div>
                        <div class="card-body">
                            <h3 class="text-center"><?php echo $total_clientes; ?> clientes</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">Website Views</h6>
              <p class="text-sm ">Last Campaign Performance</p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> campaign sent 2 days ago </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card z-index-2  ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 "> Daily Sales </h6>
              <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales. </p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm"> updated 4 min ago </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt-4 mb-3">
          <div class="card z-index-2 ">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
              <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                <div class="chart">
                  <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
            </div>
            <div class="card-body">
              <h6 class="mb-0 ">Completed Tasks</h6>
              <p class="text-sm ">Last Campaign Performance</p>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-icons text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm">just updated</p>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php
            include 'footer.php';
        ?>
    </div>
  </main>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "rgba(255, 255, 255, .8)",
          data: [50, 20, 10, 22, 50, 10, 40],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Mobile apps",
          tension: 0,
          borderWidth: 0,
          pointRadius: 5,
          pointBackgroundColor: "rgba(255, 255, 255, .8)",
          pointBorderColor: "transparent",
          borderColor: "rgba(255, 255, 255, .8)",
          borderColor: "rgba(255, 255, 255, .8)",
          borderWidth: 4,
          backgroundColor: "transparent",
          fill: true,
          data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: 'rgba(255, 255, 255, .2)'
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5],
            },
            ticks: {
              display: true,
              color: '#f8f9fa',
              padding: 10,
              font: {
                size: 14,
                weight: 300,
                family: "Roboto",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <!-- Github buttons -->
  <script async defer src="assets/js/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js"></script>
</body>
</html>