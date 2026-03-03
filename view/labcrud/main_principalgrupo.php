<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<div class="container">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>Mantenimiento de Grupos de Laboratorio Clínico</strong></h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-9">
          <form class="form-horizontal" name="frmBuscar" id="frmBuscar" onsubmit="return false;">
            <input type="hidden" name="idDepQuejaPer" id="idDepQuejaPer" value="<?php if ($acceSelecDep <> "1") echo $saaIdDep; ?>"/>
            <div class="form-group">
              <div class="col-md-2">
                <label for="txtBusIdEstado">Estado</label>
                <select name="txtBusIdEstado" id="txtBusIdEstado" class="form-control input-sm" style="border-radius: 4px;">
                  <option value="1">-- Todo --</option>
                  <option value="1">ACTIVO</option>
                  <option value="2">INACTIVO</option>
                </select>
              </div>
              <div class="col-sm-1 col-md-1">
                <br/>
                <button class="btn btn-success btn-sm" type="button" id="btnCon" onclick="buscar_datos();" tabindex="0" style="border-radius: 4px;"><i class="glyphicon glyphicon-search"></i> Buscar</button>
              </div>
              <div class="col-sm-4 col-md-4">
                <br/>
                <button id="btnRegistrarAsis" class="btn btn-warning pull-right btn-sm" type="button" onclick="exportar_busqueda();" tabindex="0" style="border-radius: 4px;"><i class="glyphicon glyphicon-open"></i> Exportar a Excel</button>
              </div>
            </div>
          </form>
          <br/>
          <table id="tblAtencion" class="display" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th><small>Nombre</small></th>
                <th><small>Estado</small></th>
                <th><small>&nbsp;</small></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <div>
            <small>
              <p><b>Leyenda:</b></p>
              <ul>
                <li>
                  <b>Editar:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-success btn-xs" style="cursor: default;"><i class="glyphicon glyphicon-pencil"></i></button></li>
                  </ul>
                </li>
              </ul>
              <p><b>Botones de acción:</b></p>
              <div class="row">
                <button type="button" class="btn btn-primary btn-sm" style="border-radius: 4px; margin-bottom: 15px;" onclick="reg_grupo()"><i class="glyphicon glyphicon-plus"></i> Registrar Grupo</button>
              </div>
              <div class="row">
                <button type="button" class="btn btn-default btn-sm" id="btnBack" type="button" onclick="back();" tabindex="1" style="border-radius: 4px;"><i class="glyphicon glyphicon-log-out"></i> Ir al Men&uacute;</button>
              </div>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="showGrupoModal" role="dialog" aria-labelledby="showGrupoModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="showGrupoModalLabel">Registro de Grupo</h4>
      </div>
      <div class="modal-body">
        <form name="frmGrupo" id="frmGrupo">
          <input type="hidden" name="txtIdGrupo" id="txtIdGrupo" value="0"/>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-12">
                <label for="txtDescGrupo">Descripción:</label>
                <input type="text" name="txtDescGrupo" id="txtDescGrupo" class="form-control input-xs text-uppercase" maxlength="255" value="" autocomplete="off" onkeydown="campoSiguiente('txtIdEstGrupo', event);"/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-sm-4">
                <label for="txtIdEstGrupo">Estado:</label>
                <select class="form-control input-xs" name="txtIdEstGrupo" id="txtIdEstGrupo" onkeydown="campoSiguiente('btnValidForm', event);" disabled>
                  <option value="1" selected>ACTIVO</option>
                  <option value="0">INACTIVO</option>
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
              <button type="button" class="btn btn-primary btn-continuar" id="btnValidForm" onclick="validForm()"><i class="fa fa-save"></i> Guardar </button>
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once '../include/footer.php'; ?>
<script Language="JavaScript">
function campoSiguiente(campo, evento) {
  if (evento.keyCode == 13 || evento.keyCode == 9) {
    if (campo == 'btnValidForm') {
      validForm();
    } else {
      document.getElementById(campo).focus();
      evento.preventDefault();
    }
  }
}

function back() {
  window.location = '../pages/';
}

function buscar_datos() {
  var idEstado = $("#txtBusIdEstado").val();

  $("#tblAtencion").dataTable().fnDraw()
}

function reg_grupo() {
  document.frmGrupo.txtIdGrupo.value = '0';
  document.frmGrupo.txtDescGrupo.value = '';
  $("#txtIdEstGrupo").val('1');

  $('#showGrupoModal').modal({
    show: true,
    backdrop: 'static',
    focus: true,
  });

  $('#showGrupoModal').on('shown.bs.modal', function (e) {
    $("#txtDescGrupo").trigger('focus');
  });
}

function validForm() {
  var msg = "";
  var sw = true;

  var descGrupo = $('#txtDescGrupo').val();
  var idEstGrupo = $('#txtIdEstGrupo').val();

  if(descGrupo == ""){
    msg+= "Ingrese Descripción del Grupo<br/>";
    sw = false;
  }
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
        form_data.append('accion', 'POST_ADD_REGGRUPO');
        form_data.append('txtIdGrupo', document.frmGrupo.txtIdGrupo.value);
        form_data.append('txtDescGrupo', document.frmGrupo.txtDescGrupo.value);
        form_data.append('txtIdEstGrupo', $("#txtIdEstGrupo").val());
        form_data.append('rand', myRand);
        $.ajax( {
          url: '../../controller/ctrlGrupo.php',
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
            if(tmsg == "OK"){
              $("#showGrupoModal").modal('hide');
              $("#tblAtencion").dataTable().fnDraw();
              if($("#txtIdGrupo").val() == "0"){
                bootbox.alert("Registro ingresado correctamente.");
              } else {
                bootbox.alert("Registro actualizado correctaente.");
              }
            } else {
              bootbox.alert(msg);
              return false;
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

$(document).ready(function () {
  $("#txtBusIdTipDoc").select2();

  $("#txtBusFecIni").datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
  });
  $("#txtBusFecFin").datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
  });

  var dTable = $('#tblAtencion').DataTable({
    /*"language": {
    "url": "../plugins/datatables/Spanish.json"
  },*/
  "bLengthChange": true, //Paginado 10,20,50 o 100
  "bProcessing": true,
  "bServerSide": true,
  "bJQueryUI": false,
  "responsive": true,
  "bInfo": true,
  "bFilter": false,
  "sAjaxSource": "tbl_principalgrupo.php", // Load Data
  "language": {
    //"url": "../plugins/datatables/Spanish.json",
    "lengthMenu": '_MENU_ registros por p\xe1gina',
    "search": '<i class="glyphicon glyphicon-search"></i>',
    "paginate": {
      "previous": '<i class="glyphicon glyphicon-arrow-left"></i>',
      "next": '<i class="glyphicon glyphicon-arrow-right"></i>'
    }
  },
  "sServerMethod": "POST",
  "fnServerParams": function (aoData)
  {
    aoData.push({"name": "idEstado", "value": $("#txtBusIdEstado").val()});
  },
  "columnDefs": [
    {"orderable": true, "targets": 0, "searchable": false, "class": "small"},
    {"orderable": true, "targets": 1, "searchable": false, "class": "small text-center"},
    {"orderable": false, "targets": 2, "searchable": false, "class": "small text-center"}
  ]
});

$('#tblAtencion').removeClass('display').addClass('table table-hover table-bordered');
});

</script>
<?php require_once '../include/masterfooter.php'; ?>
