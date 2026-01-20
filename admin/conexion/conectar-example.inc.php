<?php
$hostname_conectar = "localhost";
$database_conectar = "db";
$username_conectar = "user";
$password_conectar = "password";

$conectar = new mysqli($hostname_conectar,$username_conectar, $password_conectar,$database_conectar);
if ($conectar->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conectar->connect_errno . ") " . $conectar->connect_error;
}
$conectar->set_charset('utf8');