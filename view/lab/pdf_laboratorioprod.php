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

include '../../assets/lib/fpdf/fpdf.php';
include '../../assets/lib/qr/phpqrcode/qrlib.php';

require_once '../../model/Area.php';
$a = new Area();
require_once '../../model/Grupo.php';
$g = new Grupo();
require_once '../../model/Componente.php';
$c = new Componente();
require_once '../../model/Producto.php';
$p = new Producto();
require_once '../../model/Atencion.php';
$at = new Atencion();

$idAtencion = $_GET['id_atencion'];
$idProd =  $_GET['id_prod'];

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

$rsA = $at->get_datosAtencion($idAtencion);
//print_r($rsA);
//exit();
$codeContents ="http://app1.dirislimacentro.gob.pe/labs-result/?p=".md5($rsA[0]['id_dependencia'])."&valid=".$valini.".".md5($idAtencion).".".$valfin;
$tempDir = __DIR__ . '/'; //EXAMPLE_TMP_SERVERPATH;
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
    //Logo
    $this->Image('../../assets/images/logo_diris.png',10,4,50);
    $this->SetFont('Arial','',6);

    require_once '../../model/Atencion.php';
    $at = new Atencion();
    $idAtencion = $_GET['id_atencion'];
    $rsA = $at->get_datosAtencion($idAtencion);

    //Aubtitles
    $this->SetFont('Arial','B',7);
    $this->Cell(0,4,utf8_decode($rsA[0]['nom_depen']),0,1,'R');
    //$this->SetFont('Arial','',7);
    $this->Cell(0,3,utf8_decode('Nro. Atencion: '.$rsA[0]['nro_atenciondia']),0,1,'R');

    $this->SetFont('Arial','B',10);
    $this->Cell(40,2,'',0,1,'');
    $this->Cell(0,5,'SERVICIO DE LABORATORIO',0,1,'C');

    $this->SetFont('Arial','B',7);
    $this->Cell(40,2,'',0,1,'');

    $this->Cell(20,4,'Paciente',0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(98,4, utf8_decode(': ' . $rsA[0]['nombre_rspac']),0,0,'');

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
    $this->Cell(20,4,utf8_decode('Historia clínica'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(27,4, ': ' . $naci .'-'. $rsA[0]['nro_docpac'] ,0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(8,4,'Edad',0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(24,4,utf8_decode(': ' . $rsA[0]['edad_anio'] . ' AÑOS'),0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(23,4,utf8_decode('Fecha atención'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(30,4,': '. $rsA[0]['fechora_toma'],0,1,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(20,4,utf8_decode('Hist. Cl. Interna'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(27,4, utf8_decode(': ' . $rsA[0]['nro_hcpac']),0,0,'');


    $this->SetFont('Arial','B',7);
    $this->Cell(8,4,'SIS',0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(24,4,utf8_decode(': ' . $rsA[0]['nom_checktipopac']),0,0,'');

    $this->SetFont('Arial','B',7);
    $this->Cell(23,4,utf8_decode('Página'),0,0,'');
    $this->SetFont('Arial','',7);
    $this->Cell(30,4,':       '.$this->PageNo().'   de   {nb}',0,1,'');
  }

  //Pie de página
  function Footer()
  {
    //Posición: a 1,5 cm del final
    $labNomUser = $_SESSION['labNomUser'];
    require_once '../../model/Atencion.php';
    $at = new Atencion();
    $idAtencion = $_GET['id_atencion'];
	//$this->Image('qr/qr.jpg',11, $this->SetY(-44),27);
    $this->SetY(-35);
	
    $rsA = $at->get_datosAtencionProfesionalValidaResul($idAtencion);
    if(isset($rsA[0]['id_profesional'])){
      $url = "../genecrud/profesional/";
      $nomArchiJpg = $rsA[0]['id_profesional'].".jpg";
      if (file_exists($url . $nomArchiJpg)) {
        $this->Image($url.$nomArchiJpg,102,$this->GetY(),23);
      }
      $nomArchiPng = $rsA[0]['id_profesional'].".png";
      if (file_exists($url . $nomArchiPng)) {
        $this->Image($url.$nomArchiPng,102,$this->GetY(),23);
      }

      $rsHI = $at->get_datosfecHoraActual();
      $this->Ln(15);
      $this->SetFont('Arial','',6);
      $this->Cell(78,3,'',0,0,'');
      $this->Cell(53,3,utf8_decode($rsA[0]['nom_prof'] . " " . $rsA[0]['primer_apeprof'] . " " . $rsA[0]['segundo_aprprof']),'T',1,'C');
      $this->SetFont('Arial','',5);
      $this->Cell(78,3,$rsHI[0]['fechora_actual'] . " (".$labNomUser.")",0,0,'');
      $this->SetFont('Arial','',6);
      $this->Cell(53,3,utf8_decode("CMP. ".$rsA[0]['nro_colegiatura']),0,1,'C');
    } else {
      $rsHI = $at->get_datosfecHoraActual();
      $this->Ln(15);
	  $this->SetFont('Arial','',6);
      $this->Cell(78,3,'',0,0,'');
      $this->Cell(53,3,'','',1,'C');
      $this->SetFont('Arial','',5);
      $this->Cell(78,3,$rsHI[0]['fechora_actual'] . " (".$labNomUser.")",0,0,'');
    }
  }
}

//$pdf=new FPDF('L','mm','A4');
$pdf=new PDF('P','mm','A5');
//$pdf->SetLeftMargin(6);
$pdf->SetAutoPageBreak(true,4);
$pdf->SetMargins(8,4,8);
$pdf->AliasNbPages();


$rsP = $p->get_listaProductoPorIdAtencion($idAtencion);
foreach ($rsP as $rowP) {
  $pdf->AddPage();

  $pdf->Ln(2);
  $pdf->SetFont('Arial','IB',7);
  $pdf->Cell(52,4,utf8_decode('ANALISIS CLINICO'),0,0,'C');
  $pdf->Cell(20,4,utf8_decode('U.M.'),0,0,'C');
  $pdf->Cell(30,4,utf8_decode('RESULTADO'),0,0,'C');
  $pdf->Cell(30,4,utf8_decode('VALOR DE REFERENCIA'),0,1,'C');

  $pdf->Ln(1);
  $pdf->SetFont('Arial','IB',8);
  $pdf->Cell(0,4,utf8_decode($rowP['nom_producto']),0,0,'L');
  $pdf->Ln(3);

  $rsPA = $p->get_listaProductoAnteriorPorIdProducto($rowP['id_producto']);
  foreach ($rsPA as $rowPA) {
    $idProdAnte = "";
    if ($rowPA['id_productoori'] <> ""){
      $idProdAnte = $rowPA['id_productoori'];
      $rsDP = $p->get_datosProductoPorId($idProdAnte);
      $pdf->Ln(3);
      $pdf->SetFont('Arial','IB',7);
      $pdf->Cell(0,4,utf8_decode($rsDP[0][2]),0,0,'L');
      $pdf->Ln(1);
    }

    $rsA = $a->get_listaAreaPorIdAtencionAndIdProductoAndIdProdAnterior($idAtencion, $rowP['id_producto'], $idProdAnte);
    foreach ($rsA as $rowA) {
      $pdf->Ln(2);
      $pdf->SetFont('Arial','IB',7);
      $pdf->Cell(0,4,utf8_decode($rowA['area']),0,1,'L');

      $rsG = $g->get_listaGrupoPorIdAreaAndIdAtencionAndIdProducto($idAtencion, $rowA['id_area'], $rowP['id_producto']);
      $cantG = Count($rsG);
      foreach ($rsG as $rowG) {
        $rsC = $c->get_listaComponenteResulPorIdGrupoAreaAndIdAtencionAndIdProducto($rowG['id_grupoarea'], $idAtencion, $rowP['id_producto']);
        /*print_r($rsC);
        exit();*/
        foreach ($rsC as $rowC) {
          if($rowA['visible'] == "1"){
            if($rowC['tipo_ingresosol'] == "1"){
              $valMin = "";
              $valMax = "";
              $totVal = "";
              $valRes = $rowC['det_result'];
              $valColor = "0";
              switch($rowC['idtipcarac_ingsol']){
                case "1":
                $totVal = nl2br($rowC['valor_ref']);
                break;
                case "2":
                $totVal = nl2br($rowC['valor_ref']);
                break;
                case "3":
                if($rowC['liminf'] <> "") {
                  $valMin = $rowC['liminf'];
                  $valMax = $rowC['limsup'];
                  $totVal = number_format($rowC['liminf']) . " - " . number_format($rowC['limsup']);
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
                  $totVal = nl2br($rowC['valor_ref']);
                }
                break;
                case "4":
                if($rowC['liminf'] <> "") {
                  $valMin = $rowC['liminf'];
                  $valMax = $rowC['limsup'];
                  $totVal = number_format($rowC['liminf'], $rowC['dettipcarac_ingsol'], '.', '') . " - " . number_format($rowC['limsup'], $rowC['dettipcarac_ingsol'], '.', '');
				  if($rowC['det_result'] <> ""){
					  $valRes = number_format($rowC['det_result'], $rowC['dettipcarac_ingsol'], '.', '');
					  if($rowC['det_result'] < $valMin){
						$valColor = "1";
					  }
					  if($rowC['det_result'] > $valMax) {
						$valColor = "2";
					  }
				  }
				  
                } else {
                  $totVal = nl2br($rowC['valor_ref']);
                }
                break;
                default:
                $totVal = nl2br($rowC['valor_ref']);
                break;
              }
              $pdf->SetFont('Arial','',7);
              $pdf->Cell(54,4,utf8_decode($rowC['componente']),'',0,'L');
              $pdf->SetFont('Arial','',7);
              $pdf->Cell(18,4,utf8_decode($rowC['uni_medida']),0,0,'C');
              switch($valColor){
                case "1":
                $pdf->SetFont('Arial','BI',7);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->Cell(30,4,utf8_decode($valRes." *"),0,0,'C');
                break;
                case "2":
                $pdf->SetFont('Arial','BI',7);
                $pdf->SetTextColor(128, 0, 0);
                $pdf->Cell(30,4,utf8_decode($valRes." *"),0,0,'C');
                break;
                default:
                $pdf->Cell(30,4,utf8_decode($valRes),0,0,'C');
                break;
              }
              $pdf->SetFont('Arial','',7);
              $pdf->SetTextColor(0, 0, 0);
              $pdf->MultiCell(30,4,utf8_decode($totVal),0,'C','');//Este es el valor referencial
            } elseif ($rowC['tipo_ingresosol'] == "2") {
			  $pdf->SetFont('Arial','',7);
			  $pdf->Cell(54,4,utf8_decode($rowC['componente']),'',0,'L');
			  $pdf->MultiCell(68,4,utf8_decode($rowC['det_result']),0,'J','');		
			} else {
			  $pdf->SetFont('Arial','',7);
			  $pdf->Cell(54,4,utf8_decode($rowC['componente']),'',0,'L');
			  $pdf->Cell(18,4,utf8_decode($rowC['uni_medida']),0,0,'C');
			  $pdf->Cell(30,4,utf8_decode($rowC['nombreseleccion_resul']),0,0,'C');
			  $pdf->SetTextColor(0, 0, 0);
			  $pdf->MultiCell(30,4,utf8_decode($rowC['valor_ref']),0,'C','');//Valor referencia
			}
          } else {
            if($cantG = 1){
              $pdf->SetFont('Arial','',7);
              $pdf->MultiCell(125,4,utf8_decode($rowC['det_result']),0,'J','');
            }
          }
		  $pdf->SetTextColor(130, 130, 130);
		  $pdf->SetFont('Arial','',4);
		  $pdf->Cell(0,1,'- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -','',1,'');
		  $pdf->SetTextColor(0, 0, 0);
        }
      }

    }
  }
  $pdf->Ln(2);
  $pdf->Cell(10,1,'','',0,'');
  $pdf->Cell(50,1,'','B',0,'');
  $pdf->Cell(12,1,'***','',0,'C');
  $pdf->Cell(50,1,'','B',1,'');
}

$pdf->Output();
?>
