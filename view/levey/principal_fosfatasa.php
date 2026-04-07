<div class="row">
	<?php 
		$_id = '11';
		$rsLe = $le->get_datosControlCalidadPorId($_id);
		$cnt = count($rsLe);
		if($cnt > 0){
	?>
<p><b>&nbsp;&nbsp;&nbsp;Leyenda de botones de acción</b>: <img src="../../assets/images/details_open.png"/>= Mostrar datos adicionales | <a href="#" class="acept" title="Editar"><i class="glyphicon glyphicon-pencil" style="color: #449d44;"></i></a>= Acción correctiva | <a href="#" class="detail" style="color: #00c0ef;"><i class="glyphicon glyphicon-eye-open"></i></a>= Visualizar acción correctiva</a></p>
	
	<div class="col-sm-6">
	<div class="panel panel-success">
		<div class="panel-heading">
		  <h3 class="panel-title"><strong><?php echo $rsLe[0]['nombre_tipo'] . ": " . $rsLe[0]['nombre_control'];?></strong></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-5">
				  <div class="box box-success">
					<div class="box-body box-profile">
						<h3 class="profile-username text-center"><b>DATOS</b></h3>
						<hr/>
						<form name="frm_registra_<?php echo $_id;?>" id="frm_registra_<?php echo $_id;?>" class="form-horizontal">
							<div class="form-group">
							  <div class="col-sm-7">
								<label for="txt_fecha_<?php echo $_id;?>"><small>Fecha <i class="glyphicon glyphicon-calendar"></i>:</small></label>
								<input type="text" class="form-control input-sm" name="txt_fecha_<?php echo $_id;?>" id="txt_fecha_<?php echo $_id;?>" autocomplete="OFF" maxlength="10" value="<?php echo $fecActu ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />
							  </div>
							  <div class="col-sm-5">
								<label for="txt_valor_<?php echo $_id;?>"><small>Valor:</small></label>
								<input type="text" class="form-control input-sm" name="txt_valor_<?php echo $_id;?>" id="txt_valor_<?php echo $_id;?>" autocomplete="OFF" maxlength="6" value=""/>
							  </div>
							  <div class="col-sm-12">
								<span class="help-block">Ingrese una fecha del mes seleccionado</span>
								<br/>
								<button class="btn btn-primary btn-sm btn-block" type="button" id="btn_registra_<?php echo $_id;?>" onclick="registrar('<?php echo $_id;?>');"><i class="fa fa-save"></i> Registrar</button>
							  </div>
							</div>
						</form>
						<br/>
						<div id="datos_levey_<?php echo $_id;?>">
							<input type="hidden" name="txt_id_levey_<?php echo $_id;?>" id="txt_id_levey_<?php echo $_id;?>" value="0"/>
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">DS <span class="pull-right">N/A</span></li>
								<li class="list-group-item">X+3DS <span class="pull-right text-red"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">X+2DS <span class="pull-right text-yellow"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">X+1DS <span class="pull-right text-green"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">MEDIA <span class="pull-right"><i class="fa fa-angle-left"></i>N/A</span></li>
								<li class="list-group-item">X-1DS <span class="pull-right text-green"><i class="fa fa-angle-down"></i>N/A</span></li>
								<li class="list-group-item">X-2DS <span class="pull-right text-yellow"><i class="fa fa-angle-down"></i>N/A</span></li>
								<li class="list-group-item">X-3DS <span class="pull-right text-red"><i class="fa fa-angle-down"></i>N/A</span></li>
							</ul>
						</div>
					</div>
				  </div>
				</div>
				<div class="col-sm-7">
				  <div class="box box-warning">
					<div class="box-body box-profile">
					  <h3 class="profile-username text-center"><b>DIAS INGRESADOS</b></h3>
					  <hr/>
						<table id="tbl_<?php echo $_id;?>" class="table table-hover table-bordered" cellspacing="0" width="100%">
							<thead class="bg-aqua">
								<tr>
									<th><small>DIA</small></th>
									<th><small>LOTE</small></th>
									<th><small>VALOR</small></th>
									<th><small>Z SOCRE</small></th>
									<th><small><i class="fa fa-cogs"></i></small></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				  </div>
				</div>
				<div class="col-sm-12">
					<div class="box box-primary">
						<br/>
						<div class="responsive"  id="echart_resumen_<?php echo $_id;?>" style="width: 600px; height:400px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php
		}
		$_id = '12';
		$rsLe = $le->get_datosControlCalidadPorId($_id);
		$cnt = count($rsLe);
		if($cnt > 0){
	?>
	<div class="col-sm-6">
	<div class="panel panel-info">
		<div class="panel-heading">
		  <h3 class="panel-title"><strong><?php echo $rsLe[0]['nombre_tipo'] . ": " . $rsLe[0]['nombre_control'];?></strong></h3>	
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-5">
				  <div class="box box-info">
					<div class="box-body box-profile">
						<h3 class="profile-username text-center"><b>DATOS</b></h3>
						<hr/>
						<form name="frm_registra_<?php echo $_id;?>" id="frm_registra_<?php echo $_id;?>" class="form-horizontal">
							<div class="form-group">
							  <div class="col-sm-7">
								<label for="txt_fecha_<?php echo $_id;?>"><small>Fecha <i class="glyphicon glyphicon-calendar"></i>:</small></label>
								<input type="text" class="form-control input-sm" name="txt_fecha_<?php echo $_id;?>" id="txt_fecha_<?php echo $_id;?>" autocomplete="OFF" maxlength="10" value="<?php echo $fecActu ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask />
							  </div>
							  <div class="col-sm-5">
								<label for="txt_valor_<?php echo $_id;?>"><small>Valor:</small></label>
								<input type="text" class="form-control input-sm" name="txt_valor_<?php echo $_id;?>" id="txt_valor_<?php echo $_id;?>" autocomplete="OFF" maxlength="6" value=""/>
							  </div>
							  <div class="col-sm-12">
								<span class="help-block">Ingrese una fecha del mes seleccionado</span>
								<br/>
								<button class="btn btn-primary btn-sm btn-block" type="button" id="btn_registra_<?php echo $_id;?>" onclick="registrar('<?php echo $_id;?>');"><i class="fa fa-save"></i> Registrar</button>
							  </div>
							</div>
						</form>
						<br/>
						<div id="datos_levey_<?php echo $_id;?>">
							<input type="hidden" name="txt_id_levey_<?php echo $_id;?>" id="txt_id_levey_<?php echo $_id;?>" value="0"/>
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">DS <span class="pull-right">N/A</span></li>
								<li class="list-group-item">X+3DS <span class="pull-right text-red"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">X+2DS <span class="pull-right text-yellow"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">X+1DS <span class="pull-right text-green"><i class="fa fa-angle-up"></i>N/A</span></li>
								<li class="list-group-item">MEDIA <span class="pull-right"><i class="fa fa-angle-left"></i>N/A</span></li>
								<li class="list-group-item">X-1DS <span class="pull-right text-green"><i class="fa fa-angle-down"></i>N/A</span></li>
								<li class="list-group-item">X-2DS <span class="pull-right text-yellow"><i class="fa fa-angle-down"></i>N/A</span></li>
								<li class="list-group-item">X-3DS <span class="pull-right text-red"><i class="fa fa-angle-down"></i>N/A</span></li>
							</ul>
						</div>
					</div>
				  </div>
				</div>
				<div class="col-sm-7">
				  <div class="box box-warning">
					<div class="box-body box-profile">
					  <h3 class="profile-username text-center"><b>DIAS INGRESADOS</b></h3>
					  <hr/>
						<table id="tbl_<?php echo $_id;?>" class="table table-hover table-bordered" cellspacing="0" width="100%">
							<thead class="bg-aqua">
								<tr>
									<th><small>DIA</small></th>
									<th><small>LOTE</small></th>
									<th><small>VALOR</small></th>
									<th><small>Z SOCRE</small></th>
									<th><small><i class="fa fa-cogs"></i></small></th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				  </div>
				</div>
				<div class="col-sm-12">
					<div class="box box-primary">
						<br/>
						<div class="responsive"  id="echart_resumen_<?php echo $_id;?>" style="width: 600px; height:400px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php
		}
	?>
</div>