<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>

<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>ACCESOS x USUARIO</strong></h3>
    </div>
    <div class="panel-body">

      <!-- BUSCADOR -->
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label><i class="fa fa-search"></i> Buscar usuario por nombre o N° documento:</label>
            <input type="text" id="txtBuscarUsuario" class="form-control"
                   placeholder="Escriba nombre o DNI..." autocomplete="off"/>
          </div>
        </div>
        <div class="col-sm-6" id="panelInfoUsuario" style="display:none;">
          <div class="alert alert-info" style="margin-bottom:0; padding:8px 12px;">
            <strong id="lblNombreCompleto"></strong><br/>
            <small>
              <span id="lblDocUsuario"></span> &nbsp;|&nbsp;
              <i class="fa fa-building-o"></i> <span id="lblDependencia"></span><br/>
              <i class="fa fa-user"></i> <span id="lblRol"></span> &nbsp;|&nbsp;
              <i class="fa fa-key"></i> <span id="lblNomUsuario"></span>
            </small>
          </div>
        </div>
      </div>

      <!-- RESULTADOS BÚSQUEDA -->
      <div class="row" id="panelResultadosBusqueda" style="display:none;">
        <div class="col-sm-6">
          <div class="list-group" id="listaResultadosBusqueda" style="max-height:200px;overflow-y:auto;"></div>
        </div>
      </div>

      <!-- SELECTOR DE GRUPO DE MENÚ -->
      <div class="row" id="panelGrupoMenu" style="display:none; margin-top:15px;">
        <div class="col-sm-12">
          <label><i class="fa fa-th-list"></i> Grupo de menú:</label><br/>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-default btn-grupo-menu" data-id="1">
              <i class="fa fa-cogs"></i> Procesos
            </button>
            <button type="button" class="btn btn-default btn-grupo-menu" data-id="2">
              <i class="fa fa-bar-chart"></i> Reportes
            </button>
            <button type="button" class="btn btn-default btn-grupo-menu" data-id="3">
              <i class="fa fa-line-chart"></i> Estadística
            </button>
            <button type="button" class="btn btn-default btn-grupo-menu" data-id="4">
              <i class="fa fa-wrench"></i> Mantenimiento
            </button>
          </div>
        </div>
      </div>

      <!-- PANELES DE ACCESOS -->
      <div class="row" id="panelAccesos" style="display:none; margin-top:15px;">

        <!-- PANEL IZQUIERDO: ASIGNADOS -->
        <div class="col-sm-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h4 class="panel-title">
                <i class="fa fa-check-circle"></i> Accesos Asignados
                <span class="badge" id="cntAsignados">0</span>
              </h4>
            </div>
            <div class="panel-body" style="padding:5px;">
              <ul class="list-group" id="listaAsignados"
                  style="max-height:350px; overflow-y:auto; margin-bottom:0;">
                <li class="list-group-item text-muted text-center" id="msgSinAsignados">
                  <small>Sin accesos asignados</small>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- PANEL DERECHO: DISPONIBLES -->
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <i class="fa fa-list"></i> Accesos Disponibles
                <span class="badge" id="cntDisponibles">0</span>
              </h4>
            </div>
            <div class="panel-body" style="padding:5px;">
              <input type="text" id="txtFiltroDisponibles" class="form-control input-sm"
                     placeholder="&#xf002; Filtrar..." style="margin-bottom:5px;"/>
              <ul class="list-group" id="listaDisponibles"
                  style="max-height:320px; overflow-y:auto; margin-bottom:0;">
                <li class="list-group-item text-muted text-center" id="msgSinDisponibles">
                  <small>Seleccione un grupo de menú</small>
                </li>
              </ul>
            </div>
          </div>
        </div>

      </div><!-- /panelAccesos -->

    </div><!-- /panel-body -->
  </div><!-- /panel-prime -->
</div>

<!-- Hidden fields -->
<input type="hidden" id="hdnIdProfServ" value=""/>
<input type="hidden" id="hdnIdMenu" value=""/>

<?php require_once '../include/footer.php'; ?>

<script>
/* ============================================================
 *  HELPERS
 * ============================================================ */
var MODULO_LABEL = {
  'null': '<span class="label label-info">LAB</span>',
  '1':    '<span class="label label-success">IMG</span>',
  '2':    '<span class="label label-warning">TBC</span>'
};

function getModuloLabel(id_modulo) {
  if (id_modulo === null || id_modulo === '' || id_modulo === 'null') {
    return MODULO_LABEL['null'];
  }
  return MODULO_LABEL[String(id_modulo)] || '<span class="label label-default">' + id_modulo + '</span>';
}

function showMessage(msg, tipo) {
  if (tipo === 'success') {
    toastr.success(msg);
  } else if (tipo === 'error') {
    toastr.error(msg);
  } else {
    toastr.info(msg);
  }
}

/* ============================================================
 *  BUSCADOR DE USUARIO
 * ============================================================ */
var buscarTimer = null;

$('#txtBuscarUsuario').on('keyup', function() {
  var val = $(this).val().trim();
  clearTimeout(buscarTimer);
  if (val.length < 2) {
    $('#panelResultadosBusqueda').hide();
    return;
  }
  buscarTimer = setTimeout(function() {
    $.ajax({
      type: 'POST',
      url: '../../controller/ctrlUsuario.php',
      data: { accion: 'GET_BUSCAR_PROFSERV', busqueda: val },
      dataType: 'json',
      success: function(data) {
        var html = '';
        if (data.length === 0) {
          html = '<a class="list-group-item disabled"><small>Sin resultados</small></a>';
        } else {
          $.each(data, function(i, row) {
            html += '<a href="#" class="list-group-item item-resultado-busqueda" ' +
              'data-id="'       + row.id_profesionalservicio + '" ' +
              'data-dep="'      + row.nom_depen              + '" ' +
              'data-doc="'      + row.abrev_tipodoc + ': ' + row.nrodoc + '" ' +
              'data-nombre="'   + row.nombre_completo        + '" ' +
              'data-rol="'      + row.nom_rol                + '" ' +
              'data-usuario="'  + row.nom_usuario            + '">' +
              '<strong>' + row.nombre_completo + '</strong> ' +
              '<small class="text-muted">' + row.abrev_tipodoc + ': ' + row.nrodoc + '</small><br/>' +
              '<small><i class="fa fa-building-o"></i> ' + row.nom_depen + ' &mdash; ' + row.nom_rol + '</small>' +
              '</a>';
          });
        }
        $('#listaResultadosBusqueda').html(html);
        $('#panelResultadosBusqueda').show();
      }
    });
  }, 350);
});

/* Click en resultado de búsqueda */
$(document).on('click', '.item-resultado-busqueda', function(e) {
  e.preventDefault();
  var id   = $(this).data('id');
  var dep  = $(this).data('dep');
  var doc  = $(this).data('doc');
  var nom  = $(this).data('nombre');
  var rol  = $(this).data('rol');
  var usu  = $(this).data('usuario');

  $('#hdnIdProfServ').val(id);
  $('#hdnIdMenu').val('');

  $('#lblNombreCompleto').text(nom);
  $('#lblDocUsuario').text(doc);
  $('#lblDependencia').text(dep);
  $('#lblRol').text(rol);
  $('#lblNomUsuario').text(usu);

  $('#panelInfoUsuario').show();
  $('#panelResultadosBusqueda').hide();
  $('#txtBuscarUsuario').val(nom);

  // Resetear grupo de menú activo
  $('.btn-grupo-menu').removeClass('btn-primary').addClass('btn-default');
  $('#hdnIdMenu').val('');
  $('#panelGrupoMenu').show();
  $('#panelAccesos').hide();
  limpiarPaneles();
});

/* Cerrar resultados al hacer click fuera */
$(document).on('click', function(e) {
  if (!$(e.target).closest('#txtBuscarUsuario, #panelResultadosBusqueda').length) {
    $('#panelResultadosBusqueda').hide();
  }
});

/* ============================================================
 *  SELECTOR DE GRUPO DE MENÚ
 * ============================================================ */
$(document).on('click', '.btn-grupo-menu', function() {
  if ($('#hdnIdProfServ').val() === '') {
    showMessage('Primero seleccione un usuario', 'error');
    return;
  }
  $('.btn-grupo-menu').removeClass('btn-primary').addClass('btn-default');
  $(this).removeClass('btn-default').addClass('btn-primary');

  var idMenu = $(this).data('id');
  $('#hdnIdMenu').val(idMenu);
  $('#panelAccesos').show();
  cargarPaneles();
});

/* ============================================================
 *  CARGAR PANELES
 * ============================================================ */
function limpiarPaneles() {
  $('#listaAsignados').html('<li class="list-group-item text-muted text-center" id="msgSinAsignados"><small>Sin accesos asignados</small></li>');
  $('#listaDisponibles').html('<li class="list-group-item text-muted text-center" id="msgSinDisponibles"><small>Seleccione un grupo de menú</small></li>');
  $('#cntAsignados').text(0);
  $('#cntDisponibles').text(0);
  $('#txtFiltroDisponibles').val('');
}

function cargarPaneles() {
  var idProf = $('#hdnIdProfServ').val();
  var idMenu = $('#hdnIdMenu').val();
  if (!idProf || !idMenu) return;
  cargarAsignados(idProf, idMenu);
  cargarDisponibles(idProf, idMenu);
}

function cargarAsignados(idProf, idMenu) {
  $.ajax({
    type: 'POST',
    url: '../../controller/ctrlUsuario.php',
    data: { accion: 'GET_ACCESOS_ASIGNADOS', id_profesionalservicio: idProf, id_menu: idMenu },
    dataType: 'json',
    success: function(data) {
      var html = '';
      if (data.length === 0) {
        html = '<li class="list-group-item text-muted text-center"><small>Sin accesos asignados en este grupo</small></li>';
      } else {
        $.each(data, function(i, row) {
          html += '<li class="list-group-item" style="padding:5px 10px;">' +
            '<div class="clearfix">' +
            '<span class="pull-left">' + getModuloLabel(row.id_modulo) +
            ' ' + row.nom_detmenu + '</span>' +
            '<button class="btn btn-danger btn-xs pull-right btn-quitar" ' +
            'data-iddetmenu="' + row.id_detmenu + '" ' +
            'data-nom="' + row.nom_detmenu + '">' +
            '<i class="fa fa-times"></i> Quitar</button>' +
            '</div></li>';
        });
      }
      $('#listaAsignados').html(html);
      $('#cntAsignados').text(data.length);
    }
  });
}

function cargarDisponibles(idProf, idMenu) {
  $.ajax({
    type: 'POST',
    url: '../../controller/ctrlUsuario.php',
    data: { accion: 'GET_ACCESOS_DISPONIBLES', id_profesionalservicio: idProf, id_menu: idMenu },
    dataType: 'json',
    success: function(data) {
      var html = '';
      if (data.length === 0) {
        html = '<li class="list-group-item text-muted text-center"><small>No hay accesos disponibles para asignar</small></li>';
      } else {
        $.each(data, function(i, row) {
          html += '<li class="list-group-item item-disponible" style="padding:5px 10px;" ' +
            'data-nom="' + row.nom_detmenu.toLowerCase() + '">' +
            '<div class="clearfix">' +
            '<span class="pull-left">' + getModuloLabel(row.id_modulo) +
            ' ' + row.nom_detmenu + '</span>' +
            '<button class="btn btn-success btn-xs pull-right btn-agregar" ' +
            'data-iddetmenu="' + row.id_detmenu + '" ' +
            'data-nom="' + row.nom_detmenu + '">' +
            '<i class="fa fa-plus"></i> Agregar</button>' +
            '</div></li>';
        });
      }
      $('#listaDisponibles').html(html);
      $('#cntDisponibles').text(data.length);
    }
  });
}

/* ============================================================
 *  FILTRO PANEL DISPONIBLES
 * ============================================================ */
$('#txtFiltroDisponibles').on('keyup', function() {
  var val = $(this).val().toLowerCase();
  $('#listaDisponibles .item-disponible').each(function() {
    $(this).toggle($(this).data('nom').indexOf(val) >= 0);
  });
});

/* ============================================================
 *  AGREGAR ACCESO
 * ============================================================ */
$(document).on('click', '.btn-agregar', function() {
  var btn       = $(this);
  var idDetmenu = btn.data('iddetmenu');
  var nom       = btn.data('nom');
  var idProf    = $('#hdnIdProfServ').val();

  btn.prop('disabled', true);
  $.ajax({
    type: 'POST',
    url: '../../controller/ctrlUsuario.php',
    data: { accion: 'POST_ADD_ACCESO', id_profesionalservicio: idProf, id_detmenu: idDetmenu },
    success: function(data) {
      if (data === 'OK') {
        showMessage(nom + ' agregado correctamente', 'success');
        cargarPaneles();
      } else {
        showMessage('Error al agregar el acceso', 'error');
        btn.prop('disabled', false);
      }
    }
  });
});

/* ============================================================
 *  QUITAR ACCESO
 * ============================================================ */
$(document).on('click', '.btn-quitar', function() {
  var btn       = $(this);
  var idDetmenu = btn.data('iddetmenu');
  var nom       = btn.data('nom');
  var idProf    = $('#hdnIdProfServ').val();

  bootbox.confirm({
    message: '¿Quitar el acceso <b>' + nom + '</b>?',
    buttons: {
      confirm: { label: 'Sí, quitar', className: 'btn-danger' },
      cancel:  { label: 'Cancelar',   className: 'btn-default' }
    },
    callback: function(result) {
      if (result) {
        btn.prop('disabled', true);
        $.ajax({
          type: 'POST',
          url: '../../controller/ctrlUsuario.php',
          data: { accion: 'POST_DEL_ACCESO', id_profesionalservicio: idProf, id_detmenu: idDetmenu },
          success: function(data) {
            if (data === 'OK') {
              showMessage(nom + ' quitado correctamente', 'success');
              cargarPaneles();
            } else {
              showMessage('Error al quitar el acceso', 'error');
              btn.prop('disabled', false);
            }
          }
        });
      }
    }
  });
});

</script>

<?php require_once '../include/masterfooter.php'; ?>
