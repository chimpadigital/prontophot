<?php
ini_set("log_errors", 1);
ini_set("error_log", "error.log");
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
$respuesta=new stdClass();
function extension($filename){
    return substr(strrchr($filename, '.'), 1);
}
$id=$_POST['id'];
$nombre =trim($_POST['nombre']);
$categoria =$_POST['categoria'];
$codigo =$_POST['codigo'];
$ancho =$_POST['ancho'];
$alto =$_POST['alto'];
$profundidad =$_POST['profundidad'];
$descripcion =trim($_POST['descripcion']);
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

$query="UPDATE `productos` SET `id_categoria`='$categoria',`nombre`='$nombre',`codigo`='$codigo',`ancho`='$ancho',`alto`='$alto',`profundidad`='$profundidad',`descripcion`='$descripcion',`stock`='$stock',`descuento`='$descuento',`precio`='$precio',`color_rojo`='$rojo',`color_naranja`='$naranja',`color_azul`='$azul',`color_celeste`='$celeste',`color_violeta`='$violeta',`color_verde`='$verde',`color_amarillo`='$amarillo' WHERE id='$id'";
$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $id=$id;
    if(isset($_FILES['imagen'])){
        error_log('tiene imagenes');
        $longitud = count($_FILES['imagen']['name']);
        if ($longitud>0) {
            error_log('son '.$longitud.' imagenes a subir');
            for ($i = 0; $i < $longitud; $i++) {
                if(is_uploaded_file($_FILES['imagen']['tmp_name'][$i]))
                {
                    error_log('esta subida la iamgen '.$_FILES['imagen']['tmp_name'][$i]);
                    $dir='../../img/productos';
                    $nameold = $_FILES['imagen']['name'][$i];
                    $name = rand().'producto'.$id.'.'.extension($nameold);
                    $namenew='img/productos/'.$name;
                    //
                    if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i], "$dir/$name"))
                    {
                        error_log('subio la iamgen '.$_FILES['imagen']['tmp_name'][$i]);
                        $nombre=$dir.'/'.$name;
                        $query = "INSERT INTO `imagenes`(`id_producto`, `imagen`) VALUES ('$id','$namenew')";
                        $conectar->query($query);
                        $respuesta->error[]='error img nueva '.$conectar->error.$query;
                    }
                }
            }
        }   
    }
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);