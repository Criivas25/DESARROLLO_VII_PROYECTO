<?php
session_start();

// Incluir configuración
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../src/Database.php';

// Conexión a la base de datos
$db = Database::getInstance();
$pdo = $db->getConnection();

// Procesar registro o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        // Registro de usuario
        $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        // Validar correo
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Correo inválido.";
            header("Location: registro.php");
            exit;
        }

        // Hashear la contraseña
        $password = $_POST['password'];  // Contraseña en texto plano
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hasheamos la contraseña

        // Consulta para insertar el nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        // Obtener el ID del usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$email]);
        $user_id = $stmt->fetchColumn();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $username;

        // Redirigir al usuario
        header("Location: ../views/recetas_lista.php");
        exit;

    } elseif ($action === 'login') {
        // Inicio de sesión
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password']; // La contraseña en texto plano

        // Consultar usuario por correo
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Verificar la contraseña con password_verify
            if (password_verify($password, $user['contrasena'])) {
                // Inicio de sesión exitoso
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre_usuario'];

                // Redirigir al usuario a la página principal
                header("Location: ../views/recetas_lista.php");
                exit;
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta.";
            }
        } else {
            // Usuario no encontrado
            echo "No se encontró un usuario con ese correo.";
        }
    }
}
?>
