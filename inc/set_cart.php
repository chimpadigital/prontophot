<?php
session_start();
$id=$_POST['id'];
$cant=$_POST['cant'];
$color=$_POST['color'];
$categoria=$_POST['cat'];
$precio=$_POST['precio'];
$hoy=time();
$_SESSION['pronto']['cart_creado']=$hoy;
$_SESSION['pronto']['cart_expira']=$hoy + 1800;
$_SESSION['pronto']['cart'][$id]['color']=$color;
$_SESSION['pronto']['cart'][$id]['cantidad']=$cant;
$_SESSION['pronto']['cart'][$id]['cat']=$categoria;
$_SESSION['pronto']['cart'][$id]['precio']=$precio;

