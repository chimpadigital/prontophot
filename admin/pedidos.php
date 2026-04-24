<?php include('header.php'); ?>
<?php
include ('../conexion/conectar.inc.php');
global $conectar;
$sql = "SELECT p.*,(SELECT CONCAT(nombre,' ',apellido) FROM clientes WHERE id=p.id_cliente LIMIT 1 ) as cliente, (SELECT id_producto FROM pedidos_detalle WHERE id_pedido=p.id LIMIT 1 ) as producto,(SELECT thumb FROM pedidos_imagenes WHERE id_pedido=p.id LIMIT 1 ) as imagen,DATE_FORMAT(fecha, '%d-%m-%Y') as fecha FROM pedidos p ORDER BY p.id DESC, p.estado_pedido,p.fecha ASC";
$pedidos = $conectar->query($sql);
//echo $sql.$conectar->error;
function estados($esta)
{
    switch ($esta) {
        case '1':
            return 'Para procesar';
            break;
        case '2':
            return 'Procesado';
            break;
        case '3':
            return 'Enviado';
            break;
        case '4':
            return 'Retirado sucursal';
            break;
    }
}
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
<script>
    $(function() {
        var monkeyList = new List('pedidos', {
            valueNames: ['nombre-pedido', 'codigo-pedido', 'cliente']

        });
        $('.btn-pagar').click(function(e) {
            e.preventDefault();
            if (!confirm("Esta seguro que desea marcar como pagado este pedido?")) {
                return false;
            } else {
                var id = $(this).data('id');
                $.post('inc/cambiar_estado.php', {
                    id: id
                }, function(data) {
                    if (data.success) {
                        location.reload();
                    }
                }, 'json');
            }
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
                <a class="nav-link" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link active" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">

            <div class="tab-content" id="v-pills-tabContent">

                <div class="tab-pane fade show active" id="pedidos" role="tabpanel" aria-labelledby="v-pills-profile-tab">

                    <div class="row">
                        <h5 class="titulo-tabs">Buscador de Pedidos</h5>
                        <form id="formBuscarPedido" class="needs-validation w-100 mt-5 form-carga-producto" novalidate>
                            <div class="form-row">
                                <div class="col-sm-12 col-md-11 d-flex align-items-center"><label class="sr-only" for="buscarPedido"></label>
                                    <input type="text" class="form-control mr-sm-2 search" id="BuscarPedido" placeholder="Escribe aquí Nombre o Código de producto">
                                    <button type="submit" class="btn bg-danger text-white btn-bg-red ml-0 ml-lg-4 btn-large">Ver pedido</button>
                                </div>
                                <div class="col-sm-12 col-md-1 d-flex justify-content-center mt-3 mt-md-0">
                                    <div class="dropdown btn-dropdown-filtro">
                                        <button class="btn dropdown-toggle text-danger p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg fill="currentColor" id="Capa_1" class="text-danger" enable-background="new 0 0 512 512" height="45" viewBox="0 0 512 512" width="45" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path d="m420.404 0h-328.808c-50.506 0-91.596 41.09-91.596 91.596v328.809c0 50.505 41.09 91.595 91.596 91.595h328.809c50.505 0 91.595-41.09 91.595-91.596v-328.808c0-50.506-41.09-91.596-91.596-91.596zm61.596 420.404c0 33.964-27.632 61.596-61.596 61.596h-328.808c-33.964 0-61.596-27.632-61.596-61.596v-328.808c0-33.964 27.632-61.596 61.596-61.596h328.809c33.963 0 61.595 27.632 61.595 61.596z" />
                                                    <path d="m432.733 112.467h-228.461c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-35.661c-8.284 0-15 6.716-15 15s6.716 15 15 15h35.662c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h228.461c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-273.133 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z" />
                                                    <path d="m432.733 241h-35.662c-6.281-18.655-23.927-32.133-44.672-32.133s-38.39 13.478-44.671 32.133h-228.461c-8.284 0-15 6.716-15 15s6.716 15 15 15h228.461c6.281 18.655 23.927 32.133 44.672 32.133s38.391-13.478 44.672-32.133h35.662c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-80.333 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z" />
                                                    <path d="m432.733 369.533h-164.194c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-99.928c-8.284 0-15 6.716-15 15s6.716 15 15 15h99.928c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h164.195c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-208.866 32.134c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.685 17.133 17.132-7.686 17.134-17.133 17.134z" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left filtro-dropdown p-2" data-reference="parent" aria-labelledby="dropdownMenuButton" id="dropdownMenuButton">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                                <label class="form-check-label" for="defaultCheck1"> Email </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                                                <label class="form-check-label" for="defaultCheck2">N° de Pedido</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck3">
                                                <label class="form-check-label" for="defaultCheck3"> Nombre y Apellido</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3 d-flex align-items-center">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row mt-5">
                        <h5 class="titulo-tabs">Pedidos Recientes</h5>
                    </div>
                    <div class="row mt-3 list">
                        <?php if ($pedidos->num_rows > 0) { ?>
                            <?php while ($row = $pedidos->fetch_assoc()) {
                                $id = $row['id'];
                                $idp = $row['producto'];
                                if (!is_null($idp)) {
                                    $imag = $conectar->query("SELECT * FROM imagenes WHERE id_producto='$idp'");
                                    $rowi = $imag->fetch_assoc();
                                    if ($rowi && isset($rowi['imagen'])) {
                                        $imagen = $rowi['imagen'];
                                    } else {
                                        $imagen = null;
                                    }
                                } else {
                                    $imagen = $row['imagen'];
                                }

                                // Obtener todos los productos del pedido con sus colores
                                $productos_query = $conectar->query("
                            SELECT pd.cantidad, pd.color, p.nombre
                            FROM pedidos_detalle pd
                            LEFT JOIN productos p ON pd.id_producto = p.id
                            WHERE pd.id_pedido = '$id'
                        ");

                                $productos_lista = [];
                                $color_html = '';
                                $nombre = '';
                                $cantidad = '';
                                while ($prod = $productos_query->fetch_assoc()) {
                                    if (!empty($prod['color'])) {
                                        $color_html = '<span class="dot" style="background-color: ' . $prod['color'] . '; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span>';
                                    }
                                    $nombre =  $prod['nombre'];
                                    $cantidad = $prod['cantidad'];
                                }

                                $productos_texto = implode('<br>', $productos_lista);

                                $repla = array('<', '>');
                                $boton = '';
                                switch ($row['estado_pedido']) {
                                    case '1':
                                        $boton = ' btn-danger ';
                                        break;
                                    case '2':
                                        $boton = ' btn-info ';
                                        break;
                                    case '3':
                                        $boton = ' btn-success ';
                                        break;
                                    case '4':
                                        $boton = ' btn-warning ';
                                        break;
                                }
                                $pagado = '';
                                switch ($row['estado']) {
                                    case '0':
                                        $pagado = '<button type="button" class="btn btn-outline-danger btn-pagar" data-id="' . $id . '">No pagado</button>';
                                        break;
                                    case '1':
                                        $pagado = '<button type="button" class="btn btn-outline-success">Pagado</button>';
                                        break;
                                }
                            ?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row">
                                        <div class="col-md-3" style="max-height: 220px;overflow: hidden;">
                                            <img class="img-fluid w-100" src="<?php if (!empty($imagen)) {
                                                                                    echo '../' . $imagen;
                                                                                } else {
                                                                                    echo '../img/placeholder.png';
                                                                                } ?>" alt="">
                                        </div>
                                        <div class="col-md-9 d-block d-lg-flex flex-column justify-content-around">
                                            <span style="visibility:hidden;height:1px;" class="cliente"><?php echo $row['cliente']; ?></span>
                                            <div class="d-block d-lg-flex detalles-pedido">

                                                <p>Pedido <br> #<span class="codigo-pedido"><?php echo $row['id']; ?></span> </p>
                                                <p class="mx-0 mx-lg-4">Fecha <br> <?php echo $row['fecha']; ?> </p>
                                            </div>
                                            <div class="descripcion-pedido">
                                                <?php if ($row['descripcion'] === 'Impresion Imagenes') : ?>
                                                    <ul class="list-group">
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0">
                                                            <?php echo $row['descripcion']; ?>
                                                        </li>
                                                    </ul>
                                                <?php else : ?>
                                                    <ul class="list-group">
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0"><?php echo $nombre; ?></li>
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0">Color seleccionado: <?php echo $color_html; ?></li>
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0">Cantidad: <?php echo $cantidad; ?></li>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-block d-lg-flex align-items-center justify-content-between mt-5">
                                                <a href="pedidos_detallePedido.php?id=<?php echo $row['id']; ?>"><button class="btn text-danger border-danger mb-3 mb-md-0">Más Info</button></a>
                                                <?php echo $pagado; ?>
                                                <p class="<?php echo $boton; ?> text-white mensaje-estado m-0 text-center text-md-left"><?php echo estados($row['estado_pedido']); ?></p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>