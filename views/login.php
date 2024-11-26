<?php
// Incluir el archivo de configuración que contiene las variables de entorno
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

// Manejo de mensajes de sesión
if (isset($_SESSION['mensaje'])) {
    echo '<p>' . $_SESSION['mensaje'] . '</p>';
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="../public/assets/css/stylesLogin.css">
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>
<header>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="recetas_lista.php" class="navbar-logo">Recetario</a>
            <ul class="navbar-links">
                <li><a href="recetas_lista.php">Inicio</a></li>
                <li><a href="profile.php">Perfil</a></li>
                <li><a href="recetas_lista.php">Sobre Nosotros</a></li>
            </ul>
        </div>
    </nav>
</header>

<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="../src/authenticate.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
        <hr>
        <!-- Botón para iniciar sesión con Google -->
        <a href="https://accounts.google.com/o/oauth2/auth?...">
    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
        alt="Iniciar sesión con Google">
</a>
    </div>
</body>

</html>