<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');
?>
<div class="row text-center">
    
</div>

<div class="row">
	<div class="col-sm-1 cresumen">
		<div class="resumen center" style="margin:auto;">
			<div class="resumen_conten">
				<div class="verde"><span name="t_v"></span></div><div class="talkbubble talkbubble_verde">Number of Work Descriptions correctly analyzed</div>
			</div>	
			<div class="resumen_conten">
				 <div class="naranja_c"><span name="t_nc"></span></div><div class="talkbubble talkbubble_naranja_c">Number of Work Descriptions correctly analyzed</div>
			</div>
			<div class="resumen_conten">
				 <div class="naranja_o"><span name="t_no"></span></div><div class="talkbubble talkbubble_naranja_o">Number of Work Descriptions correctly analyzed</div>
			</div>
			<div class="resumen_conten">
				 <div class="rojo"><span name="t_r"></span></div><div class="talkbubble talkbubble_rojo">Number of Work Descriptions correctly analyzed</div>
			</div>
			<div class="resumen_conten">
				 <div class="rojo_c"><span name="t_r"></span></div><div class="talkbubble talkbubble_rojo">Number of Work Descriptions correctly analyzed</div>
			</div>
		</div>
		
	</div>
	<div class="col-sm-10">
	<?php
	$mostrar = $_POST ['actual'];

	$query = "select distinct c.id as id, c.nombre_en as n_cap,su.* from proyecto p inner join partida pa on pa.id_proyecto = p.id 
		left outer join subcapitulo su on su.codigo = pa.id_subcapitulo 
		left outer join capitulo c on c.id = su.id_capitulo
	where p.id = '$mostrar' and p.idusuario = '" . $_SESSION ['idusuario'] . "' order by c.id";


	$result = $mysqli->query($query);
	if (!$result) {
		echo json_encode('Error1');
		exit();
	}

	$proyectos = array();
	while ($datos = $result->fetch_assoc()) {
		if (!isset($proyectos [$datos ['id']])) {
			$proyectos [$datos ['id']] = array(
				'nombre' => ($datos ['n_cap']),
				'subcapitulos' => array()
			);
		}
		$proyectos [$datos ['id']] ['subcapitulos'] [$datos ['codigo']] = ($datos ['nombre_en']);
	}

	foreach ($proyectos as $id => $datos) {
		if ($id != '') {
			echo '<div class="capitulo col-sm-12" name="capitulo-' . $id . '">';
			echo '<div onclick="mostrar(' . $id . ');" class="cabecera row" >';
			echo '<div  class="titulo col-sm-9">' . $datos ['nombre'] . '</div>';

			$cont = count($datos['subcapitulos']);
			$msg = "Contain " . count($datos['subcapitulos']);
			if ($cont == 1) {
				$msg .= ' sub-chapter';
			} else {
				$msg .= ' sub-chapters';
			}

			echo '<div class="datos col-sm-3"><label class="coste">-</label><br/>' . $msg . '</div>';
			echo '</div>';
			echo '<div class="subcapitulos col-sm-12">';
			foreach ($datos ['subcapitulos'] as $idS => $datosS) {
				$idS = str_replace(' ', '_', $idS);
				echo '<div class="row subcapitulo no_cargado" name="subcapitulo-' . $idS . '">';
				echo '<div onclick="mostrarS(\'' . $idS . '\');" class="cabecera row" >';
				echo '<div class="titulo col-sm-9">' . $datosS . '</div>';
				echo '<div class="datos col-sm-3"></div>';
				echo '</div>';
				echo '<div  class="partidas col-sm-12"></div>';
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
		}
	}
	?>
	</div>
	<div class="col-sm-1"></div>
</div>
<script>
    function mostrar(id) {
        $('[name=capitulo-' + id + ']').toggleClass('abierto', 1000);
    }

    function mostrarS(id) {
        $('[name=subcapitulo-' + id + ']').toggleClass('abierto', 1000);
    }




    $(document).ready(function() {
        if ($('[name^=subcapitulo-].no_cargado').length > 0) {
            var cod = $('[name^=subcapitulo-].no_cargado:first').attr('name').split('-')[1];
            actualiza_subcapitulo(<?= $mostrar ?>, cod);
        } else {
            remove_cargando_segundo();
        }
    });
</script>
