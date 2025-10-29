<?php
/**
 * JOYERÍA MATT - Panel de Administración de Productos
 * Versión MySQL - Reemplaza productos.php
 */

require_once 'auth.php';
require_once 'database.php';

// Variables de control
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$message = '';
$messageType = '';

// ============================================
// FUNCIÓN: Procesar subida de imagen
// ============================================
function procesarImagenProducto($productoId = null) {
    $uploadDir = '../data/uploads/';
    
    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Si hay archivo subido
    if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen_nueva'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        
        // Validar tipo
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG, GIF, WEBP');
        }
        
        // Validar tamaño
        if ($file['size'] > $maxFileSize) {
            throw new Exception('Imagen muy grande. Máximo 5MB');
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreLimpio = preg_replace('/[^a-z0-9-]/', '-', strtolower(pathinfo($file['name'], PATHINFO_FILENAME)));
        $nombreArchivo = $nombreLimpio . '-' . time() . '.' . $extension;
        $rutaDestino = $uploadDir . $nombreArchivo;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
            return 'data/uploads/' . $nombreArchivo;
        } else {
            throw new Exception('Error al subir la imagen');
        }
    }
    
    // Si no hay archivo nuevo, mantener la imagen actual
    return $_POST['imagen_actual'] ?? '';
}

// ============================================
// PROCESAR FORMULARIOS (POST)
// ============================================
if ($_POST) {
    try {
        if ($action === 'add') {
            // Procesar imagen
            $rutaImagen = procesarImagenProducto();
            
            // AGREGAR NUEVO PRODUCTO
            $nuevoProducto = [
                'nombre' => $_POST['nombre'],
                'precio' => floatval($_POST['precio']),
                'categoria' => $_POST['categoria'],
                'descripcion' => $_POST['descripcion'],
                'imagen' => $rutaImagen,
                'material' => $_POST['material'],
                'tiempo_entrega' => $_POST['tiempo_entrega'],
                'personalizacion' => $_POST['personalizacion'],
                'peso_aproximado' => $_POST['peso_aproximado'],
                'garantia' => $_POST['garantia'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            $nuevoId = createProducto($nuevoProducto);
            $message = "Producto agregado exitosamente (ID: $nuevoId)";
            $messageType = 'success';
            $action = 'list';
            
        } elseif ($action === 'edit' && $id) {
            // Procesar imagen (nueva o mantener actual)
            $rutaImagen = procesarImagenProducto($id);
            
            // EDITAR PRODUCTO EXISTENTE
            $productoActualizado = [
                'nombre' => $_POST['nombre'],
                'precio' => floatval($_POST['precio']),
                'categoria' => $_POST['categoria'],
                'descripcion' => $_POST['descripcion'],
                'imagen' => $rutaImagen,
                'material' => $_POST['material'],
                'tiempo_entrega' => $_POST['tiempo_entrega'],
                'personalizacion' => $_POST['personalizacion'],
                'peso_aproximado' => $_POST['peso_aproximado'],
                'garantia' => $_POST['garantia'],
                'activo' => isset($_POST['activo']) ? 1 : 0
            ];
            
            updateProducto($id, $productoActualizado);
            $message = 'Producto actualizado exitosamente';
            $messageType = 'success';
            $action = 'list';
        }
        
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// ============================================
// PROCESAR ACCIONES (GET)
// ============================================
if ($action === 'delete' && $id) {
    try {
        deleteProducto($id);
        $message = 'Producto eliminado exitosamente';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error al eliminar: ' . $e->getMessage();
        $messageType = 'danger';
    }
    $action = 'list';
}

if ($action === 'toggle' && $id) {
    try {
        toggleProductoActivo($id);
        $message = 'Estado del producto actualizado';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'danger';
    }
    $action = 'list';
}

// ============================================
// CARGAR DATOS SEGÚN LA ACCIÓN
// ============================================
$productos = [];
$productoEditar = null;
$categoriaFiltro = $_GET['categoria'] ?? 'todas';

if ($action === 'list') {
    $todosProductos = getAllProductos(); // Todos los productos (activos e inactivos)
    
    // Filtrar por categoría si se seleccionó una
    if ($categoriaFiltro !== 'todas') {
        $productos = array_filter($todosProductos, function($p) use ($categoriaFiltro) {
            return $p['categoria'] === $categoriaFiltro;
        });
    } else {
        $productos = $todosProductos;
    }
    
    // Obtener categorías únicas para el filtro
    $categorias = array_unique(array_column($todosProductos, 'categoria'));
    sort($categorias);
    
} elseif ($action === 'edit' && $id) {
    $productoEditar = getProductoById($id);
    if (!$productoEditar) {
        $message = 'Producto no encontrado';
        $messageType = 'danger';
        $action = 'list';
        $productos = getAllProductos();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Joyería Matt</title>
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
        .btn-primary {
            background: var(--primary-color);
            border: none;
        }
        .btn-primary:hover {
            background: var(--secondary-color);
        }
        .table-hover tbody tr:hover {
            background-color: #fff8f0;
        }
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
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

        <?php if ($action === 'list'): ?>
        <!-- VISTA: LISTA DE PRODUCTOS -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-box-open me-2"></i>
                        Gestión de Productos
                    </h4>
                    <a href="?action=add" class="btn btn-light">
                        <i class="fas fa-plus"></i> Agregar Producto
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-filter"></i> Filtrar por Categoría:</label>
                        <select class="form-select" onchange="window.location.href='?action=list&categoria=' + this.value">
                            <option value="todas" <?php echo $categoriaFiltro === 'todas' ? 'selected' : ''; ?>>
                                📦 Todas las Categorías (<?php echo count($todosProductos); ?>)
                            </option>
                            <?php foreach ($categorias as $cat): ?>
                                <?php 
                                $count = count(array_filter($todosProductos, function($p) use ($cat) {
                                    return $p['categoria'] === $cat;
                                }));
                                ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" 
                                        <?php echo $categoriaFiltro === $cat ? 'selected' : ''; ?>>
                                    💎 <?php echo htmlspecialchars($cat); ?> (<?php echo $count; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8 d-flex align-items-end">
                        <div class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Mostrando <strong><?php echo count($productos); ?></strong> producto(s)
                            <?php if ($categoriaFiltro !== 'todas'): ?>
                                de la categoría <strong><?php echo htmlspecialchars($categoriaFiltro); ?></strong>
                                <a href="?action=list&categoria=todas" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="fas fa-times"></i> Limpiar filtro
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td>
                                    <?php if (file_exists('../' . $producto['imagen'])): ?>
                                        <img src="../<?php echo $producto['imagen']; ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                    <?php else: ?>
                                        <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-gem text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($producto['categoria']); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?> MXN</td>
                                <td>
                                    <?php if ($producto['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="?action=edit&id=<?php echo $producto['id']; ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?action=toggle&id=<?php echo $producto['id']; ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Activar/Desactivar">
                                            <i class="fas fa-toggle-on"></i>
                                        </a>
                                        <a href="?action=delete&id=<?php echo $producto['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Eliminar"
                                           onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Total de productos: <strong><?php echo count($productos); ?></strong>
                    </p>
                </div>
            </div>
        </div>

        <?php elseif ($action === 'add' || $action === 'edit'): ?>
        <!-- VISTA: FORMULARIO (AGREGAR/EDITAR) -->
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-<?php echo $action === 'add' ? 'plus' : 'edit'; ?> me-2"></i>
                    <?php echo $action === 'add' ? 'Agregar Nuevo Producto' : 'Editar Producto'; ?>
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="?action=<?php echo $action; ?><?php echo $id ? '&id='.$id : ''; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Producto *</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombre" 
                                   value="<?php echo $productoEditar['nombre'] ?? ''; ?>" 
                                   required>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio (MXN) *</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="precio" 
                                   step="0.01" 
                                   value="<?php echo $productoEditar['precio'] ?? ''; ?>" 
                                   required>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría *</label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Seleccionar...</option>
                                <option value="Anillos de Graduación" <?php echo ($productoEditar['categoria'] ?? '') === 'Anillos de Graduación' ? 'selected' : ''; ?>>
                                    Anillos de Graduación
                                </option>
                                <option value="Anillos de Compromiso" <?php echo ($productoEditar['categoria'] ?? '') === 'Anillos de Compromiso' ? 'selected' : ''; ?>>
                                    Anillos de Compromiso
                                </option>
                                <option value="Argollas Matrimoniales" <?php echo ($productoEditar['categoria'] ?? '') === 'Argollas Matrimoniales' ? 'selected' : ''; ?>>
                                    Argollas Matrimoniales
                                </option>
                            </select>
                        </div>

                        <!-- Material -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Material</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="material" 
                                   value="<?php echo $productoEditar['material'] ?? ''; ?>" 
                                   placeholder="Ej: Oro 14k, Plata 925">
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" 
                                      name="descripcion" 
                                      rows="4"><?php echo $productoEditar['descripcion'] ?? ''; ?></textarea>
                        </div>

                        <!-- Imagen -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Imagen del Producto</label>
                            
                            <?php if ($action === 'edit' && !empty($productoEditar['imagen'])): ?>
                                <!-- Mostrar imagen actual -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (file_exists('../' . $productoEditar['imagen'])): ?>
                                            <img src="../<?php echo $productoEditar['imagen']; ?>" 
                                                 style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px solid #ddd;">
                                        <?php else: ?>
                                            <div style="width: 150px; height: 150px; border-radius: 10px; border: 2px solid #ddd; display: flex; align-items: center; justify-content: center; background: #f5f5f5;">
                                                <i class="fas fa-gem fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="mb-1"><strong>Imagen actual:</strong></p>
                                            <small class="text-muted"><?php echo basename($productoEditar['imagen']); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="imagen_actual" value="<?php echo $productoEditar['imagen']; ?>">
                            <?php endif; ?>
                            
                            <!-- Campo para subir nueva imagen -->
                            <div class="mb-2">
                                <input type="file" 
                                       class="form-control" 
                                       name="imagen_nueva" 
                                       id="imagenNueva"
                                       accept="image/*"
                                       onchange="previewImagen(this)">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> 
                                    <?php if ($action === 'edit'): ?>
                                        Deja vacío para mantener la imagen actual. Sube una nueva para reemplazarla.
                                    <?php else: ?>
                                        Selecciona una imagen (JPG, PNG, GIF, WEBP - Máx. 5MB)
                                    <?php endif; ?>
                                </small>
                            </div>
                            
                            <!-- Preview de nueva imagen -->
                            <div id="previewContainer" style="display: none;" class="mt-2">
                                <p class="mb-1"><strong>Nueva imagen:</strong></p>
                                <img id="previewImagen" 
                                     style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px solid #D4A574;">
                            </div>
                        </div>

                        <!-- Tiempo de Entrega -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tiempo de Entrega</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="tiempo_entrega" 
                                   value="<?php echo $productoEditar['tiempo_entrega'] ?? ''; ?>" 
                                   placeholder="Ej: 7-10 días hábiles">
                        </div>

                        <!-- Personalización -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Personalización</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="personalizacion" 
                                   value="<?php echo $productoEditar['personalizacion'] ?? ''; ?>" 
                                   placeholder="Ej: Grabado de nombre y fecha">
                        </div>

                        <!-- Peso Aproximado -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peso Aproximado</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="peso_aproximado" 
                                   value="<?php echo $productoEditar['peso_aproximado'] ?? ''; ?>" 
                                   placeholder="Ej: 8-12 gramos">
                        </div>

                        <!-- Garantía -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Garantía</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="garantia" 
                                   value="<?php echo $productoEditar['garantia'] ?? ''; ?>" 
                                   placeholder="Ej: Garantía en legitimidad de materiales">
                        </div>

                        <!-- Estado Activo -->
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="activo" 
                                       id="activo"
                                       <?php echo ($productoEditar['activo'] ?? true) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="activo">
                                    Producto activo (visible en el sitio web)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> 
                            <?php echo $action === 'add' ? 'Agregar Producto' : 'Guardar Cambios'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview de imagen antes de subir
        function previewImagen(input) {
            const previewContainer = document.getElementById('previewContainer');
            const previewImg = document.getElementById('previewImagen');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</body>
</html>
