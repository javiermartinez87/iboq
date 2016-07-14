<?php

include ($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
include ($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
include ($_SERVER ['DOCUMENT_ROOT'] . '/secciones/proyectos/modulos/analisis/funciones_analisis.php');


require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/Writer/Excel2007.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/IOFactory.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/include/class_proyecto.php';
include $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/estilos_excel.php';

$n_analizar = 100000;
if ($_SESSION['modo'] == 'pruebas') {
    $_SESSION['depurando'] = 'Si';
}

$proyectos_analizar = $_POST ['proyectos'];
$proyectos_analizar = str_replace(",", "','", $proyectos_analizar);

$iniciales = 0;
$metodo = 3;
$cmp = 'cap';
$msg_metodo = '';
switch ($metodo) {
    case 0:
        $cmp = 'scap';
        $msg_metodo = 'Mejor subcapitulo del primer capitulo';
        break;
    case 1:
        $cmp = 'scap';
        $msg_metodo = 'Mejor subcapitulo de los dos primeros capitulos';
        break;
    case 2:
        $cmp = 'cap';
        $msg_metodo = 'Compruebo el capitulo';
        break;
    case 3:
        $cmp = 'cap';
        $msg_metodo = 'Arrboles';
        break;
}


$sql = "select distinct pa.*, cr.clasificacion from proyecto p  inner join partida pa on pa.id_proyecto = p.id
 and pa.id_subcapitulo is null  or pa.id_subcapitulo = ''
 left outer join clasifi_real cr on cr.id_partida = pa.id
where p.idusuario = '$_SESSION[idusuario]' and p.id in ('$proyectos_analizar')";

$n_cap = 3;
$n_scap = 0;
$result = $mysqli->query($sql);
$aciertos = 0;
$errores = 0;
$dudas = 0;

if (!$sql || $result->num_rows == 0) {
    echo json_encode('fin');
} else {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Javier Martínez");
    $objPHPExcel->getProperties()->setTitle("Partidas");
    $objPHPExcel->getProperties()->setSubject("Partidas");

    $col = 0;
    $row = 6;
    $objPHPExcel->setActiveSheetIndex(0);

    $realizan = $result->num_rows;

    //Imprimo cabeceras
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'ID');
    $col++;
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'Cap ' . $i);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col + 1, $row, '%');
    $col+=2;
    for ($j = 1; $j <= $n_scap; $j++) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, 'SCap ' . $j);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col + 1, $row, '%');
        $col+=2;
    }
    $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex(0) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex((2 * $n_scap) + 2) . $row)->applyFromArray($cabecera);

    // Fin cabeceras  
    $row++;
    $col = 0;
    $i = 1;
    while ($datos = $result->fetch_assoc()) {
        $col = 0;
        $id_subcap = analiza_depuracion($datos, $p_limpia, $n_cap, $n_scap);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $i . ' ' . $datos['clasificacion']);
        $objPHPExcel->getActiveSheet()->mergeCells(PHPExcel_Cell::stringFromColumnIndex($col) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($col) . ($row + $n_cap - 1));

        $real = $datos['clasificacion'];
        $clas_en = validacion($metodo, $id_subcap);
        if ($clas_en != false) {
            if ($cmp === 'scap') {
                if ($real === $clas_en) {
                    $style = $verde;
                    $aciertos++;
                } else {
                    $style = $rojo;
                    $errores++;
                }
            } else if ($metodo >= 2) {
                $real2 = explode(' ', $real);
                $real2 = trim($real2[0]);
                if ($real2 === $clas_en) {
                    $style = $verde;
                    $aciertos++;
                } else {
                    $style = $rojo;
                    $errores++;
                }
            }
        }else{
            $dudas++;
        }

        $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($col) . $row)->applyFromArray($style);
        $col++;

        foreach ($id_subcap['capitulos'] as $id => $v) {
            $col = 1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col + 1, $row, number_format($v, 2));
            $col+=2;
            foreach ($id_subcap['subcapitulos'][$id] as $ids => $vs) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col + ($cont * 2), $row, $ids);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col + ($cont * 2) + 1, $row, number_format($vs, 2));
                if ($datos['clasificacion'] === $ids) {
                    $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($col + ($cont * 2)) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($col + ($cont * 2) + 1) . $row)->applyFromArray($verde);
                }if ($real !== $clas_en && $ids === $clas_en) {
                    $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex($col + ($cont * 2)) . $row . ':' . PHPExcel_Cell::stringFromColumnIndex($col + ($cont * 2) + 1) . $row)->applyFromArray($amarillo);
                }
                $col+=2;
            }
            $row++;
        }
        $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex(0) . ($row - $n_cap) . ':' . PHPExcel_Cell::stringFromColumnIndex((2 * $n_scap) + 2) . $row)->applyFromArray($sinbordes);
        $objPHPExcel->getActiveSheet()->getStyle(PHPExcel_Cell::stringFromColumnIndex(0) . ($row - 1) . ':' . PHPExcel_Cell::stringFromColumnIndex((2 * $n_scap) + 2) . ($row - 1))->applyFromArray($border_botom);


        $i++;
        //$query = "update partida set id_subcapitulo = '$id_subcap', p_limpia = '$p_limpia' where id = '" . $datos['id'] . "'";
        //$mysqli->query($query);
    }

    $objPHPExcel->getActiveSheet()->mergeCells(PHPExcel_Cell::stringFromColumnIndex(0) . '1:' . PHPExcel_Cell::stringFromColumnIndex((2 * $n_scap) + 2) . '1');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $msg_metodo);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Total');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $aciertos + $errores+$dudas);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 3, 'Aciertos');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 3, $aciertos);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'Errores');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, $errores);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 5, 'Dudas');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 5, $dudas);

    $objPHPExcel->getActiveSheet()->setTitle('Simple');
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nombre = 'excel_dep.xlsx';
    $objWriter->save($nombre);

    echo '<a href="/secciones/depuracion/modulos/' . $nombre . '">Descargar</a>';
}

function analiza_depuracion($datos, &$p_limpia, $n_cap, $n_scap) {
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
    $resultados_cap = array();
    $resultados_scap = array();

    $keys = array_keys($owa_capitulos);
    for ($res = 0; $res < $n_cap; $res++) {
        $resultados_cap['C' . $keys[$res]] = $owa_capitulos[$keys[$res]];
        $owa_subcapitulos = owa_subcapitulo($palabras, $keys[$res], $posicion_palabra);
        $keys_sub = array_keys($owa_subcapitulos);
        $resultados_scap['C' . $keys[$res]] = array();
        for ($ress = 0; $ress < $n_scap; $ress++) {
            if (isset($keys_sub[$ress]))
                $resultados_scap['C' . $keys[$res]][$keys_sub[$ress]] = $owa_subcapitulos[$keys_sub[$ress]];
        }
    }

    return array('capitulos' => $resultados_cap, 'subcapitulos' => $resultados_scap);
}

function validacion($metodo, $resultados) {
    $capitulos = $resultados['capitulos'];
    $subcapitulos = $resultados['subcapitulos'];

    switch ($metodo) {
        case 0:
            // Primer capitulo, primer subcapitulo
            $keys = array_keys($capitulos);
            $cap = $keys[0];
            $keyss = array_keys($subcapitulos[$cap]);
            return $keyss[0];
            break;
        case 1:
            // Mejor subcapitulo entre dos capitulos
            $keys = array_keys($capitulos);
            $cap = $keys[0];
            $cap2 = $keys[1];
            $keyss = array_keys($subcapitulos[$cap]);
            $keyss2 = array_keys($subcapitulos[$cap2]);
            if ($subcapitulos[$cap][$keyss[0]] >= $subcapitulos[$cap2][$keyss2[0]]) {
                $mejor = $keyss[0];
            } else {
                $mejor = $keyss2[0];
            }
            return $mejor;
            break;
        case 2:
            // Primer capitulo, primer subcapitulo
            $keys = array_keys($capitulos);
            $cap = $keys[0];
            return $cap;
            break;
        case 3:
            // Reglas
            $keys = array_keys($capitulos);
            $sigo = reglas_clasificacion($keys, $capitulos,1);
            return $sigo;
            break;
    }
}
