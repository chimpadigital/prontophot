<?php include ('header.php'); ?>
<?php 
include __DIR__.'/conexion/conectar.inc.php';
global $conectar;

$query="SELECT i.*
FROM impresiones i
JOIN (
    SELECT formato, fila, MAX(id) AS max_id
    FROM impresiones
    GROUP BY formato, fila
) t
  ON i.formato = t.formato
 AND i.fila = t.fila
 AND i.id = t.max_id
ORDER BY i.formato, i.fila";

$res = $conectar->query($query);

$arrar=array();
while ($dato=$res->fetch_assoc()) {
    $i=$dato['fila'];
    $formato=$dato['formato'];
    $ulti=new DateTime($dato['fecha']);
    $array[$formato][$i]['desde']=$dato['desde'];
    $array[$formato][$i]['hasta']=$dato['hasta'];
    $array[$formato][$i]['precio']=$dato['precio'];
}
$ultima=$ulti->format('d-m-Y H:i');
//print json_encode($array);
?>
<style>
.table td{
    padding-left:1px;
    padding-right: 1px;
}
.cantidad{
    width: 37px;
    text-align: center;
    border:none;
}
.precio{
    width: 45px;
    text-align: center;
    border:none;
}
.editable{
    background-color:#f3f3f3;
}
</style>
<script>
$(function(){
	$('.precio').prop('readonly',true);
	$('.cantidad').prop('readonly',true);
	$('.precio').removeClass('editable');
	$('.cantidad').removeClass('editable');
	$('.btn-guardar').prop('disabled',true);
	$('#editar').click(function(e){
		e.preventDefault();
		$('.precio').prop('readonly',false);
		$('.cantidad').prop('readonly',false);
		$('.btn-guardar').prop('disabled',false);
		$('.precio').addClass('editable');
		$('.cantidad').addClass('editable');
	});

	$('#updateValores').submit(function(e){
		e.preventDefault();
		if (!confirm("Esta seguro de que desea actualizar los valores?")) 
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
    					$('.precio').prop('readonly',true);
    					$('.cantidad').prop('readonly',true);
    					$('.btn-guardar').prop('disabled',true);
    					$('.precio').removeClass('editable');
    					$('.cantidad').removeClass('editable');
    					$('#ultima').html(data.fecha);
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
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="\"><img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin " id="v-pills-tab" role="tablist"
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
                            <a class="nav-link " id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link active" id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <!-- TAB VALORES IMPRESION -->
                        <div class="tab-pane fade show active" id="valores" role="tabpanel" aria-labelledby="contact-tab2">
                        	<form action="inc/update_valores.php" method="post" id="updateValores">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered tabla-precios">
                                            <thead>
                                                <tr>
                                                    <th class="celda-vacia"></th>
                                                    <th class="bg-gris-claro th-inicio-radius">Polaroid</th>
                                                    <th class="bg-gris-oscuro">10x15w</th>
                                                    <th class="bg-gris-claro">13x18w</th>
                                                    <th class="bg-gris-oscuro">15x20w</th>
                                                    <th class="bg-gris-claro">20x30w</th>
                                                    <th class="bg-gris-oscuro th-fin-radius">25x38w</th>
                                                </tr>
                                            </thead>
                                        
                                        </table>
                                    <!-- </div> -->

                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[1]" value="<?php echo $array['polaroid']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[1]" value="<?php echo $array['polaroid']['1']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[1]" value="<?php echo $array['10x15']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[1]" value="<?php echo $array['10x15']['1']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[1]" value="<?php echo $array['13x18']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[1]" value="<?php echo $array['13x18']['1']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[1]" value="<?php echo $array['15x20']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[1]" value="<?php echo $array['15x20']['1']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[1]" value="<?php echo $array['20x30']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[1]" value="<?php echo $array['20x30']['1']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[1]" value="<?php echo $array['25x38']['1']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[1]" value="<?php echo $array['25x38']['1']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[1]" value="<?php echo $array['polaroid']['1']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[1]" value="<?php echo $array['10x15']['1']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[1]" value="<?php echo $array['13x18']['1']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[1]" value="<?php echo $array['15x20']['1']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[1]" value="<?php echo $array['20x30']['1']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[1]" value="<?php echo $array['25x38']['1']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->

                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[2]" value="<?php echo $array['polaroid']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[2]" value="<?php echo $array['polaroid']['2']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[2]" value="<?php echo $array['10x15']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[2]" value="<?php echo $array['10x15']['2']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[2]" value="<?php echo $array['13x18']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[2]" value="<?php echo $array['13x18']['2']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[2]" value="<?php echo $array['15x20']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[2]" value="<?php echo $array['15x20']['2']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[2]" value="<?php echo $array['20x30']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[2]" value="<?php echo $array['20x30']['2']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[2]" value="<?php echo $array['25x38']['2']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[2]" value="<?php echo $array['25x38']['2']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[2]" value="<?php echo $array['polaroid']['2']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[2]" value="<?php echo $array['10x15']['2']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[2]" value="<?php echo $array['13x18']['2']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[2]" value="<?php echo $array['15x20']['2']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[2]" value="<?php echo $array['20x30']['2']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[2]" value="<?php echo $array['25x38']['2']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[3]" value="<?php echo $array['polaroid']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[3]" value="<?php echo $array['polaroid']['3']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[3]" value="<?php echo $array['10x15']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[3]" value="<?php echo $array['10x15']['3']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[3]" value="<?php echo $array['13x18']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[3]" value="<?php echo $array['13x18']['3']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[3]" value="<?php echo $array['15x20']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[3]" value="<?php echo $array['15x20']['3']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[3]" value="<?php echo $array['20x30']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[3]" value="<?php echo $array['20x30']['3']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[3]" value="<?php echo $array['25x38']['3']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[3]" value="<?php echo $array['25x38']['3']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[3]" value="<?php echo $array['polaroid']['3']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[3]" value="<?php echo $array['10x15']['3']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[3]" value="<?php echo $array['13x18']['3']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[3]" value="<?php echo $array['15x20']['3']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[3]" value="<?php echo $array['20x30']['3']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[3]" value="<?php echo $array['25x38']['3']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[4]" value="<?php echo $array['polaroid']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[4]" value="<?php echo $array['polaroid']['4']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[4]" value="<?php echo $array['10x15']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[4]" value="<?php echo $array['10x15']['4']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[4]" value="<?php echo $array['13x18']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[4]" value="<?php echo $array['13x18']['4']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[4]" value="<?php echo $array['15x20']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[4]" value="<?php echo $array['15x20']['4']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[4]" value="<?php echo $array['20x30']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[4]" value="<?php echo $array['20x30']['4']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[4]" value="<?php echo $array['25x38']['4']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[4]" value="<?php echo $array['25x38']['4']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[4]" value="<?php echo $array['polaroid']['4']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[4]" value="<?php echo $array['10x15']['4']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[4]" value="<?php echo $array['13x18']['4']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[4]" value="<?php echo $array['15x20']['4']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[4]" value="<?php echo $array['20x30']['4']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[4]" value="<?php echo $array['25x38']['4']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[5]" value="<?php echo $array['polaroid']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[5]" value="<?php echo $array['polaroid']['5']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[5]" value="<?php echo $array['10x15']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[5]" value="<?php echo $array['10x15']['5']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[5]" value="<?php echo $array['13x18']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[5]" value="<?php echo $array['13x18']['5']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[5]" value="<?php echo $array['15x20']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[5]" value="<?php echo $array['15x20']['5']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[5]" value="<?php echo $array['20x30']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[5]" value="<?php echo $array['20x30']['5']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[5]" value="<?php echo $array['25x38']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[5]" value="<?php echo $array['25x38']['5']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[5]" value="<?php echo $array['polaroid']['5']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[5]" value="<?php echo $array['10x15']['5']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[5]" value="<?php echo $array['13x18']['5']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[5]" value="<?php echo $array['15x20']['5']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[5]" value="<?php echo $array['20x30']['5']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[5]" value="<?php echo $array['25x38']['5']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
                                    <!-- <div class="table-responsive"> -->
                                        <table class="table table-bordered tabla-precios border-black">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-danger text-white rounded-th-top">Cantidad</th>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_d[6]" value="<?php echo $array['polaroid']['6']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="polaroid_h[6]" value="<?php echo $array['polaroid']['6']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_d[6]" value="<?php echo $array['10x15']['5']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="10x15_h[6]" value="<?php echo $array['10x15']['6']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_d[6]" value="<?php echo $array['13x18']['6']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="13x18_h[6]" value="<?php echo $array['13x18']['6']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_d[6]" value="<?php echo $array['15x20']['6']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="15x20_h[6]" value="<?php echo $array['15x20']['6']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_d[6]" value="<?php echo $array['20x30']['6']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="20x30_h[6]" value="<?php echo $array['20x30']['6']['hasta'];?>"></td>
                                                    <td><input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_d[6]" value="<?php echo $array['25x38']['6']['desde'];?>"> a <input type="text" pattern="[0-9.]+" class="cantidad" name="25x38_h[6]" value="<?php echo $array['25x38']['6']['hasta'];?>"></td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-black text-white rounded-th-bot">Precio</th>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="polaroid_p[6]" value="<?php echo $array['polaroid']['6']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="10x15_p[6]" value="<?php echo $array['10x15']['6']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="13x18_p[6]" value="<?php echo $array['13x18']['6']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="15x20_p[6]" value="<?php echo $array['15x20']['6']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="20x30_p[6]" value="<?php echo $array['20x30']['6']['precio'];?>"></td>
                                                    <td class="text-center">$<input type="text" pattern="[0-9.]+" class="precio" name="25x38_p[6]" value="<?php echo $array['25x38']['6']['precio'];?>"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <!-- </div> -->
                                    </div>

                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="d-flex justify-content-end botones-lista-precios">
                                        <button type="button" class="btn text-success border-success mx-3" id="editar">Editar</button>
                                        <button type="submit" class="btn bg-success text-white btn-guardar" >Guardar</button>
                                    </div>
                                    <p class="ult-actualizacion text-right mt-3" id="actualizaHora">*Última actualización: <span id="ultima"><?php echo $ultima; ?></span>hs</p>
                                </div>
                            </div>
							</form>


                        </div>
                        <!-- FIN VALORES IMPRESION -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->
                </div>
            </div>

        </div>
	</div>
</div>
<?php include ('footer.php'); ?>