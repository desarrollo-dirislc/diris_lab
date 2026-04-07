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

require_once '../model/Producto.php';
$pr = new Producto();
require_once '../model/Producton.php';
$p = new Producton();


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
 case 'GET_SHOW_DETCOMPONENTEPRODGRUPO':
	$rs = $p->get_datosComponenteGrupoProdPorId($_POST['id']);
	$nr = count($rs);
	if ($nr > 0) {
		if($rs[0]['chk_muestra_metodo'] == "t"){
			$id_visible = "1";
		} else {
			$id_visible = "0";
		}
		if($rs[0]['chk_muestra_comp_vacio'] == "t"){
			$id_muestra_comp = "1";
		} else {
			$id_muestra_comp = "0";
		}
		$datos = array(
		  0 => $rs[0]['id'],
		  1 => $rs[0]['id_componente'],
		  2 => trim($rs[0]['componente']),
		  3 => trim($rs[0]['id_unimedida']),
		  4 => trim($rs[0]['uni_medida']),
		  5 => trim($rs[0]['idtipo_ingresol']),
		  6 => trim($rs[0]['ing_solu']),
		  7 => trim($rs[0]['id_metodocomponente']),
		  8 => trim($rs[0]['metodocomponente']),
		  9 => $id_visible,
		  10 => trim($rs[0]['nom_visible']),
		  11 => $rs[0]['estado'],
		  12 => $rs[0]['nom_estado'],
		  13 => $id_muestra_comp,
		  14 => trim($rs[0]['muestra_comp_vacio']),
		);
		echo json_encode($datos);
	} else {
		$datos = array(
			0 => '0'
		);
	}
 break;
 case 'GET_SHOW_GRUPOPORIDPRODUCTO':
  $rs = $p->get_datosGrupoPorIdProducto($_POST['id_producto'], 0);
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Orden</small></th>
		  <th><small>&nbsp;</small></th>
          <th><small>Grupo</small></th>
          <th><small>Visible</small></th>
          <th><small>Cant.<br/>Comp.</small></th>
		  <th><small>Estado</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {
			if ($row['estado'] == "1") {
				$styleEst = "bg-green";
				$btnEdit = '<button class="btn btn-success btn-xs" onclick="edit_grupo_visible(\'' . $row['id'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
				$btnOpemComp = '<button class="btn btn-success btn-xs" onclick="open_compdet(\'' . $row['id'] . '\',\'' . $row['descripcion_grupo'] . '\');"><i class="glyphicon glyphicon-list-alt"></i></button>';
				$btnBajar = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden_grupo(\'BG\',\''.$row['id'].'\',\'' . $row['orden_grupo'] . '\');"><i class="glyphicon glyphicon-circle-arrow-up"></i></button>';
				$btnSubir = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden_grupo(\'SG\',\''.$row['id'].'\',\'' . $row['orden_grupo'] . '\');"><i class="glyphicon glyphicon-circle-arrow-down"></i></button>';
				$btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_grupo(\'' . $row['id'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
			} else {
                $styleEst = "bg-red";
				$btnEdit = '';
				$btnOpemComp = '';
				$btnBajar = '';
				$btnSubir = '';
				$btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_grupo(\'' . $row['id'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-ok"></i></button>';
			}
			$nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
			echo "<tr>";
            echo "<td class='text-center'><small><b>" . $row['orden_grupo'] . "</b></small></td>";
			echo "<td class='text-center'><small>" . $btnBajar . " " . $btnSubir . "</small></td>";
            echo "<td><small>" . $row['descripcion_grupo'] . "</small></td>";
            echo "<td><small>" . $row['nom_visible'] . "</small></td>";
            echo "<td class='text-center'><small><b>" . $row['cnt_comp'] . "</b></small></td>";
			echo "<td class='text-center'><small>" . $nomEstado . "</small></td>";
            echo "<td class='text-center'><small>" . $btnEdit . $btnOpemComp . $btnEst . "</small></td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php
  break;
  case 'GET_SHOW_COMPONENTEPORIDPRODGRUPO':
  $rs = $p->get_datosComponentePorIdGrupoProd($_POST['id_productogrupo'], 0, isset($_POST['id_metodo']) ? $_POST['id_metodo'] : '');
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Orden</small></th>
          <th><small>&nbsp;</small></th>
          <th><small>Componente</small></th>
          <th><small>Unidad<br/>medida</small></th>
          <th><small>Método<br/>Componente</small></small></th>
		  <th><small>Muestra<br/>Componente<br/>si no Ingresa dato</small></small></th>
		  <th><small>Muestra<br/>Método</small></th>
		  <th><small>Cantidad<br/>Dep.</small></th>
          <th><small>Estado</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {
            if ($row['estado'] == "1") {
			  $nom_componente = str_replace("\"","",$row['componente']);
              $styleEst = "bg-green";
              $btnEdit = '<button class="btn btn-success btn-xs" onclick="edit_componente(\'' . $row['id'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
			  $btnDep = '<button class="btn btn-primary btn-xs" onclick="open_dependencia_componente(\'' . $row['id'] . '\',\'' . $nom_componente . '\');"><i class="fa fa-hospital-o"></i></button>';
              $btnBajar = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden_componente(\'BC\',\''.$row['id'].'\',\'' . $row['orden_componente'] . '\');"><i class="glyphicon glyphicon-circle-arrow-up"></i></button>';
              $btnSubir = '<button class="btn btn-primary btn-xs" onclick="cambiar_orden_componente(\'SC\',\''.$row['id'].'\',\'' . $row['orden_componente'] . '\');"><i class="glyphicon glyphicon-circle-arrow-down"></i></button>';
			  $btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_comp(\'' . $row['id'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
			  $btnCambioGrupo = '<button class="btn btn-warning btn-xs" onclick="cambio_otro_grupo(\'' . $row['id'] . '\',\'' . $nom_componente . '\');"><i class="glyphicon glyphicon-share-alt"></i></button>';
            } else {
              $styleEst = "bg-red";
			  $btnEdit = '';
			  $btnDep = '';
              $btnBajar = '';
              $btnSubir = '';
			  $btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_comp(\'' . $row['id'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-ok"></i></button>';
			  $btnCambioGrupo = '';
            }
            $nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
            echo "<tr>";
            echo "<td class='text-center'><small><b>" . $row['orden_componente'] . "</b></small></td>";
            echo "<td class='text-center'><small>" . $btnBajar . " " . $btnSubir . "</small></td>";
            echo "<td><small>" . $row['componente'] . "</small></td>";
            echo "<td><small>" . $row['uni_medida'] . "</small></td>";
            echo "<td><small>" . $row['metodocomponente'] . "</small></td>";
			echo "<td><small>" . $row['muestra_comp_vacio'] . "</small></td>";
			echo "<td><small>" . $row['nom_visible'] . "</small></td>";
			echo "<td class='text-center'><small>" . $row['cnt_dependencia'] . "</small></td>";
            echo "<td class='text-center'><small>" . $nomEstado . "</small></td>";
            echo "<td class='text-center'><small>" . $btnEdit . $btnDep . $btnCambioGrupo . $btnEst ."</small></td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php
  break;
  case 'POST_CRUD_GRUPO_COMPONENTE_ORDCOMP':
	  $arr_datos[0] = array($_POST['orden'], $_POST['id_productogrupo']);
	  $paramReg[0]['accion'] = $_POST['accion_sp'];
	  $paramReg[0]['id'] = $_POST['id'];
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO':
	  $arr_datos[0] = array($_POST['descripcion_grupo']);
	  $paramReg[0]['accion'] = 'IG';
	  $paramReg[0]['id'] = 0;
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_ORDEN':
	  $arr_datos[0] = array($_POST['orden'], $_POST['id_producto']);
	  $paramReg[0]['accion'] = $_POST['accion_sp'];
	  $paramReg[0]['id'] = $_POST['id'];
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_PRODUCTO':
	  if($_POST['id_productogrupo'] == "0"){
		 $accion_sp = "IGP";
	  } else {
		 $accion_sp = "AGP"; 
	  }
	  $arr_datos[0] = array($_POST['id_producto'], $_POST['id_grupo'], $_POST['chk_muestra_grupo']);
	  $paramReg[0]['accion'] = $accion_sp;
	  $paramReg[0]['id'] = $_POST['id_productogrupo'];
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_PRODUCTO_ESTADO':
	  $arr_datos[0] = array($_POST['id_producto'], $_POST['id_estado']);
	  $paramReg[0]['accion'] = $_POST['accion_sp'];
	  $paramReg[0]['id'] = $_POST['id_productogrupo'];
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_PRODUCTO_OTRO':
	  $arr_datos[0] = array($_POST['id_productogrupootro'], $_POST['id_componenteprodgrupo']);
	  $paramReg[0]['accion'] = "CCGP";
	  $paramReg[0]['id'] = 0;
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_COMPONENTE':
	  if($_POST['id_componenteprodgrupo'] == "0"){
		 $accion_sp = "ICGP";
		 $id = $_POST['id_productogrupo'];
	  } else {
		 $accion_sp = "ACGP";
		 $id = $_POST['id_componenteprodgrupo'];
	  }
	  $arr_datos[0] = array($_POST['id_componente'], $_POST['id_metodocomponente'], $_POST['chk_muestra_metodo'], $_POST['chk_muestra_comp_vacio']);
	  $paramReg[0]['accion'] = $accion_sp;
	  $paramReg[0]['id'] = $id;
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_COMPONENTE_ESTADO':
	  $arr_datos[0] = array($_POST['id_productogrupo'], $_POST['id_estado']);
	  $paramReg[0]['accion'] = $_POST['accion_sp'];
	  $paramReg[0]['id'] = $_POST['id_componenteprodgrupo'];
	  $paramReg[0]['datos'] = to_pg_array($arr_datos);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $p->reg_grupo_componente($paramReg);
	  echo $rs;
  break;
  case 'POST_CRUD_GRUPO_COMPONENTE_DEPEN':
	if($_POST['accion_sp'] == "ICDGP"){
		$array_id_dep = explode(",", $_POST['id_dependencia']);
		$i = 0;
		foreach ($array_id_dep as $id_dep) {
			$arr_datos[$i] = array($_POST['id_productogrupocomp'], $id_dep);
			$i++;
		}
		$id = 0;
	} else {
		$arr_datos[0] = array('');
		$id = $_POST['id_productogrupocompdep'];
	}
	$paramReg[0]['accion'] = $_POST['accion_sp'];
	$paramReg[0]['id'] = $id;
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $p->reg_grupo_componente($paramReg);
	echo $rs;
  break;
  case 'GET_SHOW_LISTAGRUPO':
	  $rs = $p->get_listaGrupoActivo();
	  echo json_encode($rs);
  break;
  case 'GET_SHOW_LISTAGRUPOOTRO':
	  $rs = $p->get_listaGrupoCambioOtroActivo($_POST['id_producto'], $_POST['id_productogrupo']);
	  echo json_encode($rs);
  break;
  case 'GET_SHOW_DEPENDENCIAPORIDCOMPONENTEGRUPO':
  $rs = $p->get_datosDependenciaPorIdProductoGrupoComp($_POST['id_productogrupocomp']);
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Dependencia</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {
            $btnEst = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_dep_comp(\'' . $row['id'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
            echo "<tr>";
            echo "<td><small>" . $row['dependencia'] . "</small></td>";
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





  
  
  
  
  
  
  
  
  
	
	
	
	
  case 'GET_SHOW_DETCOMPONENTE':
  $rs = $c->get_datosComponentePorId($_POST['idComp']);
  $nr = count($rs);
  if ($nr > 0) {
    $datos = array(
      0 => $rs[0]['id_componente'],
      1 => $rs[0]['descrip_comp'],
      2 => trim($rs[0]['id_unimedida']),
      3 => trim($rs[0]['uni_medida']),
      4 => trim($rs[0]['descrip_valor']),
      5 => $rs[0]['idtipo_ingresol'],
      6 => $rs[0]['ing_solu'],
      7 => trim($rs[0]['idtipocaracter_ingresul']),
      8 => $rs[0]['nomtipocaracter_ingresul'],
      9 => trim($rs[0]['detcaracter_ingresul']),
	  10 => $rs[0]['idseleccion_ingresul'],
	  11 => $rs[0]['nombre_selecresultado'],
      12 => $rs[0]['id_estado'],
      13 => $rs[0]['nom_estado'],
    );
    echo json_encode($datos);
  } else {
    $datos = array(
      0 => '0'
    );
  }
  break;
  case 'POST_ADD_REGCOMPONENTE':
  if($_POST['txtIdComp'] == "0"){
    $accion = 'C';
  } else {
    $accion = 'E';
  }
  $arr_area[0] = array($_POST['txtIdComp'], $_POST['txtNomComp'], trim($_POST['txtIdUnidMed']), trim($_POST['txtValRefComp']), trim($_POST['txtIngSoluComp']), $_POST['optTipoCaracResult'], trim($_POST['txtDetCaracResul']), $_POST['txtIngSeleccion']);
  $paramReg[0]['accion'] = $accion;
  $paramReg[0]['componente'] = to_pg_array($arr_area);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $c->post_reg_componente($paramReg);
  echo $rs;
  exit();
  break;
  case 'POST_ADD_REGCOMPONENTEDET':
  $arr_comp[0] = array($_POST['txtIdGrupoArea'], $_POST['txtIdComp']);
  $paramReg[0]['accion'] = 'C';
  $paramReg[0]['componentedet'] = to_pg_array($arr_comp);
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $c->post_reg_componentedet($paramReg);
  echo $rs;
  exit();
  break;

 

  case 'POST_ADD_REGCOMPPORCPT':
  if($_POST['txtTipIng'] == "C"){
    $action = 'C';
  } else {
    if($_POST['txtIdCpt'] == "1"){
      $action = 'I';
    } else {
      $action = 'A';
    }
  }

  $paramReg[0]['accion'] = $action;
  $paramReg[0]['detcompcpt'] = $_POST['txtIdCpt'] . "|" . $_POST['txtIdCompDet'];
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $c->post_reg_componentedetcpt($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el usuario";
    exit();
  }
  echo "OK|".$rs;
  exit();
  break;
  case 'GET_SHOW_COMPPORIDCPT':
  $rs = $c->get_datosComponentePorIdCpt($_POST['idCpt']);
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Componente</small></th>
          <th><small>Grupo</small></th>
          <th><small>Area</small></th>
          <th><small>Estado</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {

            if ($row['estado'] == "1") {
              $btnEdit = '<button class="btn btn-danger btn-xs" onclick="cambio_estado(\'' . $row['id_componentedetcpt'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
              $styleEst = "bg-green";
            } else {
              $btnEdit = '<button class="btn btn-success btn-xs" onclick="cambio_estado(\'' . $row['id_componentedetcpt'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-ok"></i></button>';
              $styleEst = "bg-red";
            }
            $nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
            echo "<tr>";
            echo "<td><small>" . $row['componente'] . "</small></td>";
            echo "<td><small>" . $row['grupo'] . "</small></td>";
            echo "<td><small>" . $row['area'] . "</small></td>";
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
  case 'GET_SHOW_LISTACOMPDETPORIDGRUPOAREA':
  $rs = $c->get_listaCompDetPorIdGrupoArea($_POST['txtIdGrupoArea']);
  echo json_encode($rs);
  break;
  case 'GET_SHOW_COMPPORIDPRODUCTO':
  $rs = $c->get_datosComponentePorIdProducto($_POST['idProd']);
  $nr = count($rs);
  ?>
  <!--<div class="table-responsive">-->
    <div class="panel panel-default" id="parent" style="overflow: auto;">
    <table id="fixTable" class="table table-bordered table-hover">
      <thead>
        <tr>
		  <th>Producto<br/>Relacionado</th>
          <th>Componente</th>
          <th>Grupo</th>
          <th>Area</th>
          <th>Estado</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {

            if ($row['estado'] == "1") {
              $btnEdit = '<button class="btn btn-danger btn-xs" onclick="cambio_estado(\'' . $row['id_componentedetprod'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
              $styleEst = "bg-green";
            } else {
              $btnEdit = '<button class="btn btn-success btn-xs" onclick="cambio_estado(\'' . $row['id_componentedetprod'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-ok"></i></button>';
              $styleEst = "bg-red";
            }
            $nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
            echo "<tr>";
			echo "<td><small>" . $row['nom_productoori'] . "</small></td>";
            echo "<td><small><b>" . $row['componente'] . "</b></small></td>";
            echo "<td><small>" . $row['grupo'] . "</small></td>";
            echo "<td><small>" . $row['area'] . "</small></td>";
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
  case 'POST_ADD_REGCOMPPORPRODUCTO':
  $txtIdPerfil = "";
  if($_POST['txtTipIng'] == "CC"){
    $action = 'CC';
	$txtIdPerfil = $_POST['txtIdPerfil'];
  } else if($_POST['txtTipIng'] == "CP"){
    $action = 'CP';
	$txtIdPerfil = $_POST['txtIdPerfil'];
  } else {
    if($_POST['txtIdProd'] == "1"){
      $action = 'I';
    } else {
      $action = 'A';
    }
  }

  $paramReg[0]['accion'] = $action;
  $paramReg[0]['detcompprod'] = $_POST['txtIdProd'] . "|" . $_POST['txtIdCompDet'] . "|" . $txtIdPerfil;
  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
  /*print_r($paramReg);
  exit();*/
  $rs = $c->post_reg_componentedetproducto($paramReg);
  if ($rs == "E") {
    echo "ER|Error al ingresar el registro";
    exit();
  }
  echo "OK|".$rs;
  exit();
  break;
  case 'POST_ADD_REGCOMPVALREFERENCIAL':
  if($_POST['txtTipIng'] == "ELI"){
	  $arr_area[0] = array($_POST['txtIdValComp']);
	  $paramReg[0]['accion'] ="ELI";
	  $paramReg[0]['valreferencial'] = to_pg_array($arr_area);
	  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $c->post_reg_componentevalref($paramReg);
	  echo $rs;
	  exit();
  } else {
	  if($_POST['txtIdValComp'] == "0"){
		$accion = 'C';
	  } else {
		$accion = 'E';
	  }
	  $arr_area[0] = array($_POST['txtIdComp'], $_POST['txtIdValComp'], $_POST['txtIdSexo'], trim($_POST['txtDiaMin']), trim($_POST['txtMesMin']), $_POST['txtAnioMin'], trim($_POST['txtDiaMax']), trim($_POST['txtMesMax']), trim($_POST['txtAnioMax']), trim($_POST['txtLimInf']), trim($_POST['txtLimSup']), trim($_POST['txtDescrip']));
	  $paramReg[0]['accion'] = $accion;
	  $paramReg[0]['valreferencial'] = to_pg_array($arr_area);
	  $paramReg[0]['userIngreso'] = $labIdUser . "|" . $labIdDepUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $c->post_reg_componentevalref($paramReg);
	  echo $rs;
	  exit();
  }
  break;
  case 'GET_SHOW_COMPVALORREFPORIDCOMP':
  $rs = $c->get_datosValorReferencialPorIdComp($_POST['idComp']);
  $nr = count($rs);
  ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th><small>Sexo</small></th>
          <th><small>Edad Mínima</small></th>
          <th><small>Edad Máxima</small></th>
          <th><small>Lim. Inf.</small></th>
          <th><small>Lim. Sup.</small></th>
          <th><small>Descripción</small></th>
          <th><small>Estado</small></th>
          <th><small>&nbsp;</small></th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {

            if ($row['estado'] == "1") {
              $btnEdit = '<button class="btn btn-danger btn-xs" onclick="cambio_estado_valorref(\'' . $row['id_compvalref'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
              $styleEst = "bg-green";
            } else {
              $btnEdit = '<button class="btn btn-success btn-xs" onclick="cambio_estado_valorref(\'' . $row['id_compvalref'] . '\',\'' . $row['estado'] . '\');"><i class="glyphicon glyphicon-ok"></i></button>';
              $styleEst = "bg-red";
            }
            $nomEstado = '<span class="badge ' . $styleEst . '">' . $row['nom_estado'] . '</span>';
            echo "<tr>";
            echo "<td class='text-center'><small>" . $row['id_sexo'] . "</small></td>";
            echo "<td><small>" . $row['edadanio_min'] . " años " . $row['edadmes_min'] . " meses " . $row['edaddia_min'] . " dias</small></td>";
            echo "<td><small>" . $row['edadanio_max'] . " años " . $row['edadmes_max'] . " meses " . $row['edaddia_max'] . " dias</small></td>";
            echo "<td><small>" . $row['lim_inf'] . "</small></td>";
            echo "<td><small>" . $row['lim_sup'] . "</small></td>";
            echo "<td><small>" . nl2br($row['descip_valref']) . "</small></td>";
            echo "<td class='text-center'><small><small>" . $nomEstado . "</small></small></td>";
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
  case 'GET_SHOW_DETSELECCIONRESULTADOPORIDSELECCION':
	  $rs = $c->get_listaDetSeleccionResultadoPorIdSeleccion($_POST['id_comp_seleccion']);
	  echo json_encode($rs);
  break;
  case 'GET_SHOW_LISTATIPOSELECCIONRESULTADO':
	  $rs = $c->get_listaTipoSeleccionResultado();
	  echo json_encode($rs);
  break;
  case 'POST_ADD_REGSELECCIONRESULTADO':
	  $arr_selecresul[0] = array(trim($_POST['abreviatura']), trim($_POST['nombre']));
	  $paramReg[0]['accion'] = $_POST['accion_proc'];
	  $paramReg[0]['id'] = $_POST['id'];
	  $paramReg[0]['datos'] = to_pg_array($arr_selecresul);
	  $paramReg[0]['userIngreso'] = $labIdUser;
	  /*print_r($paramReg);
	  exit();*/
	  $rs = $c->post_reg_componente_seleccionresul($paramReg);
	  echo $rs;
	  exit();
  break;
  case 'GET_SHOW_COMPPORIDPRODUCTOANDIDDEPENDENCIA':
  if(isset($_POST['idDep'])){
	  $idDep = $_POST['idDep'];
  } else {
	  $idDep = Null;
  }
  $rs = $p->get_datosComponentePoridProductoAndIdDependenciaActivo($_POST['idProd'], $idDep);
  $nr = count($rs);
  ?>
  <!--<div class="table-responsive">-->
    <div class="panel panel-default" id="parent" style="overflow: auto;">
    <table id="fixTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>COMPONENTE</th>
          <th>UNIDAD MEDIDA</th>
          <th>MÉTODO</th>
          <th>GRUPO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($nr > 0) {
          foreach ($rs as $row) {
            echo "<tr>";
            echo "<td><small><b>" . $row['componente'] . "</b></small></td>";
            echo "<td><small>" . $row['uni_medida'] . "</small></td>";
            echo "<td><small>" . $row['metodocomponente'] . "</small></td>";
			echo "<td><small>" . $row['descripcion_grupo'] . "</small></td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php
  break;
  case 'GET_SHOW_DETPRODUCTOPORIDPRO':
    $rs = $pr->get_datosProductoPorIdPro($_POST['txtIdPro']);
    $cnt = count($rs);
	
	$table = '<br/><table class="table table-striped table-bordered table-hover">
                  <thead class="bg-aqua">
                    <tr>
                      <th>Exámen(es)</th>
                    </tr>
                  </thead>
				  <tbody>';
	$rsC = $p->get_datosComponentePoridProductoAndIdDependenciaActivo($_POST['txtIdPro'], $labIdDepUser);
	foreach ($rsC as $row) {
		$table .= "<tr><td><small>" . $row['componente'] . "</small></td></tr>";
	}
    $table .= '<tr>
              <td></td>
              </tr>
              </tbody>
            </table>';
	
    $datos = array(
      0 => $cnt,
      1 => $rs,
	  2 => $table
    );
    echo json_encode($datos);
  break;
}
?>
