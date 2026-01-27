<?php include ('header.php');

if (isset($_SESSION['prontoFront']['token'])) {
    $dato=verificarToken($_SESSION['prontoFront']['token'], 'Pronto');
    if ($dato->success) {
        $id=$dato->id;
        $cliente=$conectar->query("SELECT * FROM clientes WHERE id='$id'");
        $rowc=$cliente->fetch_assoc();
    }else{

    }
}

// Obtener métodos de envío
$metodos_envio = $conectar->query("SELECT * FROM metodos_envio ORDER BY id ASC");

// Obtener datos de Epresis (ID 2) para usar en JavaScript
$epresis_data = $conectar->query("SELECT valor_gratis FROM metodos_envio WHERE id=2");
$epresis_row = $epresis_data->fetch_assoc();
$epresis_valor_gratis = $epresis_row['valor_gratis'] ?? 0;

// Obtener productos del carrito con sus dimensiones
$productos_carrito = [];
if (isset($_SESSION['pronto']['cart'])) {
    $carro = $_SESSION['pronto']['cart'];
    foreach($carro as $id_producto => $datos){
        $res = $conectar->query("SELECT ancho, alto, profundidad FROM productos WHERE id='$id_producto'");
        if($row = $res->fetch_assoc()){
            $productos_carrito[] = [
                'id' => $id_producto,
                'cantidad' => $datos['cantidad'],
                'ancho' => floatval($row['ancho'] ?? 0.25),
                'alto' => floatval($row['alto'] ?? 0.25),
                'profundidad' => floatval($row['profundidad'] ?? 0.25),
                'peso' => 0.5 // Peso por defecto en kg
            ];
        }
    }
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

<div id="contenido-paso-2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h5 class="titulo-tabs-user">Selecciona tu método de envío</h5>
            </div>
        </div>
        <form class="formCheckout " action="checkout_2.php" method="post" id="formEnvio">
        <!-- checkout-pago -->
        <!-- FORM METODO ENVIO -->
        <div class="row metodo-envio">
            <div class="col-md-6 ">
                <div class="mx-1 shadow-sm p-3 p-md-5">
                    <p class="text-bold text-danger">Retiro gratis por Sucursal</p>
                    <div class="form-check mb-3">
                        <input class="form-check-input retiroSucursal" type="radio" name="entrega"  id="sucursal1" value="suc1" checked>
                        <label class="form-check-label d-flex flex-row" for="sucursal1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                            <div>
                                <p class="d-inline-block text-bold m-0">Sucursal 1</p>
                                <span class="d-block">Calle 12 N°1108 e/55 y 56</span>
                                <span class="d-block">WhatsApp 2216784142</span>
                            </div>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input retiroSucursal" type="radio" name="entrega" id="sucursal2" value="suc2">
                        <label class="form-check-label d-flex flex-row" for="sucursal2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                            <div>
                                <p class="d-inline-block text-bold m-0">Sucursal 2</p>
                                <span class="d-block">Cantilo N° 173 e/ 13ª y 13b, City Bell</span>
                                <span class="d-block">WhatsApp 2213581837</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div id="metodo-envio" class="col-md-6 my-3 my-md-0 ">
                <div class="mx-1 shadow-sm p-3 p-md-5">
                    <p class="text-bold m-0 text-danger">Envío a Domicilio</p>
                    <?php
                    $metodo_counter = 0;
                    while($metodo = $metodos_envio->fetch_assoc()){
                        $metodo_counter++;
                        // ID 2 es para Epresis, lo manejamos por separado
                        if($metodo['id'] == 2) {
                            continue;
                        }
                    ?>
                    <div class="form-check mb-3 <?php echo $metodo_counter == 1 ? 'mt-3' : ''; ?> contenedor-input">
                        <input class="form-check-input envioDomicilio" data-toggle='collapse' data-target='#collapsediv1' type="radio" aria-expanded="false" name="entrega" id="metodo_envio_<?php echo $metodo['id']; ?>" value="envio_<?php echo $metodo['id']; ?>" data-metodo-id="<?php echo $metodo['id']; ?>" data-costo="<?php echo $metodo['valor']; ?>">
                        <label class="form-check-label d-flex flex-row" for="metodo_envio_<?php echo $metodo['id']; ?>">
                            <div>
                                <p class="d-inline-block m-0"><?php echo $metodo['nombre']; ?></p>
                                <?php if($metodo['valor'] > 0){ ?>
                                <span class="d-block text-bold">$<?php echo $metodo['valor']; ?></span>
                                <?php } ?>
                                <?php if(!empty($metodo['descripcion'])){ ?>
                                <span class="d-block text-muted descripcion-pequeña"><?php echo $metodo['descripcion']; ?></span>
                                <?php } ?>
                            </div>
                        </label>
                    </div>
                    <?php } ?>

                    <!-- EPRESIS Envío (ID 2) -->
                    <div class="form-check mb-3 contenedor-input">
                        <input class="form-check-input envioDomicilio" data-toggle='collapse' data-target='#collapsediv1' type="radio" aria-expanded="false" name="entrega" id="metodo_envio_2" value="envio_2" data-metodo-id="2" data-costo="0">
                        <label class="form-check-label d-flex flex-row" for="metodo_envio_2">
                            <div>
                                <p class="d-inline-block m-0">Envío Epresis</p>
                                <span class="d-block text-bold" id="epresis_precio_display">Calcular envío</span>
                                <span class="d-block text-muted descripcion-pequeña">Entrega a domicilio</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div id='collapsediv1' class="row metodo-envio collapse in">
            <div class="col-md-12">
                <h5 class="titulo-tabs-user">Datos de Envío</h5>
            </div>
            <div class="col-md-12 d-block d-md-flex">
                <div class="shadow-sm p-5 w-100">
                    <?php if (isset($_SESSION['prontoFront']['token'])) { ?>
                    <input type="hidden" name="envio" value="domicilio">

                    <!-- Formulario Epresis - Visible solo cuando se selecciona Epresis -->
                    <div id="epresis_calc_form" style="display:none;">
                        <h5 class="mb-3">Calcular Envío Epresis</h5>
                        <div class="form-group">
                            <label for="cp_destino_epresis">Código Postal de Destino</label>
                            <input type="text" class="form-control" id="cp_destino_epresis" placeholder="Ej: 3500" value="<?php echo isset($rowc['cp']) ? $rowc['cp'] : ''; ?>">
                        </div>
                        <button type="button" class="btn btn-primary" id="calcular_envio_epresis">Calcular Envío</button>
                        <div id="epresis_result" class="mt-3" style="display:none;">
                            <div class="alert alert-success">
                                <h6>Costo de envío: $<span id="epresis_costo">0</span></h6>
                                <p class="mb-0 small">Fecha estimada: <span id="epresis_fecha">-</span></p>
                            </div>
                        </div>
                        <div id="epresis_error" class="mt-3" style="display:none;">
                            <div class="alert alert-danger">
                                <p class="mb-0" id="epresis_error_msg">Error al calcular envío</p>
                            </div>
                        </div>
                        <input type="hidden" id="epresis_costo_hidden" name="epresis_costo" value="0">
                        <input type="hidden" id="epresis_fecha_hidden" name="epresis_fecha" value="">
                        <hr class="my-4">
                    </div>

                    <div class="d-flex align-items-baseline">
                        <label class="ml-0">
                        	<span class="text-bold">Mi direccion</span>
                            <h5 class="mt-3">Direccion</h5>
                            <p class="text-muted"><?php echo $rowc['direccion'].', '.$rowc['ciudad']?></p>
                            <h5>Provincia</h5>
                            <p class="text-muted"><?php echo $rowc['provincia'];?></p>
                            <h5>CP</h5>
                            <p class="text-muted"><?php echo $rowc['cp']?></p>
                            <h5>Telefono / Celular</h5>
                            <p class="text-muted"><?php echo $rowc['telefono'];?></p>
                        </label>
                    </div>
                    <div class="ml-0">
                    	<label for="validationTextarea">Observaciones</label>
                        <textarea class="form-control" name="observaciones" placeholder="" rows="5"></textarea>
                    </div>
                    <?php }else{?>
                    <button type="button" data-toggle="modal" data-target="#iniciar-sesion" class="btn btn-outline-primary btn-bg-white ">Iniciar Sesión</button>
                    <?php }?>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 p-md-0">
                <div class="d-block d-md-flex align-items-center justify-content-center my-5">
                    <a href="mi-pedido" class="btn btn-border-yellow text-black ">Atras</a>
                    <button class="btn btn-warning btn-cargar-producto mx-0 mx-md-3 my-3 my-md-0 " type="submit">Siguiente</button>
                </div>
            </div>

        </div>
        </form>
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
                    <a href="https://es-la.facebook.com/pg/Prontophot/about/?ref=page_internal"><svg xmlns="http://www.w3.org/2000/svg" width="17.055" height="31.843"
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
    <a class="d-md-none d-lg-none d-block text-center wp" href="https://wa.me/542216976559? &amp;text=Buenos%20días,%20quiero%20mas%20info%20">
         <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path></svg>
 </a>
<a class="d-xs-none d-sm-none d-md-block text-center wp" target="_blank" href="https://wa.me/542216976559? &amp;text=Buenos%20días,%20quiero%20mas%20info">
       <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path></svg>
</a>
</footer>

<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>

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

<!--SCRIPT QUE COLAPSA LA OPCION DE TRANSFERENCIA BANCARIA-->
<script>
$("#collapsediv1").collapse({
    "toggle": false,
    'parent': '#metodo-envio'
});

$(".retiroSucursal").click(function() {
    $("#collapsediv1").collapse('hide');
})

// Cuando hacemos click verifica si tiene la clase "show" y si es asi, no colapsa nada. Caso contrario colapsa el div.
$('.contenedor-input > input[data-toggle="collapse"]').click(function(e) {
    target = $("#collapsediv1");
    if ($(target).hasClass('show')) {
        // e.preventDefault(); Esto en el caso que el link lleve a alguna seccion.
        e.stopPropagation()
    }
})
</script>

<script>
// Variable con el valor de envío gratis de Epresis desde BD
var epresisValorGratis = <?php echo floatval($epresis_valor_gratis); ?>;

// Mostrar/ocultar formulario Epresis según selección
$('input[name="entrega"]').on('change', function() {
    if($(this).val() === 'envio_2' || $(this).data('metodo-id') == 2) {
        $('#epresis_calc_form').slideDown();
    } else {
        $('#epresis_calc_form').slideUp();
        $('#epresis_result').hide();
        $('#epresis_error').hide();
    }
});

// Calcular envío Epresis
$('#calcular_envio_epresis').on('click', function() {
    var cpDestino = $('#cp_destino_epresis').val().trim();

    if(!cpDestino) {
        alert('Por favor ingrese un código postal');
        return;
    }

    // Ocultar mensajes anteriores
    $('#epresis_result').hide();
    $('#epresis_error').hide();

    // Mostrar loading
    $(this).prop('disabled', true).text('Calculando...');

    // Realizar petición al archivo PHP
    $.ajax({
        url: 'inc/metodo_envio.php',
        type: 'POST',
        data: {
            cp_destino: cpDestino
        },
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta Epresis:', response);

            if(response.success) {
                var costoCalculado = parseFloat(response.costo);
                var costoFinal = costoCalculado;
                var esGratis = false;

                // Verificar si el costo calculado es menor o igual al valor_gratis
                if(epresisValorGratis > 0 && costoCalculado <= epresisValorGratis) {
                    costoFinal = 0;
                    esGratis = true;
                }

                // Mostrar resultado
                if(esGratis) {
                    $('#epresis_costo').html('<span class="text-success">GRATIS</span> <small class="text-muted">(antes $' + costoCalculado.toFixed(2) + ')</small>');
                } else {
                    $('#epresis_costo').text(costoFinal.toFixed(2));
                }
                $('#epresis_fecha').text(response.fecha);
                $('#epresis_result').slideDown();

                // Guardar costo final en hidden input y actualizar data-costo
                $('#epresis_costo_hidden').val(costoFinal);
                $('#epresis_fecha_hidden').val(response.fecha);
                $('#metodo_envio_2').attr('data-costo', costoFinal);

                // Actualizar el precio display
                if(esGratis) {
                    $('#epresis_precio_display').html('<span class="text-success">GRATIS</span>');
                } else {
                    $('#epresis_precio_display').text('$' + costoFinal.toFixed(2));
                }
            } else {
                $('#epresis_error_msg').text(response.error || 'Error al calcular envío');
                $('#epresis_error').slideDown();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#epresis_error_msg').text('Error al calcular envío. Intente nuevamente.');
            $('#epresis_error').slideDown();
        },
        complete: function() {
            $('#calcular_envio_epresis').prop('disabled', false).text('Calcular Envío');
        }
    });
});
</script>

<script src="assets/js/starter.js"></script>
</body>

</html>