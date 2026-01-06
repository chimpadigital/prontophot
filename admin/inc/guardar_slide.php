<?php
include __DIR__.'/../conexion/conectar.inc.php';
global $conectar;
function extension($filename){
    return substr(strrchr($filename, '.'), 1);
}
$respuesta= new stdClass();
$id=isset($_POST['id'])?$_POST['id']:'';
$titulo=$_POST['titulo'];
$subtitulo=$_POST['subtitulo'];
$link=$_POST['link'];
$boton=$_POST['boton'];
if(is_uploaded_file($_FILES['imagen']['tmp_name']))
{
    $dir='../../assets/img';
    $nameold = $_FILES['imagen']['name'];
    $name = rand().'slide.'.extension($nameold);
    $namenew='assets/img/'.$name;
    //
    if(move_uploaded_file($_FILES['imagen']['tmp_name'], "$dir/$name"))
    {
        $imagen=$namenew;
    }else{
        $imagen='';
    }
}else{
    $imagen=$_POST['imagenold']??'';
}
if (empty($id)) {
    $query="INSERT INTO `sliders`(`imagen`, `titulo`, `subtitulo`, `link`, `boton`, `pos`) VALUES ('$imagen','$titulo','$subtitulo','$link','$boton','1')";
    $mensaje="Slide agregado";
}else{
    $query="UPDATE `sliders` SET `imagen`='$imagen',`titulo`='$titulo',`subtitulo`='$subtitulo',`link`='$link',`boton`='$boton' WHERE  `id`='$id'";
    $mensaje="Slide actualizado";
}

$res=$conectar->query($query);
if($res){
    $respuesta->success=true;
    $respuesta->msg=$mensaje;
}else{
    $respuesta->success=false;
}
echo json_encode($respuesta);