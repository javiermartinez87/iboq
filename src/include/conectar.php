<?php

//conecto con la base de datos
include($_SERVER['DOCUMENT_ROOT'].'/include/datos_bd.php');

$mysqli = mysqli_connect($host_name, $user_name, $password, $database);
if (mysqli_connect_errno()) {
    echo "Error al conectar con servidor MySQL: " . mysqli_connect_error();
}

$mysqli->set_charset("utf8");
