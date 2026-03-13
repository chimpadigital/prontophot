<?php include('header.php'); ?>
<?php
// Obtener datos del cliente logueado
if (isset($_SESSION['prontoFront']['token'])) {
    $dato = verificarToken($_SESSION['prontoFront']['token'], 'Pronto');
    if ($dato->success) {
        $id = $dato->id;
        $cliente = $conectar->query("SELECT * FROM clientes WHERE id='$id'");
        $rowc = $cliente->fetch_assoc();
    }
}


if (isset($_POST['entrega'])) {
    $costoenvio = 0;
    $enviotag = '';
    $metodo_envio_id = null;

    $_SESSION['prontoFront']['envio']['tipo'] = $_POST['entrega'];
    $_SESSION['prontoFront']['envio']['envio'] = $_POST['envio'] ?? '';

    // Verificar si se seleccionó un método de envío dinámico
    if (strpos($_POST['entrega'], 'envio_') === 0) {
        // Extraer el ID del método de envío
        $metodo_envio_id = str_replace('envio_', '', $_POST['entrega']);

        // Si es Epresis (ID 2), usar el costo calculado
        if ($metodo_envio_id == 2) {
            $costoenvio = isset($_POST['epresis_costo']) ? floatval($_POST['epresis_costo']) : 0;
            $enviotag = $costoenvio > 0 ? '$ ' . $costoenvio : '<span class="text-success">GRATIS</span>';
            $_SESSION['prontoFront']['envio']['epresis_fecha'] = $_POST['epresis_fecha'] ?? '';
        } elseif ($metodo_envio_id != 2) {
            // Obtener datos del método de envío desde la base de datos
            $metodo_query = $conectar->query("SELECT * FROM metodos_envio WHERE id='$metodo_envio_id'");
            if ($metodo_row = $metodo_query->fetch_assoc()) {
                $costoenvio = $metodo_row['valor'];
                $valor_gratis = floatval($metodo_row['valor_gratis']);

                // Calcular el total del carrito para verificar envío gratis
                $total_carrito = 0;
                if (isset($_SESSION['pronto']['cart'])) {
                    foreach ($_SESSION['pronto']['cart'] as $id_prod => $item_cart) {
                        $prod_res = $conectar->query("SELECT precio, descuento_final FROM productos WHERE id='$id_prod'");
                        if ($prod_row = $prod_res->fetch_assoc()) {
                            $precioUnitario = $prod_row['precio'];
                            $descuento = isset($prod_row['descuento_final']) && $prod_row['descuento_final'] > 0 ? $prod_row['descuento_final'] : 0;

                            if ($descuento > 0) {
                                $precioUnitario = $prod_row['precio'] - ($prod_row['precio'] * $descuento / 100);
                            }

                            $total_carrito += ($precioUnitario * $item_cart['cantidad']);
                        }
                    }
                }

                // Aplicar envío gratis si el total del carrito supera el umbral
                if ($valor_gratis > 0 && $total_carrito >= $valor_gratis) {
                    $costoenvio = 0;
                    $enviotag = '<span class="text-success">GRATIS</span>';
                } else {
                    $enviotag = $metodo_row['valor'] > 0 ? '$ ' . $metodo_row['valor'] : 'Sin cargo';
                }
            }
        }
    } else {
        // Compatibilidad con los valores antiguos hardcodeados
        if ($_POST['entrega'] == 'urbano') {
            $costoenvio = 250;
            $enviotag = '$ 250';
        } elseif ($_POST['entrega'] == 'suc2' || $_POST['entrega'] == 'suc1') {
            $costoenvio = 0;
            $enviotag = 'Retiro por sucursal';
        } else {
            if ($_POST['entrega'] == 'recibir') {
                $enviotag = 'Abona al recibir';
            }
            $costoenvio = 0;
        }
    }

    $_SESSION['prontoFront']['envio']['costo'] = $costoenvio;
    $_SESSION['prontoFront']['envio']['metodo_envio_id'] = $metodo_envio_id;
    $_SESSION['prontoFront']['envio']['nombre'] = $rowc['nombre'];
    $_SESSION['prontoFront']['envio']['apellido'] = $rowc['apellido'];
    $_SESSION['prontoFront']['envio']['dni'] = $rowc['dni'];
    $_SESSION['prontoFront']['envio']['direccion'] = $rowc['direccion'];
    $_SESSION['prontoFront']['envio']['altura'] = $rowc['altura'];
    $_SESSION['prontoFront']['envio']['email'] = $rowc['email'];
    $_SESSION['prontoFront']['envio']['provincia'] = $rowc['provincia'];
    $_SESSION['prontoFront']['envio']['ciudad'] = $rowc['ciudad'];
    $_SESSION['prontoFront']['envio']['cp'] = $rowc['cp'];
    $_SESSION['prontoFront']['envio']['telefono'] = $rowc['telefono'] ?? '';
    $_SESSION['prontoFront']['envio']['celular'] = $rowc['celular'] ?? '';
    $_SESSION['prontoFront']['envio']['obs'] = $rowc['observaciones'] ?? '';

    $costototal = $_SESSION['prontoFront']['monto'];
    $cart = $_SESSION['pronto']['cart'];
    $c = 0;
    $t = 0;
    foreach ($cart as $id => $item) {
        $cat = $item['cat'];
        $cant = $item['cantidad'];

        // Obtener precio y descuento del producto
        $prod_res = $conectar->query("SELECT precio, descuento_final FROM productos WHERE id='$id'");
        $prod_row = $prod_res->fetch_assoc();
        $precioUnitario = $prod_row['precio'];
        $descuento = isset($prod_row['descuento_final']) && $prod_row['descuento_final'] > 0 ? $prod_row['descuento_final'] : 0;

        // Aplicar descuento si existe
        if ($descuento > 0) {
            $precioUnitario = $prod_row['precio'] - ($prod_row['precio'] * $descuento / 100);
        }

        $sub = ($precioUnitario * $cant);
        $t = $t + $sub;
    }
    if (isset($_SESSION['prontoFront']['cupon'])) {
        $desc = $_SESSION['prontoFront']['cupon']['valor'];
        $md = ($costototal * $desc) / 100;
        $cupon = '<span class="badge badge-info"><small>Cupon ' . $_SESSION["prontoFront"]["cupon"]["nombre"] . '</small></span>';
    } else {
        $md = 0;
        $cupon = '';
    }
    $total = $costoenvio + $t;
    $_SESSION['prontoFront']['valor'] = $total;
} else {
    header('Location: revelado-paso2');
    exit();
}
?>
<div class="">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-bold font-monument titulo-portada">Finalizar pedido</h2>
            </div>
        </div>
    </div>
</div>

<div id="contenido-paso-3">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- FORM METODO PAGO -->

                <!-- <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center ">
                    <div id="metodo-pago" class="col-9 col-md-10  order-md-1">
                        <div class="custom-control contenedor-input custom-radio  ">
                            <input type="radio" id="transfer" name="metodoPago" data-toggle='collapse' data-target='#collapsediv2' value="1" aria-expanded="false" class="custom-control-input metodoPago">
                            <label class="custom-control-label" for="transfer">Tranferencia Bancaria</label>
                        </div>
                    </div>
                    <div class="col-3 col-md-2 order-1 order-md-2">
                        <img class="img-fluid" src="assets/img/transferencia.svg" alt="">
                    </div>
                </div>

                <div id="collapsediv2" class="collapse in py-4">
                    <p>- Transferí la suma de tu pedido a la siguiente cuenta: <br><br>

                        Paso 1: <br>
                        <span class="text-bold">Número de Cuenta:</span> CC en Pesos 099-005957/6
                        <br>
                        <span class="text-bold">Número de CBU:</span> 0720099120000000595768 <br>
                        <span class="text-bold">Alias:</span> DIGITALCLIK <br>
                        <span class="text-bold">Razón Social:</span> DIGITAL CLIK SA <br>
                        <span class="text-bold">CUIT/CUIL:</span> 30710261438 <br><br>

                        Paso 2: <br>
                        - Enviá un mail a: hola@prontophot.com o un Whatsapp a 2215708341 con el
                        asunto <span class="text-bold">“Comprobante de pago – Pedido [Tu Número de
                            Pedido]”</span> no hace falta
                        ningún texto, solo tener adjunto la imagen del comprobante de
                        transferencia.<br><br>

                        Paso 3:<br>
                        - Esperar nuestro contacto para la confirmación del pedido.
                    </p>
                </div> -->

                <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center my-4">
                    <div class="col-9 col-md-10  order-md-1">
                        <div class="custom-control contenedor-input custom-radio ">
                            <input type="radio" id="mp" name="metodoPago" class="custom-control-input no-transfer metodoPago" value="2" checked>
                            <label class="custom-control-label" for="mp">Mercado Pago</label>
                        </div>
                    </div>
                    <div class="col-3 col-md-2 order-1 order-md-2">
                        <img class="img-fluid" src="assets/img/MP.svg" alt="">
                    </div>
                </div>

                <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center my-4">
                    <div class="col-9 col-md-10  order-md-1">
                        <div class="custom-control contenedor-input custom-radio ">
                            <input type="radio" id="getnet" name="metodoPago" class="custom-control-input no-transfer metodoPago" value="4">
                            <label class="custom-control-label" for="getnet">GetNet</label>
                        </div>
                    </div>
                    <div class="col-3 col-md-2 order-1 order-md-2">
                        <img class="img-fluid" src="assets/img/getnet-isotipo.png" alt="" style="max-height: 40px;">
                    </div>
                </div>

                <!--
                <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center">
                    <div class="col-9 col-md-10  order-md-1">
                        <div class="custom-control contenedor-input custom-radio ">
                            <input type="radio" id="retiroSucursal" <?php if ($_SESSION['prontoFront']['envio']['tipo'] == 'urbano' or $_SESSION['prontoFront']['envio']['tipo'] == 'sucursal' or $_SESSION['prontoFront']['envio']['tipo'] == 'recibir') {
                                                                        echo 'disabled';
                                                                    } ?> name="metodoPago" class="custom-control-input no-transfer metodoPago" value="3">
                            <label class="custom-control-label" for="retiroSucursal">Pagar al retirar por una sucursal</label>
                        </div>
                    </div>
                    <div class="col-3 col-md-2 order-1 order-md-2">
                        <img class="img-fluid" src="assets/img/returarSucursal.svg" alt="">
                    </div>
                </div>
            -->

                <!-- Formulario de Facturación -->
                <div class="row shadow-sm p-3 p-md-4 mt-4 mb-4">
                    <div class="col-md-12">
                        <h5 class="titulo-tabs-user" style="margin: 0px;">Datos de Facturación</h5>
                        <h6 clasS="mb-4">Revisar los datos de facturación antes de continuar</h6>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fac_nombre">Nombre *</label>
                                <input type="text" class="form-control" id="fac_nombre" name="fac_nombre" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['nombre'] : ''; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fac_apellido">Apellido *</label>
                                <input type="text" class="form-control" id="fac_apellido" name="fac_apellido" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['apellido'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fac_dni">DNI *</label>
                                <input type="text" class="form-control" id="fac_dni" name="fac_dni" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['dni'] : ''; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fac_email">Email *</label>
                                <input type="email" class="form-control" id="fac_email" name="fac_email" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['email'] : ''; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cuil">CUIL</label>
                                <input type="text" class="form-control" id="cuil" name="cuil" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['cuil'] : ''; ?>" placeholder="XX-XXXXXXXX-X">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fac_direccion">Dirección *</label>
                                <input type="text" class="form-control" id="fac_direccion" name="fac_direccion" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['direccion'] : ''; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fac_altura">Altura *</label>
                                <input type="number" class="form-control" id="fac_altura" name="fac_altura" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['altura'] : ''; ?>" required min="1" max="99999">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fac_provincia">Provincia *</label>
                                <select name="fac_provincia" id="fac_provincia" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Buenos Aires" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Buenos Aires') ? 'selected' : ''; ?>>Buenos Aires</option>
                                    <option value="CABA" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'CABA') ? 'selected' : ''; ?>>CABA</option>
                                    <option value="Catamarca" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Catamarca') ? 'selected' : ''; ?>>Catamarca</option>
                                    <option value="Chaco" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Chaco') ? 'selected' : ''; ?>>Chaco</option>
                                    <option value="Chubut" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Chubut') ? 'selected' : ''; ?>>Chubut</option>
                                    <option value="Cordoba" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Cordoba') ? 'selected' : ''; ?>>Cordoba</option>
                                    <option value="Corrientes" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Corrientes') ? 'selected' : ''; ?>>Corrientes</option>
                                    <option value="Entre Rios" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Entre Rios') ? 'selected' : ''; ?>>Entre Rios</option>
                                    <option value="Formosa" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Formosa') ? 'selected' : ''; ?>>Formosa</option>
                                    <option value="Jujuy" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Jujuy') ? 'selected' : ''; ?>>Jujuy</option>
                                    <option value="La Pampa" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'La Pampa') ? 'selected' : ''; ?>>La Pampa</option>
                                    <option value="La Rioja" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'La Rioja') ? 'selected' : ''; ?>>La Rioja</option>
                                    <option value="Mendoza" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Mendoza') ? 'selected' : ''; ?>>Mendoza</option>
                                    <option value="Misiones" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Misiones') ? 'selected' : ''; ?>>Misiones</option>
                                    <option value="Neuquen" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Neuquen') ? 'selected' : ''; ?>>Neuquen</option>
                                    <option value="Rio Negro" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Rio Negro') ? 'selected' : ''; ?>>Rio Negro</option>
                                    <option value="Salta" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Salta') ? 'selected' : ''; ?>>Salta</option>
                                    <option value="San Juan" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'San Juan') ? 'selected' : ''; ?>>San Juan</option>
                                    <option value="San Luis" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'San Luis') ? 'selected' : ''; ?>>San Luis</option>
                                    <option value="Santa Cruz" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Santa Cruz') ? 'selected' : ''; ?>>Santa Cruz</option>
                                    <option value="Santa Fe" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Santa Fe') ? 'selected' : ''; ?>>Santa Fe</option>
                                    <option value="Santiago del Estero" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Santiago del Estero') ? 'selected' : ''; ?>>Santiago del Estero</option>
                                    <option value="Tierra del Fuego" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Tierra del Fuego') ? 'selected' : ''; ?>>Tierra del Fuego</option>
                                    <option value="Tucuman" <?php echo (isset($_SESSION['prontoFront']['token']) && $rowc['provincia'] == 'Tucuman') ? 'selected' : ''; ?>>Tucuman</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="fac_ciudad">Ciudad *</label>
                                <input type="text" class="form-control" id="fac_ciudad" name="fac_ciudad" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['ciudad'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="fac_cp">CP *</label>
                                <input type="text" class="form-control" id="fac_cp" name="fac_cp" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['cp'] : ''; ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fac_telefono">Teléfono</label>
                                <input type="text" class="form-control" id="fac_telefono" name="fac_telefono" value="<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['telefono'] : ''; ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fac_celular">Celular</label>
                                <input type="text" class="form-control" id="fac_celular" name="fac_celular" value="">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="factura_a" id="facturaA" value="1">
                            <label class="form-check-label text-bold" for="facturaA">
                                Necesito Factura A
                            </label>
                        </div>
                        <div id="datosFacturaA" style="display: none;">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="razon_social">Razón Social</label>
                                    <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón Social">
                                </div>
                            <div class="form-group col-md-6" id="cuitContainer" style="display: none;">
                                <label for="cuit">CUIT</label>
                                <input type="text" class="form-control" id="cuit" name="cuit" placeholder="XX-XXXXXXXX-X">
                            </div>
                            </div>
                        </div>

                        <hr class="my-4">
                    </div>
                </div>

            </div>

            <div class="col-md-4 pl-0 pl-md-5 mb-5">
                <div class="row shadow-sm p-2">
                    <div class="col-md-12 ">
                        <h5 class="subtitulo-tabs-user mt-5 mt-md-0 py-3">Resumen de Compra</h5>
                        <hr class="divisor-resumen-compra">
                    </div>
                    <div class="col-md-12">
                        <?php
                        $carro = $_SESSION['pronto']['cart'];
                        $total = 0;
                        $prod = '';
                        $resumen = '';
                        $porc = 0;
                        $subd = 0;
                        foreach ($carro as $id => $datos) {
                            $res = $conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
                            $row = $res->fetch_assoc();
                            $cant = $datos['cantidad'];
                            $cat = $datos['cat'];

                            // Calcular precio con descuento si existe
                            $precioUnitario = $row['precio'];
                            $descuento = isset($row['descuento_final']) && $row['descuento_final'] > 0 ? $row['descuento_final'] : 0;
                            if ($descuento > 0) {
                                $precioUnitario = $row['precio'] - ($row['precio'] * $descuento / 100);
                            }

                            $precio = ($cant * $precioUnitario);

                            $prod .= $row['nombre'] . ', ' . $datos['color'] . ' x ' . $datos['cantidad'] . '<br>';

                            $resumen .= '<div class="d-block w-100">'.$row['nombre'] . ', <span class="dot" style="background-color: ' . $datos['color'] . '; width: 15px; height: 15px; display: inline-block; border-radius: 50%; border: 2px solid #ddd; margin-left: 5px;"></span> x ' . $datos['cantidad'] . '</div>';

                            if (isset($_SESSION['prontoFront']['cupon'])) {
                                if ($_SESSION['prontoFront']['cupon']['categoria'] == $cat) {
                                    $porc = intval($_SESSION['prontoFront']['cupon']['valor']);
                                    $sub = ($precio * $porc) / 100;
                                    $subd = $subd + $sub;
                                }
                            }

                            $total = $total + $precio;

                        ?>

                        <?php } ?>
                        <div class="descrip-pedido m-0 py-3 align-items-center"><?php echo $resumen; ?></div>
                        <input type="hidden" id="chk-desc" value="<?php echo $prod; ?>">
                        <hr class="divisor-resumen-compra">
                    </div>
                    <div class="col-md-12">
                        <h5 class="subtitulo-tabs-user pt-3">Ingresa cupon de descuento</h5>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" data-seccion="1" id="codDescuento" placeholder="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary btn-sm" id="btnDescuento" style="padding:.25rem .5rem;" type="button">Aplicar</button>
                            </div>
                        </div>
                        <hr class="divisor-resumen-compra">
                    </div>
                    <div class="col-md-12 totales">
                        <p class="m-0 mb-2">Sub-Total $ <?php echo $total; ?></p>
                        
                        <?php if($enviotag == 'Retiro por sucursal') : ?>
                        <p class="m-0 mb-2"><?php echo $enviotag; ?></p>
                        <?php else: ?>    
                        <p class="m-0 mb-2">Envio <?php echo $enviotag; ?></p>
                        <?php endif; ?>
                        <p class="m-0 mb-2">Cupón de descuento $ <?php echo $subd; ?></p>
                        <?php echo $cupon; ?>
                        <p class="m-0 text-bold mb-4">TOTAL $ <?php echo $total + $costoenvio - $subd; ?></p>
                    </div>
                </div>
                <div class="row p-0">
                    <div class="col-md-12 p-0 m-0 mb-5">
                        <button type="button" class="btn btn-success btn-lg btn-block rounded-bottom btn-pagar">Pagar</button>
                    </div>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
<script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>
<script src="https://www.pre.globalgetnet.com/digital-checkout/loader.js"></script>
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

<script>
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
        $('.metodoPago').change(function() {
            var meto = $(this).val();
            if (meto != '2' && meto != '4') {
                $('.btn-pagar').html('Finalizar pedido');
            } else {
                $('.btn-pagar').html('Pagar');
            }
        });

        $('.btn-pagar').click(function(e) {
            e.preventDefault();
            var metodo = $(".metodoPago:checked").val();
            var desc = 'Compra ' + $('#chk-desc').val();

            // Recopilar datos de facturación
            var facturacionData = {
                nombre: $('#fac_nombre').val(),
                apellido: $('#fac_apellido').val(),
                dni: $('#fac_dni').val(),
                email: $('#fac_email').val(),
                direccion: $('#fac_direccion').val(),
                provincia: $('#fac_provincia').val(),
                ciudad: $('#fac_ciudad').val(),
                cp: $('#fac_cp').val(),
                telefono: $('#fac_telefono').val(),
                celular: $('#fac_celular').val(),
                factura_a: $('#facturaA').is(':checked') ? 1 : 0,
                cuit: $('#cuit').val(),
                cuil: $('#cuil').val(),
                razon_social: $('#razon_social').val(),
                altura: $('#fac_altura').val(),
            };

            $(this).html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');
            $(this).prop('disabled', true);

            if (metodo == '2') {
                // MercadoPago
                $.post('inc/crear_pago2.php', {
                    metodo: metodo,
                    desc: desc,
                    facturacion: facturacionData
                }, function(data) {
                    $('.btn-pagar').html('Pagar');
                    $('.btn-pagar').prop('disabled', false);
                    $MPC.openCheckout({
                        url: data.url,
                        mode: "modal",
                        onreturn: function(data) {
                            if (data.collection_status == 'approved') {
                                window.location.href = "checkout-resultado";
                            } else {
                                alert('Error al realizar el pago, intente nuevamente');
                            }
                        }
                    });

                }, 'json');
            } else if (metodo == '4') {
                // GetNet
                $.post('inc/crear_pago_getnet.php', {
                    metodo: metodo,
                    desc: desc,
                    facturacion: facturacionData
                }, function(data) {
                    $('.btn-pagar').html('Pagar');
                    $('.btn-pagar').prop('disabled', false);

                    if (data.success && data.checkout_id) {
                        // Abrir checkout de GetNet
                        GetnetCheckout.open({
                            checkoutId: data.checkout_id,
                            onSuccess: function(response) {
                                console.log('GetNet Success:', response);
                                const config = { "paymentIntentId": response.payment_intent_id, "checkoutType": "lightbox" }; 
                                const checkoutButton = () => { loader.init(config) }; 
                            },
                            onError: function(error) {
                                console.error('GetNet Error:', error);
                                alert('Error al realizar el pago con GetNet, intente nuevamente');
                            },
                            onClose: function() {
                                console.log('GetNet Checkout cerrado');
                            }
                        });
                    } else {
                        alert('Error al crear el pago: ' + (data.error || 'Error desconocido'));
                    }
                }, 'json').fail(function(xhr, status, error) {
                    $('.btn-pagar').html('Pagar');
                    $('.btn-pagar').prop('disabled', false);
                    console.error('Error AJAX:', error);
                    alert('Error al procesar el pago, intente nuevamente');
                });
            } else {
                // Otros métodos de pago
                $.post('inc/crear_pedido2.php', {
                    metodo: metodo,
                    desc: desc,
                    facturacion: facturacionData
                }, function(data) {
                    if (data.success) {
                        $('.btn-pagar').html('Pagar');
                        window.location.href = "checkout-resultado?pedido=" + data.pedido;
                    }
                }, 'json');
            }
        });

        // Toggle para mostrar/ocultar datos de Factura A
        $('#facturaA').change(function() {
            if ($(this).is(':checked')) {
                $('#datosFacturaA').slideDown();
                $('#cuitContainer').slideDown();
                $('#razon_social').prop('required', true);
            } else {
                $('#datosFacturaA').slideUp();
                $('#cuitContainer').slideUp();
                $('#razon_social').prop('required', false);
                $('#razon_social').val('');
                $('#cuit').val('');
            }
        });

        // Detectar cambios en el CP y recalcular Epresis si está seleccionado
        var ultimoCp = '<?php echo isset($_SESSION['prontoFront']['token']) ? $rowc['cp'] : ''; ?>';
        var metodoEnvioActual = '<?php echo isset($_SESSION['prontoFront']['envio']['metodo_envio_id']) ? $_SESSION['prontoFront']['envio']['metodo_envio_id'] : ''; ?>';

        $('#fac_cp').on('blur', function() {
            var nuevoCp = $(this).val();

            // Solo recalcular si cambió el CP y el método de envío es Epresis (ID 2)
            if (nuevoCp !== ultimoCp && metodoEnvioActual == '2' && nuevoCp.length >= 4) {
                recalcularEpresis(nuevoCp);
                ultimoCp = nuevoCp;
            }
        });

        function recalcularEpresis(cp) {
            // Mostrar indicador de carga en el área de envío
            $('.totales p:contains("Envio")').html('Envio <i class="fa fa-spin fa-spinner"></i>');

            $.post('inc/metodo_envio.php', {
                cp_destino: cp
            }, function(data) {
                if (data.success) {
                    var costoEnvio = parseFloat(data.costo);
                    var fecha = data.fecha;
                    var subtotal = <?php echo $total; ?>;

                    // Actualizar el costo de envío en la sesión vía AJAX
                    $.post('inc/actualizar_envio_sesion.php', {
                        costo: costoEnvio,
                        fecha: fecha
                    }, function(response) {
                        if (response.success) {
                            // Actualizar visualmente el costo de envío
                            var envioTag = costoEnvio > 0 ? '$ ' + costoEnvio.toFixed(2) : '<span class="text-success">GRATIS</span>';
                            $('.totales p:contains("Envio")').html('Envio ' + envioTag);

                            // Recalcular y actualizar el total
                            var descuento = <?php echo $subd; ?>;
                            var nuevoTotal = subtotal + costoEnvio - descuento;
                            $('.totales p:contains("TOTAL")').html('TOTAL $ ' + nuevoTotal.toFixed(2));

                            // Mostrar mensaje de actualización
                            $('.totales').append('<p class="text-success mt-2 mensaje-actualizacion"><small>✓ Envío actualizado</small></p>');
                            setTimeout(function() {
                                $('.mensaje-actualizacion').fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 3000);
                        }
                    }, 'json').fail(function() {
                        $('.totales p:contains("Envio")').html('Envio <span class="text-danger">Error al actualizar</span>');
                    });
                } else {
                    $('.totales p:contains("Envio")').html('Envio <span class="text-danger">Error: ' + data.error + '</span>');
                }
            }, 'json').fail(function() {
                $('.totales p:contains("Envio")').html('Envio <span class="text-danger">Error de conexión</span>');
            });
        }

    });
</script>
</body>

</html>