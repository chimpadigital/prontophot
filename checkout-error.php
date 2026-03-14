<?php
include('header.php');

// Limpiar el carrito y sesiones relacionadas
if (isset($_SESSION['pronto']['cart'])) {
    unset($_SESSION['pronto']['cart']);
}
if (isset($_SESSION['prontoFront']['envio'])) {
    unset($_SESSION['prontoFront']['envio']);
}
if (isset($_SESSION['prontoFront']['valor'])) {
    unset($_SESSION['prontoFront']['valor']);
}
if (isset($_SESSION['prontoFront']['monto'])) {
    unset($_SESSION['prontoFront']['monto']);
}
if (isset($_SESSION['archivo'])) {
    unset($_SESSION['archivo']);
}

// Obtener datos del error desde la URL
$plataforma = isset($_GET['plataforma']) ? htmlspecialchars($_GET['plataforma']) : 'Desconocida';
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars(urldecode($_GET['mensaje'])) : 'Error desconocido al procesar el pago';
$codigo = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card w-100 border-0">
                <div class="card-header bg-danger text-white text-center py-4">
                    <i class="fa fa-exclamation-triangle" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 mb-0">Error en el Pago</h3>
                </div>
                <div class="card-body text-center py-5">
                    <h5 class="mb-4">No se pudo completar tu pedido</h5>

                    <div class="alert alert-danger mb-4 d-none">
                        <p class="mb-2"><strong>Plataforma:</strong> <?php echo $plataforma; ?></p>
                        <?php if ($codigo): ?>
                        <p class="mb-2"><strong>Código:</strong> <?php echo $codigo; ?></p>
                        <?php endif; ?>
                        <hr>
                        <p class="mb-0"><strong>Detalle:</strong><br><?php echo $mensaje; ?></p>
                    </div>

                    <div class="alert alert-info d-none">
                        <i class="fa fa-info-circle mr-2"></i>
                        <strong>Tu carrito ha sido vaciado.</strong><br>
                        <small>Por seguridad, hemos limpiado los datos de tu pedido.</small>
                    </div>

                    <h6 class="d-none mt-4 mb-3">¿Qué puedes hacer?</h6>
                    <ul class="d-none list-unstyled text-left mb-4">
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Verifica que tus datos de pago sean correctos</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Asegúrate de tener fondos suficientes</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Intenta con otro método de pago</li>
                        <li class="mb-2"><i class="fa fa-check text-success mr-2"></i>Contacta a tu banco si el problema persiste</li>
                    </ul>

                    <div class="mt-4">
                        <a href="/tienda.php" class="btn btn-primary btn-lg mr-2 mb-2">
                            <i class="fa fa-shopping-cart mr-2"></i>Volver a Comprar
                        </a>
                        <a href="contacto" class="btn btn-outline-secondary btn-lg mb-2">
                            <i class="fa fa-envelope mr-2"></i>Contactar Soporte
                        </a>
                    </div>

                    <div class="mt-4 pt-4 border-top d-none">
                        <p class="text-muted mb-0">
                            <small>
                                Si necesitas ayuda, contáctanos por WhatsApp al
                                <a href="https://wa.me/542216976559?text=Tuve%20un%20problema%20con%20mi%20pago" target="_blank">
                                    <strong>+54 221 697 6559</strong>
                                </a>
                            </small>
                        </p>
                    </div>
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
                    <a href="https://es-la.facebook.com/pg/Prontophot/about/?ref=page_internal"><svg
                            xmlns="http://www.w3.org/2000/svg" width="17.055" height="31.843"
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
    <a class="d-md-none d-lg-none d-block text-center wp" href="https://api.whatsapp.com/send?phone=+5492216784142&amp;text=Buenos%20días,%20quiero%20mas%20info%20">
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
    <a class="d-xs-none d-sm-none d-md-block text-center wp" target="_blank" href="https://wa.me/542216976559?&amp;text=Buenos%20días,%20quiero%20mas%20info">
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="assets/js/starter.js"></script>
<script>
    // Look for .hamburger
    var hamburger = document.querySelector(".hamburger");
    if (hamburger) {
        // On click
        hamburger.addEventListener("click", function() {
            // Toggle class "is-active"
            hamburger.classList.toggle("is-active");
        });
    }
</script>
</body>
</html>
