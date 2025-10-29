<?php
/**
 * JOYERÍA MATT - Gestor de Imágenes
 * 
 * Sistema para subir, ver y gestionar imágenes de productos
 */

require_once 'auth.php';

// Configuración
$uploadDir = '../data/uploads/';
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

$message = '';
$messageType = '';

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ============================================
// PROCESAR SUBIDA DE IMAGEN
// ============================================
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['imagen'];
    
    // Validar tipo de archivo
    if (!in_array($file['type'], $allowedTypes)) {
        $message = 'Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP';
        $messageType = 'danger';
    }
    // Validar tamaño
    elseif ($file['size'] > $maxFileSize) {
        $message = 'El archivo es demasiado grande. Máximo 5MB';
        $messageType = 'danger';
    }
    else {
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreLimpio = preg_replace('/[^a-z0-9-]/', '-', strtolower(pathinfo($file['name'], PATHINFO_FILENAME)));
        $nombreArchivo = $nombreLimpio . '-' . time() . '.' . $extension;
        $rutaDestino = $uploadDir . $nombreArchivo;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
            $message = "Imagen subida exitosamente: $nombreArchivo";
            $messageType = 'success';
        } else {
            $message = 'Error al subir la imagen';
            $messageType = 'danger';
        }
    }
}

// ============================================
// PROCESAR ELIMINACIÓN DE IMAGEN
// ============================================
if (isset($_GET['delete'])) {
    $imagenEliminar = basename($_GET['delete']); // Seguridad: solo nombre de archivo
    $rutaImagen = $uploadDir . $imagenEliminar;
    
    if (file_exists($rutaImagen)) {
        if (unlink($rutaImagen)) {
            $message = "Imagen eliminada: $imagenEliminar";
            $messageType = 'success';
        } else {
            $message = 'Error al eliminar la imagen';
            $messageType = 'danger';
        }
    }
}

// ============================================
// OBTENER LISTA DE IMÁGENES
// ============================================
$imagenes = [];
if (is_dir($uploadDir)) {
    $archivos = scandir($uploadDir);
    foreach ($archivos as $archivo) {
        if ($archivo !== '.' && $archivo !== '..') {
            $rutaCompleta = $uploadDir . $archivo;
            if (is_file($rutaCompleta)) {
                $imagenes[] = [
                    'nombre' => $archivo,
                    'ruta' => 'data/uploads/' . $archivo,
                    'tamano' => filesize($rutaCompleta),
                    'fecha' => filemtime($rutaCompleta)
                ];
            }
        }
    }
}

// Ordenar por fecha (más recientes primero)
usort($imagenes, function($a, $b) {
    return $b['fecha'] - $a['fecha'];
});

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Imágenes - Joyería Matt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #D4A574;
            --secondary-color: #8B7355;
        }
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .upload-area {
            border: 3px dashed var(--primary-color);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background: #fff8f0;
            border-color: var(--secondary-color);
        }
        .upload-area.dragover {
            background: #fff8f0;
            border-color: var(--secondary-color);
        }
        .image-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px;
            transition: all 0.3s;
            background: white;
            height: 100%;
        }
        .image-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .image-info {
            font-size: 0.85rem;
            color: #666;
        }
        .copy-btn {
            font-size: 0.75rem;
        }
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-gem me-2"></i>
                Joyería Matt - Admin
            </a>
            <div class="d-flex">
                <a href="dashboard.php" class="btn btn-outline-light me-2">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="productos.php" class="btn btn-outline-light me-2">
                    <i class="fas fa-gem"></i> Productos
                </a>
                <a href="logout.php" class="btn btn-outline-light">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Mensajes -->
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Área de Subida -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Nueva Imagen
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="upload-area" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Arrastra una imagen aquí o haz click para seleccionar</h5>
                        <p class="text-muted">Formatos permitidos: JPG, PNG, GIF, WEBP (Máx. 5MB)</p>
                        <input type="file" 
                               name="imagen" 
                               id="imagenInput" 
                               accept="image/*" 
                               style="display: none;"
                               required>
                        <button type="button" class="btn btn-primary mt-3" onclick="document.getElementById('imagenInput').click()">
                            <i class="fas fa-folder-open"></i> Seleccionar Archivo
                        </button>
                    </div>
                    
                    <div id="previewArea" class="mt-3" style="display: none;">
                        <img id="preview" class="img-fluid rounded" style="max-height: 300px;">
                        <div class="mt-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i> Subir Imagen
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelUpload()">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Galería de Imágenes -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-images me-2"></i>
                        Galería de Imágenes (<?php echo count($imagenes); ?>)
                    </h4>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($imagenes)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay imágenes subidas</h5>
                        <p class="text-muted">Sube tu primera imagen usando el formulario de arriba</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($imagenes as $imagen): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="image-card">
                                <img src="../<?php echo $imagen['ruta']; ?>" 
                                     class="image-preview" 
                                     alt="<?php echo $imagen['nombre']; ?>">
                                
                                <div class="image-info mb-2">
                                    <strong><?php echo $imagen['nombre']; ?></strong><br>
                                    <small>
                                        <i class="fas fa-weight"></i> <?php echo number_format($imagen['tamano'] / 1024, 2); ?> KB<br>
                                        <i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', $imagen['fecha']); ?>
                                    </small>
                                </div>
                                
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" 
                                           class="form-control copy-input" 
                                           value="<?php echo $imagen['ruta']; ?>" 
                                           readonly>
                                    <button class="btn btn-outline-secondary copy-btn" 
                                            type="button"
                                            onclick="copiarRuta('<?php echo $imagen['ruta']; ?>')">
                                        <i class="fas fa-copy"></i> Copiar
                                    </button>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="?delete=<?php echo $imagen['nombre']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Eliminar esta imagen?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Cómo usar las imágenes
                </h5>
            </div>
            <div class="card-body">
                <ol>
                    <li><strong>Subir imagen:</strong> Arrastra o selecciona una imagen para subirla</li>
                    <li><strong>Copiar ruta:</strong> Click en "Copiar" para copiar la ruta de la imagen</li>
                    <li><strong>Usar en producto:</strong> Pega la ruta en el campo "Ruta de Imagen" al agregar/editar un producto</li>
                    <li><strong>Eliminar:</strong> Solo elimina imágenes que no estés usando en ningún producto</li>
                </ol>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Importante:</strong> Antes de eliminar una imagen, asegúrate de que no esté siendo usada por ningún producto.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview de imagen antes de subir
        document.getElementById('imagenInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('previewArea').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop
        const uploadArea = document.getElementById('uploadArea');
        const imagenInput = document.getElementById('imagenInput');

        uploadArea.addEventListener('click', () => {
            imagenInput.click();
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imagenInput.files = files;
                imagenInput.dispatchEvent(new Event('change'));
            }
        });

        // Cancelar upload
        function cancelUpload() {
            document.getElementById('imagenInput').value = '';
            document.getElementById('previewArea').style.display = 'none';
        }

        // Copiar ruta al portapapeles
        function copiarRuta(ruta) {
            navigator.clipboard.writeText(ruta).then(() => {
                // Mostrar feedback
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            });
        }
    </script>
</body>
</html>
