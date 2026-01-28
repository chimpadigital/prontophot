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
$categorias=$conectar->query("SELECT * FROM categorias");

// Obtener el coeficiente de impuestos
$taxQuery = $conectar->query("SELECT coeficiente FROM tax WHERE id = 1");
$taxData = $taxQuery->fetch_assoc();
$taxCoeficiente = $taxData ? $taxData['coeficiente'] : 1.21;
?>
<style>
.imagenFile { visibility: hidden; }
#listaImagenes .btn{
    width:max-content;
    padding: 15px 10px;
    font-size:0.9rem;
}
</style>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="\"><img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt=""></a>
            </div>
            <hr class="solid my-4">
            <!-- SIDEBAR ADMIN  -->
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

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">

            <!-- tab-content -->
            <div class="tab-content" id="v-pills-tabContent">
                <!-- tab-pane -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="productosCargados" href="productos_cargados.php">Productos ya
                                Cargados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link" id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="metodosEnvio" href="productos_metodos_envio.php">Métodos de Envío</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="cargarProductoNuevo" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs">Información del Producto</h5>
							<form action="inc/guardar_producto.php" class="form-carga-producto" method="post" enctype="multipart/form-data" id="nuevoProducto">
                            <!-- CARGA IMAGENES -->
                            <div class="row mt-5">
                            	<div class="col-12 col-sm-8">
                            		<div class="row" id="listaImagenes">
                            		
                            			<div class="col-sm-6 text-center divImagen" id="imagen1">
                                            <a class="text-center" href="#">
                                            	<img class="w-100 img-admin-producto" id="img-previa1" src="../img/placeholder.png">
                                            </a>
                                            
                                            

                                            <div class="btn-grupo d-flex flex-column flex-md-row">
                                                <button type="button" class="btn bg-danger text-white btn-bg-red btn-archivo mr-auto" data-input="input-archivo1">Subir Foto</button>
                                                <button type="button" data-parent="imagen1" class="btn btn-bg-transparent text-black btn-eliminar">Eliminar Foto</button></a>
                                            </div>

                                            <div class="form-group mt-2 d-flex align-items-center">
                                                <label style="margin-right: 10px; margin-bottom: 0px;" for="color-imagen-1">Color (opcional)</label>
                                                <input type="color" name="color_imagen[]" class="form-control color-input" id="color-imagen-1" value="#000000" style="padding: 0px 2px; height: 39px; width: 39px;">
                                                <div class="form-check ml-2">
                                                    <input class="form-check-input sin-color-check" type="checkbox" id="sin-color-1">
                                                    <label class="form-check-label" for="sin-color-1">Sin color</label>
                                                </div>
                                            </div>

                                        	<input type="file" name="imagen[]" accept="image/*" data-id="img-previa1" class="imagenFile" id="input-archivo1">
                                        </div>
                                        
                                            
                            		</div>
                            	</div>
                            	<div class="col-12 col-sm-4 d-flex align-items-center justify-content-center justify-content-lg-start mt-3 mt-md-0">
                            		<a href="#" class="agregaImagen">
                                        <svg class="text-danger ml-0 ml-md-5" xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" /> <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <!-- FIN CARGA IMAGENES -->

                             <p class="subtitulo-form">Video del Producto</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="video_tipo" id="videoTipoArchivoNuevo" value="archivo" checked>
                                                <label class="form-check-label" for="videoTipoArchivoNuevo">Subir Archivo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="video_tipo" id="videoTipoUrlNuevo" value="url">
                                                <label class="form-check-label" for="videoTipoUrlNuevo">URL</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row" id="videoArchivoContainerNuevo">
                                        <div class="col-md-12 mb-3">
                                            <label for="videoArchivoNuevo">Archivo de Video</label>
                                            <input type="file" accept="video/*" name="video_archivo" class="form-control-file" id="videoArchivoNuevo">
                                        </div>
                                    </div>

                                    <div class="form-row" id="videoUrlContainerNuevo" style="display:none;">
                                        <div class="col-md-12 mb-3">
                                            <label for="videoUrlNuevo">URL del Video</label>
                                            <input type="text" name="video_url" class="form-control" id="videoUrlNuevo" placeholder="https://ejemplo.com/video.mp4">
                                        </div>
                                    </div>
                                    
                            <!-- FORM CARGA PRODUCTO -->
                            <div class="row">
                            	<div class="col-12 w-100 mt-5">
                                    <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">Nombre del Producto</label>
                                            <input type="text" name="nombre" class="form-control" id="validationCustom01" value=""
                                                required>
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Categoría</label>
                                            <select class="custom-select" name="categoria" id="validationCustom04" required>
                                                <option selected disabled value="">Elige...</option>
                                                <?php while($row=$categorias->fetch_assoc()){?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Seleccione una categoria
                                            </div>
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom03">Código</label>
                                            <input type="text" class="form-control" name="codigo" id="validationCustom03" value="">
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Dimensiones</p>

                                    <div class="form-row align-items-center">
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom04">Ancho</label>
                                            <input type="text" name="ancho" class="form-control" id="validationCustom04">
                                        </div>
                                        <div class="col-md-2 mb-3 mx-0 mx-md-5">
                                            <label for="validationCustom05">Alto</label>
                                            <input type="text" name="alto" class="form-control" id="validationCustom05">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom06">Profundidad</label>
                                            <input type="text" name="profundidad" class="form-control" id="validationCustom06">
                                        </div>
                                        <div class="col-md-2 mb-3 mx-0 mx-md-5">
                                            <label for="validationCustom07">Peso</label>
                                            <input type="number" step="0.01" name="peso" class="form-control" id="validationCustom07" placeholder="kg">
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Descripción del Producto</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustom07"></label>
                                            <textarea class="form-control" name="descripcion" id="validationTextarea" rows="6" placeholder="" required></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row mt-3 d-none">
                                        <label for="validationCustom07">Colores Disponibles</label>
                                        <div
                                            class="col-md-12 d-flex flex-row flex-wrap flex-lg-nowrap mt-2 justify-content-lg-between">                                            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_rojo"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Rojo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_naranja"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Naranja</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_azul"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Azul</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_celeste"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Celeste</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_violeta"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Violeta</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_verde"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Verde</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="color_amarillo"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Amarillo</label>                                                
                                            </div>

                                        </div>

                                        

                                    </div>

                                    <div class="form-row mt-5">

                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom08">Stock</label>
                                            <input type="text" name="stock" class="form-control" id="validationCustom08">

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom09">Código de Descuento</label>
                                            <input type="text" name="codigo_descuento" class="form-control" id="validationCustom09">

                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Precios</p>

                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom10">Precio de Lista</label>
                                            <input type="number" step="0.01" name="precio" class="form-control" id="validationCustom10" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="descuentoPorcentaje">Descuento (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="descuento_final" class="form-control" id="descuentoPorcentaje" value="0">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="precioFinal">Precio Final</label>
                                            <input type="text" class="form-control" id="precioFinal" value="0.00" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <small class="form-text text-muted">
                                                Precio final con impuestos nacionales: <strong>$<span id="precioConImpuesto">0.00</span></strong>
                                            </small>
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Cuotas Disponibles (Opcional)</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <button type="button" class="btn btn-sm btn-info" id="btnAgregarCuota">
                                                <i class="fa fa-plus"></i> Agregar Cuota
                                            </button>
                                        </div>
                                    </div>

                                    <div id="listaCuotas" class="mb-3">
                                        <!-- Aquí se agregarán las cuotas dinámicamente -->
                                    </div>

                                   

                                    <div class="d-flex my-3 justify-content-center">
                                        <button form="nuevoProducto" class="btn btn-warning btn-cargar-producto" type="submit">Publicar Producto</button>
                                    </div>
								</div>
                            </div>
                            </form>
                        </div>
                        <!-- FIN FORM CARGA PRODUCTO -->
					</div>
                    <!-- FIN CONTENIDO DE LAS TABS -->

                </div>
                <!-- fin tab-pane -->
            </div>
            <!-- fin tab-content -->
        </div>
    </div>
</div>        
<script>
$(function(){
	var taxCoeficiente = <?php echo $taxCoeficiente; ?>;

	// Función para calcular precios
	function calcularPrecios(){
		var precioLista = parseFloat($('#validationCustom10').val()) || 0;
		var descuento = parseFloat($('#descuentoPorcentaje').val()) || 0;

		// Calcular precio final: precio - (precio * descuento / 100)
		var precioFinal = precioLista - (precioLista * descuento / 100);
		$('#precioFinal').val(precioFinal.toFixed(2));

		// Calcular precio con impuestos sobre el precio final
		var precioConImpuesto = (precioFinal / taxCoeficiente).toFixed(2);
		$('#precioConImpuesto').text(precioConImpuesto);
	}

	// Calcular cuando cambia el precio de lista o el descuento
	$('#validationCustom10, #descuentoPorcentaje').on('input', function(){
		calcularPrecios();
	});

	// Manejar checkbox "sin color"
	$(document).on('change', '.sin-color-check', function() {
		var colorInput = $(this).closest('.form-group').find('.color-input');
		if ($(this).is(':checked')) {
			colorInput.prop('disabled', true);
			colorInput.css('opacity', '0.5');
		} else {
			colorInput.prop('disabled', false);
			colorInput.css('opacity', '1');
		}
	});

	$('#nuevoProducto').submit(function(e){
		e.preventDefault();
		$('.btn-cargar-producto').prop('disabled',true);
		$('.btn-cargar-producto').html('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>');
		if (!confirm("Esta seguro de que desea guardar el producto?"))
    		{	return false; }
    	else {
    		var data = new FormData(this);

    		// Reemplazar valores de color con null si "sin color" está marcado
    		$('.sin-color-check:checked').each(function() {
    			var colorInput = $(this).closest('.form-group').find('.color-input');
    			var name = colorInput.attr('name');
    			data.delete(name);
    			data.append(name, '');
    		});

    		$.ajax({
    			type: 'POST',
    			url: $(this).attr('action'),
    			data: data,
    			contentType: false,
    			dataType: "json",
    			cache: false,
    			processData: false,
    			success: function(data){
    				$('.btn-cargar-producto').prop('disabled',false);
    				$('.btn-cargar-producto').html('Publicar Producto');
    				if(data.success){
						alert('Producto agregado');
						location.reload();
        			}

    			}
    		});
    	}
	});

	$('.agregaImagen').click(function(e){
		e.preventDefault();
		
		agregaImg();
	});

	$(document).on('click', '.btn-eliminar', function(e) {
		e.preventDefault();
		var id=$(this).data('parent');
		$('#'+id).remove();
	});
	
	$(document).on('click', '.btn-archivo', function(e) {
		e.preventDefault();
		var id=$(this).data('input');
		$('#'+id).click();
	});
	$(document).on('change', '.imagenFile', function(e) {
		var id=$(this).data('id');
		var tmppath = URL.createObjectURL(event.target.files[0]);
		$('#'+id).attr('src',tmppath);
	});

	$('input[name="video_tipo"]').change(function(){
		if($(this).val() === 'archivo'){
			$('#videoArchivoContainerNuevo').show();
			$('#videoUrlContainerNuevo').hide();
			$('#videoUrlNuevo').val('');
		} else {
			$('#videoArchivoContainerNuevo').hide();
			$('#videoUrlContainerNuevo').show();
			$('#videoArchivoNuevo').val('');
		}
	});

	// Manejo de cuotas
	var cuotasArray = [];
	var cuotaIndex = 0;

	$('#btnAgregarCuota').click(function(){
		$('#modalCuota').modal('show');
		$('#formCuota')[0].reset();
		$('#cuotaPrecio').val($('#precioFinal').val());
	});

	$('#formCuota').submit(function(e){
		e.preventDefault();
		var cantidad = $('#cuotaCantidad').val();
		var precio = parseFloat($('#cuotaPrecio').val()) || 0;
		var interes = parseFloat($('#cuotaInteres').val()) || 0;
		var sinInteres = interes === 0 ? 1 : 0;

		// Calcular precio por cuota con interés
		var precioConInteres = precio * (1 + (interes / 100));
		var precioPorCuota = precioConInteres / cantidad;

		var cuota = {
			index: cuotaIndex++,
			cantidad: cantidad,
			precio: precio,
			interes: interes,
			sin_interes: sinInteres,
			precio_por_cuota: precioPorCuota.toFixed(2)
		};

		cuotasArray.push(cuota);
		renderizarCuotas();
		$('#modalCuota').modal('hide');
	});

	$(document).on('click', '.btn-eliminar-cuota', function(){
		var index = $(this).data('index');
		cuotasArray = cuotasArray.filter(function(c){ return c.index !== index; });
		renderizarCuotas();
	});

	$(document).on('click', '.btn-ver-detalle-cuota', function(){
		var index = $(this).data('index');
		var cuota = cuotasArray.find(function(c){ return c.index === index; });
		if(cuota){
			mostrarDetalleCuota(cuota);
		}
	});

	function renderizarCuotas(){
		var html = '';
		cuotasArray.forEach(function(cuota){
			html += '<div class="card mb-2">';
			html += '<div class="card-body p-2">';
			html += '<div class="row align-items-center">';
			html += '<div class="col-md-8">';
			html += '<strong>' + cuota.cantidad + ' cuotas</strong> de $' + cuota.precio_por_cuota;
			html += ' (' + (cuota.sin_interes ? 'Sin interés' : cuota.interes + '% de interés') + ')';
			html += '<input type="hidden" name="cuotas[' + cuota.index + '][cantidad]" value="' + cuota.cantidad + '">';
			html += '<input type="hidden" name="cuotas[' + cuota.index + '][precio]" value="' + cuota.precio + '">';
			html += '<input type="hidden" name="cuotas[' + cuota.index + '][interes]" value="' + cuota.interes + '">';
			html += '<input type="hidden" name="cuotas[' + cuota.index + '][sin_interes]" value="' + cuota.sin_interes + '">';
			html += '</div>';
			html += '<div class="col-md-4 text-right">';
			html += '<button type="button" class="btn btn-sm btn-primary btn-ver-detalle-cuota" data-index="' + cuota.index + '">Ver Detalle</button> ';
			html += '<button type="button" class="btn btn-sm btn-danger btn-eliminar-cuota" data-index="' + cuota.index + '">Eliminar</button>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		});
		$('#listaCuotas').html(html);
	}

	function mostrarDetalleCuota(cuota){
		var precioTotal = cuota.precio * (1 + (cuota.interes / 100));
		var precioPorCuota = precioTotal / cuota.cantidad;

		var html = '<table class="table table-sm">';
		html += '<thead><tr><th>Cuota</th><th>Precio</th><th>Interés</th></tr></thead>';
		html += '<tbody>';
		for(var i = 1; i <= cuota.cantidad; i++){
			html += '<tr>';
			html += '<td>Cuota ' + i + '</td>';
			html += '<td>$' + precioPorCuota.toFixed(2) + '</td>';
			html += '<td>' + (cuota.sin_interes ? 'Sin interés' : cuota.interes + '%') + '</td>';
			html += '</tr>';
		}
		html += '<tr class="font-weight-bold">';
		html += '<td>Total</td>';
		html += '<td>$' + precioTotal.toFixed(2) + '</td>';
		html += '<td></td>';
		html += '</tr>';
		html += '</tbody></table>';

		$('#detalleCuotaContent').html(html);
		$('#modalDetalleCuota').modal('show');
	}

	agregaImg();
});

function agregaImg(){
	var divs = document.getElementsByClassName("divImagen");
	var num = divs.length;
	var n=(num+1);

	var img='<div class="col-sm-6 text-center divImagen" id="imagen'+n+'">'+'<a class="text-center" href="#">'+'<img class="w-100 img-admin-producto" id="img-previa'+n+'" src="../img/placeholder.png">'+'</a>'+'<div class="btn-grupo d-flex flex-column flex-md-row">'+'<button type="button" class="btn bg-danger text-white btn-bg-red btn-archivo mr-auto" data-input="input-archivo'+n+'">Subir Foto</button>'+'<button type="button" data-parent="imagen'+n+'" class="btn btn-bg-transparent text-black btn-eliminar">Eliminar Foto</button>'+'</div>'+'<div class="form-group mt-2 align-items-center d-flex">'+'<label style="margin-bottom: 0px; margin-right: 10px;" for="color-imagen-'+n+'">Color (opcional)</label>'+'<input type="color" name="color_imagen[]" class="form-control color-input" id="color-imagen-'+n+'" value="#000000" style="padding: 0px 2px; height: 39px; width: 39px;">'+'<div class="form-check ml-2">'+'<input class="form-check-input sin-color-check" type="checkbox" id="sin-color-'+n+'">'+'<label class="form-check-label" for="sin-color-'+n+'">Sin color</label>'+'</div>'+'</div><input type="file" accept="image/*" name="imagen[]" data-id="img-previa'+n+'" class="imagenFile" id="input-archivo'+n+'"></div>';
	$('#listaImagenes').append(img);
}
                                // Example starter JavaScript for disabling form submissions if there are invalid fields
                                (function() {
                                    'use strict';
                                    window.addEventListener('load', function() {
                                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                        var forms = document.getElementsByClassName('needs-validation');
                                        // Loop over them and prevent submission
                                        var validation = Array.prototype.filter.call(forms, function(form) {
                                            form.addEventListener('submit', function(event) {
                                                if (form.checkValidity() === false) {
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                }
                                                form.classList.add('was-validated');
                                            }, false);
                                        });
                                    }, false);
                                })();
</script>

<!-- Modal para agregar cuota -->
<div class="modal fade" id="modalCuota" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Cuota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCuota">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cuotaCantidad">Cantidad de Cuotas</label>
                        <input type="number" class="form-control" id="cuotaCantidad" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="cuotaPrecio">Precio Base (Precio Final)</label>
                        <input type="number" step="0.01" class="form-control" id="cuotaPrecio" readonly>
                    </div>
                    <div class="form-group">
                        <label for="cuotaInteres">Interés (%)</label>
                        <input type="number" step="0.01" class="form-control" id="cuotaInteres" value="0" min="0">
                        <small class="form-text text-muted">Dejar en 0 para cuotas sin interés</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalle de cuota -->
<div class="modal fade" id="modalDetalleCuota" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Cuotas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleCuotaContent">
                <!-- El contenido se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>        