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

require_once '../model/Grupo.php';
$g = new Grupo();

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
  case 'GET_SHOW_DATOGRUPO':
  $rs = $g->get_datoGrupoPorId($_POST['idGrupo']);
  echo json_encode($rs);
  exit();
  break;

  case 'POST_ADD_REGGRUPO':
  if($_POST['txtIdGrupo'] == "0"){
    $accion = 'C';
  } else {
    $accion = 'E';
  }
  $arr_area[0] = array($_POST['txtIdGrupo'], trim($_POST['txtDescGrupo']), $_POST['txtIdEstGrupo']);
  $paramReg[0]['accion'] = $accion;
  $paramReg[0]['grupo'] = to_pg_array($arr_area);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $g->post_reg_grupo($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el registro";
    exit();
  }
  echo "OK|".$rs;
  exit();
  break;
  case 'POST_ADD_REGGRUPOAREA':
  if($_POST['txtIdGrupoArea'] == "0"){
    $accion = 'C';
  } else {
    $accion = 'E';
  }
  $arr_area[0] = array($_POST['txtIdGrupoArea'], $_POST['txtIdArea'], $_POST['txtIdGrupo'], $_POST['txtIdVisiGruArea'], $_POST['txtIdEstGruArea']);
  $paramReg[0]['accion'] = $accion;
  $paramReg[0]['grupoarea'] = to_pg_array($arr_area);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $g->post_reg_grupoarea($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el usuario";
    exit();
  }
  echo "OK|".$rs;
  exit();
  break;
  case 'GET_REG_CAMBIOORDGRUPOAREA':
  $arr_area[0] = array($_POST['idGrupoArea'], $_POST['idArea']);
  $paramReg[0]['accion'] = $_POST['tipAcc'];
  $paramReg[0]['grupoarea'] = to_pg_array($arr_area);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $g->post_reg_grupoarea($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el usuario";
    exit();
  }
  echo "OK|".$rs;
  exit();
  break;
  case 'GET_SHOW_GRUPOPORIDAREA':
  $rs = $g->get_datosGrupoPorIdArea($_POST['idArea']);
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Orden</small></th>
          <th><small>Camb. Ord.</small></th>
          <th><small>Grupo</small></th>
          <th><small>Visible</small></th>
          <th><small>Estado</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {

            $btnEdit = '<button class="btn btn-success btn-xs" onclick="edit_registro(\'' . $row['id_grupoarea'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';

            if ($row['estado'] == "1") {
              $styleEst = "bg-green";
              $btnBajar = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden(\'B\',\'' . $row['id_grupoarea'] . '|' . $row['nro_grupoarea'] . '\');"><i class="glyphicon glyphicon-circle-arrow-up"></i></button>';
              $btnSubir = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden(\'S\',\'' . $row['id_grupoarea'] . '|' . $row['nro_grupoarea'] . '\');"><i class="glyphicon glyphicon-circle-arrow-down"></i></button>';
            } else {
              $styleEst = "bg-red";
              $btnBajar = '<button class="btn btn-primary btn-xs" disabled><i class="glyphicon glyphicon-circle-arrow-up"></i></button>';
              $btnSubir = '<button class="btn btn-primary btn-xs" disabled><i class="glyphicon glyphicon-circle-arrow-down"></i></button>';
            }
            $nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
            echo "<tr>";
            echo "<td class='text-center'><small><b>" . $row['nro_grupoarea'] . "</b></small></td>";
            echo "<td class='text-center'><small>" . $btnBajar . " " . $btnSubir . "</small></td>";
            echo "<td><small>" . $row['grupo'] . "</small></td>";
            echo "<td><small>" . $row['nom_visible'] . "</small></td>";
            echo "<td class='text-center'><small>" . $nomEstado . "</small></td>";
            echo "<td class='text-center'><small>" . $btnEdit . "</small></td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php

  break;
  case 'GET_SHOW_LISTAGRUPOPORIDAREA':
  $rs = $g->get_listaGrupoAreaPorIdArea($_POST['idArea']);
  echo json_encode($rs);
  break;
}
?>
