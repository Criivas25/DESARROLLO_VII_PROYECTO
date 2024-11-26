<?php
// public/favoritos_agregar.php
require_once __DIR__ . '/../src/Usuario.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if (!isset($_GET['receta_id'])) {
    header('Location: ../views/recetas_lista.php');
    exit();
}

$usuarioId = $_SESSION['user_id'];
$recetaId = intval($_GET['receta_id']);

// Crear una instancia de la clase Usuario
$usuario = new Usuario();

// Obtener el ID de la receta desde la URL
if (isset($_GET['receta_id'])) {
    $recetaId = $_GET['receta_id'];
    
    // Agregar la receta a favoritos
    $resultado = $usuario->agregarAFavoritos($_SESSION['user_id'], $recetaId);
    
    if ($resultado) {
        // Si se agregó correctamente, redirigir al perfil
        header("Location: ../views/recetas_lista.php");
        exit;
    } else {
        // Si ya estaba en favoritos, mostrar un mensaje
        echo "La receta ya está en tus favoritos.";
        header("Location: ../views/recetas_lista.php");
        exit;
    }
} else {
    echo "Receta no encontrada.";
}
exit();
