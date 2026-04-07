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

require_once '../model/Tarifa.php';
$pt = new Tarifa();


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

switch ($_POST['accion']) {
	case 'GET_SHOW_DETPLANTARIFA':
		$rs = $pt->get_datosPlanTarifaPorId($_POST['id_plantarifa']);
		$nr = count($rs);
		if ($nr > 0) {
			$datos = array(
			  0 => $rs[0]['id_plan'],
			  1 => trim($rs[0]['abrev_plan']),
			  2 => trim($rs[0]['nom_plan']),
			  3 => trim($rs[0]['sigla_plan']),
			  4 => $rs[0]['check_precio'],
			  5 => trim($rs[0]['nom_check_precio']),
			  8 => $rs[0]['id_estado'],
			  9 => trim($rs[0]['nom_estado']),
			);
			echo json_encode($datos);
		} else {
			$datos = array(
			  0 => '0'
			);
		}
	break;
	case 'POST_ADD_REGPLANTARIFARIO':
		if($_POST['id_plan'] == "0"){
			$action = 'C';
		} else {
			$action = 'E';
		}
		$arr_reg_datos[0] = array(trim($_POST['abrev_plan']), trim($_POST['sigla_plan']), trim($_POST['nom_plan']), $_POST['check_precio']);
		$paramReg[0]['accion'] = $action;
		$paramReg[0]['id'] = $_POST['id_plan'];
		$paramReg[0]['reg_datos'] = to_pg_array($arr_reg_datos);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $pt->post_reg_plantarifario($paramReg);
		echo $rs;
		exit();
	break;
	case 'GET_SHOW_DEPPORIDPLAN':
		$rs = $pt->get_datosDependenciaPorIdPlanTarifario($_POST['id_plan']);
		$nr = count($rs);
		?>
		<div class="table-responsive">
		<table class="table table-bordered table-hover">
		  <thead>
			<tr>
			  <th><small>Dependencia</small></th>
			  <th><small>Estado</small></th>
			  <th><small>&nbsp;</small></th>
			</tr>
		  </thead>
		  <tbody>
			<?php
			if ($nr > 0) {
			  foreach ($rs as $row) {

				$btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_dep(\'' . $row['id_plandep'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
				$styleEst = "bg-green";

				$nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
				echo "<tr>";
				echo "<td><small>" . $row['nom_depen'] . "</small></td>";
				echo "<td class='text-center'><small>" . $nomEstado . "</small></td>";
				echo "<td class='text-center'><small>" . $btnEst . "</small></td>";
				echo "</tr>";
			  }
			}
			?>
		  </tbody>
		</table>
		</div>
		<?php
	break;
	case 'POST_ADD_REGDEPPORPLANATARIFARIO':
	if($_POST['txtTipIng'] == "AD"){
		$id_plan = $_POST['id_plan'];
		$id_dependencia = $_POST['id_dependencia'];
		$array_id_dep = explode(",", $id_dependencia);
		$nro = 0;
		foreach ($array_id_dep as $id_dep) {
			$arr_reg_datos[$nro] = array($id_dep);
			$nro++;
		}
		$paramReg[0]['accion'] = 'AD';
		$paramReg[0]['id'] = $id_plan;
		$paramReg[0]['reg_datos'] = to_pg_array($arr_reg_datos);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $pt->post_reg_plantarifario($paramReg);
		echo $rs;
		exit();
	} else {
		$arr_reg_datos[0] = array('');
		$paramReg[0]['accion'] = 'ED';
		$paramReg[0]['id'] = $_POST['txt_id_plan_dep'];
		$paramReg[0]['reg_datos'] = to_pg_array($arr_reg_datos);
		$paramReg[0]['userIngreso'] = $labIdUser;
		/*print_r($paramReg);
		exit();*/
		$rs = $pt->post_reg_plantarifario($paramReg);
		echo $rs;
		exit();
	}
	break;
}
?>
