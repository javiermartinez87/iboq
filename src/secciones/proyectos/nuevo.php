<?php
	include ('./control/seguridad.php');
?>
<style>
	.t_app{
	color:#3F51B5;
	width: 100%;
	padding-left:20px;
	}
	
	.info-app{
	text-align: justify;
	}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<style>
    .alert-danger.oculto{height:0px;overflow:hidden;padding:0;min-height:0px;border:none;}
</style>
<script>
	
    $(document).ready(function() {
        $('body').on('click', '[name=aceptar]', function(e) {
            var value = $('[name=sube_proyecto]').val();
            var error = false;
			
            $('#form_sube_proyecto input[type=text]').each(function() {
                if ($(this).val() == '' || $(this).val().length > $(this).attr('maxlength')) {
                    $('[name=error_' + $(this).attr('name') + ']').removeClass('oculto', 500);
                    error = true;
					} else {
                    $('[name=error_' + $(this).attr('name') + ']').addClass('oculto', 500);
				}
			});
			
			
			
			
            if (value === '' || value === null) {
                $(this.parentNode.getElementsByClassName("texto_file")[0]).val('Seleccione un fichero');
                $('[name=error_fichero]').removeClass('oculto', 500);
                error = true;
				} else {
                $('[name=error_fichero]').addClass('oculto', 500);
			}
			
            if (error == false) {
                $(this.parentNode.getElementsByClassName("texto_file")[0]).val(value);
                cargando_indefinido('Processing data, please, be patient ...');
                var name = $(this).attr('name');
                $('#form_sube_proyecto').submit();
			}
			
		});
		
        var map;
        google.maps.event.addDomListener(window, 'load', inicializa_mapa);
		
        $("[name=fecha]").datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(value) {
                $('[name=fecha]').val(value);
			}
		});
	});
	
    function inicializa_mapa() {
        $('[name=lat]').val(37.177219);
        $('[name=lon]').val(-3.600100);
        var myLatlng = new google.maps.LatLng(37.177219, -3.600100);
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
		
        google.maps.event.addListener(marker, 'dragend', function()
        {
            var position = marker.getPosition();
            $('[name=lat]').val(position['A']);
            $('[name=lon]').val(position['F']);
		});
		
	}
	
    function muestra_proyecto(actual) {
        var param = 'actual=' + actual;
        cambia_menu('ver_proyecto', param);
	}
	
	
    function analiza_proyectos(porcentaje, iniciales, analizadas) {
        if (iniciales !== -1)
		cargando_porcentaje('' + analizadas + ' work descriptions have been analyzed from ' + iniciales, porcentaje);
        else
		cargando_porcentaje('Analyzing work descriptions', porcentaje);
        $.ajax({
            type: 'POST', url: '/secciones/proyectos/modulos/analisis/analizar_proyectos.php',
            data: 'proyectos=' + $('#partidas_selec').val() + '&iniciales=' + iniciales,
            dataType: "JSON", success: function(datos) {
                if (datos != 'fin') {
                    analiza_proyectos(datos.porcentaje, datos.iniciales, datos.analizadas);
					} else {
					
                    remove_modal();
				}
			}
		});
	}
	
</script>


<h1>New project</h1>
<form id="form_sube_proyecto" target="ejecuta" action="/secciones/proyectos/modulos/subir_proyecto_presto.php" method="post" enctype="multipart/form-data">
    <div  class="row">
        <div class="col-sm-6">
            <div class="row">
                <input class="big col-sm-12" type="text" placeholder="Project name" name="nombre" maxlength="20"/>
                <div class="col-sm-12 alert alert-danger oculto" role="alert" name='error_nombre'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    Project name must be entered
				</div>
			</div>
            <div class="row">
                <input class="col-sm-6" type="text" placeholder="Country" name="pais"  maxlength="20">
                <input class="col-sm-6" type="text" placeholder="State" name="comunidad"  maxlength="30">
				
                <div class="col-sm-6 alert alert-danger oculto" role="alert"  name='error_pais'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    Country must be entered
				</div>
                <div class="col-sm-6 alert alert-danger oculto" role="alert"  name='error_comunidad'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    State must be entered
				</div>
			</div>
            <div class="row">
                <input class="col-sm-6" type="text" placeholder="City" name="ciudad" maxlength="30">
                <input class="col-sm-6" type="text" placeholder="Location" name="poblacion" maxlength="30">
				
                <div class="col-sm-6 alert alert-danger oculto" role="alert"  name='error_ciudad'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    City must be entered
				</div>
                <div class="col-sm-6 alert alert-danger oculto" role="alert"  name='error_poblacion'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    Location must be entered
				</div>
			</div>
            <div class="row">
                <input class="col-sm-6" type="text" placeholder="Typology" name="tipologia" maxlength="20">
                <input class="col-sm-6" value="<?= date('Y-m-d') ?>" name="fecha"/>
                <input class="col-sm-3 small" type="hidden" name="lat">
                <input class="col-sm-3 small" type="hidden" name="lon">
				
                <div class="col-sm-6 alert alert-danger oculto" role="alert"  name='error_tipologia'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    Project typology must be entered
				</div>
				
			</div>
            <div class="row">
                <textarea class="col-sm-12 big" placeholder="Project description" name="descripcion"></textarea>
			</div>
            <div class="row">
                <div class='input'>
					
				<h4 class="t_app">Bill of Quantities document</h4>
                    <div class="boton_file" onclick="subir_fichero('sube_proyecto')"></div>
                    <input type='file' name='sube_proyecto' />
                    <div class='estado'></div>
				</div>
				<div class='input'>
					
				<h4 class="t_app">Select Schema</h4>
                    <div class="boton_file" onclick=""></div>
                    <input type='file' name='sube_schema' />
                    <div class='estado'></div>
				</div>
                <div class="col-sm-12 alert alert-danger oculto" role="alert"  name='error_fichero'>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    Project typology must be entered
				</div>
			</div>
			<div class="row text_center">
				<input type="button" name='aceptar' value="accept" class="btn btn-primary">
			</div>
		</div>
        <div class="col-sm-6">
            <div class="mapa" id="mapa"></div>
		</div>
	</div>
</form>
<iframe id='ejecuta' name="ejecuta" style="display:none;"></iframe>
