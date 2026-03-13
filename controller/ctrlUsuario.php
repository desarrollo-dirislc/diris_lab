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

require_once '../model/Usuario.php';
$u = new Usuario();
require_once '../model/Menu.php';
$ma = new Menu();

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
  case 'GET_SHOW_DETUSUARIO':
  $rs = $u->get_datosUsuarioPorId($_POST['idUsu']);
  $nr = count($rs);
  if ($nr > 0) {
    $datos = array(
      0 => $rs[0]['id_usuario'],
      1 => $rs[0]['id_persona'],
      2 => $rs[0]['id_tipodoc'],
      3 => $rs[0]['nrodoc'],
      4 => $rs[0]['nom_usuario']
    );
    echo json_encode($datos);
  } else {
    $datos = array(
      0 => '0'
    );
  }
  break;
  case 'GET_SHOW_EXISUSUARIO':
  $rs = $u->post_valid_exis_usuario($_POST['txtIdTipDoc'], $_POST['txtNroDoc']);
  echo $rs;
  break;
  case 'POST_ADD_REGUSUARIO':
  if($_POST['txtIdUser'] == "0"){
    $accion = 'C';
	$fec_nac = ($_POST['txtFecNacPac'] == "") ? Null : $_POST['txtFecNacPac'];
    $arr_persona[0] = array($_POST['txtIdPer'], $_POST['txtIdTipDoc'], $_POST['txtNroDoc'], trim($_POST['txtNomPac']), trim($_POST['txtPriApePac']), trim($_POST['txtSegApePac']), $_POST['txtIdSexoPac'], $_POST['txtFecNacPac'], trim($_POST['txtNroTelFijoPac']), trim($_POST['txtNroTelMovilPac']), trim($_POST['txtEmailPac']), $_POST['txtValidReniec']);
    $arr_usuario[0] = array(trim($_POST['txtNomUsuario']), trim($_POST['txtClave']));
  } else {
    $accion = 'E';
    $arr_persona[0] = array($_POST['txtIdPer'], trim($_POST['txtNroTelFijoPac']), trim($_POST['txtNroTelMovilPac']) , trim($_POST['txtEmailPac']));
    $arr_usuario[0] = array($_POST['txtIdUser'], trim($_POST['txtNomUsuario']));
  }
  $paramReg[0]['accion'] = $accion;
  $paramReg[0]['persona'] = to_pg_array($arr_persona);
  $paramReg[0]['usuario'] = to_pg_array($arr_usuario);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $u->post_reg_usuario($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el usuario";
    exit();
  }
  echo $rs;
  exit();
  break;
  /* =========================================================
   *  ACCESOS x USUARIO
   * ========================================================= */
  case 'GET_BUSCAR_PROFSERV':
    $rs = $ma->get_buscarProfServParaAcceso($_POST['busqueda']);
    $salida = array();
    foreach ($rs as $row) {
      $salida[] = array(
        'id_profesionalservicio' => $row['id_profesionalservicio'],
        'id_dependencia'         => $row['id_dependencia'],
        'nom_depen'              => $row['nom_depen'],
        'nrodoc'                 => $row['nrodoc'],
        'abrev_tipodoc'          => $row['abrev_tipodoc'],
        'nombre_completo'        => $row['nombre_completo'],
        'nom_usuario'            => $row['nom_usuario'],
        'nom_rol'                => $row['nom_rol'],
      );
    }
    echo json_encode($salida);
  break;
  case 'GET_ACCESOS_ASIGNADOS':
    $rs = $ma->get_accesosAsignados($_POST['id_profesionalservicio'], $_POST['id_menu']);
    echo json_encode($rs);
  break;
  case 'GET_ACCESOS_DISPONIBLES':
    $rs = $ma->get_accesosDisponibles($_POST['id_profesionalservicio'], $_POST['id_menu']);
    echo json_encode($rs);
  break;
  case 'POST_ADD_ACCESO':
    $rs = $ma->post_add_accesoUsuario($_POST['id_profesionalservicio'], $_POST['id_detmenu'], $labIdUser);
    echo $rs;
  break;
  case 'POST_DEL_ACCESO':
    $rs = $ma->post_del_accesoUsuario($_POST['id_profesionalservicio'], $_POST['id_detmenu'], $labIdUser);
    echo $rs;
  break;
  case 'POST_ADD_PWDUSUARIO':
  if(isset($_POST['id_usuario'])){
	$id_usuario = $_POST['id_usuario'];
  } else {
	$id_usuario = $labIdUser;  
  }
  
  $arr_persona[0] = array('');
  $arr_usuario[0] = array($id_usuario, $_POST['pass_usuario'], $labIdUser);
  $paramReg[0]['accion'] = 'EC';
  $paramReg[0]['persona'] = to_pg_array($arr_persona);
  $paramReg[0]['usuario'] = to_pg_array($arr_usuario);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $u->post_reg_usuario($paramReg);
  echo $rs;
  break;
}
?>
