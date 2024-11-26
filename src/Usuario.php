<?php
// src/Usuario.php
require_once 'Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener información del usuario
    public function obtenerUsuario($usuarioId) {
        $pdo = $this->db->getConnection();

        // Uso correcto de prepared statements
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    // Agregar receta a favoritos
    public function agregarAFavoritos($usuarioId, $recetaId) {
        $pdo = $this->db->getConnection();

        try {
            // Verificar si ya existe en favoritos
            $stmt = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND receta_id = ?");
            $stmt->execute([$usuarioId, $recetaId]);
            
            // Si ya existe, no agregar
            if ($stmt->rowCount() > 0) {
                return false;  // Ya está en favoritos
            }
            
            // Si no existe, insertamos en la tabla favoritos
            $stmt = $pdo->prepare("INSERT INTO favoritos (usuario_id, receta_id) VALUES (?, ?)");
            $stmt->execute([$usuarioId, $recetaId]);

            return true;  // Se agregó exitosamente
        } catch (PDOException $e) {
            // Si ocurre un error, lo puedes registrar y devolver false
            error_log("Error al agregar a favoritos: " . $e->getMessage());
            return false;  // Algo salió mal
        }
    }

    // Obtener recetas favoritas del usuario
    public function obtenerFavoritos($usuarioId) {
        $pdo = $this->db->getConnection();

        try {
            // Primero, obtener todas las receta_id desde la tabla favoritos
            $stmt = $pdo->prepare("SELECT receta_id FROM favoritos WHERE usuario_id = ?");
            $stmt->execute([$usuarioId]);
            $favoritosIds = $stmt->fetchAll(PDO::FETCH_COLUMN);  // Obtenemos un array con los ids de las recetas favoritas
            
            // Si no hay recetas favoritas, retornamos un array vacío
            if (empty($favoritosIds)) {
                return [];
            }
    
            // Ahora, obtener las recetas completas usando los ids obtenidos
            // Convertimos el array de ids en un string para usarlo en un IN()
            $placeholders = implode(',', array_fill(0, count($favoritosIds), '?'));
            $stmt = $pdo->prepare("SELECT id, nombre, imagen FROM recetas WHERE id IN ($placeholders)");
            $stmt->execute($favoritosIds);
    
            // Retornamos las recetas favoritas obtenidas
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Si ocurre un error, se puede logear el error y devolver un array vacío
            error_log("Error al obtener favoritos: " . $e->getMessage());
            return [];
        }
    }
}
?>
