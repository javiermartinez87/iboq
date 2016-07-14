<?php

include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');
include ($_SERVER['DOCUMENT_ROOT'].'/include/conectar.php');

$proyecto = $_POST ['proyecto'];
$mostrar = $mostrar = $_POST ['mostrar'];

// Listado de capitulos y subcapitulos
$array_cap = array();
$result = $mysqli->query('select * from capitulo');
while ($datos = $result->fetch_assoc()) {
    $array_cap[$datos['id']] = $datos['nombre'];
}

$array_scap = array();
$result = $mysqli->query('select * from subcapitulo');
while ($datos = $result->fetch_assoc()) {
    $array_scap[$datos['id_capitulo'].'-'.$datos['codigo']] = $datos['codigo'].' '.$datos['nombre'];
}

$array_subcap = array();
$result = $mysqli->query('select * from subcapitulo');
while ($datos = $result->fetch_assoc()) {
    if (!isset($array_subcap[$datos['id_capitulo']])) {
        $array_subcap[$datos['id_capitulo']] = array();
    }
    $array_subcap[$datos['id_capitulo']][$datos['codigo']] = $datos['nombre'];
}
//


if ($mostrar != 'sin_analizars') {
    $mostrar_o = $mostrar;
    $mostrar = str_replace('_', ' ', $mostrar);

    $query = "select distinct  pa.*, cr.clasificacion from proyecto p inner join partida pa on pa.id_proyecto = p.id inner join subcapitulo su
on su.codigo = pa.id_subcapitulo inner join capitulo c on c.id = su.id_capitulo
left outer join clasifi_real cr on cr.id_partida = pa.id
where p.id = '$proyecto' and p.idusuario = '" . $_SESSION ['idusuario'] . "' and su.codigo = '$mostrar' order by p.id";

    $result = $mysqli->query($query);
    if (!$result) {
        echo json_encode('Error1');
        exit();
    }

    $coste = 0;
    $subcapitulo = array();
    while ($datos = $result->fetch_assoc()) {
        $coste += $datos ['cantidad'] * $datos ['presupuesto'];
        $subcapitulo [$datos ['id']] = $datos;
    }

    $cont = count($subcapitulo);
    $msg = "Contiene " . count($subcapitulo);
    if ($cont == 1) {
        $msg .= ' partida';
    } else {
        $msg .= ' partidas';
    }

// echo "<script>$('[name=subcapitulo-$mostrar_o] .datos').parent().parent().html('$coste <br>$msg');</script>";
    echo "<script>$('[name=subcapitulo-$mostrar_o] .datos').html('$coste &euro;<br>$msg');</script>";
    $count_partidas = 0;
    $count_cantidad = 0;
    foreach ($subcapitulo as $k => $partida) {echo '<pre>';print_r($datos);echo '</pre>';
        echo '<div class="partida row '.$datos['clase'].'" name="partida-' . $k . '">';
        echo "<h3>" . ($partida['nombre']) . "</h3>";
        echo "<p>" . ($partida['descripcion']) . "</p>";
        echo "<div class='cantidades row'>";
        echo "<div class='col-sm-4'>Cantidad<br/>" . $partida['cantidad'] . "</div>";
        echo "<div class='col-sm-4'>Presupuesto<br/>" . $partida['presupuesto'] . "</div>";
        echo "<div class='col-sm-4'>Total<br/>" . $partida['cantidad'] * $partida['presupuesto'] . "</div>";
        echo '</div>';
        echo '</div>';        
    }
} else {
    $query = "select distinct  pa.* from proyecto p inner join partida pa on pa.id_proyecto = p.id 
        where p.id = '$proyecto' and p.idusuario = '" . $_SESSION ['idusuario'] . "' and (pa.id_subcapitulo = '' or pa.id_subcapitulo is null or pa.id_subcapitulo = 'DUDA') order by pa.id";

    $result = $mysqli->query($query);
    if (!$result) {
        echo json_encode('Error1');
        exit();
    }
    
    while ($datos = $result->fetch_assoc()) {
        
        echo '<div class="partida '.$datos['clase'].'" name="partida-' . $datos['id'] . '">';
        echo '<div class="selector_c_s" name="selec_pc-' . $datos['id'] . '">';
        echo '<div class="select_cap"  name="selec_psc-' . $datos['id'] . '">';
        echo '<div class="noselect">Seleccione un cap&iacute;tulo</div>';
        echo '<div style="display:none;" class="opciones">';
        foreach ($array_cap as $kc => $n) {
            echo '<div class="opcion" name="op_c-'.$kc.'">' . $n . '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '<div style="display:none;" class="select_scap">';
        echo '<div class="noselect">Seleccione un subcap&iacute;tulo</div>';
        echo '<div style="display:none;" class="opciones">';
        foreach ($array_scap as $ks => $n) {
            echo '<div class="opcion" name="op_sc-'.$ks.'">' . $n . '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo "<h3>" . ($datos['nombre']) . "</h3>";
        echo "<p>" . ($datos['descripcion']) . "</p>";
        echo "<div class='cantidades'>";
        echo "<div>Cantidad<br/>" . $datos['cantidad'] . "</div>";
        echo "<div>Presupuesto<br/>" . $datos['presupuesto'] . "</div>";
        echo "<div>Total<br/>" . $datos['cantidad'] * $datos['presupuesto'] . "</div>";
        echo '</div>';
        echo '</div>';
    }
}