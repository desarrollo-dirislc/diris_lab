<?php
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

include '../../assets/lib/fpdf/fpdf.php';
include '../../assets/lib/qr/phpqrcode/qrlib.php';

require_once '../../model/Atencion.php';
$at = new Atencion();
require_once '../../model/Lab.php';
$lab = new Lab();
require_once '../../model/Producton.php';
$pn = new Producton();
require_once '../../model/Componente.php';
$c = new Componente();

$idAtencion = $_GET['valid'];
$idDependencia = $_GET['p'];
$idProducto = $_GET['pr'];

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}
$valini=generate_string($permitted_chars, 20);
$valfin=generate_string($permitted_chars, 10);

$rsA = $at->get_datosAtencion_md5($idAtencion, $idDependencia);
if(count($rsA) == 0){
	$labIdDepUser = md5($labIdDepUser);
	$rsA = $at->get_datosAtencion_md5($idAtencion, $labIdDepUser);
}
/*print_r(count($rsA));
exit();*/

$_GET['id_atencion'] = $rsA[0]['id'];
$_GET['id_dependencia'] = $rsA[0]['id_dependencia'];
$idAtencion = $rsA[0]['id']; 
$_GET['ref_id_dependencia_procesa'] = '';
$_GET['ref_nom_dependencia_procesa'] = '';
$_GET['ref_fec_valid_resul'] = '';
$_GET['ref_id_profesional_valid_resul'] = '';
$_GET['ref_nombre_per_valid_resul'] = '';
$_GET['ref_nom_prof_cole_user_valid_resul'] = '';
$_GET['ref_id_profesional_encargado_lab'] = '';
$_GET['ref_nombre_per_encargado_lab'] = '';
$_GET['ref_nom_prof_cole_user_encargado_lab'] = '';

if(!empty($rowP['id_producto'])){
	$rsDER = $lab->get_datosDetalleResultadoPorIdExamenReferenciado($idAtencion, $_GET['pr']);
	/*print_r($rsDER);
	exit();*/
	//print_r($_GET['id_dependencia'] . "|" . $rsDER['id_dependencia_procesa']);
	if (count($rsDER) > 0) {
		if ($_GET['id_dependencia'] <> $rsDER['id_dependencia_procesa']){
			$_GET['ref_id_dependencia_procesa'] = $rsDER['id_dependencia_procesa'];
			$_GET['ref_nom_dependencia_procesa'] = $rsDER['nom_dependencia_procesa'];
			$_GET['ref_fec_valid_resul'] = substr($rsDER['fec_valid_resul'], 0, 10);
			$_GET['ref_id_profesional_valid_resul'] = $rsDER['id_profesional_valid_resul'];
			$_GET['ref_nombre_per_valid_resul'] = $rsDER['nombre_per_valid_resul'];
			$_GET['ref_nom_prof_cole_user_valid_resul'] = $rsDER['nom_prof_cole_user_valid_resul'];

			$_GET['ref_id_profesional_encargado_lab'] = $rsDER['id_profesional_encargado_lab'];
			$_GET['ref_nombre_per_encargado_lab'] = $rsDER['nombre_per_encargado_lab'];
			$_GET['ref_nom_prof_cole_user_encargado_lab'] = $rsDER['nom_prof_cole_user_encargado_lab'];
		}
	}
}

$codeContents ="http://app1.dirislimacentro.gob.pe/labs-result/?p=".md5($rsA[0]['id_dependencia'])."&valid=".$valini.".".md5($idAtencion).".".$valfin;
//$tempDir = ''; //EXAMPLE_TMP_SERVERPATH;
$tempDir = __DIR__ . '/';
$fileName = 'qr/qr.jpg';
$outerFrame = 4;
$pixelPerPoint = 4;
$jpegQuality = 95;

// generating frame
$frame = QRcode::text($codeContents, false, QR_ECLEVEL_M);

// rendering frame with GD2 (that should be function by real impl.!!!)
$h = count($frame);
$w = strlen($frame[0]);

$imgW = $w + 2*$outerFrame;
$imgH = $h + 2*$outerFrame;

$base_image = imagecreate($imgW, $imgH);

$col[0] = imagecolorallocate($base_image,255,255,255); // BG, white 
$col[1] = imagecolorallocate($base_image,0,0,0);     // FG, blue

imagefill($base_image, 0, 0, $col[0]);

for($y=0; $y<$h; $y++) {
	for($x=0; $x<$w; $x++) {
		if ($frame[$y][$x] == '1') {
			imagesetpixel($base_image,$x+$outerFrame,$y+$outerFrame,$col[1]); 
		}
	}
}

// saving to file
$target_image = imagecreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
imagecopyresized(
	$target_image, 
	$base_image, 
	0, 0, 0, 0, 
	$imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH
);
imagedestroy($base_image);
imagejpeg($target_image, $tempDir.$fileName, $jpegQuality);
imagedestroy($target_image);

class PDF extends FPDF
{
  //Cabecera de página
  function Header()
  {
	$this->SetTextColor(0, 0, 0);
    //Logo
    $this->Image('../../assets/images/logo_diris.png',10,4,50);
    $this->SetFont('Arial','',6);

    require_once '../../model/Atencion.php';
    $at = new Atencion();
    $idAtencion = $_GET['id_atencion'];
	$labIdDepUser = $_SESSION['labIdDepUser'];
    $rsA = $at->get_datosAtencion($idAtencion);
	/*print_r($rsA);
	exit();*/
    //Aubtitles
    $this->SetFont('Arial','B',7);
    $this->Cell(0,4,utf8_decode($rsA[0]['nom_depen'])."          ",0,1,'R');
    $this->SetFont('Arial','B',10);
    $this->Cell(0,3,utf8_decode('HC: '.$rsA[0]['nro_hcpac'])."          ",0,1,'R');

    $this->SetFont('Arial','B',10);
    $this->Cell(40,2,'',0,1,'');
    $this->Cell(0,5,'SERVICIO DE LABORATORIO',0,1,'C');

    $this->SetFont('Arial','B',7);
    $this->Cell(40,2,'',0,1,'');

    $this->Cell(22,4,'Paciente',0,0,'');
    $this->SetFont('Arial','',8);
    $this->Cell(96,4, utf8_decode(': ' . $rsA[0]['nombre_rspac']),0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(8,4,'Sexo',0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(5,4, utf8_decode(': ' . $rsA[0]['nom_sexopac']),0,1,'');

    if($rsA[0]['abrev_tipodocpac'] == "DNI"){
      $naci = "PER";
    } else {
      $naci = "EXT";
    }

    $this->SetFont('Arial','B',7);
    $this->Cell(22,4,utf8_decode('Doc. Identificación'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(25,4, ': ' . $rsA[0]['abrev_tipodocpac'] .'-'. $rsA[0]['nro_docpac'] ,0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(8,4,'Edad',0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(24,4,utf8_decode(': ' . $rsA[0]['edad_anio'] . ' AÑOS'),0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(23,4,utf8_decode('Fecha cita'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(30,4,': '. $rsA[0]['fec_cita'],0,1,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(22,4,utf8_decode('Nro. Atención'),0,0,'');
    $this->SetFont('Arial','',7);
	if($rsA[0]['id_tipo_genera_correlativo'] == "1"){
		$nroAtencion = $rsA[0]['nro_atencion'] . "-". $rsA[0]['anio_atencion'];
	} else {
		$nroAtencion = substr($rsA[0]['nro_atencion'], 0, 6).substr($rsA[0]['nro_atencion'],6);
	}
    $this->Cell(25,4, utf8_decode(': ' . $nroAtencion),0,0,'');


    $this->SetFont('Arial','B',7);
    $this->Cell(11,4,utf8_decode('Atención'),0,0,'');
    $this->SetFont('Arial','',7);
	if($rsA[0]['nombre_medico'] == ""){
		$this->Cell(21,4,utf8_decode(': ' . $rsA[0]['abrev_plan']),0,0,'');
	} else {
		$this->Cell(20,4,utf8_decode(': ' . $rsA[0]['abrev_plan']),0,0,'');
	}
	
	if($rsA[0]['nombre_medico'] == ""){
		$this->SetFont('Arial','B',7);
		$this->Cell(23,4,utf8_decode('Página'),0,0,'');
		$this->SetFont('Arial','',7);
		$this->Cell(30,4,':       '.$this->PageNo().'   de   {nb}',0,1,'');
	} else {
		$this->SetFont('Arial','B',7);
		$this->Cell(15,4,utf8_decode('Prfsnal. Soli.'),0,0,'');
		$this->SetFont('Arial','',7);
		$this->Cell(30,4,utf8_decode(': ' . $rsA[0]['nombre_medico']),0,1,'');		
	}

	if(!empty($_GET['ref_id_dependencia_procesa'])){//La función que muestra estos datos esta más arriba
		$this->SetFont('Arial','B',7);
		$this->Cell(22,4,utf8_decode('EESS resultado'),0,0,'');
		$this->SetFont('Arial','',7);
		$this->Cell(80,4, utf8_decode(': '. $_GET['ref_nom_dependencia_procesa']),0,0,'');
		$this->SetFont('Arial','B',7);
		$this->Cell(23,4,utf8_decode('Fecha resultado: '),0,0,'R');
		$this->SetFont('Arial','',7);
		$this->Cell(15,4, utf8_decode($_GET['ref_fec_valid_resul']),0,0,'L');
	}
  }

  //Pie de página
  function Footer()
  {
    //Posición: a 1,5 cm del final
    $labNomUser = $_SESSION['labNomUser'];
	$labIdDepUser = $_SESSION['labIdDepUser'];
    require_once '../../model/Atencion.php';
    $at = new Atencion();
	require_once '../../model/Lab.php';
    $lab = new Lab();
    $idAtencion = $_GET['id_atencion'];
	$idDependencia = $_GET['id_dependencia'];
	//$this->Image('qr/qr.jpg',11, $this->SetY(-44),27);
	if(!empty($_GET['ref_id_dependencia_procesa'])){
		$this->SetY(-45);
		$this->SetFont('Arial','B',7);
		$this->Cell(37,4,'',0,0,'');
		$this->Cell(20,4,'Procesado por:',0,0,'');
		$this->SetFont('Arial','',7);
		$this->Cell(40,4,'LIC. ' . $_GET['ref_nom_prof_cole_user_valid_resul'] . ' '  . $_GET['ref_nombre_per_valid_resul'],0,1,'');
		$this->SetFont('Arial','B',7);
		$this->Cell(37,4,'',0,0,'');
		$this->Cell(20,4,'Validdo por:',0,0,'');
		$this->SetFont('Arial','',7);
		$this->Cell(40,4,'LIC. ' . $_GET['ref_nom_prof_cole_user_encargado_lab'] . ' '  . $_GET['ref_nombre_per_encargado_lab'],0,1,'');
		$this->SetY(-35);
	} else {
		$this->SetY(-35);
	}
	
    $rsA = $at->get_datosAtencionProfesionalResponsableTurno($idAtencion);
    if(isset($rsA[0]['id_profesional'])){
      $url = "../genecrud/profesional/";
      $nomArchiJpg = $rsA[0]['id_profesional'].".jpg";
      if (file_exists($url . $nomArchiJpg)) {
        $this->Image($url.$nomArchiJpg,80,$this->GetY(),50);
      }
      $nomArchiPng = $rsA[0]['id_profesional'].".png";
      if (file_exists($url . $nomArchiPng)) {
        $this->Image($url.$nomArchiPng,80,$this->GetY(),50);
      }

      $rsHI = $at->get_datosfecHoraActual();
      $this->Ln(15);
      $this->SetFont('Arial','',6);
	  //$this->SetTextColor(255, 0, 0);
      $this->Cell(78,3,'(*) Resultado fuera del valor de referencia',0,1,'');
	  //$this->SetTextColor(0, 0, 0);
      //$this->Cell(53,3,utf8_decode($rsA[0]['nom_prof'] . " " . $rsA[0]['primer_apeprof'] . " " . $rsA[0]['segundo_aprprof']),'T',1,'C');
      $this->SetFont('Arial','',5);
      $this->Cell(78,3,$rsHI[0]['fechora_actual'] . " (".$labNomUser.")",0,0,'');
      //$this->SetFont('Arial','',6);
      //$this->Cell(53,3,utf8_decode("CMP. ".$rsA[0]['nro_colegiatura']),0,1,'C');
    } else {
		if(!empty($_GET['ref_id_dependencia_procesa'])){//La función que muestra estos datos esta más arriba
		
			$url = "../genecrud/profesional/";
			$nomArchiResEESSJpg = $_GET['ref_id_profesional_encargado_lab'].".jpg";
			if (file_exists($url . $nomArchiResEESSJpg)) {
				$this->Image($url.$nomArchiResEESSJpg,10,$this->GetY(),50);
			}
			$nomArchiResEESSJpg = $_GET['ref_id_profesional_encargado_lab'].".png";
			if (file_exists($url . $nomArchiResEESSJpg)) {
				$this->Image($url.$nomArchiResEESSJpg,10,$this->GetY(),50);
			}
				
			$nomArchiJpg = $_GET['ref_id_profesional_valid_resul'].".jpg";
			if (file_exists($url . $nomArchiJpg)) {
				$this->Image($url.$nomArchiJpg,80,$this->GetY(),50);
			}
			$nomArchiPng = $_GET['ref_id_profesional_valid_resul'].".png";
			if (file_exists($url . $nomArchiPng)) {
				$this->Image($url.$nomArchiPng,80,$this->GetY(),50);
			}			
			$this->Ln(20);
			$rsHI = $at->get_datosfecHoraActual();
			$this->SetFont('Arial','',5);
			$this->Cell(78,3,$rsHI[0]['fechora_actual'] . " (".$labNomUser.")",0,0,'');
		} else {
		  $rsHI = $at->get_datosfecHoraActual();
		  $this->Ln(15);
		  $this->SetFont('Arial','',6);
		  //$this->SetTextColor(255, 0, 0);
		  $this->Cell(78,3,'(*) Resultado fuera del valor de referencia',0,0,'');
		  $this->SetTextColor(0, 0, 0);
		  $this->Cell(53,3,'','',1,'C');
		  $this->SetFont('Arial','',5);
		  $this->Cell(78,3,$rsHI[0]['fechora_actual'] . " (".$labNomUser.")",0,0,'');
		}
	}
  }
}

//$pdf=new FPDF('L','mm','A4');
$pdf=new PDF('P','mm','A5');
//$pdf->SetLeftMargin(6);
$pdf->SetAutoPageBreak(true,35);
$pdf->SetMargins(5,4,5);
$pdf->AliasNbPages();

$nomSexo = $rsA[0]['nom_sexopac'];
$edadAnio = $rsA[0]['edad_anio'];
$edadMes =  $rsA[0]['edad_mes'];
$edadDia =  $rsA[0]['edad_dia'];
$rsP = $at->get_datosProductoPorIdAtencion($idAtencion, $idProducto,'RV');
/*print_r($rsP);
exit();*/
foreach ($rsP as $rowP) {
  $pdf->AddPage();

  if(!empty($_GET['ref_id_dependencia_procesa'])){//La función que muestra estos datos esta más arriba
	$pdf->Ln(4);
  } else {
	$pdf->Ln(2);
  }
  $pdf->SetFont('Arial','IB',7);
  $pdf->Cell(65,4,utf8_decode('ANALISIS CLINICO'),0,0,'C');
  $pdf->Cell(27,4,utf8_decode('RESULTADO'),0,0,'C');
  $pdf->Cell(18,4,utf8_decode('U.M.'),0,0,'C');
  $pdf->Cell(30,4,utf8_decode('VALOR DE REFERENCIA'),0,1,'C');

  $cnt_componente = (int)($pn->get_cntComponentePorIdProductoAndIdAtencion($idAtencion, $rowP['id_producto']));
	  $pdf->Ln(1);
	  
	  $nom_productot = $rowP['nom_producto'];
	  $nom_producton = str_replace("TOMA DE MUESTRA ", "", $nom_productot);
	  if(strlen($rowP['nom_producto'])<34){
		  $pdf->SetFont('Arial','IB',9);
		  $pdf->Cell(0,4,utf8_decode($nom_producton),0,0,'L');
	  } else {
		  $pdf->SetFont('Arial','IB',7);
		  $pdf->Cell(0,4,utf8_decode($nom_producton),0,0,'L');
	  }
	  
	  $pdf->Ln(3);
      $rsG = $pn->get_datosGrupoPorIdProductoAndidAtencion($idAtencion, $rowP['id_producto']);
	  $muestra_entrelineas = "NO";
	  $cnt_grupo = 0;
      $cantG = Count($rsG);
      foreach ($rsG as $rowG) {
		/*$rsCObs = $pn->get_datosSiTieneObsComponentePorIdGrupoProdAndIdAtencion($idAtencion, $rowP['id_producto'], $rowG['id']);
		if(isset($rsCObs[0]['det_result_obs'])) {
			if($rsCObs[0]['det_result_obs']<>"") {
				$muestra_entrelineas = "SI";
			}
		}*/
		  
		  
		$cnt_grupo ++;
		//print_r($rsC);
		if($rowG['nom_visible'] == "SI"){
			$pdf->Ln(2);
			$pdf->SetFont('Arial','IB',7);
			$pdf->Cell(0,4,utf8_decode($rowG['descripcion_grupo']),0,1,'L');
		}
        $rsC = $pn->get_datosComponentePorIdGrupoProdAndIdAtencion($idAtencion, $rowP['id_producto'], $rowG['id']);
		//print_r($rsC);exit();
		$cnt_comp = 0;
		$cantC = Count($rsC);
        foreach ($rsC as $rowC) {
				if ($rowC['muestra_comp_vacio'] == "NO" && $rowC['det_result'] == ""){
				} else {
				$cnt_comp++;
				switch($rowC['id_tipo_val_ref']){
					case "2":
						if ($rowC['det_result'] == ""){
							$resulVRef = 0;
						}else {
							$resulVRef = $rowC['det_result'];
						}
					break;
					default:
						$resulVRef = 0;
					break;
				}
				if ($rowC['idtipo_ingresol'] == "1" And $rowC['idtipocaracter_ingresul'] <> "1"){
					$rsVC = $c->get_datosValidaValReferencialComp($rowC['id'], $rowC['id_tipo_val_ref'], $resulVRef, $edadAnio, $edadMes, $edadDia, $nomSexo);
				}
				if($rowC['idtipo_ingresol'] == "1"){
				  $valMin = "";
				  $valMax = "";
				  $totVal = "";
				  $valRes = $rowC['det_result'];
				  if ($rowC['idtipo_ingresol'] == "1" And $rowC['idtipocaracter_ingresul'] <> "1"){
					$valResDescrip = $rowC['descrip_valref_metodo'];//Para la descripcion que se registro en los valores referenciales.
				  } else {
					  $valResDescrip = '';
				  }
				  $valColor = "0";
				  switch($rowC['idtipocaracter_ingresul']){
					case "1":
					$totVal = $rowC['valor_ref'];
					break;
					case "2":
					$totVal = $rowC['valor_ref'];
					break;
					case "3":
						if ($rowC['opt_origen_sistema'] == "1"){
							if(isset($rsVC[0]['liminf'])) {
								if($rsVC[0]['liminf'] <> "") {
								  $valMin = $rsVC[0]['liminf'];
								  $valMax = $rsVC[0]['limsup'];
								  if ($rsVC[0]['limsup'] == 99999){
										$totVal = "> " . number_format($rsVC[0]['liminf']);
								  } else {
										if ($rsVC[0]['liminf'] == -1){
										$totVal = "< " . number_format($rsVC[0]['limsup']);
										} else {
											$totVal = number_format($rsVC[0]['liminf']) . " - " . number_format($rsVC[0]['limsup']);
										}
								  }
								  if($rowC['chk_muestra_valref_especifico'] == 't'){
									$valResDescrip = $rsVC[0]['descripvalref'];
								  }
								  if($rowC['det_result'] <> ""){
									  $valRes = number_format($rowC['det_result']);
									  if($rowC['det_result'] < $valMin){
										$valColor = "1";
									  }
									  if($rowC['det_result'] > $valMax) {
										$valColor = "2";
									  }
								  }
								} else {
								  $totVal = $rowC['valor_ref'];
								}
							} else {
								$totVal = $rowC['valor_ref'];
							}
						} else {
							if ($rowC['valor_ref_minimo'] <> "" && $rowC['valor_ref_maximo'] <> "") {
								$totVal = number_format($rowC['valor_ref_minimo']) . " - " . number_format($rowC['valor_ref_maximo']);
								$valMin = $rowC['valor_ref_minimo'];
								$valMax = $rowC['valor_ref_maximo'];
								if($rowC['det_result'] < $valMin){
									$valColor = "1";
								}
								if($rowC['det_result'] > $valMax) {
									$valColor = "2";
								}
							}
							if ($rowC['valor_ref_minimo'] <> "" && $rowC['valor_ref_maximo'] == "") {
								$totVal = "> " . number_format($rowC['valor_ref_minimo']);
							}
							if ($rowC['valor_ref_minimo'] == "" && $rowC['valor_ref_maximo'] <> "") {
								$totVal = "< " . number_format($rowC['valor_ref_maximo']);
							}
						}
					break;
					case "4":
						if ($rowC['opt_origen_sistema'] == "1"){
							if(isset($rsVC[0]['liminf'])){
								if($rsVC[0]['liminf'] <> "") {
								  $valMin = $rsVC[0]['liminf'];
								  $valMax = $rsVC[0]['limsup'];
								  if ($rsVC[0]['limsup'] == 99999){
									$totVal = "> " . number_format($rsVC[0]['liminf'], $rowC['detcaracter_ingresul'], '.', '');
								  } else {
									if ($rsVC[0]['liminf'] == -1){
										$totVal = "< " . number_format($rsVC[0]['limsup'], $rowC['detcaracter_ingresul'], '.', '');
									} else {
										$totVal = number_format($rsVC[0]['liminf'], $rowC['detcaracter_ingresul'], '.', '') . " - " . number_format($rsVC[0]['limsup'], $rowC['detcaracter_ingresul'], '.', '');	
									}
								  }
								  if($rowC['chk_muestra_valref_especifico'] == 't'){
									$valResDescrip = $rsVC[0]['descripvalref'];
								  }
								  if($rowC['det_result'] <> ""){
									  $valRes = number_format($rowC['det_result'], $rowC['detcaracter_ingresul'], '.', '');
									  if($rowC['det_result'] < $valMin){
										$valColor = "1";
									  }
									  if($rowC['det_result'] > $valMax) {
										$valColor = "2";
									  }
								  }
								} else {
								  $totVal = $rowC['valor_ref'];
								}
							} else {
							  $totVal = $rowC['valor_ref'];
							}
						} else {
							if ($rowC['valor_ref_minimo'] <> "" && $rowC['valor_ref_maximo'] <> "") {
								$totVal = number_format($rowC['valor_ref_minimo'], $rowC['detcaracter_ingresul'], '.', '') . " - " . number_format($rowC['valor_ref_maximo'], $rowC['detcaracter_ingresul'], '.', '');	
								$valMin = $rowC['valor_ref_minimo'];
								$valMax = $rowC['valor_ref_maximo'];
								if($rowC['det_result'] < $valMin){
									$valColor = "1";
								}
								if($rowC['det_result'] > $valMax) {
									$valColor = "2";
								}
							}
							if ($rowC['valor_ref_minimo'] <> "" && $rowC['valor_ref_maximo'] == "") {
								$totVal = "> " . number_format($rowC['valor_ref_minimo'], $rowC['detcaracter_ingresul'], '.', '');
							}
							if ($rowC['valor_ref_minimo'] == "" && $rowC['valor_ref_maximo'] <> "") {
								$totVal = "< " . number_format($rowC['valor_ref_maximo'], $rowC['detcaracter_ingresul'], '.', '');
							}
						}
					break;
					default:
					$totVal = $rowC['valor_ref'];
					break;
				  }
				  if($cnt_componente == 1){
					   if($rowP['nom_producto'] == $rowC['componente']){
						  $oldY = $pdf->getY();
						  $pdf->setY($oldY-3);
						  $pdf->SetFont('Arial','',7);
						  $pdf->Cell(65,3,'','',0,'L');
					  } else {
						$pdf->Cell(65,3,utf8_decode($rowC['componente']),'',0,'L');  
					  }
				  } else {
					   if($rowP['nom_producto'] == $rowC['componente']){
						  $oldY = $pdf->getY();
						  $pdf->setY($oldY-3);
						  $pdf->SetFont('Arial','',7);
						  $pdf->Cell(65,3,'','',0,'L');
					  } else {
							if($cnt_comp == 1){
								$pdf->Ln(1.5);
							}
							$pdf->SetFont('Arial','',7);
							$pdf->Cell(65,3,utf8_decode($rowC['componente']),'',0,'L');
					  }
				  }
				  switch($valColor){
					case "1":
						$pdf->SetFont('Arial','BI',8);
						//$pdf->SetTextColor(255, 0, 0);
						$pdf->Cell(27,3,utf8_decode($valRes." *"),0,0,'C');
					break;
					case "2":
						$pdf->SetFont('Arial','BI',8);
						//$pdf->SetTextColor(128, 0, 0);
						$pdf->Cell(27,3,utf8_decode($valRes." *"),0,0,'C');
					break;
					default:
						if(($rowC['idtipocaracter_ingresul']==3) OR ($rowC['idtipocaracter_ingresul']==4)){
							$pdf->SetFont('Arial','I',8);
							$pdf->Cell(27,3,utf8_decode($valRes),0,0,'C');
						} else {
							$pdf->SetFont('Arial','I',7);
							$pdf->Cell(27,3,utf8_decode($valRes),0,0,'C');								
						}
					break;
				  }
				  $pdf->SetFont('Arial','',6);
				  $pdf->SetTextColor(0, 0, 0);
				  $pdf->Cell(18,3,utf8_decode($rowC['uni_medida']),0,0,'C');
				  
				  if(($rowC['chk_muestra_valref_especifico'] == 't') OR ($rowC['opt_origen_sistema'] == "2")){
					$valResDescrip = $totVal . " " . $valResDescrip;
				  }
				  
				  $pdf->SetFont('Arial','',6);
				  $pdf->MultiCell(30,3,utf8_decode(trim($valResDescrip)),0,'C','');//Este es el valor referencial
				} elseif ($rowC['idtipo_ingresol'] == "2") { //Cuando es textarea observación
					if($rowC['id_componente'] == '159'){
						if (trim($rowC['det_result']) <> ""){
							$pdf->SetFont('Arial','',7);
							$pdf->Cell(65,4,utf8_decode($rowC['componente']),'',1,'L');
							$pdf->Cell(5,4,'','',0,'L');
							$pdf->MultiCell(130,4,utf8_decode($rowC['det_result']),0,'J','');
						}
					} else {
						$pdf->SetFont('Arial','',7);
						$pdf->Cell(65,4,utf8_decode($rowC['componente']),'',1,'L');
						$pdf->Cell(5,4,'','',0,'L');
						$pdf->MultiCell(130,4,utf8_decode($rowC['det_result']),0,'J','');						
					}	
				} else {//Cuando es combo seleccion
				  if($cnt_componente == 1){
					   if($rowP['nom_producto'] == $rowC['componente']){
						  $oldY = $pdf->getY();
						  $pdf->setY($oldY-3);
						  $pdf->SetFont('Arial','',7);
						  $pdf->Cell(65,3,'','',0,'L');
					  } else {
						$pdf->SetFont('Arial','',7);
						$pdf->Cell(65,3,utf8_decode($rowC['componente']),'',0,'L');  
					  }
				  } else {
					   if($rowP['nom_producto'] == $rowC['componente']){
						  $oldY = $pdf->getY();
						  $pdf->setY($oldY-3);
						  $pdf->SetFont('Arial','',7);
						  $pdf->Cell(65,3,'','',0,'L');
					  } else {
							if($cnt_comp == 1){
								$pdf->Ln(1.5);
							}
							$pdf->SetFont('Arial','',7);
							$pdf->Cell(65,3,utf8_decode($rowC['componente']),'',0,'L');
					  }
				  }
					if($rowC['seleccion_resul_negrita'] == "SI"){
					  $pdf->SetFont('Arial','BU',7);
					} else {
					  $pdf->SetFont('Arial','',7);
					}
					if ($rowC['opt_origen_sistema'] == "1"){
						$minuscula = $rowC['componente'];
						if((preg_match("/[g,q,p,y,j]/", $minuscula)) == "1"){
							$pdf->Cell(27,4,utf8_decode($rowC['nombreseleccion_resul']),0,1,'L');
						} else {
							$pdf->Cell(27,3,utf8_decode($rowC['nombreseleccion_resul']),0,1,'L');
						}
					} else {
						$pdf->Cell(27,4,utf8_decode($rowC['det_result']),0,1,'L');
					}
					$pdf->SetFont('Arial','',7);

				}
				$pdf->SetTextColor(130, 130, 130);
				$pdf->SetFont('Arial','',4);
				$pdf->Cell(0,1,'- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -','',1,'');
				$pdf->SetTextColor(0, 0, 0);

			}
        }
      }
	  $oldY = $pdf->getY();
	  $pdf->setY($oldY-1);
	  $pdf->SetFillColor(255,255,255);
	  $pdf->Cell(10,1,'','',0,'',True);
	  $pdf->Cell(50,1,'','B',0,'',True);
	  $pdf->Cell(12,1,'---','',0,'C',True);
	  $pdf->Cell(50,1,'','B',0,'',True);
	  $pdf->Cell(13,1,'','',1,'',True);
	  
	  
}

$pdf->Output();
?>
