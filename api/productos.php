<?php
/**
 * JOYERÍA MATT - API de Productos
 * 
 * Este endpoint devuelve todos los productos activos desde MySQL
 * Usado por el frontend (index.html) para mostrar el catálogo
 * 
 * CAMBIO: Ahora lee de MySQL en lugar de productos.json
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir conexión a base de datos
require_once '../admin/database.php';

try {
    // Obtener solo productos activos desde MySQL
    $productosActivos = getAllProductos(true); // true = solo activos
    
    // Preparar respuesta en el mismo formato que antes
    $response = [
        'success' => true,
        'total' => count($productosActivos),
        'productos' => $productosActivos
    ];
    
    // Devolver JSON
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Error en caso de problemas
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al cargar productos',
        'message' => IS_LOCAL ? $e->getMessage() : 'Error interno del servidor'
    ]);
}
?>
