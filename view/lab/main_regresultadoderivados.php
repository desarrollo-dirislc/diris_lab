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
    line-height: 25px !important;
    /* <-- esto centra verticalmente */
    font-size: 12px;
    padding-left: 5px !important;
    padding-right: 20px !important;
  }

  /* Ajuste de la flecha */
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 30px !important;
    top: 0 !important;
  }

  .text-bold {
    font-weight: bold !important;
  }

  .btn-copy {
    padding: 0 2px !important;
    height: 16px !important;
    line-height: 14px !important;
    font-size: 10px !important;
  }

  /* Estilos modernos para input file y botón de carga */
  .upload-section {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    margin-top: 15px;
    max-width: 600px;
  }

  .upload-file-wrapper {
    flex: 1;
  }

  .upload-file-wrapper label {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    display: block;
  }

  .custom-file-input {
    position: relative;
    display: inline-block;
    width: 100%;
  }

  .custom-file-input input[type="file"] {
    cursor: pointer;
    padding: 8px 12px;
    border: 2px solid #ddd;
    border-radius: 6px;
    background-color: #fff;
    transition: all 0.3s ease;
    font-size: 14px;
    width: 100%;
  }

  .custom-file-input input[type="file"]:hover {
    border-color: #5cb85c;
    background-color: #f8fff8;
  }

  .custom-file-input input[type="file"]:focus {
    outline: none;
    border-color: #5cb85c;
    box-shadow: 0 0 0 3px rgba(92, 184, 92, 0.1);
  }

  .upload-btn-wrapper {
    flex: 0 0 180px;
  }

  .btn-upload-modern {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    background: linear-gradient(135deg, #5cb85c 0%, #4cae4c 100%);
    color: white;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    white-space: nowrap;
  }

  .btn-upload-modern:hover {
    background: linear-gradient(135deg, #4cae4c 0%, #449d44 100%);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
  }

  .btn-upload-modern:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .btn-upload-modern i {
    font-size: 16px;
  }
</style>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <h3 class="panel-title"><strong>PROCESAR MUESTRAS RECIBIDAS</strong></h3>
    </div>
    <div class="panel-body">
      <input type="hidden" id="txt_id_registro" value="" />
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-por-recibir" data-toggle="tab"><i class="fa fa-thumbs-o-up text-primary"></i> Adecuadas</a></li>
          <li><a href="#tab-proceso" data-toggle="tab"><i class="fa  fa-upload text-warning"></i> En proceso</a></li>
          <li><a href="#tab-validadas" data-toggle="tab"><i class="fa fa-check text-success"></i> Validadas/Atendidas</a></li>
          <li><a href="#tab-observadas" data-toggle="tab"><i class="fa fa-ban text-danger"></i> Observadas</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-por-recibir">
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
                      if ($row['id_producto'] == "60") {
                        echo " selected";
                      }
                      echo ">" . $row['nom_producto'] . "</option>";
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
                  <button type="button" class="btn btn-info btn-sm btn-block" id="btn_bus_adecuadas_reset">Restablecer filtros</button>
                </div>
              </div>
            </form>
            <p></p>
            <table id="tbl_aceptados" class="table table-hover table-bordered" cellspacing="0" width="100%">
              <thead class="bg-aqua">
                <tr>
                  <th><i class="fa fa-cogs"></i></th>
                  <th>CÓDIGO<br />ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA</br>TOMA MUESTRA</th>
                  <th>FECHA<br />ENVÍO</th>
                  <th>FECHA<br />RECEPCIÓN</th>
                  <th>ESTADO<br />RESULTADO</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-proceso">
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
                      if ($row['id_producto'] == "60") {
                        echo " selected";
                      }
                      echo ">" . $row['nom_producto'] . "</option>";
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
                  <button type="button" class="btn btn-warning btn-sm btn-block" id="btn_bus_adecuadas_reset">Restablecer filtros</button>
                </div>
                <div class="col-md-12">
                  <div class="upload-section">
                    <div class="upload-file-wrapper">
                      <label for="fl_programacion">Archivo Excel</label>
                      <div class="custom-file-input">
                        <input type="file" id="fl_programacion" accept=".xlsx,.xls">
                      </div>
                    </div>
                    <div class="upload-btn-wrapper">
                      <button id="btn_insertar" type="button" class="btn-upload-modern">
                        <i class="fa fa-upload"></i>
                        <span>Cargar Excel</span>
                      </button>
                    </div>
                  </div>
                </div>

                <script>
                  $('#btn_insertar').click(function() {

                    var fileInput = document.getElementById("fl_programacion");
                    if (fileInput.files.length === 0) {
                      alert("Seleccione un archivo Excel primero.");
                      return;
                    }

                    var formData = new FormData();
                    formData.append("excel_file", fileInput.files[0]);

                    // Mostrar spinner
                    document.getElementById("overlay-carga").style.display = "flex";

                    $.ajax({
                      url: "insert_labresultadodet.php",
                      type: "POST",
                      data: formData,
                      contentType: false,
                      processData: false,

                      success: function(response) {

                        // Ocultar y eliminar overlay (MUY IMPORTANTE)
                        let overlay = document.getElementById("overlay-carga");
                        if (overlay) {
                          overlay.style.display = "none";
                          overlay.remove();
                        }

                        alert(response);

                        // Recargar página
                        setTimeout(function() {
                          window.location.reload();
                        }, 200);
                      },

                      error: function(xhr, status, error) {

                        // También ocultar overlay si hay error
                        document.getElementById("overlay-carga").style.display = "none";

                        alert("Error: " + error);
                      }
                    });

                  });
                </script>


                <!-- SPINNER + BLOQUEO DE PANTALLA -->
                <div id="overlay-carga" style="
                      display: none;
                      position: fixed;
                      top: 0;
                      left: 0;
                      width: 100%;
                      height: 100%;
                      background: rgba(0,0,0,0.5);
                      z-index: 9999;
                      align-items: center;
                      justify-content: center;
                      text-align: center;
                  ">
                    <div style="background:white; padding:20px; border-radius:10px;">
                      <div class="spinner-border text-primary" role="status"></div>
                      <p style="margin-top:10px;">Procesando... por favor espere</p>
                    </div>
                </div>
              </div>
            </form>
            <p></p>
            <table id="tbl_enproceso" class="table table-hover table-bordered" cellspacing="0" width="100%">
              <thead class="bg-yellow">
                <tr>
                  <th><i class="fa fa-cogs"></i></th>
                  <th>CÓDIGO<br />ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA</br>TOMA MUESTRA</th>
                  <th>FECHA<br />ENVÍO</th>
                  <th>FECHA<br />RECEPCIÓN</th>
                  <th>ESTADO<br />RESULTADO</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

          <div class="tab-pane" id="tab-validadas">
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
                      if ($row['id_producto'] == "60") {
                        echo " selected";
                      }
                      echo ">" . $row['nom_producto'] . "</option>";
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
            <table id="tbl_validadas" class="table table-hover table-bordered" cellspacing="0" width="100%">
              <thead class="bg-green">
                <tr>
                  <th><i class="fa fa-cogs"></i></th>
                  <th>CÓDIGO<br />ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA</br>TOMA MUESTRA</th>
                  <th>FECHA<br />ENVÍO</th>
                  <th>FECHA<br />RECEPCIÓN</th>
                  <th>FECHA<br />RESULTADO</th>
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
                      if ($row['id_producto'] == "60") {
                        echo " selected";
                      }
                      echo ">" . $row['nom_producto'] . "</option>";
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
                  <th>CÓDIGO<br />ATENCIÓN</th>
                  <th>NOMBRE DEL PACIENTE</th>
                  <th>DEPENDENCIA ORIGEN</th>
                  <th>FECHA</br>TOMA MUESTRA</th>
                  <th>FECHA<br />ENVÍO</th>
                  <th>FECHA<br />RECEPCIÓN</th>
                  <th>MOTIVO<br />OBSERVACIÓN</th>
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
  <div class="modal fade" id="modal_ver_detalle" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="fa  fa-remove"></span></button>
          <h4 class="modal-title" id="showComenModalLabel">DETALLE EXAMEN SOLICITADO</h4>
        </div>
        <div class="modal-body">
          <!-- Datos Establecimiento -->
          <h5 class="mb-3"><strong>Establecimiento de salud</strong></h5>
          <p id="lbl_dependencia_pac" class="form-control-plaintext"></p>
          <hr style="margin-bottom: 10px;">
          <!-- Datos del paciente -->
          <h5 class="mb-3"><strong>Datos generales</strong></h5>
          <div class="row">
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Tipo Doc.:</small></label><br />
              <span id="lbl_tipo_documento_pac"></span>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold"><small>Nro. Doc.:</small></label><br />
              <span id="lbl_nro_documento_pac"></span><button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_nro_documento_pac')"><i class="fa fa-copy"></i></button>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold"><small>País de Nac:</small></label><br />
              <span id="lbl_pais_nac_pac"></span>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Primer Ape:</small></label><br />
              <span id="lbl_primer_ape_pac"></span><button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_primer_ape_pac')"><i class="fa fa-copy"></i></button>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Segun. Ape:</small></label><br />
              <span id="lbl_segundo_ape_pac"></span><button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_segundo_ape_pac')"><i class="fa fa-copy"></i></button>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Nombre(s):</small></label><br />
              <span id="lbl_nombre_pac"></span><button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_nombre_pac')"><i class="fa fa-copy"></i></button>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Fecha Nacimiento:</small></label><br />
              <span id="lbl_fnac"></span> <button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_fnac')"><i class="fa fa-copy"></i></button>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Sexo:</small></label><br />
              <span id="lbl_sexo"></span>
            </div>
            <div class="col-sm-4">
              <label class="text-primary text-bold" style="margin-bottom: 1px;"><small>Fecha toma muestra:</small></label><br />
              <span id="lbl_forden"></span> <button type="button" class="btn btn-info btn-xs btn-copy" onclick="copiarTexto('lbl_forden')"><i class="fa fa-copy"></i></button>
            </div>
          </div>
          <hr>
          <!-- Exámenes -->
          <h5 class="mb-3"><strong>Exámenen solicitado</strong></h5>
          <p class="form-control-plaintext"><span id="lista_examenes"></span></p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-warning btn-sm" id="btn_motivo_env_proceso" onclick="enviar_proceso()"><i class="fa fa-save"></i> Enviar a "En proceso"</button>
        </div>
      </div>
    </div>
  </div>

  <?php require_once '../include/footer.php'; ?>
  <script Language="JavaScript">
    var table;
    let resetInProgressAcep = false; //Para combo Adecuadas
    let resetInProgressObs = false; //Para combo Adecuadas

    function reg_resultado(idatencion, id_producto) {
      window.location = './main_regresultadoprod2.php?nroSoli=' + idatencion + '&id_prod=' + id_producto + '&ori=deri';
    }

    function imprime_resultado(idaten, iddep, idprod) {
      if (iddep != "735b90b4568125ed6c3f678819b6e058") {
        var urlwindow = "pdf_laboratorioprodn.php?p=" + iddep + "&valid=" + idaten + "&pr=" + idprod;
      } else {
        var urlwindow = "pdf_laboratorio_labref.php?p=" + iddep + "&valid=" + idaten + "&pr=" + idprod;
      }
      day = new Date();
      id = day.getTime();
      Xpos = (screen.width / 2) - 390;
      Ypos = (screen.height / 2) - 300;
      eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
    }

    function copiarTexto(idElemento) {
      const texto = document.getElementById(idElemento).innerText;

      navigator.clipboard.writeText(texto)
        .then(() => {
          showMessage("Texto copiado", "success");
        })
        .catch(err => {
          console.error('Error al copiar:', err);
        });
    }

    function enviar_proceso() {
      $('#btn_motivo_env_proceso').prop('disabled', true);
      var id = $('#txt_id_registro').val();

      var myRand = parseInt(Math.random() * 999999999999999);
      $.ajax({
        type: 'POST',
        url: '../../controller/ctrlLab.php',
        data: {
          accion: 'POST_CLASIFICA_EXAMEN_ENVIADO',
          accion_sp: 'ENV_PROCESO',
          id: id,
          rand: myRand
        },
        success: function(data) {
          $('#btn_motivo_env_proceso').prop('disabled', false);
          var tmsg = data.substring(0, 2);
          var lmsg = data.length;
          var msg = data.substring(3, lmsg);
          //console.log(tmsg);
          if (tmsg == "OK") {
            $("#tbl_aceptados").dataTable().fnDraw();
            $("#tbl_enproceso").dataTable().fnDraw();
            $("#tbl_observadas").dataTable().fnDraw();
            $('#modal_ver_detalle').modal('hide');
            showMessage("Se actualizó correctamente", "success");
          } else {
            bootbox.alert(msg);
            return false;
          }
        }
      });
    }

    function verDetalleAtencion(id) {
      $('#txt_id_registro').val(id);
      $.post("../../controller/ctrlLab.php", {
        id: id,
        accion: 'POST_DETALLE_EXAMEN_ENVIADO'
      }, function(data) {
        if (data) {
          $("#lbl_dependencia_pac").text(data.lbl_dependencia);
          $("#lbl_tipo_documento_pac").text(data.abrev_tipodoc_pac);
          $("#lbl_nro_documento_pac").text(data.nrodoc_pac);
          $("#lbl_pais_nac_pac").text(data.pais_nac_pac);
          $("#lbl_primer_ape_pac").text(data.primer_ape_pac);
          $("#lbl_segundo_ape_pac").text(data.segundo_ape_pac);
          $("#lbl_nombre_pac").text(data.nombre_pac);
          $("#lbl_fnac").text(data.fec_nac_pac);
          $("#lbl_sexo").text(data.sexo_pac);
          $("#lbl_forden").text(data.fec_cita);
          $("#lista_examenes").text(data.producto);
        }
        $('#modal_ver_detalle').modal({
          backdrop: 'static',
          keyboard: false
        });
      }, "json");
    }

    function envia_datos_reg_orion(id) {
      $("#env_orion_correcto").hide();
      $("#env_orion_error").hide();
      $("#env_diagnostica_contenido").hide();
      bootbox.confirm({
        title: "Mensaje",
        message: "Enviar datos de atención al otro sistema?",
        buttons: {
          cancel: {
            label: '<i class="fa fa-times"></i> No'
          },
          confirm: {
            label: '<i class="fa fa-check"></i> Si'
          }
        },
        callback: function(result) {
          if (result) {
            $("#loaderModal").show();
            var parametros = {
              "accion": "POST_REG_ATENCION",
              "id_atencion": id
            };
            $.ajax({
              data: parametros,
              url: '../../controller/ctrlWSOrion.php',
              type: 'post',
              dataType: 'json',
              success: function(result) {
                $("#loaderModal").hide();
                var datos = eval(result);
                if (datos[0] == "NEA") {
                  $("#env_orion_error").show();
                  $("#env_orion_correcto").hide();
                  $("#env_orion_contenido").text('No Existe Atencion');
                } else if (datos[0] == "NEE") {
                  $("#env_orion_error").show();
                  $("#env_orion_correcto").hide();
                  $("#env_orion_contenido").text('No existen examenenes homologados con el interfaz.');
                } else if (datos[0] == "RC") {
                  $("#env_orion_correcto").show();
                  $("#env_orion_error").hide();
                  $("#env_orion_contenido").text("CODIGO: " + datos[2]);
                } else {
                  $("#env_orion_error").show();
                  $("#env_orion_correcto").hide();
                  $("#env_orion_contenido").text(datos[1]);
                }
                //console.log(result)
                $('#id_modal_env_orion').modal({
                  show: true,
                  backdrop: 'static',
                  focus: true,
                });
                buscar_datos();
              }
            });
          }
        }
      });
    }

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

    function nueva_busqueda() {
      table.ajax.reload();
    }

    function nueva_busqueda_adecuada() {
      $("#tbl_aceptados").dataTable().fnDraw();
    }

    function nueva_busqueda_observada() {
      $("#tbl_observadas").dataTable().fnDraw();
    }

    function aceptar_muestra(id) {
      var myRand = parseInt(Math.random() * 999999999999999);
      $.ajax({
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
          if (tmsg == "OK") {
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
        callback: function(result) {
          if (result == true) {
            var myRand = parseInt(Math.random() * 999999999999999);

            $.ajax({
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
                if (tmsg == "OK") {
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

    function rechazar_muestra() {
      $('#btn_motivo_rechazo').prop('disabled', true);
      var id = $('#txt_id_registro').val();
      var id_motivo_rechazo = $('#txt_id_motivo_rechazo').val();
      var motivo_rechazo = $('#txt_motivo_rechazo').val().trim();

      if (id_motivo_rechazo == "") {
        $('#btn_motivo_rechazo').prop('disabled', false);
        showMessage("Seleccione el motivo del rechazo", "warning");
        return false;
      }

      var myRand = parseInt(Math.random() * 999999999999999);
      $.ajax({
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
          if (tmsg == "OK") {
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
        callback: function(result) {
          if (result == true) {
            var myRand = parseInt(Math.random() * 999999999999999);

            $.ajax({
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
                if (tmsg == "OK") {
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

    document.getElementById("txt_bus_datos_pac_ade").addEventListener("keyup", function() {
      let valor = this.value.trim();

      if (valor.length >= 2) {
        this.classList.remove("is-invalid");
      } else {
        this.classList.add("is-invalid");
      }

      // Ejecutar búsqueda al llegar a 3 caracteres
      if (valor.length >= 3) {
        nueva_busqueda_adecuada();
      } else if (valor.length == 0) {
        nueva_busqueda_adecuada();
      }
    });

    document.getElementById("txt_bus_datos_pac_obs").addEventListener("keyup", function() {
      let valor = this.value.trim();

      if (valor.length >= 2) {
        this.classList.remove("is-invalid");
      } else {
        this.classList.add("is-invalid");
      }

      // Ejecutar búsqueda al llegar a 3 caracteres
      if (valor.length >= 3) {
        nueva_busqueda_observada();
      } else if (valor.length == 0) {
        nueva_busqueda_observada();
      }
    });

    $("#txt_bus_id_dependencia_ade").on("change", function() {
      if (resetInProgressAcep) return;
      nueva_busqueda_adecuada();
    });
    $("#btn_bus_adecuadas_reset").on("click", function() {
      resetInProgressAcep = true;
      $("#txt_bus_id_dependencia_ade").val("0").change();
      $("#txt_bus_datos_pac_ade").val("");
      setTimeout(() => {
        $("#tbl_aceptados").dataTable().fnDraw();
        resetInProgressAcep = false;
      }, 100);
    });

    $("#txt_bus_id_dependencia_obs").on("change", function() {
      if (resetInProgressObs) return;
      nueva_busqueda_observada();
    });
    $("#btn_bus_observadas_reset").on("click", function() {
      resetInProgressObs = true;
      $("#txt_bus_id_dependencia_obs").val("0").change();
      $("#txt_bus_datos_pac_obs").val("");
      setTimeout(() => {
        $("#tbl_observadas").dataTable().fnDraw();
        resetInProgressObs = false;
      }, 100);
    });

    $(document).ready(function() {

      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab === 'proceso') {
        $('.nav-tabs a[href="#tab-proceso"]').tab('show');
      }

      $("#txt_bus_id_dependencia_ade, #txt_bus_id_producto_ade, #txt_bus_id_dependencia_obs, #txt_bus_id_producto_obs").select2();

      var dTableP = $('#tbl_aceptados').DataTable({
        autoWidth: false,
        bLengthChange: false,
        bProcessing: true,
        bServerSide: true,
        responsive: true,
        bInfo: true,
        bFilter: false,
        sAjaxSource: "../labrefen/tbl_psarecepcionn_acep.php",
        sServerMethod: "POST",
        fnServerParams: function(aoData) {
          aoData.push({
            name: "id_producto",
            value: $("#txt_bus_id_producto_ade").val()
          });
          aoData.push({
            name: "id_dependencia_origen",
            value: $("#txt_bus_id_dependencia_ade").val()
          });
          aoData.push({
            name: "datos_pac",
            value: $("#txt_bus_datos_pac_ade").val()
          });
        },
        language: {
          url: "../../assets/plugins/datatables/Spanish.json"
        },
        columnDefs: [{
            targets: 0,
            className: "text-center"
          }, // botones
          {
            targets: 1,
            className: "text-center small text-bold"
          }, // nro_atencion
          {
            targets: 2,
            className: "small"
          }, // paciente
          {
            targets: 3,
            className: "small"
          }, // dependencia
          {
            targets: 4,
            className: "text-center small"
          }, // fecha toma
          {
            targets: 5,
            className: "text-center small"
          }, // fecha envío
          {
            targets: 6,
            className: "text-center small"
          }, // fecha recibe
          {
            targets: 7,
            className: "fw-bold small"
          } // estado
        ],
        columns: [{
            render: (data, type, row, meta) => {
              return `<div class="">        
          <div class='text-center'>${row.btn_envio_ori} ${row.btn_rechazar}</div>
        </div>`;
            },
          },
          {
            data: "nro_atencion"
          },
          {
            data: "paciente"
          },
          {
            data: "dependencia_origen"
          },
          {
            data: "fecha_toma_muestra"
          },
          {
            data: "fecha_envio_destino"
          },
          {
            data: "fecha_recibe_destino"
          },
          {
            data: "estado_resul"
          }
        ]
      });

      var dTableP = $('#tbl_enproceso').DataTable({
        autoWidth: false,
        bLengthChange: false,
        bProcessing: true,
        bServerSide: true,
        responsive: true,
        bInfo: true,
        bFilter: false,
        sAjaxSource: "tbl_psarecepcionn_proceso.php",
        sServerMethod: "POST",
        fnServerParams: function(aoData) {
          aoData.push({
            name: "id_producto",
            value: $("#txt_bus_id_producto_ade").val()
          });
          aoData.push({
            name: "id_dependencia_origen",
            value: $("#txt_bus_id_dependencia_ade").val()
          });
          aoData.push({
            name: "datos_pac",
            value: $("#txt_bus_datos_pac_ade").val()
          });
        },
        language: {
          url: "../../assets/plugins/datatables/Spanish.json"
        },
        columnDefs: [{
            targets: 0,
            className: "text-center"
          }, // botones
          {
            targets: 1,
            className: "text-center text-bold"
          }, // nro_atencion
          {
            targets: 2,
            className: "small"
          }, // paciente
          {
            targets: 3,
            className: "small"
          }, // dependencia
          {
            targets: 4,
            className: "text-center small"
          }, // fecha toma
          {
            targets: 5,
            className: "text-center small"
          }, // fecha envío
          {
            targets: 6,
            className: "text-center small"
          }, // fecha recibe
          {
            targets: 7,
            className: "fw-bold small"
          } // estado
        ],
        createdRow: function(row, data) {
          if (data.id_estado_resul === "1") {
            $('td', row).eq(7).addClass("active");
          }
          if (data.id_estado_resul === "2") {
            $('td', row).eq(7).addClass("primary");
          }
        },
        columns: [{
            render: (data, type, row, meta) => {
              return `<div class="">        
          <div class='text-center'>${row.btn_rechazar} ${row.btn_resultado}</div>
        </div>`;
            },
          },
          {
            data: "nro_atencion"
          },
          {
            data: "paciente"
          },
          {
            data: "dependencia_origen"
          },
          {
            data: "fecha_toma_muestra"
          },
          {
            data: "fecha_envio_destino"
          },
          {
            data: "fecha_recibe_destino"
          },
          {
            data: "estado_resul"
          }
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
        sAjaxSource: "../labrefen/tbl_psarecepcionn_obs.php",
        sServerMethod: "POST",
        fnServerParams: function(aoData) {
          aoData.push({
            name: "id_producto",
            value: $("#txt_bus_id_producto_obs").val()
          });
          aoData.push({
            name: "id_dependencia_origen",
            value: $("#txt_bus_id_dependencia_obs").val()
          });
          aoData.push({
            name: "datos_pac",
            value: $("#txt_bus_datos_pac_obs").val()
          });
        },
        language: {
          url: "../../assets/plugins/datatables/Spanish.json"
        },
        columnDefs: [{
            targets: 0,
            className: "text-center small text-bold"
          }, // nro_atencion
          {
            targets: 1,
            className: "small"
          }, // paciente
          {
            targets: 2,
            className: "small"
          }, // dependencia
          {
            targets: 3,
            className: "text-center small"
          }, // fecha toma
          {
            targets: 4,
            className: "text-center small"
          }, // fecha envío
          {
            targets: 5,
            className: "text-center small"
          }, // fecha recibe
          {
            targets: 6,
            className: "fw-bold small"
          }, // estado
          {
            targets: 7,
            className: "text-center small"
          } // botón rechazar
        ],
        createdRow: function(row, data) {
          if (data.id_estado_resul === "2") {
            $('td', row).eq(6).addClass("info");
          }
          if (data.id_estado_resul === "4") {
            $('td', row).eq(6).addClass("success");
          }
        },
        columns: [{
            data: "nro_atencion"
          },
          {
            data: "paciente"
          },
          {
            data: "dependencia_origen"
          },
          {
            data: "fecha_toma_muestra"
          },
          {
            data: "fecha_envio_destino"
          },
          {
            data: "fecha_recibe_destino"
          },
          {
            render: (data, type, row, meta) => {
              const det_rechazo = row.detalle_motivo_rechazo ? `<div class='text-left'>${row.detalle_motivo_rechazo}</div>` : ``;
              return `<div class="">        
          <div class='text-left text-bold'>${row.motivo_rechazo}</div> ${det_rechazo}
        </div>`;
            },
          },
          {
            data: "btn_aceptar"
          }
        ]
      });

      var dTableP = $('#tbl_validadas').DataTable({
        autoWidth: false,
        bLengthChange: false,
        bProcessing: true,
        bServerSide: true,
        responsive: true,
        bInfo: true,
        bFilter: false,
        sAjaxSource: "tbl_psarecepcionn_validado.php",
        sServerMethod: "POST",
        fnServerParams: function(aoData) {
          aoData.push({
            name: "id_producto",
            value: $("#txt_bus_id_producto_obs").val()
          });
          aoData.push({
            name: "id_dependencia_origen",
            value: $("#txt_bus_id_dependencia_obs").val()
          });
          aoData.push({
            name: "datos_pac",
            value: $("#txt_bus_datos_pac_obs").val()
          });
        },
        language: {
          url: "../../assets/plugins/datatables/Spanish.json"
        },
        columnDefs: [{
            targets: 0,
            className: "text-center"
          }, // nro_atencion
          {
            targets: 1,
            className: "text-center text-bold"
          }, // paciente
          {
            targets: 2,
            className: "small"
          }, // dependencia
          {
            targets: 3,
            className: "text-center small"
          }, // fecha toma
          {
            targets: 4,
            className: "text-center small"
          }, // fecha envío
          {
            targets: 5,
            className: "text-center small"
          }, // fecha recibe
          {
            targets: 6,
            className: "text-center small"
          }, // estado
          {
            targets: 7,
            className: "text-center small"
          } // botón rechazar
        ],
        createdRow: function(row, data) {
          if (data.id_estado_atencion === "5") {
            $(row).addClass("success");
          }
        },
        columns: [{
            render: (data, type, row, meta) => {
              return `<div class="">        
          <div class='text-center'>${row.btn_rechazar} ${row.btn_resultado}</div>
        </div>`;
            },
          },
          {
            data: "nro_atencion"
          },
          {
            data: "paciente"
          },
          {
            data: "dependencia_origen"
          },
          {
            data: "fecha_toma_muestra"
          },
          {
            data: "fecha_envio_destino"
          },
          {
            data: "fecha_recibe_destino"
          },
          {
            data: "fecha_valida_resultado"
          }
        ]
      });

    });
  </script>
  <?php require_once '../include/masterfooter.php'; ?>