<?php
//../views/recetas_detalles.php
require_once __DIR__ . '/../src/Database.php';

session_start(); // Asegura que se pueda verificar el usuario autenticado

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Obtener la receta específica por ID
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM recetas WHERE id = ?");
    $stmt->execute([$id]);
    $receta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receta) {
        die('Receta no encontrada');
    }
} else {
    die('ID de receta no proporcionado');
}
?>

<?php include 'layout.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($receta['nombre']); ?></title>
</head>
<body>
<div class="receta-detalles">
    <h1><?php echo htmlspecialchars($receta['nombre']); ?></h1>
    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de la receta">
    <p><strong>Categoría:</strong> <?= htmlspecialchars($receta['categoria']) ?></p>
    <p><strong>Ingredientes:</strong> <?= htmlspecialchars($receta['ingredientes']) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($receta['descripcion']) ?></p>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="favoritos_agregar.php?receta_id=<?= $receta['id'] ?>" class="favoritos-boton">Agregar a Favoritos</a>
    <?php endif; ?>
    <br><br>
    </div>
    <a href="recetas_lista.php">Volver a la lista de recetas</a>
</body>
</html>
