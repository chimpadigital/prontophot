<?php 
session_start();
$id=$_POST['img'];
$archi=$_SESSION['archivostmp'][$id]['archivo'];
unlink(__DIR__.'/../'.$archi);
unset($_SESSION['archivostmp'][$id]);
echo $id;
?>