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
include ('../conexion/conectar.inc.php');
global $conectar;
$categorias=$conectar->query("SELECT * FROM categorias");

// Obtener el coeficiente de impuestos
$taxQuery = $conectar->query("SELECT coeficiente FROM tax WHERE id = 1");
$taxData = $taxQuery->fetch_assoc();
$taxCoeficiente = $taxData ? $taxData['coeficiente'] : 1.21;

if (isset($_GET['id'])) {
    $id=$_GET['id'];
    $prod=$conectar->query("SELECT * FROM productos WHERE id='$id'");
    $row=$prod->fetch_assoc();
    $imagenes=$conectar->query("SELECT * FROM imagenes WHERe id_producto='$id'");

}else{
    header("Location: ../");
    exit();
}


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
            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3" width="32" height="32">
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

            <!-- tab-content -->
            <div class="tab-content" id="v-pills-tabContent">
                <!-- tab-pane -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="cargarProducto" href="#">Este Producto</a>
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
							<form action="inc/update_producto.php" class="form-carga-producto" method="post" enctype="multipart/form-data" id="updateProducto">
                            <!-- CARGA IMAGENES -->
                            <div class="row mt-5">
                            	<div class="col-12 col-sm-8">
                            		<div class="row" id="listaImagenes">
                            		<?php
                            		$i=1;
                            		while ($rowi=$imagenes->fetch_assoc()) { ?>
                            			<div class="col-sm-6 text-center divImagen" id="imagen<?php echo $i;?>">
                                            <a class="text-center" href="#">
                                            	<img class="w-100 img-admin-producto" id="img-previa<?php echo $i;?>" src="../<?php echo $rowi['imagen'];?>">
                                            </a>

                                            <div class="btn-grupo d-flex flex-column flex-md-row">
                                                <button type="button" data-id="<?php echo $rowi['id'];?>" data-parent="imagen<?php echo $i;?>" class="btn btn-bg-transparent ml-auto text-black btn-removerimg">Eliminar Foto</button></a>
                                            </div>

                                            <div class="form-group mt-2 d-flex align-items-center">
                                                <label style="margin-bottom: 0px!important; margin-right: 10px;" class=" d-flex" for="color-imagen-existente-<?php echo $rowi['id'];?>">Color (opcional)</label>

                                                <input type="color"
                                                name="color_imagen_existente[<?php echo $rowi['id'];?>]"
                                                class="form-control color-input"
                                                id="color-imagen-existente-<?php echo $rowi['id'];?>"
                                                value="<?php echo !empty($rowi['color']) ? $rowi['color'] : '#000000'; ?>"
                                                style="padding: 0px 2px; height: 39px; width: 39px;"
                                                <?php echo empty($rowi['color']) ? 'disabled' : ''; ?>>
                                                <div class="form-check ml-2">
                                                    <input class="form-check-input sin-color-check" type="checkbox" id="sin-color-existente-<?php echo $rowi['id'];?>" <?php echo empty($rowi['color']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="sin-color-existente-<?php echo $rowi['id'];?>">Sin color</label>
                                                </div>
                                            </div>

                                        </div>
                                    <?php $i++; }?>        
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
                                                <input class="form-check-input" type="radio" name="video_tipo" id="videoTipoArchivo" value="archivo" <?php echo (!empty($row['video']) && strpos($row['video'], 'http') === false) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="videoTipoArchivo">Subir Archivo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="video_tipo" id="videoTipoUrl" value="url" <?php echo (!empty($row['video']) && strpos($row['video'], 'http') !== false) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="videoTipoUrl">URL</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="video_tipo" id="videoTipoNinguno" value="ninguno" <?php echo empty($row['video']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="videoTipoNinguno">Sin Video</label>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (!empty($row['video'])): ?>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <p><strong>Video actual:</strong> <?php echo basename($row['video']); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="form-row" id="videoArchivoContainer" style="display:<?php echo (!empty($row['video']) && strpos($row['video'], 'http') === false) ? 'block' : 'none'; ?>;">
                                        <div class="col-md-12 mb-3">
                                            <label for="videoArchivo">Archivo de Video</label>
                                            <input type="file" accept="video/*" name="video_archivo" class="form-control-file" id="videoArchivo">
                                            <small class="form-text text-muted">Dejar vacío para mantener el video actual</small>
                                        </div>
                                    </div>

                                    <div class="form-row" id="videoUrlContainer" style="display:<?php echo (!empty($row['video']) && strpos($row['video'], 'http') !== false) ? 'block' : 'none'; ?>;">
                                        <div class="col-md-12 mb-3">
                                            <label for="videoUrl">URL del Video</label>
                                            <input type="text" name="video_url" class="form-control" id="videoUrl" placeholder="https://ejemplo.com/video.mp4" value="<?php echo (strpos($row['video'], 'http') !== false) ? $row['video'] : ''; ?>">
                                        </div>
                                    </div>
                            <!-- FORM CARGA PRODUCTO -->
                            <div class="row">
                            	<div class="col-12 w-100 mt-5">
                                    <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">Nombre del Producto</label>
                                            <input type="text" name="nombre" class="form-control" id="validationCustom01" value="<?php echo $row['nombre'];?>"
                                                required>
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Categoría</label>
                                            <select class="custom-select" name="categoria" id="validationCustom04" required>
                                                <option selected disabled value="">Elige...</option>
                                                <?php while($rowc=$categorias->fetch_assoc()){?>
                                                <option <?php if($rowc['id']==$row['id_categoria']){echo ' selected ';}?> value="<?php echo $rowc['id']; ?>"><?php echo $rowc['nombre']; ?></option>
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
                                            <input type="text" class="form-control" name="codigo" id="validationCustom03" value="<?php echo $row['codigo'];?>">
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Dimensiones</p>

                                    <div class="form-row align-items-center">
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom04">Ancho</label>
                                            <input type="text" name="ancho" value="<?php echo $row['ancho'];?>" class="form-control" id="validationCustom04">
                                        </div>
                                        <div class="col-md-2 mb-3 mx-0 mx-md-5">
                                            <label for="validationCustom05">Alto</label>
                                            <input type="text" name="alto" value="<?php echo $row['alto'];?>" class="form-control" id="validationCustom05">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom06">Profundidad</label>
                                            <input type="text" name="profundidad" value="<?php echo $row['profundidad'];?>" class="form-control" id="validationCustom06">
                                        </div>
                                        <div class="col-md-2 mb-3 mx-0 mx-md-5">
                                            <label for="validationCustom07">Peso</label>
                                            <input type="number" step="0.01" name="peso" value="<?php echo $row['peso'];?>" class="form-control" id="validationCustom07" placeholder="kg">
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Descripción del Producto</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustom07"></label>
                                            <textarea class="form-control"  name="descripcion" rows="6" required><?php echo trim($row['descripcion']);?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row mt-3 d-none">
                                        <label for="validationCustom07">Colores Disponibles</label>
                                        <div
                                            class="col-md-12 d-flex flex-row flex-wrap flex-lg-nowrap mt-2 justify-content-lg-between">                                            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_rojo']=='1'){echo ' checked ';}?> type="checkbox" name="color_rojo"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Rojo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_naranja']=='1'){echo ' checked ';}?>type="checkbox" name="color_naranja"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Naranja</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_azul']=='1'){echo ' checked ';}?>type="checkbox" name="color_azul"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Azul</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_celeste']=='1'){echo ' checked ';}?>type="checkbox" name="color_celeste"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Celeste</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_violeta']=='1'){echo ' checked ';}?>type="checkbox" name="color_violeta"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Violeta</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_verde']=='1'){echo ' checked ';}?>type="checkbox" name="color_verde"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Verde</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" <?php if($row['color_amarillo']=='1'){echo ' checked ';}?>type="checkbox" name="color_amarillo"
                                                    id="" value="1">
                                                <label class="form-check-label" for="">Amarillo</label>                                                
                                            </div>

                                        </div>

                                        

                                    </div>

                                    <div class="form-row mt-5">

                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom08">Stock</label>
                                            <input type="text" name="stock" class="form-control" value="<?php echo $row['stock']; ?>" id="validationCustom08">

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="validationCustom09">Código de Descuento</label>
                                            <input type="text" name="codigo_descuento" class="form-control" value="<?php echo $row['descuento']; ?>" id="validationCustom09">

                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Precios</p>

                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom10">Precio de Lista</label>
                                            <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $row['precio']; ?>" id="validationCustom10" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="descuentoPorcentaje">Descuento (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="descuento_final" class="form-control" id="descuentoPorcentaje" value="<?php echo isset($row['descuento_final']) ? $row['descuento_final'] : 0; ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="precioFinal">Precio Final</label>
                                            <?php
                                                $precioLista = $row['precio'];
                                                $descuento_porcentaje = isset($row['descuento_final']) ? $row['descuento_final'] : 0;
                                                $precioFinal = $precioLista - ($precioLista * $descuento_porcentaje / 100);
                                            ?>
                                            <input type="text" class="form-control" id="precioFinal" value="<?php echo number_format($precioFinal, 2); ?>" readonly style="background-color: #e9ecef;">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <small class="form-text text-muted">
                                                Precio final con impuestos nacionales: <strong>$<span id="precioConImpuesto"><?php echo number_format($precioFinal * $taxCoeficiente, 2); ?></span></strong>
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
                                        <?php
                                        // Cargar cuotas existentes
                                        $cuotasExistentes = $conectar->query("SELECT * FROM cuotas WHERE producto_id = '$id'");
                                        while($cuotaRow = $cuotasExistentes->fetch_assoc()){
                                            $precioPorCuota = ($cuotaRow['precio'] * (1 + ($cuotaRow['interes'] / 100))) / $cuotaRow['cantidad'];
                                        ?>
                                        <div class="card mb-2" data-cuota-id="<?php echo $cuotaRow['id']; ?>">
                                            <div class="card-body p-2">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <strong><?php echo $cuotaRow['cantidad']; ?> cuotas</strong> de $<?php echo number_format($precioPorCuota, 2); ?>
                                                        (<?php echo $cuotaRow['sin_interes'] ? 'Sin interés' : $cuotaRow['interes'] . '% de interés'; ?>)
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-sm btn-danger btn-eliminar-cuota-existente" data-id="<?php echo $cuotaRow['id']; ?>">Eliminar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    

                                    <div class="d-flex my-3 justify-content-center">
                                    	<input type="hidden" name="id" value="<?php echo $row['id'];?>">
                                    	<button type="button" class="btn btn-danger btn-remover" data-id="<?php echo $row['id'];?>">Eliminar Producto</button>
                                        <button form="updateProducto" class="btn btn-warning btn-cargar-producto" type="submit">Actualizar Producto</button>
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

	// Inicializar estado de inputs de color existentes al cargar
	$('.sin-color-check:checked').each(function() {
		var colorInput = $(this).closest('.form-group').find('.color-input');
		colorInput.css('opacity', '0.5');
	});

	$('#updateProducto').submit(function(e){
		e.preventDefault();
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
    				if(data.success){
						alert('Producto actualizado');
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
		$(this).parent().parent().remove();
	});

	$(document).on('click', '.btn-removerimg', function(e) {
		e.preventDefault();
		if (!confirm("Esta seguro de que desea eliminar esta imagen?")) 
    		{	return false; }
    	else { 
    		var div=$(this).data('parent');
    		var id=$(this).data('id');
    		
    		$.post('inc/borrar_item.php',{id:id,tabla:'imagenes'},function(data){
    			if(data.success){
    				location.reload();
    			}
    				
    		},'json');
    	}
	});
	$(document).on('click', '.btn-remover', function(e) {
		e.preventDefault();
		if (!confirm("Esta seguro de que desea eliminar este producto?")) 
    		{	return false; }
    	else { 
    		var id=$(this).data('id');
    		$.post('inc/borrar_item.php',{id:id,tabla:'productos'},function(data){
    			if(data.success){
    				window.history.back();
    			}
    		},'json');
    	}
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
			$('#videoArchivoContainer').show();
			$('#videoUrlContainer').hide();
			$('#videoUrl').val('');
		} else if($(this).val() === 'url'){
			$('#videoArchivoContainer').hide();
			$('#videoUrlContainer').show();
			$('#videoArchivo').val('');
		} else {
			$('#videoArchivoContainer').hide();
			$('#videoUrlContainer').hide();
			$('#videoArchivo').val('');
			$('#videoUrl').val('');
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
		renderizarCuotasNuevas();
		$('#modalCuota').modal('hide');
	});

	$(document).on('click', '.btn-eliminar-cuota', function(){
		var index = $(this).data('index');
		cuotasArray = cuotasArray.filter(function(c){ return c.index !== index; });
		renderizarCuotasNuevas();
	});

	$(document).on('click', '.btn-eliminar-cuota-existente', function(){
		if (!confirm("¿Está seguro de que desea eliminar esta cuota?")) {
			return false;
		}
		var id = $(this).data('id');
		var card = $(this).closest('.card');

		$.post('inc/borrar_item.php', {id: id, tabla: 'cuotas'}, function(data){
			if(data.success){
				card.fadeOut(function(){ $(this).remove(); });
			}
		}, 'json');
	});

	$(document).on('click', '.btn-ver-detalle-cuota', function(){
		var index = $(this).data('index');
		var cuota = cuotasArray.find(function(c){ return c.index === index; });
		if(cuota){
			mostrarDetalleCuota(cuota);
		}
	});

	function renderizarCuotasNuevas(){
		$('.cuota-nueva').remove();
		var html = '';
		cuotasArray.forEach(function(cuota){
			html += '<div class="card mb-2 cuota-nueva">';
			html += '<div class="card-body p-2">';
			html += '<div class="row align-items-center">';
			html += '<div class="col-md-8">';
			html += '<strong>' + cuota.cantidad + ' cuotas</strong> de $' + cuota.precio_por_cuota;
			html += ' (' + (cuota.sin_interes ? 'Sin interés' : cuota.interes + '% de interés') + ')';
			html += '<input type="hidden" name="cuotas_nuevas[' + cuota.index + '][cantidad]" value="' + cuota.cantidad + '">';
			html += '<input type="hidden" name="cuotas_nuevas[' + cuota.index + '][precio]" value="' + cuota.precio + '">';
			html += '<input type="hidden" name="cuotas_nuevas[' + cuota.index + '][interes]" value="' + cuota.interes + '">';
			html += '<input type="hidden" name="cuotas_nuevas[' + cuota.index + '][sin_interes]" value="' + cuota.sin_interes + '">';
			html += '</div>';
			html += '<div class="col-md-4 text-right">';
			html += '<button type="button" class="btn btn-sm btn-primary btn-ver-detalle-cuota" data-index="' + cuota.index + '">Ver Detalle</button> ';
			html += '<button type="button" class="btn btn-sm btn-danger btn-eliminar-cuota" data-index="' + cuota.index + '">Eliminar</button>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		});
		$('#listaCuotas').append(html);
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

});

function agregaImg(){
	var divs = document.getElementsByClassName("divImagen");
	var num = divs.length;
	var n=(num+1);

	var img='<div class="col-sm-6 text-center divImagen" id="imagen'+n+'">'+'<a class="text-center" href="#">'+'<img class="w-100 img-admin-producto" id="img-previa'+n+'" src="../img/placeholder.png"></a>'+'<div class="btn-grupo d-flex flex-column flex-md-row"><button type="button" class="btn bg-danger text-white btn-bg-red btn-archivo mr-auto" data-input="input-archivo'+n+'">Subir Foto</button>'+'<button type="button" data-parent="imagen'+n+'" class="btn btn-bg-transparent text-black btn-eliminar">Eliminar Foto</button>'+'</div><input type="file" accept="image/*" name="imagen[]" data-id="img-previa'+n+'" class="imagenFile" id="input-archivo'+n+'">'+'<div class="form-group d-flex align-items-center">'+'<label style="margin-bottom: 0px; margin-right: 10px; " for="color-imagen-'+n+'">Color (opcional)</label>'+'<input type="color" name="color_imagen[]" class="form-control color-input" id="color-imagen-'+n+'" value="#000000" style="padding: 0px 2px; height: 39px; width: 39px;">'+'<div class="form-check ml-2">'+'<input class="form-check-input sin-color-check" type="checkbox" id="sin-color-'+n+'">'+'<label class="form-check-label" for="sin-color-'+n+'">Sin color</label>'+'</div>'+'</div>'+'<div class="btn-grupo d-flex flex-column flex-md-row"></div>';
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