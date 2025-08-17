<?php
// Configuración del sistema de administración
// Este archivo contiene toda la configuración básica

// Configuración de autenticación
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin'); // En producción usar password_hash()

// Rutas de archivos
define('DATA_DIR', '../data/');
define('UPLOADS_DIR', '../data/uploads/');
define('PRODUCTOS_FILE', DATA_DIR . 'productos.json');

// Configuración de sesión
session_start();

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Función para verificar login
function checkLogin($username, $password) {
    // Por ahora verificación simple, después se puede mejorar con hash
    return ($username === ADMIN_USER && $password === ADMIN_PASS);
}

// Función para hacer login
function doLogin() {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = ADMIN_USER;
}

// Función para hacer logout
function doLogout() {
    session_destroy();
}

// Función para cargar productos desde JSON
function loadProductos() {
    if (!file_exists(PRODUCTOS_FILE)) {
        return ['productos' => []];
    }
    $json = file_get_contents(PRODUCTOS_FILE);
    return json_decode($json, true) ?: ['productos' => []];
}

// Función para guardar productos en JSON
function saveProductos($data) {
    // Crear directorio si no existe
    if (!is_dir(DATA_DIR)) {
        mkdir(DATA_DIR, 0755, true);
    }
    
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(PRODUCTOS_FILE, $json) !== false;
}

// Función para generar ID único para productos
function generateProductId() {
    $productos = loadProductos();
    $maxId = 0;
    foreach ($productos['productos'] as $producto) {
        if ($producto['id'] > $maxId) {
            $maxId = $producto['id'];
        }
    }
    return $maxId + 1;
}
?>
