<?php include ('header.php'); ?>
<?php 

?>
<script type="text/javascript">
$(function(){
	cargarCategorias();
	$('#NuevaCategoria').click(function(e){
		e.preventDefault();
		if (!confirm("Esta seguro de que desea guardar la categoria?")) 
    		{	return false; }
    	else { 
    		var cat=$('#CrearCategoria').val();
    		var id=$('#idCategoria').val();
    		$.post('inc/guardar_categoria.php',{id:id,cat:cat},function(data){
    			if(data.success){
    				cargarCategorias();
    				alert(data.msg);
    				
    			}
    			$('#idCategoria').val('');
    			$('#CrearCategoria').val('');
    			$('#NuevaCategoria').html('Crear <span class="d-none d-md-inline-block">nueva categoría</span>');
    		},'json');
    	}
	});
	$(document).on('click', '.editarCategoria', function(e) {
		var id=$(this).data('id');
		var nombre=$(this).data('nombre');
		$('#idCategoria').val(id);
		$('#CrearCategoria').val(nombre).focus();
		$('#NuevaCategoria').html('Actualizar <span class="d-none d-md-inline-block">categoría</span>');
	});
	$(document).on('click', '.btn-eliminar', function(e) {
		e.preventDefault();
		if (!confirm("Esta seguro de que desea eliminar la categoria?")) 
			{	return false; }
		else { 
			var id=$(this).data('id');
			var tabla=$(this).data('tabla');
			$.post('inc/borrar_item.php',{id:id,tabla:tabla},function(data){
				if(data.success){
					alert('Elemento Eliminado');
					location.reload();
				}
			},'json');
		}
	});
	function cargarCategorias(){
		$.post('inc/cargar_categorias.php',function(data){
			if(data.success){
				$('#listaCategorias').empty();
				$('#listaCategorias').html(data.result);
			}
		},'json');
	}
});
</script>
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
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>    
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">

            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="productosCargados" href="productos_cargados.php">Productos ya
                                Cargados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link" id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->
                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <!-- TAB CATEGORIAS -->
                        <div class="tab-pane fade show active" id="categorias" role="tabpanel"
                            aria-labelledby="contact-tab">
                            <h5 class="titulo-tabs">Crear nueva Categoría</h5>
                            <div class="row">
                                <form id="formCrearCategoria" class="needs-validation w-100 mt-5 form-carga-producto"
                                    novalidate>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3 d-flex align-items-center">
                                            <label class="sr-only" for="CrearCategoria"></label>
                                            <input type="hidden" id="idCategoria">
                                            <input type="text" class="form-control mr-sm-2 h-100" id="CrearCategoria" placeholder="Nombre de la Categoría">
                                            <button type="button" id="NuevaCategoria" class="btn bg-danger text-white btn-bg-red btn-x-large">
                                            	Crear <span class="d-none d-md-inline-block">nueva categoría</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="row mt-5">
                                <h5 class="titulo-tabs">Lista de Categorías</h5>
                            </div>

                            <div class="row mt-3" id="listaCategorias">
                                
                            </div>

                        </div>
                        <!-- FIN CATEGORIAS -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->
                </div>
            </div>
        </div>
	</div>
</div>
        <?php include ('footer.php'); ?>