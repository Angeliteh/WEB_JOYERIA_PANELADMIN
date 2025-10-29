<?php
/**
 * JOYERÍA MATT - Conexión a Base de Datos
 * 
 * Este archivo maneja la conexión a MySQL
 * Funciona tanto en local como en InfinityFree
 */

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

// Para desarrollo LOCAL (Linux/XAMPP/MAMP)
define('DB_HOST_LOCAL', 'localhost');
define('DB_USER_LOCAL', 'root');  // Cambiar a 'joyeria_user' si creaste usuario
define('DB_PASS_LOCAL', '');      // Cambiar a tu contraseña si creaste usuario
define('DB_NAME_LOCAL', 'joyeria_matt');

// Para PRODUCCIÓN (InfinityFree)
// CREDENCIALES CONFIGURADAS
define('DB_HOST_PROD', 'sql303.infinityfree.com');  // Host de InfinityFree
define('DB_USER_PROD', 'if0_40271876');             // Usuario MySQL
define('DB_PASS_PROD', 'betito4321');               // Contraseña MySQL
define('DB_NAME_PROD', 'if0_40271876_joyeria');     // Base de datos

// Detectar si estamos en local o producción
define('IS_LOCAL', in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', 'localhost:8000']));

// Seleccionar credenciales según el entorno
if (IS_LOCAL) {
    define('DB_HOST', DB_HOST_LOCAL);
    define('DB_USER', DB_USER_LOCAL);
    define('DB_PASS', DB_PASS_LOCAL);
    define('DB_NAME', DB_NAME_LOCAL);
} else {
    define('DB_HOST', DB_HOST_PROD);
    define('DB_USER', DB_USER_PROD);
    define('DB_PASS', DB_PASS_PROD);
    define('DB_NAME', DB_NAME_PROD);
}

// ============================================
// FUNCIÓN: Obtener Conexión a la Base de Datos
// ============================================
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            // Verificar conexión
            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }
            
            // Configurar charset UTF-8
            $conn->set_charset("utf8mb4");
            
        } catch (Exception $e) {
            // Log del error (en producción, no mostrar detalles al usuario)
            error_log("Error de base de datos: " . $e->getMessage());
            
            if (IS_LOCAL) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            } else {
                die("Error de conexión. Por favor, contacta al administrador.");
            }
        }
    }
    
    return $conn;
}

// Alias para compatibilidad
function getConnection() {
    return getDBConnection();
}

// ============================================
// FUNCIÓN: Cerrar Conexión
// ============================================
function closeDBConnection() {
    $conn = getDBConnection();
    if ($conn) {
        $conn->close();
    }
}

// ============================================
// FUNCIÓN: Ejecutar Query con Protección SQL Injection
// ============================================
function executeQuery($query, $params = [], $types = '') {
    $conn = getDBConnection();
    
    try {
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error preparando query: " . $conn->error);
        }
        
        // Bind parameters si existen
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        return $stmt;
        
    } catch (Exception $e) {
        error_log("Error ejecutando query: " . $e->getMessage());
        throw $e;
    }
}

// ============================================
// FUNCIÓN: Obtener Todos los Productos
// ============================================
function getAllProductos($soloActivos = false) {
    $conn = getDBConnection();
    
    $query = "SELECT * FROM productos";
    if ($soloActivos) {
        $query .= " WHERE activo = 1";
    }
    $query .= " ORDER BY fecha_creacion DESC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Error obteniendo productos: " . $conn->error);
    }
    
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        // Convertir activo a boolean para compatibilidad con JSON anterior
        $row['activo'] = (bool)$row['activo'];
        $productos[] = $row;
    }
    
    return $productos;
}

// ============================================
// FUNCIÓN: Obtener Producto por ID
// ============================================
function getProductoById($id) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return null;
    }
    
    $producto = $result->fetch_assoc();
    $producto['activo'] = (bool)$producto['activo'];
    
    return $producto;
}

// ============================================
// FUNCIÓN: Crear Nuevo Producto
// ============================================
function createProducto($data) {
    $conn = getDBConnection();
    
    $query = "INSERT INTO productos (nombre, precio, categoria, descripcion, imagen, 
              material, tiempo_entrega, personalizacion, garantia, activo) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sdsssssssi",
        $data['nombre'],
        $data['precio'],
        $data['categoria'],
        $data['descripcion'],
        $data['imagen'],
        $data['material'],
        $data['tiempo_entrega'],
        $data['personalizacion'],
        $data['garantia'],
        $data['activo']
    );
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    
    throw new Exception("Error creando producto: " . $conn->error);
}

// ============================================
// FUNCIÓN: Actualizar Producto
// ============================================
function updateProducto($id, $data) {
    $conn = getDBConnection();
    
    $query = "UPDATE productos SET 
              nombre = ?, precio = ?, categoria = ?, descripcion = ?, 
              imagen = ?, material = ?, tiempo_entrega = ?, personalizacion = ?, 
              garantia = ?, activo = ?
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sdsssssssii",
        $data['nombre'],
        $data['precio'],
        $data['categoria'],
        $data['descripcion'],
        $data['imagen'],
        $data['material'],
        $data['tiempo_entrega'],
        $data['personalizacion'],
        $data['garantia'],
        $data['activo'],
        $id
    );
    
    return $stmt->execute();
}

// ============================================
// FUNCIÓN: Eliminar Producto
// ============================================
function deleteProducto($id) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

// ============================================
// FUNCIÓN: Toggle Estado Activo
// ============================================
function toggleProductoActivo($id) {
    $conn = getDBConnection();
    
    $stmt = $conn->prepare("UPDATE productos SET activo = NOT activo WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

// ============================================
// FUNCIÓN: Obtener Estadísticas
// ============================================
function getEstadisticas() {
    $conn = getDBConnection();
    
    $stats = [];
    
    // Total de productos
    $result = $conn->query("SELECT COUNT(*) as total FROM productos");
    $stats['total_productos'] = $result->fetch_assoc()['total'];
    
    // Productos activos
    $result = $conn->query("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
    $stats['productos_activos'] = $result->fetch_assoc()['total'];
    
    // Productos por categoría
    $result = $conn->query("SELECT categoria, COUNT(*) as total FROM productos GROUP BY categoria");
    $stats['por_categoria'] = [];
    while ($row = $result->fetch_assoc()) {
        $stats['por_categoria'][$row['categoria']] = $row['total'];
    }
    
    // Solicitudes pendientes
    $result = $conn->query("SELECT COUNT(*) as total FROM solicitudes WHERE estado = 'pendiente'");
    $stats['solicitudes_pendientes'] = $result->fetch_assoc()['total'];
    
    return $stats;
}

// ============================================
// NOTAS DE USO:
// ============================================
// 1. Para usar en cualquier archivo PHP:
//    require_once 'database.php';
//    $productos = getAllProductos();
//
// 2. La conexión se cierra automáticamente al final del script
//
// 3. Todas las funciones usan prepared statements
//    para prevenir SQL injection
//
// 4. En local usa las credenciales LOCAL
//    En producción usa las credenciales PROD
// ============================================
?>
