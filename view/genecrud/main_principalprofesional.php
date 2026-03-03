<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<?php
require_once '../../model/Tipo.php';
$t = new Tipo();
require_once '../../model/Dependencia.php';
$d = new Dependencia();
require_once '../../model/Usuario.php';
$u = new Usuario();
require_once '../../model/Ups.php';
$ups = new Ups();
?>

<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>Mantenimiento de Profesional</strong></h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-10">
          <form class="form-horizontal" name="frmBuscar" id="frmBuscar" onsubmit="return false;">
            <div class="form-group">
			  <div class="col-sm-6 col-md-6">
				<label for="txtBusNomRS"><small>Documento de identidad o Nombres y Apellidos del profesional:</small></label>
				<input class="form-control input-sm text-uppercase" type="text" name="txtBusNomRS" id="txtBusNomRS" autocomplete="OFF" maxlength="50" required="" tabindex="0" oninput="buscar_datos()"/>
			  </div>
			  <div class="col-sm-6 col-md-6">
				<label for="txtIdDep">Dependencia:</label>
				<?php $rsD = $d->get_listaDepenInstitucion(); ?>
				<select name="txtBusIdDep" id="txtBusIdDep" style="width:100%;" onchange="buscar_datos()">
				  <option value="" selected="">-- Seleccione --</option>
				  <?php
				  foreach ($rsD as $row) {
					echo "<option value='" . $row['id_dependencia'] . "'>" . $row['codref_depen'] . ": " . $row['nom_depen'] . "</option>";
				  }
				  ?>
				</select>
			  </div>
			</div>
          </form>
          <br/>
          <table id="tblSolicitud" class="table table-hover table-bordered" cellspacing="0" width="100%">
            <thead class="bg-aqua">
              <tr>
                <th></th>
                <th><small>Documento<br/>Identidad</small></th>
                <th><small>Profesional</small></th>
                <th><small>Profesión</small></th>
                <th><small>Nro. Colegiatura</small></th>
                <th><small>Nro. RNE</small></th>
				<th><small>Condición<br/>Laboral</small></th>
                <th><small>Firma</small></th>
                <th><small>Estado</small></th>
                <th><i class="fa fa-cogs"></i></th>
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
                <button class="btn btn-primary btn-sm" style="margin-bottom: 15px; border-radius: 4px;" onclick="reg_registro('ins')"><i class="glyphicon glyphicon-plus"></i> Registrar Profesional</button>
              </div>
              <div class="row">
                <button class="btn btn-warning btn-sm" style="margin-bottom: 15px; border-radius: 4px;" onclick="expor_usuarios()"><i class="glyphicon glyphicon-plus"></i> Exportar Usuarios</button>
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

<?php require_once './modal/profesional/modal_principal.php'; ?>
<?php require_once './modal/profesional/modal_firma.php'; ?>
<?php require_once './modal/profesional/modal_servicio.php'; ?>
<?php require_once '../include/footer.php'; ?>
<script type="text/javascript" src="profesional.js"></script>
<script Language="JavaScript">
	var dtables = {
		dtable: null, //Tabla principal
		dtabledet1: null //Tabla detalle
	};

	function buscar_datos() {
		var idEstado = $("#txtBusIdEstado").val();
		$("#tblSolicitud").dataTable().fnDraw()
	}
	
	function inicia_evento_dtabledet() { //initEventsChildRows
			loadChildRow('dtable', 'details-control', formatChild1);
	}

	function loadChildRow(table, sclass, functionFormat) {
		$('#tblSolicitud tbody').on('click', 'td.' + sclass, function (e) {
			var tr = $(this).closest('tr');
			var row = dtables[table].row(tr);
			var el = e.currentTarget;
			if (row.child.isShown()) {
				// This row is already open - close it
				el.children[0].children[0].classList.remove('fa-minus');
				el.children[0].children[0].classList.add('fa-plus');
				row.child.hide();
				tr.removeClass('shown');
			} else {
				//Open this row
				el.children[0].children[0].classList.remove('fa-plus');
				el.children[0].children[0].classList.add('fa-minus');
				row.child(functionFormat(row.data())).show();
				tr.addClass('shown');
			}
		});
	}

	function formatChild1(d) {
		  var id_profesional = d[0];
		  var parametros = {
			"accion": "SHOW_DETPRODUCTOATENCION",
			"idProf": id_profesional
		  };
		  var div = $("<div id='row_"+d[0]+"' style='width: 70%;'>").addClass( 'Cargando' ).text( 'Cargando...' );
		  $.ajax({
			data: parametros,
			url: '../../controller/ctrlProfesional.php',
			type: 'POST',
			dataType: 'html',
			success: function (result) {
			  div.html(result).removeClass('loading');
			}
		  });
		  return div;
	}
	
	function formatChild1_actu(d) {
	  var parametros = {
		"accion": "SHOW_DETPRODUCTOATENCION",
		"idProf": d
	  };
	  console.log(d);
	  $.ajax({
		data: parametros,
		url: '../../controller/ctrlProfesional.php',
		type: 'post',
		//dataType: 'html',
		success: function (result) {
		  $("#row_"+d).html(result);
		}
	  });
	}

	$(document).ready(function () {
	  $("#txtIdTipDoc").select2();
	  $("#txtIdDep, #txtBusIdDep").select2();
	  $("#txtIdServicioDep").select2();
	  $("#txtIdProfesion").select2();
	
	  $("#ser_txtIdDep").select2();
	  $("#ser_txtIdServicioDep").select2();

	  dtables.dtable = $('#tblSolicitud').DataTable({
		"bLengthChange": true, //Paginado 10,20,50 o 100
		"bProcessing": true,
		"bServerSide": true,
		"bJQueryUI": false,
		"responsive": false,
		"bInfo": true,
		"bFilter": false,
		"sAjaxSource": "tbl_principalprofesional.php", // Load Data
		"language": {
		  "url": "../../assets/plugins/datatables/Spanish.json",
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
		  aoData.push({"name": "nomRS", "value": $("#txtBusNomRS").val()});
		  aoData.push({"name": "id_dependencia", "value": $("#txtBusIdDep").val()});
		},
		"columnDefs": [
		  {"orderable": false, "targets": 0, "searchable": false, "class": "text-center"},
		  {"orderable": false, "targets": 1, "searchable": false, "class": "small"},
		  {"orderable": false, "targets": 2, "searchable": false, "class": "small font-weit"},
		  {"orderable": false, "targets": 3, "searchable": false, "class": "small"},
		  {"orderable": false, "targets": 4, "searchable": false, "class": "small text-center"},
		  {"orderable": false, "targets": 5, "searchable": false, "class": "small text-center"},
		  {"orderable": false, "targets": 6, "searchable": false, "class": "small text-center"},
		  {"orderable": false, "targets": 7, "searchable": false, "class": "small text-center"},
		  {"orderable": false, "targets": 8, "searchable": false, "class": "small text-center"},
		  {"orderable": false, "targets": 9, "searchable": false, "class": "small text-center"}
		],
		"columns": [
			{
			 className: 'details-control',
			 defaultContent: '',
			 data: null,
			 orderable: false,
			 defaultContent: '<div class="text-center" style="cursor: pointer"><i class="fa fa-plus"><i></div>'
			},
			{ data: null, render: function ( data, type, row ) {return data.abrev_tipodocpac+': '+data.nro_docpac;}},
			{ data: "nombre_rspro"},
			{ data: "nom_profesion"},
			{ data: "nro_colegiatura"},
			{ data: "nro_rne"},
			{ data: "condicion_laboral"},
			{
			 render: function(data, type, row) {
				archivo = "SI";
				var img = new Image();
				img.src = "./profesional/" + row.id_profesional + ".png";
				existe = img.height;
				if(existe == "0"){
					archivo = "NO";
					var img = new Image();
					img.src = "./profesional/" + row.id_profesional + ".jpg";
					if(img.height != "0"){
						archivo = "SI";
					}
				}				
				return "<div class='text-center'>" + archivo + "</div>";
			 }
			},
			{
			 render: function(data, type, row) {
				color = parseInt(row.estado) === 1 ? 'bg-green' : 'bg-red';
				var bntEstado = "<span class='badge " + color + "'><small>" + row.nom_estado + "</small></span>";
				
			  return "<div class='text-center'>" + bntEstado + "</div>";
			 }
			},
			{
			 render: function(data, type, row) {
				var btnEditar = "<button class='btn btn-success btn-xs' onclick='event.preventDefault();reg_registro(\"upd\"," + JSON.stringify(row) + ")'><i class='glyphicon glyphicon-pencil'></i></button>";
				var btnFirma = "<button class='btn btn-primary btn-xs' onclick='event.preventDefault();open_firma(" + row.id_profesional + ")'><i class='fa fa-list'></i></button>";
				var btnPDF = "";
			  return "<div class='text-center'>" + btnEditar + " " + btnFirma + "</div>";
			 }
			}
		]
	  });
	  
		inicia_evento_dtabledet();

	});

</script>
<?php require_once '../include/masterfooter.php'; ?>
