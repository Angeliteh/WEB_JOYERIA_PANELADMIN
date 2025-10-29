<?php
/**
 * TEST: Verificar conexión a MySQL en InfinityFree
 */

// Mostrar todos los errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🔍 Test de Conexión - Joyería Matt</h1>";
echo "<hr>";

// Información del servidor
echo "<h2>0. Información del Servidor</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "<hr>";

// 1. Verificar que database.php existe
echo "<h2>1. Verificando archivos...</h2>";
if (file_exists('admin/database.php')) {
    echo "✅ admin/database.php existe<br>";
} else {
    echo "❌ admin/database.php NO existe<br>";
    exit;
}

// 2. Incluir database.php
echo "<h2>2. Incluyendo database.php...</h2>";
try {
    require_once 'admin/database.php';
    echo "✅ database.php incluido correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error al incluir: " . $e->getMessage() . "<br>";
    exit;
}

// 3. Verificar constantes
echo "<h2>3. Verificando configuración...</h2>";
echo "IS_LOCAL: " . (IS_LOCAL ? 'true' : 'false') . "<br>";
echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_PASS: " . (DB_PASS ? '***' : '(vacío)') . "<br>";

// 4. Intentar conexión
echo "<h2>4. Intentando conexión a MySQL...</h2>";

// Habilitar errores de MySQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Intentar conexión directa primero
    echo "Intentando conexión directa...<br>";
    $conn_test = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn_test->connect_error) {
        echo "❌ Error de conexión directa: " . $conn_test->connect_error . "<br>";
        echo "Código de error: " . $conn_test->connect_errno . "<br>";
    } else {
        echo "✅ Conexión directa exitosa<br>";
        $conn_test->close();
    }
    
    echo "<br>Intentando con getConnection()...<br>";
    $conn = getConnection();
    if ($conn) {
        echo "✅ Conexión exitosa a MySQL<br>";
        echo "Versión MySQL: " . $conn->server_info . "<br>";
        
        // 5. Verificar tablas
        echo "<h2>5. Verificando tablas...</h2>";
        
        $tables = ['productos', 'solicitudes', 'configuracion'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                echo "✅ Tabla '$table' existe<br>";
                
                // Contar registros
                $count = $conn->query("SELECT COUNT(*) as total FROM $table");
                if ($count) {
                    $row = $count->fetch_assoc();
                    echo "&nbsp;&nbsp;&nbsp;→ Registros: " . $row['total'] . "<br>";
                }
            } else {
                echo "❌ Tabla '$table' NO existe<br>";
            }
        }
        
        // 6. Probar getAllProductos
        echo "<h2>6. Probando getAllProductos()...</h2>";
        try {
            $productos = getAllProductos(true);
            if ($productos === null || $productos === false) {
                echo "⚠️ getAllProductos() devolvió null/false<br>";
                echo "Esto significa que no hay productos o hay un error<br>";
            } else {
                echo "✅ getAllProductos() funciona<br>";
                echo "Total de productos activos: " . count($productos) . "<br>";
                
                if (count($productos) > 0) {
                    echo "<h3>Primer producto:</h3>";
                    echo "<pre>";
                    print_r($productos[0]);
                    echo "</pre>";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error en getAllProductos(): " . $e->getMessage() . "<br>";
        }
        
        $conn->close();
        
    } else {
        echo "❌ No se pudo conectar a MySQL<br>";
    }
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    echo "<br><strong>Posibles causas:</strong><br>";
    echo "- Credenciales incorrectas<br>";
    echo "- Base de datos no existe<br>";
    echo "- Servidor MySQL no disponible<br>";
}

echo "<hr>";
echo "<h2>✅ Test completado</h2>";
echo "<p><a href='index.html'>← Volver al sitio</a> | <a href='admin/'>Ir al admin</a></p>";
?>
