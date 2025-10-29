<?php
require_once 'auth.php';
require_once 'database.php';

// Obtener estadísticas desde MySQL
$stats = getEstadisticas();
$productos = getAllProductos();

// Calcular estadísticas
$totalProductos = $stats['total_productos'];
$productosActivos = $stats['productos_activos'];
$productosInactivos = $totalProductos - $productosActivos;

// Calcular precio promedio
$precios = array_column($productos, 'precio');
$precioPromedio = $precios ? array_sum($precios) / count($precios) : 0;

// Productos más caros y más baratos
$productoMasCaro = $precios ? $productos[array_search(max($precios), $precios)] : null;
$productoMasBarato = $precios ? $productos[array_search(min($precios), $precios)] : null;

showAdminHeader('Dashboard');
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            <div class="text-muted">
                <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i') ?>
            </div>
        </div>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="display-4 text-primary mb-2">
                    <i class="fas fa-gem"></i>
                </div>
                <h3 class="card-title"><?= $totalProductos ?></h3>
                <p class="card-text text-muted">Total Joyas</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="display-4 text-success mb-2">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="card-title"><?= $productosActivos ?></h3>
                <p class="card-text text-muted">Joyas Activas</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="display-4 text-warning mb-2">
                    <i class="fas fa-eye-slash"></i>
                </div>
                <h3 class="card-title"><?= $productosInactivos ?></h3>
                <p class="card-text text-muted">Joyas Inactivas</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="display-4 text-info mb-2">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3 class="card-title">$<?= number_format($precioPromedio, 0) ?></h3>
                <p class="card-text text-muted">Precio Promedio</p>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt"></i> Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="productos.php?action=add" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus"></i><br>
                            Agregar Joya
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="productos.php" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-list"></i><br>
                            Ver Todas las Joyas
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="../index.html" target="_blank" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fas fa-external-link-alt"></i><br>
                            Ver Sitio Web
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información de productos destacados -->
<div class="row">
    <?php if ($productoMasCaro): ?>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-crown"></i> Producto Más Caro
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($productoMasCaro['nombre']) ?></h5>
                <p class="card-text">
                    <strong>Precio:</strong> $<?= number_format($productoMasCaro['precio']) ?> MXN<br>
                    <strong>Categoría:</strong> <?= htmlspecialchars($productoMasCaro['categoria']) ?>
                </p>
                <a href="productos.php?action=edit&id=<?= $productoMasCaro['id'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($productoMasBarato): ?>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-tag"></i> Producto Más Económico
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($productoMasBarato['nombre']) ?></h5>
                <p class="card-text">
                    <strong>Precio:</strong> $<?= number_format($productoMasBarato['precio']) ?> MXN<br>
                    <strong>Categoría:</strong> <?= htmlspecialchars($productoMasBarato['categoria']) ?>
                </p>
                <a href="productos.php?action=edit&id=<?= $productoMasBarato['id'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Últimos productos agregados -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock"></i> Últimos Productos Agregados
            </div>
            <div class="card-body">
                <?php if (empty($productos)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No hay productos registrados aún.</p>
                        <a href="productos.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Agregar primer producto
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Mostrar últimos 5 productos
                                $ultimosProductos = array_slice(array_reverse($productos), 0, 5);
                                foreach ($ultimosProductos as $producto): 
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                                    </td>
                                    <td>$<?= number_format($producto['precio']) ?> MXN</td>
                                    <td><?= htmlspecialchars($producto['categoria']) ?></td>
                                    <td>
                                        <?php if ($producto['activo']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="productos.php?action=edit&id=<?= $producto['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="productos.php" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Ver todos los productos
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php showAdminFooter(); ?>
