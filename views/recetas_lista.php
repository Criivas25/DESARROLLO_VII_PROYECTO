<?php
require_once '../src/Database.php';

session_start();

// Manejo de mensajes de sesión
if (isset($_SESSION['mensaje'])) {
    echo '<p>' . $_SESSION['mensaje'] . '</p>';
    unset($_SESSION['mensaje']);
}

if (isset($_SESSION['error'])) {
    echo '<p>' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}

// Conexión a la base de datos
$db = Database::getInstance();
$pdo = $db->getConnection();

// Verificar si se busca por una categoría
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

// Obtener la búsqueda por ingredientes
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Construir la consulta SQL según los filtros
$sql = "SELECT id, nombre, categoria, imagen, descripcion FROM recetas WHERE 1";

// Agregar filtro por categoría si se ha seleccionado
if ($categoria) {
    $sql .= " AND categoria = :categoria";
}

// Agregar filtro por ingredientes si se ha proporcionado una búsqueda
if (!empty($searchQuery)) {
    $searchQuery = "%" . $searchQuery . "%";
    $sql .= " AND ingredientes LIKE :ingredientes";
}

// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Asignar valores a los parámetros de la consulta
if ($categoria) {
    $stmt->bindParam(':categoria', $categoria);
}

if (!empty($searchQuery)) {
    $stmt->bindParam(':ingredientes', $searchQuery);
}

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'layout.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lista de recetas disponibles">
    <title>Recetas Disponibles</title>
</head>
<body>
<main class="recetas-container">
    <h1>
        <?php if ($categoria): ?>
            Recetas de la categoría: <?= htmlspecialchars($categoria) ?>
        <?php else: ?>
            Recetas Disponibles
        <?php endif; ?>

        <?php if (!empty($searchQuery)): ?>
            <br><small>Filtrado por ingrediente: <?= htmlspecialchars($searchQuery) ?></small>
        <?php endif; ?>
    </h1>

    <?php if (empty($recetas)): ?>
        <p>No se encontraron recetas con esos filtros.</p>
    <?php else: ?>
        <ul class="recetas-lista">
            <?php foreach ($recetas as $receta): ?>
                <li class="receta-item">
                    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($receta['nombre']) ?>">
                    <h2><?= htmlspecialchars($receta['nombre']) ?></h2>
                    <p>Categoría: <?= htmlspecialchars($receta['categoria']) ?></p>
                    <div class="receta-acciones">
                        <a href="recetas_detalles.php?id=<?= $receta['id'] ?>" class="btn-detalles">Ver detalles</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../public/favoritos_agregar.php?receta_id=<?= $receta['id'] ?>" class="btn-favoritos">Agregar a Favoritos</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<footer class="section footer">
    <p>&copy; <?= date('Y') ?> Recetario. Todos los derechos reservados.</p>
</footer>
</body>
</html>
