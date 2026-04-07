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

require_once '../model/Ses.php';
$ses = new Ses();

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

function jsonString20obl($str){
	return json_decode(stripcslashes($str));
}

switch ($_POST['accion']) {
  case "GET_VALIDAINF_DEPENMESYANIO":	
	$param[0]['anio'] = $_POST['anio'];
	$param[0]['mes'] = $_POST['mes'];
	$param[0]['id_dependencia'] = $_POST['id_dependencia'];
	$rs = $ses->get_repCntAtencionPorAnioMesAndIdDependencia($param);
	if(isset($rs[0]['cnt'])){
		$rsV = $ses->get_existe_informePorAnioMesIdDep($param);
		echo ($rs[0]['cnt'] + $rs[0]['cnt_bk'] + $rsV);
		exit();
	} else {
		echo 0;
	}
  break;
  case "GET_SEMAFOROLABPORDEPENDENCIA":
	?>
    <table id="fixTable" class="table table-bordered table-hover">
	<thead>
		<tr>
		<th class="bg-aqua">Dependencia</th>
		<th class="bg-aqua">L</th>
		<th class="bg-aqua">B</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$param[0]['anio'] = $_POST['anio'];
	$param[0]['mes'] = $_POST['mes'];
	$param[0]['id_dependencia'] = $_POST['id_dependencia'];
	$style = "";
	$style_bk = "";
	$rs = $ses->get_repCntAtencionPorAnioMesAndIdDependencia($param);
	//print_r($rs);
	foreach ($rs as $row) {
	  
		if($row['cnt'] <> "0"){
			$style = "bg-green";
		} else {
			$style = "bg-red";
		}
		
		if($row['cnt_bk'] <> "0"){
			$style_bk = "bg-green";
		} else {
			$style_bk = "bg-red";
		}
	?>
		<tr>
			<td>
				<small><b><?php echo $row['nro_ris_pertenece'];?> - <?php echo $row['nom_depen'];?></b></small>
			</td>
			<td class="text-center"><a href="#"><span class="badge <?php echo $style?>">&nbsp;</span></a></td>
			<td class="text-center"><a href="#"><span class="badge <?php echo $style_bk?>">&nbsp;</span></a></td>
		</tr>
	<?php
	}
	?>
	</tbody>
	</table>
  <?php
  break;
  case "POST_REG_INFORMEDETLAB":
		$data = $_POST;
		//print_r($data);
		//echo $data['txt_ate_aus'];
		/*foreach ($data['txt_hem_id_prod'] as $clave=>$valor){ //Una manera 
		  echo "El valor de $clave es:". $valor;
		}*/
		$array_det = (int) 0;
		$cnt_hem = (int) count($data['txt_hem_id_prod']);
		for($i=0; $i < $cnt_hem; $i++){
			$arr_exa[$array_det] = array(1,$data['txt_hem_id_prod'][$i], $data['txt_hem_nro_prod'][$i], $data['txt_hem_aus'][$i], $data['txt_hem_pag'][$i], $data['txt_hem_esa'][$i], $data['txt_hem_exo'][$i], ($data['txt_hem_aus'][$i] + $data['txt_hem_pag'][$i] + $data['txt_hem_esa'][$i] + $data['txt_hem_exo'][$i]));
			$array_det ++;
		}
		
		$array_bio = (int) 0;
		$cnt_bio = (int) count($data['txt_bio_id_prod']);
		for($i=0; $i < $cnt_bio; $i++){
			$arr_exa[$array_det] = array(2,$data['txt_bio_id_prod'][$i], $data['txt_bio_nro_prod'][$i], $data['txt_bio_aus'][$i], $data['txt_bio_pag'][$i], $data['txt_bio_esa'][$i], $data['txt_bio_exo'][$i],($data['txt_bio_aus'][$i] + $data['txt_bio_pag'][$i] + $data['txt_bio_esa'][$i] + $data['txt_bio_exo'][$i]));
			$array_det ++;
		}
		
		$array_inm = (int) 0;
		$cnt_inm = (int) count($data['txt_inm_id_prod']);
		for($i=0; $i < $cnt_inm; $i++){
			$arr_exa[$array_det] = array(3,$data['txt_inm_id_prod'][$i], $data['txt_inm_nro_prod'][$i], $data['txt_inm_aus'][$i], $data['txt_inm_pag'][$i], $data['txt_inm_esa'][$i], $data['txt_inm_exo'][$i], ($data['txt_inm_aus'][$i] + $data['txt_inm_pag'][$i] + $data['txt_inm_esa'][$i] + $data['txt_inm_exo'][$i]));
			$array_det ++;
		}
		
		$array_mic = (int) 0;
		$cnt_mic = (int) count($data['txt_mic_id_prod']);
		for($i=0; $i < $cnt_mic; $i++){
			$arr_exa[$array_det] = array(4,$data['txt_mic_id_prod'][$i], $data['txt_mic_nro_prod'][$i], $data['txt_mic_aus'][$i], $data['txt_mic_pag'][$i], $data['txt_mic_esa'][$i], $data['txt_mic_exo'][$i], ($data['txt_mic_aus'][$i] + $data['txt_mic_pag'][$i] + $data['txt_mic_esa'][$i] + $data['txt_mic_exo'][$i]));
			$array_det ++;
		}
		
		$array_paq = (int) 0;
		$cnt_paq = (int) count($data['txt_paq_id_prod']);
		for($i=0; $i < $cnt_paq; $i++){
			$arr_exa[$array_det] = array(6,$data['txt_paq_id_prod'][$i], $data['txt_paq_nro_prod'][$i], $data['txt_paq_aus'][$i], $data['txt_paq_pag'][$i], $data['txt_paq_esa'][$i], $data['txt_paq_exo'][$i], ($data['txt_paq_aus'][$i] + $data['txt_paq_pag'][$i] + $data['txt_paq_esa'][$i] + $data['txt_paq_exo'][$i]));
			$array_det ++;
		}
		$servicio_ing = $labIdServicioUser;
		$tot_exa = $data['hem_tot_tot'] + $data['bio_tot_tot'] + $data['inm_tot_tot'] + $data['mic_tot_tot'] + $data['paq_tot_tot'];
		$arr_informe[0] = array($data['id_dependencia'], $data['anio'], $data['mes'], $data['ate_tot'], $tot_exa, '', $servicio_ing);//'' es la observación si me piden
		$arr_total_ate[0] = array(0, 0, 0, $data['txt_ate_aus'], $data['txt_ate_pag'], $data['txt_ate_esa'], $data['txt_ate_exo'], ($data['txt_ate_aus'] + $data['txt_ate_pag'] + $data['txt_ate_esa'] + $data['txt_ate_exo']));
		$arr_total_exa[0] = array($data['hem_tot_tot'], $data['bio_tot_tot'], $data['inm_tot_tot'], $data['mic_tot_tot'], $data['paq_tot_tot']);
		
		if($_POST['id_informe'] == "0"){
			$accion = 'IL';
		} else {
			$accion = 'EL';
		}
		$paramReg[0]['accion'] = $accion;
		$paramReg[0]['id'] = $_POST['id_informe'];
		$paramReg[0]['dato_informe'] = to_pg_array($arr_informe);
		$paramReg[0]['dato_atencion'] = to_pg_array($arr_total_ate);
		$paramReg[0]['dato_examen'] = to_pg_array($arr_total_exa);
		$paramReg[0]['dato_det_examen'] = to_pg_array($arr_exa);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $ses->reg_informe_ses($paramReg);
		echo $rs;
  break;
  case "POST_REG_INFORMEDETBAC":
		$data = $_POST;
		$array_det = (int) 0;
		$cnt_hem = (int) count($data['txt_bk_id']);
		for($i=0; $i < $cnt_hem; $i++){
			$arr_exa[$array_det] = array($data['txt_bk_id'][$i], $data['txt_bk_nro'][$i], $data['txt_bk_aten'][$i], $data['txt_bk_posi1'][$i], $data['txt_bk_posi2'][$i], $data['txt_bk_posi3'][$i], $data['txt_bk_pau'][$i], ($data['txt_bk_posi1'][$i] + $data['txt_bk_posi2'][$i] + $data['txt_bk_posi3'][$i] + $data['txt_bk_pau'][$i]));
			$array_det ++;
		}
		
		$servicio_ing = $labIdServicioUser;
		$arr_informe[0] = array($data['id_dependencia'], $data['anio'], $data['mes'], $data['ate_tot'], $data['posi_tot'], '', $servicio_ing);//'' es la observación si me piden
		$arr_total_ate[0] = array('');
		$arr_total_exa[0] = array('');
		
		if($_POST['id_informe'] == "0"){
			$accion = 'IB';
		} else {
			$accion = 'EB';
		}
		$paramReg[0]['accion'] = $accion;
		$paramReg[0]['id'] = $_POST['id_informe'];
		$paramReg[0]['dato_informe'] = to_pg_array($arr_informe);
		$paramReg[0]['dato_atencion'] = to_pg_array($arr_total_ate);
		$paramReg[0]['dato_examen'] = to_pg_array($arr_total_exa);
		$paramReg[0]['dato_det_examen'] = to_pg_array($arr_exa);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $ses->reg_informe_ses($paramReg);
		echo $rs;
  break;
}
?>