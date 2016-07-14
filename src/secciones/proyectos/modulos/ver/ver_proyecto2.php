<?php
include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');

$mostrar = $_POST ['actual'];

$query = "select distinct c.id as id, c.nombre as n_cap,su.* from proyecto p inner join partida pa on pa.id_proyecto = p.id 
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
    $proyectos [$datos ['id']] ['subcapitulos'] [$datos ['codigo']] = ($datos ['nombre']);
}

foreach ($proyectos as $id => $datos) {
    if ($id != '') {
        echo '<div class="capitulo" name="capitulo-' . $id . '">';
            echo '<div onclick="mostrar(' . $id . ');" class="cabecera" >';
            echo '<div  class="titulo">' . $datos ['nombre'] . '</div>';

            $cont = count($datos['subcapitulos']);
            $msg = "Contiene " . count($datos['subcapitulos']);
            if ($cont == 1) {
                $msg .= ' subcap&iacute;tulo';
            } else {
                $msg .= ' subcap&iacute;tulos';
            }

            echo '<div class="datos"><label class="coste">-</label><br/>' . $msg . '</div>';
            echo '</div>';
            echo '<div class="subcapitulos">';
            foreach ($datos ['subcapitulos'] as $idS => $datosS) {
                $idS = str_replace(' ', '_', $idS);
                echo '<div class="subcapitulo no_cargado" name="subcapitulo-' . $idS . '">';
                echo '<div onclick="mostrarS(\'' . $idS . '\');" class="cabecera" >';
                echo '<div class="titulo">' . $datosS . '</div>';
                echo '<div class="datos"></div>';
                echo '</div>';
                echo '<div  class="partidas"></div>';
                echo '</div>';
            }
            echo '</div>';
        echo '</div>';
    } else {
        echo '<div class="capitulo rojo" name="capitulo-sin_analizarc">';
            echo '<div onclick="mostrar(\'sin_analizarc\');" class="cabecera" >';
                echo '<div class="titulo">Partidas sin analizar</div>';
                echo '</div>';
                echo '<div class="subcapitulos">';
                    echo '<div class="subcapitulo no_cargado" name="subcapitulo-sin_analizars">';
                            echo '<div onclick="mostrarS(\'sin_analizars\');" class="cabecera" >';
                                echo '<div class="titulo">Partidas sin clasificar</div>';
                                echo '<div class="datos"></div>';                         
                            echo '</div>';     
                    echo '<div  class="partidas"></div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        
    }
}
?>
<script>
    function mostrar(id) {
        $('[name=capitulo-' + id + ']').toggleClass('abierto', 1000);
    }

    function mostrarS(id) {
        $('[name=subcapitulo-' + id + ']').toggleClass('abierto', 1000);
    }

    function actualiza_subcapitulo(proyecto, cod) {
        var contenedor = $('[name=subcapitulo-' + cod + ']');
        var parametros = 'mostrar=' + cod + '&proyecto=' + proyecto;
        var title = $('[name=subcapitulo-' + cod + '] .titulo').html();
        var partidas = $('[name=subcapitulo-' + cod + '] .partidas');
        $.ajax({
            type: 'POST', url: '/secciones/proyectos/modulos/ver/subcapitulo.php', dataType: "HTML", data: parametros,
            beforeSend: function(datos) {
                add_cargando_segundo('Cargando subcapitulos: ' + title);
            }, success: function(datos) {
                partidas.html(datos);
                contenedor.removeClass('no_cargado');
                if ($('[name^=subcapitulo-].no_cargado').length > 0) {
                    var cod = $('[name^=subcapitulo-].no_cargado:first').attr('name').split('-')[1];
                    actualiza_subcapitulo(proyecto, cod);
                } else {
                    remove_cargando_segundo();
                }
            }
        });
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
