<?php

include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/include/conectar.php');

$proyecto = $_POST ['proyecto'];
$mostrar = $mostrar = $_POST ['mostrar'];

// Listado de capitulos y subcapitulos
$array_cap = array();
$result = $mysqli->query('select * from capitulo where id <> 16 and id <> 17 order by id');
while ($datos = $result->fetch_assoc()) {
    $array_cap[$datos['id']] = $datos['nombre_en'];
}

$array_scap = array();
$result = $mysqli->query('select * from subcapitulo');
while ($datos = $result->fetch_assoc()) {
    $array_scap[$datos['id_capitulo'] . '-' . $datos['codigo']] = $datos['codigo'] . ' ' . $datos['nombre_en'];
}

$array_subcap = array();
$result = $mysqli->query('select * from subcapitulo');
while ($datos = $result->fetch_assoc()) {
    if (!isset($array_subcap[$datos['id_capitulo']])) {
        $array_subcap[$datos['id_capitulo']] = array();
    }
    $array_subcap[$datos['id_capitulo']][$datos['codigo']] = $datos['nombre_en'];
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
    $msg = "Contain " . count($subcapitulo);
    if ($cont == 1) {
        $msg .= ' Work Description';
    } else {
        $msg .= ' Work Descriptions';
    }

// echo "<script>$('[name=subcapitulo-$mostrar_o] .datos').parent().parent().html('$coste <br>$msg');</script>";
    echo "<script>$('[name=subcapitulo-$mostrar_o] .datos').html('".number_format($coste,2)." &euro;<br>$msg');</script>";
    $count_partidas = 0;
    $count_cantidad = 0;
    foreach ($subcapitulo as $k => $partida) {
        echo '<div class="partida row ' . $partida['clase'] . '" name="partida-' . $k . '">';


        echo "<div class='col-sm-9'>";
        echo "<h3>" . ($partida['nombre']) . "</h3>";
        echo "<p>" . ($partida['descripcion']) . "</p>";
        echo '</div>';

        echo "<div class='cantidades col-sm-3'>";
        echo "<div class='col-sm-4'>Amount<br/>" . number_format($partida['cantidad'],2) . "</div>";
        echo "<div class='col-sm-4'>Cost<br/>" . number_format($partida['presupuesto'],2) . "</div>";
        echo "<div class='col-sm-4'>Total<br/>" . number_format($partida['cantidad'] * $partida['presupuesto'],2) . "</div>";

        echo '<div class="col-sm-12">';
        echo '<span type="button" class="validar glyphicon glyphicon-ok" onclick="quitaclase('.$k.')"></span>';
        echo '</div>';
        
        echo '<div class="col-sm-12">';
        echo '<div class="selector_c_s" name="selec_pc-' . $partida['id'] . '">';
        echo '<div class="select_cap"  name="selec_psc-' . $partida['id'] . '">';
        echo '<div class="noselect">Select a Chapter</div>';
        echo '<div style="display:none;" class="opciones">';
        foreach ($array_cap as $kc => $n) {
            echo '<div class="opcion" name="op_c-' . $kc . '">' . $n . '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '<div style="display:none;" class="select_scap">';
        echo '<div class="noselect">Select a sub-chapter</div>';
        echo '<div style="display:none;" class="opciones">';
        foreach ($array_scap as $ks => $n) {
            echo '<div class="opcion" name="op_sc-' . $ks . '">' . $n . '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';


        echo '</div>';

        echo '</div>';
    }
}