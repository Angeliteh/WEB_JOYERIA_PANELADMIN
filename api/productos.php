<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Incluir configuración
require_once '../admin/config.php';

try {
    // Cargar productos desde JSON
    $data = loadProductos();
    
    // Filtrar solo productos activos para la web pública
    $productosActivos = array_filter($data['productos'], function($producto) {
        return $producto['activo'] === true;
    });
    
    // Reindexar array para que sea secuencial
    $productosActivos = array_values($productosActivos);
    
    // Preparar respuesta
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
        'message' => $e->getMessage()
    ]);
}
?>
