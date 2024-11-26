<?php
require_once __DIR__ . '/src/Database.php'; // Incluye Database.php para manejar la conexión a la base de datos

// Configuración de OAuth
$$client_id = GOOGLE_CLIENT_ID;
$client_secret = GOOGLE_CLIENT_SECRET;
$redirect_uri = GOOGLE_REDIRECT_URI;
$token_url = GOOGLE_TOKEN_URL;
$userinfo_url = GOOGLE_USERINFO_URL;

// Verifica si Google redirigió con un código de autorización
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Datos para la solicitud del token
    $data = [
        "code" => $code,
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "redirect_uri" => $redirect_uri,
        "grant_type" => "authorization_code"
    ];

    // Solicitar el token de acceso
    $curl = curl_init($token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    $token_data = json_decode($response, true);

    // Verifica si el token de acceso fue recibido correctamente
    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Solicitar información del usuario
        $curl = curl_init($userinfo_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $userinfo_response = curl_exec($curl);
        curl_close($curl);

        $user_info = json_decode($userinfo_response, true);

        // Manejar los datos del usuario
        if (isset($user_info['email'])) {
            $google_id = $user_info['sub'];
            $nombre_usuario = $user_info['name'];
            $correo = $user_info['email'];
            $picture = $user_info['picture'] ?? null;

            if (empty($google_id) || empty($nombre_usuario) || empty($correo)) {
                throw new Exception("Los campos obligatorios están vacíos");
            }

            // Sanitización de los campos
            $google_id = filter_var($google_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nombre_usuario = filter_var($nombre_usuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validar que el correo sea válido
            if (filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
                throw new Exception("El correo electrónico no es válido");
            }

            $picture = !empty($picture) ? filter_var($picture, FILTER_SANITIZE_URL) : null;

            try {
                // Conexión a la base de datos
                $db = Database::getInstance()->getConnection();

                // Preparar el query
                $stmt = $db->prepare(
                    "INSERT INTO usuarios (google_id, nombre_usuario, correo, picture) 
            VALUES (:google_id, :nombre_usuario, :correo, :picture)
            ON DUPLICATE KEY UPDATE 
            nombre_usuario = VALUES(nombre_usuario), 
            picture = VALUES(picture)"
                );

                // Bind de parámetros
                $stmt->bindParam(':google_id', $google_id);
                $stmt->bindParam(':nombre_usuario', $nombre_usuario);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':picture', $picture);

                // Ejecutar el query
                $stmt->execute();

                // Iniciar sesión si no está iniciada
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Guardar los datos del usuario en la sesión
                $_SESSION['user_email'] = $correo;
                $_SESSION['user_name'] = $nombre_usuario;
                $_SESSION['user_picture'] = $picture;

                // Redirigir al usuario
                header("Location: views/recetas_lista.php");
                exit;
            } catch (PDOException $e) {
                echo "Error en la base de datos: " . $e->getMessage();
            }
        }
    } else {
        echo "Error al obtener el token de acceso.";
    }
} else {
    echo "No se recibió ningún código de autorización.";
}
