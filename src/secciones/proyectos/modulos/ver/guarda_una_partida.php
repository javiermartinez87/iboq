<?php
include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');

$partida = $_POST['partida'];
$sub = $_POST['scap'];

$result = $mysqli->query("select * from partida where id = '$partida'");
$datos = $result->fetch_assoc();


$mysqli->query("insert into cambios_user(user,id_subcapitulo_ini,id_subcapitulo_fin,id_partida) values ('".$_SESSION ["idusuario"]."','".$datos['id_subcapitulo']."','$sub','$partida')");
$mysqli->query("update partida set id_subcapitulo = '$sub', clase='' where id = '$partida'");

echo json_encode($sub);