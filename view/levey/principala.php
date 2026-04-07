<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Levey.php';
$le = new Levey();
require_once '../../model/Dependencia.php';
$d = new Dependencia();

$a_date = date("Y-m-d");
$fecIni = date("01/m/Y", strtotime($a_date));
$fecActu = date("d/m/Y");
?>
<style>
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

td.details-control {
  background: url('../../assets/images/details_open.png') no-repeat center center;
  cursor: pointer;
}
tr.shown td.details-control {
  background: url('../../assets/images/details_close.png') no-repeat center center;
}


hr {
  margin-top: 10px;
  margin-bottom: 5px;
  border: 0;
    border-top-color: currentcolor;
    border-top-style: none;
    border-top-width: 0px;
  border-top: 1px solid #eee;
}

.p-xs {
  padding: 10px;
}

</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>CONTROL DE CALIDAD INTERNO</strong></h3>
    </div>
    <div class="panel-body">
		<div class="row">
			<input type="hidden" name="txt_id" id="txt_id" value="0"/>
			<div class="col-sm-2">
				<div class="form-group">
					<label for="anio">Año:</label>
					<select class="form-control" name="txtBusAnio" id="txtBusAnio" onchange="nueva_busqueda();">
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
			<div class="col-sm-3">
				<div class="form-group">
					<label for="mes">Mes:</label>
					<select class="form-control" name="txtBusMes" id="txtBusMes" onchange="nueva_busqueda();">
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
			<div class="col-sm-3">
				<div class="form-group">
					<label for="txt_id_dependencia">Dependencia:</label>
					<?php $rsD = $d->get_listaDepenInstitucion(); ?>
					<select name="txt_id_dependencia" id="txt_id_dependencia" class="form-control select" style="width: 100%" <?php if($_SESSION['labIdRolUser'] <> "1"){ if($_SESSION['labIdRolUser'] <> "15"){ if($_SESSION['labIdRolUser'] <> "2"){ echo "disabled";}}}?> onchange="nueva_busqueda();">
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
		<hr/>
		
		<div class="panel with-nav-tabs panel-primary">
			<div class="panel-heading">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">COLESTEROL</a></li>
						<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">TRIGLICERIDOS</a></li>
						<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">GLUCOSA</a></li>
						<li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">TGO</a></li>
						<li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">TGP</a></li>
						<li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">FOSFATASA ALCALINA</a></li>
						<li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">ACIDO URICO</a></li>
						<li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">BILIRRUBINA TOTAL</a></li>
						<li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="false">BILIRRUBINA DIRECT</a></li>
						<li class=""><a href="#tab_10" data-toggle="tab" aria-expanded="false">UREA</a></li>
						<li class=""><a href="#tab_11" data-toggle="tab" aria-expanded="false">CREATININA</a></li>
						<li class=""><a href="#tab_12" data-toggle="tab" aria-expanded="false">PROTEINAS</a></li>
						<li class=""><a href="#tab_13" data-toggle="tab" aria-expanded="false">ALBUMINA</a></li>
						<li class=""><a href="#tab_14" data-toggle="tab" aria-expanded="false">HDL</a></li>
						<li class=""><a href="#tab_15" data-toggle="tab" aria-expanded="false">LDL</a></li>
					</ul>
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<?php include "principal_colesterol.php" ?>
					</div>
					<div class="tab-pane" id="tab_2">
						<?php include "principal_trigliceridos.php" ?>
					</div>
					<div class="tab-pane" id="tab_3">
						<?php include "principal_glucosa.php" ?>
					</div>
					<div class="tab-pane" id="tab_4">
						<?php include "principal_tgo.php" ?>
					</div>
					<div class="tab-pane" id="tab_5">
						<?php include "principal_tgp.php" ?>
					</div>
					<div class="tab-pane" id="tab_6">
						<?php include "principal_fosfatasa.php" ?>
					</div>
					<div class="tab-pane" id="tab_7">
						<?php include "principal_acido.php" ?>
					</div>
					<div class="tab-pane" id="tab_8">
						<?php include "principal_bilirrubina_tot.php" ?>
					</div>
					<div class="tab-pane" id="tab_9">
						<?php include "principal_bilirrubina_dir.php" ?>
					</div>
					<div class="tab-pane" id="tab_10">
						<?php include "principal_urea.php" ?>
					</div>
					<div class="tab-pane" id="tab_11">
						<?php include "principal_creatinina.php" ?>
					</div>
					<div class="tab-pane" id="tab_12">
						<?php include "principal_proteina.php" ?>
					</div>
					<div class="tab-pane" id="tab_13">
						<?php include "principal_albumina.php" ?>
					</div>
					<div class="tab-pane" id="tab_14">
						<?php include "principal_hdl.php" ?>
					</div>
					<div class="tab-pane" id="tab_15">
						<?php include "principal_ldl.php" ?>
					</div>
				</div>
			</div>
		</div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" role="dialog" aria-labelledby="editModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="editModalLabel"></h4>
			</div>
			<div class="modal-body">
				<form name="frmEditar" id="frmEditar" class="form-horizontal">
					<input type="hidden" name="txt_id_control_calidad" id="txt_id_control_calidad" value="1"/>
					<input type="hidden" name="txt_fecha_editar" id="txt_fecha_editar" value=""/>
					<div class="form-group">
					<div class="col-sm-6">
						<label for="txtBusAnioAsis">Valor actual:</label>
						<input type="text" class="form-control pull-right input-sm" name="txt_valor_ante" id="txt_valor_ante" autocomplete="OFF" maxlength="6" value="" disabled/>
					</div>
					<div class="col-sm-6">
						<label for="txtBusAnioAsis">Valor nuevo:</label>
						<input type="text" class="form-control pull-right input-sm" name="txt_valor_nue" id="txt_valor_nue" autocomplete="OFF" maxlength="6" value=""/>
					</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<label for="txt_justificacion">Justificación acción correctiva:</label>
							<textarea class="form-control" name="txt_justificacion" id="txt_justificacion" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-primary btn-continuar" id="btnFrmSaveEdit" onclick="editar()"><i class="fa fa-save"></i> Continuar </button>
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="justificacionModal" role="dialog" aria-labelledby="justificacionModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="justificacionModalLabel">DETALLE DE REGISTRO FECHA: </h4>
			</div>
			<div class="modal-body">
				<div id="muestra-justificacion"></div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once '../include/footer.php'; ?>
<!-- EChart-->
<script src="../../assets/plugins/echarts/dist/echarts.min.js"></script>
<script src="../../assets/plugins/echarts/map/js/world.js"></script>
<script src="../../assets/plugins/echarts/dist/extension/echarts-gl.js"></script>
<script src="grafica_table.js"></script>

<script Language="JavaScript">


function nuevo(){
    $("#nuevoModal").modal({
        show: true,
        backdrop: 'static',
        focus: true,
    });
}

function open_edit(id, fecha, valor, opt){
	document.frmEditar.txt_valor_ante.value = valor;
	document.frmEditar.txt_id_control_calidad.value = opt;
	document.frmEditar.txt_fecha_editar.value = fecha;
	$("#txt_id").val(id);

    $("#editModalLabel").text("Acción correctiva - Fecha: " + fecha);
	  
    $("#editModal").modal({
        show: true,
        backdrop: 'static',
        focus: true,
    });
    $('#editModal').on('shown.bs.modal', function (e) {
        document.frmEditar.txt_valor_nue.value = '';
		document.frmEditar.txt_justificacion.value = '';
        $('#txt_valor_nue').trigger('focus');
    })
}


function open_detalle(id, fecha){
	$.ajax({
		url: "../../controller/ctrlLevey.php",
		type: "POST",
		data: {
		  accion: 'GET_SHOW_DETALLELEVEYPORID', id: id,
		},
		success: function (registro) {
			$("#muestra-justificacion").html(registro);
		}
	});
	$("#justificacionModalLabel").text("DETALLE DE REGISTRO FECHA: " + fecha);
	
    $("#justificacionModal").modal({
        show: true,
        backdrop: 'static',
        focus: true,
    });
}


function nueva_busqueda(){
	$("input[id*='txt_fecha']").val('01/'+$("#txtBusMes").val()+'/'+$("#txtBusAnio").val());
	$("input[id*='txt_fecha']").datepicker("update");

	for (var i = 1; i <= 30; i++) {
		eval("datos_ultimo_levey(" + i + ");");
		$("#tbl_" + i).dataTable().fnDraw();
		//eval("calc_coeficiente_variacion(" + i + ");");
		eval("filtrar(" + i + ");");
	}
}

function calc_coeficiente_variacion(tipo){
  $.ajax({
    url: "../../controller/ctrlLevey.php",
    type: "POST",
	dataType: "json",
    data: {
      accion: 'POST_CALCULA_COEFICIENTE_VARIACION', anio: $("#txtBusAnio").val(), mes: $("#txtBusMes").val(), id_control_calidad: tipo, id_dependencia: $("#txt_id_dependencia").val()
    },
    success: function (registro) {
		var datos = eval(registro);
		$("#valor_cv_"+tipo).text(datos[0]+'||'+datos[1]+'||'+datos[2]+'||'+datos[3]);
    }
  });
}

function registrar(tipo){
  $.ajax({
    url: "../../controller/ctrlLevey.php",
    type: "POST",
    data: {
      accion: 'POST_REGLEVEYDET', fecha: $("#txt_fecha_" + tipo).val(), valor: $("#txt_valor_" + tipo).val(), id_control_calidad: tipo, id_levey: $("#txt_id_levey_" + tipo).val()
    },
    success: function (registro) {
		if(registro == ""){
			$("#txt_valor_" + tipo).val('');
			$("#tbl_" + tipo).dataTable().fnDraw();
			eval("filtrar(" + tipo + ");");
			//eval("calc_coeficiente_variacion(" + tipo + ");");
			showMessage("Registro guardado correctamente", "success");
		} else {
			showMessage(registro, "error");
		}
    }
  });
}

function editar(){
	$('#btnFrmSaveEdit').prop("disabled", true);
	$.ajax({
		url: "../../controller/ctrlLevey.php",
		type: "POST",
		data: {
		  accion: 'POST_EDITLEVEYDET', id: $("#txt_id").val(), fecha: $("#txt_fecha_editar").val(), valor: $("#txt_valor_nue").val(), id_control_calidad: $("#txt_id_control_calidad").val(), justificacion: $("#txt_justificacion").val(),
		},
		success: function (registro) {
			$('#btnFrmSaveEdit').prop("disabled", false);
			if(registro == ""){
				$('#editModal').modal("hide");
				$("#tbl_" + $("#txt_id_control_calidad").val()).dataTable().fnDraw();
				eval("filtrar(" + $("#txt_id_control_calidad").val() + ");");
				//eval("calc_coeficiente_variacion(" + $("#txt_id_control_calidad").val() + ");");
				showMessage("Registro actualizado correctamente", "success");
			} else {
				showMessage(registro, "error");
			}
		},
		timeout: 12000, // sets timeout to 12 secunds
		error: function (request, status, err) {
			$('#btnFrmSaveEdit').prop("disabled", false);
			if (status == "timeout") {
				showMessage("Su petición demoro mas de lo permitido", "error");
			} else {
				// another error occured
				showMessage("ocurrio un error en su petición.", "error");
			}
		}
	});
}

function justificacion(justi){
	$("#muestra-justificacion").html(justi);
    $("#justificacionModal").modal({
        show: true,
        backdrop: 'static',
        focus: true,
    });
}

$(document).ready(function () {
	
	$("#txt_id_dependencia").select2();
	
    $("input[id*='txt_fecha']").datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        immediateUpdates: true
    });
	
	for (var i = 1; i <= 30; i++) {
		eval("datos_ultimo_levey(" + i + ");");
		eval("initDataTable(" + i + ");");
		//eval("calc_coeficiente_variacion(" + i + ");");
		eval("filtrar(" + i + ");");
	}
	//datos_ultimo_levey(1);

});
</script>
<?php require_once '../include/masterfooter.php'; ?>
