<?php include('header.php'); ?>
<?php
include __DIR__ . '/../inc/funciones.inc.php';
include __DIR__ . '/conexion/conectar.inc.php';
global $conectar;
$id = $_GET['id'];
$pedidos = $conectar->query("SELECT p.*,(SELECT id_producto FROM pedidos_detalle WHERE id_pedido=p.id LIMIT 1 ) as producto,(SELECT imagen FROM imagenes WHERE id_pedido=p.id LIMIT 1 ) as imagen,DATE_FORMAT(fecha, '%d-%m-%Y') as fecha,CONCAT(c.nombre,' ',c.apellido) as clientenombre,c.dni clientedni,CONCAT(c.direccion,' ',c.ciudad) as clientedireccion,c.cp clientecp,c.provincia clienteprovincia,c.telefono clientetelefono FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE p.id='$id' ");
$row = $pedidos->fetch_assoc();
$imagenes = $conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido='$id'");
$productos = $conectar->query("SELECT pd.cantidad,p.*,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM pedidos_detalle pd LEFT JOIN productos p ON pd.id_producto=p.id WHERE pd.id_pedido='$id'");

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



                    <div class="row align-items-lg-center mt-0 mt-md-3">
                        <div class="col-md-9 p-0">
                            <h4 class="text-bold">Fotos a revelar</h4>
                        </div>
                        <div class="col-md-3 text-left text-lg-right p-0">
                            <a download href="<?php echo $archivo; ?>" class="btn btn-bg-transparent text-danger" id="descargaTodo">Descargar Todo</a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <?php
                        $i = 1;
                        $imagenes->data_seek(0);
                        while ($rowi = $imagenes->fetch_assoc()) { ?>
                            <div class="col-12 p-4 col-categoria my-2">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img class="img-fluid w-100" src="<?php echo '../' . $rowi['thumb']; ?>" alt="">
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

                    <div class="row align-items-lg-center mt-5">
                        <div class="col-md-12 p-0">
                            <h4 class="text-bold">Detalles de Compra</h4>
                            <?php
                            $repla = array('<', '>');
                            $texto = str_replace($repla, '<br>', $row['descripcion']);
                            $texto = preg_replace_callback('/#([0-9a-fA-F]{6})/', function ($m) {
                                $color = '#' . $m[1];
                                return '<span class="dot" style="background-color: ' . $color . '; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span>';
                            }, $texto);
                            ?>
                            <p><?php echo  $texto; ?></p>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <?php while ($rowp = $productos->fetch_assoc()) { ?>
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
                                            <p class="detalles-pedido"><?php echo $rowp['nombre'] ?></p>
                                        </div>
                                        <p class="descripcion-pedido"><?php echo $rowp['descripcion'] ?> <br> <?php echo $rowp['cantidad']; ?> </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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
                            if ($row['envio'] == 'domicilio') {
                                $titulo = 'Enviar a mi domicilio';
                                $nombre = $row['clientenombre'];
                                $dni = $row['clientedni'];
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
                                case 'suc1':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 1</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'suc2':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 2</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
                                    break;
                                case 'suc3':
                                    echo '<h5>Retiro Gratis por Sucursal</h5>
                            <p>Sucursal 3</p>
                            <p>Calle 12 N°1108 e/55 y 56</p>' .  $titulo = '<strong>Datos de usuario</strong><br>' . '<div> Nombre y Apellido:  ' . $nombre = $row['clientenombre'] . '</div>' . '<div> DNI:  ' . $dni = $row['clientedni'] . '</div>' . '<div>Dirección:  ' . $direccion = $row['clientedireccion'] . '</div>' . '<div> Provincia:  ' . $provincia = $row['clienteprovincia'] . '</div>' . '<div>Código Postal:  ' . $cp = $row['clientecp'] . '</div>' . '<div>Teléfono:  ' . $telefono = $row['clientetelefono'] . '</div>';
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
                                    <h5 class="text-danger">Requiere Factura A</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>CUIT:</strong> <?php echo $rowf['cuit']; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Razón Social:</strong> <?php echo $rowf['razon_social']; ?></p>
                                        </div>
                                    </div>
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
                                <h4 class="text-bold">Guía de Envío Epresis</h4>
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

        // Generar Guía Epresis
        $('#generarGuiaEpresis').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var idpedido = $('#idpedido').val();

            // Confirmar acción
            if (!confirm('¿Deseas generar la guía de envío Epresis para este pedido?')) {
                return;
            }

            // Deshabilitar botón y mostrar loading
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generando...');

            $.post('inc/generar_guia_ajax.php', {
                pedido_id: idpedido
            }, function(data) {
                if (data.success) {
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
                    btn.remove(); // Remover el botón ya que ya se generó la guía

                    alert('Guía generada exitosamente. Código: ' + data.guia);
                } else {
                    alert('Error al generar guía: ' + (data.error || 'Error desconocido'));
                    btn.prop('disabled', false).html('<i class="fa fa-truck"></i> Generar Guía');
                }
            }, 'json').fail(function(xhr, status, error) {
                alert('Error de conexión: ' + error);
                btn.prop('disabled', false).html('<i class="fa fa-truck"></i> Generar Guía');
            });
        });

    });
</script>