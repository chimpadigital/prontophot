<?php include('header.php'); ?>
<?php
include ('../conexion/conectar.inc.php');
global $conectar;
$productos = $conectar->query("SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id ORDER BY id DESC");

?>
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
<script>
    $(function() {
        var monkeyList = new List('productosCarga', {
            valueNames: ['nombre-producto', 'categoria-producto', 'codigo-producto'],
            page: 6,
            pagination: true
        });

    });
</script>
<style>
    .pagination .active .page {
        color: #fff !important;
        background-color: #DA0000;
        border-color: #DA0000;

    }

    .pagination .page {
        color: #000 !important;
        position: relative;
        display: block;
        padding: .5rem .75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #007bff;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
</style>
<div class="container-fluid bg-black border-top border-white">
    <div class="row">
        <div class="col-2 bg-black py-4 px-3 d-none d-md-block">
            <div class="align-items-end d-flex flex-column justify-items-end mt-3 mb-4">
                <a href="\"><img class="logo-blanco" src="assets\img\logo-pronto-white.svg" alt=""></a>
            </div>

            <hr class="solid my-4">

            <!-- TABS ADMIN  -->
            <div class="nav flex-column nav-pills sidebar-admin" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" href="index.php"><svg class="bi text-yellow mr-3" width="32"
                        height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cart-fill" />
                    </svg>Productos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="pedidos.php"><svg class="bi text-yellow mr-3"
                        width="32" height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#file-earmark-image" />
                    </svg>Pedidos</a>
                <a class="nav-link" id="v-pills-profile-tab" href="sliders.php"><i class="fa fa-2x fa-picture-o mr-3" aria-hidden="true"></i>Slider</a>
                <a class="nav-link" id="v-pills-messages-tab" href="cupones.php"><svg class="bi text-yellow mr-3" width="32"
                        height="32">
                        <use xlink:href="node_modules/bootstrap-icons/bootstrap-icons.svg#cash" />
                    </svg>Cupones de Descuento</a>
            </div>
            <!-- FIN TABS ADMIN  -->
        </div>
        <!-- FIN COL-4  -->

        <div class="col-md-10 bg-white p-5 rounded-lg columna-content-admin">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <!-- TABS PRODUCTOS -->
                    <ul class="nav nav-tabs tab-cargar-productos tabs-admin" id="tabProductos" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="cargarProducto" href="index.php">Cargar
                                Producto Nuevo</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="productosCargados" href="productos_cargados.php">Productos ya
                                Cargados</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cat" href="productos_categorias.php">Categorias</a>
                        </li>
                        <li class="nav-item" role="presentation2">
                            <a class="nav-link" id="valoresImpresion" href="productos_valoresImpresion.php">Valores
                                Impresión</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="impuestos" href="productos_impuestos.php">Impuestos</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="metodosEnvio" href="productos_metodos_envio.php">Métodos de Envío</a>
                        </li>
                    </ul>
                    <!-- FIN TABS PRODUCTOS -->

                    <!-- CONTENIDO DE LAS TABS -->
                    <div class="tab-content" id="contenidoTabsAdmin">

                        <!-- TAB PRODUCTOS YA CARGADOS -->
                        <div class="tab-pane fade show active" id="productosCarga" role="tabpanel" aria-labelledby="profile-tab">
                            <h5 class="titulo-tabs">Buscador de Productos</h5>
                            <div class="row">
                                <form id="formBuscarProducto" class="needs-validation w-100 mt-5 form-carga-producto"
                                    novalidate>
                                    <div class="form-row">
                                        <div class="col-md-12 mb-3 d-flex align-items-center">
                                            <label class="sr-only" for="buscadorProductos"></label>
                                            <input type="text" class="form-control mr-sm-2 h-100 search" id="buscadorProductos"
                                                placeholder="Escribe aquí Nombre, Categoría o Código de producto">
                                            <button type="submit"
                                                class="btn bg-danger text-white btn-bg-red">Buscar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-5">
                                <h5 class="titulo-tabs">Búsquedas Recientes</h5>
                            </div>
                            <!-- BUSQUEDAS RECIENTES -->
                            <div class="row mt-4 list">
                                <?php while ($row = $productos->fetch_assoc()) { ?>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex flex-column justify-content-center align-items-center mb-5">
                                        <div class="contenedor-card">
                                            <div class="card-producto text-center effect-hover">
                                                <div class="mask d-flex justify-content-center align-items-center">
                                                    <a href="producto.php?id=<?php echo $row['id']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-search text-white" viewBox="0 0 16 16">
                                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <img class="w-100 image-fluid" src="<?php if (!empty($row['imagen'])) {
                                                                                        echo '../' . $row['imagen'];
                                                                                    } else {
                                                                                        echo 'https://via.placeholder.com/150';
                                                                                    } ?>" alt="">
                                                <div class="contenido-card">
                                                    <p class="nombre-producto"><?php echo $row['nombre']; ?></p>
                                                    <p class="detalle-producto">
                                                        Codigo : <?php echo '<span class="codigo-producto">' . $row['codigo'] . '</span>'; ?>
                                                        <br>
                                                        <?php echo '<span class="categoria-producto">' . $row['categoria'] . '</span>'; ?>
                                                        <br>
                                                        <?php echo $row['alto'] . 'x' . $row['ancho'] . 'x' . $row['profundidad']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="producto.php?id=<?php echo $row['id']; ?>" class="btn bg-danger text-white btn-bg-red w-100">Más Información</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

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


                            <!-- FIN BUSQUEDAS RECIENTES -->

                        </div>
                        <!-- FIN PRODUCTOS YA CARGADOS -->

                    </div>
                    <!-- FIN CONTENIDO DE LAS TABS -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>