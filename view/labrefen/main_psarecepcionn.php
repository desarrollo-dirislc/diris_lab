<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Producto.php';
$pr = new Producto();
require_once '../../model/Dependencia.php';
$d = new Dependencia();
require_once '../../model/Lab.php';
$lab = new Lab();
?>
<style>
.label-primary {
    background-color: #1D71B8 !important;
}
.label-info {
    background-color: #00c0ef !important;
}
.label-success {
    background-color: #5cb85c !important;
}
.label-warning {
    background-color: #f0ad4e !important;
}
.label-danger {
    background-color: #d9534f !important;
}

/* Ajuste Select2 para que el texto quede centrado verticalmente */
.select2-container .select2-selection--single {
    height: 30px !important;
    border: 1px solid #ccc !important;
    border-radius: 0 !important;
}

.select2-selection__rendered {
    line-height: 25px !important;  /* <-- esto centra verticalmente */
    font-size: 12px;
    padding-left: 5px !important;
    padding-right: 20px !important;
}

/* Ajuste de la flecha */
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 30px !important;
    top: 0 !important;
}

</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>RECEPCIÓN DE MUESTRA POR CLASIFICAR</strong></h3>
    </div>
    <div class="panel-body">
      <input type="hidden" id="txt_id_registro" value=""/>
		<div class="nav-tabs-custom">
		  <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-por-recibir" data-toggle="tab"><i class="fa fa-envelope text-primary"></i> Por recibir</a></li>
        <li><a href="#tab-adecuadas" data-toggle="tab"><i class="fa fa-check text-success"></i> Adecuadas</a></li>
        <li><a href="#tab-observadas" data-toggle="tab"><i class="fa fa-ban text-danger"></i> Observadas</a></li>
		  </ul>
		  <div class="tab-content">
			<div class="tab-pane active" id="tab-por-recibir">
        <form name="frmBus" id="frmBus" class="form-horizontal">
          <div class="form-group">
            <div class="col-sm-3">
              <label for="txt_bus_id_producto_clasi"><small>Examen:</small></label>
              <?php $rsP = $pr->get_listaProductoLaboratorio(); ?>
              <select name="txt_bus_id_producto_clasi" id="txt_bus_id_producto_clasi" class="form-control" style="width: 100%" disabled="">
                <option value="0" selected>-- SELECCIONE --</option>
                <?php
                foreach ($rsP as $row) {
                  echo "<option value='" . $row['id_producto'] . "'"; 
                  if($row['id_producto'] == "60"){ echo " selected";}
                  echo">" . $row['nom_producto'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="txt_bus_id_dependencia_clasi"><small>Dependencia origen:</small></label>
              <?php $rsD = $d->get_listaDepenInstitucion(); ?>
              <select name="txt_bus_id_dependencia_clasi" id="txt_bus_id_dependencia_clasi" class="form-control" style="width: 100%" onchange="nueva_busqueda();">
                <option value="0" selected>-- TODOS --</option>
                <?php
                foreach ($rsD as $row) {
                  echo "<option value='" . $row['id_dependencia'] . "'>" . $row['nom_depen'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-info btn-sm btn-block" id="btn_bus_pendiente" onclick="nueva_busqueda();">Buscar</button>
            </div>
          </div>
        </form>
        <p class="text-right"><b>Acciones</b>: <input type="checkbox" checked>=Marcar como Adecuada</p>
        <!--<button type="button" class="btn btn-primary" id="tbn_env_adecuadas" onclick="validForm()">Enviar a adecuadas</button>-->
        <form class="" id="frm-example" method="POST">
            <table id="example" class="table table-hover table-bordered" cellspacing="0" width="100%">
              <thead class="bg-aqua">
                <tr>
                  <th></th>
                  <th></th>
                  <th>CÓDIGO<br/>ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA<br/>TOMA MUESTRA</th>
                  <th>FECHA<br/>ENVÍO</th>
                  <th>DÍAS<br/>TRANSCURRIDOS</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th></th>
                  <th>CÓDIGO<br/>ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA</br>TOMA MUESTRA</th>
                  <th>FECHA<br/>ENVÍO</th>
                  <th>DÍAS<br/>TRANSCURRIDOS</th>
                </tr>
              </tfoot>
            </table>
            <pre id="example-console-rows" style="display: none;"></pre>
        </form>
			</div>

			<div class="tab-pane" id="tab-adecuadas">
        <form name="frmBusAdecuadas" id="frmBusAdecuadas" class="form-horizontal" autocomplete="off">
          <div class="form-group">
            <div class="col-sm-3">
              <label for="txt_bus_id_producto_ade"><small>Examen:</small></label>
              <?php $rsP = $pr->get_listaProductoLaboratorio(); ?>
              <select name="txt_bus_id_producto_ade" id="txt_bus_id_producto_ade" class="form-control" style="width: 100%" disabled="">
                <option value="0" selected>-- SELECCIONE --</option>
                <?php
                foreach ($rsP as $row) {
                  echo "<option value='" . $row['id_producto'] . "'"; 
                  if($row['id_producto'] == "60"){ echo " selected";}
                  echo">" . $row['nom_producto'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="txt_bus_id_dependencia_ade"><small>Dependencia origen:</small></label>
              <?php $rsD = $d->get_listaDepenInstitucion(); ?>
              <select name="txt_bus_id_dependencia_ade" id="txt_bus_id_dependencia_ade" class="form-control" style="width: 100%">
                <option value="0" selected>-- TODOS --</option>
                <?php
                foreach ($rsD as $row) {
                  echo "<option value='" . $row['id_dependencia'] . "'>" . $row['nom_depen'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="txt_bus_datos_pac_ade"><small>Por Nro. Atención/Nombres/Nro. de Documento:</small></label>
              <input type="text" name="txt_bus_datos_pac_ade" id="txt_bus_datos_pac_ade" class="form-control form-control-sm" value="" />
              <p class="help-block">Digite mínimo dos caracteres.</p>
            </div>
            <div class="col-sm-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-success btn-sm btn-block" id="btn_bus_adecuadas_reset">Restablecer filtros</button>
            </div>
          </div>
        </form>
        <p></p>
        <table id="tbl_aceptados" class="table table-hover table-bordered" cellspacing="0" width="100%">
          <thead class="bg-green">
            <tr>
              <th>CÓDIGO<br/>ATENCIÓN</th>
              <th>NOMBRE DEL PACIENTE</th>
              <th>DEPENDENCIA ORIGEN</th>
              <th>FECHA</br>TOMA MUESTRA</th>
              <th>FECHA<br/>ENVÍO</th>
              <th>FECHA<br/>RECEPCIÓN</th>
              <th>ESTADO<br/>RESULTADO</th>
              <th><i class="fa fa-cogs"></i></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
			</div>

			<div class="tab-pane" id="tab-observadas">
        <form name="frmBusObservadas" id="frmBusObservadas" class="form-horizontal" autocomplete="off">
          <div class="form-group">
            <div class="col-sm-3">
              <label for="txt_bus_id_producto_obs"><small>Examen:</small></label>
              <?php $rsP = $pr->get_listaProductoLaboratorio(); ?>
              <select name="txt_bus_id_producto_obs" id="txt_bus_id_producto_obs" class="form-control" style="width: 100%" disabled="">
                <option value="0" selected>-- SELECCIONE --</option>
                <?php
                foreach ($rsP as $row) {
                  echo "<option value='" . $row['id_producto'] . "'"; 
                  if($row['id_producto'] == "60"){ echo " selected";}
                  echo">" . $row['nom_producto'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="txt_bus_id_dependencia_obs"><small>Dependencia origen:</small></label>
              <?php $rsD = $d->get_listaDepenInstitucion(); ?>
              <select name="txt_bus_id_dependencia_obs" id="txt_bus_id_dependencia_obs" class="form-control" style="width: 100%">
                <option value="0" selected>-- TODOS --</option>
                <?php
                foreach ($rsD as $row) {
                  echo "<option value='" . $row['id_dependencia'] . "'>" . $row['nom_depen'] . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-3">
              <label for="txt_bus_datos_pac_obs"><small>Por Nro. Atención/Nombres/Nro. de Documento:</small></label>
              <input type="text" name="txt_bus_datos_pac_obs" id="txt_bus_datos_pac_obs" class="form-control form-control-sm" value="" />
              <p class="help-block">Digite mínimo dos caracteres.</p>
            </div>
            <div class="col-sm-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-danger btn-sm btn-block" id="btn_bus_observadas_reset">Restablecer filtros</button>
            </div>
          </div>
        </form>
        <p></p>
        <table id="tbl_observadas" class="table table-hover table-bordered" cellspacing="0" width="100%">
			    <thead class="bg-red">
            <tr>
              <th>CÓDIGO<br/>ATENCIÓN</th>
              <th>NOMBRE DEL PACIENTE</th>
              <th>DEPENDENCIA ORIGEN</th>
              <th>FECHA</br>TOMA MUESTRA</th>
              <th>FECHA<br/>ENVÍO</th>
              <th>FECHA<br/>RECEPCIÓN</th>
              <th>MOTIVO<br/>OBSERVACIÓN</th>
              <th><i class="fa fa-cogs"></i></th>
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
<!-- modal -->
<div class="modal fade" id="modal_rechazar_muestra">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        <h4 class="modal-title" id="showComenModalLabel">RECHAZAR MUESTRA</h4>
      </div>
      <div class="modal-body">
        <form id="frm_motivo_rechazo">
          <div class="form-group">
            <label>Motivo:</label>
              <?php $rsMR = $lab->get_listaMotivoRechazoMuestra(60); ?>
              <select class="form-control" id="txt_id_motivo_rechazo" name="txt_id_motivo_rechazo">
                <option value="">-- SELECCIONE --</option>
                <?php
                foreach ($rsMR as $row) {
                  echo "<option value='" . $row['id'] . "'>" . $row['motivo'] . "</option>";
                }
                ?>
              </select>
          </div>
          <div class="form-group">
            <label>Detalle:</label>
            <textarea class="form-control" id="txt_motivo_rechazo" name="txt_motivo_rechazo" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
        <button type="button" class="btn btn-primary btn-continuar" id="btn_motivo_rechazo" onclick="rechazar_muestra()"><i class="fa fa-save"></i> Grabar</button>
      </div>
    </div>
  </div>
</div>

<?php require_once '../include/footer.php'; ?>
<script Language="JavaScript">
  var table;
  let resetInProgressAcep = false; //Para combo Adecuadas
  let resetInProgressObs = false; //Para combo Adecuadas

function abrirModalRechazo(id) {
    $('#txt_id_registro').val(id);
    $('#txt_id_motivo_rechazo').val('').change();
    $('#txt_motivo_rechazo').val('');
    $('#modal_rechazar_muestra').modal({
      backdrop: 'static',
      keyboard: false
    });
}

function open_pdfsinvalor(idSoli) {

  var urlwindow = "pdf_solisinvalor.php?id_solicitud=" + idSoli;
  day = new Date();
  id = day.getTime();
  Xpos = (screen.width / 2) - 390;
  Ypos = (screen.height / 2) - 300;
  eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function nueva_busqueda(){
	table.ajax.reload();
}

function nueva_busqueda_adecuada(){
	$("#tbl_aceptados").dataTable().fnDraw();
}

function nueva_busqueda_observada(){
	$("#tbl_observadas").dataTable().fnDraw();
}

function aceptar_muestra(id){
  var myRand = parseInt(Math.random() * 999999999999999);
  $.ajax( {
      type: 'POST',
      url: '../../controller/ctrlLab.php',
      data: {
        accion: 'POST_CLASIFICA_EXAMEN_ENVIADO',
        accion_sp: 'ACEPTAR',
        id: id,
        rand: myRand,
      },
      success: function(data) {
        var tmsg = data.substring(0, 2);
        var lmsg = data.length;
        var msg = data.substring(3, lmsg);
        //console.log(tmsg);
        if(tmsg == "OK"){
          table.ajax.reload();
          $("#tbl_aceptados").dataTable().fnDraw();
          $("#tbl_observadas").dataTable().fnDraw();
          showMessage("Se actualizó correctamente", "success");
        } else {
          bootbox.alert(msg);
          return false;
        }
      }
    });
}

function aceptar_muestra_desde_obs(id) {
  bootbox.confirm({
    message: "Se cambiará de estado, ¿Está seguro de continuar?",
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

        $.ajax( {
          type: 'POST',
          url: '../../controller/ctrlLab.php',
          data: {
            accion: 'POST_CLASIFICA_EXAMEN_ENVIADO',
            accion_sp: 'ACEPTAR',
            id: id,
            rand: myRand,
          },
          success: function(data) {
            var tmsg = data.substring(0, 2);
            var lmsg = data.length;
            var msg = data.substring(3, lmsg);
            //console.log(tmsg);
            if(tmsg == "OK"){
              table.ajax.reload();
              $("#tbl_aceptados").dataTable().fnDraw();
              $("#tbl_observadas").dataTable().fnDraw();
              showMessage("Muestras actualizada correctamente", "success");
            } else {
              bootbox.alert(msg);
              return false;
            }
          }
        });
      } else {
        $('#btnValidForm').prop("disabled", false);
      }
    }
  });
}

function rechazar_muestra(){
  $('#btn_motivo_rechazo').prop('disabled', true);
  var id = $('#txt_id_registro').val();
  var id_motivo_rechazo = $('#txt_id_motivo_rechazo').val();
  var motivo_rechazo = $('#txt_motivo_rechazo').val().trim();

  if (id_motivo_rechazo == ""){
    $('#btn_motivo_rechazo').prop('disabled', false);
    showMessage("Seleccione el motivo del rechazo", "warning");
    return false;
  }

  var myRand = parseInt(Math.random() * 999999999999999);
  $.ajax( {
          type: 'POST',
          url: '../../controller/ctrlLab.php',
          data: {
            accion: 'POST_CLASIFICA_EXAMEN_ENVIADO',
            accion_sp: 'RECHAZAR',
            id: id,
            id_motivo_rechazo: id_motivo_rechazo,
            motivo_rechazo: motivo_rechazo,
            rand: myRand,
          },
          success: function(data) {
            $('#btn_motivo_rechazo').prop('disabled', false);
            var tmsg = data.substring(0, 2);
            var lmsg = data.length;
            var msg = data.substring(3, lmsg);
            //console.log(tmsg);
            if(tmsg == "OK"){
              table.ajax.reload();
              $("#tbl_aceptados").dataTable().fnDraw();
              $("#tbl_observadas").dataTable().fnDraw();
              $('#modal_rechazar_muestra').modal('hide');
              showMessage("Se actualizó correctamente", "success");
            } else {
              bootbox.alert(msg);
              return false;
            }
          }
        });
}

function validForm() {
  //$('#btnValidForm').prop("disabled", true);
  var msg = "";
  var sw = true;

  var table = $('#example').DataTable();;
  var rows_selected = table.column(0).checkboxes.selected();

  // Output form data to a console
  $('#example-console-rows').text(rows_selected.join(","));

  var idpac = $('#txtIdPac').val();

  if(rows_selected.join(",") == ""){
    msg+= "Seleccione al menos una atención<br/>";
    sw = false;
  }

  if (sw == false) {
    bootbox.alert(msg);
    $('#btnValidForm').prop("disabled", false);
    return sw;
  } else {
    save_form();
  }
  return false;
}

function save_form() {
  bootbox.confirm({
    message: "Se enviaran las atenciones seleccionadas, ¿Está seguro de continuar?",
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

        $.ajax( {
          type: 'POST',
          url: '../../controller/ctrlLab.php',
          data: {
            accion: 'POST_CLASIFICA_EXAMEN_ENVIADO',
            accion_sp: 'ACEPTAR',
            id: $('#example-console-rows').text(),
            rand: myRand,
          },
          success: function(data) {
            var tmsg = data.substring(0, 2);
            var lmsg = data.length;
            var msg = data.substring(3, lmsg);
            //console.log(tmsg);
            if(tmsg == "OK"){
              table.ajax.reload();
              $("#tbl_aceptados").dataTable().fnDraw();
              $("#tbl_observadas").dataTable().fnDraw();
              showMessage("Muestras actualizada correctamente", "success");
            } else {
              bootbox.alert(msg);
              return false;
            }
          }
        });
      } else {
        $('#btnValidForm').prop("disabled", false);
      }
    }
  });
}

document.getElementById("txt_bus_datos_pac_ade").addEventListener("keyup", function () {
    let valor = this.value.trim();

    if (valor.length >= 2) {
        this.classList.remove("is-invalid");
    } else {
        this.classList.add("is-invalid");
    }

    // Ejecutar búsqueda al llegar a 3 caracteres
    if (valor.length >= 3) {
        nueva_busqueda_adecuada();
    } else if  (valor.length == 0) {
        nueva_busqueda_adecuada();
    }
});

document.getElementById("txt_bus_datos_pac_obs").addEventListener("keyup", function () {
    let valor = this.value.trim();

    if (valor.length >= 2) {
        this.classList.remove("is-invalid");
    } else {
        this.classList.add("is-invalid");
    }

    // Ejecutar búsqueda al llegar a 3 caracteres
    if (valor.length >= 3) {
        nueva_busqueda_observada();
    } else if  (valor.length == 0) {
        nueva_busqueda_observada();
    }
});

$("#txt_bus_id_dependencia_ade").on("change", function () {
    if (resetInProgressAcep) return;
    nueva_busqueda_adecuada();
});
$("#btn_bus_adecuadas_reset").on("click", function () {
    resetInProgressAcep = true;
    $("#txt_bus_id_dependencia_ade").val("0").change();
    $("#txt_bus_datos_pac_ade").val("");
    setTimeout(() => {
        $("#tbl_aceptados").dataTable().fnDraw();
        resetInProgressAcep = false;
    }, 100);
});

$("#txt_bus_id_dependencia_obs").on("change", function () {
    if (resetInProgressObs) return;
    nueva_busqueda_observada();
});
$("#btn_bus_observadas_reset").on("click", function () {
    resetInProgressObs = true;
    $("#txt_bus_id_dependencia_obs").val("0").change();
    $("#txt_bus_datos_pac_obs").val("");
    setTimeout(() => {
        $("#tbl_observadas").dataTable().fnDraw();
        resetInProgressObs = false;
    }, 100);
});

$(document).ready(function () {
	$("#txt_bus_id_dependencia_clasi, #txt_bus_id_producto_clasi, #txt_bus_id_dependencia_ade, #txt_bus_id_producto_ade, #txt_bus_id_dependencia_obs, #txt_bus_id_producto_obs").select2();

  table = $('#example').DataTable({
    dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>' +
         'rt' +
         '<"row mt-2"<"col-md-6"l><"col-md-6"p>>',

    buttons: [
        {
            text: '<i class="fa fa-mail-forward"></i> Enviar a Adecuadas',
            className: "btn btn-success",
            action: function (e, dt, node, config) {
                var rows_selected = table.column(0).checkboxes.selected();
                if (rows_selected.length === 0) {
                    bootbox.alert("Debe seleccionar al menos un registro.");
                    return;
                }

                var ids = [];
                $.each(rows_selected, function(index, rowId){
                    ids.push(rowId);
                });

                validForm(ids);
            }
        }
    ],

    processing: true,
    serverSide: false,
    deferLoading: 0,   // ← EVITA CARGA INICIAL

    ajax: {
        url: "tbl_psarecepcionn_clasi.php",
        type: "POST",
        data: function() {
            return {
                id_producto: $("#txt_bus_id_producto_clasi").val(),
                id_dependencia_origen: $("#txt_bus_id_dependencia_clasi").val()
            };
        },
        dataSrc: "aaData"
    },

    lengthMenu: [[50, 100 ,250], [50, 100 ,250, "All"]],
    responsive: true,
    bFilter: true,

    language: {
        url: "../../assets/plugins/datatables/Spanish.json",
        search: "Buscar:"
    },

    columnDefs: [
        {targets: 0, checkboxes: {selectRow: true}, className: "text-center"},
        {orderable: false, targets: 1, searchable: true, className: "text-center"},
        {orderable: false, targets: 2, searchable: true, className: "text-center font-weit"},
        {orderable: false, targets: 3, searchable: true},
        {orderable: false, targets: 4, searchable: true},
        {orderable: false, targets: 5, searchable: true, className: "text-center"},
        {orderable: false, targets: 6, searchable: true, className: "text-center"},
        {orderable: false, targets: 7, searchable: true, className: "text-center"}
    ],
    select: {style: "multi"},
    order: [[2, "asc"]]
});

var dTableP = $('#tbl_aceptados').DataTable({
    autoWidth: false,
    bLengthChange: false,
    bProcessing: true,
    bServerSide: true,
    responsive: true,
    bInfo: true,
    bFilter: false,
    sAjaxSource: "tbl_psarecepcionn_acep.php",
    sServerMethod: "POST",
    fnServerParams: function (aoData) {
        aoData.push({name: "id_producto", value: $("#txt_bus_id_producto_ade").val()});
        aoData.push({name: "id_dependencia_origen", value: $("#txt_bus_id_dependencia_ade").val()});
        aoData.push({name: "datos_pac", value: $("#txt_bus_datos_pac_ade").val()});
    },
    language: {
        url: "../../assets/plugins/datatables/Spanish.json"
    },
    columnDefs: [
        { targets: 0, className: "text-center small" }, // nro_atencion
        { targets: 1, className: "small" },             // paciente
        { targets: 2, className: "small" }, // dependencia
        { targets: 3, className: "text-center small" }, // fecha toma
        { targets: 4, className: "text-center small" }, // fecha envío
        { targets: 5, className: "text-center small" }, // fecha recibe
        { targets: 6, className: "fw-bold small" }, // estado
        { targets: 7, className: "text-center small" } // botón rechazar
    ],
    createdRow: function (row, data) {
        if (data.id_estado_resul === "2") {
            $('td', row).eq(6).addClass("info");
        }
        if (data.id_estado_resul === "4") {
            $('td', row).eq(6).addClass("success");
        }
    },
    columns: [
      { data: "nro_atencion" },
      { data: "paciente" },
      { data: "dependencia_origen" },
      { data: "fecha_toma_muestra" },
      { data: "fecha_envio_destino" },
      { data: "fecha_recibe_destino" },
      { data: "estado_resul" },
      { data: "btn_rechazar" }
    ]
});

var dTableP = $('#tbl_observadas').DataTable({
    autoWidth: false,
    bLengthChange: false,
    bProcessing: true,
    bServerSide: true,
    responsive: true,
    bInfo: true,
    bFilter: false,
    sAjaxSource: "tbl_psarecepcionn_obs.php",
    sServerMethod: "POST",
    fnServerParams: function (aoData) {
        aoData.push({name: "id_producto", value: $("#txt_bus_id_producto_obs").val()});
        aoData.push({name: "id_dependencia_origen", value: $("#txt_bus_id_dependencia_obs").val()});
        aoData.push({name: "datos_pac", value: $("#txt_bus_datos_pac_obs").val()});
    },
    language: {
        url: "../../assets/plugins/datatables/Spanish.json"
    },
    columnDefs: [
        { targets: 0, className: "text-center small text-bold" }, // nro_atencion
        { targets: 1, className: "small" },             // paciente
        { targets: 2, className: "small" }, // dependencia
        { targets: 3, className: "text-center small" }, // fecha toma
        { targets: 4, className: "text-center small" }, // fecha envío
        { targets: 5, className: "text-center small" }, // fecha recibe
        { targets: 6, className: "fw-bold small" }, // estado
        { targets: 7, className: "text-center small" } // botón rechazar
    ],
    createdRow: function (row, data) {
        if (data.id_estado_resul === "2") {
            $('td', row).eq(6).addClass("info");
        }
        if (data.id_estado_resul === "4") {
            $('td', row).eq(6).addClass("success");
        }
    },
    columns: [
      { data: "nro_atencion" },
      { data: "paciente" },
      { data: "dependencia_origen" },
      { data: "fecha_toma_muestra" },
      { data: "fecha_envio_destino" },
      { data: "fecha_recibe_destino" },
      {
        render: (data, type, row, meta) => {
          const det_rechazo = row.detalle_motivo_rechazo ? `<div class='text-left'>${row.detalle_motivo_rechazo}</div>` : ``;
          return `<div class="">        
          <div class='text-left text-bold'>${row.motivo_rechazo}</div> ${det_rechazo}
        </div>`; },
      },
      { data: "btn_aceptar" }
    ]
});

});
</script>
<?php require_once '../include/masterfooter.php'; ?>
