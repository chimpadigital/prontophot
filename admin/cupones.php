<?php include ('header.php'); ?>
<?php 
include __DIR__.'/conexion/conectar.inc.php';
global $conectar;
$query="SELECT categorias,seccion,id,nombre,descuento,DATE_FORMAT(desde, '%d-%m-%Y') as desde,DATE_FORMAT(hasta, '%d-%m-%Y') as hasta FROM cupones";
$cupones=$conectar->query($query);

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
	});
</script>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
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
                            <a class="nav-link active" id="listaCupon" href="cupones.php">Listado de Cupones</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="newCupon" href="cupones_nuevoCupon.php">Nuevo Cupón</a>
                        </li>
                    </ul>
                    <!-- FIN TABS CUPONES -->
                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <div class="tab-pane fade show active" id="listadoCupones" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs">Cupones Actuales</h5>
                            <div class="row mt-3">
								<?php while($row=$cupones->fetch_assoc()){ ?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row d-flex flex-row align-items-center">
                                        <div class="col-md-10">
                                            <h4 class="text-bold"><?php echo $row['nombre']; ?></h4>
                                            <p class="m-0 my-1">Descuento <?php echo $row['descuento']; ?>%</p>
                                            <p class="m-0 my-1"><?php echo $row['desde'].' || '.$row['hasta']; ?></p>
                                            <p class="m-0 my-1">Aplica a <?php if($row['seccion']=='0'){echo 'Impresiones';}else{echo 'Productos';}?></p>
                                            <?php if($row['seccion']=='1'){
                                                if(!empty($row['categorias'])){
                                                $categ=unserialize($row['categorias']);
                                            
                                                ?>
                                                <p class="m-0 my-1">Categorias :
                                                <?php foreach ($categ as $cat){
                                                $ncat=$conectar->query("SELECT nombre FROM categorias WHERE id='$cat'");
                                                $cate=$ncat->fetch_assoc();
                                                echo $cate['nombre'].' ';
                                                    ?>
                                                <?php }?>
                                                </p>
                                            <?php } }?>
                                        </div>
                                        <div class="col-md-2 ">
                                            <a class="nav-link" href="#"><button type="button" data-tabla="cupones" data-id="<?php echo $row['id']; ?>" class="btn bg-danger text-white btn-bg-red btn-eliminar">Eliminar</button></a>
                                        </div>
                                    </div>
                                </div>
								<?php } ?>
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