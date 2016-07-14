<?php
include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
include($_SERVER['DOCUMENT_ROOT'].'/modulos/analisis/funciones_analisis.php');

$busqueda = $_POST['busqueda'];
$min_cantidad = $_POST['min_cantidad'];
$max_cantidad = $_POST['max_cantidad'];
$min_presupuesto = $_POST['min_presupuesto'];
$max_presupuesto = $_POST['max_presupuesto'];

$cantidad = " and (cantidad >= $min_cantidad and cantidad <= $max_cantidad)";
$presupuesto = " and (presupuesto >= $min_presupuesto and presupuesto <= $max_presupuesto)";

limpia_desc_completa($busqueda, $palabras, $posicion_palabra);

$copy_pal = array();
foreach ($palabras as $p) {
    if (trim($p) != '[ BASURA ]' && trim($p) != '')
        array_push($copy_pal, trim($p));
}
$palabras = $copy_pal;

$where = '';
foreach ($palabras as $p) {
    if ($where == '')
        $where .= " IF( p_limpia like '%$p%', 1, 0 ) ";
    else
        $where .= " + IF( p_limpia like '%$p%', 1, 0 ) ";
}

$sql = "SELECT * from partida
 where $where >= " . ceil(count($palabras) / 2) .$cantidad.$presupuesto;


$result = $mysqli->query($sql);
if ($result->num_rows == 0) {
    echo '<div class="error">No se han encontrado datos</div>';
} else {
    while ($datos = $result->fetch_assoc()) {
        $asocidas = asocia_palabras($datos['descripcion']);
        
        foreach ($palabras as $p) {
            if(isset($asocidas[$p])){
                $datos['descripcion'] = str_replace($asocidas[$p], "<label class='destacada'>$asocidas[$p]</label>", $datos['descripcion']);
            }            
        }
        ?>
        <div class="partida" name="partida-<?= $datos['id'] ?>">
            <h3><input type="checkbox" checked="checked" /><?= $datos['nombre'] ?></h3>
            <p><?= $datos['descripcion'] ?></p>
            <div class='cantidades'>
                <div>Amount<br/><label><?= $datos['cantidad'] ?></label></div>
                <div>Cost<br/><label><?= $datos['presupuesto'] ?></label></div>
                <div>Total<br/><label><?= ($datos['cantidad'] * $datos['presupuesto']) ?></label></div>
            </div>
        </div>
        <?php
    }
}


