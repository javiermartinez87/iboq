<?php
	include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');
	include($_SERVER['DOCUMENT_ROOT'] . '/include/class_proyecto.php');
	
	$id = $parametros[2];
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

<input type="hidden" id="proyecto_abierto"  value = "<?= $id ?>"/>
<style>
	.cuadrado{
		border:1px solid #3F51B5;border-radius: 10px;
	}
	</style>
<?php
	$proyecto = new Proyecto();
	$proyecto->carga_bd($id);
	$datos_proyecto = $proyecto->devuelve_datos();
?>
<div name="datos_resumen">
	<div class='row'>
		<h1 class='text-center'><?= $datos_proyecto['nombre'] ?></h1>
		<div class='col-sm-8'>
			<div class='row'>
				<div class='col-sm-6'>
					<div class='col-sm-12 cuadrado'>
						<p>Country: <b><?= $datos_proyecto['pais'] ?></b></p>
						<p>State: <b><?= $datos_proyecto['comunidad'] ?></b></p>
						<p>City: <b><?= $datos_proyecto['ciudad'] ?></b></p>
						<p>Location:<b> <?= $datos_proyecto['poblacion'] ?></b></p>
						<p>Description:<b></p><p> <?= nl2br($datos_proyecto['descripcion']) ?></b></p>
						<input type='hidden' name='lat' value='<?= $datos_proyecto['lat'] ?>'/>
						<input type='hidden' name='lon' value='<?= $datos_proyecto['lon'] ?>'/>
					</div>
				</div>
				<div class='col-sm-6'>
					
					
					<?php
						$tabla = '<table class="center">	<tbody>';
						
						
						$query = $mysqli->query('select c.nombre_en as nombre, p.id_subcapitulo,count(*) as contador from partida p inner join proyecto pr on pr.id = p.id_proyecto
						left outer join subcapitulo sub on sub.codigo = p.id_subcapitulo
						left outer join capitulo c on c.id = sub.id_capitulo
						where p.id_proyecto = ' . $id . '
						group by c.id order by c.id ASC');
						$disabled2 = 0;
						if (!$query) {
							$tabla .= '<div class="error">Error to access to the Data Base.</div>';
						}
						if ($query->num_rows == 0) {
							$disabled2 = 1;
							$tabla .=  '<div style="width:250px;margin:auto;" class="info">The current project has no work descriptions</div>';
							} else {
							$nulos = 0;
							$par = 0;
							while ($datos = $query->fetch_assoc()) {
								if ($datos['id_subcapitulo'] == null or $datos['id_subcapitulo'] == '') {
									$nulos = $datos['contador'];
									} else {
									$clase = "class='impar'";
									if ($par == 0) {
										$clase = "class='par'";
									}
									$tabla .=  "<tr $clase><td>" . ($datos['nombre']) . '</td><td><b>' . $datos['contador'] . '</b></td></tr>';
								}
							}
						}
						
						$tabla .= '</tbody></table>'
					?>
					
					
					<div>
						<?php
							$disabled = 0;
							$texto2  = '';
							if ($nulos == 1) {
								$texto2 = '<div class="info"><b>1</b> work description without analysis proccess</div>';
							}
							else if ($nulos > 0) {								
								$texto2 =  '<div class="info"> <b>' . $nulos . '</b> work descriptions expecting to be analyzed</div>';
								$disabled = 0;
							} 
							else {
								$disabled = 1;
							}
							
						?>            
					</div>
					
					
					<div class='col-sm-12'>
						<?php if ($disabled == 0) { 
							$disabled2 = 1;
						?>
						<div style="text-align:center;"><input class="btn btn-primary" type="button" onclick="automatico(0, -1, 0)" value="Classification process" /></div>
						<?php } ?>
						<?php if ($disabled2 == 0) { ?>
							<div style="text-align:center;"><input class="btn btn-primary" type="button" onclick="consulta_partidas()" value="See Work Descriptions" /></div>
							<?php }
							
							echo '<h2>Project summary</h2>';
							echo $texto2;
							echo $tabla;
						?>
					</div>
				</div>
			</div>
		</div>
		<div class='col-sm-4'>
			<div class="mapa" id="mapa"></div>
		</div>
	</div>
	<div class="clr"></div>
</div>


<div name="datos_partidas" style=margin:auto;"></div>


<script>
$(document).ready(function() {
google.maps.event.addDomListener(window, 'load', inicializa_mapa);

$('[name=datos_resumen]').on('click', '.div_reducido', function() {
$(this).toggleClass('div_ampliado', 1000);
});


$('[name=datos_partidas]').on('click', '.select_cap', function(e) {


if ($($(this).children('.opciones')[0]).is(':visible')) {
$($(this).children('.opciones')[0]).hide('blind', 500);
} else {
$($(this).children('.opciones')[0]).show('blind', 500);
}

});

$('[name=datos_partidas]').on('click', '.select_scap', function() {
if ($($(this).children('.opciones')[0]).is(':visible')) {
$($(this).children('.opciones')[0]).hide('blind', 500);
} else {
$($(this).children('.opciones')[0]).show('blind', 500);
}
});

$('[name=datos_partidas]').on('click', '[name^=op_c]', function() {
//$($(this).parent()).hide();
var id = $(this).attr('name').split('-')[1];
var nombre = $(this).text();
$($(this).parent().parent().parent().children('.select_scap')[0]).show();
$($(this).parent().parent().children('.noselect')[0]).text(nombre);

$('[name=' + $($(this).parent().parent().parent()).attr('name') + '] [name^=op_sc-]').hide();
$('[name=' + $($(this).parent().parent().parent()).attr('name') + '] [name^=op_sc-' + id + '-]').show();
});

$('[name=datos_partidas]').on('click', '[name^=op_sc]', function() {
var nombre = $(this).text();
$($(this).parent().parent().parent().children('.select_scap')[0]).show();
$($(this).parent().parent().children('.noselect')[0]).text(nombre);


var scap = $(this).attr('name').split('-')[2];
var partida = $(this).parent().parent().parent().attr('name').split('-')[1];
$.ajax({
type: 'POST', url: '/secciones/proyectos/modulos/ver/guarda_una_partida.php',
data: 'partida=' + partida + '&scap=' + scap,
dataType: "JSON",
beforeSend: function() {
$('[name=partida-' + partida + ']').hide('blind', 1000);
}, success: function() {
var subcap = scap.replace(" ", "_");
if ($('[name=subcapitulo-' + subcap + ']').length > 0) {
	$('[name=subcapitulo-' + subcap + '] .partidas').html('');
	$('[name=subcapitulo-' + subcap + ']').addClass('no_cargado');
	actualiza_subcapitulo($('#proyecto_abierto').val(), subcap);
	} else {
	
	}
	}
	});
	});
	});
	
	function actualiza_subcapitulo(proyecto, cod) {
	var contenedor = $('[name=subcapitulo-' + cod + ']');
	var parametros = 'mostrar=' + cod + '&proyecto=' + proyecto;
	var title = $('[name=subcapitulo-' + cod + '] .titulo').html();
	var partidas = $('[name=subcapitulo-' + cod + '] .partidas');
	$.ajax({
	type: 'POST', url: '/secciones/proyectos/modulos/ver/subcapitulo.php', dataType: "HTML", data: parametros,
	beforeSend: function(datos) {
	add_cargando_segundo('Loading sub-chapters: ' + title);
	}, success: function(datos) {
	partidas.html(datos);
	contenedor.removeClass('no_cargado');
	if ($('[name^=subcapitulo-].no_cargado').length > 0) {
	var cod = $('[name^=subcapitulo-].no_cargado:first').attr('name').split('-')[1];
	actualiza_subcapitulo(proyecto, cod);
	} else {	
	actualiza_costes();
	remove_cargando_segundo();
	}
	}
	});
	}
	
	function actualiza_costes(){
	add_cargando_segundo('Upgrading the cost of chapters');
	$('[name^=capitulo-]').each(function(){
	var capitulo = this;
	var datos = $(this).find('.subcapitulo .datos');
	var total = 0;
	$(datos).each(function(){
	total += parseFloat( ($(this).text().split(' ')[0]).replace(',',''));
	});
	var coste = $(capitulo).find('.coste')[0];
	$(coste).text(total.toFixed(2) + '€');
	
	
	});
	
	remove_cargando_segundo();	
	}
	
	function automatico(porcentaje, iniciales, analizadas) {
	if (iniciales != -1)
	cargando_porcentaje('' + analizadas + ' work descriptions of ' + iniciales, porcentaje);
	else
	cargando_porcentaje('Work description analysis', porcentaje);
	$.ajax({
	type: 'POST', url: '/secciones/proyectos/modulos/analisis/analizar_proyectos.php',
	data: 'proyectos=' + $('#proyecto_abierto').val() + '&iniciales=' + iniciales,
	dataType: "JSON", success: function(datos) {
	if (datos != 'fin') {
	automatico(datos.porcentaje, datos.iniciales, datos.analizadas);
	} else {
	remove_modal();
	location.reload();
	}
	}
	});
	}
	
	function consulta_partidas() {
	$.ajax({
	type: 'POST', url: '../secciones/proyectos/modulos/ver/ver_proyecto.php',
	data: 'actual=' + $('#proyecto_abierto').val(),
	dataType: "HTML",
	beforeSend: function() {
	cargando_indefinido('Loading the project, be patient...');
	$('[name=datos_resumen]').hide();
	$('[name=datos_partidas]').show();
	},
	success: function(datos) {
	$('[name=datos_partidas]').html(datos);
	remove_modal();
	}
	});
	}
	
	
	
	function inicializa_mapa(myLatlng) {
	var myLatlng = new google.maps.LatLng($('[name=lat]').val(), $('[name=lon]').val());
	var mapOptions = {
	zoom: 16,
	center: myLatlng,
};
var map = new google.maps.Map(document.getElementById('mapa'),
mapOptions);

var marker = new google.maps.Marker({
position: myLatlng,
map: map,
draggable: true,
animation: google.maps.Animation.DROP,
});

}

function quitaclase(id) {
$.ajax({
type: 'POST', url: '../secciones/proyectos/modulos/ver/quita_clase.php',
data: 'id=' + id,
dataType: "HTML",
success: function(datos) {
$('[name=partida-' + id + ']').removeClass('verde');
$('[name=partida-' + id + ']').removeClass('rojo');
$('[name=partida-' + id + ']').removeClass('naranja_o');
$('[name=partida-' + id + ']').removeClass('naranja_c');
}
});
}
</script>
<?php
