<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
$respuesta=new stdClass();
function extension($filename){
    return substr(strrchr($filename, '.'), 1);
}
$nombre =$_POST['nombre'];
$categoria =$_POST['categoria'];
$codigo =$_POST['codigo'];
$ancho =$_POST['ancho'];
$alto =$_POST['alto'];
$profundidad =$_POST['profundidad'];
$descripcion =$_POST['descripcion'];
$rojo =isset($_POST['color_rojo'])?$_POST['color_rojo']:'0';
$naranja =isset($_POST['color_naranja'])?$_POST['color_naranja']:'0';
$azul =isset($_POST['color_azul'])?$_POST['color_azul']:'0';
$celeste =isset($_POST['color_celeste'])?$_POST['color_celeste']:'0';
$violeta =isset($_POST['color_violeta'])?$_POST['color_violeta']:'0';
$verde =isset($_POST['color_verde'])?$_POST['color_verde']:'0';
$amarillo =isset($_POST['color_amarillo'])?$_POST['color_amarillo']:'0';
$stock =$_POST['stock'];
$descuento =$_POST['descuento'];
$precio =$_POST['precio'];

$query="INSERT INTO `productos`(`id_categoria`, `nombre`, `codigo`, `ancho`, `alto`, `profundidad`, `descripcion`, `stock`, `descuento`, `precio`, `color_rojo`, `color_naranja`, `color_azul`, `color_celeste`, `color_violeta`, `color_verde`, `color_amarillo`, `estado`) VALUES ('$categoria','$nombre','$codigo','$ancho','$alto','$profundidad','$descripcion','$stock','$descuento','$precio','$rojo','$naranja','$azul','$celeste','$violeta','$verde','$amarillo','0')";
$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $id=$conectar->insert_id;
    
    $longitud = count($_FILES['imagen']['name']);
    if ($longitud>0) {
        for ($i = 0; $i < $longitud; $i++) {
            if(is_uploaded_file($_FILES['imagen']['tmp_name'][$i]))
            {
                $dir='../../img/productos';
                $nameold = $_FILES['imagen']['name'][$i];
                $name = rand().'producto'.$id.'.'.extension($nameold);
                $namenew='img/productos/'.$name;
                //
                if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i], "$dir/$name"))
                {
                    $nombre=$dir.'/'.$name;
                    $query = "INSERT INTO `imagenes`(`id_producto`, `imagen`) VALUES ('$id','$namenew')";
                    $conectar->query($query);
                    $respuesta->error[]='error img nueva '.$conectar->error;
                }
            }
        }
    }   
    
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);