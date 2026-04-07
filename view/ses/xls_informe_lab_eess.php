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

require_once '../../model/Ses.php';
$ses = new Ses();
require_once '../../model/Producto.php';
$pr = new Producto();
require_once '../../model/Dependencia.php';
$d = new Dependencia();


/** Include PHPExcel */
require_once '../../assets/lib/PHPExcel/Classes/PHPExcel.php';
require_once '../../assets/lib/PHPExcel/Classes/PHPExcel/IOFactory.php';

$file_path = "plantilla_xls_cnt_eess.xlsx";
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
$mes_desde = $_GET['mes_desde'];
$mes_hasta = $_GET['mes_hasta'];

if($mes_desde == $mes_hasta){
	$meses = $meses_arr[$mes_desde];
} else {
	$meses = $meses_arr[$mes_desde] . "-" . $meses_arr[$mes_hasta];
}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', $anio);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', $meses);

$rsDep = $d->get_datosDepenendenciaPorId($id_dependencia);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', $rsDep[0]['nom_depen']);

$max = 9;
$count = 0;
$cnt_total=0;
$tot_sis = 0;
$tot_demanda = 0;
$tot_estrategia = 0;
$tot_exonerado = 0;

$nro_meses = ($mes_hasta - $mes_desde) + 1;
$nro_columna = (($mes_hasta - $mes_desde) + 1) * 5;

$col = array("C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");
$nomtipopac_arr = ["AUS","PAGANTES","ESTRATEGIAS","EXONERADOS","TOTAL EXAMENES"];

$column = 0;
$fila = 0;
$nro_mestitu= $mes_desde;
for ($i = 0; $i < $nro_meses; $i++) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $meses_arr[$nro_mestitu]);
	$column = $column + 5;
	$nro_mestitu ++;
}

$max = $max + 1;
$column = 0;
$fila = 0;
for ($i = 0; $i < $nro_columna; $i++) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $nomtipopac_arr[$fila]);
	$column++;
	$fila++;
	if($fila == 5){
		$fila = 0;
	}
}
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacionTitulotprod, "C" . $max . ":" . $col[$column - 1] . $max);
//print_r($mes_hasta);exit();

$max = 11;
$column = 0;
$nro_mesate= $mes_desde;
for ($i = 0; $i < $nro_meses; $i++) {
	$rsAte = $ses->get_CntDetalleInformeSESLabAtencion('EESS', $anio, $nro_mesate, 0, $id_dependencia);
	//print_r($rsAte); exit();
	if(count($rsAte) <> 0){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsAte[0]['cnt_sis']);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsAte[0]['cnt_pagante']);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsAte[0]['cnt_estrategia']);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsAte[0]['cnt_exonerado']);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsAte[0]['cnt_total']);
		$column++;
	} else {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
		$column++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
		$column++;		
	}
	$nro_mesate ++;
}
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloborde, "C" . $max . ":" . $col[$column - 1] . $max);

$maxHema = 13;
$maxBio = 13;
$maxImn = 13;
$maxMic = 13;
$maxPaq = 13;
$itemHema = 0;
$itemBio = 0;
$itemImn = 0;
$itemMic = 0;
$itemPaq = 0;
$max = 13;
$item = 0;
$rsTP = $pr->get_listaTipoProducto();
foreach ($rsTP as $rowTP) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $max, $rowTP['nombre_tipo_producto']);
	
	$column = 0;
	$nro_mestprod = $mes_desde;
	for ($i = 0; $i < $nro_meses; $i++) {
		$rsTPr = $ses->get_CntDetalleInformeSESLabDetTipoProducto('EESS', $anio, $nro_mestprod, 0, $id_dependencia, $rowTP['id']);
		//print_r($rsTPr); exit();
		if(count($rsTPr) <> 0){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_sis']);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_pagante']);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_estrategia']);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_exonerado']);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_total']);
			$column++;
		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;		
		}
		$nro_mestprod ++;
	}
	$objPHPExcel->getActiveSheet()->setSharedStyle($estiloNumero, "B" . $max . ":" . $col[$column - 1] . $max);
	
	if($rowTP['id'] == "1"){
		if($itemHema == 0) {
			$maxHema = $max;
			$itemHema++;
		}
	}
	if($rowTP['id'] == "2"){
		if($itemBio == 0) {
			$maxBio = $max;
			$itemBio++;
		}
	}
	if($rowTP['id'] == "3"){
		if($itemImn == 0) {
			$maxImn = $max;
			$itemImn++;
		}
	}
	if($rowTP['id'] == "4"){
		if($itemMic == 0) {
			$maxMic = $max;
			$itemMic++;
		}
	}
	if($rowTP['id'] == "6"){
		if($itemPaq == 0) {
			$maxPaq = $max;
			$itemPaq++;
		}
	}
	
	$rsPr = $ses->get_CntDetalleInformeSESLabDetProducto($anio, $mes_desde, $mes_hasta, $rowTP['id']);
	//print_r($rsPr); exit();
	if (count($rsPr) > 0) {
	  $item = 0;
	  foreach ($rsPr as $row) {
		$item++;
		$max++;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $max, $item);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $max, $row['nom_producto']);//tipo doc
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A" . $max . ":B" . $max);
		$column = 0;
		$nro_mesexa = $mes_desde;
		for ($i = 0; $i < $nro_meses; $i++) {
			$rsExa = $ses->get_CntDetalleInformeSESLabTotLabExamenXProd('EESS', $anio, $nro_mesexa, 0, $id_dependencia, $row['id_producto']);
			if(count($rsExa) <> 0){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_sis']);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_pagante']);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_estrategia']);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_exonerado']);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_total']);
				$column++;
			} else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
				$column++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
				$column++;		
			}
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloNumerocuerpo, "C" . $max . ":" . $col[$column - 1] . $max);
			$nro_mesexa ++;
		}
	  }
	}
	$max++;
}
 //exit();

$column = 0;
$fila = 0;
for ($i = 0; $i < $nro_columna; $i++) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "12", "=".$col[$column] . $maxHema . " + " . $col[$column].$maxBio . " + " . $col[$column] . $maxImn . " + " . $col[$column] . $maxMic . " + " . $col[$column] . $maxPaq);
	$column++;
	$fila++;
	if($fila == 5){
		$fila = 0;
	}
}
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacionTitulotprod, "C" . $max . ":" . $col[$column - 1] . $max);

/********************************************************************************************/
////////////////////////////////////// BASILOSCOPIA /////////////////////////////////////////
/********************************************************************************************/

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('L2', $rsDep[0]['nom_depen']);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G3', "CULTIVOS DE MICOBACTERIAS MES " . $meses . " DEL " . $anio);

$max = (int)7;
for ($i = $mes_desde; $i <= $mes_hasta; $i++) {
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue("A". $max, $meses_arr[$i]);
	$rsBac = $ses->get_CntDetalleInformeSESLabTotBacBasiloscopia('EESS', $anio, $i, 0, $id_dependencia);
	//print_r($rsBac); exit();
	if(count($rsBac) <> 0){
		$ateRes = 0; $posi1Res = 0; $posi2Res = 0; $posi3Res = 0; $pauRes = 0;
		$ateSeg = 0; $posi1Seg = 0; $posi2Seg = 0; $posi3Seg = 0; $pauSeg = 0;
		$ateCon = 0; $posi1Con = 0; $posi2Con = 0; $posi3Con = 0; $pauCon = 0;
		$ateRad = 0; $posi1Rad = 0; $posi2Rad = 0; $posi3Rad = 0; $pauRad = 0;
		$ateLoc = 0; $posi1Loc = 0; $posi2Loc = 0; $posi3Loc = 0; $pauLoc = 0;
		$ateOtr = 0; $posi1Otr = 0; $posi2Otr = 0; $posi3Otr = 0; $pauOtr = 0;
		foreach ($rsBac as $row) {
			switch($row['id_diagnostico']){
				case 175://respi
					$ateRes = $row['cnt_atencion'];
					$posi1Res = $row['cnt_posi1'];
					$posi2Res = $row['cnt_posi2'];
					$posi3Res = $row['cnt_posi3'];
					$pauRes = $row['cnt_pau'];
				break;
				case 177://seguimiento dia
					$ateSeg = $row['cnt_atencion'];
					$posi1Seg = $row['cnt_posi1'];
					$posi2Seg = $row['cnt_posi2'];
					$posi3Seg = $row['cnt_posi3'];
					$pauSeg = $row['cnt_pau'];
				break;
				case 180://control trata
					$ateCon = $row['cnt_atencion'];
					$posi1Con = $row['cnt_posi1'];
					$posi2Con = $row['cnt_posi2'];
					$posi3Con = $row['cnt_posi3'];
					$pauCon = $row['cnt_pau'];
				break;
				case 176://rx anormal
					$ateRad = $row['cnt_atencion'];
					$posi1Rad = $row['cnt_posi1'];
					$posi2Rad = $row['cnt_posi2'];
					$posi3Rad = $row['cnt_posi3'];
					$pauRad = $row['cnt_pau'];
				break;
				case 178://localizacio extra
					$ateLoc = $row['cnt_atencion'];
					$posi1Loc = $row['cnt_posi1'];
					$posi2Loc = $row['cnt_posi2'];
					$posi3Loc = $row['cnt_posi3'];
					$pauLoc = $row['cnt_pau'];
				break;
				case 179://otras indica
					$ateOtr = $row['cnt_atencion'];
					$posi1Otr = $row['cnt_posi1'];
					$posi2Otr = $row['cnt_posi2'];
					$posi3Otr = $row['cnt_posi3'];
					$pauOtr = $row['cnt_pau'];
				break;
			}
		}
		//
		$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue("B" . $max, $ateRes)
		->setCellValue("C" . $max, ($posi1Res + $posi2Res + $posi3Res + $pauRes))
		->setCellValue("D" . $max, $ateSeg)
		->setCellValue("E" . $max, ($posi1Seg + $posi2Seg + $posi3Seg + $pauSeg))
		->setCellValue("F" . $max, $ateCon)
		->setCellValue("G" . $max, ($posi1Con + $posi2Con + $posi3Con + $pauCon))
		->setCellValue("H" . $max, $ateRad)
		->setCellValue("I" . $max, ($posi1Rad + $posi2Rad + $posi3Rad + $pauRad))
		->setCellValue("J" . $max, $ateLoc)
		->setCellValue("K" . $max, ($posi1Loc + $posi2Loc + $posi3Loc + $pauLoc))
		->setCellValue("L" . $max, $ateOtr)
		->setCellValue("M" . $max, ($posi1Otr + $posi2Otr + $posi3Otr + $pauOtr))
		->setCellValue("N" . $max, ($ateRes + $ateSeg + $ateCon + $ateRad + $ateLoc + $ateOtr))
		->setCellValue("O" . $max, (($posi1Res + $posi2Res + $posi3Res + $pauRes) + ($posi1Seg + $posi2Seg + $posi3Seg + $pauSeg) + ($posi1Con + $posi2Con + $posi3Con + $pauCon) + ($posi1Rad + $posi2Rad + $posi3Rad + $pauRad) + ($posi1Loc + $posi2Loc + $posi3Loc + $pauLoc) + ($posi1Otr + $posi2Otr + $posi3Otr + $pauOtr)))
		->setCellValue("P" . $max, ($posi1Res + $posi1Seg + $posi1Con + $posi1Rad + $posi1Loc + $posi1Otr))
		->setCellValue("Q" . $max, ($posi2Res + $posi2Seg + $posi2Con + $posi2Rad + $posi2Loc + $posi2Otr))
		->setCellValue("R" . $max, ($posi3Res + $posi3Seg + $posi3Con + $posi3Rad + $posi3Loc + $posi3Otr))
		->setCellValue("S" . $max, ($pauRes + $pauSeg + $pauCon + $pauRad + $pauLoc + $pauOtr));		
	} else {
		$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue("B" . $max, 0)
		->setCellValue("C" . $max, 0)
		->setCellValue("D" . $max, 0)
		->setCellValue("E" . $max, 0)
		->setCellValue("F" . $max, 0)
		->setCellValue("G" . $max, 0)
		->setCellValue("H" . $max, 0)
		->setCellValue("I" . $max, 0)
		->setCellValue("J" . $max, 0)
		->setCellValue("K" . $max, 0)
		->setCellValue("L" . $max, 0)
		->setCellValue("M" . $max, 0)
		->setCellValue("N" . $max, 0)
		->setCellValue("O" . $max, 0)
		->setCellValue("P" . $max, 0)
		->setCellValue("Q" . $max, 0)
		->setCellValue("R" . $max, 0)
		->setCellValue("S" . $max, 0);
	}
	$max ++;
}

$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_ses'.date("Ymdhis").'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
ob_end_clean(); // ob_end_clean Limpia el búfer de salida y desactiva el búfer de salida
$objWriter->save('php://output');
