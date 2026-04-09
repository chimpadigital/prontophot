<?php include ('header_usuario.php'); ?>
<?php 
include_once '../conexion/conectar.inc.php';
global $conectar;
$id=$ingresar->id;
$sql="SELECT p.*,DATE_FORMAT(p.fecha, '%d-%m-%Y') as fecha ,(SELECT id_producto FROM pedidos_detalle WHERE id_pedido=p.id LIMIT 1) as idproducto,(SELECT thumb FROM pedidos_imagenes WHERE id_pedido=p.id ORDER BY id ASC LIMIT 1) as imagen FROM `pedidos` p WHERE p.id_cliente='$id' AND p.estado='1' ORDER BY p.id DESC";
$pedidos=$conectar->query($sql);
//echo $conectar->error.$sql;
?>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="/"><img class="logo-blanco" src="../assets/img/logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link" id="v-pills-profile-tab" href="miPerfil.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#person-circle" />
                    </svg>Mi Perfil</a>

            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin min-vh-100">

            <!-- tab-content -->
            <div class="tab-content" id="v-pills-tabContent">
                <!-- tab-pane -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <!-- TABS MIS COMPRAS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin pl-0 pl-lg-5" id="tabMisCompras"
                        role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cargarProducto" href="index.php">Nuevo Revelado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="productosCargados" href="misCompras.php">Mis Compras</a>
                        </li>
                    </ul>
                    <!-- FIN TABS MIS COMPRAS -->
                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">
                        <div class="tab-pane fade show active" id="misCompras" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row mt-3">
                            <?php while($row=$pedidos->fetch_assoc()){
                                $repla=array('<','>');
                                
                                $idp=$row['idproducto'];
                                if(!is_null($idp)){
                                    $imag=$conectar->query("SELECT * FROM imagenes WHERE id_producto='$idp'");
                                    $rowi=$imag->fetch_assoc();
                                    $imagen=$rowi['imagen'];
                                }else{
                                    $imagen=$row['imagen'];
                                }
                                
                                
                                ?>    

                                <div class="col-12 p-4 col-categoria my-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img class="img-fluid w-100" src="<?php if(!empty($imagen)){echo '../'.$imagen; }else{echo '../img/placeholder.png';}?>" alt="">
                                        </div>
                                        <div class="col-md-9 d-block d-lg-flex flex-column justify-content-around">
                                            <div class="d-block d-lg-flex detalles-pedido">
                                                <p>Pedido <br> #<?php echo $row['id'];?> </p>
                                                <p class="mx-0 mx-lg-4">Fecha <br> <?php echo $row['fecha'];?> </p>
                                            </div>
                                            <p class="descripcion-pedido"><?php echo str_replace($repla,'<br>',$row['descripcion']); ?></p>
                                            <div class="d-block d-lg-flex align-items-center justify-content-between mt-5">
                                                <a href="detallePedido.php?id=<?php echo $row['id']; ?>"><button class="btn btn-danger mb-3 mb-md-0">Más Info</button></a>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
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
        	$('.cerrarSesion').click(function(e){
            	e.preventDefault();
            	$.post('../inc/salir.php');
            	location.replace("/");	
            });
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