<?php
include_once 'conexion/conectar.inc.php';
include_once 'inc/funciones.inc.php';
global $conectar;
$recomendados=$conectar->query("SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id ORDER BY id DESC LIMIT 3");
echo $conectar->error;


// Obtener el coeficiente de impuestos
$taxQuery = $conectar->query("SELECT coeficiente FROM tax WHERE id = 1");
$taxData = $taxQuery->fetch_assoc();
$taxCoeficiente = $taxData ? $taxData['coeficiente'] : 1.21;
?>

<div class="bg-light prod-destacados">
    <div class="container py-5 productos-destacados">
        <div class="row">
            <div class="col-md-12 py-5">
                <h3 class="font-monument text-uppercase text-bold text-center">Productos Destacados</h3>
            </div>
			<?php while ($row=$recomendados->fetch_assoc()){ $colores='';
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
                            <img class="img-product w-100" src="<?php echo $row['imagen']; ?>" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto orden-nombre mb-2"><?php echo $row['nombre']; ?></p>

                                <?php if ($descuento_porcentaje > 0): ?>
                                    
                                    <p class="m-0" style="text-decoration: line-through; color: #999; font-size: 0.9rem;">$<?php echo number_format($precioLista, 2); ?></p>

                                    <p class="nombre-producto orden-precio">$<?php echo number_format($precioFinal, 2); ?> <span class="tag-percent-tienda"><?php echo $descuento_porcentaje; ?>% OFF</span></p>

                                <?php else: ?> 
                                    
                                    <p class="m-0" style="text-decoration: line-through; color: #999; font-size: 0.9rem; visibility: hidden;">0</p>

                                    <p class="nombre-producto  orden-precio">$<?php echo number_format($precioLista, 2); ?></p>
                                
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
			<?php }?>
        </div>
    </div>
</div>