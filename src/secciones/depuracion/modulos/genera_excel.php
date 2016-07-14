<?php

include ($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/Writer/Excel2007.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/lib/phpExcel/Classes/PHPExcel/IOFactory.php';
require_once $_SERVER ['DOCUMENT_ROOT'] . '/include/class_proyecto.php';


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="fichero.xls"');
header('Cache-Control: max-age=0');


$objPHPExcel = new PHPExcel();

// Set properties

$objPHPExcel->getProperties()->setCreator("Javier Martínez");
$objPHPExcel->getProperties()->setTitle("Partidas");
$objPHPExcel->getProperties()->setSubject("Partidas");


// Add some data
$col = 0;
$row = 1;
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'ID_PARTIDA');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'id_proyecto');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'nombre');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'descripcion');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'cantidad');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'presupuesto');
$query = $mysqli->query('select * from pr_partida');
$row++;
while ($datos = $query->fetch_assoc()) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $datos['id_partida']);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $datos['nombre_proyecto']);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, utf8_encode($datos['nombre_partida']));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, utf8_encode($datos['descripcion']));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $datos['cantidad']);
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $datos['presupuesto']);
    $row++;
}



$objPHPExcel->getActiveSheet()->setTitle('Simple');

// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

$objWriter->save('php://output');
