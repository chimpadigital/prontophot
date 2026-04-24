<?php include ('header.php'); 
?>
<?php 
include ('../conexion/conectar.inc.php');
global $conectar;
$query="SELECT * FROM categorias";
$categorias=$conectar->query($query);

?>
<script>
	$(function(){
		$('#formCargaCupon').submit(function(e){
			e.preventDefault();
			if (!confirm("Esta seguro de que desea guardar el cupon?")) 
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
    					if (data.success){
        					alert(data.msg);
    						$('#formCargaCupon')[0].reset();
    					}else{
    						alert('Error '+data.error);	
    					}
    				    	
    				}          
    			});		
    		}	
	    });
		
	});
</script>
<div class="container-fluid bg-black border-top border-white">
    <div class="row fila-admin">
        <div class="col-4 bg-black py-4 px-3 d-none d-md-block">
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
                <a class="nav-link" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>    
                <a class="nav-link active" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3" width="32" height="32">
                    <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-8 bg-white p-5 rounded-lg columna-content-admin">

            <div class="tab-content" id="v-pills-tabContent">

                <div class="tab-pane fade show active" id="v-pills-messages" role="tabpanel"
                    aria-labelledby="v-pills-messages-tab">

                    <!-- TABS CUPONES -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin pl-0 pl-lg-5" id="tabCupones"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="listaCupon" href="cupones.php">Listado de Cupones</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="newCupon" href="cupones_nuevoCupon.php">Nuevo Cupón</a>
                        </li>
                    </ul>
                    <!-- FIN TABS CUPONES -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="nuevoCupon" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs mb-0">Crear nuevo cupón</h5>

                            <div class="row mt-3">
                                <div class="col-md-6">

                                    <form id="formCargaCupon" action="inc/guardar_cupon.php" method="post" class="needs-validation mt-5 form-carga-producto" novalidate>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="nombreCupon">Nombre del Cupón</label>
                                                <input type="text" name="nombre" class="form-control" id="">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="porcentajeDescuento">Procentaje de Descuento</label>
                                                <input type="text" name="descuento" class="form-control" id="">
                                            </div>
                                        </div>
                                        <h5 class="text-bold mt-4 mb-2">Duración del Cupón</h5>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="cuponDesde">Desde</label>
                                                <input type="date" name="desde" class="form-control" id="">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="cuponHasta">Hasta</label>
                                                <input type="date" name="hasta" class="form-control" id="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="cuponDesde">Aplica</label>
                                                <select name="seccion" class="form-control">
                                                	<option value="0">Impresiones</option>
                                                	<option value="1">Productos</option>
                                                </select>
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="cuponCat">Categorias</label>
                                                <select name="categorias[]" multiple class="form-control">
                                                <?php while($rowc=$categorias->fetch_assoc()){?>
                                                	<option value="<?php echo $rowc['id']?>"><?php echo $rowc['nombre'];?></option>
                                                <?php }?>
                                                </select>
                                            </div>
                                        </div>    
                                        <div class="form-row mt-4">
                                            <button type="submit" class="btn btn-warning btn-cargar-producto">Crear Cupón</button>
                                        </div>
                                        
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