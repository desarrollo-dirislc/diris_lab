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

require_once '../model/Levey.php';
$l = new Levey();

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
  case 'POST_REGLEVEY':
    if($_POST['id_levey'] == 0){
		$accion = "IL";
	} else {
		$accion = "EL";
	}

	$arr_datos[0] = array($_POST['id_control_calidad'], $_POST['lote'], $_POST['ds'], $_POST['media'], $_POST['x_1ds_posi'], $_POST['x_2ds_posi'], $_POST['x_3ds_posi'], $_POST['x_1ds_nega'], $_POST['x_2ds_nega'], $_POST['x_3ds_nega']);
	$paramReg[0]['accion'] = $accion;
	$paramReg[0]['id'] = $_POST['id_levey'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $l->post_reg_levey($paramReg);
	echo $rs;
	exit();
  break;
  case 'POST_REGLEVEYDEPENDENCIA':
	$array_id_dep = explode(",", $_POST['id_dependencia']);
	$array_mes = explode(",", $_POST['mes']);
	$i = 0;
	foreach ($array_id_dep as $id_dep) {
		foreach ($array_mes as $mes) {
			$arr_datos[$i] = array($id_dep, $_POST['anio'], $mes, $_POST['id_control_calidad']);
			$i++;
		}
	}
	$paramReg[0]['accion'] = 'IEESS';
	$paramReg[0]['id'] = $_POST['id_levey'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $l->post_reg_levey($paramReg);
	echo $rs;
	exit();
  break;
  case 'POST_DELLEVEYDEPENDENCIA':
	$arr_datos[0] = array('');
	$paramReg[0]['accion'] = 'DEESS';
	$paramReg[0]['id'] = $_POST['id_levey_dep'];
	$paramReg[0]['datos'] = to_pg_array($arr_datos);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $l->post_reg_levey($paramReg);
	echo $rs;
	exit();
  break;
  case 'POST_REGLEVEYDET':
	$arr_area[0] = array($labIdDepUser, $_POST['id_control_calidad'], $_POST['fecha'], $_POST['valor'], '');
	$paramReg[0]['accion'] = 'ID';
	$paramReg[0]['id'] = $_POST['id_levey'];
	$paramReg[0]['datos'] = to_pg_array($arr_area);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $l->post_reg_levey($paramReg);
	echo $rs;
	exit();
  break;
  case 'POST_EDITLEVEYDET':
	$arr_area[0] = array($labIdDepUser, $_POST['id_control_calidad'], $_POST['fecha'], $_POST['valor'], $_POST['justificacion']);
	$paramReg[0]['accion'] = 'ED';
	$paramReg[0]['id'] = $_POST['id'];
	$paramReg[0]['datos'] = to_pg_array($arr_area);
	$paramReg[0]['userIngreso'] = $labIdUser;
	/*print_r($paramReg);
	exit();*/
	$rs = $l->post_reg_levey($paramReg);
	echo $rs;
	exit();
  break;
  case 'GRAFICACOLPATOLO':
	$result = ["rows" => [], "rowsvalor" => []];
	
	$item=0;
	for ($i=1; $i<=cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']); $i++){
		//$result["rows"][] = array($item => $i);
		array_push($result["rows"], $i);
		array_push($result["rowsvalor"], '');
		$item++;
	}
	//Aqui se manda los parametros de busqueda (Cabecera)
	$rsLe = $l->get_datosLeveyUltimoPorIdControlAndIdDepAndAnioAndMes($_POST['id_control_calidad'], $_POST['id_dependencia'], $_POST['anio'], $_POST['mes']);
	$cntLe = count($rsLe);
	if ($cntLe > 0) {
		//Aqui se manda los parametros de busqueda (Cabecera)
		$result["rowscabecera"] = $rsLe[0];
		//Aqui se manda los parametros de busqueda (Detalle)
		$sWhere=''; $sOrder=' Order by led.fecha'; $sLimit='';
		$param[0]['anio'] = $_POST['anio'];
		$param[0]['mes'] = $_POST['mes'];
		$param[0]['id_dependencia'] = $_POST['id_dependencia'];
		$param[0]['id_control_calidad'] = $_POST['id_control_calidad'];
		$srValor = $l->get_tblDatosLeveyDetalle($sWhere, $sOrder, $sLimit, $param);
		if ($srValor > 0) {
			foreach ($srValor as $row) {
				$result["rowsvalor"][$row['dia']-1] = $row['valor_fecha'];
			}
		}
	} else {
		$rsLe = $l->get_datosControlCalidadPorId($_POST['id_control_calidad']);
		$result["rowscabecera"] = array("nombre_tipo"=>$rsLe[0]['nombre_tipo'], "nombre_control"=>$rsLe[0]['nombre_control'], "media"=>50.00,"x_3ds_nega"=>10.00,"x_2ds_nega"=>25.00,"x_1ds_nega"=>40.00,"x_3ds_posi"=>90.00,"x_2ds_posi"=>75.00,"x_1ds_posi"=>60.00,"valor_min"=>0.00,"valor_max"=>100.00);
	}
	
	echo json_encode($result);
  break;
  case 'POST_CALCULA_COEFICIENTE_VARIACION':
	//Declarando valores iniciales
	$cnt_valores = 0;
	$suma_valores = 0;
	$media_total = 0;
	$xi_x = 0;
	$suma_xi_x2 = 0;
	$sqDS = 0;
	//Aquí cantidad de días del mes que selecciona
	$cnt_valores += cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']);
	//Aquí cantidad de registros
	$sWhere=''; $sOrder=' Order by led.fecha'; $sLimit='';
	$param[0]['anio'] = $_POST['anio'];
	$param[0]['mes'] = $_POST['mes'];
	$param[0]['id_dependencia'] = $_POST['id_dependencia'];
	$param[0]['id_control_calidad'] = $_POST['id_control_calidad'];
	$param[0]['id_estado_levey_dep'] = '1';
	$nums = $l->get_tblDatosLeveyDetalle($sWhere, $sOrder, $sLimit, $param);
	$cnt_valores += count($nums);//Número(cantidad) de datos incluye dias del mes y los valores ingresados 
	if (count($nums) > 0) {
		//Buscamos si en el mes hay un solo lote o más
		$rsLe = $l->get_datosLeveyTodosPorIdControlAndIdDepAndAnioAndMes($_POST['id_control_calidad'], $_POST['id_dependencia'], $_POST['anio'], $_POST['mes']);
		//print_r($rsLe);
		//sumamos todos los valores tanto la media del control y valores ingresados y finalmente calculamos la media
		if(count($rsLe) > 0){
			for ($i=1; $i<=cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']); $i++){
				$suma_valores+=$rsLe[0]['media'];
			}
		}
		foreach ($nums as $row1) {
			$suma_valores+=$row1['valor_fecha'];
		}
		
		$media_total = (float) ($suma_valores/$cnt_valores);//Aquí está la media
	
		//Defrente hayamos el total de la suma de todos $xi_x2 ya que incluye el $xi_x	
		if(count($rsLe) > 0){
			$media_lote = (float) $rsLe[0]['media'];
			//print_r($media_lote);			
			for ($i=1; $i<=cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']); $i++){
				$suma_xi_x2 +=($media_lote - $media_total) * ($media_lote - $media_total);
			}
		}
		foreach ($nums as $row) {
			$suma_xi_x2 +=($row['valor_fecha'] - $media_total)*($row['valor_fecha'] - $media_total);
		}
		$sqDS = (float) number_format(sqrt(($suma_xi_x2/($cnt_valores-1))), 7, '.', '');
		$cv = (float) number_format($sqDS/$media_total*100, 7, '.', '');
		$datos = array(
			0 => $media_total,
			1 => $sqDS,
			2 => $cv,
			3 => $cnt_valores
		);
	} else {
		$datos = array(
			0 => 0,
			1 => 0,
			2 => 0,
			3 => $cnt_valores
		);
	}
	
	//Anterior
	//print_r($nums);
	/*if (count($nums) > 1) {
		$sum=0;
		foreach ($nums as $row1) {
			$sum+=$row1['valor_fecha'];
		}
		$media = (float) number_format($sum/count($nums), 5, '.', '');
		$sum2=0;
		foreach ($nums as $row) {
			$sum2+=($row['valor_fecha']-$media)*($row['valor_fecha']-$media);
		}
		$vari = (float) number_format($sum2/(count($nums) - 1), 5, '.', '');
		$sqDS = (float) number_format(sqrt($vari), 7, '.', '');
		$cv = (float) number_format($sqDS/$media*100, 7, '.', '');
		$datos = array(
			0 => $media,
			1 => $sqDS,
			2 => $cv,
			3 => $cnt_valores
		);
	} else {
		 $datos = array(
			0 => 0,
			1 => 0,
			2 => 0,
			3 => $cnt_valores
		);
	}*/
	echo json_encode($datos);
  break;
  case 'GET_SHOW_DEPPORIDLEVEY':
	$rs = $l->get_datosDependenciaPorIdLevey($_POST['id_levey']);
	$nr = count($rs);
	?>
	<div class="table-responsive">
	<table class="table table-bordered table-hover">
	  <thead class="bg-aqua">
		<tr>
		  <th><small>Dependencia</small></th>
		  <th><small>Año</small></th>
		  <th><small>Mes</small></th>
		  <th><small>&nbsp;</small></th>
		</tr>
	  </thead>
	  <tbody>
		<?php
		if ($nr > 0) {
		  foreach ($rs as $row) {
			$btnEst = '<button class="btn btn-danger btn-xs" onclick="eliminar_levey_dep(\'' . $row['id'] . '\');"><i class="glyphicon glyphicon-remove"></i></button>';
			echo "<tr>";
			echo "<td><small>" . $row['nom_depen'] . "</small></td>";
			echo "<td><small>" . $row['anio'] . "</small></td>";
			echo "<td><small>" . $row['mes'] . "</small></td>";
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
  case 'GET_SHOW_LEVEYULTIMOPORIDCONTROLANDIDDEPANDANIOANDMES':
	$rsLe = $l->get_datosLeveyUltimoPorIdControlAndIdDepAndAnioAndMes($_POST['id_control_calidad'], $_POST['id_dependencia'], $_POST['anio'], $_POST['mes']);
	$cnt = count($rsLe);
	if($cnt > 0){
	?>
		<input type="hidden" name="txt_id_levey_<?php echo $_POST['id_control_calidad'];?>" id="txt_id_levey_<?php echo $_POST['id_control_calidad'];?>" value="<?php echo $rsLe[0]['id'];?>"/>
		<ul class="list-group list-group-unbordered">
			<li class="list-group-item">LOTE ACTUAL <span class="pull-right"><b><?php echo $rsLe[0]['nro_lote'];?></b></span></li>
			<!--<li class="list-group-item">DS <span class="pull-right"><?php echo $rsLe[0]['ds'];?></span></li>
			<li class="list-group-item">X+3DS <span class="pull-right text-red"><i class="fa fa-angle-up"></i> <?php echo $rsLe[0]['x_3ds_posi'];?></span></li>
			<li class="list-group-item">X+2DS <span class="pull-right text-yellow"><i class="fa fa-angle-up"></i> <?php echo $rsLe[0]['x_2ds_posi'];?></span></li>
			<li class="list-group-item">X+1DS <span class="pull-right text-green"><i class="fa fa-angle-up"></i> <?php echo $rsLe[0]['x_1ds_posi'];?></span></li>
			<li class="list-group-item">MEDIA <span class="pull-right"><i class="fa fa-angle-left"></i> <?php echo $rsLe[0]['media'];?></span></li>
			<li class="list-group-item">X-1DS <span class="pull-right text-green"><i class="fa fa-angle-down"></i> <?php echo $rsLe[0]['x_1ds_nega'];?></span></li>
			<li class="list-group-item">X-2DS <span class="pull-right text-yellow"><i class="fa fa-angle-down"></i> <?php echo $rsLe[0]['x_2ds_nega'];?></span></li>
			<li class="list-group-item">X-3DS <span class="pull-right text-red"><i class="fa fa-angle-down"></i> <?php echo $rsLe[0]['x_3ds_nega'];?></span></li>-->
		</ul>
	<?php
		//Declarando valores iniciales
		$cnt_valores = 0;
		$suma_valores = 0;
		$media_total = 0;
		$xi_x = 0;
		$suma_xi_x2 = 0;
		$sqDS = 0;
		$cv = 0;
		//Aquí cantidad de días del mes que selecciona
		$cnt_valores += cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']);
		//Aquí cantidad de registros
		$sWhere=''; $sOrder=' Order by led.fecha'; $sLimit='';
		$param[0]['anio'] = $_POST['anio'];
		$param[0]['mes'] = $_POST['mes'];
		$param[0]['id_dependencia'] = $_POST['id_dependencia'];
		$param[0]['id_control_calidad'] = $_POST['id_control_calidad'];
		$param[0]['id_estado_levey_dep'] = '1,3';
		$nums = $l->get_tblDatosLeveyDetalle($sWhere, $sOrder, $sLimit, $param);
		//print_r($nums);
		$cnt_valores += count($nums);//Número(cantidad) de datos incluye dias del mes y los valores ingresados 
		if (count($nums) > 0) {
			//Buscamos si en el mes hay un solo lote o más
			$rsLe = $l->get_datosLeveyTodosPorIdControlAndIdDepAndAnioAndMes($_POST['id_control_calidad'], $_POST['id_dependencia'], $_POST['anio'], $_POST['mes']);
			//print_r($rsLe);
			//sumamos todos los valores tanto la media del control y valores ingresados y finalmente calculamos la media
			if(count($rsLe) > 0){
				for ($i=1; $i<=cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']); $i++){
					$suma_valores+=$rsLe[0]['media'];
				}
			}
			foreach ($nums as $row1) {
				$suma_valores+=$row1['valor_fecha'];
			}
			
			$media_total = (float) ($suma_valores/$cnt_valores);//Aquí está la media
		
			//Defrente hayamos el total de la suma de todos $xi_x2 ya que incluye el $xi_x	
			if(count($rsLe) > 0){
				$media_lote = (float) $rsLe[0]['media'];
				//print_r($media_lote);			
				for ($i=1; $i<=cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']); $i++){
					$suma_xi_x2 +=($media_lote - $media_total) * ($media_lote - $media_total);
				}
			}
			foreach ($nums as $row) {
				$suma_xi_x2 +=($row['valor_fecha'] - $media_total)*($row['valor_fecha'] - $media_total);
			}
			$sqDS = (float) number_format(sqrt(($suma_xi_x2/($cnt_valores-1))), 7, '.', '');
			$cv = (float) number_format($sqDS/$media_total*100, 7, '.', '');
			$datos = array(
				0 => $media_total,
				1 => $sqDS,
				2 => $cv,
				3 => $cnt_valores
			);
		}
		?>
		<div class="row col-sm-12"><div class="col-sm-4 small bg-success p-xs"><span><b>CV MES:</b></span></div><div class="col-sm-8 p-xs"><?php echo $cv;?></div></div>
		<?php
		} else {
	?>
		<input type="hidden" name="txt_id_levey_<?php echo $_POST['id_control_calidad'];?>" id="txt_id_levey_<?php echo $_POST['id_control_calidad'];?>" value="0"/>
		<ul class="list-group list-group-unbordered">
			<li class="list-group-item">LOTE ACTUAL <span class="pull-right"><b>N/A</b></span></li>
			<!--<li class="list-group-item">DS <span class="pull-right">N/A</span></li>
			<li class="list-group-item">X+3DS <span class="pull-right text-red"><i class="fa fa-angle-up"></i>N/A</span></li>
			<li class="list-group-item">X+2DS <span class="pull-right text-yellow"><i class="fa fa-angle-up"></i>N/A</span></li>
			<li class="list-group-item">X+1DS <span class="pull-right text-green"><i class="fa fa-angle-up"></i>N/A</span></li>
			<li class="list-group-item">MEDIA <span class="pull-right"><i class="fa fa-angle-left"></i>N/A</span></li>
			<li class="list-group-item">X-1DS <span class="pull-right text-green"><i class="fa fa-angle-down"></i>N/A</span></li>
			<li class="list-group-item">X-2DS <span class="pull-right text-yellow"><i class="fa fa-angle-down"></i>N/A</span></li>
			<li class="list-group-item">X-3DS <span class="pull-right text-red"><i class="fa fa-angle-down"></i>N/A</span></li>-->
		</ul>
		<div class="row col-sm-12"><div class="col-sm-4 small bg-success p-xs"><span><b>CV MES:</b></span></div><div class="col-sm-8 p-xs"><?php echo 0;?></div></div>
	<?php
		}
  break;
  case 'GET_SHOW_DETALLELEVEYPORID':
	$rsLe = $l->get_datosListaDetalleLeveyPorId($_POST['id']);
	if (count($rsLe) == 1) {
		?>
		<ul class="list-group list-group-unbordered">
			<li class="list-group-item" style="padding: 2px 2px">FECHA REG. <span class="pull-right"><b><?php echo $rsLe[0]['fec_registro'];?></b></span></li>
			<li class="list-group-item" style="padding: 2px 2px">USUARIO <span class="pull-right"><b><?php echo $rsLe[0]['nom_usuregistro'];?></b></span></li>
		</ul>
		
		<?php
	} else {
		$item = 0;
		foreach ($rsLe as $row) {
		?>
			
		<?php

		?>
		<ul class="list-group list-group-unbordered" style="margin-bottom: 2px;">
			<li class="list-group-item" style="padding: 2px 2px">FECHA REG. <?php if($row['id_estado']== "3"){ echo " ANTERIOR";}?> <span class="pull-right"><b><?php echo $row['fec_registro'];?></b></span></li>
			<li class="list-group-item" style="padding: 2px 2px">USUARIO REG.  <?php if($row['id_estado']== "3"){ echo " ANTERIOR";}?> <span class="pull-right"><b><?php echo $row['nom_usuregistro'];?></b></span></li>
			<li class="list-group-item" style="padding: 2px 2px">VALOR <?php if($row['id_estado']== "3"){ echo " ANTERIOR";} else { echo " ACTUAL";}?> <span class="pull-right"><b><?php echo $row['valor_fecha'];?></b></span></li>
		</ul>
		<?php 
			if($item <> 0){
						?>
		<p style="padding: 2px 2px"><span><b>Justificación:</b><br/></span><?php echo $row['justificacion'];?></p>
		<?php
				}
				echo "<br/>";
		$item ++;
		}
	}
  break;
}
?>
