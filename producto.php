<?php include('header.php'); ?>
<?php
include_once __DIR__ . '/conexion/conectar.inc.php';
global $conectar;
if (isset($_GET['id'])) {
    //$get=explode('_', $_GET['id']);
    $id = $_GET['id'];
    $query = "SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id WHERE p.id='$id'";
} else {
}
//echo $query;
$producto = $conectar->query($query);
$prod = $producto->fetch_assoc();

$id = $prod['id'];
$imagenes = $conectar->query("SELECT * FROM imagenes WHERE id_producto='$id'");

// Agrupar imágenes por color
$imagenesPorColor = array();
$primerColor = '';
$imagenes->data_seek(0);
while ($imgRow = $imagenes->fetch_assoc()) {
    $color = !empty($imgRow['color']) && $imgRow['color'] != '#000000' ? $imgRow['color'] : 'sin_color';
    if (!isset($imagenesPorColor[$color])) {
        $imagenesPorColor[$color] = array();
        if (empty($primerColor) && $color != 'sin_color') {
            $primerColor = $color;
        }
    }
    $imagenesPorColor[$color][] = $imgRow;
}

// Obtener colores únicos de las imágenes
$colores = array();
foreach ($imagenesPorColor as $color => $imgs) {
    if ($color != 'sin_color') {
        $colores[] = $color;
    }
}

// Si no hay colores, usar todas las imágenes
//if (empty($primerColor)) {
    $primerColor = 'sin_color';
//}

// Obtener el coeficiente de impuestos
$taxQuery = $conectar->query("SELECT coeficiente FROM tax WHERE id = 1");
$taxData = $taxQuery->fetch_assoc();
$taxCoeficiente = $taxData ? $taxData['coeficiente'] : 1.21;

// Calcular precios
$precioLista = $prod['precio'];
$descuento_porcentaje = isset($prod['descuento_final']) ? $prod['descuento_final'] : 0;
$precioFinal = $precioLista - ($precioLista * $descuento_porcentaje / 100);
$precioConImpuestos = $precioFinal / $taxCoeficiente;

// Procesar video si existe
$videoHTML = '';
$tieneVideo = false;
if (!empty($prod['video'])) {
    $tieneVideo = true;
    $videoUrl = $prod['video'];

    // Detectar si es un video de YouTube
    if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
        // Extraer ID de YouTube
        $videoId = '';
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $videoUrl, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $videoUrl, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $videoUrl, $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            $videoHTML = '
    <div class="video-wrapper"><div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' . $videoId . '" allowfullscreen></iframe>
            </div></div>';
        }
    } else {
        // Video local
        $videoHTML = '<div class="video-wrapper"><video controls class="w-100" style="max-height: 500px;">
            <source src="' . $videoUrl . '" type="video/mp4">
            Tu navegador no soporta la reproducción de videos.
        </video></div>';
    }
}

// Obtener cuotas disponibles
$cuotasQuery = $conectar->query("SELECT * FROM cuotas WHERE producto_id = '$id' ORDER BY cantidad ASC");
$cuotas = array();
while ($cuotaRow = $cuotasQuery->fetch_assoc()) {
    $cuotas[] = $cuotaRow;
}

?>
<style>
    .color-selector.current {
        border: 3px solid #da0000 !important;
        box-shadow: 0 0 5px rgba(218, 0, 0, 0.5);
    }

    .color-selector {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .color-selector:hover {
        transform: scale(1.1);
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="tienda">
                <button class="btn pl-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8.627" height="15.09" viewBox="0 0 8.627 15.09">
                        <path id="Icon_ionic-ios-arrow-back" data-name="Icon ionic-ios-arrow-back" d="M13.851,13.736l5.709-5.705a1.078,1.078,0,0,0-1.527-1.523l-6.469,6.464a1.076,1.076,0,0,0-.031,1.487l6.5,6.509a1.078,1.078,0,0,0,1.527-1.523Z" transform="translate(-11.251 -6.194)" fill="#da0000" />
                    </svg>
                </button>
            </a>
            <p class="text-medium"><?php echo $prod['categoria']; ?> / <?php echo $prod['nombre']; ?></p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-5 mt-4">
                <div id="message"></div>
            </div>

        </div>
        <div class="col-md-1">
            <div id="sync2" class="owl-carousel owl-theme d-none d-md-block">
                <?php
                // Mostrar miniaturas del color seleccionado
                if (isset($imagenesPorColor[$primerColor])) {
                    foreach ($imagenesPorColor[$primerColor] as $img) { ?>
                        <div class="mb-2 shadow-sm imagen-miniatura" data-color="<?php echo $primerColor; ?>">
                            <img class="img-fluid w-100" src="<?php echo $img['imagen']; ?>" alt="">
                        </div>
                <?php }
                }

                // Mostrar video en miniatura al final si existe
                if ($tieneVideo) {
                    echo '<div class="mb-2 shadow-sm" data-color="video">
                        <div style="background: #000; padding: 10px; text-align: center; color: #fff; font-size: 10px;">
                            <i class="fa fa-play-circle" style="font-size: 20px;"></i><br>Video
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
        <div class="col-md-7">
            <div class="shadow-sm">
                <div id="sync1" class="owl-carousel owl-theme">
                    <?php
                    // Mostrar imágenes del color seleccionado
                    if (isset($imagenesPorColor[$primerColor])) {
                        foreach ($imagenesPorColor[$primerColor] as $img) { ?>
                            <div class="item imagen-principal" data-color="<?php echo $primerColor; ?>">
                                <img class="img-fluid mt-0" src="<?php echo $img['imagen']; ?>" alt="">
                            </div>
                    <?php }
                    }

                    // Mostrar video al final si existe
                    if ($tieneVideo) {
                        echo '<div class="item" data-color="video">' . $videoHTML . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div>
                <h5 class="titulo-tabs-user mt-5 mt-md-0"><?php echo $prod['nombre']; ?></h5>

                <?php if ($descuento_porcentaje > 0): ?>
                    <h5 class="titulo-tabs-user m-0" style="text-decoration: line-through; color: #999; font-size: 1.5rem;">$ <?php echo number_format($precioLista, 2); ?></h5>
                    <h5 class="titulo-tabs-user m-0">$ <?php echo number_format($precioFinal, 2); ?> <span class="tag-percent"><?php echo $descuento_porcentaje; ?>% OFF</span></h5>
                <?php else: ?>
                    <h5 class="titulo-tabs-user">$ <?php echo number_format($precioLista, 2); ?>
                    </h5>
                <?php endif; ?>

                <p class="text-muted small">Precio sin impuestos nacionales: <strong>$<?php echo number_format($precioConImpuestos, 2); ?></strong></p>

                <?php if (count($cuotas) > 0): ?>
                    <div class="my-2">
                        <span>
                            <?php foreach ($cuotas as $index => $cuota):
                                $montoCuota = ($precioFinal * (1 + $cuota['interes'] / 100)) / $cuota['cantidad'];
                                if ($cuota['interes'] <= 0):
                                    echo $cuota['cantidad'] . ' cuotas sin interés de $' . $montoCuota;
                                    break;
                                endif;
                            endforeach; ?>
                        </span>
                        <button type="button" class="btn btn-link p-0 text-danger" data-toggle="modal" data-target="#cuotasModal">
                            Ver cuotas disponibles
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (count($colores) > 0): ?>
                    <p class="text-bold">Colores Disponibles</p>
                    <div class="my-2">
                        <?php foreach ($colores as $key => $color): ?>
                            <button class="btn btn-link p-0 btn-selector" data-color="<?php echo $color; ?>" title="Color" type="button">
                                <span class="dot mr-2 color-selector <?php if ($color == $primerColor) {
                                                                            echo 'current';
                                                                        } ?>" style="background-color: <?php echo $color; ?>; width: 30px; height: 30px; display: inline-block; border-radius: 50%; border: 2px solid #ddd;"></span>
                            </button>
                        <?php endforeach; ?>
                        <input type="hidden" id="color" name="color" value="<?php echo $primerColor; ?>">
                        <!-- JSON con todas las imágenes por color -->
                        <input type="hidden" id="imagenesPorColor" value='<?php echo json_encode($imagenesPorColor, JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
                        <input type="hidden" id="tieneVideo" value="<?php echo $tieneVideo ? '1' : '0'; ?>">
                        <input type="hidden" id="videoHTML" value='<?php echo htmlspecialchars($videoHTML, ENT_QUOTES); ?>'>
                    </div>
                <?php endif; ?>

                <p class="text-bold">Dimensiones</p>
                <div class="d-flex">
                    <div class="col border text-center">Alto <br> <?php echo $prod['alto']; ?></div>
                    <div class="col border text-center">Ancho <br> <?php echo $prod['ancho']; ?></div>
                    <div class="col border text-center">Profundidad <br> <?php echo $prod['profundidad']; ?></div>
                </div>
                <div class="form-carga-producto d-flex align-items-center mt-4">
                    <label class="m-0 text-bold" for="">Cant.</label>
                    <input class="form-control w-25 ml-4" id="cantidad" name="cantidad" type="number" step="1" value="1" min="1" max="<?php echo $prod['stock']; ?>">
                </div>

                <div class="mt-5">
                    <button type="button" id="success" class="btn bg-danger text-white btn-bg-red addtoCart" data-precio="<?php echo $precioFinal; ?>" data-categoria='<?php echo $prod['id_categoria']; ?>' data-id="<?php echo $prod['id']; ?>"><svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528" viewBox="0 0 16.528 16.528">
                            <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart" d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z" transform="translate(-1.5 -3)" fill="#fff" />
                        </svg>
                        Agregar Pedido
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="row descripcion-producto separador-red">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <p class="p-0 text-bold">Descripción</p>
            <p>
                <?php echo $prod['descripcion']; ?>
            </p>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>

<?php include 'recomendados.inc.php'; ?>

<!-- FOOTER -->
<footer class="bg-black">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <div class="d-block d-md-flex text-center text-md-left flex-row align-items-center">
                    <a href="#"><img src="assets\img\logo-pronto-white.svg" alt=""></a>
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

<!-- Modal de Cuotas -->
<div class="modal fade" id="cuotasModal" tabindex="-1" role="dialog" aria-labelledby="cuotasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cuotasModalLabel">Cuotas Disponibles</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (count($cuotas) > 0): ?>
                    <div class="accordion" id="accordionCuotas">
                        <?php foreach ($cuotas as $index => $cuota):
                            $montoCuota = ($precioFinal * (1 + $cuota['interes'] / 100)) / $cuota['cantidad'];
                        ?>
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $index; ?>">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link text-danger btn-block text-left" type="button"
                                            style="padding: 0px;"
                                            data-toggle="collapse" data-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo $index == 0 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                                            <?php echo $cuota['cantidad']; ?> cuotas
                                            <i class="fa fa-chevron-down float-right mt-1"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse<?php echo $index; ?>" class="collapse <?php echo $index == 0 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $index; ?>" data-parent="#accordionCuotas">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="text-bold mb-1">Cuotas</p>
                                                <p><?php echo $cuota['cantidad']; ?> cuotas</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-bold mb-1">Precio por Cuota</p>
                                                <p>$<?php echo number_format($montoCuota, 2); ?></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-bold mb-1">Interés</p>
                                                <p><?php echo $cuota['interes']; ?>%</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <p class="text-bold mb-1">Total a Pagar</p>
                                                <p class="h5 text-danger">$<?php echo number_format($montoCuota * $cuota['cantidad'], 2); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">No hay cuotas disponibles para este producto.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
<script src="node_modules\owl.carousel\dist\owl.carousel.min.js"></script>

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

<script src="assets/js/starter.js?v=4"></script>
<script>
    $(function() {
        // Cargar datos de imágenes por color
        var imagenesPorColorJson = $('#imagenesPorColor').val();
        var imagenesPorColor = {};

        try {
            imagenesPorColor = JSON.parse(imagenesPorColorJson);
        } catch (e) {
            console.error('Error parsing JSON:', e);
        }

        var tieneVideo = $('#tieneVideo').val() == '1';
        var videoHTML = $('#videoHTML').val();

        $('.btn-selector').click(function(e) {
            e.preventDefault();
            var color = $(this).data('color');
            $('#color').val(color);
            $('.btn-selector span').removeClass('current');
            $(this).children('span').addClass('current');

            // Actualizar las imágenes del slider
            actualizarSlider(color);
        });

        function actualizarSlider(color) {
            // Destruir los sliders actuales
            var sync1 = $('#sync1');
            var sync2 = $('#sync2');

            sync1.trigger('destroy.owl.carousel');
            sync2.trigger('destroy.owl.carousel');

            // Limpiar el contenido
            sync1.html('');
            sync2.html('');

            // Agregar imágenes del color seleccionado PRIMERO
            if (imagenesPorColor[color] && imagenesPorColor[color].length > 0) {
                imagenesPorColor[color].forEach(function(img) {
                    sync1.append('<div class="item imagen-principal" data-color="' + color + '"><img class="img-fluid mt-0" src="' + img.imagen + '" alt=""></div>');
                    sync2.append('<div class="mb-2 shadow-sm imagen-miniatura" data-color="' + color + '"><img class="img-fluid w-100" src="' + img.imagen + '" alt=""></div>');
                });
            }

            // Agregar video AL FINAL si existe
            if (tieneVideo) {
                sync1.append('<div class="item" data-color="video">' + videoHTML + '</div>');
                sync2.append('<div class="mb-2 shadow-sm" data-color="video"><div style="background: #000; padding: 10px; text-align: center; color: #fff; font-size: 10px;"><i class="fa fa-play-circle" style="font-size: 20px;"></i><br>Video</div></div>');
            }

            // Reinicializar los sliders
            inicializarSliders();
        }

        function inicializarSliders() {
            var sync1 = $("#sync1");
            var sync2 = $("#sync2");

            sync1.owlCarousel({
                items: 1,
                slideSpeed: 2000,
                nav: true,
                autoplay: false,
                dots: false,
                loop: false,
                responsiveRefreshRate: 200,
                navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="pointer-events: none; fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606-8.606l-8.606-8.607"/></svg>'],
            }).on('changed.owl.carousel', syncPosition);

            sync2.on('initialized.owl.carousel', function() {
                sync2.find(".owl-item").eq(0).addClass("current");
            }).owlCarousel({
                items: 4,
                dots: false,
                nav: false,
                margin: 10,
                smartSpeed: 200,
                slideSpeed: 500,
                slideBy: 1,
                responsiveRefreshRate: 100
            }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                var index = el.item.index;
                $('#sync2 .owl-item').removeClass('current');
                $('#sync2 .owl-item').eq(index).addClass('current');
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    sync1.data('owl.carousel').to(number, 100, true);
                }
            }

            $('#sync2').on('click', '.owl-item', function(e) {
                e.preventDefault();
                var index = $(this).index();
                $('#sync1').trigger('to.owl.carousel', [index, 300, true]);
            });

            var owl2 = sync2.data('owl.carousel');

            if (owl2) {
                if (current > end) {
                    owl2.to(current, 100, true);
                }
                if (current < start) {
                    owl2.to(current - onscreen, 100, true);
                }
            }

        }

        var syncedSecondary = true;

        // Inicializar sliders al cargar la página
        inicializarSliders();
    });
</script>
</body>

</html>