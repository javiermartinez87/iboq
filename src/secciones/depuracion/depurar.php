<?php
include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');
?>


<script>
    $(document).ready(function() {
        $('body').on('change', 'input[type=file]', function() {
            var value = $(this).val();
            if (value === '' || value === null) {
                $(this.parentNode.getElementsByClassName("texto_file")[0]).val('Seleccione un fichero');
            } else {
                $(this.parentNode.getElementsByClassName("texto_file")[0]).val(value);
                cargando_indefinido('Procesando datos, Por favor espere...');
                var name = $(this).attr('name');
                $('#form_' + name).submit();
            }

        });

    });


    function muestra_proyecto(actual) {
        var param = 'actual=' + actual;
        cambia_menu('ver_proyecto', param);
    }

    // Funciones para modo pruebas
    function analiza_proyectos(id) {
        cargando_indefinido('Se esta comprobando los resultados, espere...');
        $.ajax({
            type: 'POST', url: '/secciones/depuracion/modulos/analizar_proyecto_depuracion.php',
            data: 'proyectos=' + id,
            dataType: "HTML", success: function(datos) {
                $('.body').html(datos);
                remove_modal();
            }
        });
    }


</script>


<div class='clr'></div>
<h1>Nuevo proyecto</h1>
<form id="form_sube_proyecto" target="ejecuta" action="/secciones/depuracion/modulos/subir_proyecto_depuracion.php" method="post" enctype="multipart/form-data">
    <div style="width:800px;margin:auto;">
        <div style="width:500px;float:left;">
            <div class="input">
                <input class="big" type="text" placeholder="Nombre del proyecto" onblur="" name="nombre">
                <div class="estado"></div>
            </div>

            <div class="clr"></div>
            <div class="input">
                <input class="medio" type="text" placeholder="Pa&iacute;s" onblur="" name="pais" value="Espa&ntilde;a">
                <div class="estado"></div>
            </div>

            <div class="input">
                <input class="medio" type="text" placeholder="Comunidad" onblur="" name="comunidad" value="Andaluc&iacute;a">
                <div class="estado"></div>
            </div>

            <div class="clr"></div>
            <div class="input">
                <input class="medio" type="text" placeholder="Ciudad" onblur="" name="ciudad" value="Granada">
                <div class="estado"></div>
            </div>

            <div class="input">
                <input class="medio" type="text" placeholder="Poblacion" onblur="" name="poblacion" value="Granada">
                <div class="estado"></div>
            </div>   

            <div class="clr"></div>

            <div class="input">
                <input class="medio" type="text" placeholder="Tipolog&iacute;a" onblur="" name="tipologia" value="P&uacute;blica">
                <div class="estado"></div>
            </div>

            <div class="input">
                <input class="small" type="hidden"  name="lat" value="0">
                <div class="estado"></div>
            </div>

            <div class="input">
                <input class="small" type="hidden"  name="lon" value="0">
                <div class="estado"></div>
            </div>

            <div class="clr"></div>
            <div id="fecha"></div>
            <input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>"/>
            <div class="clr"></div>
            <div class="input">
                <textarea class="big" placeholder="Descripci&oacute;n del proyecto" onblur="" name="descripcion"></textarea>
                <div class="estado"></div>
            </div>
            <div class="clr"></div>
            <div class='input'>
                <input class="texto_file" name="subir" value="Seleccione un fichero" disabled="disabled" />
                <div class="boton_file" onclick="subir_fichero('sube_proyecto')"></div>
                <input type='file' name='sube_proyecto' />
                <div class='estado'></div>
            </div>
        </div>
        <div style="background-color:#aaaaaa;width:300px;height:400px;float:left" id="mapa"></div>
    </div>
</form>
<div class='clr'></div>

