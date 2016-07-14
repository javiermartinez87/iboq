<?php
include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
	
	$id = $_POST['elimina'];
	
	$mysqli->query("delete from partida where id_proyecto = '$id'");
	$mysqli->query("delete from proyecto where id = '$id'");
	echo 'ok';
?>