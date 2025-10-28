<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuración
$to_email = "betitopanchito214@gmail.com"; // Email de prueba
$subject_prefix = "[Joyería Matt] Nuevo mensaje de contacto";

// Función para limpiar datos
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

// Obtener datos del formulario
$name = isset($_POST['name']) ? clean_input($_POST['name']) : '';
$email = isset($_POST['email']) ? clean_input($_POST['email']) : '';
$message = isset($_POST['message']) ? clean_input($_POST['message']) : '';

// Validaciones
$errors = [];

if (empty($name)) {
    $errors[] = "El nombre es obligatorio";
}

if (empty($email)) {
    $errors[] = "El email es obligatorio";
} elseif (!validate_email($email)) {
    $errors[] = "El email no es válido";
}

if (empty($message)) {
    $errors[] = "El mensaje es obligatorio";
}

// Si hay errores, devolver error
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        "success" => false, 
        "message" => "Por favor, corrige los siguientes errores:",
        "errors" => $errors
    ]);
    exit;
}

// Preparar el email
$subject = $subject_prefix . " - " . $name;
$email_body = "
Nuevo mensaje de contacto desde Joyería Matt

Nombre: $name
Email: $email
Fecha: " . date('d/m/Y H:i:s') . "

Mensaje:
$message

---
Este mensaje fue enviado desde el formulario de contacto de joyeriamatt.com
";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Enviar email
if (mail($to_email, $subject, $email_body, $headers)) {
    // Email enviado correctamente
    echo json_encode([
        "success" => true,
        "message" => "¡Gracias por tu mensaje! Te responderemos pronto."
    ]);
    
    // Log opcional (crear archivo log.txt si se quiere guardar registro)
    $log_entry = date('Y-m-d H:i:s') . " - Mensaje enviado de: $name ($email)\n";
    file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
} else {
    // Error al enviar email
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error al enviar el mensaje. Por favor, inténtalo de nuevo o contáctanos directamente."
    ]);
}
?>
