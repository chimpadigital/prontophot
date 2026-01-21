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
$descuento = isset($_POST['codigo_descuento']) ? $_POST['codigo_descuento'] : '';
$descuento_final = isset($_POST['descuento_final']) ? $_POST['descuento_final'] : 0;
$precio =$_POST['precio'];

// Procesar video
$videoUpdate = '';
if(isset($_POST['video_tipo'])){
    if($_POST['video_tipo'] === 'url' && !empty($_POST['video_url'])){
        $videoUpdate = ",`video`='".$_POST['video_url']."'";
    } elseif($_POST['video_tipo'] === 'archivo' && isset($_FILES['video_archivo']) && $_FILES['video_archivo']['error'] === 0){
        $dir = '../../img/videos';
        if(!is_dir($dir)){
            mkdir($dir, 0755, true);
        }
        $nameold = $_FILES['video_archivo']['name'];
        $name = time().'_video_'.rand().'.'.extension($nameold);
        if(move_uploaded_file($_FILES['video_archivo']['tmp_name'], "$dir/$name")){
            $videoUpdate = ",`video`='img/videos/".$name."'";
        }
    } else {
        $videoUpdate = ",`video`=NULL";
    }
}

$query="UPDATE `productos` SET `id_categoria`='$categoria',`nombre`='$nombre',`codigo`='$codigo',`ancho`='$ancho',`alto`='$alto',`profundidad`='$profundidad',`descripcion`='$descripcion',`stock`='$stock',`descuento`='$descuento',`descuento_final`='$descuento_final',`precio`='$precio',`color_rojo`='$rojo',`color_naranja`='$naranja',`color_azul`='$azul',`color_celeste`='$celeste',`color_violeta`='$violeta',`color_verde`='$verde',`color_amarillo`='$amarillo'".$videoUpdate." WHERE id='$id'";
$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $id=$id;

    // Actualizar colores de imágenes existentes
    if(isset($_POST['color_imagen_existente']) && is_array($_POST['color_imagen_existente'])){
        foreach($_POST['color_imagen_existente'] as $imagen_id => $color){
            $color = trim($color);
            $query_color = "UPDATE `imagenes` SET `color`='$color' WHERE `id`='$imagen_id'";
            $conectar->query($query_color);
        }
    }

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

                    // Obtener color de la imagen nueva (opcional)
                    $colorImagen = '#000000';
                    if(isset($_POST['color_imagen'][$i]) && !empty($_POST['color_imagen'][$i])){
                        $colorImagen = trim($_POST['color_imagen'][$i]);
                    }

                    if(move_uploaded_file($_FILES['imagen']['tmp_name'][$i], "$dir/$name"))
                    {
                        error_log('subio la iamgen '.$_FILES['imagen']['tmp_name'][$i]);
                        $nombre=$dir.'/'.$name;
                        $query = "INSERT INTO `imagenes`(`id_producto`, `imagen`, `color`) VALUES ('$id','$namenew','$colorImagen')";
                        $conectar->query($query);
                        $respuesta->error[]='error img nueva '.$conectar->error.$query;
                    }
                }
            }
        }
    }

    // Guardar nuevas cuotas si existen
    if(isset($_POST['cuotas_nuevas']) && is_array($_POST['cuotas_nuevas'])){
        foreach($_POST['cuotas_nuevas'] as $cuota){
            $cantidad = (int)$cuota['cantidad'];
            $precio = (float)$cuota['precio'];
            $interes = (float)$cuota['interes'];
            $sin_interes = (int)$cuota['sin_interes'];

            $queryCuota = "INSERT INTO `cuotas`(`cantidad`, `precio`, `interes`, `sin_interes`, `producto_id`)
                          VALUES ('$cantidad', '$precio', '$interes', '$sin_interes', '$id')";
            $conectar->query($queryCuota);
        }
    }

}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);