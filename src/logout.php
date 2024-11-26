<?php
session_start();
session_unset();
session_destroy();
header("Location: ../views/recetas_lista.php");
exit;
?>
