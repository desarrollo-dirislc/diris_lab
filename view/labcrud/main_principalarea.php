<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>Mantenimiento de Áreas de Laboratorio Clínico</strong></h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-10">
          <form class="form-horizontal" name="frmBuscar" id="frmBuscar" onsubmit="return false;">
            <input type="hidden" name="idDepQuejaPer" id="idDepQuejaPer" value="<?php if ($acceSelecDep <> "1") echo $saaIdDep; ?>"/>
            <div class="form-group">
              <div class="col-md-2">
                <label for="txtBusIdEstado">Estado</label>
                <select name="txtBusIdEstado" id="txtBusIdEstado" class="form-control input-sm" style="border-radius: 4px;">
                  <option value="">-- Todo --</option>
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
                <th><small>N° Orden</small></th>
                <th><small>&nbsp;</small></th>
                <th><small>Abreviatura</small></th>
                <th><small>Nombre</small></th>
                <th><small>Visible</small></th>
                <th><small>Estado</small></th>
                <th><small>&nbsp;</small></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="col-sm-2">
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
                <li>
                  <b>Bajar orden:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-primary btn-xs" style="cursor: default;"><i class="glyphicon glyphicon-circle-arrow-up"></i></button></li>
                  </ul>
                </li>
                <li>
                  <b>Subir orden:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-primary btn-xs" style="cursor: default;"><i class="glyphicon glyphicon-circle-arrow-down"></i></button></li>
                  </ul>
                </li>
              </ul>
              <p><b>Botones de acción:</b></p>
              <div class="row">
                <button class="btn btn-primary btn-sm" style="margin-bottom: 15px; border-radius: 4px;" onclick="reg_registro()" ><i class="glyphicon glyphicon-plus"></i> Registrar Área</button>
              </div>
              <div class="row">
                <button class="btn btn-default btn-sm" id="btnBack" type="button" onclick="back();" tabindex="1" style="border-radius: 4px;"><i class="glyphicon glyphicon-log-out"></i> Ir al Men&uacute;</button>
              </div>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="showAreaModal" role="dialog" aria-labelledby="showAreaModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="showAreaModalLabel">Registro de Área</h4>
        </div>
        <div class="modal-body">
          <form name="frmArea" id="frmArea">
            <input type="hidden" name="txtIdArea" id="txtIdArea" value="0"/>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-4">
                  <label for="txtOrdArea">Nro. Ord.:</label>
                  <input type="text" name="txtOrdArea" id="txtOrdArea" class="form-control input-xs" maxlength="4" value="" readonly/>
                </div>
                <div class="col-sm-8">
                  <label for="txtAbrevArea">Abreviatura:</label>
                  <input type="text" name="txtAbrevArea" id="txtAbrevArea" class="form-control input-xs text-uppercase" maxlength="50" value="" autocomplete="off" onkeydown="campoSiguiente('txtDescArea', event);"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-12">
                  <label for="txtDescArea">Descripción:</label>
                  <input type="text" name="txtDescArea" id="txtDescArea" class="form-control input-xs text-uppercase" maxlength="255" value="" autocomplete="off" onkeydown="campoSiguiente('txtIdVisiArea', event);"/>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-sm-4">
                  <label for="txtIdVisiArea">Visible:</label>
                  <select class="form-control input-xs" name="txtIdVisiArea" id="txtIdVisiArea" onkeydown="campoSiguiente('btnValidForm', event);">
                    <option value="">-- Seleccione --</option>
                    <option value="1">SI</option>
                    <option value="0">NO</option>
                  </select>
                </div>
                <div class="col-sm-4">
                  <label for="txtIdEstArea">Estado:</label>
                  <select class="form-control input-xs" name="txtIdEstArea" id="txtIdEstArea" onkeydown="campoSiguiente('btnValidForm', event);" disabled>
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

    $("#tblAtencion").dataTable().fnDraw();
  }

  function cambiar_orden(idtip, idarea) {
    $.ajax({
      url: "../../controller/ctrlArea.php",
      type: "POST",
      data: {
        accion: 'GET_REG_CAMBIOORDAREA', tipAcc: idtip, idArea: idarea
      },
      success: function (registro) {
        $("#tblAtencion").dataTable().fnDraw();
      }
    });
  }

  function reg_registro() {

    $.ajax({
      url: "../../controller/ctrlArea.php",
      type: "POST",
      data: {
        accion: 'GET_SHOW_NUEVONROORDAREA'
      },
      success: function (registro) {
        document.frmArea.txtIdArea.value = '0';
        document.frmArea.txtOrdArea.value = registro;
        document.frmArea.txtAbrevArea.value = '';
        document.frmArea.txtDescArea.value = '';
        document.frmArea.txtIdVisiArea.value = '';
        document.frmArea.txtIdEstArea.value = '1';
        $('#txtIdEstArea').prop("disabled", true);

        $('#showAreaModal').modal({
          show: true,
          backdrop: 'static',
          focus: true,
        });

        $('#showAreaModal').on('shown.bs.modal', function (e) {
          $("#txtAbrevArea").trigger('focus');
        });
      }
    });
  }

  function edit_registro(idarea) {

    $.ajax({
      url: "../../controller/ctrlArea.php",
      type: "POST",
      dataType: 'json',
      data: {
        accion: 'GET_SHOW_DETAREA', idArea: idarea
      },
      success: function (registro) {
        var datos = eval(registro);
        document.frmArea.txtIdArea.value = datos[0];
        document.frmArea.txtOrdArea.value = datos[1];
        document.frmArea.txtAbrevArea.value = datos[2];
        document.frmArea.txtDescArea.value = datos[3];
        document.frmArea.txtIdVisiArea.value = datos[4];
        document.frmArea.txtIdEstArea.value = datos[6];
        $('#txtIdEstArea').prop("disabled", false);

        $('#showAreaModal').modal({
          show: true,
          backdrop: 'static',
          focus: true,
        });

        $('#showAreaModal').on('shown.bs.modal', function (e) {
          $("#txtAbrevArea").trigger('focus');
        })
      }
    });
  }

  function validForm() {
    var msg = "";
    var sw = true;

    var ordArea = $('#txtOrdArea').val();
    var abrevArea = $('#txtAbrevArea').val();
    var descArea = $('#txtDescArea').val();
    var idVisiArea = $('#txtIdVisiArea').val();
    var idEstArea = $('#txtIdEstArea').val();

    if(descArea == ""){
      msg+= "Ingrese Descripción del Área<br/>";
      sw = false;
    }

    if(idVisiArea == ""){
      msg+= "Seleccione si el Área va hacer Visible o no<br/>";
      sw = false;
    }

    if (sw == false) {
      bootbox.alert(msg);
      $('#btnValidForm').prop("disabled", false);
      return sw;
    } else {
      save_form();
    }
  }

  function save_form() {
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
          form_data.append('accion', 'POST_ADD_REGAREA');
          form_data.append('txtIdArea', document.frmArea.txtIdArea.value);
          form_data.append('txtOrdArea', document.frmArea.txtOrdArea.value);
          form_data.append('txtAbrevArea', document.frmArea.txtAbrevArea.value);
          form_data.append('txtDescArea', document.frmArea.txtDescArea.value);
          form_data.append('txtIdVisiArea', document.frmArea.txtIdVisiArea.value);
          form_data.append('txtIdEstArea', $("#txtIdEstArea").val());
          form_data.append('rand', myRand);
          $.ajax( {
            url: '../../controller/ctrlArea.php',
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
                $("#showAreaModal").modal('hide');
                $("#tblAtencion").dataTable().fnDraw();
                if($("#txtIdArea").val() == "0"){
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

  // dTable adalah id pada table
  var dTable;
  $(document).ready(function () {
    dTable = $('#tblAtencion').DataTable({
      /*"language": {
      "url": "../plugins/datatables/Spanish.json"
    },*/
    "pageLength": 25,
    "bLengthChange": true, //Paginado 10,20,50 o 100
    "bProcessing": true,
    "bServerSide": true,
    "bJQueryUI": false,
    "responsive": true,
    "bInfo": true,
    "bFilter": false,
    "sAjaxSource": "tbl_principalarea.php", // Load Data
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
    /*"fnServerParams": function ( aoData ) {
    aoData.push( { "name": "id_tabla", "value": $('#cboiddep').val() } );
  },*/

  "columnDefs": [
    {"orderable": true, "targets": 0, "searchable": false, "class": "small text-center font-weit"},
    {"orderable": false, "targets": 1, "searchable": false, "class": "small text-center font-weit"},
    {"orderable": false, "targets": 2, "searchable": false, "class": "small"},
    {"orderable": false, "targets": 3, "searchable": false, "class": "small"},
    {"orderable": true, "targets": 4, "searchable": false, "class": "small text-center"},
    {"orderable": true, "targets": 5, "searchable": false, "class": "small text-center"},
    {"orderable": false, "targets": 6, "searchable": false, "class": "small text-center"}
  ]
});

$('#tblAtencion').removeClass('display').addClass('table table-hover table-bordered');
});
</script>
<?php require_once '../include/masterfooter.php'; ?>
