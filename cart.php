<?php 
session_start();
include_once 'include/config.inc.php';
include_once 'conexion/conectar.inc.php';
global $conectar;
include_once 'include/funciones.inc.php';


//echo $query;
?>

<!DOCTYPE HTML>
<html lang="es">
<head>
<base href="<?php echo TIENDA_URL; ?>">
<meta charset="utf-8">
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="max-age=604800" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title><?php echo TIENDA_NOMBRE; ?> - Shopping Cart</title>

<link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">

<!-- jQuery -->
<script src="js/jquery-2.0.0.min.js" type="text/javascript"></script>

<!-- Bootstrap4 files-->
<script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>

<!-- Font awesome 5 -->
<link href="fonts/fontawesome/css/all.min.css" type="text/css" rel="stylesheet">

<!-- custom style -->
<link href="css/ui.css" rel="stylesheet" type="text/css"/>
<link href="css/responsive.css" rel="stylesheet" type="text/css" />

<link href="css/loading.css" rel="stylesheet" type="text/css"/>
<script src="js/loading.js" type="text/javascript"></script>

<!-- custom javascript -->
<script src="js/script.js" type="text/javascript"></script>

</head>
<body>


<header class="section-header">
<?php include 'include/main.inc.php'?>

<?php include 'include/nav.inc.php';?>

</header> <!-- section-header.// -->


<!-- ========================= SECTION CONTENT ========================= -->
<section class="section-content padding-y">
    <div class="container">
    <div class="row">
    	<main class="col-md-8 col-sm-6 col-12">
            <div class="card">
                <table class="table table-borderless table-shopping-cart">
                    <thead class="text-muted">
                        <tr class="small text-uppercase">
                          <th scope="col">Producto</th>
                          <th scope="col" width="120">Cantidad</th>
                          <th scope="col" width="120">Precio</th>
                          <th scope="col" class="text-right" width="100"> </th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
                    	$cart=$_SESSION['carrito'];
                    	$total=0;
                    	foreach ($cart as $id => $valor){
                    	$item=getItem($id);    
                    	$precio=($item['precio_venta']*$valor['cant']);
                    	$total=$total+$precio;
                    	?>
                        <tr>
                        	<td>
                        		<figure class="itemside">
                        			<div class="aside"><img src="<?php echo $item['imagen']; ?>" class="img-sm"></div>
                        			<figcaption class="info">
                        				<a href="#" class="title text-dark"><?php echo $item['nombre']; ?></a>
                        				<p class="text-muted small">Marca: <?php echo $item['marca']?></p>
                        			</figcaption>
                        		</figure>
                        	</td>
                        	<td> 
                        		<select data-id="<?php echo $id; ?>" class="form-control setCantidad" >
                        			<?php for ($i = 1; $i <= $item['cantidad']; $i++) { ?>
                        			<option value="<?php echo $i; ?>" <?php if($i==$valor['cant']){ echo ' selected '; }?>><?php echo $i;?></option>	
                        			<?php }?>
                        		</select> 
                        	</td>
                        	<td> 
                        		<div class="price-wrap"> 
                        			<var class="price">$<?php echo setMoneda($precio); ?></var> 
                        			<small class="text-muted"> $<?php echo setMoneda($item['precio_venta']);?> c/u </small> 
                        		</div> <!-- price-wrap .// -->
                        	</td>
                        	<td class="text-right"> 
                        	<button type="button" data-id="<?php echo $id; ?>" class="btn btn-light removerItem"> <i class="fas fa-trash-alt"></i> </button>
                        	</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="card-body border-top">
                	<a href="checkout" class="btn btn-primary float-md-right"> Proceder al Pago <i class="fa fa-chevron-right"></i> </a>
                	<a href="#" class="btn btn-light"> <i class="fa fa-chevron-left"></i> Continuar Comprando </a>
                </div>	
            </div> <!-- card.// -->
            <div class="alert alert-success mt-3">
            	<p class="icontext"><i class="icon text-success fa fa-truck"></i> Free Delivery within 1-2 weeks</p>
            </div>
        
    	</main> <!-- col.// -->
    	<aside class="col-md-4 col-12 col-sm-6">
    		<div class="card mb-3">
    			<div class="card-body">
    			<form>
    				<div class="form-group">
    					<label>Tiene Cupon?</label>
    					<div class="input-group">
    						<input type="text" class="form-control" name="" placeholder="Codigo cupon">
    						<span class="input-group-append"> 
    							<button class="btn btn-primary">Aplicar</button>
    						</span>
    					</div>
    				</div>
    			</form>
    			</div> <!-- card-body.// -->
    		</div>  <!-- card .// -->
    		<div class="card">
    			<div class="card-body">
    					<dl class="dlist-align">
    					  <dt>Precio total:</dt>
    					  <dd class="text-right">$ <?php echo setMoneda($total);?></dd>
    					</dl>
    					<dl class="dlist-align">
    					  <dt>Descuento:</dt>
    					  <dd class="text-right">$ 0</dd>
    					</dl>
    					<dl class="dlist-align">
    					  <dt>Total:</dt>
    					  <dd class="text-right  h5"><strong>$ <?php echo setMoneda($total); ?></strong></dd>
    					</dl>
    					<hr>
    					<p class="text-center mb-3">
    						<img src="images/misc/payments.png" height="26">
    					</p>
    					
    			</div> <!-- card-body.// -->
    		</div>  <!-- card .// -->
    	</aside> <!-- col.// -->
    </div>
    
    </div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->

<!-- ========================= SECTION SUBSCRIBE  ========================= -->
<section class="padding-y-lg bg-light border-top">
	<?php include 'include/newsletter.inc.php';?>
</section>
<!-- ========================= SECTION SUBSCRIBE END// ========================= -->
<!-- ========================= FOOTER ========================= -->
<footer class="section-footer bg-secondary">
	<?php include 'include/footer.inc.php';?>
</footer>
<!-- ========================= FOOTER END // ========================= -->
<script>
$(document).ready(function() {
	getCart();
	
	$('.setCantidad').change(function(e){
		e.preventDefault();
		var cant=$(this).val();
		var id=$(this).data('id');
		$.post('include/set_cantidad.php',{id:id,cant:cant});
		location.reload();
	});
	
});
</script>
</body>
</html>