<?php
if(session_status() === PHP_SESSION_NONE) session_start();
include '../inc/seguridad.inc.php';
if (isset($_SESSION['prontoFront']['token'])) {
    $token=$_SESSION['prontoFront']['token'];
    $key="Pronto";
    $ingresar=verificarToken($token, $key);
    if ($ingresar->success==false) {
        header("Location: ../");
        exit();
    }
}else{
    header("Location: ../");
    exit();
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!--STEPS CSS-->
    <link rel="stylesheet" href="../node_modules/bootstrap-steps/dist/bootstrap-steps.css" />

    <!--HAMBURGUER ANIMATION-->
    <link href="../node_modules/hamburgers/dist/hamburgers.css" rel="stylesheet">

    <!--OWL CAROUSEL-->
    <link rel="stylesheet" href="../node_modules/owl.carousel/dist/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="../node_modules/owl.carousel/dist/assets/owl.theme.default.min.css" />

    <title>Prontophot</title>
    
</head>

<body>
    <!-- NAV -->
    <div class="contenedor-top-bar d-none d-md-block">
        <div class="container">
            <nav>
                <ul class="nav justify-content-end">
                    <li class="nav-link active">
                	        <button type="button" class="btn btn-bg-white cerrarSesion">Cerrar Sesión</button>
                	    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- END NAV -->

    <!-- NAV MOBILE -->
    <div class="contenedor-top-bar d-block d-md-none">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="index.php">
                	<img class="logo-blanco responsive" src="../assets/img/logo-pronto-white.svg" alt="">
                </a>
                <a class="nav-link active">
                	<button type="button" class="btn btn-bg-white btn-sm cerrarSesion">Salir</button>
                </a>
                <button class="hamburger hamburger--collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="hamburger-box mt-1">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>            
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="misCompras.php">Mis Compras <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="miPerfil.php">Mi Perfil</a>
                        </li>
                                             
                    </ul>                    
                </div>
            </nav>
        </div>
    </div>
    <!-- END NAV MOBILE -->

    