<?php require_once '../include/masterheader.php'; ?>
<?php require_once '../include/header.php'; ?>
<?php require_once '../include/sidebar.php'; ?>
<style>
#parent {
  height: 450px;
}

@media all and (min-width: 480px) {
	.deskContent {display:block;}
}

@media all and (max-width: 479px) {
	.deskContent {display:none;}
	.input-group-addon {
		display:none;
	}
}
</style>
<?php
require_once '../../model/Profesional.php';
$prof = new Profesional();
require_once '../../model/Producton.php';
$pn = new Producton();
require_once '../../model/Componente.php';
$c = new Componente();
require_once '../../model/Atencion.php';
$at = new Atencion();
$idAtencion = $_GET['nroSoli'];
$rsA = $at->get_datosAtencion($idAtencion);
//print_r($rsA);
$frm_origen = $_GET['ori'];
if($frm_origen == "LR"){
	$nro_atencion = $rsA[0]['nro_atencion_manual'];
} else {
	if($rsA[0]['id_tipo_genera_correlativo'] == "1"){
		$nro_atencion = $rsA[0]['nro_atencion'] . "-". $rsA[0]['anio_atencion'];
	} else {
		$nro_atencion = substr($rsA[0]['nro_atencion'], 0, 6).substr($rsA[0]['nro_atencion'],6);
	}
}
?>
<div class="container-fluid">
  <div class="panel-prime">
    <div class="panel-heading">
      <div class="row">
		<div class="col-sm-6">
			<h3 class="panel-title"><strong>REGISTRAR O MODIFICAR RESULTADOS <?php echo ($frm_origen <> 'deri') ? '' : " - ATENCION N°" . $nro_atencion?></strong></h3>
		</div>
		<div class="col-sm-6 text-right">
			<h3 class="panel-title"><a href="#" onclick="event.preventDefault(); open_ayuda()">Ayuda <i class="fa fa-question-circle-o" aria-hidden="true"></i></a></h3>
		</div>
	  </div>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-5 col-md-4">
			<input type="hidden" name="txt_origen" id="txt_origen" value="<?php echo $_GET['ori'];?>"/>
          <form name="frmPaciente" id="frmPaciente">
            <input type="hidden" name="txtIdAtencion" id="txtIdAtencion" value="<?php echo $_GET['nroSoli']?>"/>
			<input type="hidden" name="txt_id_dependencia" id="txt_id_dependencia" value="<?php echo $rsA[0]['id_dependencia'];?>"/>
            <input type="hidden" name="txtIngResul" id="txtIngResul" value="NO"/>
            <input type="hidden" name="txtShowOptPrint" id="txtShowOptPrint" value=""/>
			<?php if(isset($_GET['id_prod'])){$id_producto=$_GET['id_prod'];} else {$id_producto = 0;} ?>
			<input type="hidden" name="txt_id_producto_selec" id="txt_id_producto_selec" value="<?php echo $id_producto;?>"/>
            <div class="panel panel-info">
              <div class="panel-heading">
				<div class="row">
					<div class="col-sm-6"><h3 class="panel-title"><strong>DATOS DE LA ATENCION</strong></h3></div>
					<?php if($frm_origen <> 'deri'){ ?>
						<div class="col-sm-6 text-right">
							<button type="button" class="btn btn-primary btn-xs" onclick="open_lista_20_atenciones();">Ver últimas 20 atenciones</button>
						</div>
					<?php }?>
				</div>
              </div>
              <div class="panel-body" style="padding-top: 5px;">
                <div class="row">
				  <?php if($labIdDepUser == "67"){?>
					  <div class="col-sm-12">
						<label for="txtNomDepRef">EESS procedencia</label>
						<input type="text" name="txtNomDepRef" id="txtNomDepRef" class="form-control input-xs" value="<?php echo !empty($rsA[0]['nom_depenori']) ? $rsA[0]['nom_depenori'] : $rsA[0]['nom_depen'];?>" disabled/>
					  </div>
				  <?php } ?>
                  <div class="col-sm-4 col-md-3">
                    <label for="txtHCPac">HCL.</label>
                    <input type="text" name="txtHCPac" id="txtHCPac" class="form-control input-xs" value="<?php echo $rsA[0]['nro_hcpac']?>" disabled/>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <label for="txtNomPac">Paciente</label>
                    <input type="text" name="txtNomPac" id="txtNomPac" class="form-control input-xs" value="<?php echo $rsA[0]['nombre_rspac']?>" disabled/>
                  </div>
                  <!--<div class="col-sm-1 text-center">
                  <label>Detalle</label><br/>
                  <button type="button" class="btn btn-primary btn-xs" onclick="open_fua('<?php echo $_GET['nroSoli']?>');"><i class="fa fa-h-square"></i></button>
                </div>-->
                <div class="col-sm-8 col-md-4">
                  <label for="txtEdadDiaPac">Edad</label>
                  <input type="text" name="txtDetEdadPac" id="txtDetEdadPac" class="form-control input-xs" value="<?php echo $rsA[0]['edad_anio']." años ". $rsA[0]['edad_mes']. " meses ". $rsA[0]['edad_dia']. " dias."?>" disabled/>
                  <input type="hidden" name="txtEdadAnioPac" id="txtEdadAnioPac" value="<?php echo $rsA[0]['edad_anio'];?>"/>
                  <input type="hidden" name="txtEdadMesPac" id="txtEdadMesPac" value="<?php echo $rsA[0]['edad_mes'];?>"/>
                  <input type="hidden" name="txtEdadDiaPac" id="txtEdadDiaPac" value="<?php echo $rsA[0]['edad_dia'];?>"/>
                </div>
				<div class="col-sm-4 col-md-2">
                  <label for="txtSexoPac">Sexo</label>
                  <input type="text" name="txtSexoPac" id="txtSexoPac" class="form-control input-xs" value="<?php echo $rsA[0]['nom_sexopac']?>" disabled/>
                </div>
				<div class="col-sm-6 col-md-6">
				<?php 
					$peso = " - ";
					$talla = " - ";
					$etnia = " -";
					if($rsA[0]['peso_pac'] <> ""){
						$peso = $rsA[0]['peso_pac'] . "Kg.";
					}
					if($rsA[0]['talla_pac'] <> ""){
						$talla = $rsA[0]['talla_pac'] . "Cm.";
					}
					if($rsA[0]['nom_etnia'] <> ""){
						$etnia = $rsA[0]['nom_etnia'];
					}
				?>
				  <div class="form-group">
                  <label for="txtSexoPac"><small>Datos adicionales</small></label>
                  <input type="text" name="txtEstado" id="txtEstado" class="form-control input-xs" value="Peso: <?php echo $peso;?>  Talla: <?php echo $talla;?>  Etnia: <?php echo $etnia;?>" disabled/>
				  <input type="hidden" name="txtPesoPac" id="txtPesoPac" value="<?php echo $rsA[0]['peso_pac'];?>"/>
				  <input type="hidden" name="txtTallaPac" id="txtTallaPac" value="<?php echo $rsA[0]['talla_pac'];?>"/>
				  <input type="hidden" name="txtIdEtniaPac" id="txtIdEtniaPac" value="<?php echo $rsA[0]['id_etnia'];?>"/>
				  </div>
                </div>
              </div>
			  <div class="row">
				  <?php 
					if($rsA[0]['id_estado_reg'] == "5"){
						$nom_estado_atencion = $rsA[0]['nom_estadoreg'];
						$color_estado_atencion = "has-success";
					} else {
						$nom_estado_atencion = $rsA[0]['nom_estadoresul'];
						$color_estado_atencion = "";
					}
				  ?>
				<div class="form-group <?php echo $color_estado_atencion;?>">
					<label for="txtEstado" class="col-sm-6 col-md-6 control-label text-right"><small>Estado resultado</small></label>
					<div class="col-sm-6 col-md-6">
						<input type="email" class="form-control input-xs" id="txtEstado" value="<?php echo $nom_estado_atencion;?>" disabled/>
					</div>
				</div>
			  </div>
              <?php
              $nomSexo = $rsA[0]['nom_sexopac'];
              $edadAnio = $rsA[0]['edad_anio'];
              $edadMes =  $rsA[0]['edad_mes'];
              $edadDia =  $rsA[0]['edad_dia'];
			  $id_dependencia =  $rsA[0]['id_dependencia'];
              ?>
              <h5>Examen(es) solicitado(s)</h5>
              <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
						<th><small>&nbsp;</small></th>
                        <th><small>&nbsp;</small></th>
                        <th><small>Nombre</small></th>
						<th><small>&nbsp;</small></th>
						<th class='text-center'>
						<?php 
							if($_GET['ori'] <> "LR"){
						?>
							<button type="button" class="btn btn-warning btn-xs" onclick="imprime_resultado_unido_check('<?php echo $idAtencion?>');"><i class="fa fa-file-text-o"></i></button><br/>
						<?php 
							}
						?>
						<input type="checkbox" id="checkb" name="check" checked="true">
						</th>
                      </tr>
                    </thead>
                    <tbody id="datos-lista-examenenes" class="small">
                      <?php
                      $ip = (int)1;
                      $rsCtp = $at->get_datosProductoPorIdAtencion($idAtencion);
					  //print_r($rsCtp);
					  $cnt_productos = count($rsCtp);
					  $cnt_validado = (int)0;
					  $id_produc_sin_val = "";
                      foreach ($rsCtp as $rowCpt) {
						$btnIng = '';
						$btnImpr = '';
						$btnDesvalida = '';
						if($rowCpt['id_estado_envio'] == "1"){
							if($rowCpt['fec_recepciontoma'] == ""){
								$id_produc_sin_val .= $rowCpt['id_producto'];
								$colorstyle = "active";
								$btnIng = '<button type="button" class="btn btn-primary btn-xs tbn-ing-producto" onclick="reg_resultado(\'' . $idAtencion . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="glyphicon glyphicon-eject"></i></button>';
							} else {
								if($rowCpt['id_estado_resul'] == "1"){
									$id_produc_sin_val .= $rowCpt['id_producto'];
									$colorstyle = "info";
									if($cnt_productos <> 1){
										$btnIng = '<button type="button" class="btn btn-primary btn-xs tbn-ing-producto" onclick="reg_resultado(\'' . $idAtencion . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="glyphicon glyphicon-eject"></i></button>';
									}
								} else if($rowCpt['id_estado_resul'] == "2"){
									$id_produc_sin_val .= $rowCpt['id_producto'];
									//if($cnt_productos <> 1){
										$btnIng = '<button type="button" class="btn btn-success btn-xs tbn-ing-producto" onclick="reg_resultado(\'' . $idAtencion . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
									//}
									$colorstyle = "primary";
								} else {
									$colorstyle = "success";
									$btnImpr = '<button type="button" class="btn btn-warning btn-xs" onclick="imprime_resultado(\'' . md5($idAtencion) . '\',\'' . md5($id_dependencia) . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="fa fa-file-text-o"></i></button>';
									$btnIng = '<button type="button" class="btn btn-success btn-xs tbn-ing-producto" onclick="reg_resultado(\'' . $idAtencion . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
									$btnDesvalida = '<button id="btn_desv_prod' . $rowCpt['id_producto'] . '" type="button" class="btn btn-danger btn-xs" onclick="open_desvalidar_resultado(\'' . $idAtencion . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="fa fa-thumbs-down"></i></button>';
									$cnt_validado ++;
								}
							}
						} else {
							$colorstyle = "warning";
							if($rowCpt['id_estado_resul'] == "4"){
								$colorstyle = "success";
								$idAtenOri = $at->get_id_atencion_procesa_resultado($rowCpt['id_dependencia'], $rowCpt['cod_ref_nro_atencion']);
								if($idAtenOri <> ""){
									$id_atencion_md5 = md5($idAtenOri);
									$id_dependencia_md5 = md5($rowCpt['id_dependencia']);
									$btnImpr = '<button type="button" class="btn btn-warning btn-xs" onclick="imprime_resultado(\'' . $id_atencion_md5 . '\',\'' . $id_dependencia_md5 . '\',\'' . $rowCpt['id_producto'] . '\');"><i class="fa fa-file-text-o"></i></button>';
								}
								$cnt_validado ++;
							}
						}
						
                        echo "<tr>";
                        echo "<td class=\"text-center\">".$ip ++."</td>";
						?>
						<td class="text-center">
							<?php if ($ip > 2){?>
								<button type="button" class="btn btn-primary btn-xs" onclick="cambiar_orden_producto('BP',<?php echo $idAtencion;?>, <?php echo $rowCpt['id_producto'];?>);"><i class="glyphicon glyphicon-circle-arrow-up"></i></button>
							<?php } ?>
							<?php if ($ip < $cnt_productos + 1){?>
								<button type="button" class="btn btn-primary btn-xs" onclick="cambiar_orden_producto('SP',<?php echo $idAtencion;?>, <?php echo $rowCpt['id_producto'];?>);"><i class="glyphicon glyphicon-circle-arrow-down"></i></button>
							<?php }?>
							
						</td>
						<?php 
						$nom_producto = str_replace("TOMA DE MUESTRA ", "", $rowCpt['nom_producto']);
						$nom_producto = str_replace("PARA ", "", $nom_producto);
						echo "<td class='" . $colorstyle . "'><b>" . $nom_producto . "</b>";
						?>
						(<span style="font-weight: bold; cursor: pointer;" id="show-datos-adicionales-<?php echo $rowCpt['id_producto']?>" onclick="show_datos_adicionales(<?php echo $rowCpt['id_producto']?>)">+</span>)
						<div id="datos-adicionales-<?php echo $rowCpt['id_producto']?>" style="display: none;">
							<?php
								if($rowCpt['fec_recepciontoma'] <> ""){
								echo "<small>Recibido: " . $rowCpt['fec_recepciontoma']. "</small>"; 
								if($rowCpt['id_estado_resul'] == "2" OR $rowCpt['id_estado_resul'] == "4"){
									echo "<br/><small>Ing. Resul.: (" . $rowCpt['user_ing_resul'] . ") " . $rowCpt['fec_ing_resul'] . "</small>";
										if($rowCpt['user_modif_resul'] <> ""){echo "<br/><small>Mod. Resul.: (" . $rowCpt['user_modif_resul'] . ") " . $rowCpt['fec_modif_resul'] . "</small>";}
										if($rowCpt['user_valid_resul'] <> ""){echo "<br/><small>Val. Resul.: (" . $rowCpt['user_valid_resul'] . ") " . $rowCpt['fec_valid_resul'] . "</small>";}
									}
								} 
							?>
						</div>
						<?php
						echo "</td>";
						echo "<td class=\"text-center\">" . $btnIng . $btnDesvalida . $btnImpr ."</td><td class='text-center'>";
						if ($rowCpt['id_estado_resul'] == '3' OR $rowCpt['id_estado_resul'] == '4'){//3 validado //4entregado pac
						if ($rowCpt['id_estado_envio']=="1"){
						?>
							<input type="checkbox" class="check_atencion_<?php echo $rowCpt['id_atencion']?>" name="txt_<?php echo $rowCpt['id_atencion']?>_<?php echo $rowCpt['id_producto']?>" id="txt_<?php echo $rowCpt['id_atencion']?>_<?php echo $rowCpt['id_producto']?>" value="<?php echo $rowCpt['id_producto']?>" checked/></label>
						<?php
						}}
                        echo "</td></tr>";
					  }
						if($cnt_productos <> 1){
							if ($rowCpt['id_estado_resul'] == '1' OR $rowCpt['id_estado_resul'] == '2'){//3 validado //4entregado pac
								echo "<tr><td colspan='3'><b>Mostrar todos los examenes que no fueron validados</b></td>";
								$btnIng = '<button type="button" class="btn btn-primary btn-xs tbn-ing-producto" onclick="reg_resultado(\'' . $idAtencion . '\',\'\');"><i class="glyphicon glyphicon-eject"></i></button>';
								echo "<td class=\"text-center\">" . $btnIng . "</td><td>&nbsp;</td></tr>";
							}
						}
						if($cnt_productos <> 1){
							echo "<tr><td colspan='3'><b>Imprimir todos los resultados</b></td>";
							echo "<td class=\"text-center\">";?>
							<button type="button" class="btn btn-warning btn-xs" onclick="print_resul('<?php echo md5($rsA[0]['id']);?>','<?php echo md5($rsA[0]['id_dependencia']);?>','0','<?php echo $rsA[0]['nombre_rspac']?>')"><i class="fa fa-file-pdf-o"></i></button>
							<?php 
							echo "</td><td></td></tr>";
						}?>
						<tr><td colspan='3'><b>Mostrar resultado(s) a consultorio(s)</b></td></td><td class="text-center">
						<button id="btn_mostrar_resul" type="button" class="btn btn-default btn-xs" onclick="cambio_mostrar_resul('<?php echo $rsA[0]['id'];?>','<?php echo $rsA[0]['nombre_rspac']?>')"><?php echo $rsA[0]['nom_muestra_resul_servicios']?></button>
						</td><td></td></tr>
                    </tbody>
                  </table>
					<?php
						$ctn_falta_validado = (int)0;
						$ctn_falta_validado = $cnt_productos - $cnt_validado;
                    ?>
                </div>
				<div class="row">
					<div class="col-sm-10" style="padding-right: 5px;">
						<div class="form-group">
							<label for="txt_id_usuario_sello"><small>Sello y firma responsable de turno:</small></label>
							<?php $rsP = $prof->get_ListaProfesionalPoridServicioAndIdDependencia($labIdDepUser, 9, 1);?>
							<select name="txt_id_usuario_sello" id="txt_id_usuario_sello" class="form-control input-sm">
							<option value="">Seleccione</option>
							<?php
								foreach ($rsP as $row) {
									echo "<option value='" . $row['id_usuario'] . "'"; 
									if($rsA[0]['user_encargado_lab'] <> ""){if($row['id_usuario'] == $rsA[0]['user_encargado_lab']) echo "selected";} else {if($row['id_cargo'] == "8") echo "selected";}
									echo ">" . $row['primer_ape'] . " " . $row['segundo_ape'] . " " . $row['nombre_rs'] . "</option>";
								}
							?>
							</select>
						</div>
					</div>
					<div class="col-sm-2" style="padding-left: 1px;">
					<?php if($rsA[0]['user_encargado_lab'] <> "") {?>
						<br/>
						<button type="button" class="btn btn-success btn-sm" id="btn-encargado" onclick="save_encargado()"><i class="fa fa-save"></i></button>
					<?php }?>
					</div>
				</div>
            </div>
			<?php 
				if($rsA[0]['id_estado_reg'] <> "5"){
					if($rsA[0]['id_estado_resul'] == "4"){
			?>
				<div class="panel-footer text-center">
					<button type="button" class="btn btn-primary btn-lg" id="btn_entrega_pac" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_entrega_paciente()"><i class="fa fa-save"></i> Entregar a paciente</button>
				</div>
			<?php
				}}
			?>
          </div><!-- Fin Datos Personales -->
        </form>
      </div>
      <div class="col-sm-7 col-md-8">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title"><strong>DATOS DE EXAMENES</strong></h3>
          </div>
          <div class="panel-body">
            <form name="frmArea" id="frmArea">
              <div id="parent">
                <table id="fixTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Examen</th>
                      <th>Resultado</th>
                      <th>Valor de referencia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
					$countP = 0;
					if ($frm_origen == "deri") {
						$rsCtp = $at->get_datosProductoPorIdAtencion($idAtencion, $id_producto, 'MD', '2');//$idAtencion, $idProducto = 0, $tipo_reporte = '', $opt_estado = ''
					} else {
						$rsCtp = $at->get_datosProductoPorIdAtencion($idAtencion, $id_producto, 'MNR', '1');//$idAtencion, $idProducto = 0, $tipo_reporte = '', $opt_estado = ''
					}				
					//print_r($rsCtp);
					$ctn_producto_ing = count($rsCtp);
					foreach ($rsCtp as $rowP) {
						$id_user_valid_resul = $rowP['id_user_valid_resul'];
						$id_user_ing_resul = $rowP['id_user_ing_resul'];
						$id_estado_ing_resul = $rowP['id_estado_resul'];
						$countP++;
						if($countP%2==0){
							$trPColor = "success";
						} else {
							$trPColor = "";
						}
						$nom_producto = str_replace("TOMA DE MUESTRA ", "", $rowP['nom_producto']);
						$nom_producto = str_replace("PARA ", "", $nom_producto);
						echo '<tr class="'.$trPColor.'"><td colspan="3"><b><ins>'.$nom_producto.':</ins></b></td></tr>';
						echo '<tr class="'.$trPColor.'"><td style="padding-top: 1px; padding-bottom: 1px;">FECHA RECEP. MUESTRA</td><td style="padding-top: 1px; padding-bottom: 1px;">';
						if($rowP['fecha_recepciontoma'] <> ""){
							$fecha_recepmuestra = $rowP['fecha_recepciontoma'];
						} else {
							$fecha_recepmuestra = date("d/m/Y");
						}
						?>
						<input type="text" class="form-control input-sm" id="txt_<?php echo $rowP['id_producto']?>_fecha" name="txt_<?php echo $rowP['id_producto']?>_fecha" placeholder="" onfocus="this.select()" maxlength="10" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $fecha_recepmuestra;?>"/>
						<?php echo '</td><td></td><tr>';
						$rsG = $pn->get_datosGrupoPorIdProductoAndidAtencion($idAtencion, $rowP['id_producto']); //Aquí validará si existe o no existe en la tabla detresultado
						if(count($rsG) == 0){
							$rsG = $pn->get_datosGrupoPorIdProducto($rowP['id_producto'], 1); /////
						}
						foreach ($rsG as $rowG) {
							if($rowG['nom_visible'] == "SI"){
								echo '<tr class="'.$trPColor.'"><td colspan="3"><small><b>&nbsp; '.$rowG['descripcion_grupo'].'</b></small></td></tr>';
							}
							
							$rsC = $pn->get_datosComponentePorIdGrupoProdAndIdAtencion($idAtencion, $rowP['id_producto'], $rowG['id']); //Aquí validará si existe o no existe en la tabla detresultado
							if(count($rsC) == 0){
								$rsC = $pn->get_datosComponentePorIdGrupoProdAndIdDependenciaActivo($rowG['id'], $rsA[0]['id_dependencia']); //Aquí valida en editar si existe o coge esta funcion
							}
							//print_r($rsC);
							foreach ($rsC as $rowC) {
                              if ($rowC['idtipo_ingresol'] == "1"){
                                echo '<tr class="'.$trPColor.'"><td style="padding-top: 1px; padding-bottom: 1px;">'.$rowC['componente'].'</td><td style="padding-top: 1px; padding-bottom: 1px;">';
                                $idVal = "";
                                $valMin = "";
                                $valMax = "";
                                $desVal = "";
                                $totVal = "";
                                $valColor = "";
								$nameFunCalBiliAndProte = "";
                                $valRes = $rowC['det_result'];
								$id_compgrupoprod = $rowC['id'];
								$id_tipoingvalref = $rowC['id_tipo_val_ref'];
								$rsVC = $c->get_datosValidaValReferencialComp($rowC['id'], $rowC['id_tipo_val_ref'], 0, $edadAnio, $edadMes, $edadDia, $nomSexo);
								//print_r($rowC['id'].$rowC['id_metodocomponente'].'J'.$edadAnio.$edadMes.$edadDia.$nomSexo);
                                switch($rowC['idtipocaracter_ingresul']){
                                  case "1":
                                  $nameFunValida="";
                                  $nameFunValRef="";
                                  $totVal = nl2br($rowC['valor_ref']);
                                  break;
                                  case "2":
                                  $nameFunValida="keyValidLetter(this.id);";
                                  $nameFunValRef="";
                                  $totVal = nl2br($rowC['valor_ref']);
                                  break;
                                  case "3":
                                  $nameFunValida="keyValidNumber(this.id);";
                                  if(isset($rsVC[0][0])) {
                                    $idVal = $rsVC[0]['idcompvalref'];
                                    $valMin = $rsVC[0]['liminf'];
                                    $valMax = $rsVC[0]['limsup'];
                                    $desVal = $rsVC[0]['descripvalref'];
									if ($rsVC[0]['limsup'] == 99999){
										$totVal = "> " . number_format($rsVC[0]['liminf']) . "<br/>" . $rsVC[0]['descripvalref'];
									} else {
										if ($rsVC[0]['liminf'] == -1){
										$totVal = "< " . number_format($rsVC[0]['limsup']) . "<br/>" . $rsVC[0]['descripvalref'];
										} else {
											$totVal = number_format($rsVC[0]['liminf']) . " - " . number_format($rsVC[0]['limsup']) . "<br/>" . $rsVC[0]['descripvalref'];
										}
									}
									if($rowC['det_result'] <> ""){
										$valRes = number_format($rowC['det_result']);
										if($rowC['det_result'] < $valMin){
										  $valColor = "has-error";
										}
										if($rowC['det_result'] > $valMax) {
										  $valColor = "has-warning";
										}
									} else {
										$valRes = "";
									}
                                    $nameFunValRef="keyValidValRef(this.id);";
                                  } else {
                                    $totVal = nl2br($rowC['valor_ref']);
                                    $nameFunValRef="";
                                  }
                                  break;
                                  case "4":
								  $nameFunCalBiliAndProte = "sumComponenteBiliAndProte(this.id);"; //jose
                                  switch($rowC['detcaracter_ingresul']){
                                    case "1":
                                    $nameFunValida="keyValidNumberDecimalOne(this.id);";
                                    break;
                                    case "2":
                                    $nameFunValida="keyValidNumberDecimalTwo(this.id);";
                                    break;
                                    case "3":
                                    $nameFunValida="keyValidNumberDecimalThree(this.id);";
                                    break;
                                    case "4":
                                    $nameFunValida="keyValidNumberDecimalFour(this.id);";
                                    break;
                                  }
                                  if(isset($rsVC[0][0])) {
                                    $idVal = $rsVC[0]['idcompvalref'];
                                    $valMin = $rsVC[0]['liminf'];
                                    $valMax = $rsVC[0]['limsup'];
                                    $desVal = $rsVC[0]['descripvalref'];
									if ($rsVC[0]['limsup'] == 99999){
										$totVal = "> " . number_format($rsVC[0]['liminf'], $rowC['detcaracter_ingresul'], '.', '') . "<br/>" . $rsVC[0]['descripvalref'];
									} else {
										if ($rsVC[0]['liminf'] == -1){
											$totVal = "< " . number_format($rsVC[0]['limsup'], $rowC['detcaracter_ingresul'], '.', '') . "<br/>" . $rsVC[0]['descripvalref'];
										} else {
											$totVal = number_format($rsVC[0]['liminf'], $rowC['detcaracter_ingresul'], '.', '') . " - " . number_format($rsVC[0]['limsup'], $rowC['detcaracter_ingresul'], '.', '') . "<br/>" . $rsVC[0]['descripvalref'];	
										}
									}
									if($rowC['det_result'] <> ""){
										$valRes = number_format($rowC['det_result'], $rowC['detcaracter_ingresul'], '.', '');
										if($rowC['det_result'] < $valMin){
										  $valColor = "has-error";
										}
										if($rowC['det_result'] > $valMax) {
										  $valColor = "has-warning";
										}
									} else {
										$valRes = "";
									}
                                    $nameFunValRef="keyValidValRef(this.id);";
                                  } else {
                                    $totVal = nl2br($rowC['valor_ref']);
                                    $nameFunValRef="";
                                  }
                                  break;
                                  default:
                                  $nameFunValida="";
                                  $nameFunValRef="";
                                  $totVal = nl2br($rowC['valor_ref']);
                                  break;
                                }
								if($id_tipoingvalref == "2"){
									$nameFunValRefPorcentaje = "validValRefPorcentaje(this.id);";
								} else {
									$nameFunValRefPorcentaje = "";
								}
								//Funciones segú producto y compomenente
								$nameFunVolxMin_97_566 = "";
								$nameFunDepCrea_97_573 = "";
								if($rowP['id_producto'] == "97"){
									if($rowC['id'] == "566"){
										$nameFunVolxMin_97_566 = "sumVolxMinCreatinina(this.id);"; //DEPURACION DE CREATININA 24 HORAS
									}
									if($rowC['id'] == "572"){
										$nameFunDepCrea_97_573 = "sumDepCreatinina(this.id);"; //DEPURACION DE CREATININA 24 HORAS
									}
								}
								
								//Para metodo de DENGUE
								if ($valRes == ""){
								  if (($rowC['id'] == "624") Or ($rowC['id'] == "626")){
									  $valRes = "Inmunoensayo ELISA";
								  }
								}
                                ?>
                                <div class="form-group<?php echo ' '.$valColor ?>" style="margin-bottom: 1px;">
                                  <div class="input-group input-group-lg">
                                    <input type="text" class="form-control input-lg" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>" placeholder="" onfocus="this.select()" onkeypress="<?php echo $nameFunValida . " " . $nameFunValRefPorcentaje . " " . $nameFunVolxMin_97_566 . " " . $nameFunDepCrea_97_573;?>" onblur="<?php echo $nameFunValida . " " . $nameFunValRefPorcentaje . " " . $nameFunValRef . " " . $nameFunCalBiliAndProte . " " . $nameFunVolxMin_97_566 . " " . $nameFunDepCrea_97_573;?>" value="<?php echo $valRes;?>"/>
                                    <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" value="<?php echo $idVal;?>"/>
                                    <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" value="<?php echo $valMin;?>"/>
                                    <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" value="<?php echo $valMax;?>"/>
                                    <div class="input-group-addon"><div class="phoneContent"><?php echo $rowC['uni_medida']?></div></div>
									<div class="c1" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_ran" style="display: none;">0<div>
                                  </div>
                                </div>
                                <?php
                                echo '</td><td style="padding-top: 1px; padding-bottom: 1px;"><p class="help-block">'.$totVal.'</p></td></tr>';
                              } elseif ($rowC['idtipo_ingresol'] == "2") {
                                echo '<tr class="'.$trPColor.'"><td style="padding-top: 1px; padding-bottom: 1px;">'.$rowC['componente'].'</td><td style="padding-top: 1px; padding-bottom: 1px;" colspan="2">';
                                ?>
                                <div class="form-group" style="margin-bottom: 1px;">
									<?php if ($rowC['id_componente'] == "71"){ ?>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'NO SE OBSERVA QUISTE, HUEVO NI LARVAS DE PARÁSITOS')">NO SE OBSERVA QUISTE, HUEVO NI LARVAS DE PARÁSITOS</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'quiste de ')">quiste de</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'huevo de ')">huevo de</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'trofozoito de ')">trofozoito de</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Entamoeba histolytica ')">Entamoeba histolytica</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Entamoeba dispar ')">Entamoeba dispar</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Entamoeba spp ')">Entamoeba spp</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Entamoeba coli ')">Entamoeba coli</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Giardia lamblia ')">Giardia lamblia</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Blastocystis hominis ')">Blastocystis hominis</button>
										<button type="button" class="btn-default btn-xs" onclick="agregarTexto('txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>', 'Endolimax nana ')">Endolimax nana</button>
									<?php } ?>
                                  <textarea class="form-control input-lg" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>" rows="3"><?php echo $rowC['det_result'];?></textarea>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" value="<?php echo $idVal;?>"/>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" value="<?php echo $valMin;?>"/>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" value="<?php echo $valMax;?>"/>
								  <div class="c1" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_ran" style="display: none;">0<div>
                                </div>
                                <?php
                              } else {
                                    $idVal = "";
                                    $valMin = "";
                                    $valMax = "";
                                    $desVal = "";
                                    $totVal = "";
								echo '<tr class="'.$trPColor.'"><td style="padding-top: 1px; padding-bottom: 1px;">'.$rowC['componente'].'</td><td style="padding-top: 1px; padding-bottom: 1px;">';
                                ?>
                                <div class="form-group" style="margin-bottom: 1px;">
								  <?php
									$rsSelDef = $c->get_valor_defectoSeleccionResultado($rowC['idseleccion_ingresul']);
									$rsSel = $c->get_listaSeleccionResultadoPorTipo($rowC['idseleccion_ingresul']);
								  ?>
								  <select class="form-control input-lg" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>">
									<option value="" selected>-- Seleccione --</option>
									<?php
									foreach ($rsSel as $rowSel) {
										echo "<option value='" . $rowSel['id'] . "'";
										if($rowC['det_result']<> ""){
											if ($rowSel['id'] == $rowC['det_result']) echo " selected";
										} else {
											if ($rowSel['id'] == $rsSelDef) echo " selected";
										}
										echo ">" . $rowSel['nombre'] . "</option>";
									}
									?>
								  </select>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_idval" value="<?php echo $idVal;?>"/>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_inf" value="<?php echo $valMin;?>"/>
                                  <input type="hidden" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" name="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_sup" value="<?php echo $valMax;?>"/>
								  <div class="c1" id="txt_<?php echo $rowP['id_producto']."_".$rowC['id']?>_ran" style="display: none;">0<div>
                                </div>
                                <?php
								//print_r($rsSel);
								$totVal = nl2br($rowC['valor_ref']);
                                echo '</td><td style="padding-top: 1px; padding-bottom: 1px;"><p class="help-block">'.$totVal.'</p></td></tr>';
                              }
                            }
						}
						echo '<tr class="'.$trPColor.'"><td style="padding-top: 1px; padding-bottom: 1px;">FECHA VALID. RESUTADO </td><td style="padding-top: 1px; padding-bottom: 1px;">';
						if($rowP['fec_valid_resul'] <> ""){
							$fecha_valid_resul = $rowP['fec_valid_resul'];
						} else {
							$fecha_valid_resul = date("d/m/Y");
						}
						?>
						<input type="text" class="form-control input-sm" id="txt_<?php echo $rowP['id_producto']?>_fecha_valid" name="txt_<?php echo $rowP['id_producto']?>_fecha_valid" placeholder="" onfocus="this.select()" maxlength="10" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $fecha_valid_resul;?>" <?php if($_GET['ori'] == ""){ echo " ";}?>/>
						<?php echo '</td><td></td><tr>';
					}

                    ?>
                  </tfoot>
                </table>
              </div>
            </form>
          </div>
			<div class="panel-footer">
			<form name="frm_profesional" id="frm_profesional" class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-6">
						<label for="txt_id_usuario_resul"><small><b>Resultado procesado por:</b></small></label>
						<?php $rsP = $prof->get_ListaProfesionalPoridServicioAndIdDependencia($labIdDepUser, 9, 1); //id_dep, id_servicio, id_estado_profservicio ?>
						<select name="txt_id_usuario_resul" id="txt_id_usuario_resul" class="form-control input-sm">
						  <option value="">-- Seleecione un profesional--</option>
						  <?php
						  foreach ($rsP as $row) {
							echo "<option value='" . $row['id_usuario'] . "'";  
							if($id_user_ing_resul <> "") { if($row['id_usuario'] == $id_user_ing_resul) echo "selected";} else {if($row['id_usuario'] == $labIdUser) echo "selected";} 
							echo ">" . $row['primer_ape'] . " " . $row['segundo_ape'] . " " . $row['nombre_rs'] . "</option>";
						  }
						  ?>
						</select>
					</div>
					<div class="col-sm-6">
						<label for="txt_id_usuario_valid"><small><b>Resultado validado por:</b></small></label>
						<?php $rsP = $prof->get_ListaProfesionalPoridServicioAndIdDependencia($labIdDepUser, 9, 1); //id_dep, id_servicio, id_estado_profservicio ?>
						<select name="txt_id_usuario_valid" id="txt_id_usuario_valid" class="form-control input-sm">
						  <option value="">-- Seleecione un profesional--</option>
						  <?php
						  foreach ($rsP as $row) {
							echo "<option value='" . $row['id_usuario'] . "'";  
							if($id_user_valid_resul <> "") { if($row['id_usuario'] == $id_user_valid_resul) echo "selected";} else {if($row['id_usuario'] == $labIdUser) echo "selected";} 
							echo ">" . $row['primer_ape'] . " " . $row['segundo_ape'] . " " . $row['nombre_rs'] . "</option>";
						  }
						  ?>
						</select>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-12 text-center">
				<span class="text-danger">Si solo va a GUARDAR LO PROCESADO, no se va a considerar el USUARIO QUE VALIDA</span>
				  <div id="saveAtencion">
					<div class="btn-group">
					<?php 
					if (isset($id_estado_ing_resul)) {
						if($id_estado_ing_resul <> 4) { 
					?>
					  <!--<button class="btn btn-primary btn-lg" id="btn-submit-proc" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('I')"><i class="fa fa-save"></i> Guardar</button> -->
					<?php } ?>
					  <button class="btn btn-success btn-lg" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('ITV')"><i class="fa fa-save"></i> <i class="fa fa-thumbs-up"></i> Guardar y validar</button>
					<?php }
					  	/*$opt_mu = 0;
						if($ctn_producto_ing == $rsA[0]['cnt_producto']){
						if($rsA[0]['id_estado_reg']<>"5"){
						  ?>
						<button class="btn btn-primary btn-lg" id="btn-submit-en-pac" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('ITEP')"><i class="fa fa-save"></i>  Guardar y entregar a paciente</button>
					  <?php 
					  $opt_mu = 1;
					  }} ?>
					  <?php 
					  if($opt_mu == 0){
					  if($ctn_falta_validado == 1){
						 if($id_produc_sin_val == $id_producto){
						  ?>
						<button class="btn btn-primary btn-lg" id="btn-submit-en-pac" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('ITEP')"><i class="fa fa-save"></i> Guardar y entregar a paciente</button>
					  <?php $opt_mu = 1;
					  }
					  if ($opt_mu == 0 And $id_producto == 0){
					    ?>
						<button class="btn btn-primary btn-lg" id="btn-submit-en-pac" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Espere" data-done-text="<i class='fa fa-save'></i> Guardar" onclick="save_atencion('ITEP')"><i class="fa fa-save"></i> Guardar y entregar a paciente</button>
					  <?php
					  } } }*/
					  ?>
					</div>
					<hr style="margin-top: 7px; margin-bottom: 7px;"/>
					<div class="btn-group">
						<?php if($frm_origen <> 'deri'){?>
							<button type="button" class="btn btn-default btn-lg" onclick="reg_solicitud();"><i class="glyphicon glyphicon-plus"></i>  Nueva atención</button>
							<button type="button" class="btn btn-default btn-lg" onclick="buscar_paciente();"><i class="glyphicon glyphicon-search"></i>  Buscar paciente</button>
						<?php } else { ?>
							<button type="button" class="btn btn-default btn-lg" onclick="reg_solicitud();"><i class="glyphicon glyphicon-search"></i> Volver listado</button>
						<?php }?>
					</div>
				  </div>
				  <div id="impriAtencion" style="display: none;">
					<div class="btn-group">
						<button class="btn btn-lg btn-success" id="btn-edit-otro-prod" onclick="open_edit()"  style="display: none;"><i class="glyphicon glyphicon-pencil"></i> Editar resultados</button>
						<button class="btn btn-lg btn-success" id="btn-edit" onclick="open_edit()"  style="display: none;"><i class="glyphicon glyphicon-pencil"></i> Editar resultados</button>
						<button class="btn btn-lg btn-primary" id="btn-imrimirall" onclick="print_resul('<?php echo md5($rsA[0]['id']);?>','<?php echo md5($rsA[0]['id_dependencia']);?>','0','<?php echo $rsA[0]['nombre_rspac']?>')"><i class="fa fa-file-pdf-o"></i> imprimir ficha de resultado</button>
					</div>
				  </div>
				</div>
			  </div>
			</div>
        </div><!-- Fin Datos de Parentesco -->
      </div>
    </div>
  </form>
</div>
</div>
</div>

<div class="modal fade" id="mostrar_ayuda" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
			<h2 class="modal-title">AYUDA</h2>
		</div>
		<div class="modal-body">
			<p class="text-left small" style="margin: 0 0 0px;"><b>Botones de acción</b>:<br/> <button class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-eject"></i></button>=Seleccionar examen para ingresar resultado | <button class="btn btn-success btn-xs"><i class="glyphicon glyphicon-pencil"></i></button>=Seleccionar examen  para editar resultado | <button class="btn btn-warning btn-xs"><i class="fa fa-file-text-o"></i></button>=Imprimir Resultado</p>
			<hr/>
			<h5>Leyenda:</h5>
				<div class="table-responsive">
				  <table class="table table-bordered table-hover">
					<thead>
					  <tr><th><small>COLOR</small></th><th><small>DESCRIPCIÓN</small></th></tr>
					</thead>
					<tbody>
						<tr><td class="active"><small>Plomo</small></td><td><small>Muestra no recibida</small></td></tr>
						<tr><td class="info"><small>Celeste</small></td><td><small>Muestra recibida</small></td></tr>
						<tr><td class="primary"><small>Azul</small></td><td><small>Resultado ingresado pero sin validar</small></td></tr>
						<tr><td class="success"><small>Verde</small></td><td><small>Resultado validado</small></td></tr>
						<tr><td class="warning"><small>Amarrillo</small></td><td><small>Muestra derivada o referenciada a otro EESS</small></td></tr>
					</tbody>
				  </table>
				</div>
		</div>
		<div class="modal-footer">
		<button class="btn btn-default btn-block" type="button" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar Ventana</button>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="showLisDepModal" tabindex="-1" role="dialog" aria-labelledby="showLisDepModalLabel">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <h4 class="modal-title" id="showLisDepModalLabel">ULTIMAS ATENCIONES REGISTRADAS</h4>
	</div>
	<div class="modal-body">
	  <div id="datos-lis-dep" style="height: 250px;">
		<table id="fixTableDep" class="table table-bordered table-hover">
		  <thead>
			<tr>
			  <th><small>NRO. ATENCION</small></th>
			  <th><small>NOMBRE DE PACIENTE</small></th>
			  <th><small>NRO. DOCUMENTO</small></th>
			  <th><small>ESTADO RESULTADO</small></th>
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

<div id="mostrar_anexos_d" class="modal fade" role="dialog" data-backdrop="static"></div>


<div class="modal fade in" id="modal-save-atencion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class='fa fa-exclamation-circle' style='font-size: 20px;'></i> Alerta</h4>
			</div>
		<div class="modal-body">
			<span>Se registrarán los registros ingresados, ¿Está seguro de continuar?</span>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="opt_accion_save" value=""/>
			<button type="button" class="btn btn-danger" id="btn-save-atencion-no" onclick="hidden_atencion_ahora()">No</button>
			<button type="button" class="btn btn-success" id="btn-save-atencion-si" onclick="save_atencion_ahora()">Si</button>
		</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="modal-accion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><i class='fa fa-exclamation-circle' style='font-size: 20px;'></i> DESVALIDAR EXAMEN</h4>
			</div>
		<div class="modal-body">
			<div class="form-group" id="group_txt_obs">
				<label for="txt_obs_desva_resultado">Ingrese motivo:</label>
				<textarea id="txt_obs_desva_resultado" class="form-control" rows="3"></textarea>
				<span class="help-block" style="color: red;">
					La descripción debe tener entre 10 y 500 caracteres.
				</span>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" id="btn-save-desvalidar-canc" onclick="hidden_desvalidar_resultado()">Cancelar</button>
			<button type="button" class="btn btn-primary" id="btn-save-desvalidar-guar" onclick="desvalidar_resultado()"><i class="fa fa-save"></i> Guardar</button>
		</div>
		</div>
	</div>
</div>

<?php require_once '../include/footer.php'; ?>
<script Language="JavaScript">

function save_entrega_paciente(){
    $('#btn_entrega_pac').prop("disabled", true);

    bootbox.confirm({
      message: 'Se cambiará el estado a <b>ENTREGADO A PACIENTE</b>. ¿Está seguro de continuar?',
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
          $.ajax({
            url: "../../controller/ctrlAtencion.php",
            type: "POST",
            data: {
              accion: 'POST_ADD_REG_ACCION_COMPLEMENTARIA', id_atencion: $('#txtIdAtencion').val(), accion_sp: 'EP', detalle: '',
            },
            success: function (data) {
              if(data == ""){
					window.location = './main_regresultadoprod2.php?nroSoli=' + $('#txtIdAtencion').val() + '&ori=' + $('#txt_origen').val();
              } else {
                bootbox.alert(data);
				$('#btn_entrega_pac').prop("disabled", false);
                return false;
              }
            }
          });
        } else {
          $('#btn_entrega_pac').prop("disabled", false);
        }
      }
	});
}

function sumVolxMinCreatinina(id){
	var prival = parseFloat($("#txt_97_566").val());
	var segval = parseFloat(1440);
	var total = prival / segval;
	if (isNaN(total)){
		total='';
	} else {
		total=total.toFixed(2);
	}
	$("#txt_97_567").val(total);
	//alert($("#txtPesoPac").val());
	if(($("#txtPesoPac").val() == "") || ($("#txtTallaPac").val() == "")){
		showMessage("Peso o talla no registrado", "error");		
	} else {
		var peso = parseFloat($("#txtPesoPac").val());
		var talla = parseFloat($("#txtTallaPac").val());
		var totSup = (peso * talla)/3600;
		if (isNaN(totSup)){
			//totSup='';
		} else {
			totSup=totSup.toFixed(2);
			totSup=Math.sqrt(totSup);
			totSup=totSup.toFixed(2);
			$("#txt_97_570").val(totSup);
		}
	}
	
	if(($("#txt_97_571").val() != "") && ($("#txt_97_572").val() != "")){
		sumDepCreatinina('');
	}
}

function sumDepCreatinina(id){
	if(($("#txtPesoPac").val() == "") || ($("#txtTallaPac").val() == "")){
		//showMessage("Peso o talla no registrado", "error");		
	} else {
		var creaori = parseFloat($("#txt_97_571").val());
		var volxmin = parseFloat($("#txt_97_567").val());
		var creasan = parseFloat($("#txt_97_572").val());
		//alert($("#txt_97_567").val());
		var totDepcrea = (creaori*volxmin)/creasan;
		if (isNaN(totDepcrea)){
			//totDepcrea='';
		} else {
			totDepcrea=totDepcrea.toFixed(2);
			$("#txt_97_573").val(totDepcrea);
			
			var valmanual = parseFloat(1.73);
			var supCorpor = parseFloat($("#txt_97_570").val());
			var totDepcreaCo =  (totDepcrea*valmanual)/supCorpor;
			totDepcreaCo=totDepcreaCo.toFixed(1);
			$("#txt_97_574").val(totDepcreaCo);
			
			totCrea = Math.pow(creasan, -1.154);
			totCrea=totCrea.toFixed(1);
			
			if($("#txtIdEtniaPac").val() != ""){
				var edadpaccre = parseFloat($("#txtEdadAnioPac").val());
				if($("#txtIdEtniaPac").val() != "61"){
					if($("#txtSexoPac").val() == "F"){
						var totTasaFil = Math.pow(edadpaccre, -0.203)*0.742;
						totTasaFil=totTasaFil.toFixed(2);
						var total = 186*totTasaFil*totCrea;
						total=total.toFixed(1);
						$("#txt_97_576").val(total);
					} else {
						var totTasaFil = Math.pow(edadpaccre, -0.203);
						totTasaFil=totTasaFil.toFixed(2);
						var total = 186*totTasaFil*totCrea;
						total=total.toFixed(1);
						$("#txt_97_576").val(total);						
					}
				} else {
					if($("#txtSexoPac").val() == "F"){
						var totTasaFil = Math.pow(edadpaccre, -0.203)*0.742*1.21;
						totTasaFil=totTasaFil.toFixed(2);
						var total = 186*totTasaFil*totCrea;
						total=total.toFixed(1);
						$("#txt_97_576").val(total);
					} else {
						var totTasaFil = Math.pow(edadpaccre, -0.203)*1.21;
						totTasaFil=totTasaFil.toFixed(2);
						var total = 186*totTasaFil*totCrea;
						total=total.toFixed(1);
						$("#txt_97_576").val(total);						
					}
				}
			}
		}
	}
}


function sumComponenteBiliAndProte(id){
	//Alburrina
	if(id == 'txt_53_112' || id == 'txt_53_41'){
		var c = parseFloat($("#txt_53_112").val());
		var r = parseFloat($("#txt_53_41").val());
		var total = c - r;
		if (isNaN(total)){
			total='';
		} else {
			total=total.toFixed(2);
		}
		$("#txt_53_42").val(total);
	}
	
	if(id == 'txt_23_27' || id == 'txt_23_28'){
		var c = parseFloat($("#txt_23_27").val());
		var r = parseFloat($("#txt_23_28").val());
		var total = c - r;
		if (isNaN(total)){
			total='';
		} else {
			total=total.toFixed(2);
		}
		$("#txt_23_29").val(total); //parseInt
	}
	
	//Proteina
	if(id == 'txt_53_113' || id == 'txt_53_140'){
		var c = parseFloat($("#txt_53_113").val());
		var r = parseFloat($("#txt_53_140").val());
		var total = c - r;
		if (isNaN(total)){
			total='';
		} else {
			total=total.toFixed(2);
		}
		$("#txt_53_45").val(total);
	}
	
	if(id == 'txt_22_24' || id == 'txt_22_25'){
		var c = parseFloat($("#txt_22_24").val());
		var r = parseFloat($("#txt_22_25").val());
		var total = c - r;
		if (isNaN(total)){
			total='';
		} else {
			total=total.toFixed(1);
		}
		$("#txt_22_26").val(total);
	}	
}

function open_ayuda(){
  $('#mostrar_ayuda').modal();
}

function buscar_paciente() {
  
	var ori = $('#txt_origen').val();
	if(ori == "LR"){
		window.location = '../labrefen/main_principalsoli.php';
	} else {
		window.location = './main_principalsoli.php';
	}
}

function reg_solicitud() {
	var ori = $('#txt_origen').val();
	if(ori == "LR"){
		window.location = '../labrefen/main_regsolicitudlabref.php';
	} else if (ori == "deri"){
		window.location = './main_regresultadoderivados.php?tab=proceso';
	}  else {
		window.location = './main_regsolicitudsinfua.php';
	}
}

function reg_resultado(idatencion,id_prod) {
	if(id_prod == ""){
		window.location = './main_regresultadoprod2.php?nroSoli='+idatencion + '&ori=' + $('#txt_origen').val();
	} else {
		window.location = './main_regresultadoprod2.php?nroSoli='+idatencion + '&id_prod=' + id_prod + '&ori=' + $('#txt_origen').val();
	}
}

function imprime_resultado(idaten, iddep, idprod) {
	if(iddep != "735b90b4568125ed6c3f678819b6e058") {
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

function imprime_resultado_unido(idaten, iddep, idprod) {
    var urlwindow = "pdf_laboratorion.php?p=" + iddep + "&valid=" + idaten + "&pr=" + idprod;
  day = new Date();
  id = day.getTime();
  Xpos = (screen.width / 2) - 390;
  Ypos = (screen.height / 2) - 300;
  eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function imprime_resultado_area(idaten, iddep, idprod) {
    var urlwindow = "pdf_laboratorio_area.php?p=" + iddep + "&valid=" + idaten + "&pr=" + idprod;
  day = new Date();
  id = day.getTime();
  Xpos = (screen.width / 2) - 390;
  Ypos = (screen.height / 2) - 300;
  eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function imprime_resultado_unido_check(idaten) {
	if ($('input.check_atencion_' + idaten).is(':checked')) {
	  var id_producto = [];
	  $.each($('input.check_atencion_' + idaten), function() {
		if( $('#txt_'+idaten+'_'+$(this).val()).is(':checked') ){
			id_producto.push($(this).val());
		}
	  });
	} else {
	  var id_producto = '';
	}
  var urlwindow = "pdf_laboratorion_check.php?p=&valid=" + idaten + "&pr=" + id_producto;
  day = new Date();
  id = day.getTime();
  Xpos = (screen.width / 2) - 390;
  Ypos = (screen.height / 2) - 300;
  eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function print_resul(idAten, idDep, idProd, nomPac){
  $('#mostrar_anexos_d').modal('show');
  $.ajax({
    url: '../../controller/ctrlAtencion.php',
    type: 'POST',
    data: 'accion=GET_SHOW_PDFATENCION&idAten=' + idAten +'&idDep=' + idDep +'&idProd=' + idProd +'&nomPac=' + nomPac,
    success: function(data){
      $('#mostrar_anexos_d').html(data);
    }
  });
}

function save_encargado(){
	$('#btn-encargado').prop("disabled", true);
	id_atencion = $('#txtIdAtencion').val();
	id_usuario_sello = $('#txt_id_usuario_sello').val();
	$.ajax({
		url: '../../controller/ctrlLab.php',
		type: 'POST',
		data: 'accion=POST_ADD_CAMBIAENCARGADOTURNO&id_atencion=' + id_atencion +'&id_usuario_sello=' + id_usuario_sello,
		success: function(data){
			if(data == ""){
				showMessage("Responsable de turno para la atención, actualizado correctamente.", "success");
			} else {
				showMessage(data, "error");
			}
			$('#btn-encargado').prop("disabled", false);
		}
	});
}

function cambio_mostrar_resul(id,pac) {
	$('#btn_mostrar_resul').prop("disabled", true);
	est_actu = $('#btn_mostrar_resul').text();
	if(est_actu == "NO"){
		est_nue = "SI"
		id_est_nue = "1";
	} else {
		est_nue = "NO"
		id_est_nue = "0";		
	}
	
	bootbox.confirm({
	message: "Se cambiará el estado para mostrar o no el resultado del paciente: <br/><b>"+pac+"</b><br/>¿Está seguro de continuar?",
	buttons: {
	  confirm: {
		label: '<i class="fa fa-check"></i> Si',
		className: 'btn-success'
	  },
	  cancel: {
		label: '<i class="fa fa-times"></i> No',
		className: 'btn-danger'
	  }
	},
	callback: function (result) {
	  if (result == true) {
		var parametros = {
		  "accion": "POST_REG_CAMBIAMUESTRA_RESULTADO",
		  "id_atencion": id,
		  "id_est_nuevo": id_est_nue
		};
		$.ajax({
		  data: parametros,
		  url: '../../controller/ctrlAtencion.php',
		  type: 'post',
		  success: function (rs) {
			$('#btn_mostrar_resul').text(est_nue);
			$('#btn_mostrar_resul').prop("disabled", false);
		  }
		});
	  } else {
		$('#btn_mostrar_resul').prop("disabled", false);
	  }
	}
	});
}

function open_pdf(idAten, opt, idAreaProd) {
  if(opt == "1"){
    var urlwindow = "pdf_laboratorio.php?id_atencion=" + idAten +"&id_area=" + idAreaProd;
  } else {
    var urlwindow = "pdf_laboratorioprod.php?id_atencion=" + idAten +"&id_prod=0";
  }
  day = new Date();
  id = day.getTime();
  Xpos = (screen.width / 2) - 390;
  Ypos = (screen.height / 2) - 300;
  eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function open_fua(id) {
  window.location = '../fua/genera_fuaxls.php?nroAtencion='+id;
}

function open_edit() {
  var id = $('#txtIdAtencion').val();
  window.location = './main_editresultadoprod.php?nroSoli='+id;
}

function load_focus_inicio(){
  var nameInput = '';
  var AnameInput = $('#frmArea').serializeArray();
  nameInput = AnameInput[0]['name'];
  $("#" + nameInput).trigger('focus');
}

function keyValidValRef(id) {
  var valIng = $('#' + id).val();
  if (valIng != ""){
    valIng = $('#' + id).val();
    var valInf = $('#' + id + "_inf").val();
    var valSup = $('#' + id + "_sup").val();
    valIng = Number(valIng);
    valInf = Number(valInf);
    valSup = Number(valSup);
    if(valIng < valInf){//Menor
      $('#' + id).closest(".form-group").removeClass("has-error");
      $('#' + id).closest(".form-group").addClass("has-error");
	  $('#' + id + '_ran').text("1");
    }
    if(valIng > valSup) {//Mayor
      $('#' + id).closest(".form-group").removeClass("has-error");
      $('#' + id).closest(".form-group").addClass("has-error");
	  $('#' + id + '_ran').text("1");
    }
    if(valIng >= valInf && valIng <= valSup){
      $('#' + id).closest(".form-group").removeClass("has-error");
      $('#' + id).closest(".form-group").removeClass("has-error");
	  $('#' + id + '_ran').text("0");
    }
  } else {
    $('#' + id).closest(".form-group").removeClass("has-error");
    $('#' + id).closest(".form-group").removeClass("has-error");
	$('#' + id + '_ran').text("0");
  }
}
function hidden_atencion_ahora(){
	opt = $('#opt_accion_save').val();
	habilita_btn_save_atencion(opt);
	$("#modal-save-atencion").modal("hide");
}

function habilita_btn_save_atencion(opt) {
	if(opt == "ITV"){
		$('#btn-submit').prop("disabled", false);
	} else {
		$('#btn-submit-proc').prop("disabled", false);
	}
}

function save_atencion(opt) {
	if(opt == "ITV"){
		$('#btn-submit').prop("disabled", true);
	} else {
		$('#btn-submit-proc').prop("disabled", true);
	}
	var msg = "";
	var sw = true;

	var AnameInput = $('#frmArea').serializeArray();
	var ing = "";
	len = AnameInput.length;
	for (i=0; i<len; i++) {
		nameInput = AnameInput[i]['name'];
		var arrayCadenas = nameInput.split('_');
		if(arrayCadenas.length == 3){
		  if($("#" + nameInput).val().trim() != ""){
			ing = "1";
			break;
		  }
		}
	}

	if(ing == ""){
		msg += "Ingrese la información de almenos un exámen<br/>";
		sw = false;
	}  
	if($('#txt_id_usuario_resul').val()==""){
		msg += "Seleccione el personal que ha procesado el resultado<br/>";
		sw = false;
	}
	if(opt == "ITV"){
		if($('#txt_id_usuario_valid').val()==""){
			msg += "Seleccione el personal que ha validado el resultado<br/>";
			sw = false;
		}
	} else {
		
	}
	if($('#txt_id_usuario_sello').val()==""){
		msg += "Seleccione el responsable de turno<br/>";
		sw = false;
	}
	if (sw == false) {
		bootbox.alert(msg);
		habilita_btn_save_atencion(opt);
		return false;
	}
	
	$('#opt_accion_save').val(opt);
	
	$('#modal-save-atencion').modal({
		show: true,
		backdrop: 'static',
		focus: true,
	});

	var sinvalorref = 0;
	var myClasses = document.getElementsByClassName("c1");
	for (var i = 0; i < myClasses.length; i++) {
		if(myClasses[i].innerText == "1"){
			sinvalorref = 1;
			break;
		}
	}
	//alert(sinvalorref);
	if(sinvalorref === 1){
		$('#modal-save-atencion').on('shown.bs.modal', function (e) {
			var modal = $(this);
			modal.find('.modal-body span').html('<b><i class="text-red">Existen valores ingresados fuera del valor de referencica.</i></b><br/>Se registrarán los registros ingresados, ¿Está seguro de continuar?');
		})
		$('#modal-save-atencion').addClass("modal-warning"); // Cambia el color a otro de tu preferencia
	} else {
		$('#modal-save-atencion').on('shown.bs.modal', function (e) {
			var modal = $(this);
			modal.find('.modal-body span').html('Se registrarán los registros ingresados, ¿Está seguro de continuar?');
		})
		$('#modal-save-atencion').removeClass("modal-warning"); // Cambia el color a otro de tu preferencia		
	}
}


function save_atencion_ahora(opt) {
	opt = $('#opt_accion_save').val();
  /*$('#btn-submit').prop("disabled", true);
  var msg = "";
  var sw = true;

  var AnameInput = $('#frmArea').serializeArray();
  var ing = "";
  len = AnameInput.length;
  for (i=0; i<len; i++) {
    nameInput = AnameInput[i]['name'];
    var arrayCadenas = nameInput.split('_');
    if(arrayCadenas.length == 3){
      if($("#" + nameInput).val().trim() != ""){
        ing = "1";
        break;
      }
    }
  }

  if(ing == ""){
    msg += "Ingrese la información de almenos un exámen<br/>";
    sw = false;
  }  
  if($('#txt_id_usuario_valid').val()==""){
    msg += "Seleccione el personal que ha procesado y validado el resultado<br/>";
    sw = false;
  }
  if($('#txt_id_usuario_sello').val()==""){
    msg += "Seleccione el responsable de turno<br/>";
    sw = false;
  }
  if (sw == false) {
    bootbox.alert(msg);
    $('#btn-submit').prop("disabled", false);
    return false;
  }*/

  /*bootbox.confirm({
	title: "<i class='fa fa-exclamation-circle' style='font-size: 20px; color: white'></i> Alerta",
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
      if (result == true) {*/

        $.ajax( {
          type: 'POST',
          url: '../../controller/ctrlLab.php',
          data: $('#frmArea').serialize()
          + "&id_atencion=" + $('#txtIdAtencion').val() + "&id_dependencia=" + $('#txt_id_dependencia').val() + "&txt_id_producto_selec=" + $('#txt_id_producto_selec').val() + "&txtNroRefAtencion=" + $('#txtNroRefAtencion').val() + "&accion_sp=" + opt
          + "&accion=POST_REG_RESULTADOLABSINMUESTRA&id_usuario_resul="+ $('#txt_id_usuario_resul').val() + "&id_usuario_valid=" + $('#txt_id_usuario_valid').val() + "&id_usuario_sello=" + $('#txt_id_usuario_sello').val() + "&origen_procesa=" + $('#txt_origen').val(),
          success: function(data) {
            var tmsg = data.substring(0, 2);
            var lmsg = data.length;
            var msg = data.substring(3, lmsg);
            if(tmsg == "OK"){
				if($('#txt_origen').val() == 'deri'){
					window.location = './main_regresultadoprod2.php?nroSoli=' + $('#txtIdAtencion').val() + '&id_prod=' + $('#txt_id_producto_selec').val() + '&ori=' + $('#txt_origen').val();
				} else {
					window.location = './main_regresultadoprod2.php?nroSoli=' + $('#txtIdAtencion').val() + '&ori=' + $('#txt_origen').val();
				}

            } else {
              habilita_btn_save_atencion(opt);
              showMessage(msg, "error");
              return false;
            }
          }
        });
/*      } else {
        $('#btn-submit').prop("disabled", false);
      }
    }
  });*/
}

function open_desvalidar_resultado(id_atencion, id_producto){
	$('#btn_desv_prod' + id_producto).prop("disabled", true);
	$('#txtIdAtencion').val(id_atencion);
	$('#txt_id_producto_selec').val(id_producto);
	
    $('#modal-accion').modal({
      show: true,
      backdrop: 'static',
      focus: true,
    });
}

function desvalidar_resultado(){
	$('#btn-save-desvalidar-guar').prop("disabled", true);
	let descripcion = $('#txt_obs_desva_resultado').val().trim();
	if (descripcion === "") {
		showMessage("La descripción no puede estar vacía.", "error");
		$('#btn-save-desvalidar-guar').prop("disabled", false);
		return false;
	}
	if (descripcion.length < 10) {
		showMessage("La descripción debe tener al menos 10 caracteres.", "error");
		$('#btn-save-desvalidar-guar').prop("disabled", false);
		return false;
	} else if (descripcion.length > 500) {
		showMessage("La descripción no puede superar los 500 caracteres.", "error");
		$('#btn-save-desvalidar-guar').prop("disabled", false);
		return false;
	}
	if (!descripcion.replace(/\s/g, '').length) {
		showMessage("La descripción no puede contener solo espacios.", "error");
		$('#btn-save-desvalidar-guar').prop("disabled", false);
		return false;
	}

    bootbox.confirm({
      message: 'Se quitará la validación del examen seleccionado. ¿Está seguro de continuar?',
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
		$('#btn_desv_prod' + $('#txt_id_producto_selec').val()).prop("disabled", false);
        if (result == true) {
			$.ajax({
				url: '../../controller/ctrlAtencion.php',
				type: 'POST',
				data: 'accion=POST_REG_DESVALIDAR_XAMEN&id_atencion=' + $('#txtIdAtencion').val() + '&id_producto=' + $('#txt_id_producto_selec').val() + '&obs_desva_resultado=' + $('#txt_obs_desva_resultado').val(),
				success: function(data){
					window.location = './main_regresultadoprod2.php?nroSoli=' + $('#txtIdAtencion').val() + '&id_prod=' + $('#txt_id_producto_selec').val() + '&ori=' + $('#txt_origen').val();
					$("#modal-accion").modal("hide");
				}
			});
        }
      }
	});
}

function hidden_desvalidar_resultado(){
	$('#btn_desv_prod' + $('#txt_id_producto_selec').val()).prop("disabled", false);
	$("#modal-accion").modal("hide");
}

function show_datos_adicionales(opt) {
  if($('#show-datos-adicionales-'+opt).text() == "+"){
    $('#datos-adicionales-'+opt).show();
    $('#show-datos-adicionales-'+opt).text("-");
  } else {
    $('#datos-adicionales-'+opt).hide();
    $('#show-datos-adicionales-'+opt).text("+");
  }
}

function cambiar_orden_producto(opt,id_atencion,id_producto){
  $.ajax({
    url: '../../controller/ctrlAtencion.php',
    type: 'POST',
    data: 'accion=POST_REG_CAMBIAR_ORDENEXAMEN&id_atencion=' + id_atencion + '&id_producto=' + id_producto + '&opt=' + opt,
    success: function(data){
      mostrar_lista_examenes();
    }
  });
}

function mostrar_lista_examenes(){
  $.ajax({
    url: '../../controller/ctrlAtencion.php',
    type: 'POST',
    data: 'accion=GET_SHOW_LISTAEXAMENES_PARA_ORDEN&idAten=' + $('#txtIdAtencion').val() + '&id_dependencia=' + $('#txt_id_dependencia').val(),
    success: function(data){
      $('#datos-lista-examenenes').html(data);
    }
  });
}

function open_lista_20_atenciones(){
    $('#showLisDepModal').modal({
      show: true,
      backdrop: 'static',
      focus: true,
    });
	
	$.ajax({
		url: '../../controller/ctrlAtencion.php',
		type: 'POST',
		data: 'accion=GET_SHOW_LISTAULTI20EXAMENES&id_dependencia=' + $('#txt_id_dependencia').val() + '&ori=' + $('#txt_origen').val(),
		success: function(data){
			$('#datos-lis-dep').html(data);
		}
	});
}

function reg_resultado_new(idatencion) {
	window.location = './main_regresultadoprod2.php?nroSoli=' + idatencion + '&ori=' + $('#txt_origen').val();
}

function agregarTexto(id, texto) {
  const textarea =  document.getElementById('' + id + '');
  const inicio = textarea.selectionStart;
  const fin = textarea.selectionEnd;
  const contenido = textarea.value;

  // Insertar el texto en la posición del cursor
  const nuevoContenido = contenido.slice(0, inicio) + texto + contenido.slice(fin);

  textarea.value = nuevoContenido;

  // Ajustar la posición del cursor después de agregar el texto
  const nuevaPosicion = inicio + texto.length;
  textarea.setSelectionRange(nuevaPosicion, nuevaPosicion);

  // Enfocar el textarea nuevamente
  textarea.focus();
}


// Si se hace click sobre el input de tipo checkbox con id checkb
$('#checkb').click(function() {
	// Si esta seleccionado (si la propiedad checked es igual a true)
	if ($(this).prop('checked')) {
		// Selecciona cada input que tenga la clase .checar
		$('.check_atencion_<?php echo $idAtencion?>').prop('checked', true);
	} else {
		// Deselecciona cada input que tenga la clase .checar
		$('.check_atencion_<?php echo $idAtencion?>').prop('checked', false);
	}
});

$(document).ready(function() {
	$("#txt_id_usuario_sello").select2();
	$("#txt_id_usuario_valid").select2();
	$("#txt_id_usuario_resul").select2();
	$("#fixTable").tableHeadFixer();
	$("#fixTableDep").tableHeadFixer();
	setTimeout(function(){load_focus_inicio();}, 2);
});

</script>
<?php require_once '../include/masterfooter.php'; ?>
