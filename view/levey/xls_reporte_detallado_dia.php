<?php
ini_set('memory_limit', '2048M');
session_start();

if (!isset($_SESSION["labAccess"])) {
  header("location:../index.php");
  exit();
}
if ($_SESSION["labAccess"] <> "Yes") {
  header("location:../index.php");
  exit();
}
$labIdUser = $_SESSION['labIdUser'];
$labIdDepUser = $_SESSION['labIdDepUser'];

require_once '../../model/Levey.php';
$le = new Levey();
require_once '../../model/Dependencia.php';
$d = new Dependencia();


/** Include PHPExcel */
require_once '../../assets/lib/PHPExcel/Classes/PHPExcel.php';
require_once '../../assets/lib/PHPExcel/Classes/PHPExcel/IOFactory.php';

$file_path = "reporte_detallado_dia.xlsx";
$objPHPExcel = new PHPExcel();


$estiloInformacionTitulotprod = new PHPExcel_Style();
$estiloInformacionTitulotprod->applyFromArray(array(
  'font' => array(
    'name' => 'Calibri',
	'bold' => true,
    'size' => 9,
    'color' => array(
      'rgb' => '000000'
    )
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    )
  ),
  'alignment' => array(
    //'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
)
);

$estiloInformacion = new PHPExcel_Style();
$estiloInformacion->applyFromArray(array(
  'font' => array(
    'name' => 'Calibri',
    'size' => 9,
    'color' => array(
      'rgb' => '000000'
    )
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    ),
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    )
  ),
)
);

$estiloNumero = new PHPExcel_Style();
$estiloNumero->applyFromArray(array(
  'font' => array(
    'name' => 'Calibri',
	'bold' => true,
    'size' => 11,
    'color' => array(
      'rgb' => '000000'
    )
  ),
  'numberformat' => array(
    'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    ),
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    )
  ),
)
);

$estiloNumerocuerpo = new PHPExcel_Style();
$estiloNumerocuerpo->applyFromArray(array(
  'font' => array(
    'name' => 'Calibri',
	'bold' => false,
    'size' => 10,
    'color' => array(
      'rgb' => '000000'
    )
  ),
  'numberformat' => array(
    'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    ),
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    )
  ),
)
);

$estiloborde = new PHPExcel_Style();
$estiloborde->applyFromArray(array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    ),
    'top' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array(
        'rgb' => '3a2a47'
      )
    )
  ),
)
);


try {
	$inputFileType = PHPExcel_IOFactory::identify($file_path);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	//  Tell the reader to include charts when it loads a file
	$objReader->setIncludeCharts(TRUE);
	//  Load the file
	$objPHPExcel = $objReader->load($file_path);
} catch (Exception $e) {
    exit('Error cargando el archivo ' . $e->getMessage());
}

$meses_arr = ["","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SETIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"];

$id_dependencia = $_GET['id_dependencia'];
$anio = $_GET['anio'];
$mes = $_GET['mes'];

if($_GET['opt'] == "EESS"){
	$rsDep = $d->get_datosDepenendenciaPorId($id_dependencia);
	$nom_dep = $rsDep[0]['nom_depen'];
} else {
	$nom_dep = "TODOS";
	$id_dependencia = 0;
}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('B1', $nom_dep)
->setCellValue('E1', $anio."-".$meses_arr[$mes]);

if($_GET['opt'] == "TOT"){
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "ESTABLECIMIENTO DE SALUD");
}

$rsTP = $le->get_repListaDetalleLevey(1, $anio, $mes, $id_dependencia);
$max=(int)3;
foreach ($rsTP as $rowTP) {
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A' . $max, $rowTP['nombre_tipo'])
	->setCellValue('B' . $max, $rowTP['nombre_control'])
	->setCellValue('C' . $max, $rowTP['dia'])
	->setCellValue('D' . $max, $rowTP['nro_lote'])
	->setCellValue('E' . $max, $rowTP['valor_fecha']);
	if($_GET['opt'] == "TOT"){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $max, $rowTP['nom_depen']);
	}
	$max++;
}

$objPHPExcel->setActiveSheetIndex(1)
->setCellValue('B1', $nom_dep)
->setCellValue('E1', $anio."-".$meses_arr[$mes]);

if($_GET['opt'] == "TOT"){
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F2', "ESTABLECIMIENTO DE SALUD");
}

$rsTP = $le->get_repListaDetalleLevey(2, $anio, $mes, $id_dependencia);
$max=(int)3;
foreach ($rsTP as $rowTP) {
	$objPHPExcel->setActiveSheetIndex(1)
	->setCellValue('A' . $max, $rowTP['nombre_tipo'])
	->setCellValue('B' . $max, $rowTP['nombre_control'])
	->setCellValue('C' . $max, $rowTP['dia'])
	->setCellValue('D' . $max, $rowTP['nro_lote'])
	->setCellValue('E' . $max, $rowTP['valor_fecha']);
	if($_GET['opt'] == "TOT"){
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F' . $max, $rowTP['nom_depen']);
	}
	$max++;
}


$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_lab_control_calidad_'.date("Ymdhis").'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
ob_end_clean(); // ob_end_clean Limpia el búfer de salida y desactiva el búfer de salida
$objWriter->save('php://output');
