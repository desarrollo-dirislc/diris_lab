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

$file_path = "plantilla_xls_cnt_tot.xlsx";
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

$id_ris = $_GET['id_ris'];
$anio = $_GET['anio'];
$mes_desde = $_GET['mes_desde'];
$mes_hasta = $_GET['mes_hasta'];

if($mes_desde == $mes_hasta){
	$meses = $meses_arr[$mes_desde];
} else {
	$meses = $meses_arr[$mes_desde] . "-" . $meses_arr[$mes_hasta];
}

if($id_ris <> ""){
	$nom_ris = "RIS " . $id_ris;
} else {
	$nom_ris = "TODAS LAS RIS";
}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', $anio);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', $meses);

$rsDep = $d->get_datosDepenendenciaPorIdRIS($id_ris);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', $nom_ris);
//print_r($rsDep); exit();

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

$column = 0;
$fila = 0;
for ($i = 0; $i < count($rsDep); $i++) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "10", $rsDep[$i]['nom_depen']);
	$rsCabe = $ses->get_CntInformeSESLabAtencionAndExamenes('TOT', $anio, $mes_desde, $mes_hasta, $rsDep[$i]['id_dependencia']);
	//print_r($rsCabe);exit();
	if(count($rsCabe) <> 0){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "11", $rsCabe[0]['cnt_total_ate_lab']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "12", $rsCabe[0]['cnt_total_exa_lab']);
	} else {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "11", 0);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . "12", 0);
	}
	$column++;
	$fila++;
}
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloNumero, "C11:" . $col[$column - 1] . "11");
$objPHPExcel->getActiveSheet()->setSharedStyle($estiloNumero, "C12:" . $col[$column - 1] . "12");
//print_r($mes_hasta);exit();

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
	for ($i = 0; $i < count($rsDep); $i++) {
		$rsTPr = $ses->get_CntDetalleInformeSESLabDetTipoProducto('TOT', $anio, $mes_desde, $mes_hasta, $rsDep[$i]['id_dependencia'], $rowTP['id']);
		//print_r($rsTPr); exit();
		if(count($rsTPr) <> 0){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsTPr[0]['cnt_total']);
			$column++;
		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, 0);
			$column++;
		}
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
		for ($i = 0; $i < count($rsDep); $i++) {
			$rsExa = $ses->get_CntDetalleInformeSESLabTotLabExamenXProd('TOT', $anio, $mes_desde, $mes_hasta, $rsDep[$i]['id_dependencia'], $row['id_producto']);
			if(count($rsExa) <> 0){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col[$column] . $max, $rsExa[0]['cnt_total']);
				$column++;
			} else {
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

/********************************************************************************************/
////////////////////////////////////// BASILOSCOPIA /////////////////////////////////////////
/********************************************************************************************/

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I2', "CULTIVOS DE MICOBACTERIAS MES " . $meses . " DEL " . $anio);

$max = (int)7;
$item = 1;
for ($i = 0; $i < count($rsDep); $i++) {
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue("A". $max, $item);
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue("B". $max, $rsDep[$i]['nro_ris_pertenece']);
	$objPHPExcel->setActiveSheetIndex(1)->setCellValue("C". $max, $rsDep[$i]['nom_depen']);
	$rsBac = $ses->get_CntDetalleInformeSESLabTotBacBasiloscopia('TOT', $anio, $mes_desde, $mes_hasta, $rsDep[$i]['id_dependencia']);
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
		->setCellValue("D" . $max, $ateRes)
		->setCellValue("E" . $max, ($posi1Res + $posi2Res + $posi3Res + $pauRes))
		->setCellValue("F" . $max, $ateSeg)
		->setCellValue("G" . $max, ($posi1Seg + $posi2Seg + $posi3Seg + $pauSeg))
		->setCellValue("H" . $max, $ateCon)
		->setCellValue("I" . $max, ($posi1Con + $posi2Con + $posi3Con + $pauCon))
		->setCellValue("J" . $max, $ateRad)
		->setCellValue("K" . $max, ($posi1Rad + $posi2Rad + $posi3Rad + $pauRad))
		->setCellValue("L" . $max, $ateLoc)
		->setCellValue("M" . $max, ($posi1Loc + $posi2Loc + $posi3Loc + $pauLoc))
		->setCellValue("N" . $max, $ateOtr)
		->setCellValue("O" . $max, ($posi1Otr + $posi2Otr + $posi3Otr + $pauOtr))
		->setCellValue("P" . $max, ($ateRes + $ateSeg + $ateCon + $ateRad + $ateLoc + $ateOtr))
		->setCellValue("Q" . $max, (($posi1Res + $posi2Res + $posi3Res + $pauRes) + ($posi1Seg + $posi2Seg + $posi3Seg + $pauSeg) + ($posi1Con + $posi2Con + $posi3Con + $pauCon) + ($posi1Rad + $posi2Rad + $posi3Rad + $pauRad) + ($posi1Loc + $posi2Loc + $posi3Loc + $pauLoc) + ($posi1Otr + $posi2Otr + $posi3Otr + $pauOtr)))
		->setCellValue("R" . $max, ($posi1Res + $posi1Seg + $posi1Con + $posi1Rad + $posi1Loc + $posi1Otr))
		->setCellValue("S" . $max, ($posi2Res + $posi2Seg + $posi2Con + $posi2Rad + $posi2Loc + $posi2Otr))
		->setCellValue("T" . $max, ($posi3Res + $posi3Seg + $posi3Con + $posi3Rad + $posi3Loc + $posi3Otr))
		->setCellValue("U" . $max, ($pauRes + $pauSeg + $pauCon + $pauRad + $pauLoc + $pauOtr));		
	} else {
		$objPHPExcel->setActiveSheetIndex(1)
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
		->setCellValue("S" . $max, 0)
		->setCellValue("T" . $max, 0)
		->setCellValue("U" . $max, 0);
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
