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
$labNomUser = $_SESSION['labNomUser'];
$labIdDepUser = $_SESSION['labIdDepUser'];
require_once '../model/Persona.php';
$p = new Persona();
require_once '../model/Tipo.php';
$t = new Tipo();


function to_pg_array($set) {
  settype($set, 'array'); // can be called with a scalar or array
  $result = array();
  foreach ($set as $t) {
    if (is_array($t)) {
      $result[] = to_pg_array($t);
    } else {
      $t = str_replace('"', '\\"', $t); // escape double quote
      if (!is_numeric($t)) // quote only non-numeric values
      $t = '"' . $t . '"';
      $result[] = $t;
    }
  }
  return '{' . implode(",", $result) . '}'; // format
}

function to_pg_array_one($set) {
  settype($set, 'array'); // can be called with a scalar or array
  $result = array();
  foreach ($set as $t) {
    if (is_array($t)) {
      $result[] = to_pg_array($t);
    } else {
      $t = str_replace('"', '\\"', $t); // escape double quote
      if (!is_numeric($t)) // quote only non-numeric values
      $t = '"' . $t . '"';
      $result[] = $t;
    }
  }
  return implode(",", $result); // format
}

function formatFechaNacimiento($fecha) {
	// Intentamos crear la fecha en formato yyyy-mm-dd
	$date = date_create_from_format('Y-m-d', $fecha);
	// Si falla, intentamos crear la fecha en formato dd/mm/yyyy
	if (!$date) {
		$date = date_create_from_format('d/m/Y', $fecha);
	}
	// Si se pudo crear la fecha en cualquiera de los dos formatos, la formateamos a dd/mm/yyyy
	if ($date) {
		return date_format($date, 'd/m/Y');
	} else {
		// Manejo de errores si la fecha no tiene un formato válido
		return 'Formato de fecha no válido';
	}
}

switch ($_POST['accion']) {
	case 'POST_REG_PERSONA':
		$arr_datos[0] = array($_POST['id_tipo_doc_pac'], $_POST['nro_doc_pac'], trim(strtoupper($_POST['nombres_pac'])), trim(strtoupper($_POST['primer_ape_pac'])), trim(strtoupper($_POST['segundo_ape_pac'])), trim($_POST['id_sexo_pac']), $_POST['fec_nac_pac'], trim($_POST['id_pais_pac']), trim($_POST['id_etnia_pac']), trim($_POST['tel_fij_pac']), trim($_POST['tel_mov_pac']), trim($_POST['email_pac']), trim($_POST['id_ubigeo_pac']), trim($_POST['direccion_pac']), trim($_POST['ref_direccion_pac']), trim($_POST['nro_hc_pac']));
		$paramReg[0]['accion'] = 'ADLAB';
		$paramReg[0]['id'] = $_POST['id_paciente'];
		$paramReg[0]['id_atencion'] = $_POST['id_atencion'];
		$paramReg[0]['datos'] = to_pg_array_one($arr_datos);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $p->post_reg_persona($paramReg);
		echo $rs;
	break;
	case 'GET_SHOW_PERSONA_EDITAR':
		$opt = ($_POST['interfaz_origen'] == "LAB") ? '3' : '4';
	
		$rs = $p->get_datosDetallePersonaUltimaAtencionPorIdDed($opt, $_POST['id_atencion'], '','');//Opcion 2 es por documento  y 1 por idpersona, 3:paciente lab, 4paciente pap
		$nr = count($rs);
		if ($nr > 0) {
			if($rs[0]['fec_nac'] == ""){
			  $fecNacPac = "";
			} else {
			  $fecNacPac = date_create($rs[0]['fec_nac']);
			  $fecNacPac = date_format($fecNacPac, "d/m/Y");
			}
			if($labIdDepUser == "67"){
				if(trim($rs[0]['nro_hc']) == ""){
					$nro_hc = "00";
				} else {
					$nro_hc = trim($rs[0]['nro_hc']); 
				}
			} else {
				$nro_hc = trim($rs[0]['nro_hc']);
			}
			$datos = array(
			  0 => $rs[0]['id_persona'],
			  1 => $rs[0]['id_tipodoc'],
			  2 => $rs[0]['abrev_tipodoc'],
			  3 => $rs[0]['nrodoc'],
			  4 => $rs[0]['nombre_rs'],
			  5 => $rs[0]['primer_ape'],
			  6 => trim($rs[0]['segundo_ape']),
			  7 => $rs[0]['id_sexo'],
			  8 => $rs[0]['abrev_sexo'],
			  9 => $fecNacPac,
			  10 => $nro_hc,
			  11 => trim($rs[0]['nro_telfijo']),
			  12 => trim($rs[0]['nro_telmovil']),
			  13 => trim($rs[0]['email']),
			  14 => trim($rs[0]['id_ubigeo']),
			  15 => trim($rs[0]['departamento']),
			  16 => trim($rs[0]['provincia']),
			  17 => trim($rs[0]['distrito']),
			  18 => trim($rs[0]['direccion']),
			  19 => trim($rs[0]['referencia_dir']),
			  20 => trim($rs[0]['edad']),
			  21 => trim($rs[0]['id_paisnac']),
			  22 => trim($rs[0]['id_etnia']),
			  23 => 'SISTEMA'
			);
			echo json_encode($datos);
		}
	break;
  case 'GET_SHOW_PERSON':
  $rs = $p->get_datosDetallePersona('2', $_POST['txtIdTipDoc'], $_POST['txtNroDoc']);//Opcion 2 es por documento  y 1 por idpersona
  $nr = count($rs);
  if ($nr > 0) {
    if($rs[0]['fec_nac'] == ""){
      $fecNacPac = "";
    } else {
      $fecNacPac = date_create($rs[0]['fec_nac']);
      $fecNacPac = date_format($fecNacPac, "d/m/Y");
    }

    $datos = array(
      0 => $rs[0]['id_persona'],
      1 => $rs[0]['id_tipodoc'],
      2 => $rs[0]['abrev_tipodoc'],
      3 => $rs[0]['nrodoc'],
      4 => $rs[0]['nombre_rs'],
      5 => $rs[0]['primer_ape'],
      6 => trim($rs[0]['segundo_ape']),
      7 => $rs[0]['id_sexo'],
      8 => $rs[0]['abrev_sexo'],
      9 => $fecNacPac
    );
    echo json_encode($datos);
  } else {
    $datos = array(
      0 => '0'
    );
  }

  break;
  case 'GET_SHOW_PERSONULTIMAATENCIONPORIDDEP':
  $nro_hc = "";
  if(!isset($_POST['txtTipoBus'])){
    $rs = $p->get_datosDetallePersonaUltimaAtencionPorIdDed('2', $_POST['txtIdTipDoc'], $_POST['txtNroDoc'], $labIdDepUser);//Opcion 2 es por documento
  }else {
    $rs = $p->get_datosDetallePersonaUltimaAtencionPorIdDed($_POST['txtTipoBus'], $_POST['txtIdTipDoc'], $_POST['txtNroDoc'], $labIdDepUser);//Opcion 1 es por idpersona
  }
  $nr = count($rs);
  if ($nr > 0) {//MISMO SISTEMA
    if($rs[0]['fec_nac'] == ""){
      $fecNacPac = "";
    } else {
      $fecNacPac = date_create($rs[0]['fec_nac']);
      $fecNacPac = date_format($fecNacPac, "d/m/Y");
    }
	if($labIdDepUser == "67"){
		if(trim($rs[0]['nro_hc']) == ""){
			$nro_hc = "00";
		} else {
			$nro_hc = trim($rs[0]['nro_hc']); 
		}
	} else {
		$nro_hc = trim($rs[0]['nro_hc']);
	}
    $datos = array(
      0 => $rs[0]['id_persona'],
      1 => $rs[0]['id_tipodoc'],
      2 => $rs[0]['abrev_tipodoc'],
      3 => $rs[0]['nrodoc'],
      4 => $rs[0]['nombre_rs'],
      5 => $rs[0]['primer_ape'],
      6 => trim($rs[0]['segundo_ape']),
      7 => $rs[0]['id_sexo'],
      8 => $rs[0]['abrev_sexo'],
      9 => $fecNacPac,
      10 => $nro_hc,
      11 => trim($rs[0]['nro_telfijo']),
      12 => trim($rs[0]['nro_telmovil']),
      13 => trim($rs[0]['email']),
      14 => trim($rs[0]['id_ubigeo']),
      15 => trim($rs[0]['departamento']),
      16 => trim($rs[0]['provincia']),
      17 => trim($rs[0]['distrito']),
      18 => trim($rs[0]['direccion']),
      19 => trim($rs[0]['referencia_dir']),
      20 => trim($rs[0]['edad']),
      21 => trim($rs[0]['id_paisnac']),
      22 => trim($rs[0]['id_etnia']),
	  23 => 'SISTEMA'
    );
    echo json_encode($datos);
  } else {//PADRON DNI Y API MINSA
    //Si es DNI
    if ($_POST['txtIdTipDoc'] == "1"){
		/*include './ctrlPersonaMinsa.php';
		exit();*/
		
      //Padron
      $rs = $p->get_datosDetallePersonaPadron($_POST['txtNroDoc']);
      $nr = count($rs);
	  //$nr = 0;
      if ($nr > 0) { //PADRON DNI
        if($rs[0]['fec_nac'] == ""){
          $fecNacPac = "";
        } else {
          $fecNacPac = date_create($rs[0]['fec_nac']);
          $fecNacPac = date_format($fecNacPac, "d/m/Y");
        }
		if($labIdDepUser == "67"){
			$nro_hc = "00";
		}
        $datos = array(
          0 => 0,
          1 => $_POST['txtIdTipDoc'],
          2 => '',
          3 => $_POST['txtNroDoc'],
          3 => $rs[0]['nrodoc'],
          4 => $rs[0]['nombre_rs'],
          5 => $rs[0]['primer_ape'],
          6 => trim($rs[0]['segundo_ape']),
          7 => $rs[0]['id_sexo'],
          8 => $rs[0]['abrev_sexo'],
          9 => $fecNacPac,
          10 => $nro_hc,
          11 => '',
          12 => '',
          13 => '',
          14 => '',
          15 => '',
          16 => '',
          17 => '',
          18 => '',
          19 => '',
          20 => trim($rs[0]['edad']),
          21 => 'PER',
          22 => '',
		  23 => 'PADRON'
        );
        echo json_encode($datos);
      } else {//NO ENCONTRÓ EN PADRON, AHORA BUSCA EN API
			//Sino encontro en el padron
			//Webservices
			include './ctrlPersonaMinsa.php';
			//$dataminsa = 0;
			/*$datos = array(
				0 => "C",
				23 => ""
				);
			echo json_encode($datos);*/
			exit();
			
			//Lo del minsa lo desabilite porque bloquearon la IP (YA NO FUNCIONA QUEDÓ OBSOLETO)
		if ($dataminsa == 0) {
				$url="http://logincentral.minsa.gob.pe";
				$ch = curl_init();
				$timeout = 60;
				curl_setopt($ch, CURLOPT_COOKIESESSION, true);
				curl_setopt($ch, CURLOPT_COOKIE, "csrftoken=CqpqIojatsMIUk0IOwJ3W1s0JiYJjsGzbOlOoCKpBnVu8H6JrknS9ZHjrREXLODo");
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "csrfmiddlewaretoken=99xrVKNnO6TUaYnUy42ugNxVOc2EgRS1IxtPBYeCW12GoltVbSGjtLMewLISIdPQ&username=45089276&password=jasv1234&app_identifier=pe.gob.minsa.citas&login_uuid=");
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);/*verificar y cambiar a false para que funcione*/
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch, CURLOPT_HEADER, true);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
				curl_setopt($ch, CURLOPT_AUTOREFERER,true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$data = curl_exec($ch);
				//curl_close($ch);

				$data= iconv("ISO-8859-1","UTF-8",$data);
				$chars = preg_split('/<[^>]*[^\/]>/i', $data, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);


				$csrftoken=substr($chars[0],strpos($chars[0],'csrftoken')+10,64);
				$sessionid=substr($chars[0],strpos($chars[0],'sessionid')+10,400);
				$separator=strpos($sessionid,'Domain')-2;
				$sessionidfinal=substr($sessionid,0,$separator);

				$urlki="http://limacentro.login.minsa.gob.pe/api/v1/ciudadano/01/".$_POST['txtNroDoc']."/";
				$chki = curl_init();
				$timeout = 0;
				curl_setopt($chki, CURLOPT_COOKIESESSION, false);
				curl_setopt($chki, CURLOPT_COOKIE, "csrftoken=".$csrftoken."; sessionid=".$sessionidfinal);
				curl_setopt($chki, CURLOPT_URL, $urlki);
				curl_setopt($chki, CURLOPT_POST, 0);
				curl_setopt($chki, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($chki, CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($chki, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($chki, CURLOPT_HEADER, false);
				curl_setopt($chki, CURLOPT_REFERER, $urlki);
				curl_setopt($chki, CURLOPT_AUTOREFERER, true);
				curl_setopt($chki, CURLOPT_FOLLOWLOCATION,true);
				curl_setopt($chki, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
				curl_setopt($chki, CURLOPT_AUTOREFERER,true);
				curl_setopt($chki, CURLOPT_CONNECTTIMEOUT, $timeout);
				$dataki = curl_exec($chki);
				curl_close($chki);

				//$data= mb_convert_encoding($data,"utf-8","ISO-8859-1");
				//$data=htmlspecialchars_decode(utf8_decode(htmlentities($data, ENT_COMPAT, 'utf-8', false)));
				$dataki= iconv("ISO-8859-1","UTF-8",$dataki);
				$charski = preg_split('/<[^>]*[^\/]>/i', $dataki, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

				if(isset($charski[0])){
				  $error=strpos($charski[0],'Bad Request');
				  //echo $error;

				  if (empty($error)){
					$convert= $charski[0];
					$result=json_decode($convert);
					$var=(array)$result;
					//print_r($var);

					if(isset($var['errors'])){
					  $datos = array(
						0 => "E"//No existe ese DNI
					  );
					  echo json_encode($datos);
					} else if(!isset($var['sexo'])){
					  $datos = array(
						0 => "C"
					  );
					  echo json_encode($datos);
					} else {
						if($var['sexo']=='1'){$varsexo='1';}else{$varsexo='2';}
						if($var['domicilio_direccion_actual']==''){$vardir=  $var['domicilio_direccion']; }else{$vardir=$var['domicilio_direccion_actual'];}

						if($var['fecha_nacimiento'] == ""){
						  $fecNacPac = "";
						} else {
						  $fecNacPac = date_create($var['fecha_nacimiento']);
						  $fecNacPac = date_format($fecNacPac, "d/m/Y");
						}

						$datos = array(
						  0 => 0,
						  1 => $_POST['txtIdTipDoc'],
						  2 => '',
						  3 => $_POST['txtNroDoc'],
						  4 => trim(utf8_decode($var['nombres'])),
						  5 => trim(utf8_decode($var['apellido_paterno'])),
						  6 => trim(utf8_decode($var['apellido_materno'])),
						  7 => $varsexo,
						  8 => '',
						  9 => $fecNacPac,
						  10 => '',
						  11 => '',
						  12 => '',
						  13 => '',
						  14 => '',
						  15 => '',
						  16 => '',
						  17 => '',
						  18 => trim(utf8_decode($vardir)),
						  19 => '',
						  20 => $var['edad_anios'],
						  21 => 'PER',
						  22 => $var['etnia'],
						  23 => 'EQ'
						);
						echo json_encode($datos);
					}//Fin cuando no hay variable sexo osea no hay conexción
				}// fin no hay Web services Equali
				} else {//Fin empty Webservice
					$datos = array(
					0 => 0
					);
					echo json_encode($datos);
				}//Fin sino encontro en el Webservice
        } else {//Fin Si no encontro en MINSA
          $datos = array(
            0 => 0
          );
          echo json_encode($datos);
        }//Fin sino encontro en el Webservice Equali
      }//Fin sino encontro en el padron
    } else {//Fin si es DNI
      //Sino es DNI
      $datos = array(
        0 => 'NE'
      );
      echo json_encode($datos);
    }//Fin sino es DNI
  }//Fin de Persona por id dependencia

  break;
  case 'GET_SHOW_PERSONULTIMAATENCIONPORIDDEP1':
	include './ctrlPersonaMinsa.php';
  break;
  case "GET_SHOW_DETDISRECCIONVIAPORPERSONA":
  if($_POST['txtIdPer'] <> "0"){
    $idTipDoc = $_POST['txtIdPer'];
    $nroDoc = "";
  } else {
    $idTipDoc = $_POST['txtIdTipDoc'];
    $nroDoc = $_POST['txtNroDoc'];
  }

  $rs = $p->get_datosDetalleDireccionConViaPorDatosPersona('1', $idTipDoc, $nroDoc);//Opcion 2 es por documento  y 1 por idpersona
  $nr = count($rs);
  if ($nr > 0) {
    $datos = array(
      0 => $rs[0]['id_histodireccion'],
      1 => $rs[0]['id_ubigeo'],
      2 => $rs[0]['nom_departamento'],
      3 => $rs[0]['nom_provincia'],
      4 => $rs[0]['nom_distrito'],
      5 => trim($rs[0]['id_tipovia']),
      6 => trim($rs[0]['abrev_tipovia']),
      7 => trim($rs[0]['nom_tipovia']),
      8 => trim($rs[0]['nom_via']),
      9 => trim($rs[0]['nro_dir']),
      10 => trim($rs[0]['int_dir']),
      11 => trim($rs[0]['dpto_dir']),
      12 => trim($rs[0]['mz_dir']),
      13 => trim($rs[0]['lt_dir']),
      14 => trim($rs[0]['id_tipopoblacion']),
      15 => trim($rs[0]['abrev_tipopoblacion']),
      16 => trim($rs[0]['nom_tipopoblacion']),
      17 => trim($rs[0]['nom_poblacion']),
      18 => trim($rs[0]['direccion']),
      19 => trim($rs[0]['referencia_dir'])
    );
    echo json_encode($datos);
  } else {
    $datos = array(
      0 => 0
    );
    echo json_encode($datos);
  }
  break;

}
?>
