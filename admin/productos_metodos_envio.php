<?php
session_start();
include '../inc/seguridad.inc.php';
if (isset($_SESSION['prontoFront']['token'])) {
    $token=$_SESSION['prontoFront']['token'];
    $key="Pronto";
    $ingresar=verificarToken($token, $key);
    if ($ingresar->success==false) {
        header("Location: ../");
        exit();
    }
}else{
    header("Location: ../");
    exit();
}
?>
<?php include ('header.php'); ?>
<?php
include __DIR__.'/conexion/conectar.inc.php';
global $conectar;

// Obtener todos los métodos de envío
$query = "SELECT * FROM metodos_envio ORDER BY id ASC";
$metodos = $conectar->query($query);
?>
<style>
.form-control:disabled {
    background-color: #f3f3f3;
}
.btn-editar-metodo {
    cursor: pointer;
}
</style>
<script>
$(function(){
    // Editar método de envío
    $(document).on('click', '.btn-editar-metodo', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var valor = $(this).data('valor');

        $('#metodo_id').val(id);
        $('#metodo_nombre').val(nombre);
        $('#metodo_valor').val(valor);

        $('#modalEditarMetodo').modal('show');
    });

    // Guardar cambios del método
    $('#formEditarMetodo').submit(function(e){
        e.preventDefault();
        if (!confirm("¿Está seguro de que desea actualizar este método de envío?")) {
            return false;
        }

        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'inc/update_metodo_envio.php',
            data: data,
            contentType: false,
            dataType: "json",
            cache: false,
            processData: false,
            success: function(data){
                if (data.success){
                    alert('Método de envío actualizado correctamente');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            }
        });
    });
});
</script>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="\"><img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt=""></a>
            </div>
            <hr class="solid my-4">
            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="slider.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin min-vh-100">
            <!-- tab-content -->
            <div class="tab-content" id="v-pills-tabContent">
                <!-- tab-pane -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="productosCargados" href="productos_cargados.php">Productos ya
                                Cargados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link " id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="metodosEnvio" href="productos_metodos_envio.php">Métodos de Envío</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <div class="tab-pane fade show active" id="metodosEnvioTab" role="tabpanel">
                            <h5 class="titulo-tabs">Métodos de Envío</h5>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Valor</th>
                                                    <th>CP</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($metodos && $metodos->num_rows > 0): ?>
                                                    <?php while($metodo = $metodos->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo $metodo['id']; ?></td>
                                                            <td><?php echo htmlspecialchars($metodo['nombre']); ?></td>
                                                            <td>$<?php echo number_format($metodo['valor'], 2); ?></td>
                                                            <td><?php echo $metodo['cp'] ? htmlspecialchars($metodo['cp']) : 'N/A'; ?></td>
                                                            <td>
                                                                <button class="btn btn-sm btn-primary btn-editar-metodo"
                                                                        data-id="<?php echo $metodo['id']; ?>"
                                                                        data-nombre="<?php echo htmlspecialchars($metodo['nombre']); ?>"
                                                                        data-valor="<?php echo $metodo['valor']; ?>">
                                                                    <i class="fa fa-edit"></i> Editar
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No hay métodos de envío registrados</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->
                </div>
                <!-- fin tab-pane -->
            </div>
            <!-- fin tab-content -->
        </div>
    </div>
</div>

<!-- Modal para editar método de envío -->
<div class="modal fade" id="modalEditarMetodo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Método de Envío</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarMetodo">
                <input type="hidden" name="id" id="metodo_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="metodo_nombre">Nombre</label>
                        <input type="text" class="form-control" id="metodo_nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="metodo_valor">Valor</label>
                        <input type="number" step="0.01" class="form-control" id="metodo_valor" name="valor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>