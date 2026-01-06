<?php include ('header_usuario.php'); ?>
<?php 
include '../inc/funciones.inc.php';
include_once '../conexion/conectar.inc.php';
global $conectar;
$id=$_GET['id'];
$pedidod=$conectar->query("SELECT p.*,CONCAT(c.nombre,' ',c.apellido) as clientenombre,c.dni clientedni,CONCAT(c.direccion,' ',c.ciudad) as clientedireccion,c.cp clientecp,c.provincia clienteprovincia,c.telefono clientetelefono FROM pedidos p LEFT JOIN clientes c ON p.id_cliente=c.id WHERE p.id='$id'");

$pedido=$pedidod->fetch_assoc();

$imagenes=$conectar->query("SELECT * FROM pedidos_imagenes WHERE id_pedido='$id'");
$productos=$conectar->query("SELECT pd.cantidad,p.*,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM pedidos_detalle pd LEFT JOIN productos p ON pd.id_producto=p.id WHERE pd.id_pedido='$id'");
?>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-4 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="/"><img class="logo-blanco" src="../assets/img/logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg
                        class="bi text-yellow mr-3" width="32" height="32">
                        <use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Mis Compras</a>
                <a class="nav-link" id="v-pills-profile-tab" href="miPerfil.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#person-circle" />
                    </svg>Mi Perfil</a>

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
                    <!-- TABS MIS COMPRAS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin pl-0 pl-lg-5" id="tabMisCompras"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cargarProducto" href="index.php">Nuevo Revelado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="productosCargados" href="misCompras.php">Mis
                                Compras</a>
                        </li>
                    </ul>
                    <!-- FIN TABS MIS COMPRAS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="misCompras" role="tabpanel"
                            aria-labelledby="home-tab">

                            <h5 class="titulo-tabs-user">Detalle de el pedido #<?php echo $pedido['id']; ?></h5>
							<?php $repla=array('<','>'); ?>
                            <p><?php echo str_replace($repla,'<br>',$pedido['descripcion']); ?></p>
                            <?php if($productos->num_rows>0){ ?>
                            <div class="row my-3">
                            <?php
                            
                            while($rowp=$productos->fetch_assoc()){?>
                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <img class="img-fluid w-100" src="<?php if(!empty($rowp['imagen'])){echo '../'.$rowp['imagen']; }else{echo 'https://via.placeholder.com/50';}?>" alt="">
                                        </div>
                                        <div class="col-md-10 d-block d-lg-flex flex-column">
                                            <div class="d-block d-lg-flex">
                                                <p class="detalles-pedido"><?php echo $rowp['nombre']?></p>
                                            </div>
                                            <p class="descripcion-pedido"><?php echo $rowp['descripcion']?> <br> <?php echo $rowp['cantidad']; ?> </p>
                                        </div>
                                    </div>
                                </div>
        					<?php } ?>	
        					
        					
                            </div>
                            <?php }?>
                            <?php if($imagenes->num_rows>0){?>
                            <h4 class="text-bold">Fotos Reveladas</h4>

                            <div class="row align-items-lg-center mt-5">
                                <div class="col-md-1 d-none d-md-block">
                                    <button class="customNextBtn btn"><svg xmlns="http://www.w3.org/2000/svg" width="40"
                                            height="40" fill="currentColor" class="text-danger bi bi-chevron-left"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg></button>

                                </div>
                                <div class="col-md-10 col-sm-12">
                                    <div class="owl-carousel owl-theme">
                                        <?php 
                                        $i=1;
                                        while($row=$imagenes->fetch_assoc()){?>
                                        <div class="d-flex flex-column align-items-center">
                                            <h5 class="align-self-baseline text-bold">Foto <?php echo $i;?> </h5>
                                            <img class="shadow-sm" src="../<?php echo $row['thumb'];?>" alt="">
                                            <p class="mt-3">Tamaño : <?php echo $row['formato']?>, acabado <?php echo $row['acabado'];?></p>
                                            <p>Cantidad <?php echo $row['cantidad'];?></p>
                                        </div>
                                        <?php 
                                        $i++;
                                        }?>
                                    </div>
                                </div>
                                <div class="col-md-1 d-none d-md-block">
                                    <button class="customNextBtn btn"><svg xmlns="http://www.w3.org/2000/svg" width="40"
                                            height="40" fill="currentColor" class="text-danger bi bi-chevron-right"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg></button>
                                </div>
                            </div>
                            <?php }?>

                            <div class="row align-items-lg-center mt-5">
                                <div class="col-md-12 p-0">
                                    <h4 class="text-bold">Datos de Envíos</h4>
                                </div>
                            </div>


                            <div class="row mt-4 mb-5 datos-envio">
                                <div class="col-md-8 p-4 shadowBox">
                                    <?php 
                                	if ($pedido['envio']=='domicilio') {
                                	    $titulo='Enviar a mi domicilio';
                                	    $nombre=$pedido['clientenombre'];
                                	    $dni=$pedido['clientedni'];
                                	    $direccion=$pedido['clientedireccion'];
                                	    $provincia=$pedido['clienteprovincia'];
                                	    $cp=$pedido['clientecp'];
                                	    $telefono=$pedido['clientetelefono'];
                                	}else{
                                	    $titulo='Enviar como regalo';
                                	    $nombre=$pedido['nombre'];
                                	    $dni=$pedido['dni'];
                                	    $direccion=$pedido['direccion'];
                                	    $provincia=$pedido['provincia'];
                                	    $cp=$pedido['cp'];
                                	    $telefono=$pedido['telefono'];
                                	}
                                	
                                	switch ($pedido['entrega']){
                                	    case 'suc1':
                                	        echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 1</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>';
                                	    break;
                                	    case 'suc2':
                                	        echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 2</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>';
                                	    break;
                                	    case 'suc3':
                                	        echo '<h5>Retiro Gratis por Sucursal</h5>
                                    <p>Sucursal 3</p>
                                    <p>Calle 12 N°1108 e/55 y 56</p>';
                                	    break;
                                	    case 'urbano':
                                	        echo '<h5 class="mt-4">'.$titulo.'</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nombre y Apellido :'.$nombre.'</p>
                                            <p>DNI :'.$dni.'</p>
                                            <p>Dirección :'.$direccion.'</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Provincia : '.$provincia.'</p>
                                            <p>CP : '.$cp.'</p>
                                            <p>Teléfono/Celular : '.$telefono.'</p>
                                        </div>
                                    </div>';
                                	    break;
                                	    case 'recibir':
                                	        echo '<h5 class="mt-4">'.$titulo.'</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>Nombre y Apellido :'.$nombre.'</p>
                                            <p>DNI :'.$dni.'</p>
                                            <p>Dirección :'.$direccion.'</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p>Provincia : '.$provincia.'</p>
                                            <p>CP : '.$cp.'</p>
                                            <p>Teléfono/Celular : '.$telefono.'</p>
                                        </div>
                                    </div>';
                                	    break;    
                                	    
                                	}?>
                                </div>
                                <div class="col-md-3 offset-0 offset-md-1 p-4 shadowBox my-3 my-lg-0">
                                    <h5>Método de Pago</h5>
                                    <p class="text-bold"><?php echo metodoPago($pedido['metodo']);?></p>
                                    <h4 class="text-bold">Total: $<?php echo $pedido['total']?></h4>
                                </div>
                            </div>

                        </div>
                        <!-- FIN .tab-pane.show -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->

                </div>
                <!-- fin tab-pane -->
            </div>
            <!-- fin tab-content -->
        </div>
    </div>
</div>
<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
<script src="../node_modules\owl.carousel\dist\owl.carousel.js"></script>

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

<!-- MOVER ESTO AL FOOTER -->
<script>
        var owl = $('.owl-carousel');
        owl.owlCarousel();
        // Go to the next item
        $('.customNextBtn').click(function() {
            owl.trigger('next.owl.carousel');
        })
        // Go to the previous item
        $('.customPrevBtn').click(function() {
            // With optional speed parameter
            // Parameters has to be in square bracket '[]'
            owl.trigger('prev.owl.carousel', [300]);
        })
        </script>

        <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel();
            $('.cerrarSesion').click(function(e){
    			e.preventDefault();
    			$.post('../inc/salir.php');
    			window.location.reload();	
    		});
        });
</script>

</body>

</html>