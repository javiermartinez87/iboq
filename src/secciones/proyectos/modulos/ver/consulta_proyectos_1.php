<?php

include $_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php';

$limit = $_POST['limit'];
$inicio = ($_POST['inicio'] - 1) * $limit;
$result = $mysqli->query("select * from vista_proyecto where idusuario = '" . $_SESSION['idusuario'] . "' order by id_proyecto limit $inicio,$limit");
if (!$result) {
    echo json_encode(array('estado' => 'error', 'msg' => 'Se ha perdido la conexión, por favor inténtelo más tarde'));
    exit();
} else if ($result->num_rows == 0) {
    echo json_encode(array('estado' => 'nodatos', 'msg' => 'No se han encontrado datos'));
    exit();
} else {
    $return = array();
    while ($datos = mysqli_fetch_assoc($result)) {
        array_push($return, $datos);
    }
    
    
    echo json_encode(array('estado' => 'ok', 'datos' => $return));
}