<?php

include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');
include ($_SERVER['DOCUMENT_ROOT'].'/include/conectar.php');
include ($_SERVER['DOCUMENT_ROOT'].'/secciones/proyectos/modulos/analisis/funciones_analisis.php');

if($_SESSION['modo'] == 'pruebas'){
    $_SESSION['depurando'] = 'No';
}

$n_analizar = 30;
$proyectos_analizar = $_POST ['proyectos'];
$proyectos_analizar = str_replace(",", "','", $proyectos_analizar);
$iniciales = $_POST ['iniciales'];

$sql = "update proyecto set analizado = 1 where id in ('$proyectos_analizar')";

$result = $mysqli->query($sql);

$sql = "select distinct pa.* from proyecto p  inner join partida pa on pa.id_proyecto = p.id
 and pa.id_subcapitulo is null  or pa.id_subcapitulo = ''
where p.idusuario = '$_SESSION[idusuario]' and p.id in ('$proyectos_analizar') limit $n_analizar";

$result = $mysqli->query($sql);

if (!$sql || $result->num_rows == 0) {
    echo json_encode('fin');
} else {
    $realizan = $result->num_rows;

    while ($datos = $result->fetch_assoc()) {
        $ret = analiza($datos,$p_limpia);
        $id_subcap = $ret['valor'];
        $clase = $ret['clase'];
     
        $query = "update partida set id_subcapitulo = '$id_subcap', p_limpia = '$p_limpia',clase='$clase' where id = '" . $datos['id'] . "'";
        $mysqli->query($query);
    }


    $sql = "select distinct count(*) as contador from proyecto p  inner join partida pa on pa.id_proyecto = p.id
		and pa.id_subcapitulo is null or pa.id_subcapitulo = ''
		where p.idusuario = '$_SESSION[idusuario]' and p.id in ('$proyectos_analizar')";
    

    $result = $mysqli->query($sql);
    $datos = $result->fetch_assoc();
    $faltan = $datos ['contador'];
    if ($iniciales == - 1) {
        $iniciales = $faltan + $realizan;
    }

    echo json_encode(array(
        'iniciales' => $iniciales,
        'porcentaje' => ceil(100 - (($faltan / $iniciales) * 100)),
        'analizadas' => $iniciales - $faltan,
        'faltan' => $faltan
    ));
}
