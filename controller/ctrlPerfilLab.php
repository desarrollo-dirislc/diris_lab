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

require_once '../model/PerfilLab.php';
$pe = new PerfilLab();

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
  case 'POST_ADD_REGPERFIL':
	$arr_datos[0] = array($_POST['nombre_perfil'], $_POST['abrev_perfil']);

	if($_POST['id'] == "0"){
		$action = "C";
	} else {
		$action = "E";
	}
	$paramReg[0]['accion'] = $action;
	$paramReg[0]['id'] = $_POST['id'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $pe->post_crud_perfillab($paramReg);
	if($rs == ""){
		echo "OK";
	} else {
		echo $rs;
	}
	exit();
  break;
  case 'GET_SHOW_COMPONENTEXPERFIL':
	if($_POST['id_perfil'] <> ""){
		$rs = $pe->get_datosComponenteporPerfil($_POST['id_perfil']);
		$nr = count($rs);
		if ($nr > 0) {
		  foreach ($rs as $row) {
			$btn = '<button class="btn btn-danger btn-xs" onclick="delete_componente(\'' . $row['id'] . '\');"><i class="glyphicon glyphicon-trash"></i></button>';
			echo "<tr>";
			echo "<td><small>" . $row['nombre_perfil'] . "</small></td>";
			echo "<td><small><b>" . $row['nom_producto'] . "</b></small></td>";
			echo "<td class='text-center'><small>" . $btn . "</small></td>";
			echo "</tr>";
		  }
		}
	}
  break;
  case 'POST_ADD_REGCOMPPERFIL':
	$arr_datos[0] = array($_POST['idcompdet']);

	$paramReg[0]['accion'] = "CP";
	$paramReg[0]['id'] = $_POST['id_perfil'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $pe->post_crud_perfillab($paramReg);
	if($rs == ""){
		echo "OK";
	} else {
		echo $rs;
	}
	exit();
  break;
  case 'POST_DELETE_REGCOMPPERFIL':
	$arr_datos[0] = array('');

	$paramReg[0]['accion'] = "DCP";
	$paramReg[0]['id'] = $_POST['idcompdetperfil'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $pe->post_crud_perfillab($paramReg);
	if($rs == ""){
		echo "OK";
	} else {
		echo $rs;
	}
	exit();
  break;
}
?>
