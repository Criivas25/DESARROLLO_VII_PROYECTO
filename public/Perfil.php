<?php
// public/perfil.php
require_once __DIR__ . '/../src/usuario.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuarioId = $_SESSION['usuario_id'];
$usuario = new Usuario();

// Obtener datos del usuario
$datosUsuario = $usuario->obtenerUsuario($usuarioId);
$favoritos = $usuario->obtenerFavoritos($usuarioId);

require_once __DIR__ . '/../views/perfil_view.php';
?>