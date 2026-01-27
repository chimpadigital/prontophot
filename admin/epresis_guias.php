<?php
/**
 * Panel de administración para ver guías de Epresis generadas
 */
include 'header.php';

// Obtener todas las guías con información del pedido
$query = "
    SELECT
        eg.*,
        p.id as pedido_numero,
        p.nombre as cliente_nombre,
        p.total as pedido_total,
        p.estado_pedido,
        p.fecha as pedido_fecha
    FROM epresis_guias eg
    LEFT JOIN pedidos p ON eg.pedido_id = p.id
    ORDER BY eg.fecha_creacion DESC
";

$resultado = $conectar->query($query);
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-truck"></i> Guías de Envío Epresis
            </h2>

            <?php if (!$resultado || $resultado->num_rows === 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No hay guías generadas todavía.
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Pedido</th>
                                        <th>Código Guía</th>
                                        <th>Remito</th>
                                        <th>Cliente</th>
                                        <th>Importe</th>
                                        <th>Zona</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($guia = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $guia['id']; ?></td>
                                        <td>
                                            <a href="pedido.php?id=<?php echo $guia['pedido_id']; ?>" class="btn btn-sm btn-link">
                                                #<?php echo $guia['pedido_numero']; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <strong class="text-primary"><?php echo $guia['codigo_guia']; ?></strong>
                                        </td>
                                        <td><?php echo $guia['remito']; ?></td>
                                        <td><?php echo $guia['cliente_nombre']; ?></td>
                                        <td>$<?php echo number_format($guia['importe'], 2); ?></td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo $guia['sub_zona_destino']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($guia['fecha_creacion'])); ?>
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-info"
                                                onclick="verDetalles(<?php echo $guia['id']; ?>)"
                                                title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Guías</h5>
                                <h2><?php echo $resultado->num_rows; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Facturado</h5>
                                <?php
                                $resultado->data_seek(0);
                                $total = 0;
                                while ($g = $resultado->fetch_assoc()) {
                                    $total += $g['importe'];
                                }
                                ?>
                                <h2>$<?php echo number_format($total, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Guía</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detallesContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalles(guiaId) {
    $('#detallesModal').modal('show');
    $('#detallesContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');

    $.get('inc/get_guia_detalles.php', {id: guiaId}, function(data) {
        if (data.success) {
            let html = `
                <table class="table table-bordered">
                    <tr>
                        <th>Código de Guía</th>
                        <td><strong class="h4 text-primary">${data.guia.codigo_guia}</strong></td>
                    </tr>
                    <tr>
                        <th>Remito</th>
                        <td>${data.guia.remito}</td>
                    </tr>
                    <tr>
                        <th>Importe</th>
                        <td>$${parseFloat(data.guia.importe).toFixed(2)}</td>
                    </tr>
                    <tr>
                        <th>Zona de Destino</th>
                        <td>${data.guia.sub_zona_destino}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Creación</th>
                        <td>${data.guia.fecha_creacion}</td>
                    </tr>
                </table>
                <h5>Respuesta Completa de Epresis</h5>
                <pre class="bg-light p-3">${JSON.stringify(JSON.parse(data.guia.respuesta_json), null, 2)}</pre>
            `;
            $('#detallesContent').html(html);
        } else {
            $('#detallesContent').html('<div class="alert alert-danger">Error al cargar detalles</div>');
        }
    }, 'json');
}
</script>

<?php include 'footer.php'; ?>
