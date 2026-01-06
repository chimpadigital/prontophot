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
        <div class="col-4 bg-black py-4 px-3 d-none d-md-block">
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

        <div class="col-md-8 bg-white p-5 rounded-lg columna-content-admin">

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
                                    </div>

                                    <p class="subtitulo-form">Descripción del Producto</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustom07"></label>
                                            <textarea class="form-control"  name="descripcion" rows="6" required><?php echo trim($row['descripcion']);?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row mt-3">
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

                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom08">Stock</label>
                                            <input type="text" name="stock" class="form-control" value="<?php echo $row['stock']; ?>" id="validationCustom08">

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom09">Código de Descuento</label>
                                            <input type="text" name="descuento" class="form-control" value="<?php echo $row['descuento']; ?>" id="validationCustom09">

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom10">Precio</label>
                                            <input type="text" name="precio" class="form-control" value="<?php echo $row['precio']; ?>" id="validationCustom10" required>

                                        </div>
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
	$('#updateProducto').submit(function(e){
		e.preventDefault();
		if (!confirm("Esta seguro de que desea guardar el producto?")) 
    		{	return false; }
    	else { 
    		var data = new FormData(this);
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
	
});

function agregaImg(){
	var divs = document.getElementsByClassName("divImagen"); 
	var num = divs.length;
	var n=(num+1);
	
	var img='<div class="col-sm-6 text-center divImagen" id="imagen'+n+'">'+'<a class="text-center" href="#">'+'<img class="w-100 img-admin-producto" id="img-previa'+n+'" src="../img/placeholder.png">'+'</a>'+'<div class="btn-grupo d-flex flex-column flex-md-row">'+'<button type="button" class="btn bg-danger text-white btn-bg-red btn-archivo mr-auto" data-input="input-archivo'+n+'">Subir Foto</button>'+'<button type="button" data-parent="imagen'+n+'" class="btn btn-bg-transparent text-black btn-eliminar">Eliminar Foto</button>'+'</div><input type="file" accept="image/*" name="imagen[]" data-id="img-previa'+n+'" class="imagenFile" id="input-archivo'+n+'"></div>';
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