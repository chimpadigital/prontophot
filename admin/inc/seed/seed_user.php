<?php
include_once __DIR__.'/../../conexion/conectar.inc.php';
global $conectar;

// Datos del usuario administrador
$nombre = "sin nombre";
$apellido = "sin apellido";
$dni = "123123";
$email = "francoaxeldam@gmail.com";
$passwd = "$2y$10\$Bx4sPd2wnD9MqN7UHs0v6eGPhUtGbZ/OWFRRvg4PCoKPuA5ZSKc9e";
$direccion = "123123";
$provincia = "Cordoba";
$ciudad = "Cordoba";
$cp = "3333";
$telefono = "03516012877";
$code = "kox5rmdjo6l9toeo9a9etyyipoam5j875iobs1kjzcljjbf52ghonls85c6xio12";
$activo = 1;
$nivel = 1;
$creado = date('Y-m-d H:i:s');

// Verificar si el usuario ya existe
$checkSql = "SELECT * FROM clientes WHERE email = '$email'";
$result = $conectar->query($checkSql);

if ($result->num_rows > 0) {
    echo "El usuario con email '$email' ya existe en la base de datos.<br>";
} else {
    // Insertar usuario
    $sql = "INSERT INTO clientes (nombre, apellido, dni, email, passwd, direccion, provincia, ciudad, cp, telefono, code, activo, nivel, creado)
            VALUES ('$nombre', '$apellido', '$dni', '$email', '$passwd', '$direccion', '$provincia', '$ciudad', '$cp', '$telefono', '$code', $activo, $nivel, '$creado')";

    if ($conectar->query($sql) === TRUE) {
        echo "Usuario administrador creado exitosamente.<br>";
        echo "Email: $email<br>";
        echo "Nivel: $nivel (Administrador)<br>";
        echo "Fecha de creación: $creado<br>";
    } else {
        echo "Error al crear usuario: " . $conectar->error . "<br>";
    }
}

echo "<br>Seed de usuario completado.";
?>
