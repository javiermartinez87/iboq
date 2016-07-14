<?php

include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');

require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/Reader/Excel2007.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/IOFactory.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/include/class_proyecto.php';

$path = $_FILES ['sube_proyecto'] ['tmp_name'];
$fileInfo = pathinfo($_FILES ['sube_proyecto'] ['name']);
$extension = $fileInfo ['extension'];
if (strcmp(trim($extension), 'xlsx') == 0) {
    if (!file_exists($path)) {
        echo '<script>this.parent.error_modal("No se puede leer el fichero introducido");</script>';
        exit();
    }

    $col_naturaleza = 1;

    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($path);
    $objPHPExcel->setActiveSheetIndex(0);
    $doc = $objPHPExcel->getActiveSheet();
    $filas = $objPHPExcel->getActiveSheet()->getHighestRow();


    $contPartida = 0;

    $proyecto = new Proyecto($_POST['nombre'], $_POST['pais'], $_POST['comunidad'], $_POST['ciudad'], $_POST['poblacion'], $_POST['tipologia'], $_POST['descripcion'], $_POST['fecha'], $_POST['lat'], $_POST['lon']);


    for ($i = 1; $i <= $filas; $i ++) {
        $desc = $doc->getCellByColumnAndRow(0, $i)->getValue();
        $desc = str_replace("'", '', $desc);
        $desc = str_replace('"', '', $desc);
        $real = $doc->getCellByColumnAndRow(1, $i)->getValue();
        $nom = 'Partida: ' . $real;
        $proyecto->add_partida(new Partida($i, $nom, $desc, 0, 0, $real));
        $cod = $nom = $desc = '';
    }

    $proyecto->guarda_bd();
    $datos = $proyecto->devuelve_datos();
     echo '<script>this.parent.analiza_proyectos("'.$datos['id'].'");</script>';
} else {
    echo '<script>this.parent.error_modal("El fichero introducido es incorrecto");</script>';
}

