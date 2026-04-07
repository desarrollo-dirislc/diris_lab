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
$labIdServicioDepUser = $_SESSION['labIdServicioDep'];

require_once '../model/UnidadMedida.php';
$um = new UnidadMedida();


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
  case 'POST_ADD_REGUNIDADMEDIDA':
	  $arr_unidmed[0] = array(trim($_POST['descrip_unimedida']), trim($_POST['nombre_unimedida']));
	  $paramReg[0]['accion'] = 'I';
	  $paramReg[0]['id'] = 0;
	  $paramReg[0]['datos'] = to_pg_array($arr_unidmed);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $um->post_reg_unimedida($paramReg);
	  echo $rs;
	  exit();
  break;
  case 'GET_SHOW_LISTAUNIDADMEDIDA':
	  $rs = $um->get_listaUnidadMedida();
	  echo json_encode($rs);
  break;
}
?>
