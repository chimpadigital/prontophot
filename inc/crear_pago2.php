<?php 
if(session_status() === PHP_SESSION_NONE) session_start();
ini_set("log_errors", 1);
ini_set("error_log", "crearpago.log");

set_error_handler(function ($severity, $message, $file, $line) {

    // Si el error proviene del SDK de MercadoPago lo ignoramos
    if (strpos($file, 'vendor/mercadopago') !== false) {
        return true;
    }

    // Para el resto del sistema se maneja normalmente
    return false;
});

include 'config.inc.php';
require 'vendor/autoload.php';
include 'funciones.inc.php';
global $conectar;
MercadoPago\SDK::setAccessToken(ML_TOKEN);
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
            $descuento=($precio*$porc)/100;
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
$param['altura']=$_SESSION['prontoFront']['envio']['altura'] ?? null;
$param['ciudad']=$_SESSION['prontoFront']['envio']['ciudad'];
$param['provincia']=$_SESSION['prontoFront']['envio']['provincia'];
$param['telefono']=$_SESSION['prontoFront']['envio']['telefono'].' - '.$_SESSION['prontoFront']['envio']['celular'];
$param['dni']=$_SESSION['prontoFront']['envio']['dni'];
$param['email']=$_SESSION['prontoFront']['envio']['email'];
$param['valor']=$monto;
$param['metodo']=$metodo;
$param['desc']=$desc;
$param['metodo_envio_id']=$_SESSION['prontoFront']['envio']['metodo_envio_id'] ?? null;

// Agregar datos de facturación desde el POST
if(isset($_POST['facturacion'])){
    $param['facturacion']=$_POST['facturacion'];
}

$pedido=crearPedido($param);

if($pedido->success){
    $idpedido=$pedido->pedido;
    $respuesta->success=true;
    
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

    if (isset($_SESSION['archivos'])) {
        $imagenes = $_SESSION['archivos'];
        pedidoImagenes($idpedido, $imagenes);
    }
    notificarPedido($idpedido, $items, $entrega);
    //

    $preference = new MercadoPago\Preference();
    
    // Crea un �tem en la preferencia
    $item = new MercadoPago\Item();
    $item->title = $desc;
    $item->quantity = 1;
    $item->unit_price = $monto;
    $preference->items = array($item);
    $preference->back_urls = array(
        "success" => URL_SITIO."checkout-resultado",
        "failure" => URL_SITIO."checkout-resultado",
        "pending" => URL_SITIO."checkout-resultado"
    );
    $preference->auto_return = "approved"; 
    $preference->binary_mode = true;
    $preference->external_reference =$idpedido;
    $preference->save();
    
    $idventa=$preference->id;
    //$link=$preference->sandbox_init_point;
    $link=$preference->init_point;
    //print_r($preference);
    $respuesta->id=$idventa;
    $respuesta->url=$link;
}else{
    $respuesta->success=false;
    error_log($pedido->error);
    $respuesta->error=$pedido->error;
}


echo json_encode($respuesta);
?>