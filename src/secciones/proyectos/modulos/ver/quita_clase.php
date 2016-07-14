<?php
include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');

$id = $_POST['id'];
$mysqli->query("update partida set clase='' where id = '$id'");

echo json_encode($sub);