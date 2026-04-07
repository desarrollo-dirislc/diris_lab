<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Producto.php';
$pr = new Producto();
require_once '../../model/Ses.php';
$ses = new Ses();
require_once '../../model/Dependencia.php';
$d = new Dependencia();
require_once '../../model/Atencion.php';
$at = new Atencion();
require_once '../../model/Componente.php';
$c = new Componente();

$id = $_GET['id'];
$rsA = $ses->get_datosCabeceraInformeSESLab($id);
//print_r($rsA);

?>
<style>

.table > tbody > tr.info > td, .table > tbody > tr.info > th, .table > tbody > tr > td.info, .table > tbody > tr > th.info, .table > tfoot > tr.info > td, .table > tfoot > tr.info > th, .table > tfoot > tr > td.info, .table > tfoot > tr > th.info, .table > thead > tr.info > td, .table > thead > tr.info > th, .table > thead > tr > td.info, .table > thead > tr > th.info {
  background-color: #cde7f5;
}

.panel .panel-body {
  background: #fff;
  border: 1px solid #e7eaec;
    border-top-color: rgb(231, 234, 236);
    border-top-style: solid;
    border-top-width: 1px;
  border-radius: 2px;
  position: relative;
  padding: 5px;
}

.panel-heading-tab {
  padding: 2px 5px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}
</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>EDICION DE INFORME MENSUAL DE EXAMENES DE LABORATORIO</strong></h3>
    </div>
    <div class="panel-body">
	<input type="hidden" name="txt_informe" id="txt_informe" value="<?php echo $id;?>"/>
	<div class="row">
        <div class="col-sm-3">
          <div class="box box-success">
            <br/>
				<div class="box-body box-profile">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="txt_anio">Año:</label>
						<select name="txt_anio" id="txt_anio" class="form-control" disabled>
						<?php
							$year_init = 2022;
							$year_curent = date('Y');
							for ($i = $year_init; $i <= $year_curent; $i++) {
								echo "<option value='".$i."'"; if($rsA[0]['anio_informe'] == $i){ echo " selected";}  echo ">".$i."</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="txt_mes">Mes:</label>
						<select name="txt_mes" id="txt_mes" class="form-control" disabled>
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='".$i."'"; if($rsA[0]['mes_informe'] == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_id_dependencia">Dependencia:</label>
						<?php $rsD = $d->get_listaDepenInstitucion(); ?>
						<select name="txt_id_dependencia" id="txt_id_dependencia" class="form-control" disabled>
						  <?php
						  foreach ($rsD as $row) {
							echo "<option value='" . $row['id_dependencia'] . "'";
							if ($row['id_dependencia'] == $rsA[0]['id_establecimiento']) echo " selected";
							echo ">" . $row['nom_depen'] . "</option>";
						  }
						  ?>
						</select>
					</div>
				</div>
				</div>
			</div>
		</div>
        <div class="col-sm-9">
	  <div class="box box-primary">
		  <div class="box-body box-profile">
		  <div class="panel with-nav-tabs">
				<div class="panel-heading-tab">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab_p1" data-toggle="tab" aria-expanded="true"><b>Examenes de laboratorio</b></a></li>
							<li class=""><a href="#tab_p2" data-toggle="tab" aria-expanded="true"><b>Informe Bacteriologico</b></a></li>
						</ul>
				</div>		
				<div class="panel-body">				
						<div class="tab-content">
						<div class="tab-pane active" id="tab_p1">
						<form name="frmArea" id="frmArea">
	  <div class="box box-primary">
		  <div class="box-body box-profile">
				<div class="panel with-nav-tabs panel-primary">
					<div class="panel-heading">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">ATENCIONES</a></li>
								<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">HEMATOLOGICOS</a></li>
								<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">BIOQUIMICOS</a></li>
								<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">INMUNOLOGICOS</a></li>
								<li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">MICROBIOLOGICOS</a></li>
								<li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">PERFIL / PAQUETE</a></li>
							</ul>
					</div>
					<div class="panel-body">
					<?php 
					/////////// Datos iniciales
					$hem_aus = (int)0;
					$hem_pag = (int)0; 
					$hem_esa = (int)0;
					$hem_exo = (int)0;
					?>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">ATENCIONES</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$cnt_total=0;
									$tot_sis = 0;
									$tot_demanda = 0;
									$tot_estrategia = 0;
									$tot_exonerado = 0;
									$rsTotales = $ses->get_datosDetalleInformeSESLabAtencion($id); //$id
									//print_r($rsTotales);
									if(count($rsTotales) == 0){
										$param[0]['anio'] = $rsA[0]['anio_informe'];
										$param[0]['mes'] = $rsA[0]['mes_informe'];
										$param[0]['idDepAten'] = $rsA[0]['id_establecimiento'];
										$rsTotales = $at->get_repCntAtencionPorAnioMesAndIdDependencia($param);
										foreach ($rsTotales as $rowTotArea) {
										  if($rowTotArea['id_plan'] == "1"){
											  $tot_sis = $rowTotArea['cnt_atencion'] ;
										  }
										  if($rowTotArea['id_plan'] == "2"){
											  $tot_demanda = $rowTotArea['cnt_atencion'] ;
										  }
										  if($rowTotArea['id_plan'] == "3"){
											  $tot_estrategia = $rowTotArea['cnt_atencion'] ;
										  }
										  if($rowTotArea['id_plan'] == "4"){
											  $tot_exonerado = $rowTotArea['cnt_atencion'] ;
										  }
										  $cnt_total= $rowTotArea['cnt_atencion'] + $cnt_total;
										}
									} else {
										foreach ($rsTotales as $rowTotArea) {
											$tot_sis = $rowTotArea['cnt_sis'];
											$tot_demanda = $rowTotArea['cnt_pagante'];
											$tot_estrategia = $rowTotArea['cnt_estrategia'];
											$tot_exonerado = $rowTotArea['cnt_exonerado'];
											$cnt_total= $rsA[0]['cnt_total_ate_lab'];
										}
									}
								?>
									<tr>
										<td>ATENCIONES</td>
										<td><input type="number" name="txt_ate_aus" id="txt_ate_aus" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_ate();" value="<?php echo $tot_sis;?>"></td>
										<td><input type="number" name="txt_ate_pag" id="txt_ate_pag" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_ate();" value="<?php echo $tot_demanda;?>"></td>
										<td><input type="number" name="txt_ate_esa" id="txt_ate_esa" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_ate();" value="<?php echo $tot_estrategia;?>"></td>
										<td><input type="number" name="txt_ate_exo" id="txt_ate_exo" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_ate();" value="<?php echo $tot_exonerado;?>"></td>
										<td><input type="number" name="txt_ate_tot" id="txt_ate_tot" class="form-control" maxlength="5" value="<?php echo $cnt_total;?>" disabled=""></td>
									</tr>
								</tbody>
							</table>
							</div>
							<div class="tab-pane" id="tab_2">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">EXAMENES HEMATOLOGICOS</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_hem_aus_tot" id="txt_hem_aus_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_hem_pag_tot" id="txt_hem_pag_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_hem_esa_tot" id="txt_hem_esa_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_hem_exo_tot" id="txt_hem_exo_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_hem_tot_tot" id="txt_hem_tot_tot" class="form-control" value="0" disabled=""></td>
									</tr>
								<?php
									$item_hem = (int)0;
									$rs = $ses->get_datosDetalleInformeSESLabExamen($id, 1);
									$nr = count($rs);
									if ($nr > 0) {
									  foreach ($rs as $row) {
											$item_hem ++;
											if($item_hem%2==0){
												$trPColor = "";
											} else {
												$trPColor = "info";
											}
											$hem_aus = $row['cnt_sis'];
											$hem_pag = $row['cnt_pagante'];
											$hem_esa = $row['cnt_estrategia'];
											$hem_exo = $row['cnt_exonerado'];
										  
										?>
										<tr class="<?php echo $trPColor; ?>">
											<td><?php echo $item_hem . "- " . $row['nom_producto'];?></td>
											<td>
												<input type="hidden" name="txt_hem_id_prod[]" id="txt_hem_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
												<input type="hidden" name="txt_hem_nro_prod[]" id="txt_hem_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_hem;?>">
												<input type="number" name="txt_hem_aus[]" id="txt_hem_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_aus;?>">
											</td>
											<td><input type="number" name="txt_hem_pag[]" id="txt_hem_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_pag;?>"></td>
											<td><input type="number" name="txt_hem_esa[]" id="txt_hem_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_esa;?>"></td>
											<td><input type="number" name="txt_hem_exo[]" id="txt_hem_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_exo;?>"></td>
											<td><input type="number" name="txt_hem_tot" id="txt_hem_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
										</tr>
										<?php
									  }
									} else {
										$reg_anio = $rsA[0]['anio_informe'];
										$reg_mes = $rsA[0]['mes_informe'];
										$reg_id_dependencia = $rsA[0]['id_establecimiento'];
										$sWhere=''; $sOrder=' Order By orden_por_tipo_producto'; $sLimit='';
										$param[0]['id_estado'] = '1';
										$param[0]['id_tipo_producto'] = 1;
										$item_hem = (int)0;
										$rs = $pr->get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param);
										$nr = count($rs);
										if ($nr > 0) {
										  foreach ($rs as $row) {
												$item_hem ++;
												if($item_hem%2==0){
													$trPColor = "";
												} else {
													$trPColor = "info";
												}
												$hem_aus = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 1, 1,$row['id_producto']);//Produccion diaria
												$hem_pag = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 2, 1,$row['id_producto']);//Produccion diaria
												$hem_esa = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 3, 1,$row['id_producto']);//Produccion diaria
												$hem_exo = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 4, 1,$row['id_producto']);//Produccion diaria
												
												$rsCntMa = $at->get_repIndicadorProduccionPorAnioMesManual($reg_anio, $reg_mes, $reg_id_dependencia, 0, 0,$row['id_producto']);//Produccion agregado manualmente
												if(count($rsCntMa) <> "0"){
													$hem_aus = $rsCntMa[0]['cnt_sis'] + $hem_aus;
													$hem_pag = $rsCntMa[0]['cnt_pagante'] + $hem_pag;
													$hem_esa = $rsCntMa[0]['cnt_estrategia'] + $hem_esa;
													$hem_exo = $rsCntMa[0]['cnt_exonerado'] + $hem_exo;
												}
											  
											?>
											<tr class="<?php echo $trPColor; ?>">
												<td><?php echo $item_hem . "- " . $row['nom_producto'];?></td>
												<td>
													<input type="hidden" name="txt_hem_id_prod[]" id="txt_hem_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
													<input type="hidden" name="txt_hem_nro_prod[]" id="txt_hem_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_hem;?>">
													<input type="number" name="txt_hem_aus[]" id="txt_hem_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_aus;?>">
												</td>
												<td><input type="number" name="txt_hem_pag[]" id="txt_hem_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_pag;?>"></td>
												<td><input type="number" name="txt_hem_esa[]" id="txt_hem_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_esa;?>"></td>
												<td><input type="number" name="txt_hem_exo[]" id="txt_hem_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_hem('<?php echo $row['id_producto'];?>');" value="<?php echo $hem_exo;?>"></td>
												<td><input type="number" name="txt_hem_tot" id="txt_hem_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
											</tr>
											<?php
										  }
										}
									}
								?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab_3">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">EXAMENES BIOQUIMICOS</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_bio_aus_tot" id="txt_bio_aus_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bio_pag_tot" id="txt_bio_pag_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bio_esa_tot" id="txt_bio_esa_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bio_exo_tot" id="txt_bio_exo_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bio_tot_tot" id="txt_bio_tot_tot" class="form-control" value="0" disabled=""></td>
									</tr>
								<?php
									$item_bio = (int)0;
									$rsbio = $ses->get_datosDetalleInformeSESLabExamen($id, 2);
									$nrbio = count($rsbio);
									if ($nrbio > 0) {
									  foreach ($rsbio as $row) {
										$item_bio ++;
										if($item_bio%2==0){
												$trPColor = "";
											} else {
												$trPColor = "info";
											}
											$bio_aus = $row['cnt_sis'];
											$bio_pag = $row['cnt_pagante'];
											$bio_esa = $row['cnt_estrategia'];
											$bio_exo = $row['cnt_exonerado'];
										?>
										<tr class="<?php echo $trPColor; ?>">
											<td><?php echo $item_bio . "- " . $row['nom_producto'];?></td>
											<td>
											<input type="hidden" name="txt_bio_id_prod[]" id="txt_bio_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
											<input type="hidden" name="txt_bio_nro_prod[]" id="txt_bio_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_bio;?>">
											<input type="number" name="txt_bio_aus[]" id="txt_bio_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_aus;?>">
											</td>
											<td><input type="number" name="txt_bio_pag[]" id="txt_bio_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_pag;?>"></td>
											<td><input type="number" name="txt_bio_esa[]" id="txt_bio_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_esa;?>"></td>
											<td><input type="number" name="txt_bio_exo[]" id="txt_bio_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_exo;?>"></td>
											<td><input type="number" name="txt_bio_tot" id="txt_bio_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
										</tr>
										<?php
									  }
									} else {
										$sWhere=''; $sOrder=' Order By orden_por_tipo_producto'; $sLimit='';
										$param[0]['id_estado'] = '1';
										$param[0]['id_tipo_producto'] = 2;
										$item_bio = (int)0;
										$rsbio = $pr->get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param);
										$nrbio = count($rsbio);
										if ($nrbio > 0) {
										  foreach ($rsbio as $row) {
											$item_bio ++;
											if($item_bio%2==0){
													$trPColor = "";
												} else {
													$trPColor = "info";
												}
											$bio_aus = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 1, 2,$row['id_producto']);//Produccion diaria
											$bio_pag = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 2, 2,$row['id_producto']);//Produccion diaria
											$bio_esa = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 3, 2,$row['id_producto']);//Produccion diaria
											$bio_exo = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 4, 2,$row['id_producto']);//Produccion diaria
											
											$rsCntMa = $at->get_repIndicadorProduccionPorAnioMesManual($reg_anio, $reg_mes, $reg_id_dependencia, 0, 0,$row['id_producto']);//Produccion agregado manualmente
											if(count($rsCntMa) <> "0"){
												$bio_aus = $rsCntMa[0]['cnt_sis'] + $bio_aus;
												$bio_pag = $rsCntMa[0]['cnt_pagante'] + $bio_pag;
												$bio_esa = $rsCntMa[0]['cnt_estrategia'] + $bio_esa;
												$bio_exo = $rsCntMa[0]['cnt_exonerado'] + $bio_exo;
											}
											?>
											<tr class="<?php echo $trPColor; ?>">
												<td><?php echo $item_bio . "- " . $row['nom_producto'];?></td>
												<td>
												<input type="hidden" name="txt_bio_id_prod[]" id="txt_bio_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
												<input type="hidden" name="txt_bio_nro_prod[]" id="txt_bio_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_bio;?>">
												<input type="number" name="txt_bio_aus[]" id="txt_bio_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_aus;?>">
												</td>
												<td><input type="number" name="txt_bio_pag[]" id="txt_bio_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_pag;?>"></td>
												<td><input type="number" name="txt_bio_esa[]" id="txt_bio_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_esa;?>"></td>
												<td><input type="number" name="txt_bio_exo[]" id="txt_bio_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bio('<?php echo $row['id_producto'];?>');" value="<?php echo $bio_exo;?>"></td>
												<td><input type="number" name="txt_bio_tot" id="txt_bio_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
											</tr>
											<?php
										  }
										}
									}
								?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab_4">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">EXAMENES INMUNOLOGICOS</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_inm_aus_tot" id="txt_inm_aus_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_inm_pag_tot" id="txt_inm_pag_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_inm_esa_tot" id="txt_inm_esa_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_inm_exo_tot" id="txt_inm_exo_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_inm_tot_tot" id="txt_inm_tot_tot" class="form-control" value="0" disabled=""></td>
									</tr>
								<?php
									$item_inm = (int)0;
									$rsinm = $ses->get_datosDetalleInformeSESLabExamen($id, 3);
									$nrinm = count($rsinm);
									if ($nrinm > 0) {
									  foreach ($rsinm as $row) {
										$item_inm ++;
											if($item_inm%2==0){
												$trPColor = "";
											} else {
												$trPColor = "info";
											}
											$inm_aus = $row['cnt_sis'];
											$inm_pag = $row['cnt_pagante'];
											$inm_esa = $row['cnt_estrategia'];
											$inm_exo = $row['cnt_exonerado'];
										?>
										<tr class="<?php echo $trPColor; ?>">
											<td><?php echo $item_inm . "- " . $row['nom_producto'];?></td>
											<td>
												<input type="hidden" name="txt_inm_id_prod[]" id="txt_inm_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
												<input type="hidden" name="txt_inm_nro_prod[]" id="txt_inm_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_inm;?>">
												<input type="number" name="txt_inm_aus[]" id="txt_inm_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_aus;?>">
											</td>
											<td><input type="number" name="txt_inm_pag[]" id="txt_inm_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_pag;?>"></td>
											<td><input type="number" name="txt_inm_esa[]" id="txt_inm_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_esa;?>"></td>
											<td><input type="number" name="txt_inm_exo[]" id="txt_inm_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_exo;?>"></td>
											<td><input type="number" name="txt_inm_tot" id="txt_inm_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
										</tr>
										<?php
									  }
									} else {
										$sWhere=''; $sOrder=' Order By orden_por_tipo_producto'; $sLimit='';
										$param[0]['id_estado'] = '1';
										$param[0]['id_tipo_producto'] = 3;
										$item_inm = (int)0;
										$rsinm = $pr->get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param);
										$nrinm = count($rsinm);
										if ($nrinm > 0) {
										  foreach ($rsinm as $row) {
											$item_inm ++;
												if($item_inm%2==0){
													$trPColor = "";
												} else {
													$trPColor = "info";
												}
											$inm_aus = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 1, 3,$row['id_producto']);//Produccion diaria
											$inm_pag = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 2, 3,$row['id_producto']);//Produccion diaria
											$inm_esa = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 3, 3,$row['id_producto']);//Produccion diaria
											$inm_exo = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 4, 3,$row['id_producto']);//Produccion diaria
											
											$rsCntMa = $at->get_repIndicadorProduccionPorAnioMesManual($reg_anio, $reg_mes, $reg_id_dependencia, 0, 0,$row['id_producto']);//Produccion agregado manualmente
											if(count($rsCntMa) <> "0"){
												$inm_aus = $rsCntMa[0]['cnt_sis'] + $inm_aus;
												$inm_pag = $rsCntMa[0]['cnt_pagante'] + $inm_pag;
												$inm_esa = $rsCntMa[0]['cnt_estrategia'] + $inm_esa;
												$inm_exo = $rsCntMa[0]['cnt_exonerado'] + $inm_exo;
											}
											?>
											<tr class="<?php echo $trPColor; ?>">
												<td><?php echo $item_inm . "- " . $row['nom_producto'];?></td>
												<td>
													<input type="hidden" name="txt_inm_id_prod[]" id="txt_inm_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
													<input type="hidden" name="txt_inm_nro_prod[]" id="txt_inm_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_inm;?>">
													<input type="number" name="txt_inm_aus[]" id="txt_inm_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_aus;?>">
												</td>
												<td><input type="number" name="txt_inm_pag[]" id="txt_inm_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_pag;?>"></td>
												<td><input type="number" name="txt_inm_esa[]" id="txt_inm_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_esa;?>"></td>
												<td><input type="number" name="txt_inm_exo[]" id="txt_inm_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_inm('<?php echo $row['id_producto'];?>');" value="<?php echo $inm_exo;?>"></td>
												<td><input type="number" name="txt_inm_tot" id="txt_inm_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
											</tr>
											<?php
										  }
										}
									}
								?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab_5">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">EXAMENES MICROBIOLOGICOS</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_mic_aus_tot" id="txt_mic_aus_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_mic_pag_tot" id="txt_mic_pag_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_mic_esa_tot" id="txt_mic_esa_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_mic_exo_tot" id="txt_mic_exo_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_mic_tot_tot" id="txt_mic_tot_tot" class="form-control" value="0" disabled=""></td>
									</tr>
								<?php
									$item_mic = (int)0;
									$rsmic = $ses->get_datosDetalleInformeSESLabExamen($id, 4);
									$nrmic = count($rsmic);
									if ($nrmic > 0) {
									  foreach ($rsmic as $row) {
										$item_mic ++;
											if($item_mic%2==0){
												$trPColor = "";
											} else {
												$trPColor = "info";
											}
											$mic_aus = $row['cnt_sis'];
											$mic_pag = $row['cnt_pagante'];
											$mic_esa = $row['cnt_estrategia'];
											$mic_exo = $row['cnt_exonerado'];
										?>
										<tr class="<?php echo $trPColor; ?>">
											<td><?php echo $item_mic . "- " . $row['nom_producto'];?></td>
											<td>
												<input type="hidden" name="txt_mic_id_prod[]" id="txt_mic_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
												<input type="hidden" name="txt_mic_nro_prod[]" id="txt_mic_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_mic;?>">
												<input type="number" name="txt_mic_aus[]" id="txt_mic_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_aus;?>">
											</td>
											<td><input type="number" name="txt_mic_pag[]" id="txt_mic_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_pag;?>"></td>
											<td><input type="number" name="txt_mic_esa[]" id="txt_mic_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_esa;?>"></td>
											<td><input type="number" name="txt_mic_exo[]" id="txt_mic_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_exo;?>"></td>
											<td><input type="number" name="txt_mic_tot" id="txt_mic_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
										</tr>
										<?php
									  }
									} else {
										$sWhere=''; $sOrder=' Order By orden_por_tipo_producto'; $sLimit='';
										$param[0]['id_estado'] = '1';
										$param[0]['id_tipo_producto'] = 4;
										$item_mic = (int)0;
										$rsmic = $pr->get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param);
										$nrmic = count($rsmic);
										if ($nrmic > 0) {
										  foreach ($rsmic as $row) {
											$item_mic ++;
												if($item_mic%2==0){
													$trPColor = "";
												} else {
													$trPColor = "info";
												}
											$mic_aus = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 1, 4,$row['id_producto']);//Produccion diaria
											$mic_pag = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 2, 4,$row['id_producto']);//Produccion diaria
											$mic_esa = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 3, 4,$row['id_producto']);//Produccion diaria
											$mic_exo = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 4, 4,$row['id_producto']);//Produccion diaria
											
											$rsCntMa = $at->get_repIndicadorProduccionPorAnioMesManual($reg_anio, $reg_mes, $reg_id_dependencia, 0, 0,$row['id_producto']);//Produccion agregado manualmente
											if(count($rsCntMa) <> "0"){
												$mic_aus = $rsCntMa[0]['cnt_sis'] + $mic_aus;
												$mic_pag = $rsCntMa[0]['cnt_pagante'] + $mic_pag;
												$mic_esa = $rsCntMa[0]['cnt_estrategia'] + $mic_esa;
												$mic_exo = $rsCntMa[0]['cnt_exonerado'] + $mic_exo;
											}
											?>
											<tr class="<?php echo $trPColor; ?>">
												<td><?php echo $item_mic . "- " . $row['nom_producto'];?></td>
												<td>
													<input type="hidden" name="txt_mic_id_prod[]" id="txt_mic_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
													<input type="hidden" name="txt_mic_nro_prod[]" id="txt_mic_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_mic;?>">
													<input type="number" name="txt_mic_aus[]" id="txt_mic_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_aus;?>">
												</td>
												<td><input type="number" name="txt_mic_pag[]" id="txt_mic_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_pag;?>"></td>
												<td><input type="number" name="txt_mic_esa[]" id="txt_mic_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_esa;?>"></td>
												<td><input type="number" name="txt_mic_exo[]" id="txt_mic_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_mic('<?php echo $row['id_producto'];?>');" value="<?php echo $mic_exo;?>"></td>
												<td><input type="number" name="txt_mic_tot" id="txt_mic_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
											</tr>
											<?php
										  }
										}
									}
								?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane" id="tab_6">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">PERFIL / PAQUETE</th>
										<th class="text-center">AUS/SIS</th>
										<th class="text-center">PAGANTES</th>
										<th class="text-center">ESTRATEGIAS SANITARIAS</th>
										<th class="text-center">EXONERADOS</th>
										<th class="text-center">TOTAL</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_paq_aus_tot" id="txt_paq_aus_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_paq_pag_tot" id="txt_paq_pag_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_paq_esa_tot" id="txt_paq_esa_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_paq_exo_tot" id="txt_paq_exo_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_paq_tot_tot" id="txt_paq_tot_tot" class="form-control" value="0" disabled=""></td>
									</tr>
								<?php
									$item_paq = (int)0;
									$rspaq = $ses->get_datosDetalleInformeSESLabExamen($id, 6);
									$nrpaq = count($rspaq);
									if ($nrpaq > 0) {
									  foreach ($rspaq as $row) {
										$item_paq ++;
											if($item_paq%2==0){
												$trPColor = "";
											} else {
												$trPColor = "info";
											}
											$paq_aus = $row['cnt_sis'];
											$paq_pag = $row['cnt_pagante'];
											$paq_esa = $row['cnt_estrategia'];
											$paq_exo = $row['cnt_exonerado'];
										?>
										<tr class="<?php echo $trPColor; ?>">
											<td><?php echo $item_paq . "- " . $row['nom_producto'];?></td>
											<td>
												<input type="hidden" name="txt_paq_id_prod[]" id="txt_paq_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
												<input type="hidden" name="txt_paq_nro_prod[]" id="txt_paq_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_paq;?>">
												<input type="number" name="txt_paq_aus[]" id="txt_paq_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_aus;?>">
											</td>
											<td><input type="number" name="txt_paq_pag[]" id="txt_paq_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_pag;?>"></td>
											<td><input type="number" name="txt_paq_esa[]" id="txt_paq_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_esa;?>"></td>
											<td><input type="number" name="txt_paq_exo[]" id="txt_paq_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_exo;?>"></td>
											<td><input type="number" name="txt_paq_tot" id="txt_paq_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
										</tr>
										<?php
									  }
									} else {
										$sWhere=''; $sOrder=' Order By orden_por_tipo_producto'; $sLimit='';
										$param[0]['id_estado'] = '1';
										$param[0]['id_tipo_producto'] = 6;
										$item_paq = (int)0;
										$rspaq = $pr->get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param);
										$nrpaq = count($rspaq);
										if ($nrpaq > 0) {
										  foreach ($rspaq as $row) {
											$item_paq ++;
												if($item_paq%2==0){
													$trPColor = "";
												} else {
													$trPColor = "info";
												}
											$paq_aus = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 1, 6,$row['id_producto']);//Produccion diaria
											$paq_pag = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 2, 6,$row['id_producto']);//Produccion diaria
											$paq_esa = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 3, 6,$row['id_producto']);//Produccion diaria
											$paq_exo = $at->get_repIndicadorProduccionPorAnioMes($reg_anio, $reg_mes, $reg_id_dependencia, 4, 6,$row['id_producto']);//Produccion diaria
											
											$rsCntMa = $at->get_repIndicadorProduccionPorAnioMesManual($reg_anio, $reg_mes, $reg_id_dependencia, 0, 0,$row['id_producto']);//Produccion agregado manualmente
											if(count($rsCntMa) <> "0"){
												$paq_aus = $rsCntMa[0]['cnt_sis'] + $paq_aus;
												$paq_pag = $rsCntMa[0]['cnt_pagante'] + $paq_pag;
												$paq_esa = $rsCntMa[0]['cnt_estrategia'] + $paq_esa;
												$paq_exo = $rsCntMa[0]['cnt_exonerado'] + $paq_exo;
											}
											?>
											<tr class="<?php echo $trPColor; ?>">
												<td><?php echo $item_paq . "- " . $row['nom_producto'];?></td>
												<td>
													<input type="hidden" name="txt_paq_id_prod[]" id="txt_paq_id_prod_<?php echo $row['id_producto'];?>" value="<?php echo $row['id_producto'];?>">
													<input type="hidden" name="txt_paq_nro_prod[]" id="txt_paq_nro_prod_<?php echo $row['id_producto'];?>" value="<?php echo $item_paq;?>">
													<input type="number" name="txt_paq_aus[]" id="txt_paq_aus_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_aus;?>">
												</td>
												<td><input type="number" name="txt_paq_pag[]" id="txt_paq_pag_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_pag;?>"></td>
												<td><input type="number" name="txt_paq_esa[]" id="txt_paq_esa_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_esa;?>"></td>
												<td><input type="number" name="txt_paq_exo[]" id="txt_paq_exo_<?php echo $row['id_producto'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_paq('<?php echo $row['id_producto'];?>');" value="<?php echo $paq_exo;?>"></td>
												<td><input type="number" name="txt_paq_tot" id="txt_paq_tot_<?php echo $row['id_producto'];?>" class="form-control" maxlength="5" value="0" disabled=""></td>
											</tr>
											<?php
										  }
										}
									}
								?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-center">
								<div class="btn-group">
									<button type="button" class="btn btn-primary btn-lg" id="btn-submit-LAB" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('LAB')"><i class="fa fa-save"></i> Guardar informe </button>
									<a href="./main_informe_lab.php" class="btn btn-lg btn-default"><i class="glyphicon glyphicon-log-out"></i> Cancelar</a>
								</div>
							</div>
						  </div>
					</div>
				</div>
			</div>
		</div> <!-- JOSE-->
		</form>
</div>
					<div class="tab-pane" id="tab_p2">
					<form name="frmAreaBac" id="frmAreaBac">
						<div class="panel">
							<div class="panel-heading">
									<h3 class="panel-title"><strong>BASILOSCOPIAS</strong></h3>
							</div>
							<div class="panel-body">
							<table class="table table-bordered m-auto">
								<thead>
									<tr>
										<th class="text-center">DIAGNÓSTICO</th>
										<th class="text-center">REALIZADAS</th>
										<th class="text-center">POSITIVO<br/>(+)</th>
										<th class="text-center">POSITIVO<br/>(++)</th>
										<th class="text-center">POSITIVO<br/>(+++)</th>
										<th class="text-center">PAUCIBACILAR</th>
										<th class="text-center">TOTAL<br/>POSITIVOS</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><b>TOTALES</b></td>
										<td><input type="number" name="txt_bk_aten_tot" id="txt_bk_aten_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bk_posi1_tot" id="txt_bk_posi1_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bk_posi2_tot" id="txt_bk_posi2_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bk_posi3_tot" id="txt_bk_posi3_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bk_pau_tot" id="txt_bk_pau_tot" class="form-control" value="0" disabled=""></td>
										<td><input type="number" name="txt_bk_posi_tot" id="txt_bk_posi_tot" class="form-control" value="0" disabled=""></td>
										<input type="hidden" name="txt_bk_id1" id="txt_bk_id1" value="">
									</tr>
								<?php
									$item_bk = (int)0;
									$exis_bk = (int)1;
									$rsC = $ses->get_datosDetalleInformeSESBacBasiloscopia($id);
									if(count($rsC) == 0){
										$exis_bk = 0;
										$rsC = $c->get_listaSeleccionResultadoPorTipo(45);
									}
									//print_r($rsC);
									foreach ($rsC as $row) {
										$item_bk ++;
										if($item_bk%2==0){
											$trBKColor = "";
										} else {
											$trBKColor = "info";
										}
										?>
									<tr class="<?php echo $trBKColor; ?>">
										<td><?php echo $item_bk . "- " . $row['nombre']."<small>(" . $row['id'] . ")</small>";?></td>
										<td>
											<input type="hidden" name="txt_bk_id[]" id="txt_bk_id_<?php echo $row['id'];?>" value="<?php echo $row['id'];?>">
											<input type="hidden" name="txt_bk_nro[]" id="txt_bk_nro_<?php echo $row['id'];?>" value="<?php echo $item_bk;?>">
											<input type="number" name="txt_bk_aten[]" id="txt_bk_aten_<?php echo $row['id'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bk('<?php echo $row['id'];?>');" value="<?php if($exis_bk == 1){ echo $row['cnt_atencion'];}else{ echo 0;}?>">
										</td>
										<td><input type="number" name="txt_bk_posi1[]" id="txt_bk_posi1_<?php echo $row['id'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bk('<?php echo $row['id'];?>');" value="<?php if($exis_bk == 1){ echo $row['cnt_posi1'];}else{ echo 0;}?>"></td>
										<td><input type="number" name="txt_bk_posi2[]" id="txt_bk_posi2_<?php echo $row['id'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bk('<?php echo $row['id'];?>');" value="<?php if($exis_bk == 1){ echo $row['cnt_posi2'];}else{ echo 0;}?>"></td>
										<td><input type="number" name="txt_bk_posi3[]" id="txt_bk_posi3_<?php echo $row['id'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bk('<?php echo $row['id'];?>');" value="<?php if($exis_bk == 1){ echo $row['cnt_posi3'];}else{ echo 0;}?>"></td>
										<td><input type="number" name="txt_bk_pau[]" id="txt_bk_pau_<?php echo $row['id'];?>" class="form-control" maxlength="4" onfocus="this.select()" onkeypress="return NumCheck(event);" onkeyup="totales_bk('<?php echo $row['id'];?>');" value="<?php if($exis_bk == 1){ echo $row['cnt_pau'];}else{ echo 0;}?>"></td>
										<td><input type="number" name="txt_bk_tot" id="txt_bk_tot_<?php echo $row['id'];?>" class="form-control" maxlength="5" value="<?php if($exis_bk == 1){ echo $row['cnt_totalposi'];}else{ echo 0;}?>" disabled=""></td>
								<?php
									}
								?>
								</body>
							</table>
							</div>
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-center">
										<div class="btn-group">
											<button type="button" class="btn btn-success btn-lg" id="btn-submit-BAC" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('BAC')"><i class="fa fa-save"></i> Guardar informe </button>
											<a href="./main_informe_lab.php" class="btn btn-lg btn-default"><i class="glyphicon glyphicon-log-out"></i> Cancelar</a>
										</div>
									</div>
								  </div>
							</div>
						</div>
					</form>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>

    </div>
  </div>
</div>
<?php require_once '../include/footer.php'; ?>
<!-- EChart-->

<script Language="JavaScript">

function totales_ate() {
	var ta1 = str_number($("#txt_ate_aus").val());
	var ta2 = str_number($("#txt_ate_pag").val());
	var ta3 = str_number($("#txt_ate_esa").val());
	var ta4 = str_number($("#txt_ate_exo").val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_ate_tot").val(totalf);
}


function totales_hem(idprod) {
	var ta1 = str_number($("#txt_hem_aus_"+idprod).val());
	var ta2 = str_number($("#txt_hem_pag_"+idprod).val());
	var ta3 = str_number($("#txt_hem_esa_"+idprod).val());
	var ta4 = str_number($("#txt_hem_exo_"+idprod).val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_hem_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_hem_aus[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_hem_aus_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_hem_pag[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_hem_pag_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_hem_esa[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_hem_esa_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_hem_exo[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_hem_exo_tot").val(suma_exo);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_hem_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_hem_tot_tot").val(suma_tot);
}

function totales_bio(idprod) {
	var ta1 = str_number($("#txt_bio_aus_"+idprod).val());
	var ta2 = str_number($("#txt_bio_pag_"+idprod).val());
	var ta3 = str_number($("#txt_bio_esa_"+idprod).val());
	var ta4 = str_number($("#txt_bio_exo_"+idprod).val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_bio_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_bio_aus[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_bio_aus_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_bio_pag[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_bio_pag_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_bio_esa[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_bio_esa_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_bio_exo[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_bio_exo_tot").val(suma_exo);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_bio_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_bio_tot_tot").val(suma_tot);
}

function totales_mic(idprod) {
	var ta1 = str_number($("#txt_mic_aus_"+idprod).val());
	var ta2 = str_number($("#txt_mic_pag_"+idprod).val());
	var ta3 = str_number($("#txt_mic_esa_"+idprod).val());
	var ta4 = str_number($("#txt_mic_exo_"+idprod).val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_mic_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_mic_aus[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_mic_aus_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_mic_pag[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_mic_pag_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_mic_esa[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_mic_esa_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_mic_exo[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_mic_exo_tot").val(suma_exo);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_mic_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_mic_tot_tot").val(suma_tot);
}


function totales_inm(idprod) {
	var ta1 = str_number($("#txt_inm_aus_"+idprod).val());
	var ta2 = str_number($("#txt_inm_pag_"+idprod).val());
	var ta3 = str_number($("#txt_inm_esa_"+idprod).val());
	var ta4 = str_number($("#txt_inm_exo_"+idprod).val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_inm_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_inm_aus[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_inm_aus_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_inm_pag[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_inm_pag_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_inm_esa[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_inm_esa_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_inm_exo[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_inm_exo_tot").val(suma_exo);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_inm_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_inm_tot_tot").val(suma_tot);
}

function totales_paq(idprod) {
	var ta1 = str_number($("#txt_paq_aus_"+idprod).val());
	var ta2 = str_number($("#txt_paq_pag_"+idprod).val());
	var ta3 = str_number($("#txt_paq_esa_"+idprod).val());
	var ta4 = str_number($("#txt_paq_exo_"+idprod).val());
	var totalf = ta1 + ta2 + ta3 + ta4;
	$("#txt_paq_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_paq_aus[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_paq_aus_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_paq_pag[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_paq_pag_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_paq_esa[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_paq_esa_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_paq_exo[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_paq_exo_tot").val(suma_exo);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_paq_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_paq_tot_tot").val(suma_tot);
}

function totales_bk(idprod) {
	//var ta1 = str_number($("#txt_bk_aten_"+idprod).val());
	var ta2 = str_number($("#txt_bk_posi1_"+idprod).val());
	var ta3 = str_number($("#txt_bk_posi2_"+idprod).val());
	var ta4 = str_number($("#txt_bk_posi3_"+idprod).val());
	var ta5 = str_number($("#txt_bk_pau_"+idprod).val());
	var totalf = ta2 + ta3 + ta4 + ta5;
	$("#txt_bk_tot_"+idprod).val(totalf);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_aus = document.getElementsByName('txt_bk_aten[]');
	// Variable para almacenar la suma
	var suma_aus = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_aus.length; i++) {
		var valor_aus = parseFloat(elementos_aus[i].value); // Convertir el valor a número
		suma_aus += valor_aus;
	}
	// Imprimir la suma
	$("#txt_bk_aten_tot").val(suma_aus);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pag = document.getElementsByName('txt_bk_posi1[]');
	// Variable para almacenar la suma
	var suma_pag = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pag.length; i++) {
		var valor_pag = parseFloat(elementos_pag[i].value); // Convertir el valor a número
		suma_pag += valor_pag;
	}
	// Imprimir la suma
	$("#txt_bk_posi1_tot").val(suma_pag);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_esa = document.getElementsByName('txt_bk_posi2[]');
	// Variable para almacenar la suma
	var suma_esa = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_esa.length; i++) {
		var valor_esa = parseFloat(elementos_esa[i].value); // Convertir el valor a número
		suma_esa += valor_esa;
	}
	// Imprimir la suma
	$("#txt_bk_posi2_tot").val(suma_esa);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_exo = document.getElementsByName('txt_bk_posi3[]');
	// Variable para almacenar la suma
	var suma_exo = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_exo.length; i++) {
		var valor_exo = parseFloat(elementos_exo[i].value); // Convertir el valor a número
		suma_exo += valor_exo;
	}
	// Imprimir la suma
	$("#txt_bk_posi3_tot").val(suma_exo);
	
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_pau = document.getElementsByName('txt_bk_pau[]');
	// Variable para almacenar la suma
	var suma_pau = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_pau.length; i++) {
		var valor_pau = parseFloat(elementos_pau[i].value); // Convertir el valor a número
		suma_pau += valor_pau;
	}
	// Imprimir la suma
	$("#txt_bk_pau_tot").val(suma_pau);
  
	// Obtener todos los elementos de entrada con el mismo nombre
	var elementos_tot = document.getElementsByName('txt_bk_tot');
	// Variable para almacenar la suma
	var suma_tot = 0;
	// Recorrer los elementos y sumar los valores
	for (var i = 0; i < elementos_tot.length; i++) {
		var valor_tot = parseFloat(elementos_tot[i].value); // Convertir el valor a número
		suma_tot += valor_tot;
	}
	// Imprimir la suma
	$("#txt_bk_posi_tot").val(suma_tot);
}

function str_number(valor){
    var num=parseFloat(valor);
    if(isNaN(num))
        return 0;
    else
        return num;
}

function NumCheck(e) {
  var key = window.Event ? e.which : e.keyCode;
  return ((key >= 48 && key <= 57) || key == 0 || key == 8)
}

function save_atencion(opt) {
	var msg = "";
	var sw = true;
	$('#btn-submit-'+opt).prop("disabled", true);

	if(opt == "LAB"){
		var AnameInput = $('#frmArea').serializeArray();
		console.log(AnameInput);
		var ing = "";
		len = AnameInput.length;
		for (i=0; i<len; i++) {
		nameInput = AnameInput[i]['name'];
		var arrayCadenas = nameInput.split('_');
			if(arrayCadenas.length == 3){
				if($("#" + nameInput).val().trim() != ""){
					ing = "1";
					break;
				}
			}
		}
		if(ing == ""){
			msg += "Existe un exámen que no tiene cantidad, verifique porfavor<br/>";
			sw = false;
		}
	} else {
		
	}
	if (sw == false) {
		bootbox.alert(msg);
		$('#btn-submit'+opt).prop("disabled", false);
		return false;
	}
	
	var messa = "";
	if (opt == "LAB") {
		messa = "Se registrará el <b class='text-primary'>INFORME DE LABORATORIO</b>, ¿Está seguro de continuar?";
	} else {
		messa = "Se registrará el <b class='text-success'>INFORME DE BASILOSCOPIA</b>, ¿Está seguro de continuar?";  
	}
  
  bootbox.confirm({
    message: "Se registrarán los registros ingresados, ¿Está seguro de continuar?",
    buttons: {
      confirm: {
        label: 'Si',
        className: 'btn-success'
      },
      cancel: {
        label: 'No',
        className: 'btn-danger'
      }
    },
    callback: function (result) {
      if (result == true) {
		if(opt == "LAB"){
			$.ajax( {
			  type: 'POST',
			  //dataType: "json",
			  url: '../../controller/ctrlSes.php', 
			  data: $('#frmArea').serialize()
			  + "&id_informe=" + $('#txt_informe').val() + "&id_dependencia=" + $('#txt_id_dependencia').val() + "&anio=" + $('#txt_anio').val() + "&mes=" + $('#txt_mes').val()
			  + "&ate_tot=" + $('#txt_ate_tot').val() + "&hem_tot_tot=" + $('#txt_hem_tot_tot').val() + "&bio_tot_tot=" + $('#txt_bio_tot_tot').val() + "&inm_tot_tot=" + $('#txt_inm_tot_tot').val() + "&mic_tot_tot=" + $('#txt_mic_tot_tot').val() + "&paq_tot_tot=" + $('#txt_paq_tot_tot').val()
			  + "&accion=POST_REG_INFORMEDETLAB",
			  success: function(data) {
				var tmsg = data.substring(0, 2);
				var lmsg = data.length;
				var msg = data.substring(3, lmsg);
				//console.log(tmsg);
				$('#btn-submit-'+opt).prop("disabled", false);
				if(tmsg == "OK"){
					showMessage("Informe LABORATORIO actualizado correctamente", "success");
					return false;
				} else {
				  showMessage(msg, "error");
				  return false;
				}
			  }
			});
		} else {
		  $.ajax( {
			  type: 'POST',
			  url: '../../controller/ctrlSes.php',
			  data: $('#frmAreaBac').serialize()
			  + "&id_informe=" + $('#txt_informe').val() + "&id_dependencia=" + $('#txt_id_dependencia').val() + "&anio=" + $('#txt_anio').val() + "&mes=" + $('#txt_mes').val()
			  + "&ate_tot=" + $('#txt_bk_aten_tot').val() + "&posi_tot=" + $('#txt_bk_posi_tot').val()
			  + "&accion=POST_REG_INFORMEDETBAC",
			  success: function(data) {
				var tmsg = data.substring(0, 2);
				var lmsg = data.length;
				var msg = data.substring(3, lmsg);
				//console.log(tmsg);
				$('#btn-submit-'+opt).prop("disabled", false);
				if(tmsg == "OK"){
					showMessage("Informe BACTERIOLOGICO actualizado correctamente", "success");
					return false;
				} else {
				  showMessage(msg, "error");
				  return false;
				}
			  }
			});
		}

      } else {
        $('#btn-submit-'+opt).prop("disabled", false);
      }
    }
  });
  
}


$(document).ready(function () {

	<?php 
	if ($nr > 0) {
	  foreach ($rs as $row) {
		  ?>
			totales_hem('<?php echo $row['id_producto'];?>');
		<?php
	  }
	}
	if ($nrbio > 0) {
	  foreach ($rsbio as $row) {
		  ?>
			totales_bio('<?php echo $row['id_producto'];?>');
		<?php
	  }
	}
	if ($nrinm > 0) {
	  foreach ($rsinm as $row) {
		  ?>
			totales_inm('<?php echo $row['id_producto'];?>');
		<?php
	  }
	}
	if ($nrmic > 0) {
	  foreach ($rsmic as $row) {
		  ?>
			totales_mic('<?php echo $row['id_producto'];?>');
		<?php
	  }
	}
	if ($nrpaq > 0) {
	  foreach ($rspaq as $row) {
		  ?>
			totales_paq('<?php echo $row['id_producto'];?>');
		<?php
	  }
	}
	
	foreach ($rsC as $row) {
		?>
			totales_bk('<?php echo $row['id'];?>');
		<?php
	}
	?>	
});
</script>
<?php require_once '../include/masterfooter.php'; ?>
