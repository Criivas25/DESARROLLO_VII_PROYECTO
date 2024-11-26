<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

// Incluir la clase Database para la conexión a la base de datos
require_once __DIR__ . '/../src/Database.php';

// Obtener la instancia de la base de datos
$db = Database::getInstance();
$pdo = $db->getConnection();

// Obtener los detalles del usuario logueado
$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT nombre_usuario, rol FROM usuarios WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $user_name = $user['nombre_usuario'];
    $user_role = $user['rol'];
} else {
    // Si no se encuentra el usuario, redirigir al login
    header("Location: login.php");
    exit;
}

// Obtener las recetas favoritas del usuario
// Incluir la clase Usuario
require_once '../src/Usuario.php';

// Crear una instancia de la clase Usuario
$usuario = new Usuario();

// Usar solo una variable consistente para las recetas favoritas
$favorites = $usuario->obtenerFavoritos($_SESSION['user_id']);

// Si el usuario es admin, obtener todas las recetas para el CRUD
if ($user_role === 'admin') {
    $query_recipes = $pdo->prepare("SELECT id, nombre FROM recetas");
    $query_recipes->execute();
    $recipes = $query_recipes->fetchAll(PDO::FETCH_ASSOC);
}
$usuario = new Usuario();
$favorites = $usuario->obtenerFavoritos($_SESSION['user_id']);
?>

<?php include 'layout.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?= htmlspecialchars($user_name); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Bienvenido, <?= htmlspecialchars($user_name); ?>!</h1>
        <a href="../src/logout.php">Cerrar sesión</a>

        <h2>Recetas Favoritas</h2>
<?php if ($favorites && count($favorites) > 0): ?>
    <ul>
        <?php foreach ($favorites as $favorite): ?>
            <li>
                <a href="recetas_detalles.php?id=<?= $favorite['id']; ?>">
                    <img src="<?= htmlspecialchars($favorite['imagen']); ?>" alt="<?= htmlspecialchars($favorite['nombre']); ?>" width="100">
                    <p><?= htmlspecialchars($favorite['nombre']); ?></p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No tienes recetas favoritas.</p>
<?php endif; ?>

        <?php if ($user_role === 'admin'): ?>
            <h2>Gestión de Recetas</h2>
            <a href="crud_recetas.php">Ir a CRUD de Recetas</a>

            <h3>Recetas Disponibles</h3>
            <ul>
                <?php foreach ($recipes as $recipe): ?>
                    <li><?= htmlspecialchars($recipe['nombre']); ?>
                        <a href="editar_receta.php?id=<?= $recipe['id']; ?>">Editar</a> |
                        <a href="eliminar_receta.php?id=<?= $recipe['id']; ?>">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>