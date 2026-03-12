<?php session_start();
include 'inc/seguridad.inc.php';
//define('URL_SITIO','/photo/');
if (isset($_SESSION['prontoFront']['token'])) {
    $val=verificarToken($_SESSION['prontoFront']['token'], 'Pronto');
    if (!$val->success) {
        unset($_SESSION['prontoFront']['token']);
        unset($_SESSION['prontoFront']['idcliente']);
    }else{
        $_SESSION['prontoFront']['idcliente']=$val->id;
    }
}else{
    unset($_SESSION['prontoFront']['token']);
    unset($_SESSION['prontoFront']['idcliente']);
}
include 'conexion/conectar.inc.php';
include_once 'inc/config.inc.php';
global $conectar;

// Obtener topbar
$queryTopbar = "SELECT * FROM topbar WHERE id=1 AND activo=1 LIMIT 1";
$resultTopbar = $conectar->query($queryTopbar);
$topbarData = $resultTopbar && $resultTopbar->num_rows > 0 ? $resultTopbar->fetch_assoc() : null;
?>
<!doctype html>
<html lang="es">

<head>
	<base href="<?php echo URL_SITIO; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css?v=14">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link href="node_modules/hamburgers/dist/hamburgers.css" rel="stylesheet">

    <!--STEPS CSS-->
    <link rel="stylesheet" href="node_modules/bootstrap-steps/dist/bootstrap-steps.css" />

    <!--OWL CAROUSEL-->
    <link rel="stylesheet" href="node_modules/owl.carousel/dist/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="node_modules/owl.carousel/dist/assets/owl.theme.default.min.css" />  

    <title>Prontophot</title>
    
    
    
</head>

<body>
    <!-- Top Bar -->
    <?php if ($topbarData): ?>
    <div class="top-bar-pronto bg-white fw-bold text-dark text-center py-2">
        <?php if (!empty($topbarData['link'])): ?>
            <a href="<?php echo htmlspecialchars($topbarData['link']); ?>" class="fw-bold text-dark text-decoration-none">
                <?php echo htmlspecialchars($topbarData['texto']); ?>
            </a>
        <?php else: ?>
            <?php echo htmlspecialchars($topbarData['texto']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <!-- Modal Iniciar Sesion -->
    <div class="modal fade" id="iniciar-sesion" tabindex="-1" aria-labelledby="LoginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-center px-5 py-3">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-danger text-bold" id="exampleModalLabel">Iniciar Sesión</h5>
                    <form class="my-3 iniciar-sesion" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" name="email" id="login-email"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Contraseña</label>
                            <input type="password" class="form-control" name="passwd" id="login-pass">
                        </div>

                        <button type="submit" class="btn bg-danger text-white btn-bg-red">Iniciar Sesion</button>
                    </form>
                    <a class="font-italic mt-3" href data-target="#modal-recuperar-clave" data-dismiss="modal" data-toggle="modal">Recuperar contrañesa</a>
                </div>
                <!-- 
                <div class="modal-footer flex-column my-3">
                    <h5 class="modal-title text-bold">Inicia sesión con</h5>
                    <div class="btn-social-login">
                        <button type="button" class="btn btn-sm bg-light rounded mx-1 mx-md-3"><svg
                                xmlns="http://www.w3.org/2000/svg" width="17.986" height="33.583"
                                viewBox="0 0 17.986 33.583">
                                <path id="Icon_awesome-facebook-f" data-name="Icon awesome-facebook-f"
                                    d="M18.417,18.89l.933-6.078H13.518V8.869c0-1.663.815-3.283,3.426-3.283H19.6V.411A32.331,32.331,0,0,0,14.89,0c-4.8,0-7.942,2.911-7.942,8.181v4.632H1.609V18.89H6.948V33.583h6.57V18.89Z"
                                    transform="translate(-1.609)" />
                            </svg>
                        </button>
                        <button type="button" class="btn btn-sm bg-light rounded mx-1 mx-md-3"><svg
                                xmlns="http://www.w3.org/2000/svg" width="25.507" height="19.131"
                                viewBox="0 0 25.507 19.131">
                                <path id="Icon_simple-gmail" data-name="Icon simple-gmail"
                                    d="M25.507,6.094V22.036a1.566,1.566,0,0,1-1.594,1.594H22.319V9.163l-9.565,6.869L3.188,9.163V23.631H1.594A1.565,1.565,0,0,1,0,22.036V6.094A1.567,1.567,0,0,1,1.594,4.5h.531l10.628,7.705L23.382,4.5h.531a1.567,1.567,0,0,1,1.594,1.594Z"
                                    transform="translate(0 -4.5)" />
                            </svg>
                        </button>
                    </div>
                </div>
                 -->
                <div class="modal-footer flex-column">
                    <p class="">Si no tenes cuenta, <a href="" class="font-italic" data-target="#modal-registrarse" data-dismiss="modal" data-toggle="modal"><br class="d-block d-lg-none">Registrate aqui.</a></p>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Registrarse -->
    <div class="modal fade" id="modal-registrarse" tabindex="-1" aria-labelledby="RegisterLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content px-5 py-3">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-danger text-bold text-center mb-4" id="exampleModalLabel">Registrate
                    </h5>
                    <form class="iniciar-sesion" id="nuevoRegistro" method="post" novalidate>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" id="nombre" required>
                                <small class="form-text text-danger d-none" id="error-nombre">El nombre es obligatorio</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="apellido">Apellido *</label>
                                <input type="text" class="form-control" name="apellido" id="apellido" required>
                                <small class="form-text text-danger d-none" id="error-apellido">El apellido es obligatorio</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="DNI">DNI *</label>
                                <input type="text" class="form-control" name="dni" id="DNI" required>
                                <small class="form-text text-danger d-none" id="error-DNI">El DNI es obligatorio y debe ser válido</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="CUIL">CUIL</label>
                                <input type="text" class="form-control" name="cuil" id="CUIL" placeholder="XX-XXXXXXXX-X">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Email *</label>
                                <input type="email" class="form-control" name="email" id="inputEmail4" required>
                                <small class="form-text text-danger d-none" id="error-inputEmail4">Ingrese un email válido</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="contraseña">Contraseña *</label>
                                <input type="password" class="form-control pass" name="passwd" id="reg-pass1" required minlength="6">
                                <small class="form-text text-danger d-none" id="error-reg-pass1">La contraseña debe tener al menos 6 caracteres</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="contraseña_check">Confirmar Contraseña *</label>
                                <input type="password" class="form-control pass" id="reg-pass2" required>
                                <small class="form-text text-danger d-none" id="error-reg-pass2">Las contraseñas no coinciden</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Dirección">Dirección *</label>
                                <input type="text" class="form-control" name="direccion" id="Dirección" required>
                                <small class="form-text text-danger d-none" id="error-Dirección">La dirección es obligatoria</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Altura">Altura *</label>
                                <input type="number" class="form-control" name="altura" id="Altura" required min="1" max="99999">
                                <small class="form-text text-danger d-none" id="error-Altura">La altura es obligatoria</small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Provincia">Provincia *</label>
                                <select id="Provincia" name="provincia" class="form-control" required>
                                    <option value="">Seleccione...</option>
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
                                <small class="form-text text-danger d-none" id="error-Provincia">Debe seleccionar una provincia</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Ciudad">Ciudad *</label>
                                <input type="text" class="form-control" name="ciudad" id="Ciudad" required>
                                <small class="form-text text-danger d-none" id="error-Ciudad">La ciudad es obligatoria</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="CP">CP *</label>
                                <input type="text" class="form-control" name="cp" id="CP" required pattern="[0-9]{4,8}">
                                <small class="form-text text-danger d-none" id="error-CP">El código postal debe tener entre 4 y 8 dígitos</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="Telefono">Telefono</label>
                                <input type="text" class="form-control" name="telefono" id="Telefono" placeholder="351 | 6120787">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn bg-danger text-white btn-bg-red">Registrarme</button>
                        </div>


                    </form>
                </div>

                <div class="modal-footer border-0">

                </div>

            </div>
        </div>
    </div>
	<!-- Modal recuperar clave -->
	<div class="modal fade" id="modal-recuperar-clave" tabindex="-1" aria-labelledby="UpdatePassLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content px-5 py-3">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-danger text-bold text-center mb-4" id="exampleModalLabel">Recuperar contraseña</h5>
                    	<div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="contraseña">Ingrese su email</label>
                                <input type="email" class="form-control " id="recuperar-clave">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                        	<div class="respuesta"></div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn bg-danger text-white btn-bg-red btn-recuperar">Enviar</button>
                        </div>


                </div>

                <div class="modal-footer border-0">

                </div>

            </div>
        </div>
    </div>
    <!-- Modal Cambiar Contraseña -->
    <div class="modal fade" id="modal-actualizar-contraseña" tabindex="-1" aria-labelledby="UpdatePassLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content px-5 py-3">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title text-danger text-bold text-center mb-4" id="exampleModalLabel">Actualizar contraseña</h5>
                    <form id="act-form" method="post">
						<input type="hidden" name="code" id="act-code">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="contraseña">Nueva Contraseña</label>
                                <input type="password" class="form-control " name="pass" id="act-pass1">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="contraseña_check">Confirmar Contraseña</label>
                                <input type="password" class="form-control " name="pass2" id="act-pass2">
                            </div>
                        </div>
						
                        <div class="text-center mt-4">
                            <button type="submit" class="btn bg-danger text-white btn-bg-red">Actualizar</button>
                        </div>


                    </form>
                </div>

                <div class="modal-footer border-0">

                </div>

            </div>
        </div>
    </div>

    <!-- NAV -->
    <div class="contenedor-top-bar d-none d-lg-block">
        <div class="container">
            <nav>
                <ul class="nav justify-content-end">
                	<?php
                	if (isset($_SESSION['prontoFront']['token'])) {
                	    $seg=verificarToken($_SESSION['prontoFront']['token'], 'Pronto');
                	    if ($seg->success) { ?>
                	    <li class="nav-item">
                	    	<a href="usuario/index.php" class="nav-link"><button type="button" class="btn btn-bg-white " >Mi Cuenta</button></a>
                	    </li>
                	    <?php if($seg->nivel=='1'){?>
                	    <li class="nav-item">
                	    	<a href="admin" class="nav-link"><button type="button" class="btn btn-bg-white " >Admin</button></a>
                	    </li>
                	    <?php } ?>
                	    <li class="nav-link active">
                	        <button type="button" class="btn btn-bg-white cerrarSesion">Cerrar Sesión <i class="fa fa-sign-out" aria-hidden="true"></i>
                	        </button>
                	    </li>
                	    <?php     
                	    }
                	}else{ ?>
                    <li class="nav-link active">
                        <button type="button" data-toggle="modal" data-target="#iniciar-sesion" class="btn btn-bg-white ">Iniciar Sesión</button>
                    </li>
                    <li class="nav-link">
                        <button type="button" data-toggle="modal" data-target="#modal-registrarse" class="btn border-white text-white ">Crear cuenta</button>
                    </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a id="carrito-tienda" class="nav-link" href="mi-pedido"><button type="button" class="btn text-white btn-cart position-relative pr-0"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16"><path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" /></svg><span id="cartContador" class="badge badge-dark">9</span></button></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="container py-4 d-none d-lg-block">
        <div class="row">
            <div class="col-md-12 d-flex flex-row align-items-center justify-content-between">
                <a href="index.php"><img src="assets/img/logo-pronto-negro.svg" alt=""></a>
                <nav class="navbar navbar-desktop navbar-expand-md navbar-light">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav nav-home">
                            <li class="nav-item">
                                <a class="nav-link text-medium" href="index.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-medium" href="quienes-somos">Quiénes
                                    Somos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-medium" href="tienda">Tienda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-medium" href="contacto">Contacto</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <a class="d-none d-md-block" href="revelar-fotos"><button
                        class="btn btn-warning btn-cargar-producto">Imprimir Fotos <svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-printer ml-2" viewBox="0 0 16 16">
                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                            <path
                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                        </svg></button></a>

            </div>

        </div>
    </div>
    <!-- END NAV -->

    <!-- NAV MOBILE -->
    <div class="contenedor-top-bar d-block d-lg-none">
        <div class="container">
            <nav class="navbar navbar-mobile navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="index.php"><img class="logo-blanco responsive" src="assets/img/logo-pronto-white.svg" alt=""></a>
                <a class="nav-link active cerrar-sesion" href="mi-pedido"><button type="button" class="btn text-white btn-cart position-relative"><svg xmlns="http://www.w3.org/2000/svg"  width="30" height="30" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16"><path </svg></button></a>

                <button class="hamburger hamburger--collapse" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="hamburger-box mt-2">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="/">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="quienes-somos">Quiénes Somos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="tienda">Tienda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="revelar-fotos">Imprimir Fotos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="contacto">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="" data-toggle="modal"
                                data-target="#iniciar-sesion">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="" data-toggle="modal"
                                data-target="#modal-registrarse">Crear Cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-medium" href="#">Salir</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!-- END NAV MOBILE -->