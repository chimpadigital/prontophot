<?php include('header.php'); ?>
<?php
include ('../inc/funciones.inc.php');
include ('../conexion/conectar.inc.php');
global $conectar;
$id = $_GET['id'];
$pedidos = $conectar->query("SELECT p.*,(SELECT id_producto FROM pedidos_detalle WHERE id_pedido=p.id LIMIT 1 ) as producto,(SELECT imagen FROM imagenes WHERE id_pedido=p.id LIMIT 1 ) as imagen,DATE_FORMAT(fecha, '%d-%m-%Y') as fecha,CONCAT(c.nombre,' ',c.apellido) as clientenombre,c.dni clientedni,CONCAT(c.direccion) as clientedireccion,c.cp clientecp, c.ciudad clienteciudad, c.altura clientealtura,c.provincia clienteprovincia,c.telefono clientetelefono FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE p.id='$id' ");
$row = $pedidos->fetch_assoc();
$imagenes = $conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido='$id'");
$productos = $conectar->query("SELECT pd.cantidad, pd.color, p.*,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM pedidos_detalle pd LEFT JOIN productos p ON pd.id_producto=p.id WHERE pd.id_pedido='$id'");

// Obtener datos de facturación
$facturacion = $conectar->query("SELECT * FROM facturacion WHERE pedido_id='$id'");
$rowf = $facturacion->fetch_assoc();

function zipFilesAndDownload($file_names, $archive_file_name, $file_path)
{
    $zip = new \ZipArchive();
    if ($zip->open($archive_file_name, ZipArchive::CREATE) !== TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }
    $zip->addEmptyDir("imagenes");
    foreach ($file_names as $files) {
        $file = $file_path . $files;
        $zip->addFile($file_path . $files, "imagenes" . DIRECTORY_SEPARATOR . $files);
    }
    $zip->close();
}

$image = array();
while ($rowi = $imagenes->fetch_assoc()) {
    $i = explode('/', $rowi['imagen']);
    $im = $i[2];
    $image[] = $im;
}

$archivo = 'pedido_' . $row['id'] . '.zip';
$path = "../img/impresiones/";

try {
    //code...
    zipFilesAndDownload($image, $archivo, $path);
} catch (\Throwable $th) {
    //throw $th;
}

echo json_encode($row);
?>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="\"><img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link active" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos
                </a>
                <a class="nav-link" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento
                </a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">

            <div class="tab-content" id="v-pills-tabContent">

                <div class="tab-pane fade show active" id="pedidos" role="tabpanel"
                    aria-labelledby="v-pills-profile-tab">

                    <div class="row">
                        <div class="col-md-12 p-0">
                            <h5 class="titulo-detalle-pedido">Detalles del pedido #<?php echo $row['id']; ?></h5>
                        </div>
                    </div>



                    <?php if ($imagenes->num_rows > 0): ?>
                        <div class="row align-items-lg-center mt-0 mt-md-3">
                            <div class="col-md-9 p-0">
                                <h4 class="text-bold">Fotos a revelar (<?php echo $imagenes->num_rows; ?>)</h4>
                            </div>
                            <div class="col-md-3 text-left text-lg-right p-0">
                                <a download href="<?php echo $archivo; ?>" class="btn btn-bg-transparent text-danger" id="descargaTodo">Descargar Todo</a>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <?php
                            $i = 1;
                            $imagenes->data_seek(0);
                            while ($rowi = $imagenes->fetch_assoc()) {
                                // Usar imagen si thumb no existe
                                $imagen_preview = !empty($rowi['thumb']) && file_exists('../' . $rowi['thumb'])
                                    ? '../' . $rowi['thumb']
                                    : '../' . $rowi['imagen'];
                            ?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img class="img-fluid w-100" src="<?php echo $imagen_preview; ?>" alt="" style="max-height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-7 d-block d-lg-flex flex-column mt-4 mt-md-0">
                                            <div class="d-block d-lg-flex">
                                                <p class="detalles-pedido">Foto <?php echo $i; ?></p>
                                            </div>
                                            <p class="descripcion-pedido">Tamaño <?php echo $rowi['formato']; ?> <br><?php echo $rowi['acabado']; ?> <br>Cant. :<?php echo $rowi['cantidad']; ?></p>
                                        </div>
                                        <div class="col-sm-12 col-md-3 d-block d-lg-flex flex-column justify-content-around">
                                            <div class="d-block d-lg-flex align-items-center justify-content-end">
                                                <a class="nav-link descargaImagen" href="<?php echo '../' . $rowi['imagen']; ?>" download><button type="button"
                                                        class="btn bg-danger text-white btn-bg-red">Descargar Foto</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            } ?>
                        </div>
                    <?php else: ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Este pedido no contiene fotos para revelar.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row align-items-lg-center mt-5">
                        <div class="col-md-12 p-0">
                            <h4 class="text-bold">Detalles de Compra</h4>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <?php
                        // Primero obtener detalles del pedido con tipo
                        $productos_detalle = $conectar->query("SELECT pd.*, p.nombre, p.descripcion, (SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as producto_imagen FROM pedidos_detalle pd LEFT JOIN productos p ON pd.id_producto=p.id WHERE pd.id_pedido='$id'");

                        while ($rowp = $productos_detalle->fetch_assoc()) {
                            // Verificar si es revelado
                            $esRevelado = isset($rowp['tipo']) && $rowp['tipo'] === 'revelado';

                            if ($esRevelado) {

                                // Obtener imágenes del revelado desde pedidos_imagenes
                                $imagenes_revelado = $conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido='$id'");

                                // Agrupar por tamaño y acabado
                                $resumen = [];
                                $total_imagenes_revelado = 0;
                                while ($img = $imagenes_revelado->fetch_assoc()) {
                                    $key = $img['formato'] . ' ' . $img['acabado'];
                                    if (!isset($resumen[$key])) {
                                        $resumen[$key] = 0;
                                    }
                                    $resumen[$key] += intval($img['cantidad']);
                                    $total_imagenes_revelado += intval($img['cantidad']);
                                }
                        ?>
                                <div class="col-12 p-4 col-categoria my-2 bg-light">
                                    <div class="row">
                                        <div class="col-md-2 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-images text-danger" viewBox="0 0 16 16">
                                                <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                                                <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z" />
                                            </svg>
                                        </div>
                                        <div class="col-md-10 d-block d-lg-flex flex-column">
                                            <div class="d-block d-lg-flex">
                                                <ul class="list-group">
                                                    <li class="list-group-item bg-transparent border-0 mb-0 p-0"><strong class="text-danger">Revelado de Fotografías</strong></li>
                                                    <li class="list-group-item bg-transparent border-0 mb-0 p-0">Total de fotografías: <strong><?php echo $total_imagenes_revelado; ?></strong></li>
                                                    <?php if (!empty($resumen)): ?>
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0 mt-2"><strong>Detalle:</strong></li>
                                                        <?php foreach ($resumen as $tipo => $cantidad): ?>
                                                            <li class="list-group-item bg-transparent border-0 mb-0 p-0 pl-3">• <?php echo $cantidad . ' foto' . ($cantidad > 1 ? 's' : '') . ' ' . $tipo; ?></li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li class="list-group-item bg-transparent border-0 mb-0 p-0 mt-2 text-muted">
                                                            <i class="fa fa-info-circle"></i> No se encontraron detalles de las fotos en pedidos_imagenes
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                            <p class="descripcion-pedido mt-2">
                                                <?php if ($imagenes_revelado->num_rows > 0): ?>
                                                    Las fotos individuales se encuentran en la sección "Fotos a revelar" arriba.
                                                <?php else: ?>
                                                    <span class="text-warning">⚠ Las fotos no se guardaron correctamente en pedidos_imagenes.</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                // Producto normal
                                // Generar HTML del color
                                $color_html = '';
                                if (!empty($rowp['color'])) {
                                    $color_html = '<span class="dot" style="background-color: ' . $rowp['color'] . '; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span>';
                                }

                                // Obtener imagen del producto
                                $imagen_prod = !empty($rowp['producto_imagen']) ? '../' . $rowp['producto_imagen'] : 'https://via.placeholder.com/50';
                            ?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img class="img-fluid w-100" src="<?php echo $imagen_prod; ?>" alt="">
                                        </div>
                                        <div class="col-md-10 d-block d-lg-flex flex-column">
                                            <div class="d-block d-lg-flex">
                                                <ul class="list-group">
                                                    <li class="list-group-item bg-transparent border-0 mb-0 p-0"><strong><?php echo $rowp['nombre']; ?></strong></li>
                                                    <li class="list-group-item bg-transparent border-0 mb-0 p-0">Color seleccionado: <?php echo $color_html; ?></li>
                                                    <li class="list-group-item bg-transparent border-0 mb-0 p-0">Cantidad: <?php echo $rowp['cantidad'] ?></li>
                                                </ul>
                                            </div>
                                            <p class="descripcion-pedido"><?php echo $rowp['descripcion'] ?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>

                    <div class="row align-items-lg-center mt-5">
                        <div class="col-md-9 p-0">
                            <h4 class="text-bold">Datos de Envíos</h4>
                        </div>
                        <div class="col-md-3 text-left text-lg-right p-0 mt-2 mt-md-0">
                            <button type="button" id="imprimirDatos" class="btn bg-success text-white btn-bg-green">Imprimir</button>
                        </div>
                    </div>

                    <div class="row mt-4 datos-envio">
                        <div class="col-md-8 p-4 shadowBox" id="datosEnvio">
                            <?php
                            echo '<script>console.log("' . $row['envio'] . '")</script>';
                            echo '<script>console.log("' . $row['entrega'] . '")</script>';

                            if ($row['envio'] == 'domicilio') {
                                $titulo = 'Enviar a mi domicilio';
                                $nombre = $row['clientenombre'];
                                $dni = $row['clientedni'];
                                $ciudad = $row['clienteciudad'];
                                $altura = $row['clientealtura'];
                                $direccion = $row['clientedireccion'];
                                $provincia = $row['clienteprovincia'];
                                $cp = $row['clientecp'];
                                $telefono = $row['clientetelefono'];
                            } else {
                                $titulo = 'Enviar como regalo';
                                $nombre = $row['nombre'];
                                $dni = $row['dni'];
                                $direccion = $row['direccion'];
                                $provincia = $row['provincia'];
                                $cp = $row['cp'];
                                $telefono = $row['telefono'];
                            }

                            switch ($row['entrega']) {
                                case 'envio_2':
                                    echo $titulo = '<strong>Datos de envio</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Ciudad:  ' . $ciudad = $row['clienteciudad'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' Altura: ' . $altura = $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'suc1':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 1</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' - Altura: ' . $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'suc2':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 2</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' - Altura: ' . $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'suc3':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 3</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' - Altura: ' . $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'urbano':
                                    echo $titulo = '<strong>Datos de envio casco urbano</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Ciudad:  ' . $ciudad = $row['clienteciudad'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' Altura: ' . $altura = $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'envio_1':
                                    echo $titulo = '<strong>Datos de envio casco urbano</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Ciudad:  ' . $ciudad = $row['clienteciudad'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . ' Altura: ' . $altura = $row['clientealtura'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
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
                        <div class="col-md-3 offset-0 offset-md-1 p-4 shadowBox my-3 my-lg-0" id="metodopago">
                            <h5>Método de Pago</h5>
                            <p class="text-bold"><?php echo metodoPago($row['metodo']); ?></p>
                            <h4 class="text-bold">Total: $<?php echo $row['total']; ?></h4>
                        </div>
                    </div>

                    <?php if ($rowf): ?>
                        <div class="row align-items-lg-center mt-5">
                            <div class="col-md-9 p-0">
                                <h4 class="text-bold">Datos de Facturación</h4>
                            </div>
                            <div class="col-md-3 text-left text-lg-right p-0 mt-2 mt-md-0">
                                <button type="button" id="imprimirFacturacion" class="btn bg-success text-white btn-bg-green">Imprimir</button>
                            </div>
                        </div>

                        <div class="row mt-4 datos-facturacion">
                            <div class="col-md-8 p-4 shadowBox" id="datosFacturacion">
                                <h5>Datos del Comprador</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre y Apellido:</strong> <?php echo $rowf['nombre'] . ' ' . $rowf['apellido']; ?></p>
                                        <p><strong>DNI:</strong> <?php echo $rowf['dni']; ?></p>
                                        <p><strong>CUIL:</strong> <?php echo $rowf['cuil']; ?></p>
                                        <p><strong>Email:</strong> <?php echo $rowf['email']; ?></p>
                                        <p><strong>Dirección:</strong> <?php echo $rowf['direccion']; ?> - Altura: <?php echo $rowf['altura']; ?></p>
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
                                    <h5 class="text-danger mb-4">Requiere Factura A</h5>
                                    <p><strong>CUIT:</strong> <?php echo $rowf['cuit']; ?></p>
                                    <p><strong>Razon Social:</strong> <?php echo $rowf['razon_social']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Guía Epresis -->
                    <?php
                    // Verificar si el pedido usa Epresis (metodo_envio_id = 2)
                    if ($row['metodo_envio_id'] == 2) {
                        include __DIR__ . '/../inc/epresis_guia.php';
                        $guia_existente = obtenerGuiaEpresis($id);
                    ?>
                        <div class="row align-items-lg-center mt-5">
                            <div class="col-md-9 p-0">
                                <h4 class="text-bold">Guía de Envío EPSA</h4>
                            </div>
                            <div class="col-md-3 text-left text-lg-right p-0 mt-2 mt-md-0">
                                <?php if (!$guia_existente): ?>
                                    <button type="button" id="generarGuiaEpresis" class="btn bg-warning text-dark btn-bg-yellow">
                                        <i class="fa fa-truck"></i> Generar Guía
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div id="guiaEpresisContainer">
                                    <?php if ($guia_existente): ?>
                                        <div class="alert alert-success">
                                            <h5 class="mb-3"><i class="fa fa-check-circle"></i> Guía Generada</h5>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Código de Guía:</strong>
                                                    <p class="h4 text-primary"><?php echo $guia_existente['codigo_guia']; ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Remito:</strong>
                                                    <p><?php echo $guia_existente['remito']; ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Importe:</strong>
                                                    <p>$<?php echo number_format($guia_existente['importe'], 2); ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Zona:</strong>
                                                    <p><?php echo $guia_existente['sub_zona_destino']; ?></p>
                                                </div>
                                            </div>
                                            <p class="mb-0 text-muted small">Generada el <?php echo date('d/m/Y H:i', strtotime($guia_existente['fecha_creacion'])); ?></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> No se ha generado guía de envío para este pedido. Haz clic en "Generar Guía" para crear una.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Modal Generar Guía Epresis -->
                    <div class="modal fade" id="modalGenerarGuia" tabindex="-1" role="dialog" aria-labelledby="modalGenerarGuiaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title" id="modalGenerarGuiaLabel">
                                        <i class="fa fa-truck"></i> Generar Guía de Envío EPSA
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted mb-4">Revisa y corrige los datos necesarios para generar la guía de envío.</p>

                                    <form id="formGenerarGuia">
                                        <input type="hidden" id="modal_pedido_id" value="<?php echo $id; ?>">

                                        <!-- Datos del Destinatario -->
                                        <h6 class="text-bold mb-3 border-bottom pb-2">Datos del Destinatario</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="modal_empresa">Empresa</label>
                                                <input type="text" class="form-control" id="modal_empresa" placeholder="Nombre de empresa (opcional)">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modal_celular">Celular *</label>
                                                <input type="text" class="form-control" id="modal_celular" value="<?php echo $row['clientetelefono']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-8">
                                                <label for="modal_calle">Calle *</label>
                                                <input type="text" class="form-control" id="modal_calle" value="" required>
                                                <small class="form-text text-muted">Nombre de la calle sin número</small>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_altura">Altura *</label>
                                                <input type="number" class="form-control" id="modal_altura" placeholder="0" required>
                                                <small class="form-text text-muted">Número de calle</small>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <label for="modal_piso">Piso</label>
                                                <input type="text" class="form-control" id="modal_piso" placeholder="Ej: 2">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="modal_dpto">Depto</label>
                                                <input type="text" class="form-control" id="modal_dpto" placeholder="Ej: A">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_hora_desde">Hora Desde</label>
                                                <input type="time" class="form-control" id="modal_hora_desde" placeholder="10:00">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_hora_hasta">Hora Hasta</label>
                                                <input type="time" class="form-control" id="modal_hora_hasta" placeholder="18:00">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="modal_cuit">CUIT/CUIL</label>
                                                <input type="text" class="form-control" id="modal_cuit" value="<?php echo $rowf ? $rowf['cuit'] : ''; ?>" placeholder="XX-XXXXXXXX-X">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modal_contenido">Contenido</label>
                                                <input type="text" class="form-control" id="modal_contenido" placeholder="Descripción del contenido">
                                            </div>
                                        </div>

                                        <!-- Información adicional -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="modal_info_adicional_1">Info Adicional 1</label>
                                                <input type="text" class="form-control" id="modal_info_adicional_1" placeholder="Información adicional">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modal_info_adicional_2">Info Adicional 2</label>
                                                <input type="text" class="form-control" id="modal_info_adicional_2" placeholder="Información adicional">
                                            </div>
                                        </div>

                                        <!-- Configuración del Envío -->
                                        <h6 class="text-bold mb-3 border-bottom pb-2 mt-4">Configuración del Envío</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="modal_fragil">
                                                    <input type="checkbox" id="modal_fragil" value="1">
                                                    Frágil
                                                </label>
                                                <small class="form-text text-muted">Marcar si el envío es frágil</small>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_is_urgente">
                                                    <input type="checkbox" id="modal_is_urgente" value="1">
                                                    Urgente
                                                </label>
                                                <small class="form-text text-muted">Marcar si es envío urgente</small>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_valida_stock">
                                                    <input type="checkbox" id="modal_valida_stock" value="1">
                                                    Validar Stock
                                                </label>
                                                <small class="form-text text-muted">Validar stock disponible</small>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="modal_guia_agente">Guía Agente</label>
                                                <input type="text" class="form-control" id="modal_guia_agente" placeholder="Segundo remito">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_precinto">Precinto</label>
                                                <input type="text" class="form-control" id="modal_precinto" placeholder="Número de precinto">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_codigo_ceco">Código CECO</label>
                                                <input type="text" class="form-control" id="modal_codigo_ceco" placeholder="Centro de costo">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="modal_contrareembolso">Contrareembolso</label>
                                                <input type="number" step="0.01" class="form-control" id="modal_contrareembolso" placeholder="0.00">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_cobro_efectivo">Cobro Efectivo</label>
                                                <input type="number" step="0.01" class="form-control" id="modal_cobro_efectivo" placeholder="0.00">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="modal_cobro_cheque">Cobro Cheque</label>
                                                <input type="number" step="0.01" class="form-control" id="modal_cobro_cheque" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="modal_canal">Canal</label>
                                                <input type="text" class="form-control" id="modal_canal" placeholder="Ej: WEB, API, TIENDA NUBE">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="modal_codigo_expreso">Código Expreso</label>
                                                <input type="text" class="form-control" id="modal_codigo_expreso" placeholder="Ej: 01">
                                            </div>
                                        </div>

                                        <!-- Información Autocompletada -->
                                        <h6 class="text-bold mb-3 border-bottom pb-2 mt-4">Información Autocompletada</h6>
                                        <div class="alert alert-secondary">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Destinatario:</strong> <?php echo $row['clientenombre']; ?></p>
                                                    <p class="mb-1"><strong>Localidad:</strong> <?php echo $row['ciudad']; ?></p>
                                                    <p class="mb-1"><strong>Provincia:</strong> <?php echo $row['clienteprovincia']; ?></p>
                                                    <p class="mb-1"><strong>CP:</strong> <?php echo $row['clientecp']; ?></p>
                                                    <p class="mb-1"><strong>Remito:</strong> PED-<?php echo $id; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Altura:</strong> <?php echo $row['clientealtura'] ? $row['clientealtura'] : 'S/N'; ?></p>
                                                    <?php if ($rowf && $rowf['cuit']): ?>
                                                        <p class="mb-1"><strong>CUIT:</strong> <?php echo $rowf['cuit']; ?></p>
                                                    <?php endif; ?>
                                                    <p class="mb-1"><strong>Servicio:</strong> ESTANDAR</p>
                                                    <p class="mb-1"><strong>Valor Declarado:</strong> $<?php echo $row['total']; ?></p>
                                                    <p class="mb-1"><strong>Tipo Operación:</strong> ENTREGA</p>
                                                </div>
                                            </div>
                                            <small class="text-muted"><i class="fa fa-info-circle"></i> Estos datos se tomarán automáticamente del pedido.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="modal_observaciones">Observaciones</label>
                                            <textarea class="form-control" id="modal_observaciones" rows="2" placeholder="Agregar observaciones adicionales..."><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-warning" id="confirmarGenerarGuia">
                                        <i class="fa fa-check"></i> Generar Guía
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-lg-center mt-5">
                        <div class="col-md-12 p-0">
                            <h4 class="text-bold">Estado del pedido</h4>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 col-categoria p-3 d-flex flex-column flex-lg-row justify-content-around">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" class="estadoPedido" <?php if ($row['estado_pedido'] == 1) {
                                                                                                                                echo ' checked ';
                                                                                                                            } ?> value="1">
                                <label class="form-check-label" for="inlineRadio1">Para Procesar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" class="estadoPedido" <?php if ($row['estado_pedido'] == 2) {
                                                                                                                                echo ' checked ';
                                                                                                                            } ?> value="2">
                                <label class="form-check-label" for="inlineRadio2">Procesado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" class="estadoPedido" <?php if ($row['estado_pedido'] == 3) {
                                                                                                                                echo ' checked ';
                                                                                                                            } ?> value="3">
                                <label class="form-check-label" for="inlineRadio3">Enviado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" class="estadoPedido" <?php if ($row['estado_pedido'] == 4) {
                                                                                                                                echo ' checked ';
                                                                                                                            } ?> value="4">
                                <label class="form-check-label" for="inlineRadio4">Retirado Sucursal</label>
                                <input type="text" class="form-control" id="sucursal" placeholder="1">
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 d-block d-lg-flex justify-content-end p-0">
                            <input type="hidden" id="idpedido" value="<?php echo $row['id']; ?>">
                            <button type="button" id="cambiarEstado" class="btn border-success text-success">Guardar Estado</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<script>
    $(function() {


        $('#cambiarEstado').click(function(e) {
            e.preventDefault();
            var estado = $('input[name="inlineRadioOptions"]:checked').val();
            var idpedido = $('#idpedido').val();
            console.log(estado + ' ' + idpedido);
            $.post('../inc/cambiar_estado.php', {
                pedido: idpedido,
                estado: estado
            }, function(data) {
                if (data.success) {
                    alert('Estado Cambiado');
                }
            }, 'json');
        });

        $('#imprimirDatos').click(function(e) {
            e.preventDefault();
            $('#datosEnvio, #metodopago').printThis({
                importCSS: true
            });
        });

        $('#imprimirFacturacion').click(function(e) {
            e.preventDefault();
            $('#datosFacturacion').printThis({
                importCSS: true
            });
        });

        // Abrir modal para generar guía Epresis
        $('#generarGuiaEpresis').click(function(e) {
            e.preventDefault();

            // Obtener altura desde la base de datos del cliente
            var alturaCliente = '<?php echo $row['clientealtura']; ?>';

            // Parsear dirección para pre-llenar calle
            var direccion = '<?php echo addslashes($row['clientedireccion']); ?>';
            var calle = direccion;
            var altura = alturaCliente || 'S/N';

            // Si hay altura en BD, usarla. Sino, intentar separar de la dirección
            if (!alturaCliente || alturaCliente === '' || alturaCliente === 'null') {
                var match = direccion.match(/^(.+?)[\s,]+(\d+)/);
                if (match) {
                    calle = match[1].trim();
                    altura = match[2].trim();
                }
            } else {
                // Si hay altura en BD, limpiar la dirección de números
                calle = direccion.replace(/[\s,]+\d+.*$/, '').trim();
            }

            $('#modal_calle').val(calle);
            $('#modal_altura').val(altura);

            // Abrir modal
            $('#modalGenerarGuia').modal('show');
        });

        // Confirmar generación de guía desde el modal
        $('#confirmarGenerarGuia').click(function(e) {
            e.preventDefault();

            // Validar formulario
            if (!$('#formGenerarGuia')[0].checkValidity()) {
                $('#formGenerarGuia')[0].reportValidity();
                return;
            }

            var btn = $(this);
            var idpedido = $('#modal_pedido_id').val();

            // Recopilar datos del formulario
            var datosGuia = {
                pedido_id: idpedido,
                // Datos del destinatario
                empresa: $('#modal_empresa').val(),
                calle: $('#modal_calle').val(),
                altura: $('#modal_altura').val(),
                piso: $('#modal_piso').val(),
                dpto: $('#modal_dpto').val(),
                hora_desde: $('#modal_hora_desde').val(),
                hora_hasta: $('#modal_hora_hasta').val(),
                celular: $('#modal_celular').val(),
                cuit: $('#modal_cuit').val(),
                contenido: $('#modal_contenido').val(),
                info_adicional_1: $('#modal_info_adicional_1').val(),
                info_adicional_2: $('#modal_info_adicional_2').val(),
                // Configuración del envío
                fragil: $('#modal_fragil').is(':checked') ? 1 : 0,
                is_urgente: $('#modal_is_urgente').is(':checked') ? 1 : 0,
                valida_stock: $('#modal_valida_stock').is(':checked') ? 1 : 0,
                guia_agente: $('#modal_guia_agente').val(),
                precinto: $('#modal_precinto').val(),
                codigo_ceco: $('#modal_codigo_ceco').val(),
                contrareembolso: $('#modal_contrareembolso').val(),
                cobro_efectivo: $('#modal_cobro_efectivo').val(),
                cobro_cheque: $('#modal_cobro_cheque').val(),
                canal: $('#modal_canal').val(),
                codigo_expreso: $('#modal_codigo_expreso').val(),
                observaciones: $('#modal_observaciones').val()
            };

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generando...');

            $.post('inc/generar_guia_ajax.php', datosGuia, function(data) {
                if (data.success) {
                    // Cerrar modal
                    $('#modalGenerarGuia').modal('hide');

                    // Mostrar información de la guía generada
                    var html = `
                    <div class="alert alert-success">
                        <h5 class="mb-3"><i class="fa fa-check-circle"></i> Guía Generada Exitosamente</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Código de Guía:</strong>
                                <p class="h4 text-primary">${data.guia}</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Remito:</strong>
                                <p>${data.remito}</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Importe:</strong>
                                <p>$${parseFloat(data.importe).toFixed(2)}</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Zona:</strong>
                                <p>${data.sub_zona_destino}</p>
                            </div>
                        </div>
                        <p class="mb-0 text-muted small">Generada hace un momento</p>
                    </div>
                `;

                    $('#guiaEpresisContainer').html(html);
                    $('#generarGuiaEpresis').remove(); // Remover el botón ya que ya se generó la guía

                    alert('Guía generada exitosamente. Código: ' + data.guia);
                } else {
                    alert('Error al generar guía: ' + (data.error || 'Error desconocido'));
                    btn.prop('disabled', false).html('<i class="fa fa-check"></i> Generar Guía');
                }
            }, 'json').fail(function(xhr, status, error) {
                alert('Error de conexión: ' + error);
                btn.prop('disabled', false).html('<i class="fa fa-check"></i> Generar Guía');
            });
        });

    });
</script>