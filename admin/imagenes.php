<?php
require_once 'auth.php';

// Procesar eliminación de imagen
if (isset($_GET['delete'])) {
    $imagenAEliminar = $_GET['delete'];
    $rutaCompleta = '../data/uploads/' . basename($imagenAEliminar);
    
    if (file_exists($rutaCompleta)) {
        if (unlink($rutaCompleta)) {
            $message = 'Imagen eliminada exitosamente';
            $messageType = 'success';
        } else {
            $message = 'Error al eliminar la imagen';
            $messageType = 'danger';
        }
    } else {
        $message = 'La imagen no existe';
        $messageType = 'warning';
    }
}

// Obtener lista de imágenes
$directorioUploads = '../data/uploads/';
$imagenes = [];

if (is_dir($directorioUploads)) {
    $archivos = scandir($directorioUploads);
    foreach ($archivos as $archivo) {
        if ($archivo != '.' && $archivo != '..' && is_file($directorioUploads . $archivo)) {
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $imagenes[] = [
                    'nombre' => $archivo,
                    'ruta' => 'data/uploads/' . $archivo,
                    'tamaño' => filesize($directorioUploads . $archivo),
                    'fecha' => filemtime($directorioUploads . $archivo)
                ];
            }
        }
    }
}

// Ordenar por fecha (más recientes primero)
usort($imagenes, function($a, $b) {
    return $b['fecha'] - $a['fecha'];
});

showAdminHeader('Gestión de Imágenes');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-images"></i> Gestión de Imágenes</h1>
            <a href="productos.php?action=add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Producto
            </a>
        </div>
    </div>
</div>

<?php if (isset($message)): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-folder-open"></i> Imágenes Subidas (<?= count($imagenes) ?>)
            </div>
            <div class="card-body">
                <?php if (empty($imagenes)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-images fa-4x mb-3"></i>
                        <h4>No hay imágenes subidas</h4>
                        <p>Las imágenes que subas desde el formulario de productos aparecerán aquí</p>
                        <a href="productos.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar Primer Producto
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($imagenes as $imagen): ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card h-100">
                                <div class="position-relative">
                                    <img src="../<?= $imagen['ruta'] ?>" 
                                         alt="<?= htmlspecialchars($imagen['nombre']) ?>"
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;">
                                    
                                    <!-- Botones de acción -->
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <div class="btn-group-vertical btn-group-sm">
                                            <button class="btn btn-light btn-sm" 
                                                    onclick="copiarRuta('<?= $imagen['ruta'] ?>')"
                                                    title="Copiar ruta">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="eliminarImagen('<?= $imagen['nombre'] ?>')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h6 class="card-title text-truncate" title="<?= htmlspecialchars($imagen['nombre']) ?>">
                                        <?= htmlspecialchars($imagen['nombre']) ?>
                                    </h6>
                                    <div class="small text-muted">
                                        <div><i class="fas fa-weight"></i> <?= formatearTamaño($imagen['tamaño']) ?></div>
                                        <div><i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', $imagen['fecha']) ?></div>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="input-group input-group-sm">
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?= $imagen['ruta'] ?>" 
                                               readonly
                                               id="ruta-<?= md5($imagen['nombre']) ?>">
                                        <button class="btn btn-outline-secondary" 
                                                onclick="copiarRuta('<?= $imagen['ruta'] ?>', '<?= md5($imagen['nombre']) ?>')"
                                                title="Copiar ruta">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Información sobre las imágenes:</h6>
                                <ul class="mb-0">
                                    <li><strong>Ubicación:</strong> Las imágenes se guardan en <code>data/uploads/</code></li>
                                    <li><strong>Formatos permitidos:</strong> JPG, PNG, GIF, WEBP</li>
                                    <li><strong>Tamaño máximo:</strong> 5MB por imagen</li>
                                    <li><strong>Redimensionado:</strong> Las imágenes se redimensionan automáticamente a 800x600px máximo</li>
                                    <li><strong>Uso:</strong> Copia la ruta y pégala en el campo "URL de imagen" al crear/editar productos</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function copiarRuta(ruta, id = null) {
    // Crear elemento temporal para copiar
    const temp = document.createElement('textarea');
    temp.value = ruta;
    document.body.appendChild(temp);
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    
    // Mostrar feedback
    const btn = event.target.closest('button');
    const iconOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-outline-secondary', 'btn-light');
    
    setTimeout(() => {
        btn.innerHTML = iconOriginal;
        btn.classList.remove('btn-success');
        btn.classList.add(btn.closest('.card-footer') ? 'btn-outline-secondary' : 'btn-light');
    }, 1500);
}

function eliminarImagen(nombre) {
    if (confirm('¿Estás segura de eliminar esta imagen?\n\nNota: Si esta imagen está siendo usada en algún producto, dejará de mostrarse.')) {
        window.location.href = '?delete=' + encodeURIComponent(nombre);
    }
}
</script>

<?php 
function formatearTamaño($bytes) {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 1) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 1) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

showAdminFooter(); 
?>
