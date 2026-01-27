<?php
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "crearpago.log");
include 'config.inc.php';
include 'funciones.inc.php';
global $conectar;
$respuesta=new stdClass();
$metodo=$_POST['metodo'];
$desc=$_POST['desc'];
$desc = preg_replace("<br>","\r\n",$desc);
$monto=$_SESSION['prontoFront']['valor'];

$carro=$_SESSION['pronto']['cart'];
$total=0;
$descuento=0;
$porc=0;
foreach($carro as $id=>$datos){
    $cant=$datos['cantidad'];
    $cat=$datos['cat'];

    // Obtener precio y descuento del producto
    $prod_res=$conectar->query("SELECT precio, descuento_final FROM productos WHERE id='$id'");
    $prod_row=$prod_res->fetch_assoc();
    $precioUnitario=$prod_row['precio'];
    $descuentoProducto=isset($prod_row['descuento_final']) && $prod_row['descuento_final'] > 0 ? $prod_row['descuento_final'] : 0;

    // Aplicar descuento del producto si existe
    if($descuentoProducto > 0){
        $precioUnitario=$prod_row['precio'] - ($prod_row['precio'] * $descuentoProducto / 100);
    }

    $precio=($cant * $precioUnitario);

    if(isset($_SESSION['prontoFront']['cupon'])){
        if ($_SESSION['prontoFront']['cupon']['categoria']==$cat) {
            $porc=intval($_SESSION['prontoFront']['cupon']['valor']);
            $des=($precio*$porc)/100;
            $descuento=$descuento+$des;
        }
    }
    $total=$total+$precio;
}
unset($_SESSION['prontoFront']['cupon']);

// Incluir costo de envío en el total
$costoEnvio = isset($_SESSION['prontoFront']['envio']['costo']) ? floatval($_SESSION['prontoFront']['envio']['costo']) : 0;
$monto=$total-$descuento+$costoEnvio;

//crear pedido
$param=array();
$entrega=$_SESSION['prontoFront']['envio']['tipo'];
$param['entrega']=$entrega;
$param['envio']=$_SESSION['prontoFront']['envio']['envio'];
$param['costo']=$_SESSION['prontoFront']['envio']['costo'];
$param['idcliente']=$_SESSION['prontoFront']['idcliente'];
$param['nombre']=$_SESSION['prontoFront']['envio']['nombre'].' '.$_SESSION['prontoFront']['envio']['apellido'];
$param['direccion']=$_SESSION['prontoFront']['envio']['direccion'];
$param['cp']=$_SESSION['prontoFront']['envio']['cp'];
$param['ciudad']=$_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia']=$_SESSION['prontoFront']['envio']['provincia'];
$param['telefono']=$_SESSION['prontoFront']['envio']['telefono'].' - '.$_SESSION['prontoFront']['envio']['celular'];
$param['dni']=$_SESSION['prontoFront']['envio']['dni'];
$param['email']=$_SESSION['prontoFront']['envio']['email'];
$param['valor']=$monto;
$param['metodo']=$metodo;
$param['desc']=$desc;
$param['metodo_envio_id']=$_SESSION['prontoFront']['envio']['metodo_envio_id'] ?? null;
$param['epresis_tiempo_entrega']=$_SESSION['prontoFront']['envio']['epresis_fecha'] ?? null;

// Agregar datos de facturación desde el POST
if(isset($_POST['facturacion'])){
    $param['facturacion']=$_POST['facturacion'];
}

$pedido=crearPedido($param);

if($pedido->success){
    $idpedido=$pedido->pedido;
    $respuesta->success=true;
    $respuesta->pedido=$idpedido;
    
    //
    $carro=$_SESSION['pronto']['cart'];
    $items=array();
    foreach($carro as $id=>$datos){
        $res=$conectar->query("SELECT p.nombre,p.descripcion, p.precio, p.descuento_final,(SELECT imagen FROM `imagenes` WHERE id_producto='$id' ORDER BY id ASC LIMIT 1) as imagen FROM productos p  WHERE p.id='$id' ");
        $row=$res->fetch_assoc();
        $cant=$datos['cantidad'];

        // Aplicar descuento del producto si existe
        $precioUnitario=$row['precio'];
        $descuentoProducto=isset($row['descuento_final']) && $row['descuento_final'] > 0 ? $row['descuento_final'] : 0;
        if($descuentoProducto > 0){
            $precioUnitario=$row['precio'] - ($row['precio'] * $descuentoProducto / 100);
        }

        $precio=($cant * $precioUnitario);
        $color = isset($datos['color']) ? $conectar->real_escape_string($datos['color']) : NULL;
        $item['producto']=$row['nombre'].' '.$datos['color'];
        $item['cantidad']=$cant;
        $item['precio']=$precio;

        // Guardar producto con color
        if($color !== NULL){
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`, `color`) VALUES ('$idpedido','$id','$cant','$color')");
        } else {
            $conectar->query("INSERT INTO `pedidos_detalle`( `id_pedido`, `id_producto`, `cantidad`) VALUES ('$idpedido','$id','$cant')");
        }
        $items[]=$item;
    }
   
    notificarPedido($idpedido, $items, $entrega);
    //
    
    $_SESSION['prontoFront']['pedido']=$idpedido;
    
}else{
    $respuesta->success=false;
    error_log($pedido->error);
    $respuesta->error=$pedido->error;
}


echo json_encode($respuesta);
?>