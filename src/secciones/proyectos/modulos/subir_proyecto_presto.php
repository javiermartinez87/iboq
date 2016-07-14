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

    $proyecto = new Proyecto($_POST['nombre'], $_POST['pais'], $_POST['comunidad'], $_POST['ciudad'], $_POST['poblacion'], $_POST['tipologia'], nl2br($_POST['descripcion']), $_POST['fecha'], $_POST['lat'], $_POST['lon']);

    for ($i = 1; $i <= $filas; $i ++) {

        // Existe un valor en la columna de naturaleza
        if ($doc->cellExistsByColumnAndRow($col_naturaleza, $i) === true) {
            $nat = $doc->getCellByColumnAndRow($col_naturaleza, $i)->getValue();

            // Leemos el comienzo de una partida
            if ($nat == 'Partida') {
                $cod = $nom = $desc = '';
                // cojo el codigo de la partida
                if ($doc->cellExistsByColumnAndRow(0, $i) === true)
                    $cod = str_replace('.', '', $doc->getCellByColumnAndRow(0, $i)->getValue());

                // Cojo el nombre de la partida
                if ($doc->cellExistsByColumnAndRow(3, $i) === true)
                    $nom = $doc->getCellByColumnAndRow(3, $i)->getValue();

                // Cojo la descripcion				
                if ($doc->cellExistsByColumnAndRow(3, $i + 1) === true) {
                    $desc = $doc->getCellByColumnAndRow(3, $i + 1)->getValue();
                }

                $cant = 1;
                $pu = 0;

                if ($doc->cellExistsByColumnAndRow(4, $i) === true)
                    $cant = $doc->getCellByColumnAndRow(4, $i)->getCalculatedValue();
                else
                    $cant = 1;

                if ($doc->cellExistsByColumnAndRow(5, $i) === true)
                    $pu = $doc->getCellByColumnAndRow(5, $i)->getCalculatedValue();
                else
                    $pu = 0;

                $contPartida ++;
                if($desc == ''){$desc = $nom;}
                $nom = str_replace("'", "", $nom);
                $desc = str_replace("'", "", $desc);

                $nom = str_replace('"', "", $nom);
                $desc = str_replace('"', "", $desc);
                $proyecto->add_partida(new Partida($cod, $nom, $desc, $cant, $pu));
            }
            $cod = $nom = $desc = '';
        }
    }

    $proyecto->guarda_bd();
}else {
    echo '<script>this.parent.error_modal("El fichero introducido es incorrecto");</script>';
}

sleep(5);
$datos = $proyecto->devuelve_datos();
$id = $datos['id'];
$url = "/abrir/$id";

echo '<script>this.parent.location.href = "'.$url.'";</script>';
