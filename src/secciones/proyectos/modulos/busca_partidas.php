<?php
	include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
	include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
	include($_SERVER['DOCUMENT_ROOT'].'/secciones/proyectos/modulos/analisis/funciones_analisis.php');
	
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
	
	//$sql = "SELECT * from partida where $where >= " . ceil(count($palabras) / 2) .$cantidad.$presupuesto;
	
	//$sql = "SELECT w.descripcion, w.nombre, w.cantidad, w.presupuesto, w.id_proyecto, p.nombre as nombrep, p.idusuario from partida w, proyecto p
	//where w.id_proyecto=p.id and $where >= " . ceil(count($palabras) / 2) .$cantidad.$presupuesto;
	
	$sql = "(SELECT w.descripcion, w.nombre, w.cantidad, w.presupuesto, w.id_proyecto, p.nombre as nombrep, p.idusuario from partida w, proyecto p
	where p.idusuario = '".$_SESSION ['idusuario']."' and w.id_proyecto=p.id and $where >= " . ceil(count($palabras) / 2) .$cantidad.$presupuesto ." ) UNION  ";
	$sql .=	"(SELECT w.descripcion, w.nombre, w.cantidad, w.presupuesto, w.id_proyecto, p.nombre as nombrep, p.idusuario from partida w, proyecto p
	where p.idusuario <> '".$_SESSION ['idusuario']."' and w.id_proyecto=p.id and $where >= " . ceil(count($palabras) / 2) .$cantidad.$presupuesto ." )";
	
	
	
	
	//echo $sql;
	
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
			
			//echo "<pre>";
        	//	print_r( $datos);
    		//echo "</pre>";
		?>
        <div class="partidab row" name="partida-<?= $datos['id'] ?>">
			<div class='col-sm-7'>
				<h5><input type="checkbox" checked="checked" />&nbsp;&nbsp;<?= $datos['nombre'] ?></h5>
				<p><?= $datos['descripcion'] ?></p>
				
			</div>
            <div class='cantidades col-sm-5'>
                <div class="col-sm-4">Amount<br/><label><?= number_format($datos['cantidad'],2,'.','') ?></label></div>
                <div class="col-sm-4">Cost<br/><label><?= number_format($datos['presupuesto'],2,'.','') ?></label></div>
                <div class="col-sm-4">Total<br/><label><?= number_format(($datos['cantidad'] * $datos['presupuesto']),2,'.','') ?></label></div>
			</div>
			<div class='col-sm-12'>
				<p style="text-align: right;">
					<?php if($_SESSION ['idusuario']  == $datos['idusuario']) {
						echo '<a href="/abrir/'. $datos['id_proyecto'] .'">'. $datos['nombrep'] .'</a>'; 
					}
					else{
					echo '<label class="glyphicon glyphicon-lock">&nbsp;'. $datos['nombrep'].'</label>' ; 
					}?>
				</p>
			</div>
		</div>
        <?php
		}
	}
	
	
