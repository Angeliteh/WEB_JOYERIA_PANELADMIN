<?php
// Activar errores para ver qué está pasando
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Diagnóstico de API</h2>";

echo "<h3>1. Verificando archivo database.php</h3>";
if (file_exists('admin/database.php')) {
    echo "✅ database.php existe<br>";
} else {
    echo "❌ database.php NO existe<br>";
}

echo "<h3>2. Intentando incluir database.php</h3>";
try {
    require_once 'admin/database.php';
    echo "✅ database.php incluido correctamente<br>";
} catch (Exception $e) {
    echo "❌ Error incluyendo database.php: " . $e->getMessage() . "<br>";
    die();
}

echo "<h3>3. Verificando constantes de DB</h3>";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NO DEFINIDO') . "<br>";
echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'NO DEFINIDO') . "<br>";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'NO DEFINIDO') . "<br>";
echo "IS_LOCAL: " . (defined('IS_LOCAL') ? (IS_LOCAL ? 'true' : 'false') : 'NO DEFINIDO') . "<br>";

echo "<h3>4. Intentando conectar a MySQL</h3>";
try {
    $conn = getDBConnection();
    echo "✅ Conexión exitosa a MySQL<br>";
    echo "Servidor: " . $conn->host_info . "<br>";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    die();
}

echo "<h3>5. Verificando tabla productos</h3>";
try {
    $result = $conn->query("SHOW TABLES LIKE 'productos'");
    if ($result->num_rows > 0) {
        echo "✅ Tabla 'productos' existe<br>";
    } else {
        echo "❌ Tabla 'productos' NO existe<br>";
        die();
    }
} catch (Exception $e) {
    echo "❌ Error verificando tabla: " . $e->getMessage() . "<br>";
    die();
}

echo "<h3>6. Contando productos</h3>";
try {
    $result = $conn->query("SELECT COUNT(*) as total FROM productos");
    $row = $result->fetch_assoc();
    echo "📊 Total de productos en MySQL: " . $row['total'] . "<br>";
} catch (Exception $e) {
    echo "❌ Error contando productos: " . $e->getMessage() . "<br>";
}

echo "<h3>7. Intentando getAllProductos()</h3>";
try {
    $productos = getAllProductos(true);
    echo "✅ getAllProductos() funciona<br>";
    echo "📊 Productos activos: " . count($productos) . "<br>";
    
    if (count($productos) > 0) {
        echo "<h4>Primer producto:</h4>";
        echo "<pre>";
        print_r($productos[0]);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "❌ Error en getAllProductos(): " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h3>8. Simulando la API</h3>";
try {
    $productosActivos = getAllProductos(true);
    
    $response = [
        'success' => true,
        'total' => count($productosActivos),
        'productos' => $productosActivos
    ];
    
    echo "✅ API simulada exitosamente<br>";
    echo "<h4>JSON generado:</h4>";
    echo "<pre>";
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "❌ Error simulando API: " . $e->getMessage() . "<br>";
}

echo "<h3>✅ Diagnóstico completado</h3>";
?>
