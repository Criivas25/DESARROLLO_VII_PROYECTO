<?php
require_once __DIR__ . '/../src/Database.php';

session_start();

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
<link rel="stylesheet" href="../public/assets/css/perfil.css">

<div class="recetas-container">
    <h1>Recetas Disponibles</h1>
    <?php if (isset($_SESSION['mensaje'])): ?>
        <p class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <?php if (empty($recetas)): ?>
        <p>No hay recetas disponibles.</p>
    <?php else: ?>
        <ul class="recetas-lista">
            <?php foreach ($recetas as $receta): ?>
                <li>
                    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de la receta">
                    <h2><?= htmlspecialchars($receta['nombre']) ?></h2>
                    <a href="recetas_detalles.php?id=<?= $receta['id'] ?>">Ver detalles</a>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <a href="favoritos_agregar.php?receta_id=<?= $receta['id'] ?>" class="favoritos-boton">Agregar a Favoritos</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>