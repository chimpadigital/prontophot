<?php include ('header_usuario.php'); ?>

<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-4 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt="">
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index_usuario.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Mis Compras</a>
                <a class="nav-link" id="v-pills-profile-tab" href="miPerfil.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#person-circle" />
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
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="cargarProducto" href="index.php">Nuevo Revelado</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="productosCargados" href="productos_cargados.php">Mis Compras</a>
                        </li>                        
                    </ul>
                    <!-- FIN TABS MIS COMPRAS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="cargarProductoNuevo" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs">Elegí las fotos que quieres revelar</h5>

                            <!-- CARGA IMAGENES -->
                            <div class="row mt-5">
                                <div class="col-sm-3 text-center">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="row mt-4">
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm bg-danger text-white btn-bg-red w-100">Eliminar</button></a></div>
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm btn-border-yellow text-black w-100">Modificar</button></a></div>
                                    </div>                                    
                                </div>

                                <div class="col-sm-3 text-center mt-3 mt-lg-0">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="row mt-4">
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm bg-danger text-white btn-bg-red w-100">Eliminar</button></a></div>
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm btn-border-yellow text-black w-100">Modificar</button></a></div>
                                    </div>
                                    
                                </div>

                                <div class="col-sm-3 text-center mt-3 mt-lg-0">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="row mt-4">
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm bg-danger text-white btn-bg-red w-100">Eliminar</button></a></div>
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm btn-border-yellow text-black w-100">Modificar</button></a></div>
                                    </div>
                                    
                                </div>

                                <div class="col-sm-3 text-center mt-3 mt-lg-0">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="row mt-4">
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm bg-danger text-white btn-bg-red w-100">Eliminar</button></a></div>
                                        <div class="col-md-6 col-sm-12 my-1 my-md-0"><a class="" href="#"><button type="button"
                                                    class="btn btn-sm btn-border-yellow text-black w-100">Modificar</button></a></div>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-sm-3 d-flex align-items-center justify-content-center mt-5">
                                    <a href="#">
                                        <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" width="50"
                                            height="50" fill="currentColor" class="bi bi-plus-circle"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                            <path
                                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <!-- FIN CARGA IMAGENES -->

                            <!-- FORM CARGA PRODUCTO -->
                            <h5 class="titulo-tabs">Elegí el tipo de papel para tus fotografias</h5>
                            <div class="">
                                <form id="formCargaProducto" class="needs-validation w-100 mt-5 form-carga-producto"
                                    novalidate>
                                    <div class="form-row">

                                        <div class="col-md-3 col-sm-12 mb-3 d-block d-lg-flex align-items-center">                                            
                                            <h5 class="m-0 text-bold">Todas las fotos</h5> 
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-12 mb-3">                                            
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Tamaño</option>
                                                <option>Tipo Polaroid</option>
                                                <option>10x15</option>
                                                <option>13x18</option>
                                                <option>15x20</option>
                                                <option>20x30</option>
                                                <option>25x38</option>
                                            </select>  
                                        </div>
                                        <div class="col-md-3 col-sm-12 mb-3">                                            
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Acabado</option>
                                                <option>Brillante</option>
                                                <option>Mate</option>                                                
                                            </select>                                            
                                        </div>                                        
                                    </div>                                    

                                    <div class="form-row align-items-center mt-4">

                                        <div class="col-md-3 col-sm-12 mb-3">
                                            <h5 class="m-0 text-bold">Foto 1</h5> 
                                            <label class="mt-4" for="">Tamaño</label>
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Tamaño</option>
                                                <option>Tipo Polaroid</option>
                                                <option>10x15</option>
                                                <option>13x18</option>
                                                <option>15x20</option>
                                                <option>20x30</option>
                                                <option>25x38</option>
                                            </select> 

                                            <label class="mt-4" for="">Cant.</label>
                                            <input class="form-control w-25" type="number">
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-12 mb-3">
                                            <h5 class="m-0 text-bold">Foto 2</h5> 
                                            <label class="mt-4" for="">Tamaño</label>
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Tamaño</option>
                                                <option>Tipo Polaroid</option>
                                                <option>10x15</option>
                                                <option>13x18</option>
                                                <option>15x20</option>
                                                <option>20x30</option>
                                                <option>25x38</option>
                                            </select> 

                                            <label class="mt-4" for="">Cant.</label>
                                            <input class="form-control w-25" type="number">
                                        </div>

                                        <div class="col-md-3 col-sm-12 mb-3">
                                            <h5 class="m-0 text-bold">Foto 3</h5> 
                                            <label class="mt-4" for="">Tamaño</label>
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Tamaño</option>
                                                <option>Tipo Polaroid</option>
                                                <option>10x15</option>
                                                <option>13x18</option>
                                                <option>15x20</option>
                                                <option>20x30</option>
                                                <option>25x38</option>
                                            </select> 

                                            <label class="mt-4" for="">Cant.</label>
                                            <input class="form-control w-25" type="number">
                                        </div>

                                        <div class="col-md-3 col-sm-12 mb-3">
                                            <h5 class="m-0 text-bold">Foto 4</h5> 
                                            <label class="mt-4" for="">Tamaño</label>
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Tamaño</option>
                                                <option>Tipo Polaroid</option>
                                                <option>10x15</option>
                                                <option>13x18</option>
                                                <option>15x20</option>
                                                <option>20x30</option>
                                                <option>25x38</option>
                                            </select> 

                                            <label class="mt-4" for="">Cant.</label>
                                            <input class="form-control w-25" type="number">
                                        </div>
                                    </div>                                    

                                    <div class="d-flex my-3 justify-content-center">
                                        <button form="formCargaProducto" class="btn btn-warning btn-cargar-producto"
                                            type="submit">Siguiente</button>
                                    </div>

                                </form>

                                <script>
                                // Example starter JavaScript for disabling form submissions if there are invalid fields
                                (function() {
                                    'use strict';
                                    window.addEventListener('load', function() {
                                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                        var forms = document.getElementsByClassName('needs-validation');
                                        // Loop over them and prevent submission
                                        var validation = Array.prototype.filter.call(forms, function(form) {
                                            form.addEventListener('submit', function(event) {
                                                if (form.checkValidity() === false) {
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                }
                                                form.classList.add('was-validated');
                                            }, false);
                                        });
                                    }, false);
                                })();
                                </script>

                            </div>
                        </div>
                        <!-- FIN FORM CARGA PRODUCTO -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->

                </div>
                <!-- fin tab-pane -->
            </div>
            <!-- fin tab-content -->
        </div>