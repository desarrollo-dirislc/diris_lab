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
$labIdServicioUser = $_SESSION['labIdServicio'];
$labIdServicioDepUser = $_SESSION['labIdServicioDep'];

require_once '../model/Atencion.php';
$at = new Atencion();

require_once '../model/Componente.php';
$c = new Componente();

require_once '../model/Producton.php';
$pn = new Producton();

require_once '../model/Lab.php';
$la = new Lab();

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

function convertirFecha($fecha_mm_dd_yy) {
    // Convertir la fecha a formato Unix
    $fecha_unix = strtotime($fecha_mm_dd_yy);
    
    // Formatear la fecha a 'dd/mm/yyyy'
    $fecha_dd_mm_yyyy = date('d/m/Y', $fecha_unix);
    
    return $fecha_dd_mm_yyyy;
}

if(isset($_POST['accion'])){

	switch ($_POST['accion']) {
	  case 'POST_REG_RESULTADOLAB_DENGUE':
	  
        require_once __DIR__ . '/../assets/lib/PHPExcel/Classes/PHPExcel.php';
        require_once __DIR__ . '/../assets/lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
		
        $data["file_xls"] = isset($_FILES["file_xls"]) ? $_FILES["file_xls"] : [];
        $data["detalle"] = [];
		
		$file = $data["file_xls"]["tmp_name"];
		$inputFileType = PHPExcel_IOFactory::identify($file);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($file);
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		
		$lowestRow = 2;		
		for ($i = $lowestRow; $i <= $highestRow; $i++) {
			if(trim($sheet->getCell('A' . $i)->getFormattedValue()) <> "" Or trim($sheet->getCell('B' . $i)->getFormattedValue()) <> ""){
				
				$data["detalle"][$i - $lowestRow][0] = trim($sheet->getCell('A' . $i)->getFormattedValue());//Codigo
				$data["detalle"][$i - $lowestRow][1] = trim($sheet->getCell('B' . $i)->getFormattedValue());//Apellido y nombres
				$data["detalle"][$i - $lowestRow][2] = trim($sheet->getCell('C' . $i)->getFormattedValue());//Documento de identidad
				$data["detalle"][$i - $lowestRow][3] = trim($sheet->getCell('D' . $i)->getFormattedValue());//EESS
				$data["detalle"][$i - $lowestRow][4] = trim($sheet->getCell('E' . $i)->getFormattedValue());//Examen
				//$data["detalle"][$i - $lowestRow][5] = $sheet->getCell('F' . $i)->getFormattedValue();//Fecha proceso
				if (PHPExcel_Shared_Date::isDateTime($objPHPExcel->getActiveSheet()->getCell('F' . $i))) {
					$dateTimeObject = PHPExcel_Shared_Date::ExcelToPHPObject($objPHPExcel->getActiveSheet()->getCell('F' . $i)->getValue());
					$data["detalle"][$i - $lowestRow][5] = $dateTimeObject->format('d/m/Y');
				} else {
					$data["detalle"][$i - $lowestRow][5] = '';
				}
				$data["detalle"][$i - $lowestRow][6] = trim($sheet->getCell('G' . $i)->getFormattedValue());//Resultado
				$data["detalle"][$i - $lowestRow][7] = trim($sheet->getCell('H' . $i)->getFormattedValue());//Procesado
				$data["detalle"][$i - $lowestRow][8] = trim($sheet->getCell('I' . $i)->getFormattedValue());//Validado

				if ($data["detalle"][$i - $lowestRow][0] != '') {
					$rs = $la->valid_existe_atencion_lab_ref($data["detalle"][$i - $lowestRow][0], $data["detalle"][$i - $lowestRow][2]);
					if(isset($rs[0]['id_atencion'])){
						$data["detalle"][$i - $lowestRow][9] = $rs[0]['id_atencion'];
						$data["detalle"][$i - $lowestRow][10] = $rs[0]['id_producto'];
						$data["detalle"][$i - $lowestRow][11] = $rs[0]['id_estado_resul'];
						if($rs[0]['id_estado_resul'] <> "4"){
							$id_atencion = $rs[0]['id_atencion'];
							$id_producto = $rs[0]['id_producto'];
							$fec_validacion =  $data["detalle"][$i - $lowestRow][5];

							$usu_resul = "";
							$usu_valid = "";
							$usu_encargado_lab = "745";//SAlAS ALBERTO
							
							$procesa = $data["detalle"][$i - $lowestRow][7];
							$valida = $data["detalle"][$i - $lowestRow][8];
							
							if(($procesa == "T.M. ANURYS MARTINEZ") Or ($procesa=="Lic. TM. ANURYS MARTINEZ") Or ($procesa=="ANURYS MARTINEZ")){//ANURYS MARTINEZ
								$usu_resul = "758";
							}
							if(($procesa == "T.M. ANDRES CARRILLO") Or ($procesa=="Lic. TM. ANDRES CARRILLO MURGA") Or ($procesa=="ANDRES CARRILLO")){// JESUS CARRILLO
								$usu_resul = "751";
							}
							if(($procesa == "TM. CARLOS AMARANTO") Or ($procesa=="LIC T.M. CARLOS AMARANTO") Or ($procesa=="CARLOS AMARANTO")){//CARLOS AMARANTO
								$usu_resul = "758";
							}
							if($procesa == "TEC. NICOLL REAÑO"){//NICOL REAÑO
								$usu_resul = "746";
							}
							
							if(($valida == "T.M. ANURYS MARTINEZ") Or ($valida=="Lic. TM. ANURYS MARTINEZ") Or ($valida=="ANURYS MARTINEZ")){//ANURYS MARTINEZ
								$usu_valid = "758";
							}
							if(($valida == "T.M. ANDRES CARRILLO") Or ($valida=="Lic. TM. ANDRES CARRILLO MURGA") Or ($valida=="ANDRES CARRILLO")){// JESUS CARRILLO
								$usu_valid = "751";
							}
							if(($valida == "TM. CARLOS AMARANTO") Or ($valida=="LIC T.M. CARLOS AMARANTO") Or ($valida=="CARLOS AMARANTO")){//CARLOS AMARANTO
								$usu_valid = "758";
							}
							if($valida == "TEC. NICOLL REAÑO"){//NICOL REAÑO
								$usu_valid = "746";
							}
							
							if($usu_resul == "" Or $usu_valid == ""){
								$data["detalle"][$i - $lowestRow][12] = "No se encontró usuario procesa o usuario validacion";
							} else {
								$valid_ingreso = "0";
								$a = (int) 0;
								$item = (int) 0;
														 //  id_producto,  fecha_proceso,  fecha_validacion
								$datos_producto[$i] = array($id_producto, $fec_validacion, $fec_validacion);
								$rsG = $pn->get_datosGrupoPorIdProductoAndidAtencion($id_atencion, $id_producto);
								if(count($rsG) == 0){
									$rsG = $pn->get_datosGrupoPorIdProducto($id_producto, 1); /////
								}
								foreach ($rsG as $rowG) {
									$rsC = $pn->get_datosComponentePorIdGrupoProdAndIdAtencion($id_atencion, $id_producto, $rowG['id']);
									if(count($rsC) == 0){
										$rsC = $pn->get_datosComponentePorIdGrupoProdAndIdDependenciaActivo($rowG['id'], 67); //Aquí valida en editar si existe o coge esta funcion //67:lAB DE rEFERENCIA
									}
									foreach ($rsC as $rowC) {
										$cod_resultado = "";
										if ($item == 0) {
											$ingValor = 1;
											switch ($data["detalle"][$i - $lowestRow][6]) {
												case "NEGATIVO": $cod_resultado = "107"; break;
												case "POSITIVO": $cod_resultado = "106"; break;
												case "INDETERMINADO": $cod_resultado = "174"; break;
											}
											$det_resul = $cod_resultado;
										} else if($item == 2){
											$ingValor = 1;
											$det_resul = "Inmunoensayo ELISA";
										} else {
											$ingValor = 0;
											$det_resul = "";
										}
										
										//							 id_producto  , id_productogrupo,   muestra grupo ,        orden grupo  ,id_productogrupocomp,id_metodocomponente 	, 		chk_muestra_metodo	  , el id del valor referencial, si ing valor, texto_texbox_seleccion, valor_ingresado,  id de seleccion que eligió, orden componente, fecha_valid
										$reg_datos[$item] = array($id_producto, $rowG['id'], $rowG['chk_muestra'], $rowG['orden_grupo'], $rowC['id'],  $rowC['id_metodocomponente'],  $rowC['chk_muestra_metodo'], Null,  $ingValor,   $rowC['idtipo_ingresol'], $det_resul, $rowC['idseleccion_ingresul'], $rowC['orden_componente'], $fec_validacion);
										$item ++;
									}
								}
									
								$paramReg[0]['accion'] = 'ITV';
								$paramReg[0]['id'] = $id_atencion;
								$paramReg[0]['id_producto_selec'] = 0;
								$paramReg[0]['datos_producto'] = to_pg_array($datos_producto);
								$paramReg[0]['datos'] = to_pg_array($reg_datos);
								$paramReg[0]['obs'] = $usu_resul . "|" . $usu_valid . "|" . $usu_encargado_lab . "|LR";
								$paramReg[0]['userIngreso'] = $labIdUser;
								//print_r($paramReg);
								$rs = $la->reg_resultado_laboratorio($paramReg);
								//echo $rs;
								$data["detalle"][$i - $lowestRow][12] = "El resultado fue ingresado correctamente";
							}
						} else {
							$data["detalle"][$i - $lowestRow][12] = "El código ya tiene resultado";
						}
					} else {
						$data["detalle"][$i - $lowestRow][9] = null;
						$data["detalle"][$i - $lowestRow][10] = null;
						$data["detalle"][$i - $lowestRow][11] = null;
						$data["detalle"][$i - $lowestRow][12] = "No se encontró el código en la BD";
					}
				}
				//echo json_encode( $result );
			}
		}
		if (count($data["detalle"]) == 0) $result["error"] = "No se econtraron datos.";
		
		//print_r($data["detalle"]);
		$errores = array();
		for($i=0; $i < count($data["detalle"]); $i++){
				$errores[] = $data["detalle"][$i][0]."|".$data["detalle"][$i][12];
		}
		$errores_texto = implode("\n", $errores);
		file_put_contents('det_carga_resul_dengue.txt', $errores_texto);
		echo json_encode(array('errores' => $errores_texto));
	  break;
	}
}
?>
