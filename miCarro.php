<?php include('header.php'); ?>
<?php
include_once 'conexion/conectar.inc.php';
global $conectar;
if (isset($_SESSION['pronto']['cart'])) {
    $carro = $_SESSION['pronto']['cart'];
    $cant = count($carro);
    if ($cant <= 0) {
        echo '<script>window.location.replace("' . URL_SITIO . '");</script>';
    }
} else {
    echo '<script>window.location.replace("' . URL_SITIO . '");</script>';
}

// Obtener valor para envío gratis con Epresis
$epresis_data = $conectar->query("SELECT valor_gratis FROM metodos_envio WHERE id=2");
$epresis_row = $epresis_data->fetch_assoc();
$epresis_valor_gratis = $epresis_row['valor_gratis'] ?? 0;

?>
<div class="">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-bold font-monument titulo-portada">Mi pedido</h2>
            </div>
        </div>
    </div>
</div>
<div class="container mi-carro">
    <div class="row">
        <div class="col-md-12">
            <!--Section: Block Content-->
            <section>
                <!--Grid row-->
                <div class="row">
                    <!--Grid column-->
                    <div class="col-lg-8">
                        <!-- Card -->
                        <div class="mb-3">
                            <div class="wish-list">
                                <?php if ($cant > 0) { ?>
                                    <h5 class="mb-4">Pedido (<span><?php echo $cant; ?></span> artículo/s)</h5>
                                    <?php
                                    $total = 0;
                                    foreach ($carro as $id => $datos) {
                                        // Verificar si es producto de revelado
                                        $esRevelado = isset($datos['tipo']) && $datos['tipo'] === 'revelado';
                                        if ($esRevelado) {
                                            // Datos del revelado desde el carrito
                                            // Obtener la primera imagen del revelado para mostrar
                                            $imagen_revelado = 'assets/img/revelado-icon.jpg'; // Default
                                            if (isset($datos['datos_revelado']['imagenes']) && !empty($datos['datos_revelado']['imagenes'])) {
                                                $primera_imagen = reset($datos['datos_revelado']['imagenes']);
                                                if (isset($primera_imagen['archivo']) && file_exists($primera_imagen['archivo'])) {
                                                    $imagen_revelado = $primera_imagen['archivo'];
                                                }
                                            }

                                            $row = [
                                                'nombre' => 'Revelado de Fotos',
                                                'descripcion' => $datos['descripcion'] ?? 'Impresión de fotografías',
                                                'precio' => $datos['precio'],
                                                'descuento_final' => 0,
                                                'imagen' => $imagen_revelado
                                            ];
                                            $cant = $datos['cantidad'];
                                            $precioUnitario = $datos['precio'] / $cant;
                                            $precio = $datos['precio'];
                                        } else {
                                            // Producto normal
                                            $res = $conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
                                            $row = $res->fetch_assoc();
                                            $cant = $datos['cantidad'];
                                            $precioUnitario = $row['precio'];
                                            $descuento = isset($row['descuento_final']) && $row['descuento_final'] > 0 ? $row['descuento_final'] : 0;
                                            if ($descuento > 0) {
                                                $precioUnitario = $row['precio'] - ($row['precio'] * $descuento / 100);
                                            }
                                            $precio = ($cant * $precioUnitario);
                                        }

                                        $total = $total + $precio;
                                    ?>
                                        <div class="row mb-4">
                                            <div class="col-md-5 col-lg-3 col-xl-3">
                                                <div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0 <?php echo $esRevelado ? 'revelado-preview' : ''; ?>" style="position: relative;">
                                                    <img class="img-fluid w-100" src="<?php echo $row['imagen'] ?>" alt="<?php echo $esRevelado ? 'Primera foto del revelado' : 'Producto'; ?>" style="object-fit: cover; height: 180px;">
                                                    <?php if ($esRevelado): ?>
                                                        <div class="badge-fotos-revelado" style="position: absolute; top: 10px; right: 10px; background: rgba(220, 53, 69, 0.9); color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: bold;">
                                                            <i class="fa fa-images"></i> <?php echo $cant; ?> fotos
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-7 col-lg-9 col-xl-9 d-flex flex-column justify-content-around">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h5><?php echo $row['nombre']; ?></h5>
                                                        <p class="mb-2 text-muted text-uppercase small"><?php echo $row['descripcion'] . '<br>'; ?>
                                                            <?php if (!$esRevelado) { ?>
                                                                <span class="d-flex align-items-center">
                                                                    Color : <span class="dot" style="background-color:  <?php echo $datos['color'] ?>; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span>
                                                                </span>
                                                            <?php } ?>
                                                        </p>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="mr-2">Cantidad:</span>
                                                            <?php if ($esRevelado) { ?>
                                                                <span class="font-weight-bold"><?php echo $datos['cantidad']; ?> foto<?php echo $datos['cantidad'] > 1 ? 's' : ''; ?></span>
                                                            <?php } else { ?>
                                                                <div class="input-group" style="width: 130px;">
                                                                    <div class="input-group-prepend">
                                                                        <button class="btn btn-outline-secondary btn-sm btn-decrease" 
                                                                        style="
                                                                        height: calc(1.5em + .5rem + 2px);
    align-items: center;
    display: flex;"
    type="button" data-id="<?php echo $id; ?>">-</button>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm text-center cantidad-input"
                                                                        value="<?php echo $datos['cantidad']; ?>"
                                                                        data-id="<?php echo $id; ?>"
                                                                        readonly
                                                                        style="max-width: 50px;">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-secondary btn-sm btn-increase"
                                                                            style="
                                                                        height: calc(1.5em + .5rem + 2px);
    align-items: center;
    display: flex;"
                                                                            type="button" data-id="<?php echo $id; ?>">+</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex">
                                                        <?php if ($esRevelado) { ?>
                                                            <div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary btn-ver-fotos" data-producto-id="<?php echo $id; ?>">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images mb-1" viewBox="0 0 16 16">
                                                                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                                                        <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z" />
                                                                    </svg>
                                                                    Ver mis fotos
                                                                </button>
                                                            </div>
                                                        <?php } ?>
                                                        <div>
                                                            <button data-id="<?php echo $id; ?>" type="button" class="p-0 btn-link btn card-link-secondary small text-uppercase mr-3 text-danger btn-remove">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill mb-1" viewBox="0 0 16 16">
                                                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                                </svg>
                                                                Eliminar artículo </button>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0"><span><strong id="summary">$<?php echo $precio; ?></strong></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <h5 class="mb-4">Pedido vacio</h5>
                                <?php } ?>

                                <hr class="mb-4">

                                <?php if ($cant > 0 && $epresis_valor_gratis > 0) {
                                    // Calcular progreso para envío gratis
                                    $porcentaje = min(($total / $epresis_valor_gratis) * 100, 100);
                                    $faltante = max($epresis_valor_gratis - $total, 0);
                                ?>
                                    <!-- Barra de progreso envío gratis -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <?php if ($total >= $epresis_valor_gratis) { ?>
                                                <p class="mb-0 text-success font-weight-bold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill mb-1" viewBox="0 0 16 16">
                                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                    ¡Felicitaciones! Tenés envío GRATIS
                                                </p>
                                            <?php } else { ?>
                                                <p class="mb-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-truck mb-1" viewBox="0 0 16 16">
                                                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                                    </svg>
                                                    Te faltan <strong>$<?php echo number_format($faltante, 0); ?></strong> para envío GRATIS
                                                </p>
                                                <span class="text-muted small">$<?php echo number_format($total, 0); ?> / $<?php echo number_format($epresis_valor_gratis, 0); ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="progress" style="height: 25px; border-radius: 20px;">
                                            <div class="progress-bar <?php echo $total >= $epresis_valor_gratis ? 'bg-success' : 'bg-success'; ?>"
                                                role="progressbar"
                                                style="width: <?php echo $porcentaje; ?>%;"
                                                aria-valuenow="<?php echo $porcentaje; ?>"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                <?php echo number_format($porcentaje, 0); ?>%
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botón Seguir Comprando -->
                                    <div class="text-center mb-4">
                                        <a href="tienda" class="btn btn-outline-danger btn-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-plus mb-1 mr-1" viewBox="0 0 16 16">
                                                <path d="M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9V5.5z" />
                                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                            </svg>
                                            Seguir Comprando
                                        </a>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-lg-4 pl-md-5 mb-5">
                        <div class="shadow-sm mb-5 mt-5 mt-md-0 container">
                            <div class="row  p-2">
                                <div class="col-md-12 ">
                                    <h5 class="subtitulo-tabs-user py-3">Resumen de Compra</h5>
                                    <hr class="divisor-resumen-compra">
                                </div>

                                <div class="col-md-12 totales">
                                    <p class="m-0 mb-2">Sub-Total: $<?php echo $total; ?></p>
                                    <p class="m-0 mb-2">Envio: $0</p>
                                    <p class="m-0 mb-2">Cupón de descuento: $0</p>
                                    <p class="m-0 text-bold mb-4">TOTAL: $<?php echo $total; ?></p>
                                    <?php $_SESSION['prontoFront']['monto'] = $total; ?>
                                </div>
                            </div>
                            <div class="row p-0">
                                <div class="col-md-12 p-0 m-0 ">
                                    <input type="hidden" id="login" value="<?php if (isset($_SESSION['prontoFront']['idcliente'])) {
                                                                                echo '1';
                                                                            } else {
                                                                                echo '0';
                                                                            } ?>">
                                    <button type="button" class="btn btn-success btn-lg btn-block rounded-bottom btn-pagar">Pagar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Grid column-->

                </div>
                <!-- Grid row -->

            </section>
            <!--Section: Block Content-->
        </div>
    </div>
</div>

<!-- Modal Ver Fotos Revelado -->
<div class="modal fade" id="modalVerFotosRevelado" tabindex="-1" role="dialog" aria-labelledby="modalVerFotosReveladoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalVerFotosReveladoLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-images mb-1 mr-2" viewBox="0 0 16 16">
                        <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                        <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z" />
                    </svg>
                    Mis Fotos a Revelar
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalFotosContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-danger" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando fotos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="bg-black">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <div class="d-block d-md-flex text-center text-md-left flex-row align-items-center">
                    <a href="#"><img src="assets/img/logo-pronto-white.svg" alt=""></a>
                    <h4 class="text-white ml-0 ml-md-3 mt-3 mt-md-0">By Chimpancé</h4>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end align-items-center">
                <div class="mt-4 mt-md-0">
                    <a href="https://es-la.facebook.com/pg/Prontophot/about/?ref=page_internal"><svg
                            xmlns="http://www.w3.org/2000/svg" width="17.055" height="31.843"
                            viewBox="0 0 17.055 31.843">
                            <g id="Grupo_325" data-name="Grupo 325" transform="translate(402 -2245)">
                                <path id="Icon_awesome-facebook-f" data-name="Icon awesome-facebook-f"
                                    d="M17.547,17.912l.884-5.763H12.9V8.409c0-1.577.772-3.113,3.249-3.113h2.514V.389A30.656,30.656,0,0,0,14.2,0c-4.554,0-7.53,2.76-7.53,7.757v4.392H1.609v5.763H6.671V31.843H12.9V17.912Z"
                                    transform="translate(-403.609 2245)" fill="#fff" />
                            </g>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/prontophotlp/?igshid=16xmcwskod733" class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31.38" height="31.373"
                            viewBox="0 0 31.38 31.373">
                            <g id="Grupo_326" data-name="Grupo 326" transform="translate(402 -2298)">
                                <path id="Icon_awesome-instagram" data-name="Icon awesome-instagram"
                                    d="M15.688,9.881a8.044,8.044,0,1,0,8.044,8.044A8.031,8.031,0,0,0,15.688,9.881Zm0,13.273a5.229,5.229,0,1,1,5.229-5.229,5.239,5.239,0,0,1-5.229,5.229Zm10.249-13.6a1.876,1.876,0,1,1-1.876-1.876A1.872,1.872,0,0,1,25.937,9.552Zm5.327,1.9a9.285,9.285,0,0,0-2.534-6.574,9.346,9.346,0,0,0-6.574-2.534C19.567,2.2,11.8,2.2,9.213,2.348A9.332,9.332,0,0,0,2.639,4.875,9.315,9.315,0,0,0,.1,11.449C-.042,14.039-.042,21.8.1,24.393a9.285,9.285,0,0,0,2.534,6.574A9.358,9.358,0,0,0,9.213,33.5c2.59.147,10.354.147,12.944,0a9.285,9.285,0,0,0,6.574-2.534,9.346,9.346,0,0,0,2.534-6.574c.147-2.59.147-10.347,0-12.937ZM27.919,27.172a5.294,5.294,0,0,1-2.982,2.982c-2.065.819-6.966.63-9.248.63s-7.19.182-9.248-.63a5.294,5.294,0,0,1-2.982-2.982c-.819-2.065-.63-6.966-.63-9.248s-.182-7.19.63-9.248A5.294,5.294,0,0,1,6.441,5.694c2.065-.819,6.966-.63,9.248-.63s7.19-.182,9.248.63a5.294,5.294,0,0,1,2.982,2.982c.819,2.065.63,6.966.63,9.248S28.738,25.114,27.919,27.172Z"
                                    transform="translate(-401.995 2295.762)" fill="#fff" />
                            </g>
                        </svg>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <a class="d-md-none d-lg-none d-block text-center wp" href="https://api.whatsapp.com/send?phone=+5492216784142  &amp;text=Buenos%20días,%20quiero%20mas%20info%20">
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
    <a class="d-xs-none d-sm-none d-md-block text-center wp" target="_blank" href="https://wa.me/542216976559? &amp;text=Buenos%20días,%20quiero%20mas%20info">
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
</footer>

<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>

<script type="text/javascript">
    // Look for .hamburger
    var hamburger = document.querySelector(".hamburger");
    // On click
    hamburger.addEventListener("click", function() {
        // Toggle class "is-active"
        hamburger.classList.toggle("is-active");
        // Do something else, like open/close menu
    });
</script>

<script type="text/javascript">
    $(".no-transfer").click(function() {
        $("#collapsediv2").collapse('hide');
    })

    // Cuando hacemos click verifica si tiene la clase "show" y si es asi, no colapsa nada. Caso contrario colapsa el div.
    $('.contenedor-input > input[data-toggle="collapse"]').click(function(e) {
        target = $("#collapsediv2");
        if ($(target).hasClass('show')) {
            // e.preventDefault(); Esto en el caso que el link lleve a alguna seccion.
            e.stopPropagation()
        }
    })
</script>

<script src="assets/js/starter.js"></script>
<script>
    $(function() {
        $('.btn-remove').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.post('inc/rem_cart.php', {
                id: id
            }, function(data) {
                location.reload();
            });
        });

        $('.btn-pagar').click(function(e) {
            e.preventDefault();
            var login = $('#login').val();
            if (login == 1) {
                window.location.href = "checkout";
            } else {
                $('#iniciar-sesion').modal();
            }
            //
        });

        // Botón incrementar cantidad
        $('.btn-increase').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: 'inc/update_cart_quantity.php',
                data: {
                    id: id,
                    action: 'increase'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.msg);
                        btn.prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Error al actualizar la cantidad');
                    btn.prop('disabled', false);
                }
            });
        });

        // Botón decrementar cantidad
        $('.btn-decrease').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: 'inc/update_cart_quantity.php',
                data: {
                    id: id,
                    action: 'decrease'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.msg);
                        btn.prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Error al actualizar la cantidad');
                    btn.prop('disabled', false);
                }
            });
        });

        // Botón Ver Fotos Revelado
        $('.btn-ver-fotos').click(function(e) {
            e.preventDefault();
            var productoId = $(this).data('producto-id');

            // Mostrar modal
            $('#modalVerFotosRevelado').modal('show');

            // Cargar fotos desde la sesión PHP
            $.ajax({
                type: 'POST',
                url: 'inc/obtener_fotos_revelado.php',
                data: {
                    producto_id: productoId
                },
                dataType: 'json',
                success: function(data) {
                    console.log('Respuesta del servidor:', data);

                    if (data.success && data.fotos && data.fotos.length > 0) {
                        var html = '<div class="container-fluid">';
                        html += '<div class="row mb-3">';
                        html += '<div class="col-12">';
                        html += '<div class="alert alert-info">';
                        html += '<strong><i class="fa fa-info-circle"></i> Total: ' + data.total_fotos + ' fotografía' + (data.total_fotos > 1 ? 's' : '') + '</strong>';
                        html += '<p class="mb-0 mt-2">Resumen por tipo:</p>';
                        html += '<ul class="mb-0">';
                        for (var tipo in data.resumen) {
                            html += '<li>' + data.resumen[tipo] + ' foto' + (data.resumen[tipo] > 1 ? 's' : '') + ' ' + tipo + '</li>';
                        }
                        html += '</ul>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="row">';

                        data.fotos.forEach(function(foto, index) {
                            html += '<div class="col-md-3 col-sm-6 mb-4">';
                            html += '<div class="card shadow-sm">';
                            html += '<img src="' + foto.archivo + '" class="card-img-top" alt="Foto ' + (index + 1) + '" style="height: 200px; object-fit: cover;">';
                            html += '<div class="card-body">';
                            html += '<h6 class="card-title text-bold">Foto ' + (index + 1) + '</h6>';
                            html += '<p class="card-text small mb-1"><strong>Tamaño:</strong> ' + foto.tamano + '</p>';
                            html += '<p class="card-text small mb-1"><strong>Acabado:</strong> ' + foto.acabado + '</p>';
                            html += '<p class="card-text small mb-0"><strong>Cantidad:</strong> ' + foto.cantidad + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                        });

                        html += '</div>';
                        html += '</div>';

                        $('#modalFotosContent').html(html);
                    } else {
                        var errorHtml = '<div class="alert alert-warning">';
                        errorHtml += '<h5><i class="fa fa-exclamation-triangle"></i> No se encontraron fotos</h5>';
                        errorHtml += '<p><strong>Error:</strong> ' + (data.error || 'Desconocido') + '</p>';
                        if (data.debug) {
                            errorHtml += '<p><strong>Debug:</strong> ' + data.debug + '</p>';
                        }
                        if (data.ids_disponibles) {
                            errorHtml += '<p><strong>IDs disponibles:</strong> ' + data.ids_disponibles.join(', ') + '</p>';
                        }
                        errorHtml += '<hr><p class="mb-0"><small>Consulta el archivo de logs del servidor para más detalles.</small></p>';
                        errorHtml += '</div>';
                        $('#modalFotosContent').html(errorHtml);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    console.error('Response Text:', xhr.responseText);
                    var errorHtml = '<div class="alert alert-danger">';
                    errorHtml += '<h5><i class="fa fa-times-circle"></i> Error de conexión</h5>';
                    errorHtml += '<p><strong>Error:</strong> ' + error + '</p>';
                    errorHtml += '<p><strong>Status:</strong> ' + status + '</p>';
                    if (xhr.responseText) {
                        errorHtml += '<hr><p><strong>Respuesta del servidor:</strong></p>';
                        errorHtml += '<pre style="max-height: 200px; overflow-y: auto;">' + xhr.responseText + '</pre>';
                    }
                    errorHtml += '</div>';
                    $('#modalFotosContent').html(errorHtml);
                }
            });
        });
    });
</script>
</body>

</html>