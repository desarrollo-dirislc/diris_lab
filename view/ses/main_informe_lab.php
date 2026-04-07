<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php';

require_once '../../model/Producto.php';
$pr = new Producto();
require_once '../../model/Dependencia.php';
$d = new Dependencia();

$a_date = date("Y-m-d");
$fecIni = date("01/m/Y", strtotime($a_date));
?>
<style>
#cntSIS {
  height: 450px;
}

div.dt-buttons {
  float: left;
}
div.dataTables_length {
  float: right;
  text-align: right;
}
div.dataTables_info {
  float: left;
}
div.dataTables_paginate paging_simple_numbers {
  float: right;
  text-align: right;
}

@media screen and (max-width: 450px) {
  div.dt-buttons {
    float: none;
    text-align: center;
  }
  div.dataTables_length {
    float: none;
  }
  div.dataTables_info {
    float: none;
  }
  div.dataTables_paginate paging_simple_numbers {
    float: none;
  }
}

.table.dataTable tbody tr.active:hover td, .table.dataTable tbody tr.active:hover th {
  background-color: #a7a7a7 !important;
}

.table.dataTable tbody tr.active td, .table.dataTable tbody tr.active th {
  background-color: #cecece;
  color: #333;
}


.list-group-item {
  padding: 3px 15px !important;
}

.table-bordered {
  border: 1px solid #ddd;
    border-top-color: rgb(221, 221, 221);
    border-top-style: solid;
    border-top-width: 0px;
    border-right-color: rgb(221, 221, 221);
    border-right-style: solid;
    border-right-width: 1px;
    border-bottom-color: rgb(221, 221, 221);
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-left-color: rgb(221, 221, 221);
    border-left-style: solid;
    border-left-width: 1px;
    border-image-outset: 0;
    border-image-repeat: stretch;
    border-image-slice: 100%;
    border-image-source: none;
    border-image-width: 1;
}

</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>LISTADO DE INFORMES</strong></h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-4">
          <div class="box box-success">
            <br/>
            <div class="box-body box-profile">
				<input type="hidden" name="txt_id_rol_usr" id="txt_id_rol_usr" value="<?php echo $_SESSION['labIdRolUser'];?>">
				<div class="col-xs-12">
				  <div class="form-group">
					<div class="row">
					  <div class="col-sm-6">
						<label for="txt_bus_anio">Año</label>
						<select class="form-control" name="txt_bus_anio" id="txt_bus_anio" onchange="buscar_cnt_produccion_por_mes(); buscar_datos();">
							<?php
								$month_curent = (int)date('m');
								$year_init = 2022;
								$year_curent = (int)(date('Y'));
								$year_future = $year_curent + 1;
								$month_pased = $month_curent - 1;
								for ($i = $year_init; $i <= $year_future; $i++) {
									echo "<option value='$i'"; if($i == date('Y')) echo " selected";
									echo ">$i</option>";
								}
							?>
						</select>
					  </div>
					  <div class="col-sm-6">
						<label for="txt_bus_mes">Mes</label>
						<select class="form-control" name="txt_bus_mes" id="txt_bus_mes" onchange="buscar_cnt_produccion_por_mes(); buscar_datos();">
							<?php
								$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
								"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];
								for ($i = 1; $i <= count($meses_arr); $i++) {
									if($month_pased == 0){
										echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
									} else {
										echo "<option value='$i'"; if($month_curent - 1 == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
									}
								}
							?>
						</select>
					  </div>
					</div>
					<div class="row">
					  <div class="col-sm-12">
							<label for="txt_bus_id_dependencia">Dependencia:<?php echo $_SESSION['labIdDepUser'];?></label>
							<?php $rsD = $d->get_listaDepenInstitucion(); ?>
							<select name="txt_bus_id_dependencia" id="txt_bus_id_dependencia" style="width:100%;" <?php if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "15") Or ($_SESSION['labIdRolUser'] == "19")){ echo "";} else { echo " disabled";}?> onchange="buscar_cnt_produccion_por_mes(); buscar_datos();">>
							  <option value="" selected="">-- Todos --</option>
							  <?php
							  foreach ($rsD as $row) {
								echo "<option value='" . $row['id_dependencia'] . "'";
								if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "15") Or ($_SESSION['labIdRolUser'] == "19")){ echo "";} else { if($_SESSION['labIdDepUser'] == $row['id_dependencia']){ echo " selected";}}
								echo ">" . $row['codref_depen'] . ": " . $row['nom_depen'] . "</option>";
							  }
							  ?>
							</select>
					  </div>
					</div>
				  </div>
				  <hr/>
				  <h3 class="profile-username text-center">Semáforo por Laboratorio</h3>
				  <div id="cntSIS"></div>
				  <!--<button type="button" class="btn btn-warning btn-block" id="btnExportCnt" onclick="expor_cantidad()"><i class="glyphicon glyphicon-open"></i> Exportar cantidad mensual </button>-->
				  <?php //print_r($_SESSION) ?>
				</div>
            </div>
		  </div>
        </div>
        <div class="col-sm-8">
			  <div class="box box-primary">
				  <div class="box-body box-profile">
						<form name="frmBus" id="frmBus" class="form-horizontal">
							<div class="checkbox">
								<label>
								  <input type="checkbox" id="checkbox1" checked="true" value="1"/> Ver de todo el año
								</label>
							</div>						
						</form>
					<br/>
						<div class="row">
							<div class="col-xs-12">
							<div class="table-responsive">
							<table id="tblAtencion" class="display" cellspacing="0" width="100%">
							  <thead class="bg-aqua">
								<tr>
								  <th>DEPENDENCIA</th>
								  <th>AÑO</th>
								  <th>MES</th>
								  <th>LABORATORIO<br/>TOTAL ATENCION</th>
								  <th>LABORATORIO<br/>TOTAL EXAMEN</th>
								  <th>BASILOSCOPIA<br/>TOTAL ATENCION</th>
								  <th>BASILOSCOPIA<br/>TOTAL POSITIVOS</th>
								  <th>FECHA REGISTRO</th>
								  <th>&nbsp;</th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
							</div>
							</div>
					  </div>
				</div>
			</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-info pull-right" id="btnBackForm" onclick="back()"><i class="glyphicon glyphicon-log-out"></i> Ir al Menú</button>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_add_examen" role="dialog" data-backdrop="static">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h2 class="modal-title">AGREGAR CANTIDAD DE EXAMEN</h2>
		</div>
		<div class="modal-body">
		<input type="hidden" name="txt_id_cnt_prod_mes" id="txt_id_cnt_prod_mes" value="0"/>
		<div class="row">
		 <div class="form-group">
			<div class="col-sm-6">
				<label for="txt_cnt_anio">AÑO</label>
				<select class="form-control" name="txt_cnt_anio" id="txt_cnt_anio">
					<option value=""> -- Seleccione -- </option>
					<?php
						$year_init = 2022;
						$year_curent = (int)(date('Y')) + 1;
						for ($i = $year_init; $i <= $year_curent; $i++) {
							echo "<option value='$i'"; if($i == date('Y')) echo " selected";
							echo ">$i</option>";
						}
					?>
				</select>
			</div>
			<div class="col-sm-6">
				<label for="txt_cnt_mes">MES</label>
				<select class="form-control" name="txt_cnt_mes" id="txt_cnt_mes">
					<option value=""> -- Seleccione -- </option>
					<?php
						$month_curent = date('m');
						$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
						"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

						for ($i = 1; $i <= count($meses_arr); $i++) {
							echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
						}
					?>
				</select>
			</div>
		  </div>
		 </div>
		 <div class="row">
			 <div class="form-group">
			 <div class="col-sm-12">
			 <label for="txt_id_producto">EXAMEN:</label>
				<select class="form-control" name="txt_id_producto" id="txt_id_producto" style="width: 100%">
				  <option value="">--Seleccione--</option>
				  <?php
				  $rsP = $pr->get_listaProductoLaboratorioPorIdDep($labIdDepUser);
				  foreach ($rsP as $rowP) {
					echo "<option value='" . $rowP['id_producto'] ."'>" . $rowP['nom_producto'] . "</option>";
				  }
				  ?>
				</select>
			 </div>
			 </div>
		 </div>
		 <div class="row">
			 <div class="form-group">
				<div class="col-sm-3">
					<label for="txt_cnt_sis_producto">AUS:</label>
					<input type="text" name="txt_cnt_sis_producto" id="txt_cnt_sis_producto" class="form-control input-sm" onfocus="this.select()" maxlength="4" onkeyup="totales();" value="0"/>
				</div>
				<div class="col-sm-3">
					<label for="txt_cnt_pag_producto">PAGANTE:</label>
					<input type="text" name="txt_cnt_pag_producto" id="txt_cnt_pag_producto" class="form-control input-sm" onfocus="this.select()" maxlength="4" onkeyup="totales();" value="0"/>
				</div>
				<div class="col-sm-3">
					<label for="txt_cnt_est_producto">ESTRATEGIA:</label>
					<input type="text" name="txt_cnt_est_producto" id="txt_cnt_est_producto" class="form-control input-sm" onfocus="this.select()" maxlength="4" onkeyup="totales();" value="0"/>
				</div>
				<div class="col-sm-3">
					<label for="txt_cnt_exo_producto">EXONERADO:</label>
					<input type="text" name="txt_cnt_exo_producto" id="txt_cnt_exo_producto" class="form-control input-sm" onfocus="this.select()" maxlength="4" onkeyup="totales();" value="0"/>
				</div>
			 </div>
			 <hr/>
			 <br/>
			 <div class="form-group">
				<div class="col-sm-9 text-right">
					<label class="">TOTAL:</label>
				</div>
				<div class="col-sm-3">
					<input type="text" name="txt_cnt_total" id="txt_cnt_total" class="form-control input-sm" maxlength="4" value="0" disabled/>
				</div>
			 </div>
		 </div>
		</div>
		<div class="modal-footer">
          <div class="row">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-primary btn-continuar" id="btnFrmSaveIngExa" onclick="save_ing_cantidad()"><i class="fa fa-save"></i> Guardar </button>
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
            </div>
          </div>
        </div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal_rep_bac" role="dialog" data-backdrop="static">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h2 class="modal-title">REGISTRAR INFORME</h2>
		</div>
		<form  name="frm_main_reg" id="frm_main_reg" method="POST" action="./main_reglaboratorio.php">
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-6">
						<div class="form-group">
							<label for="txt_reg_anio">Año:</label>
							<select class="form-control" name="txt_reg_anio" id="txt_reg_anio">
							<?php
								$year_init = 2023;
								$year_curent = date('Y');
								for ($i = $year_init; $i <= $year_curent; $i++) {
									echo "<option value='$i'"; if($year_curent == $i){ echo " selected";}  echo ">$i</option>";
								}
							?>
							</select>
						</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="txt_reg_mes">Mes:</label>
						<select class="form-control" name="txt_reg_mes" id="txt_reg_mes">
							<option value="" selected>--SELECCIONE MES--</option>
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'>" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_id_reg_dependencia">Dependencia:</label>
						<?php $rsD = $d->get_listaDepenInstitucion(); ?>
						<select name="txt_id_reg_dependencia" id="txt_id_reg_dependencia" class="form-control select" style="width: 100%" <?php if($_SESSION['labIdRolUser'] <> "1"){ if($_SESSION['labIdRolUser'] <> "15"){ if($_SESSION['labIdRolUser'] <> "2"){ if($_SESSION['labIdRolUser'] <> "19"){ echo "disabled";}}}}?>>
						  <?php
						  foreach ($rsD as $row) {
							echo "<option value='" . $row['id_dependencia'] . "'";
							if ($row['id_dependencia'] == $_SESSION['labIdDepUser']) echo " selected";
							echo ">" . $row['nom_depen'] . "</option>";
						  }
						  ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-primary btn-sm" value="Registrar">
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
        </div>
		</form>
	</div>
	</div>
</div>

<div class="modal fade" id="modal_ses_eess" role="dialog" data-backdrop="static">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h2 class="modal-title">EXPORTAR INFORME EESS</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_id_dependencia_eess">Dependencia:</label>
						<?php $rsD = $d->get_listaDepenInstitucion(); ?>
						<select name="txt_id_dependencia_eess" id="txt_id_dependencia_eess" class="form-control select" style="width: 100%" <?php if($_SESSION['labIdRolUser'] <> "1"){ if($_SESSION['labIdRolUser'] <> "15"){ if($_SESSION['labIdRolUser'] <> "2"){ if($_SESSION['labIdRolUser'] <> "19"){ echo "disabled";}}}}?>>
						  <?php
						  foreach ($rsD as $row) {
							echo "<option value='" . $row['id_dependencia'] . "'";
							if ($row['id_dependencia'] == $_SESSION['labIdDepUser']) echo " selected";
							echo ">" . $row['nom_depen'] . "</option>";
						  }
						  ?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_anio_eess">Año:</label>
						<select class="form-control" name="txt_anio_eess" id="txt_anio_eess">
						<?php
							$year_init = 2022;
							$year_curent = date('Y');
							for ($i = $year_init; $i <= $year_curent; $i++) {
								echo "<option value='$i'"; if($year_curent == $i){ echo " selected";}  echo ">$i</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_mes_desde_eess">Desde:</label>
						<select class="form-control" name="txt_mes_desde_eess" id="txt_mes_desde_eess">
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_mes_hasta_eess">Hasta:</label>
						<select class="form-control" name="txt_mes_hasta_eess" id="txt_mes_hasta_eess">
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_tipo_reporte_eess">Tipo reporte:</label>
						<select name="txt_tipo_reporte_eess" id="txt_tipo_reporte_eess" class="form-control">
							<option value="1" selected>POR MES</option>
							<option value="2">ACUMULADO</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success btn-sm" id="btn-expor-eess"onclick="expor_opt('eess')"><i class="fa fa-save"></i> Exportar</button>
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
        </div>
	</div>
	</div>
</div>


<div class="modal fade" id="modal_ses_tot" role="dialog" data-backdrop="static">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h2 class="modal-title">EXPORTAR INFORME EESS TOTAL</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_id_dependencia_tot">RIS:</label>
						<select name="txt_id_dependencia_tot" id="txt_id_dependencia_tot" class="form-control select" style="width: 100%">
							<option value="">TODOS</option>
							<option value="1">RIS 1</option>
							<option value="2">RIS 2</option>
							<option value="3">RIS 3</option>
							<option value="4">RIS 4</option>
							<option value="5">RIS 5</option>
							<option value="6">RIS 6</option>
							<option value="7">RIS 7</option>							
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_anio_tot">Año:</label>
						<select class="form-control" name="txt_anio_tot" id="txt_anio_tot">
						<?php
							$year_init = 2022;
							$year_curent = date('Y');
							for ($i = $year_init; $i <= $year_curent; $i++) {
								echo "<option value='$i'"; if($year_curent == $i){ echo " selected";}  echo ">$i</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_mes_desde_tot">Desde:</label>
						<select class="form-control" name="txt_mes_desde_tot" id="txt_mes_desde_tot">
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="txt_mes_hasta_tot">Hasta:</label>
						<select class="form-control" name="txt_mes_hasta_tot" id="txt_mes_hasta_tot">
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'"; if($month_curent == $i){ echo " selected";}  echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_id_dependencia_eess">Tipo de atención:</label>
						<select name="txt_plan_tarifario_eess" id="txt_plan_tarifario_eess" class="form-control">
							<option value="" selected>TOTAL EXAMEN</option>
							<option value="1">AUS/SIS</option>
							<option value="2">PAGANTES</option>
							<option value="3">ESTRATEGIAS SANITARIAS</option>
							<option value="4">EXONERADOS</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success btn-sm" id="btn-expor-eess"onclick="expor_opt('tot')"><i class="fa fa-save"></i> Exportar</button>
			<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
        </div>
	</div>
	</div>
</div>


<?php require_once '../include/footer.php'; ?>
<script src="../../assets/plugins/multiselect/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../../assets/plugins/multiselect/bootstrap-multiselect.css" type="text/css"/>
<script Language="JavaScript">
var tipo_repor = '0';


$(function() {
	jQuery('#txt_cnt_sis_producto').keypress(function (tecla) {
		if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
		return false;
	});
	jQuery('#txt_cnt_parti_producto').keypress(function (tecla) {
		if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
		return false;
	});
	jQuery('#txt_cnt_est_producto').keypress(function (tecla) {
		if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
		return false;
	});
	jQuery('#txt_cnt_exo_producto').keypress(function (tecla) {
		if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
		return false;
	});
	
	/*$('#txt_id_producto').on("change", function(e) { 
		$('#txt_cnt_sis_producto').trigger('focus');
	});*/

	$('[name="opt_gestante"]').change(function(){
		if ($(this).is(':checked')) {
		  if($(this).val() == "1"){
			$("#txt_condicion_eg").prop('disabled', false);
			$("#txt_nro_eg").prop('disabled', false);
			$("#txt_condicion_eg").val('');
			$("#txt_nro_eg").val('');
		  } else {
			$("#txt_condicion_eg").prop('disabled', true);
			$("#txt_nro_eg").prop('disabled', true);
			$("#txt_condicion_eg").val('');
			$("#txt_nro_eg").val('');
		  }
		};
	});

	$('[name="chk_condicion_edad"]').change(function(){
		if ($(this).is(':checked')) {
			$("#txt_edad_desde").prop('disabled', false);
			$("#txt_edad_desde").val('');
			$("#txt_edad_hasta").prop('disabled', false);
			$("#txt_edad_hasta").val('');
			setTimeout(function(){$('#txt_edad_desde').trigger('focus');}, 2);
		} else {
			$("#txt_edad_desde").prop('disabled', true);
			$("#txt_edad_desde").val('');
			$("#txt_edad_hasta").prop('disabled', true);
			$("#txt_edad_hasta").val('');			
		}
	});
});

function edit_registro(id) {
  window.location = './main_editlaboratorio.php?id=' + id;
}

function totales() {
  var a = parseInt($("#txt_cnt_sis_producto").val());
  var b = parseInt($("#txt_cnt_pag_producto").val());
  var c = parseInt($("#txt_cnt_est_producto").val());
  var d = parseInt($("#txt_cnt_exo_producto").val());
  var total = a + b + c + d;
  $("#txt_cnt_total").val(total);
}


function open_ing_cantidad(){
	$("#txt_id_producto").val('').trigger("change");
	$("#txt_cnt_sis_producto").val('0');
	$("#txt_cnt_pag_producto").val('0');
	$("#txt_cnt_est_producto").val('0');
	$("#txt_cnt_exo_producto").val('0');
	$("#txt_cnt_total").val('0');
	
	$('#modal_add_examen').modal({
		show: true,
		backdrop: 'static',
		focus: true,
	});
}

function save_ing_cantidad() {
	$.ajax( {
		type: 'POST',
		url: '../../controller/ctrlLab.php',
		data: "id_cnt_prod_mes=" + $('#txt_id_cnt_prod_mes').val() + "&id_producto=" + $('#txt_id_producto').val() + "&anio=" + $('#txt_cnt_anio').val() + "&mes=" + $('#txt_cnt_mes').val() 
		+ "&cnt_sis_producto=" + $('#txt_cnt_sis_producto').val() + "&cnt_pag_producto=" + $('#txt_cnt_pag_producto').val() + "&cnt_est_producto=" + $('#txt_cnt_est_producto').val() + "&cnt_exo_producto=" + $('#txt_cnt_exo_producto').val() 
		+ "&cnt_total=" + $('#txt_cnt_total').val() + "&txtTipIng=S&accion=POST_ADD_REGCNTEXAMENPORMESANIO",
		success: function(data) {
			var tmsg = data.substring(0, 2);
			var lmsg = data.length;
			var msg = data.substring(3, lmsg);
			//console.log(tmsg);
			if(tmsg == "OK"){
				$('#modal_add_examen').modal("hide");
				buscar_cnt_produccion_por_mes();
			} else {
			  $('#btn-submit').prop("disabled", false);
			  showMessage(msg, "error");
			  return false;
			}
		}
	});
}

function buscar_datos() {
  $("#tblAtencion").dataTable().fnDraw();
}

function expor_ses_plano(opt) {
    var anio = $("#txt_bus_anio").val();
    var mes = $("#txt_bus_mes").val();

    $('#titleModalAlert').text('Mensaje de Alerta ...');

	if (anio == "") {
		$('#infoModalAlert').text('Seleccione AÑO');
		$('#alertModal').modal("show");
		return false;
	}
	if (mes == "") {
		$('#infoModalAlert').text('Seleccione MES DESDE');
		$('#alertModal').modal("show");
		return false;
	}

	if(opt == "LAB"){
		window.location = "csv_informe_lab.php?anio="+ anio + "&mes=" + mes;
	} else {
		window.location = "xls_informe_lab_tot.php?id_ris=" + id_dependencia + "&anio="+ anio + "&mes_desde=" + mes_desde + "&mes_hasta=" + mes_hasta;
	}
}


function expor_opt(opt) {
	var id_dependencia = $("#txt_id_dependencia_"+opt).val();
    var anio = $("#txt_anio_"+opt).val();
    var mes_desde = $("#txt_mes_desde_"+opt).val();
	var mes_hasta = $("#txt_mes_hasta_"+opt).val();
	var opcion_eess = $("#txt_tipo_reporte_"+opt).val();

    $('#titleModalAlert').text('Mensaje de Alerta ...');

	if (anio == "") {
		$('#infoModalAlert').text('Seleccione AÑO');
		$('#alertModal').modal("show");
		return false;
	}
	if (mes_desde == "") {
		$('#infoModalAlert').text('Seleccione MES DESDE');
		$('#alertModal').modal("show");
		return false;
	}
	if (mes_hasta == "") {
		$('#infoModalAlert').text('Seleccione MES HASTA');
		$('#alertModal').modal("show");
		return false;
	}
	mes_desde = parseInt(mes_desde);
	mes_hasta = parseInt(mes_hasta);
	
	if(mes_desde > mes_hasta){
		$('#infoModalAlert').text('El MES HASTA debe ser mayor o igual al MES DESDE');
		$('#alertModal').modal("show");
		return false;
	}
	
	if(opt == "eess"){
		if(opcion_eess == "1"){
			window.location = "xls_informe_lab_eess.php?id_dependencia=" + id_dependencia + "&anio="+ anio + "&mes_desde=" + mes_desde + "&mes_hasta=" + mes_hasta;
		} else {
			window.location = "xls_informe_lab_eess_acumulado.php?id_dependencia=" + id_dependencia + "&anio="+ anio + "&mes_desde=" + mes_desde + "&mes_hasta=" + mes_hasta;
		}
	} else {
		window.location = "xls_informe_lab_tot.php?id_ris=" + id_dependencia + "&anio="+ anio + "&mes_desde=" + mes_desde + "&mes_hasta=" + mes_hasta;
	}
}


function reg_informe() {
	$('#modal_rep_bac').modal({
		show: true,
		backdrop: 'static',
		focus: true,
	});
}

function expor_ses(opt) {
	if(opt == "EESS"){
		$('#modal_ses_eess').modal({
			show: true,
			backdrop: 'static',
			focus: true,
		});
	} else {
		$('#modal_ses_tot').modal({
			show: true,
			backdrop: 'static',
			focus: true,
		});		
	}
	
	//window.location = "./main_reglaboratorio.php";
}

function buscar_cnt_produccion_por_mes(){
  $.ajax({
    url: "../../controller/ctrlSes.php",
    type: "POST",
    data: {
      accion: 'GET_SEMAFOROLABPORDEPENDENCIA', anio: $("#txt_bus_anio").val(), mes: $("#txt_bus_mes").val(), id_dependencia: $("#txt_bus_id_dependencia").val()
    },
    success: function (registro) {
      $("#cntSIS").html(registro);
	  $("#fixTable").tableHeadFixer();
    }
  });
}

function save_delet_cantidad(id, cnt) {
	bootbox.confirm({
		message: "Se <b class='text-danger'>eliminará</b> la cantidad (<b>" + cnt + "</b>) del examen seleccionado.<br/>¿Está seguro de continuar?",
		buttons: {
		  confirm: {
			label: '<i class="fa fa-check"></i> Si',
			className: 'btn-success'
		  },
		  cancel: {
			label: '<i class="fa fa-times"></i> No',
			className: 'btn-danger'
		  }
		},
		callback: function (result) {
		  if (result == true) {
			var parametros = {
			  "accion": "POST_DELET_REGCNTEXAMENPORMESANIO",
			  "id_cnt_prod_mes": id
			};
			$.ajax({
			  data: parametros,
			  url: '../../controller/ctrlLab.php',
			  type: 'post',
			  success: function (rs) {
				buscar_cnt_produccion_por_mes();
			  }
			});
		  } else {
			
		  }
		}
	});
}


function expor_cantidad(id) {

  window.location = './xls_cnt_produccion.php?anio='+$("#txt_bus_anio").val()+'&mes='+$("#txt_bus_mes").val();
}


function back() {
  window.location = '../pages/';
}

function post(path, params, method='post') {
  // The rest of this code assumes you are not using a library.
  // It can be made less verbose if you use one.
  const form = document.createElement('form');
  form.method = method;
  form.action = path;

  for (const key in params) {
    if (params.hasOwnProperty(key)) {
      const hiddenField = document.createElement('input');
      hiddenField.type = 'hidden';
      hiddenField.name = key;
      hiddenField.value = params[key];

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}


let loginForm = document.getElementById("frm_main_reg");
loginForm.addEventListener("submit", (e) => {
  e.preventDefault();

  let anio = document.getElementById("txt_reg_anio");
  let mes = document.getElementById("txt_reg_mes");
  let id_dep = document.getElementById("txt_id_reg_dependencia");

  if (anio.value == "" || mes.value == "" || id_dep.value == "") {
	showMessage("Seleccione AÑO / MES / DEPENDENCIA", "error");
	return false;
  } else {
	  $.ajax({
		url: "../../controller/ctrlSes.php",
		type: "POST",
		data: {
		  accion: 'GET_VALIDAINF_DEPENMESYANIO', anio: $("#txt_reg_anio").val(), mes: $("#txt_reg_mes").val(), id_dependencia: $("#txt_id_reg_dependencia").val()
		},
		success: function (registro) {
			if(registro == "0"){
				//document.getElementById("frm_main_reg").submit();
				post('./main_reglaboratorio.php', {txt_reg_anio: $("#txt_reg_anio").val(), txt_reg_mes: $("#txt_reg_mes").val(), txt_id_reg_dependencia: $("#txt_id_reg_dependencia").val()});
			} else {
				showMessage("Informe ya fué registrado, verificar por favor...", "error");
				return false;				
			}
		}
	  });
  }
});


var dTable;
$(document).ready(function () {
	$("#fixTable").tableHeadFixer();
	
	$('#checkbox1').click(function() {
		if (!$(this).is(':checked')) {
			tipo_repor = '0';
		} else {
			tipo_repor = '1';
		}
		buscar_datos();
	});
	
	if( $('#checkbox1').prop('checked') ) {
		tipo_repor = '1';
	}
	
	buscar_cnt_produccion_por_mes();
	$("#txt_bus_id_dependencia").select2();
	$("#txt_id_reg_dependencia").select2();
	$("#txt_id_dependencia_eess").select2();
	$("#txt_id_dependencia_tot").select2();
	
	dTable = $('#tblAtencion').DataTable({
			//dom: 'Bltip',
		dom: 'Bltip',
		"buttons": [
			<?php if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "19") Or ($_SESSION['labIdRolUser'] == "20")){?>
		  {
			text: '<i class="glyphicon glyphicon-plus"></i> Registrar informe',
			className: "btn btn-primary",
			action: function ( e, dt, node, config ) {
			  reg_informe();
			}
		  },
			<?php }?>
		  {
			text: '<i class="glyphicon glyphicon-open"></i> Exportar consolidado EESS',
			className: "btn btn-success",
			action: function ( e, dt, node, config ) {
			  expor_ses('EESS');
			}
		  },
		  <?php
			if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "15") Or ($_SESSION['labIdRolUser'] == "19")){
		  ?>
		  {
			text: '<i class="glyphicon glyphicon-open"></i> Exportar consolidado Comparativo',
			className: "btn btn-warning",
			action: function ( e, dt, node, config ) {
			  expor_ses('TODOS');
			},
		  },
		  <?php
			}
			if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "15") Or ($_SESSION['labIdRolUser'] == "19")){
		  ?>
		  {
			text: '<i class="glyphicon glyphicon-open"></i> Exportar plano CSV',
			className: "btn btn-info",
			action: function ( e, dt, node, config ) {
			  expor_ses_plano('LAB');
			},
		  }
		  <?php
			}
		  ?>
		],
		"lengthMenu": [[25, 50, 100 ,250], [25, 50, 100 ,250]],
		"bLengthChange": true, //Paginado 10,20,50 o 100
		"bProcessing": true,
		"bServerSide": true,
		"bJQueryUI": false,
		"responsive": false,
		"bInfo": true,
		"bFilter": false,
		"sAjaxSource": "tbl_informe_lab.php", // Load Data
		"language": {
		  "url": "../../assets/plugins/datatables/Spanish.json",
		  "lengthMenu": '_MENU_ entries per page',
		  "search": '<i class="fa fa-search"></i>',
		  "paginate": {
			"previous": '<i class="fa fa-angle-left"></i>',
			"next": '<i class="fa fa-angle-right"></i>'
		  }
		},
		"sServerMethod": "POST",
		"fnServerParams": function (aoData)
		{
			aoData.push({"name": "tipo_repor", "value": tipo_repor});
			aoData.push({"name": "anio", "value": $("#txt_bus_anio").val()});
			aoData.push({"name": "mes", "value": $("#txt_bus_mes").val()});
			aoData.push({"name": "id_dependencia", "value": $("#txt_bus_id_dependencia").val()});
		},
		"columnDefs": [
			{"orderable": true, "targets": 0, "searchable": true, "class": "small"},
			{"orderable": false, "targets": 1, "searchable": false, "class": "small text-center"},
			{"orderable": true, "targets": 2, "searchable": false, "class": "small text-center"},
			{"orderable": false, "targets": 3, "searchable": false, "class": "small text-right"},
			{"orderable": true, "targets": 4, "searchable": false, "class": "small text-right"},
			{"orderable": false, "targets": 5, "searchable": false, "class": "small text-right"},
			{"orderable": true, "targets": 6, "searchable": false, "class": "small text-right"},
			{"orderable": false, "targets": 7, "searchable": false, "class": "small text-center"},
			{"orderable": false, "targets": 8, "searchable": false, "class": "small text-center"}
		]
	});

$('#tblAtencion').removeClass('display').addClass('table table-hover table-bordered');

});
</script>
<?php require_once '../include/masterfooter.php'; ?>
