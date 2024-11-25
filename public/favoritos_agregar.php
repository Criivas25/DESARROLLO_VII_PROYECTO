<?php
// public/favoritos_agregar.php
require_once __DIR__ . '/../src/Usuario.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['receta_id'])) {
    header('Location: recetas_lista.php');
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$recetaId = intval($_GET['receta_id']);

$usuario = new Usuario();
$agregado = $usuario->agregarAFavoritos($usuarioId, $recetaId);

if ($agregado) {
    $_SESSION['mensaje'] = "Receta añadida a tus favoritos.";
} else {
    $_SESSION['mensaje'] = "La receta ya está en tus favoritos.";
}

header('Location: recetas_lista.php');
exit();
