<?php include ('header.php'); ?>

<div class="position-relative portada-tienda d-flex align-items-center">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-white text-bold">Nuestra Tienda</h2>
                <p class="text-white">Productos elaborados con la más alta tecnología y asesoramiento personalizado para
                    cada uno de nuestros clientes.</p>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>

<div class="container py-5 mt-0 mt-md-5">
    <div class="row">
        <div class="col-md-3">
            <h4 class="text-bold mb-3">Categorias</h4>
        </div>
        <div class="col-md-9"></div>
    </div>
    <div class="row d-flex flex-row">
        <div class="col-md-3 order-2 order-md-1">
            <div class="bg-light">
                <!-- LISTADO DE CATEGORIAS -->
                <div class="accordion pt-2 pb-4" id="categorias-tienda">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Categoria 1 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down float-right text-danger"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-up float-right text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#categorias-tienda">
                            <div class="card-body">
                                Subcategoria
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    Categoria 2 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down float-right text-danger"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-up float-right text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                            data-parent="#categorias-tienda">
                            <div class="card-body">
                                Subcategoria
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Categoria 3 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down float-right text-danger"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-up float-right text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#categorias-tienda">
                            <div class="card-body">
                                Subcategoria
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                    Categoria 4 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down float-right text-danger"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-up float-right text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                            data-parent="#categorias-tienda">
                            <div class="card-body">
                                Subcategoria
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                                    aria-controls="collapseFive">
                                    Categoria 5 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-chevron-down float-right text-danger"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-up float-right text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z" />
                                    </svg>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                            data-parent="#categorias-tienda">
                            <div class="card-body">
                                Subcategoria
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 order-1 order-md-2">

            <!-- LISTADO DE PRODUCTOS -->
            <div class="row">
                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <a href="producto.php"><button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button></a>
                        
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button>
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <a href="producto.php"><button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button></a>
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <a href="producto.php"><button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button></a>
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <a href="producto.php"><button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button></a>
                    </div>
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-center mb-5">
                    <div class="contenedor-card">
                        <div class="card-producto effect-hover">
                            <div class="mask d-flex justify-content-center align-items-center">
                                <a href="/prontophot/producto.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                        class="bi bi-search text-white" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg>
                                </a>
                            </div>
                            <img class="w-100 image-fluid" src="https://via.placeholder.com/150" alt="">
                            <div class="contenido-card">
                                <p class="nombre-producto">Nombre del Producto</p>
                                <p class="detalle-producto">Detalles del Producto</p>
                                <p class="nombre-producto">Precio</p>

                            </div>
                        </div>
                        <a href="producto.php"><button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1"
                                xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528"
                                viewBox="0 0 16.528 16.528">
                                <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart"
                                    d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z"
                                    transform="translate(-1.5 -3)" fill="#fff" />
                            </svg>
                            Agregar Pedido</button></a>
                    </div>
                </div>
            </div>

            <!-- PAGINACION -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example paginacion">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
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
</footer>

<!--Eliminar esto para que funciona el dropdown, pero deja de funcionar la animacion del menu hamburguesa-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
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

<script src="assets/js/starter.js"></script>

</body>

</html>