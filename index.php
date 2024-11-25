<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the base path for includes
define('BASE_PATH', __DIR__ . '/');

// Include the configuration file
require_once BASE_PATH . 'config.php';

// Include necessary files
require_once BASE_PATH . 'src/Database.php';
require_once BASE_PATH . 'src/Task.php';

// Iniciar sesión y verificar si el usuario está logueado
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si no ha iniciado sesión, redirigir a login.php
    header('Location: login/login.php');
    exit;
}

// Obtener la acción desde la URL, por defecto es 'list'
$action = $_GET['action'] ?? 'list';

// Manejar las diferentes acciones
switch ($action) {
    case 'create':
        // Crear una nueva tarea
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskManager->createTask($_POST['title']); // Asumiendo que el método 'createTask' está definido
            header('Location: ' . BASE_URL); // Redirigir después de crear la tarea
            exit;
        }
        require BASE_PATH . 'views/task_form.php'; // Mostrar el formulario para crear la tarea
        break;

    case 'toggle':
        // Alternar el estado de la tarea (completar o no)
        if (isset($_GET['id'])) {
            $taskManager->toggleTask($_GET['id']); // Asumiendo que 'toggleTask' está definido
            header('Location: ' . BASE_URL); // Redirigir después de cambiar el estado
        }
        break;

    case 'delete':
        // Eliminar una tarea
        if (isset($_GET['id'])) {
            $taskManager->deleteTask($_GET['id']); // Asumiendo que 'deleteTask' está definido
            header('Location: ' . BASE_URL); // Redirigir después de eliminar la tarea
        }
        break;

    default:
        // Mostrar la lista de tareas
        require BASE_PATH . 'views/recetas_lista.php';
        break;
}
