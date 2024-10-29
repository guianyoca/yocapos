<?php
// Datos de conexión
$host = "localhost";
$dbname = "yocapos";
$username = "root"; // Cambia esto si tienes otro usuario en MySQL
$password = ""; // Cambia esto si tienes una contraseña en MySQL

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>