<?php
include('conexion.php');
session_start();

// Verificar si el usuario tiene una sesión válida
if (!isset($_SESSION['id_comercio'])) {
    echo "Error: No hay un comercio asociado a esta sesión.";
    exit;
}

$id_comercio = $_SESSION['id_comercio'];  // Obtener el ID del comercio de la sesión

// Verificar si el parámetro 'q' está presente en la URL
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = $_GET['q'];  // Término de búsqueda

    // Preparar la consulta SQL para buscar el producto por nombre o código de barras
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_comercio = ? AND (producto LIKE ? OR cod_barra LIKE ?)");
    $search = "%" . $q . "%";  // Agregar los comodines para la búsqueda
    $stmt->bind_param("iss", $id_comercio, $search, $search);
    
    // Ejecutar la consulta y procesar los resultados
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Mostrar los productos encontrados
            while ($producto = $result->fetch_assoc()) {
                echo "<div>
                        <button type='button' class='btn btn-outline-info btn-block' onclick='agregarAlCarrito({$producto['id_producto']}, \"{$producto['producto']}\", {$producto['precio']})'>
                            {$producto['producto']} - {$producto['cod_barra']}
                        </button>
                      </div>";
            }
        } else {
            echo "No se encontraron productos.";
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Por favor ingrese un término de búsqueda.";
}
if (isset($_GET['q'])) {
    echo "Término de búsqueda recibido: " . $_GET['q'];
} else {
    echo "No se recibió ningún término de búsqueda.";
}
?>
