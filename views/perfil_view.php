<!-- views/perfil_view.php -->
<?php require_once 'layout.php'; ?>

<div class="perfil-container">
    <h1>Perfil de Usuario</h1>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($datosUsuario['nombre_usuario']) ?></p>

    <h2>Recetas Favoritas</h2>
    <?php if (empty($favoritos)): ?>
        <p>No tienes recetas favoritas.</p>
    <?php else: ?>
        <ul class="favoritos-list">
            <?php foreach ($favoritos as $receta): ?>
                <li>
                    <img src="<?= htmlspecialchars($receta['imagen']) ?>" alt="Imagen de la receta">
                    <p><?= htmlspecialchars($receta['titulo']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
