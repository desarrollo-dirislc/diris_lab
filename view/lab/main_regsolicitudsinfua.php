<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Tipo.php';
$t = new Tipo();
require_once '../../model/Dependencia.php';
$d = new Dependencia();
require_once '../../model/Area.php';
$a = new Area();
require_once '../../model/Grupo.php';
$g = new Grupo();
require_once '../../model/Componente.php';
$c = new Componente();
require_once '../../model/Cpt.php';
$cpt = new Cpt();
require_once '../../model/Ubigeo.php';
$ub = new Ubigeo();
require_once '../../model/Producto.php';
$p = new Producto();
require_once '../../model/Servicio.php';
$se = new Servicio();
require_once '../../model/Tarifa.php';
$ta = new Tarifa();
?>
<style>
.panel-prime {
  background-color: #ecf0f5 !important;
}
.box-body {
  padding: 15px;
}

.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
}

.ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 160px;   
    padding: 4px 0;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #ccc;
    border-color: rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    *border-right-width: 2px;
    *border-bottom-width: 2px;
	cursor:pointer; cursor: hand;
}

.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
	cursor:pointer; cursor: hand;
}

.ui-state-hover, .ui-state-active {
    color: #ffffff;
    text-decoration: none;
    background-color: #0088cc;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    background-image: none;
	cursor:pointer; cursor: hand;
}
</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>REGISTRO DE ATENCION</strong></h3>
    </div>
    <div class="panel-body">
      <form name="frmPaciente" id="frmPaciente">
        <input type="hidden" name="txtIdAtencion" id="txtIdAtencion" value="0"/>
        <input type="hidden" name="txtShowOptPrint" id="txtShowOptPrint" value=""/>
        <input type="hidden" name="txtIdPer" id="txtIdPer" value="0"/>
        <input type="hidden" name="txtIdSoli" id="txtIdSoli" value="0"/>
		<b>Los datos con <span class="text-danger">(*)</span> son obligatorios.</b>
        <div class="row">
          <div class="col-sm-5">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title"><strong>Datos del paciente</strong></h3>
              </div>
              <div class="box-body">
				<?php include './main_reglab_dato_personal.php'?>
              </div>
            </div>
          </div><!-- Fin Datos Personales -->
		  <div class="col-sm-7">
            <div class="box box-primary" style="margin-bottom: 2px !important;">
              <div class="box-header with-border">
                <h3 class="box-title"><strong>Datos de la atención</strong></h3>
              </div>
              <div class="box-body" style="padding-top: 0px !important; padding-bottom: 2px !important;">
                <div class="row">
                  <div class="form-group col-sm-4 col-md-3">
                    <label for="txtIdPlanTari"><small>Origen de atención / Plan tarifa<span class="text-danger">(*)</span>:</small></label>
                    <select class="form-control input-lg" name="txtIdPlanTari" id="txtIdPlanTari" onkeydown="campoSiguiente('txtFechaAten', event);" disabled>
                      <option value="">-- Seleccione --</option>
                      <?php
                      $rsTa = $ta->get_listaTarifaPorIdDep($labIdDepUser);
                      foreach ($rsTa as $rowTa) {
                        echo "<option value='" . $rowTa['id_plan'] . "#" . $rowTa['check_precio'] . "'>" . $rowTa['nom_plan'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group col-sm-4 col-md-3">
                    <label for="txtFechaAten">Fecha cita <span class="text-danger">(*)</span>:</label>
                    <div class="input-group input-group-lg">
                      <div class="input-group-addon" for="txtFechaAten"><i class="fa fa-calendar" for="txtFechaAten" onclick="show_calendario();"></i></div>
                      <input type="text" name="txtFechaAten" placeholder="DD/MM/AAAA" id="txtFechaAten" autofocus="" class="form-control" maxlength="10" value="<?php echo date("d/m/Y") ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask onkeydown="campoSiguiente('txtIdTipAtencion', event);" disabled/>
                      <input type="hidden" name="txtHoraAten" placeholder="HH:MM" id="txtHoraAten" onfocus="this.select()" class="form-control" maxlength="5" value="<?php echo date("h:i") ?>" data-inputmask="'mask': '99:99'" onkeydown="campoSiguiente('txtPesoPac', event);"/>
                    </div>
                  </div>
                  <div class="form-group col-sm-4 col-md-2">
                    <label for="txtNroRefAtencion"><small>Cnt. cita fec. <span class="text-danger">(*)</span>:</small></label>
                    <input type="text" name="txtNroRefAtencion" id="txtNroRefAtencion" class="form-control input-lg" maxlength="20" disabled/>
                  </div>
				  <div class="form-group col-sm-12 col-md-4">
					<div class="checkbox">
					<label>
						<input type="checkbox" name="txtPersonalSalud" id="txtPersonalSalud" value="1" disabled> ¿Paciente es<br/>personal de salud?
					</label>
					</div>
				  </div>
                </div>
                <div style="display: none;">
				<div class="form-group">
				  <div class="col-sm-6 col-md-3">
                    <label for="txtIdTipAtencion">Tipo de atención <span class="text-danger">(*)</span>:</label>
                    <select class="form-control input-sm" name="txtIdTipAtencion" id="txtIdTipAtencion" onchange="change_tipoatencion()" onkeydown="campoSiguiente('txtAtenUrgente', event);" disabled>
                      <option value="">-- Seleccione --</option>
                      <option value="1" selected>AMBULATORIA</option>
                      <option value="2">REFERENCIA EXTERNA</option>
                      <option value="4">REFERENCIA INTERNA</option>
                      <option value="3">URGENCIA</option>
                    </select>
                  </div>
                  
                  <div class="col-sm-6 col-md-2">
                    <label for="txtNroRefDep">Nro. referencia:</label>
                    <input type="text" name="txtNroRefDep" id="txtNroRefDep" class="form-control input-sm" maxlength="20" readonly/>
                  </div>
				  <div class="col-sm-6 col-md-2">
                    <label for="txtAnioRefDep">Año referencia:</label>
                    <input type="text" name="txtAnioRefDep" id="txtAnioRefDep" class="form-control input-sm" maxlength="20" readonly/>
                  </div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-sm-12 col-md-3">
                    <label for="txtIdServicio">Servicio de procedencia<span class="text-danger">(*)</span>:</label>
                    <select class="form-control input-sm" style="width: 100%" name="txtIdServicio" id="txtIdServicio" onkeydown="campoSiguiente('txtDirPac', event);" disabled>
                      <option value="" selected>-- Seleccione --</option>
                      <?php
                      $rsSe = $se->get_listaServicioPorIdDep($labIdDepUser);
                      foreach ($rsSe as $rowSe) {
                        echo "<option value='" . $rowSe['id_servicio'] . "'>" . $rowSe['nom_servicio'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
				  <div class="form-group col-sm-6 col-md-6">
                    <label for="txtNombreMedico">Nombre(s) del médico<span class="text-danger">(*)</span>:</label>
                    <input type="text" name="txtNombreMedico" id="txtNombreMedico" class="form-control input-sm text-uppercase" maxlength="170" value="" disabled/>
					<span class="help-block">Ingrese primero los apellidos luego los nombres</span>
                  </div>
				  <div class="form-group col-sm-6 col-md-3">
                    <label for="txtFechaPedido">Fecha orden médico:</label>
                    <div class="input-group input-group-sm" id='datetimepicker2'>
                      <div class="input-group-addon" for="txtFechaPedido"><i class="fa fa-calendar" for="txtFechaPedido"></i></div>
                      <input type="text" name="txtFechaPedido" placeholder="DD/MM/AAAA" id="txtFechaPedido" autofocus="" class="form-control" maxlength="10" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask onkeydown="campoSiguiente('txtNombreMedico', event);" value="" disabled/>
                    </div>
                  </div>
                </div>
				<fieldset class="scheduler-border">
				<legend class="scheduler-border" style="margin-bottom: 0px;">Datos adicionales (<span class="text-primary" id="show-datos-adicionales-aten" style="cursor: pointer;" onclick="show_datos_adicionales_aten()">Mostrar</span>)</legend>
				<div id="datos-adicionales-aten" style="display: none;">
				<div class="row">
				<div class="col-sm-7">
                    <label for="txtIdDepRef">EESS procedencia:</label>
                    <?php $rsD = $d->get_listaDepenInstitucion(); ?>
                    <select name="txtIdDepRef" id="txtIdDepRef" style="width:100%;" class="form-control"  onkeydown="campoSiguiente('txtNroRefDep', event);">
                      <option value="" selected>-- Seleccione --</option>
                      <?php
                      foreach ($rsD as $row) {
                        echo "<option value='" . $row['id_dependencia'] . "'>" . $row['nom_depen'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
				</div>
					<div class="row">
					  <div class="col-sm-3 col-md-2">
						<div class="checkbox">
						  <label>
							<input type="checkbox" name="txtIdGestante" id="txtIdGestante" value="1" disabled> ¿Es gestante?
						  </label>
						</div>
					  </div>
					  <div class="col-sm-4 col-md-2">
						<label for="txtEdadGest">Edad Gest.:</label>
						<input type="text" name="txtEdadGest" id="txtEdadGest" class="form-control input-sm" maxlength="25" onkeydown="campoSiguiente('txtFechaParto', event);" disabled="">
					  </div>
					  <div class="col-sm-5 col-md-3">
						<label for="txtFechaParto">Fec. Prob. de parto:</label>
						<div class="input-group input-group-sm">
						  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
						  <input type="text" name="txtFechaParto" id="txtFechaParto" placeholder="DD/MM/AAAA" class="form-control" maxlength="20" value="" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask="" data-date-end-date="0d" disabled="">
						</div>
					  </div>
					</div>
					<div class="row">
						<div class="col-sm-3 col-md-2">
							<label for="txtPesoPac">Peso (<span class="text-danger"><b>Kg</b></span>):</label>
							<input type="text" name="txtPesoPac" id="txtPesoPac" onfocus="this.select()" class="form-control" maxlength="6" onkeydown="campoSiguiente('txtTallaPac', event);" onkeypress="keyValidNumberDecimalTwo(this.id);" onblur="keyValidNumberDecimalTwo(this.id);" value=""/>
						</div>
						<div class="col-sm-3 col-md-2">
							<label for="txtTallaPac">Talla (<span class="text-danger"><b>Cm</b></span>):</label>
							<input type="text" name="txtTallaPac" id="txtTallaPac" onfocus="this.select()" class="form-control" maxlength="3" onkeydown="campoSiguiente('txtPAPac', event);" onkeypress="keyValidNumber(this.id);" onblur="keyValidNumber(this.id);" value=""/>
						</div>
						<div class="col-sm-3 col-md-3"><br/>
							<div class="checkbox">
							  <label>
								<input type="checkbox" name="txtAtenUrgente" id="txtAtenUrgente" value="1" onkeydown="campoSiguiente('txtCodSIS', event);" disabled> ¿Atención urgente?
							  </label>
							</div>
						</div>
					</div>
				</div>
				</fieldset><!-- fin datos de atención-->
              </div>
            </div><!-- fin datos de la atención-->
			<div class="box box-success">
			  <div class="box-header with-border">
				<h3 class="box-title"><strong>Lista de examenes solicitados <span class="text-danger">(*)</span>:</strong></h3>
			  </div>
			  <div class="box-body" style="padding-top: 0px !important;">
				<div class="row">
				  <div class="col-sm-8 col-md-9">
					<select class="form-control" name="txtIdProducto" id="txtIdProducto" style="width: 100%" disabled>
					  <option value="">--Seleccione--</option>
					  <?php
					  $rsP = $p->get_listaProductoLaboratorioPorIdDep($labIdDepUser, 1);
					  foreach ($rsP as $rowP) {
						echo "<option value='" . $rowP['id_producto'] . "#".$rowP['codref_producto']."#".$rowP['nomtipo_producto']."#".$rowP['prec_sis']."#".$rowP['prec_parti']."'>" . $rowP['nom_producto'] . "</option>";
					  }
					  ?>
					</select>
				  </div>
				  <div class="col-sm-4 col-md-3">
					<button type="button" class="btn btn-sm pull-left btn-success" style="margin-bottom: 15px;" onclick="addRow('')"><i class="glyphicon glyphicon-plus"></i> Agregar Producto</button>
				  </div>
				</div>
				<table class="table table-striped table-bordered table-hover" id="tblDet">
				  <thead class="bg-green">
					<tr>
					  <th><small>CODIGO CPMS</small></th>
					  <th><small>EXAMEN SOLICITADO</small></th>
					  <th><small>TIPO</small></th>
					  <th><small>PRECIO<small></th>
					  <th class="text-center" style="width: 45px;"><small><i class="fa fa-cogs"></i></small></th>
					</tr>
				  </thead>
				  <tbody></tbody>
				  <tfoot>
					<th colspan="3" class="text-center"><small>Sub Total</small></th>
					<th colspan="2" class="text-right"><small>S/ <span id="totPrecProd">0.0000</span></small></th>
				  </tfoot>
				</table>
				<div class="row">
				  <div class="col-sm-3">
				  <br/>
					<b><i id="nro_examen">0</i></b> Items
				  </div>
				  <div class="col-sm-3">
					<label for="txtPorDescuentoMonto"><small>% Descuento <span class="text-danger">(*)</span></small></label>
					<div class="input-group input-group-sm">
					  <div class="input-group-addon" for="txtPorDescuentoMonto">%</div>
					  <input type="text" name="txtPorDescuentoMonto" id="txtPorDescuentoMonto" onfocus="this.select()" class="form-control" maxlength="6" onkeypress="return keyValidNumberDecimalTwo('txtPorDescuentoMonto');" onkeyup="tot_con_descuento();" value="0.00" onblur="poner_cero('txtPorDescuentoMonto')" disabled/>
					</div>
				  </div>
				  <div class="col-sm-3">
					<label for="txtDescuentoMonto"><small>Descuento <span class="text-danger">(*)</span></small></label>
					<div class="input-group input-group-sm">
					  <div class="input-group-addon" for="txtDescuentoMonto"><i class="glyphicon glyphicon-triangle-bottom" for="txtDescuentoMonto"></i></div>
					  <input type="text" name="txtDescuentoMonto" id="txtDescuentoMonto" onfocus="this.select()" class="form-control" maxlength="7" value="0.0000" onkeypress="return keyValidNumberDecimalFour('txtDescuentoMonto');" onkeyup="tot_con_descuento_manual();" onblur="poner_cero('txtDescuentoMonto')" disabled/>
					</div>
				  </div>
				  <div class="col-sm-3">
					<label for="txtTotalMonto"><small><b> Total S/. <span class="text-danger">(*)</span></b></small></label>
					<div class="input-group input-group-sm">
					  <div class="input-group-addon" for="txtTotalMonto"><i class="glyphicon glyphicon-text-size" for="txtTotalMonto"></i></div>
					  <input type="text" name="txtTotalMonto" id="txtTotalMonto" class="form-control" maxlength="7" value="0.0000" disabled/>
					</div>
				  </div>
				</div>
			  </div>
			</div>
          </div>
        </div>
        <div class="row" style="display: none;">
          <div class="col-sm-12">
            <div class="box box-warning">
              <div class="box-header with-border">
                <h3 class="box-title"><strong>Detalle del examen</strong></h3>
              </div>
              <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                  <thead class="bg-yellow">
                    <tr>
                      <th>CPT</th>
                      <th>Preparación del paciente</th>
                      <th>Insumos</th>
                      <th>Observación</th>
                    </tr>
                  </thead>
                  <tbody id="det-producto">
                    <tr>
                      <td colspan="4">Seleccione un exámen</td>
                    </tr>
                  </tbody>
                </table>
				<div class="col-xs-12">
					<div id="det-examen-producto"></div>
				</div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="panel-footer">
      <div class="row">
        <div class="col-md-12 text-center">
          <div id="saveAtencion">
            <div class="btn-group">
              <button class="btn btn-primary btn-lg" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="validForm('R')" disabled><i class="fa fa-save"></i>  Guardar  </button>
				<!--<button class="btn btn-success btn-lg" id="btn-print-aten" onclick="expor_atenciones_hoy('<?php echo date("d/m/Y")?>')"><i class="glyphicon glyphicon-open"></i>Ver atenciones ingresadas hoy</button>-->
              <a href="./main_principalsoli.php" class="btn btn-lg btn-default"><i class="glyphicon glyphicon-log-out"></i> Cancelar</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="showBuscarModal" role="dialog" aria-labelledby="showBuscarModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="showBuscarModalLabel">Citas</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<div class="col-sm-2">
				<select class="form-control" name="txtBusAnio" id="txtBusAnio" onchange="busca_calendariocita_mes_anio();">
				<?php
					$year_init = 2022;
					$year_curent = date('Y') + 1;
					for ($i = $year_init; $i <= $year_curent; $i++) {
						echo "<option value='$i'"; if(date('Y') == $i){ echo " selected";}  echo ">$i</option>";
					}
				?>
				</select>
			</div>
			<div class="col-sm-3">
				<select class="form-control" name="txtBusMes" id="txtBusMes" onchange="busca_calendariocita_mes_anio();">
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
			<div class="col-sm-7 pull-right text-right">
				<span>
					<small class="label bg-blue" style="margin-right: 5px;">Cnt. Citados</small>
					<small class="label bg-green" style="margin-right: 5px;">Atendidos</small> 
					<small class="label bg-yellow" style="margin-right: 5px;">No atendidos</small> 
				</span>
			</div>
		</div>
		<hr/>
		<br/>
		<div id="calendario"></div>
      </div>
	  <div class="modal-footer">
		<button class="btn btn-default btn-block" type="button" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar Ventana</button>
	  </div>
    </div>
  </div>
</div>
<div id="mostrar_datospac" class="modal fade" role="dialog" data-backdrop="static"></div>

<?php require_once '../include/footer.php'; ?>
<script src="../../assets/plugins/jQuery/jquery.ui.autocomplete.js"></script>
<script src="../../assets/plugins/jQuery/jquery-ui.js"></script>
<script src="../../assets/js/consulta_persona.js?v=<?php echo rand(); ?>"></script>
<script src="main_reglab.js?v=<?php echo rand(); ?>"></script>
<script Language="JavaScript">

function show_calendario(){
	$('#showBuscarModal').modal({
		show: true,
		backdrop: 'static',
		focus: true,
	});
}

function desabilita_datosgene(){
  $('#txtIdTipDoc').prop("disabled", false);
  $('#txtNroDoc').prop("disabled", false);
  $('#btnPacSearch').prop("disabled", false);
  $('#txtNroHC').prop("disabled", false);
  $('#txtNroTelFijoPac').prop("disabled", false);
  $('#txtNroTelMovilPac').prop("disabled", false);
  $('#txtEmailPac').prop("disabled", false);

  setTimeout(function(){$('#txtNroDoc').trigger('focus');}, 2);
}

function habilita_atencionubi(){
  $('#txtIdAvDirPac').prop("disabled", false);
  $('#txtNomAvDirPac').prop("disabled", false);
  $('#txtNroDirPac').prop("disabled", false);
  $('#txtIntDirPac').prop("disabled", false);
  $('#txtDptoDirPac').prop("disabled", false);
  $('#txtMzDirPac').prop("disabled", false);
  $('#txtLtDirPac').prop("disabled", false);
  $('#txtIdPoblaDirPac').prop("disabled", false);
  $('#txtNomPoblaDirPac').prop("disabled", false);
  $('#txtDirRefPac').prop("readonly", false);

  $('#txtIdPlanTari').prop("disabled", false);
  $('#txtIdServicio').prop("disabled", false);
  $('#txtFechaAten').prop("disabled", false);
  $('#txtIdTipAtencion').prop("disabled", false);
  $('#txtIdGestante').prop("disabled", false);
  $('#txtPersonalSalud').prop("disabled", false);
  $('#txtAtenUrgente').prop("disabled", false);
  $('#txtFechaPedido').prop("disabled", false);
  $('#txtNombreMedico').prop("disabled", false);
  
  $('#txtIdProducto').prop("disabled", false);
  $('#cbx_id_perfil').prop("disabled", false);

  $('#txtPesoPac').prop("disabled", false);
  $('#txtTallaPac').prop("disabled", false);
  $('#txtPAPac').prop("disabled", false);
  
  $('#txtPorDescuentoMonto').prop("disabled", false);
  $('#txtDescuentoMonto').prop("disabled", false);

}

function habilita_atencion(){
  $('#txtIdPlanTari').prop("disabled", false);
  $('#txtFechaAten').prop("disabled", false);
  $('#txtIdTipAtencion').prop("disabled", false);
  $('#txtIdProducto').prop("disabled", false);
  setTimeout(function(){$('#txtIdPlanTari').trigger('focus');}, 2);
}

function compledir(ord){
  var txtIdAvDirPac = "";
  var txtNomAvDirPac = "";
  var txtNroDirPac = "";
  var txtIntDirPac = "";
  var txtDptoDirPac = "";
  var txtMzDirPac = "";
  var txtLtDirPac = "";
  var txtIdPoblaDirPac = "";
  var txtNomPoblaDirPac = "";

  if($("#txtNomAvDirPac").val() != "") {
    var cmbid_av = $("#txtIdAvDirPac option:selected").val();
    var datos = cmbid_av.split("#");
    var id_av = datos[0];
    var abrev_av = datos[1];
    txtNomAvDirPac = abrev_av + " "+$("#txtNomAvDirPac").val();
  }

  if($("#txtNroDirPac").val() != "") {
    txtNroDirPac =  " NRO. " + $("#txtNroDirPac").val();
  }

  if($("#txtIntDirPac").val() != "") {
    txtIntDirPac =  " INT. " + $("#txtIntDirPac").val();
  }

  if($("#txtDptoDirPac").val() != "") {
    txtDptoDirPac =  " DTO. " + $("#txtDptoDirPac").val();
  }

  if($("#txtMzDirPac").val() != "") {
    txtMzDirPac =  " MZ. " + $("#txtMzDirPac").val();
  }

  if($("#txtLtDirPac").val() != "") {
    txtLtDirPac =  " LT. " + $("#txtLtDirPac").val();
  }

  if($("#txtNomPoblaDirPac").val() != "") {
    var cmbid_po = $("#txtIdPoblaDirPac option:selected").val();
    var datospo = cmbid_po.split("#");
    var id_po = datospo[0];
    var abrev_po = datospo[1];
    txtNomPoblaDirPac =  " " + abrev_po + " "+$("#txtNomPoblaDirPac").val();
  }

  var dirpac = txtNomAvDirPac + txtNroDirPac + txtIntDirPac + txtDptoDirPac + txtMzDirPac + txtLtDirPac + txtNomPoblaDirPac;
  dirpac = dirpac.trim();
  $("#txtDirPac").val(dirpac);
}

function maxlength_doc_bus() {

  $("#txtIdPac").val('0');

  $("#txtNomPac").val('');
  $("#txtPriApePac").val('');
  $("#txtSegApePac").val('');
  //$("#txtIdSexoPac").val('');
  $("#txtFecNacPac").val('');
  $("#txtEdadPac").val('');
  $("#txtNroTelFijoPac").val('');
  $("#txtNroTelMovilPac").val('');
  $("#txtEmailPac").val('');
  $("#txtNroHCPac").val('');

  $("#txtIdPaisNacPac").val('').trigger("change");
  $("#txtIdEtniaPac").val('58').trigger("change");
  $("#txtUBIGEOPac").val('').trigger("change");
  $("#txtDirPac").val('');
  $("#txtDirRefPac").val('');

  if ($("#txtIdTipDocPac").val() == "7") {
    $("#txtNroDocPac").val('SD0000000000000');
    $("#txtNroDocPac").prop("disabled", false);
    $("#btnPacSearch").prop("disabled", true);
	$("#txtNroHCPac").prop("disabled", false);
    $("#txtNomPac").prop("readonly", false);
    $("#txtPriApePac").prop("readonly", false);
    $("#txtSegApePac").prop("readonly", false);
    $("#txtIdSexoPac").prop("disabled", false);
    $("#txtFecNacPac").prop("disabled", false);
    $("#txtIdPaisNacPac").prop("disabled", false);
    $("#txtIdEtniaPac").prop("disabled", false);

    $('#txtIdTipDocSoliT').prop("disabled", false);
    $('#txtNroDocSoliT').prop("disabled", false);
    $('#btnSoliTSearch').prop("disabled", false);
	
	$('#txtNroTelMovilPac').prop("disabled", false);
	$('#txtUBIGEOPac').prop("disabled", false);
	$('#txtEmailPac').prop("disabled", false);
	
	bootbox.alert("Puedes ingresar el número que indica en la hoja FUA o dejarlo así para que el aplicativo genere un correlativo interno...");
	$("#txtNroDocPac").attr('maxlength', '15');
    setTimeout(function(){$('#txtNroHCPac').trigger('focus');}, 2);
  } else {
    $("#txtNroDocPac").val('');
    $("#txtNroDocPac").prop("disabled", false);
    $("#btnPacSearch").prop("disabled", false);
    $("#txtNomPac").prop("readonly", true);
    $("#txtPriApePac").prop("readonly", true);
    $("#txtSegApePac").prop("readonly", true);
    //$("#txtIdSexoPac").prop("disabled", true);
    $("#txtFecNacPac").prop("disabled", true);
    $("#txtIdPaisNacPac").prop("disabled", true);
    $("#txtIdEtniaPac").prop("disabled", true);

    $('#txtIdTipDocSoliT').prop("disabled", true);
    $('#txtNroDocSoliT').prop("disabled", true);
    $('#btnSoliTSearch').prop("disabled", true);

    if ($("#txtIdTipDocPac").val() == "1"){
      $("#txtNroDocPac").attr('maxlength', '8');
    } else {
      $("#txtNroDocPac").attr('maxlength', '15');
    }
    setTimeout(function(){$('#txtNroDocPac').trigger('focus');}, 2);
  }
}

function campoSiguiente(campo, evento) {
  if (evento.keyCode == 13 || evento.keyCode == 9) {
    if (campo == 'btnPacSearch') {
      buscar_datos_personales('reglaboratorio');
    } else if (campo == 'btnSoliSearch') {
      buscar_datos_personalessoli('reglaboratorio');
    } else if (campo == 'txtUBIGEOPac') {
      $('#txtUBIGEOPac').select2('open');
    } else if (campo == 'txtAtenUrgente') {
      $('#txtIdProducto').select2('open');
    } else if (campo == 'txtFechaAten') {
      //buscar_datos_sis();
      setTimeout(function(){$('#txtFechaAten').trigger('focus');}, 2);
    } else if (campo == 'txtIdSexoPac') {
      if ($('#txtIdSexoPac').val() == ""){
        setTimeout(function(){$('#txtIdSexoPac').trigger('focus');}, 2);
      } else {
        if ($('#txtFecNacPac').val() == ""){
          setTimeout(function(){$('#txtFecNacPac').trigger('focus');}, 2);
        } else {
          if ($('#txtNomPac').val() != ""){
            setTimeout(function(){$('#txtNroTelFijoPac').trigger('focus');}, 2);
          } else {
            document.getElementById(campo).focus();
            evento.preventDefault();
          }
        }
      }
    } else if (campo == 'txtNomPac') {
      if ($('#txtNomPac').val() != ""){
        setTimeout(function(){$('#txtNroTelFijoPac').trigger('focus');}, 2);
      } else {
        document.getElementById(campo).focus();
        evento.preventDefault();
      }
    } else if (campo == 'txtIdTipDocSoli') {
      if ($('#txtEdadPac').val() != "") {
        var edad = parseInt($('#txtEdadPac').val(), 10);
        if (edad >= 17){
          habilita_atencion();
        } else {
          document.getElementById(campo).focus();
          evento.preventDefault();
        }
      } else {
        document.getElementById(campo).focus();
        evento.preventDefault();
      }
    }  else if (campo == 'txtIdPlanTari') {
      habilita_atencion();
    } else {
      document.getElementById(campo).focus();
      evento.preventDefault();
    }
  }
}

$(document).ready(function() {
	obtener_nroatencion('<?php echo date("d/m/Y"); ?>')
	
	busca_calendariocita_mes_anio();

	$('#txtFecNacPac').datetimepicker({
	locale: 'es',
	format: 'L'
	});
	$('#txtFecNacPac').inputmask();

	$('#txtFechaAten').datetimepicker({
	locale: 'es',
	format: 'L'
	});
	$('#txtFechaAten').inputmask();

	$('#datetimepicker2').datetimepicker({
		locale: 'es',
		format: 'L'
	});
	$('#txtFechaPedido').inputmask();

	$("#txtIdPaisNacPac").select2();
	$("#txtIdEtniaPac").select2();
	$("#txtUBIGEOPac").select2();
	$("#txtIdPaisNacSoli").select2();
	$("#txtIdServicio").select2();
	$("#txtIdDepRef").select2();
	$("#txtIdProducto").select2();//{closeOnSelect: false}
	$("#txtIdParenSoli").select2();

	$("body").tooltip({ selector: '[data-toggle=tooltip]' });

	$("#txtFecNacPac").focusout(function () {
	fecha_fin = '<?php echo date("d/m/Y")?>';//$("#txtFecNacPac").val();
	fecha_ini = $(this).val();
	if(fecha_ini != ""){
	  $.post("../../controller/ctrlTipo.php", { fecha_ini: fecha_ini, fecha_fin: fecha_fin, accion: "GET_FUNC_CALCULAEDAD" }, function(data){
		var datos = eval(data);
		$("#txtEdadPac").val(datos[0]);
	  });
	} else {
	  //setTimeout(function(){$('#txtFecNacPac').trigger('focus');}, 2);
	}
	});

	$("#txtFechaAten").focusout(function () {
	fechaaten = $(this).val();
	if(fechaaten != ""){
		obtener_nroatencion(fechaaten);
	} else {
		$("#txtNroRefAtencion").val('');
	}
	});
	
	$("#txtNombreMedico").autocomplete({
		source: "../../controller/ctrlLab.php?accion=GET_MEDICO_POR_EESS",
		minLength: 3,
		select: function (event, ui) {
		}
	});
});

</script>
<?php require_once '../include/masterfooter.php'; ?>
