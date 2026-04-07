<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Dependencia.php';
$d = new Dependencia();
require_once '../../model/Levey.php';
$le = new Levey();
?>
<div class="container-fluid">
	<div class="panel-prime">
		<div class="panel-heading">
		  <h3 class="panel-title"><strong>MANTENIMIENTO DE CONTROL DE CALIDAD INTERNO</strong></h3>
		</div>
		<div class="panel-body">
		  <div class="row">
			<div class="col-sm-3">
			  <div class="box box-success">
				<br/>
				<div class="box-body box-profile">
				  <h3 class="profile-username text-center" id="titleAcc">Nuevo Control</h3>
				  <form id="frmLevey" name="frmLevey" class="form-horizontal">
					<input type="hidden" name="txt_id_levey" id="txt_id_levey" value="0"/>
					<input type="hidden" name="txt_id_levey_modal" id="txt_id_levey_modal" value="0"/>
					<input type="hidden" name="txt_id_control_calidad_modal" id="txt_id_control_calidad_modal" value="0"/>
					<div class="form-group">
					  <div class="col-sm-8">
						  <label for="txt_id_control_calidad">Prueba:</label>
							<?php $rsLe = $le->get_listaControlCalidad(); ?>
							<select name="txt_id_control_calidad" id="txt_id_control_calidad" class="form-control" style="width: 100%">
								<option value="">-- Seleccione --</option>
								<?php
								foreach ($rsLe as $row) {
									echo "<option value='" . $row['id'] . "'>" . $row['nombre_control'] . " - " . $row['nombre_tipo'] . "</option>";
								}
								?>
							</select>
					  </div>
					  <div class="col-sm-4">
						  <label for="txt_lote">Lote</label>
						  <input type="text" class="form-control input-sm" name="txt_lote" id="txt_lote" autocomplete="off" maxlength="75"/>
						  <span class="help-block">Hasta 75 caracteres</span>
					  </div>
					</div>
					<div class="form-group">
					  <div class="col-sm-6">
						  <label for="txt_ds">DS <span class="help-block-label">(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_ds" id="txt_ds" autocomplete="off" maxlength="15" onkeypress="keyValidNumberDecimalThree(this.id);" onblur="keyValidNumberDecimalThree(this.id);" onkeyup="tot_con_descuento();"/>
						  <span class="help-block">Hasta 3 decimales</span>
					  </div>
					  <div class="col-sm-6">
						  <label for="txt_media">Media <span class="help-block-label">(2)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_media" id="txt_media" autocomplete="off" maxlength="15" onkeypress="keyValidNumberDecimalTwo(this.id)" onblur="keyValidNumberDecimalTwo(this.id)" onkeyup="tot_con_descuento();"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					</div>
					<div class="form-group">
					  <div class="col-sm-6">
						  <label for="txt_x_1ds_posi">X+1DS <span class="help-block-label">(2)+(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_1ds_posi" id="txt_x_1ds_posi" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					  <div class="col-sm-6">
						  <label for="txt_x_1ds_nega">X-1DS <span class="help-block-label">(2)-(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_1ds_nega" id="txt_x_1ds_nega" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					</div>
					<div class="form-group">
					  <div class="col-sm-6">
						  <label for="txt_x_2ds_posi">X+2DS <span class="help-block-label">(2)+2*(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_2ds_posi" id="txt_x_2ds_posi" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					  <div class="col-sm-6">
						  <label for="txt_x_2ds_nega">X-2DS <span class="help-block-label">(2)-2*(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_2ds_nega" id="txt_x_2ds_nega" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					</div>
					<div class="form-group">
					  <div class="col-sm-6">
						  <label for="txt_x_3ds_posi">X+3DS <span class="help-block-label">(2)+3*(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_3ds_posi" id="txt_x_3ds_posi" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					  <div class="col-sm-6">
						  <label for="txt_x_3ds_nega">X-3DS <span class="help-block-label">(2)-3*(1)</span></label>
						  <input type="text" class="form-control input-sm" name="txt_x_3ds_nega" id="txt_x_3ds_nega" autocomplete="off" maxlength="15" readonly="true"/>
						  <span class="help-block">Hasta 2 decimales</span>
					  </div>
					</div>
					<hr/>
					<button type="button" class="btn btn-primary btn-block" id="btnValidForm" onclick="save_form()"><i class="fa fa-save"></i> Guardar </button>
					<div id="show-new" style="display:none; margin-top:5px;">
					  <button type="button" class="btn btn-success btn-block" id="btnNewForm" onclick="nuevo_registro()"><i class="glyphicon glyphicon-plus"></i> Nuevo Control </button>
					</div>
				  </form>
				</div>
			  </div>
			</div>
			<div class="col-sm-9">
			  <div class="box box-primary">
				<div class="box-body box-profile">
				
				<form id="frmBusLevey" name="frmBusLevey" class="form-horizontal">
					<div class="form-group">
					  <div class="col-sm-4">
						  <label for="txt_bus_id_control_calidad">Buscar prueba:</label>
							<?php $rsLe = $le->get_listaControlCalidad(); ?>
							<select name="txt_bus_id_control_calidad" id="txt_bus_id_control_calidad" class="form-control" style="width: 100%" onchange="buscar_datos()">
								<option value="">-- Seleccione --</option>
								<?php
								foreach ($rsLe as $row) {
									echo "<option value='" . $row['id'] . "'>" . $row['nombre_control'] . " - " . $row['nombre_tipo'] . "</option>";
								}
								?>
							</select>
					  </div>
					  <div class="col-sm-2">
						  <label for="txt_lote">Buscar lote:</label>
						  <input type="text" class="form-control input-sm" name="txt_bus_lote" id="txt_bus_lote" autocomplete="off" maxlength="75" oninput="buscar_datos()"/>
					  </div>
					</div>
				  </form>
				
					<br/>
					<table id="tblAtencion" class="display" cellspacing="0" width="100%">
					  <thead>
						<tr>
						  <th>TIPO</th>
						  <th>PRUEBA</th>
						  <th>LOTE</th>
						  <th>DS</th>
						  <th>X+3DS</th>
						  <th>X+2DS</th>
						  <th>X+1DS</th>
						  <th>MEDIA</th>
						  <th>X-1DS</th>
						  <th>X-2DS</th>
						  <th>X-3DS</th>
						  <th>Estado</th>
						  <th style="width: 60px;"><i class="fa fa-cogs"></i></th>
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
		<div class="modal-footer">
		  <button type="button" class="btn btn-info pull-right" id="btnBackForm" onclick="back()"><i class="glyphicon glyphicon-log-out"></i> Ir al Menú</button>
		</div>
	</div>
</div>

<div class="modal fade" id="showLisDepModal" tabindex="-1" role="dialog" aria-labelledby="showLisDepModalLabel">
	<div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="showLisDepModalLabel"></h4>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 5px;">
            <button class="btn btn-primary btn-sm" onclick="reg_dependencia()"><i class="glyphicon glyphicon-plus"></i> Agregar Dependencia</button>
          </div>
          <div id="datos-lis-dep" style="height: 250px;">
            <table id="fixTableDep" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th><small>DEPENDENCIA</small></th>
                  <th><small>AÑO</small></th>
                  <th><small>MES</small></th>
                  <th><small>&nbsp;</small></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-md-12 text-center">
              <div class="btn-group">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Aceptar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
	</div>
</div>

<div class="modal fade" id="showDepModal" role="dialog" aria-labelledby="showDepModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="showDepModalLabel">Agregar Dependencia</h4>
		  </div>
		  <div class="modal-body">
			<form name="frmDepLevey" id="frmDepLevey">
			  <input type="hidden" name="txtIdLeveyDep" id="txtIdLeveyDep" value="0"/>
			  <div class="form-group">
				<label for="txtIdDep">Dependencia:</label>
				<?php $rsD = $d->get_listaDepenInstitucion(); ?>
				<select name="txtIdDep" id="txtIdDep" class="form-control select" multiple data-mdb-clear-button="true">
				  <?php
				  foreach ($rsD as $row) {
					echo "<option value='" . $row['id_dependencia'] . "'>" . $row['nom_depen'] . "</option>";
				  }
				  ?>
				</select>
			  </div>
			  <div class="form-group">
				<div class="row">
				  <div class="col-sm-6">
					<label for="txt_anio">Año</label>
					<select class="form-control" name="txt_anio" id="txt_anio">
						<option value=""> -- Seleccione -- </option>
						<?php
							$year_init = 2022;
							$year_curent = (int)(date('Y')) + 1;
							for ($i = $year_init; $i <= $year_curent; $i++) {
								echo "<option value='$i'>$i</option>";
							}
						?>
					</select>
				  </div>
				  <div class="col-sm-6">
					<label for="txt_mes">Mes</label>
					<select name="txt_mes" id="txt_mes" class="form-control select" multiple data-mdb-clear-button="true">
						<?php
							$month_curent = date('m');
							$meses_arr = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
							"Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

							for ($i = 1; $i <= count($meses_arr); $i++) {
								echo "<option value='$i'"; echo ">" . $meses_arr[$i - 1] . "</option>";
							}
						?>
					</select>
				  </div>
				</div>
			  </div>
		  </form>
		</div>
		<div class="modal-footer">
		  <div class="row">
			<div class="col-md-12 text-center">
			  <div class="btn-group">
				<button type="button" class="btn btn-primary btn-continuar" id="btnValidFormDep" onclick="save_form_dep()"><i class="fa fa-save"></i> Guardar </button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
</div>

<?php require_once '../include/footer.php'; ?>
<script src="../../assets/plugins/multiselect/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../../assets/plugins/multiselect/bootstrap-multiselect.css" type="text/css"/>
<script Language="JavaScript">

	function buscar_datos(){
		$('#tblAtencion').DataTable().ajax.reload();
	}

  /***********************************************************************************************************/
  /////////////////////////////////////////// Dependencia ////////////////////////////////////////////
  /**********************************************************************************************************/
	function open_dependencia(id, idcontrol, nomlevey){
		document.frmLevey.txt_id_levey_modal.value = id;
		document.frmLevey.txt_id_control_calidad_modal.value = idcontrol;
		carga_dependencia(id);

		$('#showLisDepModal').modal({
		  show: true,
		  backdrop: 'static',
		  focus: true,
		});

		$('#showLisDepModal').on('shown.bs.modal', function (e) {
		  var modal = $(this)
		  modal.find('.modal-title').text(nomlevey)
		})
	}

	function carga_dependencia(idlevey){
		$.ajax({
		  url: "../../controller/ctrlLevey.php",
		  type: "POST",
		  data: {
			accion: 'GET_SHOW_DEPPORIDLEVEY', id_levey: idlevey
		  },
		  success: function (registro) {
			$("#datos-lis-dep").html(registro);
		  }
		});
	}

	function reg_dependencia(){
		$('#btnValidFormDep').prop("disabled", false);
		document.frmDepLevey.txtIdLeveyDep.value = '0';
		$('#txtIdDep').multiselect('deselectAll', false);
		$('#txtIdDep').multiselect('updateButtonText');
		$('#txtIdDep').multiselect('enable');
		document.frmDepLevey.txt_anio.value = '';
		document.frmDepLevey.txt_mes.value = '';
		
		document.frmDepLevey.txt_mes.value = '0';
		$('#txt_mes').multiselect('deselectAll', false);
		$('#txt_mes').multiselect('updateButtonText');
		$('#txt_mes').multiselect('enable');
		
		$('#showDepModal').modal({
		  show: true,
		  backdrop: 'static',
		  focus: true,
		});
	}

	function save_form_dep() {
		$('#btnValidFormDep').prop("disabled", true);
		var msg = "";
		var sw = true;

		var idDep = $('#txtIdDep').val();
		var anio = $('#txt_anio').val();
		var mes = $('#txt_mes').val();

		if(idDep === null){ msg+= "Seleccione una Depedencia<br/>"; sw = false;}
		if(anio == ""){ msg+= "Seleccione el año<br/>"; sw = false;}
		if(mes === null){ msg+= "Seleccione el mes<br/>"; sw = false;}

		if (sw == false) {
			bootbox.alert(msg);
			$('#btnValidFormDep').prop("disabled", false);
			return false;
		}
		//alert(idDep);
		bootbox.confirm({
		  message: "Se registrará la Depedencia seleccionada, ¿Está seguro de continuar?",
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
			  var myRand = parseInt(Math.random() * 999999999999999);
			  var form_data = new FormData();
			  form_data.append('accion', 'POST_REGLEVEYDEPENDENCIA');
			  form_data.append('id_levey', $("#txt_id_levey_modal").val());
			  form_data.append('id_levey_dep', $("#txtIdLeveyDep").val());
			  form_data.append('id_dependencia', idDep.join());
			  form_data.append('anio', $("#txt_anio").val());
			  form_data.append('mes', mes.join());
			  form_data.append('id_control_calidad', $("#txt_id_control_calidad_modal").val());
			  form_data.append('rand', myRand);
			  $.ajax( {
				url: '../../controller/ctrlLevey.php',
				dataType: 'text', // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'POST',
				success: function(data) {
				  var tmsg = data.substring(0, 2);
				  var lmsg = data.length;
				  var msg = data.substring(3, lmsg);
				  //console.log(tmsg);
				  if(tmsg == ""){
					$("#showDepModal").modal('hide');
					carga_dependencia($("#txt_id_levey_modal").val());
					$("#tblAtencion").dataTable().fnDraw();
				  } else {
					showMessage(msg, "error");
					$('#btnValidFormDep').prop("disabled", false);
					return false;
				  }
				  $('#btnValidFormDep').prop("disabled", false);
				}
			  });
			} else {
			  $('#btnValidFormDep').prop("disabled", false);
			}
		  }
		});
	}
	

	function eliminar_levey_dep(id) {
		bootbox.confirm({
		  message: "Se cambiará el estado, ¿Está seguro de continuar?",
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
			  var myRand = parseInt(Math.random() * 999999999999999);
			  var form_data = new FormData();
			  form_data.append('accion', 'POST_DELLEVEYDEPENDENCIA');
			  form_data.append('id_levey_dep', id);
			  form_data.append('rand', myRand);
			  $.ajax( {
				url: '../../controller/ctrlLevey.php',
				dataType: 'text', // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'POST',
				success: function(data) {
				  var tmsg = data.substring(0, 2);
				  var lmsg = data.length;
				  var msg = data.substring(3, lmsg);
				  if(tmsg == ""){
					carga_dependencia($("#txt_id_levey_modal").val());
					$("#tblAtencion").dataTable().fnDraw();
					showMessage("Registro eliminado correctamente", "success");
				  } else {
					showMessage(msg, "error");
				  }
				}
			  });
			}
		  }
		});
	}
	
  
  /**********************************************************************************************************************/
  ////////////////////////////////////////////// Mantenimiento ///////////////////////////////////////////////
  /**********************************************************************************************************************/

	function tot_con_descuento(){
		var ds = validateNumber($("#txt_ds").val());
		var media = validateNumber($("#txt_media").val());
		
		var x_1ds_posi =  (media + ds);
		var x_1ds_nega =  (media - ds);
		var x_2ds_posi =  media + 2 * ds;
		var x_2ds_nega =  media - 2 * ds;
		var x_3ds_posi =  media + 3 * ds;
		var x_3ds_nega =  media - 3 * ds;
		
		$("#txt_x_1ds_posi").val(x_1ds_posi.toFixed(2));
		$("#txt_x_1ds_nega").val(x_1ds_nega.toFixed(2));
		$("#txt_x_2ds_posi").val(x_2ds_posi.toFixed(2));
		$("#txt_x_2ds_nega").val(x_2ds_nega.toFixed(2));
		$("#txt_x_3ds_posi").val(x_3ds_posi.toFixed(2));
		$("#txt_x_3ds_nega").val(x_3ds_nega.toFixed(2));
		//alert(x_3ds_nega);
	}


  function edit_registro(data) {
    $('#show-new').show();
    $('#titleAcc').text('Editar Control');
	$("#txt_id_control_calidad").val(data.id_control_calidad).trigger("change");
	document.frmLevey.txt_id_levey.value = data.id;
	document.frmLevey.txt_lote.value = data.nro_lote;
	document.frmLevey.txt_ds.value = data.ds;
	document.frmLevey.txt_media.value = data.media;
	document.frmLevey.txt_x_1ds_posi.value = data.x_1ds_posi;
	document.frmLevey.txt_x_1ds_nega.value = data.x_1ds_nega;
	document.frmLevey.txt_x_2ds_posi.value = data.x_2ds_posi;
	document.frmLevey.txt_x_2ds_nega.value = data.x_2ds_nega;
	document.frmLevey.txt_x_3ds_posi.value = data.x_3ds_posi;
	document.frmLevey.txt_x_3ds_nega.value = data.x_3ds_nega;
  }

  function nuevo_registro(){
    $('#show-new').hide();
    $('#titleAcc').text('Nuevo Control');
	$("#txt_id_control_calidad").val('').trigger("change");
	document.frmLevey.txt_id_levey.value = '0';
	document.frmLevey.txt_lote.value = '';
	document.frmLevey.txt_ds.value = '';
	document.frmLevey.txt_media.value = '';
	document.frmLevey.txt_x_1ds_posi.value = '';
	document.frmLevey.txt_x_1ds_nega.value = '';
	document.frmLevey.txt_x_2ds_posi.value = '';
	document.frmLevey.txt_x_2ds_nega.value = '';
	document.frmLevey.txt_x_3ds_posi.value = '';
	document.frmLevey.txt_x_3ds_nega.value = '';
  }

  function save_form() {
    $('#btnValidForm').prop("disabled", true);
    var msg = "";
    var sw = true;

	var id_levey = document.frmLevey.txt_id_levey.value;
	var id_control_calidad = $("#txt_id_control_calidad").val();
	var lote = document.frmLevey.txt_lote.value;
	var ds = document.frmLevey.txt_ds.value;
	var media = document.frmLevey.txt_media.value;
	var x_1ds_posi = document.frmLevey.txt_x_1ds_posi.value;
	var x_1ds_nega = document.frmLevey.txt_x_1ds_nega.value;
	var x_2ds_posi = document.frmLevey.txt_x_2ds_posi.value;
	var x_2ds_nega = document.frmLevey.txt_x_2ds_nega.value;
	var x_3ds_posi = document.frmLevey.txt_x_3ds_posi.value;
	var x_3ds_nega = document.frmLevey.txt_x_3ds_nega.value;

    if(id_control_calidad == ""){ msg+= "Selecione PRUEBA<br/>"; sw = false;}
	if(lote == ""){ msg+= "Ingrese el LOTE<br/>"; sw = false;}
	if(ds == ""){ msg+= "Ingrese el valor de la DESVIACION ESTANDAR (DS)<br/>"; sw = false;}
	if(media == ""){ msg+= "Ingrese el valor de la MEDIA<br/>"; sw = false;}
	if(validateNumber(ds) == 0){ msg+= "Ingrese un valor numérico en la DESVIACION ESTANDAR<br/>"; sw = false;}
	if(validateNumber(media) == 0){ msg+= "Ingrese un valor numérico en la DESVIACION ESTANDAR<br/>"; sw = false;}


    if (sw == false) {
      bootbox.alert(msg);
      $('#btnValidForm').prop("disabled", false);
      return false;
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
          var myRand = parseInt(Math.random() * 999999999999999);
          var form_data = new FormData();
          form_data.append('accion', 'POST_REGLEVEY');
		  form_data.append('id_levey', id_levey);
          form_data.append('id_control_calidad', id_control_calidad);
          form_data.append('lote', lote);
          form_data.append('ds', ds);
          form_data.append('media', media);
		  form_data.append('x_1ds_posi', x_1ds_posi);
		  form_data.append('x_1ds_nega', x_1ds_nega);
		  form_data.append('x_2ds_posi', x_2ds_posi);
		  form_data.append('x_2ds_nega', x_2ds_nega);
		  form_data.append('x_3ds_posi', x_3ds_posi);
		  form_data.append('x_3ds_nega', x_3ds_nega);
          form_data.append('rand', myRand);
          $.ajax( {
            url: '../../controller/ctrlLevey.php',
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success: function(data) {
              var tmsg = data.substring(0, 2);
              var lmsg = data.length;
              var msg = data.substring(3, lmsg);
              //console.log(tmsg);
              if(tmsg == ""){
                $("#tblAtencion").dataTable().fnDraw();
                nuevo_registro();
				showMessage("Registro guardado correctamente", "success");
              } else {
				showMessage(msg, "error");
              }
              $('#btnValidForm').prop("disabled", false);
            }
          });
        } else {
          $('#btnValidForm').prop("disabled", false);
        }
      }
    });
  }

  function back() {
    window.location = '../pages/';
  }

  $(document).ready(function () {
	$("#fixTableDep").tableHeadFixer();
	  
    $("#txt_id_control_calidad").select2();
	$("#txt_bus_id_control_calidad").select2();
	
	var dTable = $('#tblAtencion').DataTable({
      "lengthMenu": [[15, 25, 50, 100 ,250], [15, 25, 50, 100 ,250]],
      "bLengthChange": true, //Paginado 10,20,50 o 100
      "bProcessing": true,
      "bServerSide": true,
      "bJQueryUI": false,
      "responsive": true,
      "bInfo": true,
      "bFilter": false,
      "sAjaxSource": "tbl_principal.php", // Load Data
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
        aoData.push({"name": "id_control_calidad", "value": $("#txt_bus_id_control_calidad").val()});
		aoData.push({"name": "nro_lote", "value": $("#txt_bus_lote").val()});
      },
      "columnDefs": [
        {"orderable": false, "targets": 0, "searchable": false, "class": "small"},
        {"orderable": false, "targets": 1, "searchable": false, "class": "small"},
        {"orderable": false, "targets": 2, "searchable": true, "class": "small"},
        {"orderable": false, "targets": 3, "searchable": false, "class": "small text-right"},
        {"orderable": false, "targets": 4, "searchable": false, "class": "small text-right"},
        {"orderable": false, "targets": 5, "searchable": false, "class": "small text-right"},
        {"orderable": false, "targets": 6, "searchable": false, "class": "small text-right"},
		{"orderable": false, "targets": 7, "searchable": false, "class": "small text-right"},
		{"orderable": false, "targets": 8, "searchable": false, "class": "small text-right"},
		{"orderable": false, "targets": 9, "searchable": false, "class": "small text-right"},
		{"orderable": false, "targets": 10, "searchable": false, "class": "small text-right"},
		{"orderable": false, "targets": 11, "searchable": false, "class": "small text-center"},
		{"orderable": false, "targets": 12, "searchable": false, "class": "small text-center"}
      ]
    });

    $('#tblAtencion').removeClass('display').addClass('table table-hover table-bordered');
	
	var multiselect_options = {
		enableFiltering: true,
		includeSelectAllOption: true,
		selectAllName: 'select-all-name',
		nSelectedText: 'Seleccionados',
		nonSelectedText: 'Seleccionar',
		allSelectedText: 'TODOS',
		filterPlaceholder: 'Buscar',
		selectAllText: 'SELECCIONAR TODOS',
		buttonClass: 'btn input-sm',
		inheritClass: true,
		maxHeight: 170,
		buttonWidth: '100%',
		widthSynchronizationMode: 'ifPopupIsSmaller'
	};
	$('#txtIdDep').multiselect(multiselect_options);
	$('#txt_mes').multiselect(multiselect_options);
	
  });
  </script>
  <?php require_once '../include/masterfooter.php'; ?>
