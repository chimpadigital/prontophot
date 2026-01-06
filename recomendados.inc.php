<?php
include_once 'conexion/conectar.inc.php';
include_once 'inc/funciones.inc.php';
global $conectar;
$recomendados=$conectar->query("SELECT p.*,c.nombre categoria,(SELECT imagen FROM imagenes WHERE id_producto=p.id LIMIT 1) as imagen FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id ORDER BY id DESC LIMIT 3");
echo $conectar->error;
?>

<div class="bg-light prod-destacados">
    <div class="container py-5 productos-destacados">
        <div class="row">
            <div class="col-md-12 py-5">
                <h3 class="font-monument text-uppercase text-bold text-center">Productos Destacados</h3>
            </div>
			<?php while ($row=$recomendados->fetch_assoc()){ ?>
            <div class="col-12 col-sm-2 col-md-4  d-flex flex-column justify-content-center mb-5">
                <div class="contenedor-card">
                    <div class="card-producto text-center effect-hover">
                        <div class="mask d-flex justify-content-center align-items-center">
                            <a href="<?php echo $row['id'].'_'.getUrl($row['nombre']);?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-search text-white" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </a>
                        </div>
                        <img class="w-100 image-fluid" src="<?php echo $row['imagen']; ?>" alt="">
                        <div class="contenido-card">
                            <p class="nombre-producto"><?php echo $row['nombre']; ?></p>
                            <p class="detalle-producto"><?php echo $row['descripcion']; ?></p>
                        </div>
                    </div>
                    <a href="<?php echo $row['id'].'_'.getUrl($row['nombre']);?>">
                    	<button class="btn bg-danger text-white btn-bg-red w-100"><svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16.528" height="16.528" viewBox="0 0 16.528 16.528"> <path id="Icon_material-shopping-cart" data-name="Icon material-shopping-cart" d="M6.458,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,6.458,16.222ZM1.5,3V4.653H3.153l2.975,6.272L5.012,12.95a1.6,1.6,0,0,0-.207.793A1.658,1.658,0,0,0,6.458,15.4h9.917V13.743H6.805a.2.2,0,0,1-.207-.207l.025-.1.744-1.347h6.157a1.645,1.645,0,0,0,1.446-.851l2.958-5.363a.807.807,0,0,0,.1-.4.829.829,0,0,0-.826-.826H4.979L4.2,3ZM14.722,16.222a1.653,1.653,0,1,0,1.653,1.653A1.651,1.651,0,0,0,14.722,16.222Z" transform="translate(-1.5 -3)" fill="#fff" /></svg>
                        Agregar Pedido</button>
                    </a>
                </div>
            </div>
			<?php }?>
        </div>
    </div>
</div>