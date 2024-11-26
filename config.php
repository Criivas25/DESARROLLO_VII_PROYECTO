<?php
require_once __DIR__ . '/.env'; // <-- Cambia aquí: agrega la barra antes de '.env'
// Function to read .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception(".env file not found at: " . $path);
    }

    // Asegúrate de no imprimir el contenido del archivo
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) { // Saltar comentarios
            continue;
        }

        if (strpos($line, '=') !== false) { // Validar formato clave=valor
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

// Define constants using environment variables
define('BASE_URL', getenv('BASE_URL'));
define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));

// Derived constants
define('PUBLIC_URL', BASE_URL . '/public');

// Variables OAuth de Google
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID'));
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET'));
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI'));
define('GOOGLE_TOKEN_URL', getenv('GOOGLE_TOKEN_URL'));
define('GOOGLE_USERINFO_URL', getenv('GOOGLE_USERINFO_URL'));

define('GOOGLE_LOGIN_URL', "https://accounts.google.com/o/oauth2/auth?"
    . "client_id=" . GOOGLE_CLIENT_ID
    . "&redirect_uri=" . GOOGLE_REDIRECT_URI
    . "&response_type=code"
    . "&scope=openid%20email%20profile");

// You can add more configuration settings here
