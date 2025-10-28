<?php
require_once 'auth.php';

// Procesar acciones
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$messageType = '';

// Cargar productos
$data = loadProductos();

// Procesar formularios
if ($_POST) {
    if ($action === 'add') {
        // Agregar nuevo producto
        $nuevoProducto = [
            'id' => generateProductId(),
            'nombre' => $_POST['nombre'],
            'precio' => $_POST['precio'],
            'categoria' => $_POST['categoria'],
            'descripcion' => $_POST['descripcion'],
            'imagen' => $_POST['imagen'] ?: 'images/demo/default.jpg',
            'material' => $_POST['material'],
            'tiempo_entrega' => $_POST['tiempo_entrega'],
            'personalizacion' => $_POST['personalizacion'],
            'peso_aproximado' => $_POST['peso_aproximado'],
            'garantia' => $_POST['garantia'],
            'activo' => isset($_POST['activo']),
            'fecha_creacion' => date('Y-m-d')
        ];
        
        $data['productos'][] = $nuevoProducto;
        
        if (saveProductos($data)) {
            $message = 'Producto agregado exitosamente';
            $messageType = 'success';
            $action = 'list'; // Volver a la lista
        } else {
            $message = 'Error al guardar el producto';
            $messageType = 'danger';
        }
        
    } elseif ($action === 'edit' && $id) {
        // Editar producto existente
        foreach ($data['productos'] as &$producto) {
            if ($producto['id'] == $id) {
                $producto['nombre'] = $_POST['nombre'];
                $producto['precio'] = $_POST['precio'];
                $producto['categoria'] = $_POST['categoria'];
                $producto['descripcion'] = $_POST['descripcion'];
                $producto['imagen'] = $_POST['imagen'] ?: $producto['imagen'];
                $producto['material'] = $_POST['material'];
                $producto['tiempo_entrega'] = $_POST['tiempo_entrega'];
                $producto['personalizacion'] = $_POST['personalizacion'];
                $producto['peso_aproximado'] = $_POST['peso_aproximado'];
                $producto['garantia'] = $_POST['garantia'];
                $producto['activo'] = isset($_POST['activo']);
                break;
            }
        }
        
        if (saveProductos($data)) {
            $message = 'Producto actualizado exitosamente';
            $messageType = 'success';
            $action = 'list'; // Volver a la lista
        } else {
            $message = 'Error al actualizar el producto';
            $messageType = 'danger';
        }
    }
}

// Procesar eliminación
if ($action === 'delete' && $id) {
    $data['productos'] = array_filter($data['productos'], function($p) use ($id) {
        return $p['id'] != $id;
    });
    
    if (saveProductos($data)) {
        $message = 'Producto eliminado exitosamente';
        $messageType = 'success';
    } else {
        $message = 'Error al eliminar el producto';
        $messageType = 'danger';
    }
    $action = 'list';
}

// Procesar toggle activo/inactivo
if ($action === 'toggle' && $id) {
    foreach ($data['productos'] as &$producto) {
        if ($producto['id'] == $id) {
            $producto['activo'] = !$producto['activo'];
            break;
        }
    }
    
    if (saveProductos($data)) {
        $message = 'Estado del producto actualizado';
        $messageType = 'success';
    } else {
        $message = 'Error al actualizar el estado';
        $messageType = 'danger';
    }
    $action = 'list';
}

// Recargar datos después de cambios
$data = loadProductos();

showAdminHeader('Gestión de Productos');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-spray-can"></i> Gestión de Productos</h1>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Producto
                </a>
            <?php else: ?>
                <a href="productos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($message): ?>
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

<?php if ($action === 'list'): ?>
    <!-- LISTA DE PRODUCTOS -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> Lista de Productos (<?= count($data['productos']) ?>)
                </div>
                <div class="card-body">
                    <?php if (empty($data['productos'])): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-4x mb-3"></i>
                            <h4>No hay productos registrados</h4>
                            <p>Comienza agregando tu primer producto al catálogo</p>
                            <a href="?action=add" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i> Agregar Primer Producto
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['productos'] as $producto): ?>
                                    <tr>
                                        <td><strong>#<?= $producto['id'] ?></strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../<?= $producto['imagen'] ?>" 
                                                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                                                    <br><small class="text-muted"><?= substr($producto['descripcion'], 0, 50) ?>...</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><strong>$<?= number_format($producto['precio']) ?></strong> MXN</td>
                                        <td><?= htmlspecialchars($producto['categoria']) ?></td>
                                        <td>
                                            <?php if ($producto['activo']): ?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($producto['fecha_creacion'])) ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="?action=edit&id=<?= $producto['id'] ?>" 
                                                   class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=toggle&id=<?= $producto['id'] ?>" 
                                                   class="btn btn-outline-<?= $producto['activo'] ? 'warning' : 'success' ?>" 
                                                   title="<?= $producto['activo'] ? 'Desactivar' : 'Activar' ?>">
                                                    <i class="fas fa-<?= $producto['activo'] ? 'eye-slash' : 'eye' ?>"></i>
                                                </a>
                                                <a href="?action=delete&id=<?= $producto['id'] ?>" 
                                                   class="btn btn-outline-danger" 
                                                   title="Eliminar"
                                                   onclick="return confirm('¿Estás segura de eliminar este producto?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($action === 'add' || $action === 'edit'): ?>
    <!-- FORMULARIO AGREGAR/EDITAR PRODUCTO -->
    <?php
    $producto = null;
    if ($action === 'edit' && $id) {
        foreach ($data['productos'] as $p) {
            if ($p['id'] == $id) {
                $producto = $p;
                break;
            }
        }
    }
    ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-<?= $action === 'add' ? 'plus' : 'edit' ?>"></i> 
                    <?= $action === 'add' ? 'Agregar Nuevo Producto' : 'Editar Producto' ?>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle"></i> Información Básica
                            </h5>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre de la Joya *</label>
                                        <input type="text" name="nombre" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['nombre']) : '' ?>"
                                               required placeholder="Ej: Anillo de Graduación Ingeniería">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Precio Base (MXN) *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="precio" class="form-control"
                                                   value="<?= $producto ? $producto['precio'] : '' ?>"
                                                   required placeholder="850">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Categoría *</label>
                                        <select name="categoria" class="form-control" required>
                                            <option value="">Seleccionar categoría...</option>
                                            <option value="Anillos de Graduación" <?= (!$producto || $producto['categoria'] === 'Anillos de Graduación') ? 'selected' : '' ?>>Anillos de Graduación</option>
                                            <option value="Anillos de Compromiso" <?= ($producto && $producto['categoria'] === 'Anillos de Compromiso') ? 'selected' : '' ?>>Anillos de Compromiso</option>
                                            <option value="Argollas Matrimoniales" <?= ($producto && $producto['categoria'] === 'Argollas Matrimoniales') ? 'selected' : '' ?>>Argollas Matrimoniales</option>
                                            <option value="Relojes" <?= ($producto && $producto['categoria'] === 'Relojes') ? 'selected' : '' ?>>Relojes</option>
                                            <option value="Carátulas" <?= ($producto && $producto['categoria'] === 'Carátulas') ? 'selected' : '' ?>>Carátulas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Material *</label>
                                        <input type="text" name="material" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['material']) : '' ?>"
                                               required placeholder="Ej: Plata 925, Oro 14k">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción *</label>
                                <textarea name="descripcion" class="form-control" rows="3" required
                                          placeholder="Descripción detallada de la joya, materiales, ocasión de uso..."><?= $producto ? htmlspecialchars($producto['descripcion']) : '' ?></textarea>
                            </div>
                        </div>

                        <hr>

                        <!-- SECCIÓN 2: IMAGEN -->
                        
                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-camera"></i> Imagen del Producto</label>

                            <!-- Vista previa de imagen -->
                            <div class="text-center mb-3">
                                <div id="imagen-preview" class="d-inline-block" <?= !$producto || !$producto['imagen'] ? 'style="display:none !important;"' : '' ?>>
                                    <img id="imagen-preview-img"
                                         src="<?= $producto ? '../' . $producto['imagen'] : '' ?>"
                                         alt="Vista previa"
                                         class="img-thumbnail"
                                         style="max-width: 300px; max-height: 200px; object-fit: cover;">
                                </div>
                                <div id="no-imagen" class="text-muted" <?= $producto && $producto['imagen'] ? 'style="display:none;"' : '' ?>>
                                    <i class="fas fa-image fa-3x mb-2" style="color: #ddd;"></i>
                                    <p>No hay imagen seleccionada</p>
                                </div>
                            </div>

                            <!-- Subida de imagen simplificada -->
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="file"
                                               id="imagen-upload"
                                               name="imagen_file"
                                               class="form-control"
                                               accept="image/*">
                                        <button type="button"
                                                id="cambiar-imagen-btn"
                                                class="btn btn-outline-primary">
                                            <i class="fas fa-upload"></i> Subir Imagen
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle"></i>
                                        Formatos: JPG, PNG, GIF, WEBP • Máximo: 5MB
                                    </small>
                                </div>
                            </div>

                            <!-- Campo oculto para la ruta de la imagen -->
                            <input type="hidden" id="imagen-url" name="imagen" value="<?= $producto ? htmlspecialchars($producto['imagen']) : '' ?>">

                            <!-- Progreso y mensajes -->
                            <div id="upload-progress" class="mt-3" style="display:none;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted d-block text-center mt-1">Subiendo imagen...</small>
                            </div>

                            <div id="imagen-error" class="alert alert-danger mt-3" style="display:none;"></div>
                            <div id="imagen-success" class="alert alert-success mt-3" style="display:none;"></div>
                        </div>

                        <hr>

                        <!-- SECCIÓN 3: DETALLES DE LA JOYA -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-gem"></i> Detalles de la Joya
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-clock text-warning"></i> Tiempo de Entrega
                                        </label>
                                        <input type="text" name="tiempo_entrega" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['tiempo_entrega']) : '' ?>"
                                               placeholder="Ej: 7-10 días hábiles">
                                        <small class="text-muted">Tiempo estimado de fabricación</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-weight text-info"></i> Peso Aproximado
                                        </label>
                                        <input type="text" name="peso_aproximado" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['peso_aproximado']) : '' ?>"
                                               placeholder="Ej: 8-12 gramos">
                                        <small class="text-muted">Peso estimado de la joya</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-edit text-success"></i> Personalización Disponible
                                        </label>
                                        <input type="text" name="personalizacion" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['personalizacion']) : '' ?>"
                                               placeholder="Ej: Grabado de nombres, fechas">
                                        <small class="text-muted">Opciones de personalización</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-shield-alt text-primary"></i> Garantía
                                        </label>
                                        <input type="text" name="garantia" class="form-control"
                                               value="<?= $producto ? htmlspecialchars($producto['garantia']) : '' ?>"
                                               placeholder="Ej: 6 meses contra defectos">
                                        <small class="text-muted">Garantía ofrecida</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- SECCIÓN 4: CONFIGURACIÓN -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-cog"></i> Configuración
                            </h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Estado del Producto</label>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="activo" class="form-check-input" id="activo"
                                                   <?= (!$producto || $producto['activo']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="activo">
                                                <strong>Producto activo</strong> (visible en el sitio web)
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            Desactiva el producto para ocultarlo temporalmente sin eliminarlo
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i>
                                <?= $action === 'add' ? 'Agregar Producto' : 'Guardar Cambios' ?>
                            </button>
                            <a href="productos.php" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Volver a la lista
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<script>
// JavaScript simplificado para subida de imágenes
document.addEventListener('DOMContentLoaded', function() {
    const imagenUpload = document.getElementById('imagen-upload');
    const imagenUrl = document.getElementById('imagen-url');
    const imagenPreview = document.getElementById('imagen-preview');
    const imagenPreviewImg = document.getElementById('imagen-preview-img');
    const noImagen = document.getElementById('no-imagen');
    const uploadProgress = document.getElementById('upload-progress');
    const imagenError = document.getElementById('imagen-error');
    const imagenSuccess = document.getElementById('imagen-success');

    // Manejar subida de archivo
    if (imagenUpload) {
        imagenUpload.addEventListener('change', function(e) {
            const archivo = e.target.files[0];
            if (!archivo) return;

            // Validar archivo
            if (!validarArchivo(archivo)) return;

            // Mostrar vista previa inmediata
            mostrarVistaPrevia(archivo);

            // Subir archivo automáticamente
            subirArchivo(archivo);
        });
    }

    function validarArchivo(archivo) {
        // Validar tipo
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!tiposPermitidos.includes(archivo.type)) {
            mostrarError('Solo se permiten imágenes: JPG, PNG, GIF, WEBP');
            return false;
        }

        // Validar tamaño (5MB)
        if (archivo.size > 5 * 1024 * 1024) {
            mostrarError('La imagen es demasiado grande. Máximo 5MB');
            return false;
        }

        return true;
    }

    function mostrarVistaPrevia(archivo) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagenPreviewImg.src = e.target.result;
            imagenPreview.style.display = 'block';
            noImagen.style.display = 'none';
        };
        reader.readAsDataURL(archivo);
    }

    function subirArchivo(archivo) {
        const formData = new FormData();
        formData.append('imagen', archivo);

        // Mostrar progreso
        uploadProgress.style.display = 'block';
        const progressBar = uploadProgress.querySelector('.progress-bar');

        // Crear XMLHttpRequest para mostrar progreso
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const porcentaje = (e.loaded / e.total) * 100;
                progressBar.style.width = porcentaje + '%';
                progressBar.textContent = Math.round(porcentaje) + '%';
            }
        });

        xhr.addEventListener('load', function() {
            uploadProgress.style.display = 'none';

            if (xhr.status === 200) {
                try {
                    const respuesta = JSON.parse(xhr.responseText);
                    if (respuesta.success) {
                        // Actualizar campo URL con la ruta de la imagen subida
                        imagenUrl.value = respuesta.ruta;
                        mostrarExito('Imagen subida exitosamente');

                        // Limpiar input de archivo
                        imagenUpload.value = '';
                    } else {
                        mostrarError(respuesta.error || 'Error al subir la imagen');
                    }
                } catch (e) {
                    mostrarError('Error al procesar la respuesta del servidor');
                }
            } else {
                mostrarError('Error de conexión al subir la imagen');
            }
        });

        xhr.addEventListener('error', function() {
            uploadProgress.style.display = 'none';
            mostrarError('Error de red al subir la imagen');
        });

        xhr.open('POST', '../api/upload.php');
        xhr.send(formData);
    }

    function mostrarError(mensaje) {
        imagenError.textContent = mensaje;
        imagenError.style.display = 'block';
        imagenSuccess.style.display = 'none';
        setTimeout(() => {
            imagenError.style.display = 'none';
        }, 5000);
    }

    function mostrarExito(mensaje) {
        imagenSuccess.textContent = mensaje;
        imagenSuccess.style.display = 'block';
        imagenError.style.display = 'none';
        setTimeout(() => {
            imagenSuccess.style.display = 'none';
        }, 3000);
    }
});
</script>

<?php showAdminFooter(); ?>
