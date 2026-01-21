<?php include ('header_usuario.php'); ?>
<?php 
include_once '../conexion/conectar.inc.php';
global $conectar;
if (isset($_POST['tipoenvio'])) {
    $costoenvio=0;
    $enviotag='';
    $_SESSION['prontoFront']['envio']['tipo']=$_POST['tipoenvio'];
    $_SESSION['prontoFront']['envio']['envio']=$_POST['envio']??'';
    
    if ($_POST['tipoenvio']=='urbano') {
        $costoenvio=250;
        $enviotag='$ 250';
    }else{
        if($_POST['tipoenvio']=='recibir'){
            $enviotag='Abona al recibir';
            
        }
        $costoenvio=0;
    }
    $_SESSION['prontoFront']['envio']['costo']=$costoenvio;
    $_SESSION['prontoFront']['envio']['nombre']=$_POST['nombre'];
    $_SESSION['prontoFront']['envio']['apellido']=$_POST['apellido'];
    $_SESSION['prontoFront']['envio']['dni']=$_POST['DNI'];
    $_SESSION['prontoFront']['envio']['direccion']=$_POST['direccion'];
    $_SESSION['prontoFront']['envio']['email']=$_POST['email'];
    $_SESSION['prontoFront']['envio']['provincia']=$_POST['provincia'];
    $_SESSION['prontoFront']['envio']['ciudad']=$_POST['ciudad'];
    $_SESSION['prontoFront']['envio']['cp']=$_POST['CP'];
    $_SESSION['prontoFront']['envio']['telefono']=$_POST['telefono'];
    $_SESSION['prontoFront']['envio']['celular']=$_POST['celular'];
    
    $costototal=0;
    foreach ($_SESSION['archivos'] as $key=>$impresion){
        $tamano=$impresion['tamano'];
        $cant=$impresion['cantidad'];
        
        $calc=$conectar->query("SELECT * FROM `impresiones` WHERE formato='$tamano' AND desde<='$cant' AND hasta>='$cant' ORDER BY id DESC LIMIT 1");
        $row=$calc->fetch_assoc();
        $p=$row['precio'];
        $idimp=$row['id'];
        $precio=($p * $cant);
        $costototal=$costototal+$precio;
        $_SESSION['archivos'][$key]['idimpresion']=$idimp;
    }
    $total=$costoenvio+$costototal;
    $_SESSION['prontoFront']['valor']=$total;
}else{
    header('Location: revelado-paso2');
    exit();
}
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
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
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
                            <a class="nav-link active" id="cargarProducto" href="index.php">Nuevo Revelado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="productosCargados" href="misCompras.php">Mis Compras</a>
                        </li>
                    </ul>
                    <!-- FIN TABS MIS COMPRAS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-12 col-steps p3">

                                    <div class="align-items-center d-flex d-md-none flex-column text-center">
                                        <svg id="paso-3" xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                            viewBox="0 0 33 30.236">
                                            <path id="Trazado_127" data-name="Trazado 127"
                                                d="M32.885,234.76a2.447,2.447,0,0,0-2.434-1.717l-.236.01a2.74,2.74,0,0,0-1.83.8l-3.646,3.608A2.557,2.557,0,0,0,22.6,236.3H17.455a10.551,10.551,0,0,1-2.813-.374A6.915,6.915,0,0,0,8.2,237.278a9.317,9.317,0,0,0-1.713,1.847,2.312,2.312,0,0,0-2.983.3L.181,242.872a.646.646,0,0,0,.012.909l2.673,2.637a.646.646,0,0,0,.908-.92l-2.219-2.188,2.881-2.991a1.021,1.021,0,0,1,1.446-.024l5.14,4.988a1.021,1.021,0,0,1,.02,1.446l-2.831,2.9-.729-.694a.646.646,0,1,0-.891.936l1.191,1.135a.647.647,0,0,0,.909-.017l3.277-3.362a2.316,2.316,0,0,0,.5-2.46l1.154-.134a14.09,14.09,0,0,1,1.62-.094h7.826c.12,0,.241-.005.361-.015l.75-.062a4.34,4.34,0,0,0,2.948-1.515l5.273-6.2a2.514,2.514,0,0,0,.488-2.385Zm-1.473,1.548-5.273,6.2a3.05,3.05,0,0,1-2.07,1.064l-.75.062c-.084.007-.169.011-.253.011H15.24a15.4,15.4,0,0,0-1.769.1l-1.869.218-4.135-3.987a8,8,0,0,1,1.549-1.7,5.6,5.6,0,0,1,5.281-1.106,11.837,11.837,0,0,0,3.158.421H22.6a1.272,1.272,0,0,1,.911.388,1.258,1.258,0,0,1,.329.632.637.637,0,0,0,.022.173,1.262,1.262,0,0,1-.276.863l-.02.025a1.259,1.259,0,0,1-.986.473H18.1a.646.646,0,1,0,0,1.293h4.477a2.545,2.545,0,0,0,1.995-.957l.02-.025a2.55,2.55,0,0,0,.561-1.592l4.143-4.1a1.454,1.454,0,0,1,.971-.426l.236-.009a1.15,1.15,0,0,1,1.146.809,1.228,1.228,0,0,1-.238,1.165Zm0,0"
                                                transform="translate(0 -220.951)" fill="#DA0000" />
                                            <path id="Trazado_128" data-name="Trazado 128"
                                                d="M67.485,394.566a1.75,1.75,0,1,0,1.751,1.75A1.752,1.752,0,0,0,67.485,394.566Zm0,2.208a.458.458,0,1,1,.458-.458A.458.458,0,0,1,67.485,396.774Zm0,0"
                                                transform="translate(-62.345 -374.148)" fill="#DA0000" />
                                            <path id="Trazado_129" data-name="Trazado 129"
                                                d="M288,372.451l-.033.008a.646.646,0,1,0,.3,1.258l.033-.008a.646.646,0,0,0-.3-1.258Zm0,0"
                                                transform="translate(-272.649 -353.157)" fill="#DA0000" />
                                            <path id="Trazado_130" data-name="Trazado 130"
                                                d="M88.279,505.122l-.033.008a.646.646,0,0,0,.3,1.258l.033-.008a.646.646,0,0,0-.3-1.259Zm0,0"
                                                transform="translate(-83.223 -478.987)" fill="#DA0000" />
                                            <path id="Trazado_131" data-name="Trazado 131"
                                                d="M241.06,12.264a6.841,6.841,0,0,0,0-13.682A6.771,6.771,0,0,0,236.839.039a6.841,6.841,0,0,0,4.221,12.225Zm-4.816-9.6a5.583,5.583,0,0,1,1.394-1.611A5.492,5.492,0,0,1,241.06-.125a5.546,5.546,0,1,1-4.816,2.792Zm0,0"
                                                transform="translate(-222.142 1.418)" fill="#DA0000" />
                                            <path id="Trazado_132" data-name="Trazado 132"
                                                d="M323.94,55.035a1.73,1.73,0,0,1-.829.21c-.436,0-.818-.514-.818-1.1a.646.646,0,1,0-1.293,0,2.358,2.358,0,0,0,1.6,2.321.646.646,0,0,0,1.29-.028,2.156,2.156,0,0,0,1.739-2.152,2.424,2.424,0,0,0-2.251-2.251c-.01,0-.959-.084-.959-.691a.956.956,0,0,1,.959-.958.875.875,0,0,1,.57.279c.083.083.092.092.092.44a.646.646,0,0,0,1.293,0,1.668,1.668,0,0,0-.47-1.352,2.274,2.274,0,0,0-.974-.584.646.646,0,0,0-1.291.038v.042a2.143,2.143,0,0,0-1.471,2.1c0,1.481,1.539,1.982,2.247,1.984.194.016.963.356.963.958A.782.782,0,0,1,323.94,55.035Zm0,0"
                                                transform="translate(-304.449 -45.982)" fill="#DA0000" />
                                        </svg>
                                        <span class="step-text text-bold text-danger">Paso 3</span>
                                        <span class="step-text text-bold step-title text-danger mb-3">Pago</span>
                                    </div>

                                    <ul class="steps">
                                        <li class="step step-success">
                                            <div class="step-content">
                                                <span class="step-text mb-3 d-none d-md-block text-bold">Paso 1</span>
                                                <span class="step-circle"></span>
                                                <div class="mt-3 mb-4 mb-md-0">
                                                    <svg class="" xmlns="http://www.w3.org/2000/svg" width="32.819"
                                                        height="30.336" viewBox="0 0 32.819 30.336">
                                                        <g id="paso-1" transform="translate(0 0)">
                                                            <g id="Grupo_132" data-name="Grupo 132"
                                                                transform="translate(0 0)">
                                                                <path id="Trazado_18" data-name="Trazado 18"
                                                                    d="M26.691,26.323A9.507,9.507,0,0,0,9.077,23.165a5.446,5.446,0,0,0-1-.094,5.21,5.21,0,0,0-5.2,5.2,5.688,5.688,0,0,0,.108,1.08A7.241,7.241,0,0,0,0,35.191a7.741,7.741,0,0,0,1.964,5.122,7.009,7.009,0,0,0,4.859,2.443h5.858a.911.911,0,1,0,0-1.822H6.9a5.746,5.746,0,0,1-5.082-5.75A5.409,5.409,0,0,1,4.5,30.528a.919.919,0,0,0,.4-1.107,3.306,3.306,0,0,1-.2-1.161,3.387,3.387,0,0,1,3.381-3.381,3.323,3.323,0,0,1,1.154.2.915.915,0,0,0,1.141-.466,7.684,7.684,0,0,1,14.6,2.531.91.91,0,0,0,.756.81,6.568,6.568,0,0,1-.621,12.971H20.132a.911.911,0,0,0,0,1.822h5.075a8.074,8.074,0,0,0,5.413-2.673,8.4,8.4,0,0,0-3.928-13.754Z"
                                                                    transform="translate(0 -18.4)" fill="" />
                                                                <path id="Trazado_19" data-name="Trazado 19"
                                                                    d="M169.426,191.784a.908.908,0,0,0,0-1.289L164.6,185.67a.922.922,0,0,0-.641-.27.894.894,0,0,0-.641.27l-4.825,4.825a.914.914,0,0,0,.641,1.559.889.889,0,0,0,.641-.27l3.273-3.273v15.043a.911.911,0,0,0,1.822,0V188.511l3.273,3.273A.9.9,0,0,0,169.426,191.784Z"
                                                                    transform="translate(-147.547 -174.13)" fill="" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <span class="step-text mt-3 text-bold step-title d-none d-md-block">
                                                    Elegir Fotos</span>
                                            </div>
                                        </li>
                                        <li class="step step-success">
                                            <div class="step-content">
                                                <span class="step-text mb-3 d-none d-md-block text-bold">Paso 2</span>
                                                <span class="step-circle"></span>
                                                <div class="mt-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26.949" height="30"
                                                        viewBox="0 0 26.949 30">
                                                        <g id="paso-2" transform="translate(0)">
                                                            <g id="Grupo_364" data-name="Grupo 364"
                                                                transform="translate(2.034 16.779)">
                                                                <g id="Grupo_363" data-name="Grupo 363"
                                                                    transform="translate(0)">
                                                                    <path id="Trazado_122" data-name="Trazado 122"
                                                                        d="M56.512,264a.508.508,0,0,0-.508.508v2.034a.508.508,0,0,0,1.017,0V264.51A.508.508,0,0,0,56.512,264Z"
                                                                        transform="translate(-56.004 -264.002)"
                                                                        fill="" />
                                                                </g>
                                                            </g>
                                                            <g id="Grupo_366" data-name="Grupo 366"
                                                                transform="translate(4.067 17.796)">
                                                                <g id="Grupo_365" data-name="Grupo 365">
                                                                    <path id="Trazado_123" data-name="Trazado 123"
                                                                        d="M88.512,280a.508.508,0,0,0-.508.508v2.034a.508.508,0,0,0,1.017,0V280.51A.508.508,0,0,0,88.512,280Z"
                                                                        transform="translate(-88.004 -280.002)"
                                                                        fill="" />
                                                                </g>
                                                            </g>
                                                            <g id="Grupo_368" data-name="Grupo 368"
                                                                transform="translate(16.779 19.829)">
                                                                <g id="Grupo_367" data-name="Grupo 367">
                                                                    <path id="Trazado_124" data-name="Trazado 124"
                                                                        d="M294.358,312.7a.508.508,0,0,0-.569.842,3.051,3.051,0,1,1-1.705-.52.508.508,0,1,0,0-1.017,4.068,4.068,0,1,0,2.275.7Z"
                                                                        transform="translate(-288.016 -312.002)"
                                                                        fill="" />
                                                                </g>
                                                            </g>
                                                            <g id="Grupo_370" data-name="Grupo 370"
                                                                transform="translate(0 0)">
                                                                <g id="Grupo_369" data-name="Grupo 369"
                                                                    transform="translate(0 0)">
                                                                    <path id="Trazado_125" data-name="Trazado 125"
                                                                        d="M50.712,22.208a6.1,6.1,0,0,0-3.32-3.853V7.119a.508.508,0,0,0-.25-.438L35.957.071a.508.508,0,0,0-.518,0L24.254,6.681c-.011.006-.018.015-.028.022l-.03.023a.5.5,0,0,0-.112.124s-.006.006-.008.009v0a.509.509,0,0,0-.057.166.45.45,0,0,1-.006.045A.444.444,0,0,0,24,7.119v13.22a.508.508,0,0,0,.25.438l11.186,6.61.041.017.043.018a.454.454,0,0,0,.35,0l.043-.018c.013-.006.027-.01.041-.017l3.036-1.794a6.1,6.1,0,0,0,11.719-3.383ZM35.7,1.1l2.644,1.562-3.92,2.316a.509.509,0,1,0,.518.876l4.4-2.6,2.644,1.562L31.8,10.834,29.156,9.272l4.259-2.517a.509.509,0,1,0-.518-.876l-4.741,2.8L25.512,7.119ZM31.292,11.715V15.11l-2.627-1.552V10.163Zm7.658,10.61a6.2,6.2,0,0,0-.167,2.209l-2.576,1.523V24.406a.508.508,0,1,0-1.017,0v1.651L25.021,20.048V8.01l2.627,1.552v4.286a.508.508,0,0,0,.25.438l3.644,2.153A.509.509,0,0,0,32.308,16V12.316l2.881,1.7v8.353a.508.508,0,0,0,1.017,0V14.019l9.215-5.445A.509.509,0,0,0,44.9,7.7L35.7,13.138l-2.9-1.713L42.986,5.406l3.389,2V18A6.077,6.077,0,0,0,38.95,22.326Zm5.9,6.656A5.084,5.084,0,1,1,49.935,23.9,5.084,5.084,0,0,1,44.85,28.982Z"
                                                                        transform="translate(-24.004 0)" fill="" />
                                                                </g>
                                                            </g>
                                                            <g id="Grupo_372" data-name="Grupo 372"
                                                                transform="translate(18.819 22.378)">
                                                                <g id="Grupo_371" data-name="Grupo 371">
                                                                    <path id="Trazado_126" data-name="Trazado 126"
                                                                        d="M324,352.242a.508.508,0,0,0-.707,0l-1.928,1.929-.4-.4a.508.508,0,0,0-.719.719l.763.763a.508.508,0,0,0,.719,0l2.288-2.288A.508.508,0,0,0,324,352.242Z"
                                                                        transform="translate(-320.102 -352.1)"
                                                                        fill="" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>

                                                </div>
                                                <span
                                                    class="step-text mt-3 step-title d-none d-md-block text-bold">Envío</span>
                                            </div>
                                        </li>
                                        <li class="step step-active">
                                            <div class="step-content">
                                                <span class="step-text mb-3 d-none d-md-block text-bold">Paso 3</span>
                                                <span class="step-circle"></span>
                                                <div class="mt-3">
                                                    <svg id="paso-3" xmlns="http://www.w3.org/2000/svg" width="33"
                                                        height="30.236" viewBox="0 0 33 30.236">
                                                        <path id="Trazado_127" data-name="Trazado 127"
                                                            d="M32.885,234.76a2.447,2.447,0,0,0-2.434-1.717l-.236.01a2.74,2.74,0,0,0-1.83.8l-3.646,3.608A2.557,2.557,0,0,0,22.6,236.3H17.455a10.551,10.551,0,0,1-2.813-.374A6.915,6.915,0,0,0,8.2,237.278a9.317,9.317,0,0,0-1.713,1.847,2.312,2.312,0,0,0-2.983.3L.181,242.872a.646.646,0,0,0,.012.909l2.673,2.637a.646.646,0,0,0,.908-.92l-2.219-2.188,2.881-2.991a1.021,1.021,0,0,1,1.446-.024l5.14,4.988a1.021,1.021,0,0,1,.02,1.446l-2.831,2.9-.729-.694a.646.646,0,1,0-.891.936l1.191,1.135a.647.647,0,0,0,.909-.017l3.277-3.362a2.316,2.316,0,0,0,.5-2.46l1.154-.134a14.09,14.09,0,0,1,1.62-.094h7.826c.12,0,.241-.005.361-.015l.75-.062a4.34,4.34,0,0,0,2.948-1.515l5.273-6.2a2.514,2.514,0,0,0,.488-2.385Zm-1.473,1.548-5.273,6.2a3.05,3.05,0,0,1-2.07,1.064l-.75.062c-.084.007-.169.011-.253.011H15.24a15.4,15.4,0,0,0-1.769.1l-1.869.218-4.135-3.987a8,8,0,0,1,1.549-1.7,5.6,5.6,0,0,1,5.281-1.106,11.837,11.837,0,0,0,3.158.421H22.6a1.272,1.272,0,0,1,.911.388,1.258,1.258,0,0,1,.329.632.637.637,0,0,0,.022.173,1.262,1.262,0,0,1-.276.863l-.02.025a1.259,1.259,0,0,1-.986.473H18.1a.646.646,0,1,0,0,1.293h4.477a2.545,2.545,0,0,0,1.995-.957l.02-.025a2.55,2.55,0,0,0,.561-1.592l4.143-4.1a1.454,1.454,0,0,1,.971-.426l.236-.009a1.15,1.15,0,0,1,1.146.809,1.228,1.228,0,0,1-.238,1.165Zm0,0"
                                                            transform="translate(0 -220.951)" fill="" />
                                                        <path id="Trazado_128" data-name="Trazado 128"
                                                            d="M67.485,394.566a1.75,1.75,0,1,0,1.751,1.75A1.752,1.752,0,0,0,67.485,394.566Zm0,2.208a.458.458,0,1,1,.458-.458A.458.458,0,0,1,67.485,396.774Zm0,0"
                                                            transform="translate(-62.345 -374.148)" fill="" />
                                                        <path id="Trazado_129" data-name="Trazado 129"
                                                            d="M288,372.451l-.033.008a.646.646,0,1,0,.3,1.258l.033-.008a.646.646,0,0,0-.3-1.258Zm0,0"
                                                            transform="translate(-272.649 -353.157)" fill="" />
                                                        <path id="Trazado_130" data-name="Trazado 130"
                                                            d="M88.279,505.122l-.033.008a.646.646,0,0,0,.3,1.258l.033-.008a.646.646,0,0,0-.3-1.259Zm0,0"
                                                            transform="translate(-83.223 -478.987)" fill="" />
                                                        <path id="Trazado_131" data-name="Trazado 131"
                                                            d="M241.06,12.264a6.841,6.841,0,0,0,0-13.682A6.771,6.771,0,0,0,236.839.039a6.841,6.841,0,0,0,4.221,12.225Zm-4.816-9.6a5.583,5.583,0,0,1,1.394-1.611A5.492,5.492,0,0,1,241.06-.125a5.546,5.546,0,1,1-4.816,2.792Zm0,0"
                                                            transform="translate(-222.142 1.418)" fill="" />
                                                        <path id="Trazado_132" data-name="Trazado 132"
                                                            d="M323.94,55.035a1.73,1.73,0,0,1-.829.21c-.436,0-.818-.514-.818-1.1a.646.646,0,1,0-1.293,0,2.358,2.358,0,0,0,1.6,2.321.646.646,0,0,0,1.29-.028,2.156,2.156,0,0,0,1.739-2.152,2.424,2.424,0,0,0-2.251-2.251c-.01,0-.959-.084-.959-.691a.956.956,0,0,1,.959-.958.875.875,0,0,1,.57.279c.083.083.092.092.092.44a.646.646,0,0,0,1.293,0,1.668,1.668,0,0,0-.47-1.352,2.274,2.274,0,0,0-.974-.584.646.646,0,0,0-1.291.038v.042a2.143,2.143,0,0,0-1.471,2.1c0,1.481,1.539,1.982,2.247,1.984.194.016.963.356.963.958A.782.782,0,0,1,323.94,55.035Zm0,0"
                                                            transform="translate(-304.449 -45.982)" fill="" />
                                                    </svg>

                                                </div>
                                                <span
                                                    class="step-text mt-3 step-title d-none d-md-block text-bold">Pago</span>
                                            </div>
                                        </li>
                                        <li class="step">
                                            <div class="step-content">
                                                <span class="step-text mb-3 d-none d-md-block text-bold">Paso 4</span>
                                                <span class="step-circle"></span>
                                                <div class="mt-3">
                                                    <svg id="paso-4" xmlns="http://www.w3.org/2000/svg" width="30.318"
                                                        height="30.318" viewBox="0 0 30.318 30.318">
                                                        <path id="Trazado_33" data-name="Trazado 33"
                                                            d="M87.219,288H69.01l-1.956,3.912h1.769v8.8H87.405v-8.8h1.769Zm-.6.978.978,1.956H79.884l-.978-1.956Zm-17,0h7.708l-.978,1.956H68.637Zm16.813,10.758H78.6v-3.423h-.978v3.423H69.8v-7.824H76.95l.676-1.352v4.286H78.6V290.56l.676,1.352h7.148Zm0,0"
                                                            transform="translate(-62.955 -270.396)" fill="" />
                                                        <path id="Trazado_34" data-name="Trazado 34"
                                                            d="M128,416h2.934v.978H128Zm0,0"
                                                            transform="translate(-120.176 -390.572)" fill="" />
                                                        <path id="Trazado_35" data-name="Trazado 35"
                                                            d="M128,448h2.934v.978H128Zm0,0"
                                                            transform="translate(-120.176 -420.616)" fill="" />
                                                        <path id="Trazado_36" data-name="Trazado 36"
                                                            d="M192,448h.978v.978H192Zm0,0"
                                                            transform="translate(-180.264 -420.616)" fill="" />
                                                        <path id="Trazado_37" data-name="Trazado 37"
                                                            d="M189.9,18.545l2.258-.576,2.258.575.177-2.425,1.2-2.049-2.122-.918-1.513-1.871-1.514,1.871-2.122.918,1.2,2.049Zm1.354-4.591.9-1.117.9,1.118,1.329.576-.756,1.292-.109,1.487-1.368-.349-1.368.348-.109-1.487-.756-1.292Zm0,0"
                                                            transform="translate(-177.004 -10.591)" fill="" />
                                                        <path id="Trazado_38" data-name="Trazado 38"
                                                            d="M32.16,102.163l.711-1.213-1.259-.545L30.7,99.281l-.909,1.124-1.259.545.711,1.213.105,1.447,1.352-.344,1.353.344Zm-.958-.3-.037.509-.462-.117-.461.117-.038-.509-.267-.457.466-.2.3-.37.3.371.466.2Zm0,0"
                                                            transform="translate(-26.791 -93.212)" fill="" />
                                                        <path id="Trazado_39" data-name="Trazado 39"
                                                            d="M400.16,102.163l.711-1.213-1.259-.545-.909-1.124-.909,1.124-1.259.545.711,1.213.105,1.447,1.352-.344,1.353.344Zm-.958-.3-.037.509-.462-.117-.461.117-.037-.509-.267-.457.466-.2.3-.37.3.371.466.2Zm0,0"
                                                            transform="translate(-372.297 -93.212)" fill="" />
                                                        <path id="Trazado_40" data-name="Trazado 40"
                                                            d="M94.039,142.953a10.845,10.845,0,0,1,2.457,2.253l1.937,2.42-.4-3.2a6.281,6.281,0,0,0-2.451-4.224l-.468-.351-1.433,2.866Zm1.409-1.589a5.307,5.307,0,0,1,1.58,2.951,11.84,11.84,0,0,0-2.091-1.929Zm0,0"
                                                            transform="translate(-87.953 -131.299)" fill="" />
                                                        <path id="Trazado_41" data-name="Trazado 41"
                                                            d="M324.951,144.415l-.4,3.2,1.937-2.421a10.855,10.855,0,0,1,2.457-2.253l.359-.24-1.433-2.866-.468.352a6.279,6.279,0,0,0-2.451,4.223Zm3.1-2.037a11.806,11.806,0,0,0-2.091,1.929,5.31,5.31,0,0,1,1.58-2.951Zm0,0"
                                                            transform="translate(-304.712 -131.292)" fill="" />
                                                        <path id="Trazado_42" data-name="Trazado 42"
                                                            d="M280,131.994h.978v-2.049a10.076,10.076,0,0,1,2.332-6.44l-.752-.626A11.059,11.059,0,0,0,280,129.945Zm0,0"
                                                            transform="translate(-262.885 -115.368)" fill="" />
                                                        <path id="Trazado_43" data-name="Trazado 43"
                                                            d="M336,96h.978v.978H336Zm0,0"
                                                            transform="translate(-315.462 -90.132)" fill="" />
                                                        <path id="Trazado_44" data-name="Trazado 44"
                                                            d="M360,64h.978v.978H360Zm0,0"
                                                            transform="translate(-337.995 -60.088)" fill="" />
                                                        <path id="Trazado_45" data-name="Trazado 45"
                                                            d="M164.187,131.994h.978v-2.049a11.061,11.061,0,0,0-2.558-7.066l-.752.626a10.078,10.078,0,0,1,2.332,6.44Zm0,0"
                                                            transform="translate(-151.962 -115.368)" fill="" />
                                                        <path id="Trazado_46" data-name="Trazado 46"
                                                            d="M144,96h.978v.978H144Zm0,0"
                                                            transform="translate(-135.198 -90.132)" fill="" />
                                                        <path id="Trazado_47" data-name="Trazado 47"
                                                            d="M120,64h.978v.978H120Zm0,0"
                                                            transform="translate(-112.665 -60.088)" fill="" />
                                                        <path id="Trazado_48" data-name="Trazado 48"
                                                            d="M240,136h.978v.978H240Zm0,0"
                                                            transform="translate(-225.33 -127.687)" fill="" />
                                                        <path id="Trazado_49" data-name="Trazado 49"
                                                            d="M240,168h.978v6.357H240Zm0,0"
                                                            transform="translate(-225.33 -157.731)" fill="" />
                                                        <path id="Trazado_50" data-name="Trazado 50"
                                                            d="M464,216h.978v.978H464Zm0,0"
                                                            transform="translate(-435.638 -202.797)" fill="" />
                                                        <path id="Trazado_51" data-name="Trazado 51"
                                                            d="M464,248h.978v.978H464Zm0,0"
                                                            transform="translate(-435.638 -232.841)" fill="" />
                                                        <path id="Trazado_52" data-name="Trazado 52"
                                                            d="M480,232h.978v.978H480Zm0,0"
                                                            transform="translate(-450.66 -217.819)" fill="" />
                                                        <path id="Trazado_53" data-name="Trazado 53"
                                                            d="M448,232h.978v.978H448Zm0,0"
                                                            transform="translate(-420.616 -217.819)" fill="" />
                                                        <path id="Trazado_54" data-name="Trazado 54"
                                                            d="M440,0h.978V.978H440Zm0,0"
                                                            transform="translate(-413.105)" fill="" />
                                                        <path id="Trazado_55" data-name="Trazado 55"
                                                            d="M440,32h.978v.978H440Zm0,0"
                                                            transform="translate(-413.105 -30.044)" fill="" />
                                                        <path id="Trazado_56" data-name="Trazado 56"
                                                            d="M456,16h.978v.978H456Zm0,0"
                                                            transform="translate(-428.127 -15.022)" fill="" />
                                                        <path id="Trazado_57" data-name="Trazado 57"
                                                            d="M424,16h.978v.978H424Zm0,0"
                                                            transform="translate(-398.083 -15.022)" fill="" />
                                                        <path id="Trazado_58" data-name="Trazado 58"
                                                            d="M16,248h.978v.978H16Zm0,0"
                                                            transform="translate(-15.022 -232.841)" fill="" />
                                                        <path id="Trazado_59" data-name="Trazado 59"
                                                            d="M16,280h.978v.978H16Zm0,0"
                                                            transform="translate(-15.022 -262.885)" fill="" />
                                                        <path id="Trazado_60" data-name="Trazado 60"
                                                            d="M32,264h.978v.978H32Zm0,0"
                                                            transform="translate(-30.044 -247.863)" fill="" />
                                                        <path id="Trazado_61" data-name="Trazado 61"
                                                            d="M0,264H.978v.978H0Zm0,0"
                                                            transform="translate(0 -247.863)" fill="" />
                                                        <path id="Trazado_62" data-name="Trazado 62"
                                                            d="M16,0h.978V.978H16Zm0,0" transform="translate(-15.022)"
                                                            fill="" />
                                                        <path id="Trazado_63" data-name="Trazado 63"
                                                            d="M16,32h.978v.978H16Zm0,0"
                                                            transform="translate(-15.022 -30.044)" fill="" />
                                                        <path id="Trazado_64" data-name="Trazado 64"
                                                            d="M32,16h.978v.978H32Zm0,0"
                                                            transform="translate(-30.044 -15.022)" fill="" />
                                                        <path id="Trazado_65" data-name="Trazado 65"
                                                            d="M0,16H.978v.978H0Zm0,0" transform="translate(0 -15.022)"
                                                            fill="" />
                                                    </svg>

                                                </div>
                                                <span class="step-text mt-3 step-title d-none d-md-block">Pedido
                                                    Finalizado</span>
                                            </div>
                                        </li>
                                    </ul>

                                    <span
                                        class="bg-danger d-inline-block text-white font-monument py-3 px-4 cartel-step text-bold mt-5">PASO
                                        3</span>
                                </div>
                            </div>

                            <h5 class="titulo-tabs-user">Finalizar pedido</h5>
                            <h5 class="titulo-tabs-user">Seleccione Método de Pago</h5>

                            <!-- FORM METODO PAGO -->
                            <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center">
                                <div id="metodo-pago" class="col-9 col-md-10  order-md-1">
                                    <div class="custom-control contenedor-input custom-radio  ">
                                        <input type="radio" id="transfer" value="1" name="metodoPago" data-toggle='collapse' data-target='#collapsediv2' aria-expanded="false" class="custom-control-input  metodoPago">
                                        <label class="custom-control-label" for="transfer">Tranferencia Bancaria</label>
                                    </div>
            
                                </div>
                                <div class="col-3 col-md-2 order-1 order-md-2">
                                    <img class="img-fluid" src="../assets/img/transferencia.svg" alt="">
                                </div>
            
            
                            </div>
            
                            <div id="collapsediv2" class="collapse in py-4">
                                <p>- Transferí la suma de tu pedido a la siguiente cuenta: <br><br>
            
                                    Paso 1: <br>
                                    <span class="text-bold">Número de Cuenta:</span> CC en Pesos 099-005957/6
                                    <br>
                                    <span class="text-bold">Número de CBU:</span> 0720099120000000595768 <br>
                                    <span class="text-bold">Alias:</span> DIGITALCLIK <br>
                                    <span class="text-bold">Razón Social:</span> DIGITAL CLIK SA <br>
                                    <span class="text-bold">CUIT/CUIL:</span> 30710261438 <br><br>
            
                                    Paso 2: <br>
                                    - Enviá un mail a: hola@prontophotp.com o un Whatsapp a 2215708341 con el
                                    asunto <span class="text-bold">“Comprobante de pago – Pedido [Tu Número de
                                        Pedido]”</span> no hace falta
                                    ningún texto, solo tener adjunto la imagen del comprobante de
                                    transferencia.<br><br>
            
                                    Paso 3:<br>
                                    - Esperar nuestro contacto para la confirmación del pedido.
                                </p>
                            </div>
            
                            <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center my-4">
                                <div class="col-9 col-md-10  order-md-1">
                                    <div class="custom-control contenedor-input custom-radio ">
                                        <input type="radio" id="mp" name="metodoPago" value="2" class="custom-control-input no-transfer metodoPago" checked>
                                        <label class="custom-control-label" for="mp">Mercado Pago</label>
                                    </div>
                                </div>
                                <div class="col-3 col-md-2 order-1 order-md-2">
                                    <img class="img-fluid" src="../assets/img/MP.svg" alt="">
                                </div>
                            </div>
            
                            <div class="row shadow-sm p-3 metodo-envio d-flex align-items-center">
                                <div class="col-9 col-md-10  order-md-1">
                                    <div class="custom-control contenedor-input custom-radio ">
                                        <input type="radio" id="retiroSucursal"  <?php if($_SESSION['prontoFront']['envio']['tipo']=='urbano' OR $_SESSION['prontoFront']['envio']['tipo']=='sucursal' OR $_SESSION['prontoFront']['envio']['tipo']=='recibir'){echo 'disabled';}?> name="metodoPago" value="3" class="custom-control-input no-transfer metodoPago">
                                        <label class="custom-control-label" for="retiroSucursal">Pagar al retirar por
                                            una sucursal</label>
                                    </div>
                                </div>
                                <div class="col-3 col-md-2 order-1 order-md-2">
                                    <img class="img-fluid" src="../assets/img/returarSucursal.svg" alt="">
                                </div>
                            </div>

                            <div class="row mt-5 shadow-sm p-2">
                                <div class="col-md-12 ">
                                    <h5 class="subtitulo-tabs-user py-3">Resumen de Compra</h5>
                                    <hr class="divisor-resumen-compra">
                                </div>
                                <div class="col-md-12">
                                    <p class="descrip-pedido m-0 py-3">
                                    <?php
                                    $desc='';
                                    foreach ($_SESSION['archivos'] as $key=>$impre){
                                        $text='Revelado de '.$impre['cantidad'].' fotografías en papel '.$impre['acabado'].', tamaño '.$impre['tamano'].'.';?>
                                        <span><?php echo $text; ?></span>
                                        <br>
                                    <?php } ?>
                                    <input type="hidden" name="descripcion" value="<?php echo $desc;?>"></p>
                                    <hr class="divisor-resumen-compra">
                                </div>
                                <div class="col-md-12">
                                    <h5 class="subtitulo-tabs-user pt-3">Ingresa cupon de descuento</h5>
                                    <div class="input-group mb-3">
                                      <input type="text" class="form-control" data-seccion="0"  id="codDescuento" placeholder="" >
                                      <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-sm" id="btnDescuento" style="padding:.25rem .5rem;" type="button">Aplicar</button>
                                      </div>
                                    </div>
                                    <hr class="divisor-resumen-compra">
                                </div>
                                <div class="col-md-12 totales">
                                    <p class="m-0 mb-2">Sub-Total $<?php echo $costototal;?></p>
                                    <p class="m-0 mb-2">Envio $<?php echo $enviotag;?></p>
                                    <?php if(isset($_SESSION['prontoFront']['cupon'])){
                                            $desc=$_SESSION['prontoFront']['cupon']['valor'];
                                            $md=($costototal*$desc)/100;
                                            $cupon='<span class="badge badge-info"><small>Cupon '.$_SESSION["prontoFront"]["cupon"]["nombre"].'</small></span>';
                                        }else{
                                            $md=0;
                                            $cupon='';
                                        } ?>
                                    <p class="m-0 mb-2">Cupón de descuento $ <?php echo $md; ?></p>
                                    <?php echo $cupon; ?>
                                    <p class="m-0 text-bold mb-4">TOTAL $<?php echo $costototal+$costoenvio-$md;?></p>
                                </div>
                            </div>
                            <div class="row p-0">
                                <div class="col-md-12 p-0 m-0">
                                    <button type="button" class="btn btn-success btn-lg btn-block rounded-bottom btn-pagar">Pagar</button>
                                </div>
                            </div>



                        </div>
                        <!-- FIN FORM PAGO -->

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
<script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script> 
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
<script>
$(".no-transfer").click(function() {
    $("#collapsediv2").collapse('hide');
})

// Cuando hacemos click verifica si tiene la clase "show" y si es asi, no colapsa nada. Caso contrario colapsa el div.
$('.contenedor-input > input[data-toggle="collapse"]').click(function(e) {
    target = $("#collapsediv2");
    if ($(target).hasClass('show')) {
        // e.preventDefault(); Esto en el caso que el link lleve a alguna seccion.
        e.stopPropagation()
    }
})
</script>
<script>
$(function(){
	$('.metodoPago').change(function(){
		var meto=$(this).val();
		if(meto!='2'){
			$('.btn-pagar').html('Finalizar pedido');
		}else{
			$('.btn-pagar').html('Pagar');
		}
	});
	$('.btn-pagar').click(function(e){
		e.preventDefault();
		var metodo=$(".metodoPago:checked").val();
		if(metodo=='2'){
			$.post('../inc/crear_pago3.php',{metodo:metodo,desc:'Impresion Imagenes'},function(data){
				console.log(data);
				$MPC.openCheckout ({
				    url: data.url,
				    mode: "modal",
				    onreturn: function(data) {
					    console.log(data);
					    if(data.collection_status== 'approved'){
					    	window.location.href = "index_p4.php";
				    	}else{
							alert('Error al realizar el pago, intente nuevamente');
					    }
					}
				});
			
			},'json');	
		}else{
			$.post('../inc/crear_pedido.php',{metodo:metodo,desc:'Impresion Imagenes'},function(data){
				if(data.success){
					window.location.href = "index_p4.php?pedido="+data.pedido;
				}
			},'json');
		}
	});
	$('#btnDescuento').click(function(e){
		e.preventDefault();
		var cupon=$('#codDescuento').val();
		$.post('../inc/aplicar_cupon.php',{cupon:cupon},function(data){
			console.log(data);
			if(data.success){
				location.reload();
			}else{
				alert(data.msg);
			}
		},'json');
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