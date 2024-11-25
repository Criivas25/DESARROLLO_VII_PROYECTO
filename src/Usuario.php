<?php
// src/Usuario.php
require_once 'Database.php';

class usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener información del usuario
    public function obtenerUsuario($usuarioId) {
        $pdo = $this->db->getConnection();
        $query = $pdo->query(
            "SELECT * FROM usuarios WHERE id = ?", 
            $usuarioId
        );
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function agregarAFavoritos($usuarioId, $recetaId) {
        // Verificar si ya existe
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare(
            "SELECT id FROM favoritos WHERE usuario_id = ? AND receta_id = ?",
            [$usuarioId, $recetaId]
        );
        if ($stmt->rowCount() > 0) {
            return false; // Ya está en favoritos
        }
    
        // Insertar en la tabla favoritos
        $pdo->prepare("INSERT INTO favoritos (usuario_id, receta_id) VALUES (?, ?)");
$stmt->execute([$usuarioId, $recetaId]);
        return true;
    }

    // Obtener recetas favoritas del usuario
    public function obtenerFavoritos($usuarioId) {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare(
            "SELECT r.id, r.titulo, r.imagen 
            FROM favoritos f 
            JOIN recetas r ON f.receta_id = r.id 
            WHERE f.usuario_id = ?", 
            [$usuarioId]
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
