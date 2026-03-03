<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<div class="container">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>Mantenimiento de Tipo de Colegiatura o Profesión</strong></h3>
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
                <th><small>Descripción</small></th>
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
              </ul>
              <p><b>Botones de acción:</b></p>
              <div class="row">
                <button class="btn btn-primary btn-sm" style="margin-bottom: 15px; border-radius: 4px;" onclick="reg_analisis()"><i class="glyphicon glyphicon-plus"></i> Registrar</button>
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
</div>
  <?php require_once '../include/footer.php'; ?>
  <script Language="JavaScript">
  var dTable;
  //var id_dep= document.getElementById('cboiddep').value;
  // #areas-grid adalah id pada table
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

    dTable = $('#tblAtencion').DataTable({
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
    "sAjaxSource": "tbl_principalcargo.php", // Load Data
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
    {"orderable": false, "targets": 0, "searchable": false, "class": "small"},
    {"orderable": false, "targets": 1, "searchable": false, "class": "small text-center"},
    {"orderable": false, "targets": 2, "searchable": false, "class": "small text-center"}
  ]
});

$('#tblAtencion').removeClass('display').addClass('table table-hover table-bordered');
});

function buscar_datos() {
  var idEstado = $("#txtBusIdEstado").val();

  $("#tblAtencion").dataTable().fnDraw()
}


function reg_analisis() {
  window.location = './main_laboratorio.php';
}

function open_fua(id) {
  window.location = 'fua/genera_fua.php?nroAtencion='+id;
}

function back() {
  window.location = '../pages/';
}
</script>
<?php require_once '../include/masterfooter.php'; ?>
