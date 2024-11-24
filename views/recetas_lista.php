<?php
require_once __DIR__ . '/../src/Database.php';

// Obtén la instancia única de la base de datos
$db = Database::getInstance();
$pdo = $db->getConnection();

// Obtener todas las recetas
$stmt = $pdo->prepare("SELECT id, nombre, categoria, imagen FROM recetas");
$stmt->execute();
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'layout.php'; ?>
<link rel="stylesheet" href="../public/assets/css/styles.css">
<div class="container">
    <h1>Recetas Disponibles</h1>
    <div class="recipe-grid">
        <?php foreach ($recetas as $receta): ?>
            <div class="recipe-card">
                <img src="../public/assets/images/<?php echo htmlspecialchars($receta['imagen']); ?>" alt="<?php echo htmlspecialchars($receta['nombre']); ?>">
                <h2><?php echo htmlspecialchars($receta['nombre']); ?></h2>
                <p>Categoría: <?php echo htmlspecialchars($receta['categoria']); ?></p>
                <a href="recetas_detalles.php?id=<?php echo $receta['id']; ?>">Ver Detalles</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
