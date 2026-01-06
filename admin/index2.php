<?php include ('header.php'); ?>

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
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
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
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="productosCargados" href="productos_cargados.php">Productos ya
                                Cargados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link" id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <div class="tab-pane fade show active" id="cargarProductoNuevo" role="tabpanel"
                            aria-labelledby="home-tab">
                            <h5 class="titulo-tabs">Información del Producto</h5>

                            <!-- CARGA IMAGENES -->
                            <div class="row mt-5">
                                <div class="col-sm-4 text-center">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="btn-grupo d-flex flex-column flex-md-row">
                                        <div>
                                            <a class="nav-link" href="#"><button type="button"
                                                    class="btn bg-danger text-white btn-bg-red">Subir
                                                    Foto</button></a>
                                        </div>
                                        <div>
                                            <a class="nav-link" href="#"><button type="button"
                                                    class="btn btn-bg-transparent text-black">Eliminar Foto</button></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-center">
                                    <a class="text-center" href="#"><img class="w-100 img-admin-producto"
                                            src="https://via.placeholder.com/300"></a>
                                    <div class="btn-grupo d-flex flex-column flex-md-row">
                                        <div>
                                            <a class="nav-link" href="#"><button type="button"
                                                    class="btn bg-danger text-white btn-bg-red">Subir
                                                    Foto</button></a>
                                        </div>
                                        <div>
                                            <a class="nav-link" href="#"><button type="button"
                                                    class="btn btn-bg-transparent text-black">Eliminar Foto</button></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 d-flex align-items-center justify-content-center justify-content-lg-start mt-3 mt-md-0">
                                    <a href="#">
                                        <svg class="text-danger ml-0 ml-md-5" xmlns="http://www.w3.org/2000/svg" width="50"
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
                            <div class="row">
                                <form id="formCargaProducto" class="needs-validation w-100 mt-5 form-carga-producto"
                                    novalidate>
                                    <div class="form-row">
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom01">Nombre del Producto</label>
                                            <input type="text" class="form-control" id="validationCustom01" value=""
                                                required>
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                        <div class="col-md-5 mb-3">
                                            <label for="validationCustom02">Categoría</label>
                                            <select class="custom-select" id="validationCustom04" required>
                                                <option selected disabled value="">Elige...</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Seleccione una categoria
                                            </div>
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom03">Código</label>
                                            <input type="text" class="form-control" id="validationCustom03" value="">
                                            <div class="valid-feedback">
                                                Genial!
                                            </div>
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Dimensiones</p>

                                    <div class="form-row align-items-center">
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom04">Ancho</label>
                                            <input type="text" class="form-control" id="validationCustom04">
                                        </div>
                                        <div class="col-md-2 mb-3 mx-0 mx-md-5">
                                            <label for="validationCustom05">Alto</label>
                                            <input type="text" class="form-control" id="validationCustom05">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="validationCustom06">Profundidad</label>
                                            <input type="text" class="form-control" id="validationCustom06">
                                        </div>
                                    </div>

                                    <p class="subtitulo-form">Descripción del Producto</p>

                                    <div class="form-row">
                                        <div class="col-md-12 mb-3">
                                            <label for="validationCustom07"></label>
                                            <textarea class="form-control" id="validationTextarea" rows="6"
                                                placeholder="" required=""></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row mt-3">
                                        <label for="validationCustom07">Colores Disponibles</label>
                                        <div
                                            class="col-md-12 d-flex flex-row flex-wrap flex-lg-nowrap mt-2 justify-content-lg-between">                                            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option1">
                                                <label class="form-check-label" for="">Rojo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option2">
                                                <label class="form-check-label" for="">Naranja</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option3">
                                                <label class="form-check-label" for="">Azul</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option4">
                                                <label class="form-check-label" for="">Celeste</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option4">
                                                <label class="form-check-label" for="">Violeta</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option4">
                                                <label class="form-check-label" for="">Verde</label>                                                
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="inlineRadioOptions"
                                                    id="" value="option4">
                                                <label class="form-check-label" for="">Amarillo</label>                                                
                                            </div>

                                        </div>

                                        

                                    </div>

                                    <div class="form-row mt-5">

                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom08">Stock</label>
                                            <input type="text" class="form-control" id="validationCustom08">

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom09">Código de Descuento</label>
                                            <input type="text" class="form-control" id="validationCustom09">

                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="validationCustom10">Precio</label>
                                            <input type="text" class="form-control" id="validationCustom10" required>

                                        </div>
                                    </div>

                                    <div class="d-flex my-3 justify-content-center">
                                        <button form="formCargaProducto" class="btn btn-warning btn-cargar-producto"
                                            type="submit">Publicar
                                            Producto</button>
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