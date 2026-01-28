<?php include ('header_usuario.php'); ?>
<?php 
include_once '../conexion/conectar.inc.php';
global $conectar;
$id=$ingresar->id;
$usuario=$conectar->query("SELECT * FROM clientes WHERE id='$id'");
$datos=$usuario->fetch_assoc();
?>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="/"><img class="logo-blanco" src="../assets/img/logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link " id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32" height="32"><use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" /></svg>Mis Compras</a>
                <a class="nav-link active" id="v-pills-profile-tab" href="miPerfil.php"><svg class="bi text-yellow mr-3" width="32" height="32"><use xlink:href="../node_modules/bootstrap-icons/bootstrap-icons.svg#person-circle" /></svg>Mi Perfil</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin min-vh-100">
            <div class="row mt-0 mt-md-5">
                <div class="col-md-12">
                    <h5 class="titulo-tabs-user">Mis Datos</h5>
                </div>
            </div>
            <div class="row mt-0 mt-md-3">
                <div class="col-md-12 col-sm-12">
                    <form class="form-mi-perfil" method="post" id="formPerfil" >
                        <div class="form-row">
                            <div class="col-md-4 d-none">
                                <img class="img-fluid w-100" src="../img/placeholder.png" alt="">
                                <div class="d-flex align-items-center justify-content-center mt-3">
                                    <!-- <button class="btn btn-danger mb-3 mb-md-0">Editar</button> -->
                                </div>
                            </div>
                            <div class="col-md-10 offset-0 offset-md-0">
                                <div class="form-group ">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" value="<?php echo $datos['nombre']; ?>" name="nombre">
                                </div>
                                <div class="form-group ">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" class="form-control" value="<?php echo $datos['apellido']; ?>" name="apellido">
                                </div>
                                <div class="form-group ">
                                    <label for="dni">DNI</label>
                                    <input type="text" class="form-control"  value="<?php echo $datos['dni']; ?>" name="dni">
                                </div>
                                <div class="form-group ">
                                    <label for="cuit">CUIT</label>
                                    <input type="text" class="form-control" value="<?php echo $datos['cuit']; ?>" name="cuit" placeholder="XX-XXXXXXXX-X">
                                </div>
                                <div class="form-group ">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $datos['email']; ?>" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-0 mt-md-4">
                            <div class="col-md-10 col-sm-10 ">
                                <div class="form-group">
                                    <label for="tel">Teléfono</label>
                                    <input type="text" class="form-control" value="<?php echo $datos['telefono']; ?>" name="telefono">
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-5">
                                <label for="prov">Provincia</label>
                                <input type="text" class="form-control"  value="<?php echo $datos['provincia']; ?>" name="provincia">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" class="form-control" value="<?php echo $datos['ciudad']; ?>" name="ciudad">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="dir">Dirección</label>
                                <input type="text" class="form-control" value="<?php echo $datos['direccion']; ?>" name="direccion">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="altura">Altura</label>
                                <input type="number" class="form-control" value="<?php echo $datos['altura']; ?>" name="altura" min="1" max="99999">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="cp">CP</label>
                                <input type="text" class="form-control" value="<?php echo $datos['cp']; ?>" name="cp">
                            </div>
                        </div>
                        <hr class="border-danger">
                        <div class="form-group">
                                <label for="inputPassword3" class="mt-4">Contraseña</label>
                                <div class="d-block d-md-flex align-items-center">
                                    <input type="password" class="form-control w-100" id="nuevaClave">
                                    <button type="button" data-id="<?php echo $datos['id']; ?>" id="cambiarClave" class="btn text-success border-success ml-0 ml-md-3 mt-3 mt-md-0 w-100 modificar-contraseña">Modificar Contraseña</button>
                                </div>
                        </div>
                        <div class="d-flex justify-content-center mt-5">
                        	<input type="hidden" name="id" value="<?php echo $datos['id']; ?>">
                            <button type="submit" class="btn bg-success text-white">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
$(function(){
	$('#cambiarClave').click(function(e){
		e.preventDefault();
		var clave=$('#nuevaClave').val();
		var idcliente=<?php echo $ingresar->id;?>;
		if(clave!=''){
			if (!confirm("Esta seguro de que desea cambiar su clave?")) 
        		{	return false; }
        	else { 
            	$.post('../inc/cambiar_clave.php',{pass:clave,id:idcliente},function(data){
    				if(data.success){
    					alert('Contraseña Guardada');
    				}else{
    					console.log(data.error);
    					alert('Error al guardar. intente de nuevo');
    				}
                },'json');
        	}
		}
		
	});

	$('#formPerfil').submit(function(e){
		e.preventDefault();
		if (!confirm("Esta seguro de que desea guardar los datos?")) 
    		{	return false; }
    	else { 
    		var data = new FormData(this);
    		$.ajax({
    			type: 'POST',
    			url: '../inc/update_registro.php',
    			data: data,
    			contentType: false,
    			dataType: "json",
    			cache: false,
    			processData: false,
    			success: function(data){
    				if (data.success){
    					location.reload();
    					
    				}else{
    					alert('Error '+data.error);	
    				}
    			    	
    			}          
    		});
    	}
	});	
	
	$('.cerrarSesion').click(function(e){
    	e.preventDefault();
    	$.post('../inc/salir.php');
    	window.location.reload();	
    });
});
</script>
</body>

</html>