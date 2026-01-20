<?php
$hostname_conectar = "localhost";
$database_conectar = "prontophot_db";
//$username_conectar = "prontophot_user";
//$password_conectar = "lSCerIm4xPkX";
$username_conectar = "root";
$password_conectar = "";
$conectar = new mysqli($hostname_conectar,$username_conectar, $password_conectar,$database_conectar);
if ($conectar->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conectar->connect_errno . ") " . $conectar->connect_error;
}
$conectar->set_charset('utf8');