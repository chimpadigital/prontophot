<?php include ('header.php'); ?>
<?php
include ('../conexion/conectar.inc.php');
global $conectar;
$query="SELECT * FROM sliders";
$sliders=$conectar->query($query);

// Obtener topbar
$queryTopbar="SELECT * FROM topbar WHERE id=1 LIMIT 1";
$resultTopbar=$conectar->query($queryTopbar);
$topbar=$resultTopbar ? $resultTopbar->fetch_assoc() : null;

// Determinar qué tab está activa
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'sliders';
?>
<script>
	$(function(){
		$('.btn-eliminar').click(function(e){
			e.preventDefault();
			if (!confirm("Esta seguro de que desea eliminar el registro?"))
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

		// Manejo del formulario de topbar
		$('#formTopbar').submit(function(e){
			e.preventDefault();
			var formData = {
				texto: $('#topbarTexto').val(),
				link: $('#topbarLink').val(),
				activo: $('#topbarActivo').is(':checked') ? 1 : 0
			};

			$.ajax({
				type: 'POST',
				url: 'inc/guardar_topbar.php',
				data: formData,
				dataType: 'json',
				success: function(data){
					if(data.success){
						$('#topbarRespuesta').html('<div class="alert alert-success">' + data.msg + '</div>');
					} else {
						$('#topbarRespuesta').html('<div class="alert alert-danger">' + data.msg + '</div>');
					}
					setTimeout(function(){
						$('#topbarRespuesta').html('');
					}, 4000);
				},
				error: function(){
					$('#topbarRespuesta').html('<div class="alert alert-danger">Error al guardar el Top Bar</div>');
					setTimeout(function(){
						$('#topbarRespuesta').html('');
					}, 4000);
				}
			});
		});
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
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link active" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>    
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin min-vh-100">

            <div class="tab-content" id="v-pills-tabContent">

                <div class="tab-pane fade show active" id="v-pills-messages" role="tabpanel"
                    aria-labelledby="v-pills-messages-tab">

                    <!-- TABS CUPONES -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin pl-0 pl-lg-5" id="tabCupones"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?php echo $activeTab == 'sliders' ? 'active' : ''; ?>" id="listaCupon" href="sliders.php?tab=sliders">Listado de Sliders</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="newCupon" href="sliders_nuevo.php">Nuevo Slider</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link <?php echo $activeTab == 'topbar' ? 'active' : ''; ?>" id="topbarTab" href="sliders.php?tab=topbar">Top Bar</a>
                        </li>
                    </ul>
                    <!-- FIN TABS CUPONES -->
                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <!-- TAB SLIDERS -->
                        <div class="tab-pane fade <?php echo $activeTab == 'sliders' ? 'show active' : ''; ?>" id="listadoCupones" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs">Sliders Actuales</h5>
                            <div class="row mt-3">
								<?php while($row=$sliders->fetch_assoc()){ ?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row d-flex flex-row align-items-center">
                                    	<div class="col-md-4">
                                    		<img class="w-100 img-fluid" src="../<?php echo $row['imagen']?>">
                                    	</div>
                                        <div class="col-md-6">
                                            <h4 class="text-bold"><?php echo $row['titulo']; ?></h4>
                                            <p class="m-0 my-1"><?php echo $row['subtitulo']; ?></p>
                                            <p class="m-0 my-1">Link :<?php echo $row['link']; ?></p>
                                            <p class="m-0 my-1">Boton :<?php echo $row['boton']; ?></p>
                                        </div>
                                        <div class="col-md-2 ">
                                            <a class="nav-link" href="#"><button type="button" data-tabla="sliders" data-id="<?php echo $row['id']; ?>" class="btn bg-danger text-white btn-bg-red btn-eliminar">Eliminar</button></a>
                                        </div>
                                    </div>
                                </div>
								<?php } ?>
                            </div>
                        </div>

                        <!-- TAB TOPBAR -->
                        <div class="tab-pane fade <?php echo $activeTab == 'topbar' ? 'show active' : ''; ?>" id="topbarContent" role="tabpanel"
                            aria-labelledby="topbar-tab">
                            <h5 class="titulo-tabs">Configuración del Top Bar</h5>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <form id="formTopbar" method="post">
                                        <div class="form-group">
                                            <label for="topbarTexto">Texto del Top Bar</label>
                                            <input type="text" class="form-control" id="topbarTexto" name="texto"
                                                   value="<?php echo $topbar ? htmlspecialchars($topbar['texto']) : ''; ?>"
                                                   placeholder="Ej: Envío gratis en compras mayores a $5000" required maxlength="255">
                                            <small class="form-text text-muted">Este texto aparecerá centrado en la parte superior de la página.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="topbarLink">Link (opcional)</label>
                                            <input type="url" class="form-control" id="topbarLink" name="link"
                                                   value="<?php echo $topbar ? htmlspecialchars($topbar['link']) : ''; ?>"
                                                   placeholder="https://ejemplo.com/promocion" maxlength="500">
                                            <small class="form-text text-muted">Si agregas un link, el top bar será clickeable.</small>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="topbarActivo" name="activo"
                                                       value="1" <?php echo ($topbar && $topbar['activo']) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="topbarActivo">Mostrar Top Bar en el sitio</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn bg-danger text-white btn-bg-red">Guardar Top Bar</button>
                                        <div id="topbarRespuesta" class="mt-3"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>