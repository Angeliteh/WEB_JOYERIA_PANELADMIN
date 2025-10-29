<?php
/**
 * JOYERÍA MATT - Gestión de Solicitudes
 */

require_once 'auth.php';
require_once 'database.php';

// Obtener todas las solicitudes
$conn = getConnection();
$sql = "SELECT * FROM solicitudes ORDER BY fecha_creacion DESC";
$result = $conn->query($sql);
$solicitudes = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $solicitudes[] = $row;
    }
}

$conn->close();

// Mostrar header
showAdminHeader('Solicitudes de Clientes');
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="fas fa-envelope-open-text me-2"></i>
                Solicitudes de Clientes
            </h4>
        </div>
        <div class="card-body">
            <?php if (empty($solicitudes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay solicitudes aún</h5>
                    <p class="text-muted">Las solicitudes de los clientes aparecerán aquí</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Contacto</th>
                                <th>Tipo</th>
                                <th>Mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td><?php echo $solicitud['id']; ?></td>
                                <td>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($solicitud['fecha_creacion'])); ?><br>
                                        <?php echo date('H:i', strtotime($solicitud['fecha_creacion'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($solicitud['nombre']); ?></strong>
                                </td>
                                <td>
                                    <small>
                                        <?php if ($solicitud['email']): ?>
                                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($solicitud['email']); ?><br>
                                        <?php endif; ?>
                                        <?php if ($solicitud['telefono']): ?>
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($solicitud['telefono']); ?>
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php
                                    $badges = [
                                        'consulta' => 'secondary',
                                        'cotizacion' => 'info',
                                        'personalizado' => 'primary'
                                    ];
                                    $badge = $badges[$solicitud['tipo']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $badge; ?>">
                                        <?php echo ucfirst($solicitud['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="verMensaje(<?php echo $solicitud['id']; ?>)">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                </td>
                                <td>
                                    <?php if ($solicitud['telefono']): ?>
                                        <a href="https://wa.me/52<?php echo preg_replace('/[^0-9]/', '', $solicitud['telefono']); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-success">
                                            <i class="fab fa-whatsapp"></i> WhatsApp
                                        </a>
                                    <?php endif; ?>
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

<!-- Modal para ver mensaje completo -->
<div class="modal fade" id="mensajeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje de la Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="mensajeContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos de solicitudes para JavaScript
    const solicitudes = <?php echo json_encode($solicitudes); ?>;
    
    function verMensaje(id) {
        const solicitud = solicitudes.find(s => s.id == id);
        if (solicitud) {
            const content = `
                <div class="mb-3">
                    <strong>Cliente:</strong> ${solicitud.nombre}<br>
                    ${solicitud.email ? `<strong>Email:</strong> ${solicitud.email}<br>` : ''}
                    ${solicitud.telefono ? `<strong>Teléfono:</strong> ${solicitud.telefono}<br>` : ''}
                    <strong>Tipo:</strong> ${solicitud.tipo}<br>
                    <strong>Fecha:</strong> ${new Date(solicitud.fecha_creacion).toLocaleString('es-MX')}
                </div>
                <div class="alert alert-light">
                    <strong>Mensaje:</strong><br>
                    ${solicitud.mensaje.replace(/\n/g, '<br>')}
                </div>
            `;
            document.getElementById('mensajeContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('mensajeModal')).show();
        }
    }
</script>

<?php showAdminFooter(); ?>
