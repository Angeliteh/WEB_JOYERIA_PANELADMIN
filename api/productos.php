<?php
/**
 * JOYERÍA MATT - API de Productos
 * 
 * Este endpoint devuelve todos los productos activos desde MySQL
 * Usado por el frontend (index.html) para mostrar el catálogo
 */

// Deshabilitar cualquier output previo
@ob_end_clean();
ob_start();

// Headers JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Incluir conexión a base de datos
    require_once '../admin/database.php';
    
    // Obtener solo productos activos desde MySQL
    $productosActivos = getAllProductos(true); // true = solo activos
    
    // Si no hay productos, devolver array vacío
    if ($productosActivos === null || $productosActivos === false) {
        $productosActivos = [];
    }
    
    // Preparar respuesta
    $response = [
        'success' => true,
        'total' => count($productosActivos),
        'productos' => $productosActivos
    ];
    
    // Limpiar cualquier output buffer
    ob_clean();
    
    // Devolver JSON limpio
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    // Limpiar buffer en caso de error
    ob_clean();
    
    // Error - devolver JSON válido siempre
    http_response_code(500);
    
    $errorResponse = [
        'success' => false,
        'error' => 'Error al cargar productos',
        'message' => $e->getMessage(),
        'productos' => []
    ];
    
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
}

// Enviar buffer y terminar
ob_end_flush();
exit;
?>
