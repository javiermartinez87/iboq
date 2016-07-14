<?php

include ($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
include ($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
include ($_SERVER ['DOCUMENT_ROOT'] . '/secciones/proyectos/modulos/analisis/stop_words.php');

error_reporting(~E_NOTICE);

function analiza($datos, &$p_limpia) {
    $descripcion = strtolower($datos ['descripcion']);
    $datos_palabras = array();

    limpia_desc_completa($descripcion, $palabras, $posicion_palabra);

    $copy_pal = array();
    foreach ($palabras as $p) {
        if (trim($p) != '[ BASURA ]' && trim($p) != '')
            array_push($copy_pal, trim($p));
    }
    $palabras = $copy_pal;
    $p_limpia = implode(', ', $palabras);

    $owa_capitulos = owa_capitulo($palabras, $posicion_palabra);

    // Busco solo 1
    $owa_subcapitulos = array();

    // Busco solo 1
    $keys = array_keys($owa_capitulos);

    $owa_subcapitulos = owa_subcapitulo($palabras, $keys[0], $posicion_palabra);
    $keys_sub = array_keys($owa_subcapitulos);
    $clase = reglas_clasificacion($keys_sub, $owa_subcapitulos);

    return array('clase'=>$clase,'valor'=>$keys_sub[0]);
}

function reglas_clasificacion($keys, $values, $trunca = 0) {
    $clase = 'verde';
    if ($values[$keys[0]] <= 0.749) {
        $clase = 'rojo';
    }elseif ($values[$keys[0]] <= 0.869) {
        $clase = 'rojo_c';
    } elseif ($values[$keys[0]] <= 0.899) {
        $clase = 'naranja_o';
    } elseif ($values[$keys[0]] <= 0.969) {
        $clase = 'naranja_c';
    }	 
    return $clase;
}

/*
 *
 */

function owa_subcapitulo($palabras, $id_capitulo, $posicion_palabra) {
    include ($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');

    $v_owa_1 = array(
        0.5,
        0.4,
        0.1
    );
    $v_owa_2 = array(
        0.6,
        0.2,
        0.1,
        0.1
    );

    $datos_palabras = array();
    $las_palabras = (join("','", $palabras));

    $sql = "select distinct codigo from subcapitulo where id_capitulo = '$id_capitulo' order by id_capitulo, codigo ASC";

    $result = $mysqli->query($sql);

    while ($sub = $result->fetch_assoc()) {
        $cap = $sub ['codigo'];
        $datos_palabras [$cap] = array();
        $sql = "select p.palabra,ws,wf,wp from peso_subcapitulo ps inner join palabra p on p.id = ps.id_palabra and p.palabra in ('$las_palabras') and cod_subcapitulo = '$cap'";

        $result2 = $mysqli->query($sql);
        while ($dt = $result2->fetch_assoc()) {
            if (trim($dt ['palabra']) != '[ BASURA ]') {
                $dt ['palabra'] = limpia_palabra(($dt ['palabra']));

                if (!isset($dt ['palabra'])) {
                    $pos = 100;
                } else {
                    $pos = $posicion_palabra[$dt ['palabra']];
                }
                $v = array(
                    $dt ['ws'], $dt ['wf'], (1 - ($pos / count($palabras)))
                );

                $datos_palabras [$cap] [$dt ['palabra']] ['ws'] = $v[0];
                $datos_palabras [$cap] [$dt ['palabra']] ['wf'] = $v[1];
                $datos_palabras [$cap] [$dt ['palabra']] ['wp'] = $v[2];

                rsort($v);
                $owa = 0;
                for ($i = 0; $i < count($v_owa_1); $i ++) {
                    $owa += ($v_owa_1 [$i] * $v [$i]);
                }
                $datos_palabras [$cap] [$dt ['palabra']] ['owa'] = $owa;
            }
        }
    }

    $owa_subcapitulos = array();

    foreach ($datos_palabras as $k => $array_cap) {
        uasort($array_cap, 'cmp');
        $datos_palabras [$k] = $array_cap;

        $owa = 0;
        $i = 0;
        foreach ($array_cap as $palabra => $valor) {
            if ($i < count($v_owa_2)) {
                $owa += ($v_owa_2 [$i] * $valor ['owa']);
            }
            $i ++;
        }

        $owa_subcapitulos [$k] = $owa;
    }
    arsort($owa_subcapitulos);
    return $owa_subcapitulos;
}

/*
 *
 */

function owa_capitulo($palabras, $posicion_palabra) {
    include ($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');

    $v_owa_1 = array(
        0.5,
        0.4,
        0.1
    );
    $v_owa_2 = array(
        0.6,
        0.1,
        0.1,
        0.1,
        0.1
    );

    $las_palabras = (join("','", $palabras));
    $sql = "select distinct id from capitulo where id <> '16' and id <> '17' order by id ASC";
    $result = $mysqli->query($sql);

    while ($sub = $result->fetch_assoc()) {

        $cap = $sub ['id'];
        $datos_palabras [$cap] = array();
        $frecuencias = array();

        $sqlf1 = $mysqli->query("select sum(ps.f) as f, p.palabra from peso_subcapitulo ps inner join palabra p on p.id = ps.id_palabra where  
                             id_capitulo = '$cap' and p.palabra in ('$las_palabras') group by ps.id_capitulo, p.id");

        $sqlf2 = $mysqli->query("select sum(ps.f) as f, p.palabra from peso_subcapitulo ps inner join palabra p on p.id = ps.id_palabra where  
                            p.palabra in ('$las_palabras') group by p.id");


        while ($fre = $sqlf1->fetch_assoc()) {

            $frecuencias [limpia_palabra(($fre ['palabra']))] = $fre ['f'];
        }

        while ($fre = $sqlf2->fetch_assoc()) {

            if (isset($frecuencias [limpia_palabra(($fre ['palabra']))]) && $fre ['f'] != 0) {
                $frecuencias [limpia_palabra(($fre ['palabra']))] = $frecuencias [limpia_palabra(($fre ['palabra']))] / $fre ['f'];
            } else {
                $frecuencias [limpia_palabra(($fre ['palabra']))] = 0;
            }
        }
        $sql = "select p.palabra,max(ws) as ws,max(wf) as wf,max(wp) as wp from peso_subcapitulo ps
                        inner join palabra p on p.id = ps.id_palabra and p.palabra in ('$las_palabras') and ps.id_capitulo = '$cap'  group by p.palabra";

        $result2 = $mysqli->query($sql);
        while ($dt = $result2->fetch_assoc()) {
            if (trim($dt ['palabra']) != '[ BASURA ]') {
                $wf = 0;
                $dt ['palabra'] = limpia_palabra(($dt ['palabra']));

                if (isset($frecuencias [$dt ['palabra']])) {
                    $wf = $frecuencias [$dt ['palabra']];
                }

                if (!isset($posicion_palabra[$dt ['palabra']])) {
                    $pos = count($palabras);
                } else {
                    $pos = $posicion_palabra[$dt ['palabra']];
                }


                $v = array(max(0, $dt ['ws']), max(0, $wf), (1 - ($pos / count($palabras))));
                $datos_palabras [$cap] [$dt ['palabra']] ['ws'] = $v[0];
                $datos_palabras [$cap] [$dt ['palabra']] ['wf'] = $v[1];
                $datos_palabras [$cap] [$dt ['palabra']] ['wp'] = $v[2];

                rsort($v);

                $owa = 0;
                for ($i = 0; $i < count($v_owa_1); $i ++) {
                    $owa += ($v_owa_1 [$i] * $v [$i]);
                }

                $datos_palabras [$cap] [$dt ['palabra']] ['owa'] = $owa;
            }
        }
    }


    $owa_capitulos = array();
    foreach ($datos_palabras as $k => $array_cap) {
        uasort($array_cap, 'cmp');
        $datos_palabras [$k] = $array_cap;

        $owa = 0;
        $i = 0;
        foreach ($array_cap as $palabra => $valor) {
            if ($i < count($v_owa_2)) {
                $owa += ($v_owa_2 [$i] * $valor ['owa']);
            }
            $i ++;
        }
        $owa_capitulos [$k] = $owa;
    }



    arsort($owa_capitulos);
    return $owa_capitulos;
}

/*
 *
 */

function cmp($a, $b) {
    if ($a ['owa'] == $b ['owa']) {
        return 0;
    }
    return ($a ['owa'] > $b ['owa']) ? - 1 : 1;
}

/*
 *
 */

function limpia_palabra($p) {
    $busca = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ü', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', '/', '.', '+', '_', "'", ":", ")");
    $reemplaza = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'u', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

    $reemplazado = str_replace($busca, $reemplaza, $p);
    return trim($reemplazado);
}

/*
 *
 */

function limpia_palabra2($p) {
    $busca = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', '/', '.', '+', '_', "'", ":", ")");
    $reemplaza = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

    $reemplazado = str_replace($busca, $reemplaza, $p);
    return trim($reemplazado);
}

/*
 *
 */

function limpia_desc_completa($descripcion, &$palabras, &$posicion_palabra) {
    $descripcion = str_replace("p.p.", " parte proporcional ", $descripcion);
    $descripcion = str_replace("i/", " incluso ", $descripcion);
    $descripcion = str_replace("nº.", " v ", $descripcion);
    $descripcion = str_replace("tmax", " tamaño máximo ", $descripcion);
    $descripcion = str_replace(" CEM .", " cemento ", $descripcion);


    $palabras = explode(' ', limpia_palabra((strtolower($descripcion))));
    $palabras = cambia_sinonimos_1($palabras);
    $palabras = elimina_stopWords($palabras);
    $posicion_palabra = calculo_las_posiciones($palabras);
}

/**/

function asocia_palabras($descripcion) {
    $palabras = array();
    $descripcion = str_replace("p.p.", " parte proporcional ", $descripcion);
    $descripcion = str_replace("i/", " incluso ", $descripcion);
    $descripcion = str_replace("nº.", " v ", $descripcion);
    $descripcion = str_replace("tmax", " tamaño máximo ", $descripcion);
    $descripcion = str_replace(" CEM .", " cemento ", $descripcion);


    $palabras = explode(' ', limpia_palabra((strtolower($descripcion))));
    $palabras = cambia_sinonimos_1($palabras);

    $result = array();
    foreach (explode(' ', limpia_palabra2($descripcion)) as $k => $p) {
        $result[$palabras[$k]] = $p;
    }
    return $result;
}

function cambia_sinonimos_1($palabras) {
    include ($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');

    $palabras_buscar = join("','", $palabras);

    $query = $mysqli->query("select * from sinonimos where palabra in ('" . $palabras_buscar . "')");
    $sinonimos = array();
    while ($datos = $query->fetch_assoc()) {
        $sinonimos[limpia_palabra(($datos['palabra']))] = limpia_palabra(($datos['representante']));
    }

    foreach ($palabras as $k => $p) {
        if (isset($sinonimos[$p])) {
            $palabras[$k] = $sinonimos[$p];
        }
    }

    unset($sinonimos);
    $palabras_buscar = (join("','", $palabras));
    $query = $mysqli->query("select p.id, p.palabra as palabra, p2.palabra as representante "
            . "from palabra p left outer join palabra p2 on p2.id = p.representante where p.palabra in ('$palabras_buscar')");

    while ($datos = $query->fetch_assoc()) {
        if ($datos ['representante'] != '' && $datos ['representante'] != null) {
            $sinonimos [limpia_palabra(($datos ['palabra']))] = limpia_palabra(($datos ['representante']));
        }
    }

    foreach ($palabras as $k => $p) {
        if (isset($sinonimos[$p])) {
            $palabras[$k] = $sinonimos[$p];
        }
    }

    return $palabras;
}

function calculo_las_posiciones($palabras) {
    $posicion_palabra = array();

    $contador = 0;
    foreach ($palabras as $k => $p) {
        if (!isset($posicion_palabra[$p]) && trim($p) != '' && trim($p) != '[ BASURA ]') {
            $posicion_palabra[$p] = $contador;
            $contador++;
        } else if (isset($posicion_palabra[$p])) {
            $posicion_palabra[$p . rand(0, 1000)] = $contador;
            $contador++;
        }
    }

    return $posicion_palabra;
}
