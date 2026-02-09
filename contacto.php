<?php include('header.php'); ?>

<div class="bg-gris-oscuro py-0 py-md-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <h4 class="font-monument text-bold text-white mb-4">Contactanos</h4>
                <div class="mb-3">
                    <div class="d-flex flex-row text-white" for="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#DA0000"
                            class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                            <path
                                d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                        </svg>
                        <div>
                            <p class="d-inline-block text-bold m-0">Sucursal 1</p>
                            <span class="d-block">Calle 12 N°1108 e/55 y 56</span>
                            <span class="d-block">WhatsApp 2216784142</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex flex-row text-white" for="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#DA0000"
                            class="bi bi-geo-alt-fill text-danger mt-1 mr-1 mr-md-2" viewBox="0 0 16 16">
                            <path
                                d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                        </svg>
                        <div>
                            <p class="d-inline-block text-bold m-0">Sucursal 2</p>
                            <span class="d-block">Cantilo N° 173 e/ 13ª y 13b, City Bell</span>
                            <span class="d-block">WhatsApp 2213581837</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex flex-row text-white" for="">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mt-1 mr-1 mr-md-2">
                            <path d="M4.252 10C4.08401 10.6534 3.99934 11.3254 4 12C4 12.69 4.088 13.36 4.252 14H7.1C6.96633 12.67 6.96633 11.33 7.1 10H4.252ZM5.07 8H7.416C7.682 6.783 8.066 5.693 8.537 4.786C7.08536 5.48568 5.87746 6.60543 5.07 8ZM19.748 10H16.9C17.0337 11.33 17.0337 12.67 16.9 14H19.748C20.0845 12.6879 20.0845 11.3121 19.748 10ZM18.93 8C18.1225 6.60543 16.9146 5.48568 15.463 4.786C15.935 5.693 16.318 6.783 16.584 8H18.93ZM9.112 10C9.03757 10.6641 9.00018 11.3318 9 12C9 12.685 9.038 13.355 9.112 14H14.888C15.0383 12.6709 15.0383 11.3291 14.888 10H9.112ZM9.47 8H14.53C14.3477 7.24839 14.0852 6.51854 13.747 5.823C13.119 4.568 12.447 4 12 4C11.553 4 10.881 4.568 10.253 5.823C9.938 6.455 9.673 7.19 9.47 8ZM5.07 16C5.87746 17.3946 7.08536 18.5143 8.537 19.214C8.065 18.307 7.682 17.217 7.416 16H5.07ZM18.93 16H16.584C16.318 17.217 15.934 18.307 15.463 19.214C16.9146 18.5143 18.1225 17.3946 18.93 16ZM9.47 16C9.673 16.81 9.938 17.545 10.253 18.177C10.881 19.432 11.553 20 12 20C12.447 20 13.119 19.432 13.747 18.177C14.062 17.545 14.327 16.81 14.53 16H9.47ZM12 22C6.477 22 2 17.523 2 12C2 6.477 6.477 2 12 2C17.523 2 22 6.477 22 12C22 17.523 17.523 22 12 22Z" fill="#DA0000" />
                        </svg>
                        <div>
                            <p class="d-inline-block text-bold m-0">Ventas Web</p>
                            <span class="d-block">WhatsApp 2216976559</span>
                            <span class="d-block">Mail prontophotenlinea@gmail.com</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 columna-form-contacto">
                <hr class="d-block d-md-none border mt-5">
                <form id="consulta" action="inc/enviar_contacto.php" method="post" class="form-transparente ml-0 ml-md-5 mt-5 mt-md-0 consultaForm">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="nombre">Nombre y Apellido</label>
                            <input type="text" class="form-control form-transparente" name="nombre">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="mail">Email</label>
                            <input type="email" class="form-control form-transparente" name="email">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="Telefono">Telefono/Celular</label>
                            <input type="text" class="form-control form-transparente" name="telefono" placeholder="351 | 6120787">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="validationTextarea">Consulta</label>
                            <textarea class="form-control form-transparente" name="consulta" placeholder="" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <button class="btn btn-warning btn-home-amarillo btn-consulta my-3 my-md-0" type="submit">ENVIAR</button>
                    </div>
                    <div class="form-row" id="respuesta"></div>
                    <div class="form-row mt-2">
                        <div class="g-recaptcha" data-sitekey="6LcHpIQcAAAAAJuF0s_iKfn2I8R7_x9SKMewRPx3"></div>
                    </div>
                </form>
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
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
    <a class="d-xs-none d-sm-none d-md-block text-center wp" target="_blank" href="https://wa.me/542216976559? &amp;text=Buenos%20días,%20quiero%20mas%20info">
        <svg fill="white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"></path>
        </svg>
    </a>
</footer>

<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous">
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
<script>
    $(function() {
        $('.consultaForm').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $('.btn-consulta').html('enviando');
            $('.btn-consulta').prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: 'inc/enviar_contacto.php',
                data: data,
                contentType: false,
                dataType: "json",
                cache: false,
                processData: false,
                success: function(data) {
                    $('.btn-consulta').html('ENVIAR');
                    $('.btn-consulta').prop('disabled', false);

                    if (data.success) {
                        $('#respuesta').html('<p class="alert alert-success">' + data.msg + '</p>');
                        setTimeout(function() {
                            $('#respuesta').html('')
                        }, 4000);
                        $('.consultaForm')[0].reset();

                    } else {
                        $('#respuesta').html('<p class="alert alert-danger">' + data.msg + '</p>');
                        setTimeout(function() {
                            $('#respuesta').html('')
                        }, 4000);
                    }

                }
            });
        });
    });
</script>
</body>

</html>