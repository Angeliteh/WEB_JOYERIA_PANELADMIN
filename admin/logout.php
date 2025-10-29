<?php
session_start();

// Destruir sesión
session_destroy();

// Redirigir al login
header('Location: index.php');
exit;
?>
