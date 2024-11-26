<?php
// src/Receta.php
require_once 'Database.php';

class Receta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
}
?>