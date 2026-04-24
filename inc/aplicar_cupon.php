<?php
session_start();
include ('../conexion/conectar.inc.php');
include ('funciones.inc.php');
global $conectar;
$respuesta= new stdClass();

$cupon=$_POST['cupon'];
$seccion=$_POST['seccion'];
if (isset($_SESSION['pronto']['cart'])) {
    foreach ($_SESSION['pronto']['cart'] as $id=>$item){
        if($item['cat']!=''){
            $cat=$item['cat'];
        }
    }
}

$query="SELECT * FROM `cupones` WHERE nombre LIKE '$cupon' AND desde<=CURDATE() AND hasta>=CURDATE() AND seccion='$seccion'";
$res=$conectar->query($query);
if($res->num_rows>0){
    $row=$res->fetch_assoc();
    $valor=$row['descuento'];
    if($row['seccion']=='1'){
        if(!empty($row['categorias'])){
            $categ=unserialize($row['categorias']);
            
            if(in_array($cat,$categ)){
                $_SESSION['prontoFront']['cupon']['valor']=$valor;
                $_SESSION['prontoFront']['cupon']['idcupon']=$row['id'];
                $_SESSION['prontoFront']['cupon']['nombre']=$row['nombre'];
                $_SESSION['prontoFront']['cupon']['categoria']=$cat;
                $respuesta->success=true;
            }else{
                $respuesta->msg="El cupon no es valido para esta categoria";
                $respuesta->success=false;
            }
        }else{
            $respuesta->msg="El cupon no tiene categoria designada";
            $respuesta->success=false;
        }
    }else{
        $_SESSION['prontoFront']['cupon']['valor']=$valor;
        $_SESSION['prontoFront']['cupon']['idcupon']=$row['id'];
        $_SESSION['prontoFront']['cupon']['nombre']=$row['nombre'];
        $respuesta->success=true;
    }
    
}else{
    $respuesta->msg="El cupon no es valido o expiro";
    $respuesta->success=false;
}
echo json_encode($respuesta);