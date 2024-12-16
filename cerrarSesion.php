<?php
session_start(); // Iniciar la sesión
include_once("sesiones.php"); // Incluir el archivo de funciones de sesión

// Verificar si la sesión está activa antes de cerrarla
if (isset($_SESSION['usuario'])) {
    cerrar_sesion(); // Cerrar la sesión
}

// Redirigir al usuario a la página principal
header("Location: index.php");
exit(); // Asegura que el script termine aquí
?>

