<?php
/**
 * TEST SIMPLE: Conexión MySQL en InfinityFree
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Simple - MySQL</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Test Simple de MySQL</h1>
    <hr>
    
    <?php
    // Credenciales
    $host = 'sql303.infinityfree.com';
    $user = 'if0_40271876';
    $pass = 'betito4321';
    $db   = 'if0_40271876_joyeria';
    
    echo "<h2>1. Credenciales:</h2>";
    echo "<pre>";
    echo "Host: $host\n";
    echo "User: $user\n";
    echo "Pass: " . str_repeat('*', strlen($pass)) . "\n";
    echo "DB:   $db\n";
    echo "</pre>";
    
    echo "<h2>2. Extensión MySQLi:</h2>";
    if (extension_loaded('mysqli')) {
        echo "<p class='success'>✅ MySQLi está disponible</p>";
    } else {
        echo "<p class='error'>❌ MySQLi NO está disponible</p>";
        exit;
    }
    
    echo "<h2>3. Intentando conexión...</h2>";
    
    // Intentar conexión
    $conn = @new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        echo "<p class='error'>❌ Error de conexión</p>";
        echo "<pre>";
        echo "Error: " . $conn->connect_error . "\n";
        echo "Código: " . $conn->connect_errno . "\n";
        echo "</pre>";
        
        echo "<h3>Posibles causas:</h3>";
        echo "<ul>";
        echo "<li>Credenciales incorrectas</li>";
        echo "<li>Base de datos no existe</li>";
        echo "<li>Host incorrecto</li>";
        echo "<li>Usuario no tiene permisos</li>";
        echo "</ul>";
        
        echo "<h3>Verifica en InfinityFree:</h3>";
        echo "<ol>";
        echo "<li>Panel → MySQL Databases</li>";
        echo "<li>Anota el MySQL Hostname exacto</li>";
        echo "<li>Verifica que la base de datos existe</li>";
        echo "<li>Verifica usuario y contraseña</li>";
        echo "</ol>";
        
    } else {
        echo "<p class='success'>✅ Conexión exitosa</p>";
        echo "<pre>";
        echo "Versión MySQL: " . $conn->server_info . "\n";
        echo "Protocolo: " . $conn->protocol_version . "\n";
        echo "Charset: " . $conn->character_set_name() . "\n";
        echo "</pre>";
        
        // Verificar tablas
        echo "<h2>4. Verificando tablas...</h2>";
        
        $result = $conn->query("SHOW TABLES");
        if ($result) {
            $tables = [];
            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }
            
            if (count($tables) > 0) {
                echo "<p class='success'>✅ Se encontraron " . count($tables) . " tabla(s):</p>";
                echo "<ul>";
                foreach ($tables as $table) {
                    echo "<li>$table";
                    
                    // Contar registros
                    $count = $conn->query("SELECT COUNT(*) as total FROM `$table`");
                    if ($count) {
                        $row = $count->fetch_assoc();
                        echo " (" . $row['total'] . " registros)";
                    }
                    echo "</li>";
                }
                echo "</ul>";
                
                // Verificar tabla productos específicamente
                if (in_array('productos', $tables)) {
                    echo "<h2>5. Productos en la base de datos:</h2>";
                    $productos = $conn->query("SELECT id, nombre, categoria, activo FROM productos LIMIT 5");
                    if ($productos && $productos->num_rows > 0) {
                        echo "<table border='1' cellpadding='5'>";
                        echo "<tr><th>ID</th><th>Nombre</th><th>Categoría</th><th>Activo</th></tr>";
                        while ($p = $productos->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $p['id'] . "</td>";
                            echo "<td>" . htmlspecialchars($p['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($p['categoria']) . "</td>";
                            echo "<td>" . ($p['activo'] ? '✅' : '❌') . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p class='info'>⚠️ La tabla 'productos' existe pero está vacía</p>";
                        echo "<p>Debes agregar productos desde el panel de admin</p>";
                    }
                }
                
            } else {
                echo "<p class='error'>❌ No se encontraron tablas</p>";
                echo "<p>Debes ejecutar el archivo schema.sql en phpMyAdmin</p>";
            }
        }
        
        $conn->close();
        
        echo "<hr>";
        echo "<h2>✅ Test completado exitosamente</h2>";
        echo "<p>La conexión a MySQL funciona correctamente.</p>";
        echo "<p><strong>Siguiente paso:</strong> Si no hay productos, agrégalos desde el admin.</p>";
    }
    ?>
    
    <hr>
    <p>
        <a href="index.html">← Volver al sitio</a> | 
        <a href="admin/">Ir al admin</a> |
        <a href="api/productos.php" target="_blank">Ver API</a>
    </p>
</body>
</html>
