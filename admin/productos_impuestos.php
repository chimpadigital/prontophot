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

// Obtener el registro de tax con id 1
$query = "SELECT * FROM tax WHERE id = 1";
$res = $conectar->query($query);
$tax = $res->fetch_assoc();

// Si no existe, crear uno por defecto
if (!$tax) {
    $conectar->query("INSERT INTO tax (id, coeficiente) VALUES (1, 1.21)");
    $res = $conectar->query($query);
    $tax = $res->fetch_assoc();
}
?>
<style>
.form-control:disabled {
    background-color: #f3f3f3;
}
</style>
<script>
$(function(){
    // Inicialmente el formulario está deshabilitado
    $('#coeficiente').prop('readonly', true);
    $('.btn-guardar').prop('disabled', true);

    $('#editar').click(function(e){
        e.preventDefault();
        $('#coeficiente').prop('readonly', false);
        $('.btn-guardar').prop('disabled', false);
        $('#coeficiente').focus();
    });

    $('#updateTax').submit(function(e){
        e.preventDefault();
        if (!confirm("¿Está seguro de que desea actualizar el IVA?")) {
            return false;
        }

        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: data,
            contentType: false,
            dataType: "json",
            cache: false,
            processData: false,
            success: function(data){
                if (data.success){
                    $('#coeficiente').prop('readonly', true);
                    $('.btn-guardar').prop('disabled', true);
                    $('#ultima').html(data.fecha);
                    alert('IVA actualizado correctamente');
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
                            <a class="nav-link active" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="metodosEnvio" href="productos_metodos_envio.php">Métodos de Envío</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <div class="tab-pane fade show active" id="impuestosTab" role="tabpanel">
                            <h5 class="titulo-tabs">Configuración de Impuestos</h5>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <p class="text-muted">
                                        Última actualización: <span id="ultima"><?php echo date('d-m-Y H:i', strtotime($tax['update_at'])); ?></span>
                                    </p>
                                </div>
                            </div>

                            <form action="inc/update_tax.php" id="updateTax" method="post">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="coeficiente">IVA (Coeficiente)</label>
                                            <input type="number"
                                                   step="0.01"
                                                   min="1"
                                                   max="2"
                                                   class="form-control"
                                                   id="coeficiente"
                                                   name="coeficiente"
                                                   value="<?php echo $tax['coeficiente']; ?>"
                                                   readonly
                                                   required>
                                            <small class="form-text text-muted">
                                                Ejemplo: 1.21 representa un IVA del 21%
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="button" id="editar" class="btn btn-primary">
                                            Editar
                                        </button>
                                        <button type="submit" class="btn btn-warning btn-guardar">
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </form>
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

<?php include ('footer.php'); ?>
