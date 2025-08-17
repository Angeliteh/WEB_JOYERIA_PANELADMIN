<?php
require_once 'config.php';

// Hacer logout
doLogout();

// Redirigir al login
header('Location: index.php');
exit;
?>
