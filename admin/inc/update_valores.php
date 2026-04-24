<?php
include ('../../conexion/conectar.inc.php');
global $conectar;
$polaroid=$_POST['polaroid_p'];
$polaroidd=$_POST['polaroid_d'];
$polaroidh=$_POST['polaroid_h'];
$ultima=date('d-m-Y h:i');
foreach ($polaroid as $i =>$precio){
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('polaroid','$i','$polaroidd[$i]','$polaroidh[$i]','$precio')";
    $conectar->query($query);
}

$p1015=$_POST['10x15_p'];
$d1015=$_POST['10x15_d'];
$h1015=$_POST['10x15_h'];

foreach ($p1015 as $i =>$precio){
    $desde=$d1015[$i];
    $hasta=$h1015[$i];
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('10x15','$i','$desde','$hasta','$precio')";
    $conectar->query($query);
}

$p1318=$_POST['13x18_p'];
$d1318=$_POST['13x18_d'];
$h1318=$_POST['13x18_h'];

foreach ($p1318 as $i =>$precio){
    $desde=$d1318[$i];
    $hasta=$h1318[$i];
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('13x18','$i','$desde','$hasta','$precio')";
    $conectar->query($query);
}

$p1520=$_POST['15x20_p'];
$d1520=$_POST['15x20_d'];
$h1520=$_POST['15x20_h'];

foreach ($p1520 as $i =>$precio){
    $desde=$d1520[$i];
    $hasta=$h1520[$i];
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('15x20','$i','$desde','$hasta','$precio')";
    $conectar->query($query);
}

$p2030=$_POST['20x30_p'];
$d2030=$_POST['20x30_d'];
$h2030=$_POST['20x30_h'];

foreach ($p2030 as $i =>$precio){
    $desde=$d2030[$i];
    $hasta=$h2030[$i];
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('20x30','$i','$desde','$hasta','$precio')";
    $conectar->query($query);
}

$p2538=$_POST['25x38_p'];
$d2538=$_POST['25x38_d'];
$h2538=$_POST['25x38_h'];

foreach ($p2538 as $i =>$precio){
    $desde=$d2538[$i];
    $hasta=$h2538[$i];
    $query="INSERT INTO `impresiones`(`formato`, `fila`, `desde`, `hasta`, `precio`) VALUES ('25x38','$i','$desde','$hasta','$precio')";
    $conectar->query($query);
}
$respuesta=new stdClass();
$respuesta->success=true;
$respuesta->fecha=$ultima;
echo json_encode($respuesta);