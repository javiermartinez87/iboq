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
</script>

<p>&nbsp;</p>
<p>&nbsp;</p>
 <h1>Subir fichero de sinonimos</h1>
    <div class='center' style='width: 490px;'>
        <form id="form_sube_sinonimos" target="ejecuta" action="/modulos/depuracion/subir_sinonimos.php" method="post" enctype="multipart/form-data">

            <div class='input'>
                <input class="texto_file" value="Seleccione un fichero" disabled="disabled" />
                <div class="boton_file" onclick="subir_fichero('sube_sinonimos')"></div>
                <input type='file' name='sube_sinonimos' />
                <div class='estado'></div>
            </div>
        </form>
    </div>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

