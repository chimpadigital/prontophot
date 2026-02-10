<?php include ('header.php'); ?>
<?php
include_once __DIR__.'/conexion/conectar.inc.php';
include_once __DIR__.'/inc/funciones.inc.php';
global $conectar;
//var_dump($_GET);
$categorias=$conectar->query("SELECT * FROM categorias ");
if (isset($_GET['id'])) {
    //$cats=explode('_',$_GET['cat']);
    $idc=$_GET['id'];
    $query="SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id WHERE p.stock>0 AND c.id = '$idc'  ORDER BY p.id DESC  ";
}else{
    $query="SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id WHERE p.stock>0 ORDER BY p.id DESC";
}
$productos=$conectar->query($query);

// Obtener el coeficiente de impuestos
$taxQuery = $conectar->query("SELECT coeficiente FROM tax WHERE id = 1");
$taxData = $taxQuery->fetch_assoc();
$taxCoeficiente = $taxData ? $taxData['coeficiente'] : 1.21;
?>
<style>
.pagination .active .page{
        color: #fff !important;
    background-color: #DA0000;
    border-color: #DA0000;

    }
.pagination .page{
    color: #000 !important;
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
}
.tag-percent {
    background-color: #DA0000;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.75rem;
    font-weight: bold;
}
</style>
<div class="position-relative portada-tienda d-flex align-items-center">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-white text-bold">Nuestra Tienda</h2>
                <p class="text-white">Productos elaborados con la más alta tecnología y asesoramiento personalizado para
                    cada uno de nuestros clientes.</p>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>

<div class="container py-5 mt-0 mt-md-5">
    <div class="row">
        <div class="col-md-3">
            <!-- <h4 class="text-bold mb-3">Categorias</h4> -->
        </div>
        <div class="col-md-9"></div>
    </div>
    <div class="row d-flex flex-row">
        <div class="col-md-3 order-2 order-md-1">
            <h4 class="text-bold mb-3">Categorias</h4>
            <div class="bg-light">
                <!-- LISTADO DE CATEGORIAS -->
                
                <div class="accordion pt-2 pb-4" id="categorias-tienda">
                	<div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <a href="tienda/" class="btn btn-link btn-block text-left " >
                                    Todos
                                </a>
                            </h2>
                        </div>
                    </div>
                	<?php while($rowc=$categorias->fetch_assoc()){ ?>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <a href="tienda/<?php echo $rowc['id'].'_'.getUrl($rowc['nombre'])?>" class="btn btn-link btn-block text-left " >
                                    <?php echo $rowc['nombre']; ?>
                                    
                                </a>
                            </h2>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-9 order-1 order-md-2" id="Productos">
			<div class="row justify-content-end">
				<div class="col-12 col-sm-12 col-md-6 input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
                  </div>
                  <input type="text" class="search form-control" placeholder="Categoria, nombre, color" />
                </div>
			</div>
            <!-- LISTADO DE PRODUCTOS -->
            <div class="row list">
            	<?php while($row=$productos->fetch_assoc()){
            	$colores='';
            	if($row['color_rojo']=='1'){$colores.=' rojo';}
            	if($row['color_azul']=='1'){$colores.=' azul';}
            	if($row['color_naranja']=='1'){$colores.=' naranja';}
            	if($row['color_celeste']=='1'){$colores.=' celeste';}
            	if($row['color_violeta']=='1'){$colores.=' violeta';}
            	if($row['color_verde']=='1'){$colores.=' verde';}
            	if($row['color_amarillo']=='1'){$colores.=' amarillo';}

            	// Calcular precios
            	$precioLista = $row['precio'];
            	$descuento_porcentaje = isset($row['descuento_final']) ? $row['descuento_final'] : 0;
            	$precioFinal = $precioLista - ($precioLista * $descuento_porcentaje / 100);
            	$precioConImpuestos = $precioFinal / $taxCoeficiente;
            	?>
                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="<?php echo $row['id'].'_'.getUrl($row['nombre']);?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="<?php echo $row['imagen']; ?>" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto orden-nombre mb-2"><?php echo $row['nombre']; ?></p>
                                <p class="detalle-producto bsc-descripcion"><?php echo $row['descripcion']?></p>

                                <?php if ($descuento_porcentaje > 0): ?>
                                    <p class="m-0" style="text-decoration: line-through; color: #999; font-size: 0.9rem;">$<?php echo number_format($precioLista, 2); ?></p>
                                    <p class="nombre-producto mb-2 orden-precio">$<?php echo number_format($precioFinal, 2); ?> <span class="tag-percent-tienda"><?php echo $descuento_porcentaje; ?>% OFF</span></p>
                                <?php else: ?>
                                    <p class="nombre-producto m-0 orden-precio">$<?php echo number_format($precioLista, 2); ?></p>
                                <?php endif; ?>

                                <p class="text-muted small mb-0">Sin impuestos: $<?php echo number_format($precioConImpuestos, 2); ?></p>

								<span style="display:none;" class="bsc-categoria"><?php echo $row['categoria']?></span>
								<span style="display:none;" class="bsc-colores"><?php echo $colores;?></span>
                            </div>
                        </div>
                        <a href="<?php echo $row['id'].'_'.getUrl($row['nombre']);?>">
                        	<button class="btn bg-danger text-white btn-bg-red w-100">
                        		Ver Producto
                            </button>
                        </a>
                    </div>
                </div>
				<?php } ?>
                
            </div>

            <!-- PAGINACION -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example paginacion">
                        <ul class="pagination justify-content-center">
                            
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
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
                    <a href="https://es-la.facebook.com/pg/Prontophot/about/?ref=page_internal">
                    	<svg xmlns="http://www.w3.org/2000/svg" width="17.055" height="31.843" viewBox="0 0 17.055 31.843">
                            <g id="Grupo_325" data-name="Grupo 325" transform="translate(402 -2245)">
                                <path id="Icon_awesome-facebook-f" data-name="Icon awesome-facebook-f" d="M17.547,17.912l.884-5.763H12.9V8.409c0-1.577.772-3.113,3.249-3.113h2.514V.389A30.656,30.656,0,0,0,14.2,0c-4.554,0-7.53,2.76-7.53,7.757v4.392H1.609v5.763H6.671V31.843H12.9V17.912Z" transform="translate(-403.609 2245)" fill="#fff" />
                            </g>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/prontophotlp/?igshid=16xmcwskod733" class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31.38" height="31.373" viewBox="0 0 31.38 31.373">
                            <g id="Grupo_326" data-name="Grupo 326" transform="translate(402 -2298)">
                                <path id="Icon_awesome-instagram" data-name="Icon awesome-instagram" d="M15.688,9.881a8.044,8.044,0,1,0,8.044,8.044A8.031,8.031,0,0,0,15.688,9.881Zm0,13.273a5.229,5.229,0,1,1,5.229-5.229,5.239,5.239,0,0,1-5.229,5.229Zm10.249-13.6a1.876,1.876,0,1,1-1.876-1.876A1.872,1.872,0,0,1,25.937,9.552Zm5.327,1.9a9.285,9.285,0,0,0-2.534-6.574,9.346,9.346,0,0,0-6.574-2.534C19.567,2.2,11.8,2.2,9.213,2.348A9.332,9.332,0,0,0,2.639,4.875,9.315,9.315,0,0,0,.1,11.449C-.042,14.039-.042,21.8.1,24.393a9.285,9.285,0,0,0,2.534,6.574A9.358,9.358,0,0,0,9.213,33.5c2.59.147,10.354.147,12.944,0a9.285,9.285,0,0,0,6.574-2.534,9.346,9.346,0,0,0,2.534-6.574c.147-2.59.147-10.347,0-12.937ZM27.919,27.172a5.294,5.294,0,0,1-2.982,2.982c-2.065.819-6.966.63-9.248.63s-7.19.182-9.248-.63a5.294,5.294,0,0,1-2.982-2.982c-.819-2.065-.63-6.966-.63-9.248s-.182-7.19.63-9.248A5.294,5.294,0,0,1,6.441,5.694c2.065-.819,6.966-.63,9.248-.63s7.19-.182,9.248.63a5.294,5.294,0,0,1,2.982,2.982c.819,2.065.63,6.966.63,9.248S28.738,25.114,27.919,27.172Z" transform="translate(-401.995 2295.762)" fill="#fff" />
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <a class="d-md-none d-lg-none d-block text-center wp" href="https://api.whatsapp.com/send?phone=+5492216784142  &amp;text=Buenos%20días,%20quiero%20mas%20info%20">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
<script>
$(function(){
	var monkeyList = new List('Productos', {
		  valueNames: ['orden-nombre','orden-precio','bsc-categoria','bsc-colores','bsc-descripcion'],
		  page: 6,
		  pagination: true
		});
			
});
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

<script src="assets/js/starter.js"></script>

</body>

</html>