<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Verificar sesión de admin
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

try {
    // Verificar que se subió un archivo
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se subió ningún archivo o hubo un error');
    }
    
    $archivo = $_FILES['imagen'];
    
    // Validar tipo de archivo
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($archivo['type'], $tiposPermitidos)) {
        throw new Exception('Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP');
    }
    
    // Validar tamaño (máximo 5MB)
    $tamañoMaximo = 5 * 1024 * 1024; // 5MB
    if ($archivo['size'] > $tamañoMaximo) {
        throw new Exception('El archivo es demasiado grande. Máximo 5MB');
    }
    
    // Crear directorio si no existe
    $directorioUploads = '../data/uploads/';
    if (!is_dir($directorioUploads)) {
        mkdir($directorioUploads, 0755, true);
    }
    
    // Generar nombre único para el archivo
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'perfume_' . time() . '_' . uniqid() . '.' . strtolower($extension);
    $rutaCompleta = $directorioUploads . $nombreArchivo;
    
    // Mover archivo subido
    if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
        throw new Exception('Error al guardar el archivo');
    }
    
    // Redimensionar imagen si es necesario
    redimensionarImagen($rutaCompleta, 800, 600);
    
    // Ruta relativa para guardar en la base de datos
    $rutaRelativa = 'data/uploads/' . $nombreArchivo;
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'mensaje' => 'Imagen subida exitosamente',
        'ruta' => $rutaRelativa,
        'nombre' => $nombreArchivo
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// Función para redimensionar imagen
function redimensionarImagen($rutaArchivo, $anchoMax, $altoMax) {
    // Obtener información de la imagen
    $info = getimagesize($rutaArchivo);
    if (!$info) return false;
    
    $anchoOriginal = $info[0];
    $altoOriginal = $info[1];
    $tipo = $info[2];
    
    // Si la imagen ya es más pequeña, no hacer nada
    if ($anchoOriginal <= $anchoMax && $altoOriginal <= $altoMax) {
        return true;
    }
    
    // Calcular nuevas dimensiones manteniendo proporción
    $ratio = min($anchoMax / $anchoOriginal, $altoMax / $altoOriginal);
    $nuevoAncho = round($anchoOriginal * $ratio);
    $nuevoAlto = round($altoOriginal * $ratio);
    
    // Crear imagen desde archivo según el tipo
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagenOriginal = imagecreatefromjpeg($rutaArchivo);
            break;
        case IMAGETYPE_PNG:
            $imagenOriginal = imagecreatefrompng($rutaArchivo);
            break;
        case IMAGETYPE_GIF:
            $imagenOriginal = imagecreatefromgif($rutaArchivo);
            break;
        case IMAGETYPE_WEBP:
            $imagenOriginal = imagecreatefromwebp($rutaArchivo);
            break;
        default:
            return false;
    }
    
    if (!$imagenOriginal) return false;
    
    // Crear nueva imagen redimensionada
    $imagenNueva = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
    
    // Preservar transparencia para PNG y GIF
    if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_GIF) {
        imagealphablending($imagenNueva, false);
        imagesavealpha($imagenNueva, true);
        $transparente = imagecolorallocatealpha($imagenNueva, 255, 255, 255, 127);
        imagefill($imagenNueva, 0, 0, $transparente);
    }
    
    // Redimensionar
    imagecopyresampled(
        $imagenNueva, $imagenOriginal,
        0, 0, 0, 0,
        $nuevoAncho, $nuevoAlto,
        $anchoOriginal, $altoOriginal
    );
    
    // Guardar imagen redimensionada
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($imagenNueva, $rutaArchivo, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($imagenNueva, $rutaArchivo, 8);
            break;
        case IMAGETYPE_GIF:
            imagegif($imagenNueva, $rutaArchivo);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($imagenNueva, $rutaArchivo, 85);
            break;
    }
    
    // Limpiar memoria
    imagedestroy($imagenOriginal);
    imagedestroy($imagenNueva);
    
    return true;
}
?>
