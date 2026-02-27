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
      <h3 class="panel-title"><strong>MANTENIMIENTO DE USUARIO</strong></h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-10">
          <form class="form-horizontal" name="frmBuscar" id="frmBuscar" onsubmit="return false;">
            <div class="form-group">
              <div class="col-md-2">
                <label for="txtBusDoc"><small>N° Documento:</small></label>
                <input class="form-control input-sm" type="text" name="txtBusDoc" id="txtBusDoc" autocomplete="OFF" maxlength="20" tabindex="0" oninput="buscar_datos()"/>
              </div>
              <div class="col-md-3">
                <label for="txtBusUsuario"><small>Nombre de Usuario:</small></label>
                <input class="form-control input-sm text-uppercase" type="text" name="txtBusUsuario" id="txtBusUsuario" autocomplete="OFF" maxlength="50" tabindex="0" oninput="buscar_datos()"/>
              </div>
              <div class="col-sm-1 col-md-1">
                <br/>
                <button class="btn btn-default btn-sm" type="button" onclick="limpiar_filtros();" tabindex="0"><i class="glyphicon glyphicon-remove"></i> Limpiar</button>
              </div>
            </div>
          </form>
          <br/>
          <table id="tblAtencion" class="table table-hover table-bordered" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th><small>Documento</small></th>
                <th><small>Persona</small></th>
                <th><small>Usuario</small></th>
                <th><small>Fec. Caduca</small></th>
                <th><small>Estado</small></th>
                <th><small>&nbsp;</small></th><!-- Editar-->
                <th><small>&nbsp;</small></th><!-- Clave-->
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
                  <b>Editar Usuario:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-success btn-xs" style="cursor: default;"><i class="glyphicon glyphicon-pencil"></i></button></li>
                  </ul>
                </li>
                <li>
                  <b>Editar Accesos:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-primary btn-xs" style="cursor: default;"><i class="fa fa-list"></i></button></li>
                  </ul>
                </li>
                <li>
                  <b>Editar Contraseña:</b>
                  <ul class="list-unstyled">
                    <li><button class="btn btn-warning btn-xs" style="cursor: default;"><i class="glyphicon glyphicon-lock"></i></button></li>
                  </ul>
                </li>
              </ul>
              <p><b>Botones de acción:</b></p>
              <div class="row">
                <button class="btn btn-primary btn-sm" style="margin-bottom: 15px;" onclick="reg_usuario()"><i class="glyphicon glyphicon-plus"></i> Registrar Usuario</button>
              </div>
              <div class="row">
                <button class="btn btn-default btn-sm" id="btnBack" type="button" onclick="back();" tabindex="1"><i class="glyphicon glyphicon-log-out"></i> Ir al Men&uacute;</button>
              </div>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once './modal/user/modal_principal.php'; ?>
<?php require_once './modal/user/modal_clave.php'; ?>
<?php require_once '../include/footer.php'; ?>
<script Language="JavaScript">
var dTable;
//var id_dep= document.getElementById('cboiddep').value;
// #areas-grid adalah id pada table
$(document).ready(function () {
  dTable = $('#tblAtencion').DataTable({
  "bLengthChange": true,
  "bProcessing": true,
  "bServerSide": true,
  "bJQueryUI": false,
  "responsive": false,
  "bInfo": true,
  "bFilter": false,
  "sAjaxSource": "tbl_principaluser.php",
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
  "fnServerParams": function (aoData) {
    aoData.push({"name": "busDoc",     "value": $("#txtBusDoc").val()});
    aoData.push({"name": "busUsuario", "value": $("#txtBusUsuario").val()});
  },
"columnDefs": [
  {"orderable": false, "targets": 0, "searchable": false, "class": "small"},
  {"orderable": false, "targets": 1, "searchable": false, "class": "small font-weit"},
  {"orderable": false, "targets": 2, "searchable": false, "class": "small"},
  {"orderable": false, "targets": 3, "searchable": false, "class": "small text-center"},
  {"orderable": false, "targets": 4, "searchable": false, "class": "small text-center"},
  {"orderable": false, "targets": 5, "searchable": false, "class": "small text-center"},
  {"orderable": false, "targets": 6, "searchable": false, "class": "small text-center"}
]
});
});

function buscar_datos() {
  $("#tblAtencion").dataTable().fnDraw();
}

function limpiar_filtros() {
  $("#txtBusDoc").val('');
  $("#txtBusUsuario").val('');
  $("#tblAtencion").dataTable().fnDraw();
}

function reg_usuario() {
  $('#showUsuarioModal').modal({
    show: true,
    backdrop: 'static',
    focus: true,
  });

  $('#ingContra').show();
  $('#txtNomUsuario').prop("readonly", false);
  $('#txtIdTipDoc').prop("disabled", false);
  $('#txtNroDoc').prop("readonly", false);
  $('#btn-pac-search').prop("disabled", false);

  document.frmUsuario.txtIdUser.value = '0';
  document.frmUsuario.txtIdPer.value = '0';
  $("#txtIdTipDoc").val('1').trigger("change");
  document.frmUsuario.txtNroDoc.value = '';
  document.frmUsuario.txtIdSexoPac.value = '';
  document.frmUsuario.txtFecNacPac.value = '';
  document.frmUsuario.txtNomPac.value = '';
  document.frmUsuario.txtPriApePac.value = '';
  document.frmUsuario.txtSegApePac.value = '';
  document.frmUsuario.txtNroTelFijoPac.value = '';
  document.frmUsuario.txtNroTelMovilPac.value = '';
  document.frmUsuario.txtEmailPac.value = '';

  document.frmUsuario.txtNomUsuario.value = '';
  document.frmUsuario.txtClave.value = '';


  $('#showUsuarioModal').on('shown.bs.modal', function (e) {
    $("#txtNroDoc").trigger('focus');
  });

}

function edit_usuario(idusu) {

  $.ajax({
    url: "../../controller/ctrlUsuario.php",
    type: "POST",
    dataType: 'json',
    data: {
      accion: 'GET_SHOW_DETUSUARIO', idUsu: idusu
    },
    success: function (registro) {
      var datos = eval(registro);
      document.frmUsuario.txtIdUser.value = datos[0];
      buscar_datos_personales(datos[1]);

      $("#txtIdTipDoc").val(datos[2]).trigger("change");
      document.frmUsuario.txtNroDoc.value = datos[3];
      document.frmUsuario.txtNomUsuario.value = datos[4];
      document.frmUsuario.txtClave.value = '';

      $('#ingContra').hide();
      $('#txtNomUsuario').prop("readonly", true);
      $('#txtIdTipDoc').prop("disabled", true);
      $('#txtNroDoc').prop("readonly", true);
      $('#btn-pac-search').prop("disabled", true);


      $('#showUsuarioModal').modal({
        show: true,
        backdrop: 'static',
        focus: true,
      });

      $('#showUsuarioModal').on('shown.bs.modal', function (e) {
        $("#txtNroTelFijoPac").trigger('focus');
      })
    }
  });
}


function reg_clave(id) {
	$('#cla_iduser').val(id);
	$('#cla_clave').val('');
	
	$('#showClaveUsuario').modal({
	  show: true,
	  backdrop: 'static',
	  focus: true,
	});

	$('#showClaveUsuario').on('shown.bs.modal', function (e) {
	  $("#cla_clave").trigger('focus');
	})
}

function validFormClave() {
	//$('#btnValidFormClave').prop("disabled", true);
	var msg = "";
	var sw = true;

	var pass_usuario = $('#cla_clave').val();

	if(pass_usuario.length < 6){
		msg += "Ingrese la contraseña como mínimo 6 caracteres<br/>";
		sw = false;
	}

	if (sw == false) {
		bootbox.alert(msg);
		$('#btnValidFormClave').prop("disabled", false);
		return false;
	}
  
  bootbox.confirm({
    message: "Se cambiará la contraseña, ¿Está seguro de continuar?",
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
        form_data.append('accion', 'POST_ADD_PWDUSUARIO');
        form_data.append('id_usuario', $('#cla_iduser').val());
        form_data.append('pass_usuario', $('#cla_clave').val());

        form_data.append('rand', myRand);
        $.ajax( {
          url: '../../controller/ctrlUsuario.php',
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
              $("#showClaveUsuario").modal('hide');
              $("#tblAtencion").dataTable().fnDraw();
            } else {
              bootbox.alert(msg);
              return false;
            }
            $('#btnValidFormClave').prop("disabled", false);
          }
        });
      } else {
        $('#btnValidFormClave').prop("disabled", false);
      }
    }
  });
}

function maxlength_doc_bus() {
  if ($("#txtIdTipDoc").val() == "1") {
    $("#txtNroDoc").attr('maxlength', '8');
  } else {
    $("#txtNroDoc").attr('maxlength', '12');
  }
  $("#txtNroDoc").val('');
  $("#txtNroDoc").focus();
  $('#txtNroDoc').trigger('focus');
  setTimeout(function(){$('#txtNroDoc').trigger('focus');}, 2);
}

function campoSiguiente(campo, evento) {
  if (evento.keyCode == 13 || evento.keyCode == 9) {
    if (campo == 'btn-pac-search') {
      validate_exis_user();
    } else {
      document.getElementById(campo).focus();
      evento.preventDefault();
    }
  }
}

function validate_exis_user(){
  $('#txtIdPer').val('0');
  var msg = "";
  var sw = true;
  var txtIdTipDoc = $('#txtIdTipDoc').val();
  var txtNroDoc = $('#txtNroDoc').val();
  var txtNroDocLn = txtNroDoc.length;

  if (txtIdTipDoc == "1") {
    if (txtNroDocLn != 8) {
      msg += "Ingrese el Nro. de documento correctamente<br/>";
      sw = false;
    }
  } else if(txtIdTipDoc == "2" || txtIdTipDoc == "4"){
    if (txtNroDocLn <= 5) {
      msg += "Ingrese el Nro. de documento correctamente<br/>";
      sw = false;
    }
  } else {
    if (txtNroDocLn <= 9) {
      msg += "Ingrese el Nro. de documento correctamente<br/>";
      sw = false;
    }
  }

  if (sw == false) {
    bootbox.alert(msg);
    $('#btn-pac-search').prop("disabled", false);
    return sw;
  }

  $('#btn-pac-search').prop("disabled", true);
  $.ajax({
    url: "../../controller/ctrlUsuario.php",
    type: "POST",
    dataType: 'json',
    data: {
      accion: 'GET_SHOW_EXISUSUARIO', txtIdTipDoc: txtIdTipDoc, txtNroDoc: txtNroDoc
    },
    success: function (reg) {
      if(reg == "0"){
        buscar_datos_personales('');
      } else {
        $('#txtNomPac').prop("readonly", true);
        $('#txtPriApePac').prop("readonly", true);
        $('#txtSegApePac').prop("readonly", true);
        $('#txtIdSexoPac').prop("disabled", true);
        $('#txtFecNacPac').prop("disabled", true);

        bootbox.alert("El Nro. de Documento ingresado ya se encuenta registrado.");
        $('#btn-pac-search').prop("disabled", false);
      }
    }
  });
}

function buscar_datos_personales(idPer){
	if(idPer == ""){
	txtTipoBus = '2';
	txtIdTipDoc = $('#txtIdTipDoc').val();
	txtNroDoc = $('#txtNroDoc').val();
	} else {
	txtTipoBus = '1';
	txtIdTipDoc = idPer;
	txtNroDoc = '';
	}
	$("#txtNomPac").val('');
	$("#txtPriApePac").val('');
	$("#txtSegApePac").val('');
	$("#txtIdSexoPac").val('');
	$("#txtFecNacPac").val('');
	$("#txtNroTelfFijoPac").val('');
	$("#txtNroTelfMovilPac").val('');
	$("#txtEmailPac").val('');

	$("#txtIdPer").val('0');
	$("#txtValidReniec").val('0');
	
	$('#btn-pac-search').prop("disabled", true);
	$.ajax({
	url: "../../controller/ctrlPersona.php",
	type: "POST",
	dataType: 'json',
	data: {
		accion: 'GET_SHOW_PERSONULTIMAATENCIONPORIDDEP', txtTipoBus: txtTipoBus, txtIdTipDoc: txtIdTipDoc, txtNroDoc: txtNroDoc, interfaz: 'usuario'
	},
	beforeSend: function (objeto) {
	  bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Por favor espere...</p>',
		closeButton: false
	  });
	},
	success: function (registro) {
	  bootbox.hideAll();
	  var datos = eval(registro);
	  if (datos[0] == "C"){
		$("#txtIdPer").val('0');
		showMessage(datos[1], "error");	
	  } else {
		$("#txtIdPer").val(datos[0]);  
	  }
	  if (datos[23] == ""){
		$("#txtValidReniec").val('0');
	  } else {
		$("#txtValidReniec").val('1');  
	  }
	  if((datos[4] == null) || (datos[4] == "")){
		$('#txtIdSexoPac').prop("disabled", false);
		$('#txtNomPac').prop("readonly", false);
		$('#txtPriApePac').prop("readonly", false);
		$('#txtSegApePac').prop("readonly", false);
		$('#txtFecNacPac').prop("disabled", false);
		$("#txtIdSexoPac").trigger('focus');
	  } else {
		$("#txtNomPac").val(datos[4]);
		$("#txtPriApePac").val(datos[5]);
		$("#txtSegApePac").val(datos[6]);
		$("#txtIdSexoPac").val(datos[7]);
		$("#txtFecNacPac").val(datos[9]);
		$("#txtNroTelFijoPac").val(datos[11]);
		$("#txtNroTelMovilPac").val(datos[12]);
		$("#txtEmailPac").val(datos[13]);
		$('#txtNomPac').prop("readonly", true);
		$('#txtPriApePac').prop("readonly", true);
		$('#txtSegApePac').prop("readonly", true);
		$('#txtIdSexoPac').prop("disabled", true);
		$('#txtFecNacPac').prop("disabled", true);
		if(datos[9] == ""){
		  $('#txtFecNacPac').prop("disabled", false);
		}
		$("#txtNroTelFijoPac").trigger('focus');
	  }
	  if(txtTipoBus == "1"){
		$('#btn-pac-search').prop("disabled", true);
	  } else {
		$('#btn-pac-search').prop("disabled", false);
	  }
	}
	});
}

function validForm() {
  $('#btnValidForm').prop("disabled", true);
  var msg = "";
  var sw = true;

  var idUsu = $('#txtIdUser').val();

  var idDep = $('#txtIdDep').val();
  var nomUsu = $('#txtNomUsuario').val();
  var clave = $('#txtClave').val();
  var nomRol = $('#txtIdRol').val();

  var sexopac = $('#txtIdSexoPac').val();
  var fecnacpac = $('#txtFecNacPac').val();
  var nompac = $('#txtNomPac').val();
  var priapepac = $('#txtPriApePac').val();
  var seapepac = $('#txtSegApePac').val();
  var telfipac = $('#txtNroTelFijoPac').val();
  var telmopac = $('#txtNroTelMovilPac').val();
  var emailpac = $('#txtEmailPac').val();

  if(sexopac == ""){
    msg+= "Seleccione el sexo del Paciente<br/>";
    sw = false;
  }


  if(nompac == ""){
    msg+= "Ingrese nombre del Paciente<br/>";
    sw = false;
  }

  if(priapepac == ""){
    if(seapepac == ""){
      msg+= "Ingrese el apellido paterno o materno del Paciente<br/>";
      sw = false;
    }
  }

  if(telfipac != ""){
    var ltelfipac = telfipac.length;
    if(ltelfipac < 7){
      msg+= "Ingrese correctamente el número de teléfono fijo del Paciente<br/>";
      sw = false;
    }
  }

  if(telmopac != ""){
    var ltelmopac = telmopac.length;
    if(ltelmopac < 9){
      msg+= "Ingrese correctamente el número de teléfono móvil del Paciente<br/>";
      sw = false;
    }
  }

  if(emailpac != ""){
    if(validateEmail(emailpac) === false){
      msg+= "Ingrese correctamente el email del Paciente<br/>";
      sw = false;
    };
  }

  if(idDep == ""){
    msg += "Seleccione la dependencia del usuario<br/>";
    sw = false;
  }

  if(nomUsu == ""){
    msg += "Ingrese nombre de usuario<br/>";
    sw = false;
  }
  if (idUsu == "0"){
  if(clave.length < 6){
    msg += "Ingrese la contraseña como mínimo 6 caracteres<br/>";
    sw = false;
  }
  }

  if(nomRol == ""){
    msg += "Seleccione el rol del usuario<br/>";
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
        form_data.append('accion', 'POST_ADD_REGUSUARIO');
        form_data.append('txtIdPer', document.frmUsuario.txtIdPer.value);
        form_data.append('txtIdUser', document.frmUsuario.txtIdUser.value);
        form_data.append('txtIdTipDoc', document.frmUsuario.txtIdTipDoc.value);
        form_data.append('txtNroDoc', document.frmUsuario.txtNroDoc.value);
        form_data.append('txtIdSexoPac', document.frmUsuario.txtIdSexoPac.value);
        form_data.append('txtFecNacPac', document.frmUsuario.txtFecNacPac.value);
        form_data.append('txtNomPac', document.frmUsuario.txtNomPac.value);
        form_data.append('txtPriApePac', document.frmUsuario.txtPriApePac.value);
        form_data.append('txtSegApePac', document.frmUsuario.txtSegApePac.value);
        form_data.append('txtNroTelFijoPac', document.frmUsuario.txtNroTelFijoPac.value);
        form_data.append('txtNroTelMovilPac', document.frmUsuario.txtNroTelMovilPac.value);
        form_data.append('txtEmailPac', document.frmUsuario.txtEmailPac.value);

        form_data.append('txtNomUsuario', document.frmUsuario.txtNomUsuario.value);
        form_data.append('txtClave', document.frmUsuario.txtClave.value);
		
		form_data.append('txtValidReniec', document.frmUsuario.txtValidReniec.value);

        form_data.append('rand', myRand);
        $.ajax( {
          url: '../../controller/ctrlUsuario.php',
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
              $("#showUsuarioModal").modal('hide');
              $("#tblAtencion").dataTable().fnDraw();
              if($("#").val() == "0"){
                bootbox.alert("Registro ingresado correctamente.");
              } else {
                bootbox.alert("Registro actualizado correctamente.");
              }
            } else {
              bootbox.alert(msg);
              return false;
            }
          }
        });
		$('#btnValidForm').prop("disabled", false);
      } else {
        $('#btnValidForm').prop("disabled", false);
      }
    }
  });
}

function back() {
  window.location = '../pages/';
}

$(function() {

  jQuery('#txtNroDoc').keypress(function (tecla) {
    var idTipDocPer = $("#txtIdTipDoc").val();
    if (idTipDocPer == "1") {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
      return false;
    } else {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode != 0))//(Numeros y letras)(0=borrar)
      return false;
    }
  });

  jQuery('#txtPriApePac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtSegApePac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtNomPac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });

  jQuery('#txtNroTelFijoPac').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });

  jQuery('#txtNroTelMovilPac').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });

});


$(document).ready(function () {
  $("#txtIdTipDoc").select2();
  $("#txtIdServicio").select2();
});
</script>
<?php require_once '../include/masterfooter.php'; ?>
