<?php
	include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');
?>
<script>
    $(document).ready(function() {
		$("[name=busqueda]").keyup(function(event){
			if(event.keyCode == 13){
				busqueda();
			}
		});
		
        $("#sl_cantidad").slider({
            range: true,
            min: $('[name=min_cantidad]').val(),
            max: $('[name=max_cantidad]').val(),
            values: [$('[name=min_cantidad]').val(), $('[name=max_cantidad]').val()],
            slide: function(event, ui) {
                $('[name=min_cantidad]').val(ui.values[ 0 ]);
                $('[name=max_cantidad]').val(ui.values[ 1 ]);
			}
		});
		
        $('[name=form_busqueda]').on('change', '[name=min_cantidad], [name=max_cantidad]', function() {
            $("#sl_cantidad").slider('option', 'values', [$('[name=min_cantidad]').val(), $('[name=max_cantidad]').val()]);
		});
		
        $("#sl_presupuesto").slider({
            range: true,
            min: $('[name=min_presupuesto]').val(),
            max: $('[name=max_presupuesto]').val(),
            values: [$('[name=min_presupuesto]').val(), $('[name=max_presupuesto]').val()],
            slide: function(event, ui) {
                $('[name=min_presupuesto]').val(ui.values[ 0 ]);
                $('[name=max_presupuesto]').val(ui.values[ 1 ]);
			}
		});
		
        $('[name=form_busqueda]').on('change', '[name=min_presupuesto], [name=max_presupuesto]', function() {
            $("#sl_presupuesto").slider('option', 'values', [$('[name=min_presupuesto]').val(), $('[name=max_presupuesto]').val()]);
		});
		
        $('[name=result]').on('change', '.partidab input[type=checkbox]', function() {
            actualiza_info();
		});
	});
	
    function busqueda() {		
        var value = $('[name=busqueda]').val();
		
        if (value.trim() !== '') {
			cargando_indefinido("Searching data") ;
            $.post('/secciones/proyectos/modulos/busca_partidas.php', $('[name=form_busqueda]').serialize(), function(datos) {
                $('[name=result]').html(datos);
                actualiza_info();
				remove_modal();
			});
		}
	}
	
    function actualiza_info() {
        var cantidades = 0;
        var cont = 0;
        var presupuesto = 0;
        var coste = 0;
        var medidas = null;
		
        $('.partidab  input[type=checkbox]:checked').each(function() {
            var partida = $(this).parent().parent().parent();
			
            medidas = $(partida.children('.cantidades')[0]).children('div');
            cantidades += parseFloat($($(medidas[0]).children('label')[0]).text());
            presupuesto += parseFloat($($(medidas[1]).children('label')[0]).text());
            coste += parseFloat($($(medidas[2]).children('label')[0]).text());
            cont++;
		});
		
        if (cantidades !== 0)
		cantidades = cantidades / cont;
        if (presupuesto !== 0)
		presupuesto = presupuesto / cont;
        if (coste !== 0)
		coste = coste / cont;
		
        $('[name=costmedia]').text(coste.toFixed(2));
        $('[name=cmedia]').text(cantidades.toFixed(2));
        $('[name=pmedia]').text(presupuesto.toFixed(2));
        $('[name=nres]').text(cont);
		
	}
	
    function ninguna() {
        $('.partidab  input[type=checkbox]:checked').each(function() {
            $(this).removeProp('checked');
		});
        actualiza_info()
	}
	
    function todas() {
        $('.partidab  input[type=checkbox]:not(:checked)').each(function() {
            $(this).prop('checked', 'checked');
		});
        actualiza_info()
	}
</script>

<div class='row'>
    <div class="col-sm-4" >
		<div class='row'>
			<div class="col-sm-12 col-xs-6" >
				<form name="form_busqueda">
					<h3>Filters</h3>
					<input type="text" name="busqueda" placeholder="i.e: concrete" />
					<div class="clr"></div>
					
					<p class="cantidad_lab" name="cantidad_lab">Amount between <input type="number" min="0" max="8000" value="0" name="min_cantidad" /> and <input min="0" max="8000" type="number" value="8000" name="max_cantidad" /></p>
					<div class="content_slider"><div class="slider" id="sl_cantidad"></div></div>
					
					<p class="cantidad_lab" name="presupuesto_lab">Cost between <input type="number" min="0" max="8000" value="0" name="min_presupuesto" /> and <input min="0" max="8000" type="number" value="8000" name="max_presupuesto" /></p>
					<div class="content_slider"><div class="slider" id="sl_presupuesto"></div></div>
					
				</form>
				<div class="clr"></div>
				<input type="button" onclick="busqueda()" value="Search"/>
				<div class="clr"></div>
			</div>
			<style>
				.cuadrado{
				border:1px solid #3F51B5;border-radius: 10px;max-width: 280px;
				}
			</style>
			<div class="col-sm-12 col-xs-6 cuadrado" style="margin-top:20px;">
				<div class="info">
					Work descriptions selected: <label name="nres">-</label> 
					<br/>
					Average amount (A): <label name="cmedia">-</label>
					<br/>
					Average cost (C): <label name="pmedia">-</label> €
					<br/>
					Total (T): <label name="costmedia">-</label> €
				</div>
				<div class="clr"></div>
			</div>
		</div>
	</div>
    <div class="col-sm-8">
        <div class="botonera_sup">
			Selection:
            <input type="radio" name="seleccion" checked="checked" onclick="todas();"/>All
            <input type="radio"  name="seleccion" onclick="ninguna();"/>None 
		</div>
        <div  name="result"></div>
	</div>
</div>