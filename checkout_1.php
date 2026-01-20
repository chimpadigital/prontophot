<?php include ('header.php'); 

if (isset($_SESSION['prontoFront']['token'])) {
    $dato=verificarToken($_SESSION['prontoFront']['token'], 'Pronto');
    if ($dato->success) {
        $id=$dato->id;
        $cliente=$conectar->query("SELECT * FROM clientes WHERE id='$id'");
        $rowc=$cliente->fetch_assoc();
    }else{
        
    }
}
?>

<div class="">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-bold font-monument titulo-portada">Finalizar pedido</h2>
            </div>
        </div>
    </div>
</div>

<div id="contenido-paso-2">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h5 class="titulo-tabs-user">Selecciona tu método de envío</h5>
            </div>
        </div>
        <form class="formCheckout " action="checkout-pago" method="post" >
        <!-- FORM METODO ENVIO -->
        <div class="row metodo-envio">
            <div class="col-md-6 ">
                <div class="mx-1 shadow-sm p-3 p-md-5">
                    <p class="text-bold text-danger">Retiro gratis por Sucursal</p>
                    <div class="form-check mb-3">
                        <input class="form-check-input retiroSucursal" type="radio" name="entrega"  id="sucursal1" value="suc1" checked>
                        <label class="form-check-label d-flex flex-row" for="sucursal1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                            <div>
                                <p class="d-inline-block text-bold m-0">Sucursal 1</p>
                                <span class="d-block">Calle 12 N°1108 e/55 y 56</span>
                                <span class="d-block">WhatsApp 2216784142</span>
                            </div>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input retiroSucursal" type="radio" name="entrega" id="sucursal2" value="suc2">
                        <label class="form-check-label d-flex flex-row" for="sucursal2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                            <div>
                                <p class="d-inline-block text-bold m-0">Sucursal 2</p>
                                <span class="d-block">Cantilo N° 173 e/ 13ª y 13b, City Bell</span>
                                <span class="d-block">WhatsApp 2213581837</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div id="metodo-envio" class="col-md-6 my-3 my-md-0 ">
                <div class="mx-1 shadow-sm p-3 p-md-5">
                    <p class="text-bold m-0 text-danger">Envío a Domicilio</p>
                    <span class="text-muted descripcion-pequeña">(disponible para pedidos superiores a $1000)*</span>
                    <div class="form-check mb-3 mt-3 contenedor-input">
                        <input class="form-check-input envioDomicilio" data-toggle='collapse' data-target='#collapsediv1' type="radio" aria-expanded="false" name="entrega" id="CascoUrbano" value="urbano">
                        <label class="form-check-label d-flex flex-row" for="CascoUrbano">
                            <div>
                                <p class="d-inline-block m-0">Casco Urbano La Plata</p>
                                <span class="d-block text-bold">$250</span>
                            </div>
                        </label>
                    </div>

                    <div class="form-check mb-3 contenedor-input">
                        <input class="form-check-input envioDomicilio" data-toggle='collapse' data-target='#collapsediv1' type="radio" aria-expanded="false" name="entrega" id="alRecibir" value="recibir">
                        <label class="form-check-label d-flex flex-row" for="alRecibir">
                            <div>
                                <p class="d-inline-block m-0">Abonas al recibir/Correo <br>(fuera del casco urbano)</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div id='collapsediv1' class="row metodo-envio collapse in">
            <div class="col-md-12">
                <h5 class="titulo-tabs-user">Datos de Envío</h5>
            </div>
            <div class="col-md-6 d-block d-md-flex">
                <div class="shadow-sm p-5 w-100">
                    <?php if (isset($_SESSION['prontoFront']['token'])) { ?>
                    <div class="form-check form-check-inline d-flex align-items-baseline">
                        <input class="form-check-input" type="radio" name="envio" id="inlineRadio1" value="domicilio">
                        <label class="form-check-label ml-0 ml-md-5" for="inlineRadio1">
                        	<span class="text-bold">Mi direccion</span>
                            <h5 class="mt-3">Direccion</h5>
                            <p class="text-muted"><?php echo $rowc['direccion'].', '.$rowc['ciudad']?></p>
                            <h5>Provincia</h5>
                            <p class="text-muted"><?php echo $rowc['provincia'];?></p>
                            <h5>CP</h5>
                            <p class="text-muted"><?php echo $rowc['cp']?></p>
                            <h5>Telefono / Celular</h5>
                            <p class="text-muted"><?php echo $rowc['telefono'];?></p>
                        </label>
                    </div>
                    <div class="ml-0 ml-md-5 pl-0 pl-md-3">
                    	<label for="validationTextarea">Observaciones</label>
                        <textarea class="form-control" name="observaciones" placeholder="" rows="5"></textarea>
                    </div>
                    <?php }else{?>
                    <button type="button" data-toggle="modal" data-target="#iniciar-sesion" class="btn btn-outline-primary btn-bg-white ">Iniciar Sesión</button>
                    <?php }?>
                </div>
            </div>

            <div class="col-md-6 mt-4 mt-md-0">
                <div class="shadow-sm p-5">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="envio" id="inlineRadio2" value="regalo">
                        <label class="form-check-label ml-0 ml-md-5" for="inlineRadio2">Enviar como
                            Regalo</label>
                    </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control form-rosa" name="nombre">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="apellido">Apellido</label>
                                <input type="text" class="form-control form-rosa" name="apellido">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="DNI">DNI</label>
                                <input type="text" class="form-control form-rosa" name="DNI">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="Dirección">Dirección</label>
                                <input type="text" class="form-control form-rosa" name="direccion">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Email</label>
                                <input type="email" class="form-control form-rosa" name="email">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="Provincia">Provincia</label>
                                <select name="provincia" class="form-control form-rosa">
                                    <option selected>Seleccione...</option>
                                    <option value="Buenos Aires">Buenos Aires</option>
                                    <option value="CABA">CABA</option>
                                    <option value="Catamarca">Catamarca</option>
                                    <option value="Chaco">Chaco</option>
                                    <option value="Chubut">Chubut</option>
                                    <option value="Cordoba">Cordoba</option>
                                    <option value="Corrientes">Corrientes</option>
                                    <option value="Entre Rios">Entre Rios</option>
                                    <option value="Formosa">Formosa</option>
                                    <option value="Jujuy">Jujuy</option>
                                    <option value="La Pampa">La Pampa</option>
                                    <option value="La Rioja">La Rioja</option>
                                    <option value="Mendoza">Mendoza</option>
                                    <option value="Misiones">Misiones</option>
                                    <option value="Neuquen">Neuquen</option>
                                    <option value="Rio Negro">Rio Negro</option>
                                    <option value="Salta">Salta</option>
                                    <option value="San Juan">San Juan</option>
                                    <option value="San Luis">San Luis</option>
                                    <option value="Santa Cruz">Santa Cruz</option>
                                    <option value="Santa Fe">Santa Fe</option>
                                    <option value="Santiago del Estero">Santiago del Estero</option>
                                    <option value="Tierra del Fuego">Tierra del Fuego</option>
                                    <option value="Tucuman">Tucuman</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="Ciudad">Ciudad</label>
                                <input type="text" class="form-control form-rosa" name="ciudad">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="CP">CP</label>
                                <input type="text" class="form-control form-rosa" name="CP">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 position-relative">
                                
                                <label for="Telefono">Telefono</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control form-rosa number" name="telefono" placeholder="Teléfono sin 15">
                                </div>
                                
                            </div>
                            <div class="form-group col-md-12 position-relative">
                                
                                <label for="Celular">Celular</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control form-rosa number" name="celular" placeholder="Teléfono sin 15">
                                </div>
                                
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12 p-md-0">
                <div class="d-block d-md-flex align-items-center justify-content-center my-5">
                    <a href="mi-pedido" class="btn btn-border-yellow text-black ">Atras</a>
                    <button class="btn btn-warning btn-cargar-producto mx-0 mx-md-3 my-3 my-md-0 " type="submit">Siguiente</button>
                </div>
            </div>

        </div>
        </form>
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
                    <a href="https://es-la.facebook.com/pg/Prontophot/about/?ref=page_internal"><svg xmlns="http://www.w3.org/2000/svg" width="17.055" height="31.843"
                            viewBox="0 0 17.055 31.843">
                            <g id="Grupo_325" data-name="Grupo 325" transform="translate(402 -2245)">
                                <path id="Icon_awesome-facebook-f" data-name="Icon awesome-facebook-f"
                                    d="M17.547,17.912l.884-5.763H12.9V8.409c0-1.577.772-3.113,3.249-3.113h2.514V.389A30.656,30.656,0,0,0,14.2,0c-4.554,0-7.53,2.76-7.53,7.757v4.392H1.609v5.763H6.671V31.843H12.9V17.912Z"
                                    transform="translate(-403.609 2245)" fill="#fff" />
                            </g>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/prontophotlp/?igshid=16xmcwskod733" class="ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31.38" height="31.373"
                            viewBox="0 0 31.38 31.373">
                            <g id="Grupo_326" data-name="Grupo 326" transform="translate(402 -2298)">
                                <path id="Icon_awesome-instagram" data-name="Icon awesome-instagram"
                                    d="M15.688,9.881a8.044,8.044,0,1,0,8.044,8.044A8.031,8.031,0,0,0,15.688,9.881Zm0,13.273a5.229,5.229,0,1,1,5.229-5.229,5.239,5.239,0,0,1-5.229,5.229Zm10.249-13.6a1.876,1.876,0,1,1-1.876-1.876A1.872,1.872,0,0,1,25.937,9.552Zm5.327,1.9a9.285,9.285,0,0,0-2.534-6.574,9.346,9.346,0,0,0-6.574-2.534C19.567,2.2,11.8,2.2,9.213,2.348A9.332,9.332,0,0,0,2.639,4.875,9.315,9.315,0,0,0,.1,11.449C-.042,14.039-.042,21.8.1,24.393a9.285,9.285,0,0,0,2.534,6.574A9.358,9.358,0,0,0,9.213,33.5c2.59.147,10.354.147,12.944,0a9.285,9.285,0,0,0,6.574-2.534,9.346,9.346,0,0,0,2.534-6.574c.147-2.59.147-10.347,0-12.937ZM27.919,27.172a5.294,5.294,0,0,1-2.982,2.982c-2.065.819-6.966.63-9.248.63s-7.19.182-9.248-.63a5.294,5.294,0,0,1-2.982-2.982c-.819-2.065-.63-6.966-.63-9.248s-.182-7.19.63-9.248A5.294,5.294,0,0,1,6.441,5.694c2.065-.819,6.966-.63,9.248-.63s7.19-.182,9.248.63a5.294,5.294,0,0,1,2.982,2.982c.819,2.065.63,6.966.63,9.248S28.738,25.114,27.919,27.172Z"
                                    transform="translate(-401.995 2295.762)" fill="#fff" />
                            </g>
                        </svg>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <a class="d-md-none d-lg-none d-block text-center wp" href="https://wa.me/542216976559? &amp;text=Buenos%20días,%20quiero%20mas%20info%20">
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

<!--SCRIPT QUE COLAPSA LA OPCION DE TRANSFERENCIA BANCARIA-->
<script>
$("#collapsediv1").collapse({
    "toggle": false,
    'parent': '#metodo-envio'
});

$(".retiroSucursal").click(function() {
    $("#collapsediv1").collapse('hide');
})

// Cuando hacemos click verifica si tiene la clase "show" y si es asi, no colapsa nada. Caso contrario colapsa el div.
$('.contenedor-input > input[data-toggle="collapse"]').click(function(e) {
    target = $("#collapsediv1");
    if ($(target).hasClass('show')) {
        // e.preventDefault(); Esto en el caso que el link lleve a alguna seccion.
        e.stopPropagation()
    }
})
</script>

<script src="assets/js/starter.js"></script>
<script>
	$(function(){
		$('#inlineRadio2').click(function(){
			$(".form-rosa").attr("required", "true");
			$(".form-rosa").prop('required',true);
		});
		$('#inlineRadio1').click(function(){
			$(".form-rosa").attr("required", "false");
			$(".form-rosa").prop('required',false);
		});
	});
</script>
</body>

</html>