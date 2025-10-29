<?php
/**
 * JOYERÍA MATT - API de Solicitudes
 * 
 * Procesa solicitudes de contacto y redirige a WhatsApp
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../admin/database.php';

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

try {
    // Obtener datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $tipo = trim($_POST['tipo'] ?? 'consulta'); // consulta, cotizacion, personalizado
    $producto_id = isset($_POST['producto_id']) ? intval($_POST['producto_id']) : null;
    
    // Validaciones básicas
    if (empty($nombre)) {
        throw new Exception('El nombre es requerido');
    }
    
    if (empty($email) && empty($telefono)) {
        throw new Exception('Debes proporcionar email o teléfono');
    }
    
    if (empty($mensaje)) {
        throw new Exception('El mensaje es requerido');
    }
    
    // Validar email si se proporcionó
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }
    
    // Guardar en base de datos
    $conn = getConnection();
    
    $sql = "INSERT INTO solicitudes (
        nombre, 
        email, 
        telefono, 
        mensaje, 
        tipo, 
        producto_id,
        fecha_creacion
    ) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssssi',
        $nombre,
        $email,
        $telefono,
        $mensaje,
        $tipo,
        $producto_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Error al guardar la solicitud');
    }
    
    $solicitud_id = $conn->insert_id;
    $stmt->close();
    $conn->close();
    
    // Generar mensaje para WhatsApp
    $whatsappMsg = generarMensajeWhatsApp($nombre, $email, $telefono, $mensaje, $tipo, $producto_id);
    
    // Número de WhatsApp de la joyería (CAMBIAR POR EL NÚMERO REAL)
    $whatsappNumero = '5215512345678'; // Formato: 52 + código de área + número (sin espacios ni guiones)
    
    // URL de WhatsApp
    $whatsappUrl = "https://wa.me/{$whatsappNumero}?text=" . urlencode($whatsappMsg);
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'mensaje' => 'Solicitud guardada exitosamente',
        'solicitud_id' => $solicitud_id,
        'whatsapp_url' => $whatsappUrl
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Generar mensaje formateado para WhatsApp
 */
function generarMensajeWhatsApp($nombre, $email, $telefono, $mensaje, $tipo, $producto_id = null) {
    $msg = "🌟 *Nueva Solicitud - Joyería Matt* 🌟\n\n";
    
    // Tipo de solicitud
    switch ($tipo) {
        case 'cotizacion':
            $msg .= "📋 *Tipo:* Solicitud de Cotización\n";
            break;
        case 'personalizado':
            $msg .= "💎 *Tipo:* Diseño Personalizado\n";
            break;
        default:
            $msg .= "💬 *Tipo:* Consulta General\n";
    }
    
    $msg .= "\n";
    
    // Datos del cliente
    $msg .= "👤 *Cliente:* {$nombre}\n";
    
    if (!empty($email)) {
        $msg .= "📧 *Email:* {$email}\n";
    }
    
    if (!empty($telefono)) {
        $msg .= "📱 *Teléfono:* {$telefono}\n";
    }
    
    $msg .= "\n";
    
    // Producto (si aplica)
    if ($producto_id) {
        $producto = obtenerNombreProducto($producto_id);
        if ($producto) {
            $msg .= "💍 *Producto de interés:* {$producto}\n\n";
        }
    }
    
    // Mensaje
    $msg .= "💬 *Mensaje:*\n{$mensaje}\n\n";
    
    // Pie de mensaje
    $msg .= "---\n";
    $msg .= "📅 " . date('d/m/Y H:i') . "\n";
    $msg .= "🌐 Enviado desde www.joyeriamatt.com";
    
    return $msg;
}

/**
 * Obtener nombre del producto por ID
 */
function obtenerNombreProducto($id) {
    try {
        $conn = getConnection();
        $sql = "SELECT nombre FROM productos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['nombre'];
        }
        
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Si hay error, simplemente no incluir el nombre del producto
    }
    
    return null;
}
?>
