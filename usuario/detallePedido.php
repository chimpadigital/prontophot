<?php include('header_usuario.php'); ?>
<?php
include '../inc/funciones.inc.php';
include_once '../conexion/conectar.inc.php';
global $conectar;
$id = $_GET['id'];
$pedidod = $conectar->query("SELECT p.*,CONCAT(c.nombre,' ',c.apellido) as clientenombre,c.dni clientedni,CONCAT(c.direccion,' ',c.ciudad) as clientedireccion,c.cp clientecp,c.provincia clienteprovincia,c.telefono clientetelefono FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE p.id='$id'");

$pedido = $pedidod->fetch_assoc();

$imagenes = $conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido='$id'");
$productos = $conectar->query("SELECT pd.cantidad, pd.color, p.*,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM pedidos_detalle pd LEFT JOIN productos p ON pd.id_producto=p.id WHERE pd.id_pedido='$id'");

// Obtener datos de facturación
$facturacion = $conectar->query("SELECT * FROM facturacion WHERE pedido_id='$id'");
$rowf = $facturacion->fetch_assoc();
?>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="/"><img class="logo-blanco" src="../assets/img/logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg
                        class="bi text-yellow mr-3" width="32" height="32">
                        <use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Mis Compras</a>
                <a class="nav-link" id="v-pills-profile-tab" href="miPerfil.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#person-circle" />
                    </svg>Mi Perfil</a>

            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">

            <!-- tab-content -->
            <div class="tab-content" id="v-pills-tabContent">
                <!-- tab-pane -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS MIS COMPRAS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin pl-0 pl-lg-5" id="tabMisCompras"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="productosCargados" href="misCompras.php">Mis
                                Compras</a>
                        </li>
                    </ul>
                    <!-- FIN TABS MIS COMPRAS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="misCompras" role="tabpanel"
                            aria-labelledby="home-tab">

                            <h5 class="titulo-tabs-user">Detalle de el pedido #<?php echo $pedido['id']; ?></h5>
                            <?php if ($productos->num_rows > 0) { ?>
                                <div class="row my-3">
                                    <?php
                                    while ($rowp = $productos->fetch_assoc()) {
                                        // Generar HTML del color
                                        $color_html = '';
                                        if (!empty($rowp['color'])) {
                                            $color_html = '<span class="dot" style="background-color: ' . $rowp['color'] . '; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span>';
                                        }
                                    ?>
                                        <div class="col-12 p-4 col-categoria my-2">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <img class="img-fluid w-100" src="<?php if (!empty($rowp['imagen'])) {
                                                                                            echo '../' . $rowp['imagen'];
                                                                                        } else {
                                                                                            echo 'https://via.placeholder.com/50';
                                                                                        } ?>" alt="">
                                                </div>
                                                <div class="col-md-10 d-block d-lg-flex flex-column">
                                                    <div class="d-block d-lg-flex">
                                                        <ul class="list-group">
                                                            <li class="list-group-item bg-transparent border-0 mb-0 p-0"><strong><?php echo $rowp['nombre']; ?></strong></li>
                                                            <?php if (!empty($rowp['color'])): ?>
                                                                <li class="list-group-item bg-transparent border-0 mb-0 p-0">Color seleccionado: <?php echo $color_html; ?></li>
                                                            <?php endif; ?>
                                                            <li class="list-group-item bg-transparent border-0 mb-0 p-0">Cantidad: <?php echo $rowp['cantidad'] ?></li>
                                                        </ul>
                                                    </div>
                                                    <p class="descripcion-pedido"><?php echo $rowp['descripcion'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>


                                </div>
                            <?php } ?>
                            <?php if ($imagenes->num_rows > 0) { ?>
                                <h4 class="text-bold">Fotos Reveladas</h4>

                                <div class="row align-items-lg-center mt-5">
                                    <div class="col-md-1 d-none d-md-block">
                                        <button class="customNextBtn btn"><svg xmlns="http://www.w3.org/2000/svg" width="40"
                                                height="40" fill="currentColor" class="text-danger bi bi-chevron-left"
                                                viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                            </svg></button>

                                    </div>
                                    <div class="col-md-10 col-sm-12">
                                        <div class="owl-carousel owl-theme">
                                            <?php
                                            $i = 1;
                                            while ($row = $imagenes->fetch_assoc()) { ?>
                                                <div class="d-flex flex-column align-items-center">
                                                    <h5 class="align-self-baseline text-bold">Foto <?php echo $i; ?> </h5>
                                                    <img class="shadow-sm" src="../<?php echo $row['thumb']; ?>" alt="">
                                                    <p class="mt-3">Tamaño : <?php echo $row['formato'] ?>, acabado <?php echo $row['acabado']; ?></p>
                                                    <p>Cantidad <?php echo $row['cantidad']; ?></p>
                                                </div>
                                            <?php
                                                $i++;
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-none d-md-block">
                                        <button class="customNextBtn btn"><svg xmlns="http://www.w3.org/2000/svg" width="40"
                                                height="40" fill="currentColor" class="text-danger bi bi-chevron-right"
                                                viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                            </svg></button>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row align-items-lg-center mt-5">
                                <div class="col-md-12 p-0">
                                    <h4 class="text-bold">Estado del Pedido</h4>
                                </div>
                            </div>

                            <div class="row mt-3 mb-3">
                                <div class="col-md-12">
                                    <?php if ($pedido['status'] == 1): ?>
                                        <div class="alert alert-success">
                                            <strong>✓ Pedido Pagado</strong> - Tu pedido #<?php echo $pedido['id']; ?> ha sido confirmado y está siendo procesado.
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <strong>⚠ Pedido Impago</strong> - Tu pedido #<?php echo $pedido['id']; ?> se encuentra impago. Comunicate con nosotros para finalizar el pago.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row align-items-lg-center mt-4">
                                <div class="col-md-12 p-0">
                                    <h4 class="text-bold">Datos de Envíos</h4>
                                </div>
                            </div>


                            <div class="row mt-4 mb-5 datos-envio">
                                <div class="col-md-8 p-4 shadowBox">
                                    <?php
                                    if ($pedido['envio'] == 'domicilio') {
                                        $titulo = 'Enviar a mi domicilio';
                                        $nombre = $pedido['clientenombre'];
                                        $dni = $pedido['clientedni'];
                                        $direccion = $pedido['clientedireccion'];
                                        $provincia = $pedido['clienteprovincia'];
                                        $cp = $pedido['clientecp'];
                                        $telefono = $pedido['clientetelefono'];
                                    } else {
                                        $titulo = 'Enviar como regalo';
                                        $nombre = $pedido['nombre'];
                                        $dni = $pedido['dni'];
                                        $direccion = $pedido['direccion'];
                                        $provincia = $pedido['provincia'];
                                        $cp = $pedido['cp'];
                                        $telefono = $pedido['telefono'];
                                    }

                                    switch ($pedido['entrega']) {
                                        case 'envio_2': // Epresis
                                            echo '<strong>Datos de envio (EPSA)</strong><br>' .
                                                '<div> Nombre y Apellido:  ' . $nombre . '</div>' .
                                                '<div> DNI:  ' . $dni . '</div>' .
                                                '<div>Dirección:  ' . $direccion . '</div>' .
                                                '<div> Provincia:  ' . $provincia . '</div>' .
                                                '<div>Código Postal:  ' . $cp . '</div>' .
                                                '<div>Teléfono:  ' . $telefono . '</div>';
                                            break;
                                        case 'suc1':
                                            echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 1</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>' .
                                                '<strong>Datos de usuario</strong><br>' .
                                                '<div> Nombre y Apellido:  ' . $nombre . '</div>' .
                                                '<div> DNI:  ' . $dni . '</div>' .
                                                '<div>Dirección:  ' . $direccion . '</div>' .
                                                '<div> Provincia:  ' . $provincia . '</div>' .
                                                '<div>Código Postal:  ' . $cp . '</div>' .
                                                '<div>Teléfono:  ' . $telefono . '</div>';
                                            break;
                                        case 'suc2':
                                            echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 2</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>' .
                                                '<strong>Datos de usuario</strong><br>' .
                                                '<div> Nombre y Apellido:  ' . $nombre . '</div>' .
                                                '<div> DNI:  ' . $dni . '</div>' .
                                                '<div>Dirección:  ' . $direccion . '</div>' .
                                                '<div> Provincia:  ' . $provincia . '</div>' .
                                                '<div>Código Postal:  ' . $cp . '</div>' .
                                                '<div>Teléfono:  ' . $telefono . '</div>';
                                            break;
                                        case 'suc3':
                                            echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 3</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>' .
                                                '<strong>Datos de usuario</strong><br>' .
                                                '<div> Nombre y Apellido:  ' . $nombre . '</div>' .
                                                '<div> DNI:  ' . $dni . '</div>' .
                                                '<div>Dirección:  ' . $direccion . '</div>' .
                                                '<div> Provincia:  ' . $provincia . '</div>' .
                                                '<div>Código Postal:  ' . $cp . '</div>' .
                                                '<div>Teléfono:  ' . $telefono . '</div>';
                                            break;
                                        case 'envio_1':
                                            '<strong>Datos de envio casco urbano</strong><br>' .
                                                '<div> Nombre y Apellido:  ' . $nombre . '</div>' .
                                                '<div> DNI:  ' . $dni . '</div>' .
                                                '<div>Dirección:  ' . $direccion . '</div>' .
                                                '<div> Provincia:  ' . $provincia . '</div>' .
                                                '<div>Código Postal:  ' . $cp . '</div>' .
                                                '<div>Teléfono:  ' . $telefono . '</div>';
                                            break;
                                        case 'urbano':
                                            echo '<h5 class="mt-4">' . $titulo . '</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nombre y Apellido :' . $nombre . '</p>
                                            <p>DNI :' . $dni . '</p>
                                            <p>Dirección :' . $direccion . '</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Provincia : ' . $provincia . '</p>
                                            <p>CP : ' . $cp . '</p>
                                            <p>Teléfono/Celular : ' . $telefono . '</p>
                                        </div>
                                    </div>';
                                            break;
                                        case 'recibir':
                                            echo '<h5 class="mt-4">' . $titulo . '</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nombre y Apellido :' . $nombre . '</p>
                                            <p>DNI :' . $dni . '</p>
                                            <p>Dirección :' . $direccion . '</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Provincia : ' . $provincia . '</p>
                                            <p>CP : ' . $cp . '</p>
                                            <p>Teléfono/Celular : ' . $telefono . '</p>
                                        </div>
                                    </div>';
                                            break;
                                    } ?>
                                </div>
                                <div class="col-md-3 offset-0 offset-md-1 p-4 shadowBox my-3 my-lg-0">
                                    <h5>Método de Pago</h5>
                                    <p class="text-bold"><?php echo metodoPago($pedido['metodo']); ?></p>
                                    <h4 class="text-bold">Total: $<?php echo $pedido['total'] ?></h4>
                                </div>
                            </div>

                            <?php if ($rowf): ?>
                                <div class="row align-items-lg-center mt-5">
                                    <div class="col-md-12 p-0">
                                        <h4 class="text-bold">Datos de Facturación</h4>
                                    </div>
                                </div>

                                <div class="row mt-4 datos-facturacion">
                                    <div class="col-md-12 p-4 shadowBox">
                                        <h5>Datos del Comprador</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Nombre y Apellido:</strong> <?php echo $rowf['nombre'] . ' ' . $rowf['apellido']; ?></p>
                                                <p><strong>DNI:</strong> <?php echo $rowf['dni']; ?></p>
                                                <?php if (!empty($rowf['cuil'])): ?>
                                                    <p><strong>CUIL:</strong> <?php echo $rowf['cuil']; ?></p>
                                                <?php endif; ?>
                                                <p><strong>Email:</strong> <?php echo $rowf['email']; ?></p>
                                                <p><strong>Dirección:</strong> <?php echo $rowf['direccion']; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Ciudad:</strong> <?php echo $rowf['ciudad']; ?></p>
                                                <p><strong>Provincia:</strong> <?php echo $rowf['provincia']; ?></p>
                                                <p><strong>CP:</strong> <?php echo $rowf['cp']; ?></p>
                                                <p><strong>Teléfono:</strong> <?php echo $rowf['telefono']; ?></p>
                                                <?php if (!empty($rowf['celular'])): ?>
                                                    <p><strong>Celular:</strong> <?php echo $rowf['celular']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if ($rowf['factura_a'] == 1): ?>
                                            <hr class="my-3">
                                            <div class="alert alert-info mb-0">
                                                <i class="fa fa-file-text"></i> <strong>Requiere Factura A</strong>

                                                <?php if (!empty($rowf['cuit'])): ?>
                                                    <p><strong>CUIT:</strong> <?php echo $rowf['cuit']; ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($rowf['razon_social'])): ?>
                                                    <p><strong>Razón Social:</strong> <?php echo $rowf['razon_social']; ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Guía Epresis -->
                            <?php
                            // Verificar si el pedido usa Epresis (metodo_envio_id = 2)
                            if ($pedido['metodo_envio_id'] == 2) {
                                include __DIR__ . '/../inc/epresis_guia.php';
                                $guia_existente = obtenerGuiaEpresis($id);
                            ?>
                                <div class="row align-items-lg-center mt-5">
                                    <div class="col-md-12 p-0">
                                        <h4 class="text-bold">Información de Envío EPSA</h4>
                                    </div>
                                </div>

                                <div class="row mt-4 mb-5">
                                    <div class="col-md-12">
                                        <?php if ($guia_existente): ?>
                                            <div class="alert alert-success p-4">
                                                <h5 class="mb-3"><i class="fa fa-check-circle"></i> Tu pedido tiene guía de envío generada</h5>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <strong>Código de Seguimiento:</strong>
                                                        <p class="h4 text-white"><?php echo $guia_existente['codigo_guia']; ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Número de Remito:</strong>
                                                        <p><?php echo $guia_existente['remito']; ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Importe de Envío:</strong>
                                                        <p>$<?php echo number_format($guia_existente['importe'], 2); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Zona de Destino:</strong>
                                                        <p><?php echo $guia_existente['sub_zona_destino']; ?></p>
                                                    </div>
                                                </div>
                                                <hr class="my-3">
                                                <p class="mb-2"><i class="fa fa-info-circle"></i> <strong>Información del envío:</strong></p>
                                                <p class="mb-1">Generada el <?php echo date('d/m/Y', strtotime($guia_existente['fecha_creacion'])); ?> a las <?php echo date('H:i', strtotime($guia_existente['fecha_creacion'])); ?>hs</p>
                                                <?php if (!empty($pedido['epresis_tiempo_entrega'])): ?>
                                                    <p class="mb-0">Fecha estimada de entrega: <strong><?php echo $pedido['epresis_tiempo_entrega']; ?></strong></p>
                                                <?php endif; ?>
                                                <a target="_blank" href="https://epresis.epsared.com.ar/seguimiento" class="text-white link-underline" style="text-decoration: underline;">Seguir envio</a>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info text-dark p-4">
                                                <h5><i class="fa fa-clock-o"></i> Estamos preparando tu envío</h5>
                                                <?php if (!empty($pedido['epresis_tiempo_entrega'])): ?>
                                                    <p class="mb-0 mt-2">Fecha estimada de entrega: <strong><?php echo $pedido['epresis_tiempo_entrega']; ?></strong></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- FIN .tab-pane.show -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->

                </div>
                <!-- fin tab-pane -->
            </div>
            <!-- fin tab-content -->
        </div>
    </div>
</div>
<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
<script src="../node_modules\owl.carousel\dist\owl.carousel.js"></script>

<script>
    // Look for .hamburger
    var hamburger = document.querySelector(".hamburger");
    // On click
    hamburger.addEventListener("click", function() {
        // Toggle class "is-active"
        hamburger.classList.toggle("is-active");
        // Do something else, like open/close menu
    });
</script>

<!-- MOVER ESTO AL FOOTER -->
<script>
    var owl = $('.owl-carousel');
    owl.owlCarousel();
    // Go to the next item
    $('.customNextBtn').click(function() {
        owl.trigger('next.owl.carousel');
    })
    // Go to the previous item
    $('.customPrevBtn').click(function() {
        // With optional speed parameter
        // Parameters has to be in square bracket '[]'
        owl.trigger('prev.owl.carousel', [300]);
    })
</script>

<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel();
        $('.cerrarSesion').click(function(e) {
            e.preventDefault();
            $.post('../inc/salir.php');
            window.location.reload();
        });
    });
</script>

</body>

</html>