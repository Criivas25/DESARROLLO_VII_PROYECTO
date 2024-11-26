<!DOCTYPE html>
<html lang="en">

<head>
    <title>About Us</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar">
            <div class="navbar-container">
                <a href="index.php" class="navbar-logo">Recetario</a>
                <div class="navbar-search">
                    <form action="recetas_lista.php" method="GET">
                        <input type="text" name="q" placeholder="Buscar recetas..." class="search-input">
                        <button type="submit" class="search-button">Buscar</button>
                    </form>
                </div>
                <ul class="navbar-links">
                    <li><a href="recetas_lista.php">Inicio</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Categorías</a>
                        <ul class="dropdown-menu">
                            <?php
                            // Obtener las categorías dinámicamente
                            $stmt = $pdo->query("SELECT DISTINCT categoria FROM recetas");
                            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($categorias as $categoriaItem):
                            ?>
                                <li><a href="recetas_lista.php?categoria=<?= urlencode($categoriaItem['categoria']) ?>"><?= htmlspecialchars($categoriaItem['categoria']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="profile.php">Perfil</a></li>
                    <li><a href="recetas_lista.php">Sobre Nosotros</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <?php // Aquí va el contenido dinámico de cada página 
        ?>
    </main>

    <!-- Footer -->
    <script src="js/core.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>