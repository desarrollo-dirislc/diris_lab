<?php

include_once 'ConectaDb.php';

class Lab {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }
  
  public function reg_resultado_laboratorio($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['id_producto_selec'], $param[0]['datos_producto'], $param[0]['datos'], $param[0]['obs'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_lab_resultado($1,$2,$3,$4,$5,$6,$7);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function reg_resultado_laboratorio_procesado($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['id_producto_selec'], $param[0]['datos_producto'], $param[0]['datos'], $param[0]['obs'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_lab_resultado_procesado($1,$2,$3,$4,$5,$6,$7);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }  
  
  public function reg_resultado_laboratorio_validado($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['id_producto_selec'], $param[0]['datos_producto'], $param[0]['datos'], $param[0]['obs'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_lab_resultado_validado($1,$2,$3,$4,$5,$6,$7);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function reg_producto_cantidad_mensual($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['datos'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_producto_cantidad_mensual($1,$2,$3,$4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
    
  public function reg_cambia_profesional_encargado_turno($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['datos'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_reg_cambia_encargado_turno($1,$2,$3,$4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
  
  public function get_datosDetalleReferenciaAndFUA($id_atencion) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From lab.sp_show_referencia_fua(" . $id_atencion .", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function reg_referencia_fua($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id_atencion'], $param[0]['id'], $param[0]['datos_ref'], $param[0]['datos_fua'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_lab_referencia_fua($1,$2,$3,$4,$5,$6);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
  
  public function reg_envio($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['datos_cabecera'], $param[0]['datos'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_crud_envio($1,$2,$3,$4,$5);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
	
	public function get_tblDatosEnvio($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "Select count(*) OVER() AS cant_rows, env.id, to_char(env.fec_envio, 'dd/mm/yyyy') fec_envio, to_char(env.create_received_at, 'dd/mm/yyyy') fec_recepcion, to_char(env.create_finalize_at, 'dd/mm/yyyy') fec_finaliza, env.nro_envio, env.anio_envio,
dor.nom_depen dependencia_origen, dd.nom_depen dependencia_destino, nom_producto,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg<>0) cnt_enviado,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg=2) cnt_soliaceptada,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg=4) cnt_solirechazada,
(Select count(*) From lab.tbl_labenviodet det Inner Join lab.tbl_labproductoatencion lprod On det.id_producto_atencion = lprod.id Where det.id_envio=env.id And lprod.id_estado_resul=4) cnt_soliprocesada,
env.id_estado_reg id_estado_env, estenv.nom_estado estado_env
From lab.tbl_labenvio env
Inner Join tbl_producto pro On env.id_producto = pro.id_producto
Inner Join tbl_dependencia dor On env.id_dependencia_origen=dor.id_dependencia
Inner Join tbl_dependencia dd On env.id_dependencia_destino=dd.id_dependencia
Inner Join tbl_papenvioestado estenv On env.id_estado_reg = estenv.id_papenvestado
Where env.id_estado_reg<>0";
		if (!empty($param[0]['id_dependencia'])) {
			$this->sql .= " And env.id_dependencia_origen=" . $param[0]['id_dependencia'] . "";
		}
		if (!empty($param[0]['id_dependencia_des'])) {
			$this->sql .= " And env.id_dependencia_destino=" . $param[0]['id_dependencia_des'] . "";
		}
		if (!empty($param[0]['id_producto'])) {
			$this->sql .= " And env.id_producto=" . $param[0]['id_producto'] . "";
		}
		if (!empty($param[0]['id_estado_reg'])) {
			if ($param[0]['id_estado_reg'] == "2") {
				$this->sql .= " And env.id_estado_reg in (2,3)";
			} else {
				$this->sql .= " And env.id_estado_reg=" . $param[0]['id_estado_reg'] . "";
			}
		}
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProductoParaDerivar($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "Select count(*) OVER() AS cant_rows, dpro.id, to_char(la.fec_atencion, 'dd/mm/yyyy') fec_atencion, la.nro_atencion, la.anio_atencion,
tdp.abreviatura abrev_tipodoc, nrodoc, Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When segundo_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs nombre_rs,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc,
extract(days from (now()::timestamp - dpro.create_at)) dias_transcurrido, pro.nom_producto, la.id_tipo_genera_correlativo 
From lab.tbl_labproductoatencion dpro
Inner Join lab.tbl_labatencion la On dpro.id_atencion=la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Inner Join tbl_producto pro On dpro.id_producto = pro.id_producto
Where la.id_dependencia=" . $param[0]['id_dependencia'] . " And dpro.id_producto=" . $param[0]['id_producto'] . " And dpro.id_estado_reg=1 And dpro.id_estado_envio=1 And dpro.id_estado_resul=1";
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProductoEnviados($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, env.id_producto_atencion, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio, pro.nom_producto producto
, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, nom_depen dependencia_destino, dpro.id_estado_resul, estresul.nombre_estado estado_resul, (current_date - env.create_env::date) cnt_dia
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN public.tbl_producto pro ON dpro.id_producto = pro.id_producto
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia d ON dpro.id_dependencia = d.id_dependencia
INNER JOIN lab.tbl_estado_lab_resultado estresul On dpro.id_estado_resul = estresul.id
WHERE la.id_dependencia = " . $param[0]['id_dependencia'];
if (!empty($param[0]['id_dependencia_destino'])) {
	$this->sql .= " AND dpro.id_dependencia = " . $param[0]['id_dependencia_destino'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= "  AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env IN (1,2) AND env.id_estado_env = '1'";
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
 
	public function get_tblDatosProductoAtendidas($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, la.id id_atencion, dpro.id_producto, env.id_producto_atencion, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio, pro.nom_producto producto
, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, la.id_dependencia id_dependencia_origen, ddes.nom_depen dependencia_destino, to_char(dpro.create_valid, 'DD/MM/YYYY') fecha_valida_resultado, la.id_estado_reg id_estado_atencion, CASE WHEN la.id_estado_reg = 5 THEN estate.abreviatura_estado ELSE 'RESULTADO NO ENTREGADO A PAC.' END estado_atencion
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN public.tbl_producto pro ON dpro.id_producto = pro.id_producto
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia ddes ON dpro.id_dependencia = ddes.id_dependencia
INNER JOIN lab.tbl_estado_lab estate ON la.id_estado_reg = estate.id
WHERE la.id_dependencia = " . $param[0]['id_dependencia'];
if (!empty($param[0]['id_dependencia_destino'])) {
	$this->sql .= " AND dpro.id_dependencia = " . $param[0]['id_dependencia_destino'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= "  AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 2 AND env.id_estado_resul = 4";
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
 
	public function get_tblDatosProductoObservadas($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, la.id id_atencion, dpro.id_producto, env.id_producto_atencion, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio, pro.nom_producto producto
, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, ddes.nom_depen dependencia_destino, to_char(env.create_receive_env, 'DD/MM/YYYY') fecha_recibe_destino 
, env.id_motivo_rechazo, COALESCE(mov1.descrip_rechazo, mov0.descrip_rechazo) AS motivo_rechazo, motivo_rechazo detalle_motivo_rechazo
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN public.tbl_producto pro ON dpro.id_producto = pro.id_producto
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia ddes ON dpro.id_dependencia = ddes.id_dependencia
LEFT JOIN lab.tbl_labenviotiporechazo mov1 ON mov1.id = env.id_motivo_rechazo AND mov1.id_producto = dpro.id_producto
LEFT JOIN lab.tbl_labenviotiporechazo mov0 ON mov0.id = env.id_motivo_rechazo AND mov0.id_producto = 0
WHERE la.id_dependencia = " . $param[0]['id_dependencia'];
if (!empty($param[0]['id_dependencia_destino'])) {
	$this->sql .= " AND dpro.id_dependencia = " . $param[0]['id_dependencia_destino'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 4";
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProductoEnviadosPorClasificar($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, env.id_producto_atencion, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, nrodoc
, dori.nom_depen dependencia_origen, to_char(dpro.create_toma, 'DD/MM/YYYY') fecha_toma_muestra, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio_destino, (current_date - env.create_env::date) cnt_dia
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
WHERE dpro.id_dependencia = " . $param[0]['id_dependencia']  . " AND dpro.id_producto = " . $param[0]['id_producto'];
if (!empty($param[0]['id_dependencia_origen'])) {
	$this->sql .= " AND la.id_dependencia = " . $param[0]['id_dependencia_origen'];
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 1";
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProductoEnviadosAceptadas($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, env.id_producto_atencion, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, dori.nom_depen dependencia_origen, to_char(dpro.create_toma, 'DD/MM/YYYY') fecha_toma_muestra, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio_destino, to_char(env.create_receive_env, 'DD/MM/YYYY') fecha_recibe_destino 
, env.id_estado_resul, estresul.nombre_estado estado_resul
FROM lab.tbl_labproductoatencion_envio env 
inner JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
inner JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
inner JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
inner JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
inner JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
inner JOIN lab.tbl_estado_lab_resultado estresul On env.id_estado_resul = estresul.id
WHERE dpro.id_dependencia = " . $param[0]['id_dependencia']  . " AND dpro.id_producto = " . $param[0]['id_producto'];
if (!empty($param[0]['id_dependencia_origen'])) {
	$this->sql .= " AND la.id_dependencia = " . $param[0]['id_dependencia_origen'];                                                                
}


if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 2 AND env.id_estado_resul = 1";
		$this->sql .= $sWhere . ' ORDER BY env.create_receive_env desc ' .  $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProductoEnviadosEnProceso($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, la.id id_atencion, dpro.id_producto, env.id_producto_atencion, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, dori.nom_depen dependencia_origen, to_char(dpro.create_toma, 'DD/MM/YYYY') fecha_toma_muestra, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio_destino, to_char(env.create_receive_env, 'DD/MM/YYYY') fecha_recibe_destino 
, dpro.id_estado_resul, estresul.nombre_estado estado_resul
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
INNER JOIN lab.tbl_estado_lab_resultado estresul On dpro.id_estado_resul = estresul.id
WHERE dpro.id_dependencia = " . $param[0]['id_dependencia']  . " AND dpro.id_producto = " . $param[0]['id_producto'];
if (!empty($param[0]['id_dependencia_origen'])) {
	$this->sql .= " AND la.id_dependencia = " . $param[0]['id_dependencia_origen'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 2 AND env.id_estado_resul = 2";
		$this->sql .= $sWhere . ' ORDER BY env.create_receive_env desc ' .  $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

  
	public function get_tblDatosProductoEnviadosValidados($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, la.id id_atencion, dpro.id_producto, env.id_producto_atencion, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, dori.id_dependencia id_dependencia_origen, dori.nom_depen dependencia_origen, to_char(dpro.create_toma, 'DD/MM/YYYY') fecha_toma_muestra, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio_destino
, to_char(env.create_receive_env, 'DD/MM/YYYY') fecha_recibe_destino, to_char(dpro.create_valid, 'DD/MM/YYYY') fecha_valida_resultado, la.id_estado_reg id_estado_atencion
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
WHERE dpro.id_dependencia = " . $param[0]['id_dependencia']  . " AND dpro.id_producto = " . $param[0]['id_producto'];
if (!empty($param[0]['id_dependencia_origen'])) {
	$this->sql .= " AND la.id_dependencia = " . $param[0]['id_dependencia_origen'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 2 AND env.id_estado_resul = 4";
		$this->sql .= $sWhere . ' ORDER BY env.create_receive_env desc ' .  $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

	public function get_tblDatosProductoEnviadosObservadas($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, env.id, env.id_producto_atencion, CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar END nro_atencion
, trim(COALESCE(primer_ape, '') || ' ' || COALESCE(segundo_ape, '') || ' ' || COALESCE(nombre_rs, '')) AS nombre_paciente, tdp.abreviatura abrev_tipodoc, p.nrodoc
, dori.nom_depen dependencia_origen, to_char(dpro.create_toma, 'DD/MM/YYYY') fecha_toma_muestra, to_char(env.create_env, 'DD/MM/YYYY') fecha_envio_destino, to_char(env.create_receive_env, 'DD/MM/YYYY') fecha_recibe_destino 
, env.id_motivo_rechazo, COALESCE(mov1.descrip_rechazo, mov0.descrip_rechazo) AS motivo_rechazo, motivo_rechazo detalle_motivo_rechazo
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
LEFT JOIN lab.tbl_labenviotiporechazo mov1 ON mov1.id = env.id_motivo_rechazo AND mov1.id_producto = dpro.id_producto
LEFT JOIN lab.tbl_labenviotiporechazo mov0 ON mov0.id = env.id_motivo_rechazo AND mov0.id_producto = 0
WHERE dpro.id_dependencia = " . $param[0]['id_dependencia']  . " AND dpro.id_producto = " . $param[0]['id_producto'];
if (!empty($param[0]['id_dependencia_origen'])) {
	$this->sql .= " AND la.id_dependencia = " . $param[0]['id_dependencia_origen'];
}
if (!empty($param[0]['datos_pac'])) {
	$this->sql .= " AND trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) || ' ' || p.nrodoc || ' ' || CASE WHEN la.id_tipo_genera_correlativo = 1 THEN la.nro_atencion::Varchar||'-'||la.anio_atencion::Varchar ELSE la.nro_atencion::Varchar  END ILIKE '%" . $param[0]['datos_pac'] . "%'";
}
$this->sql .= " AND dpro.id_estado_reg = 1 AND env.id_estado_registro = 1 AND env.id_estado_env = 4";
		$this->sql .= $sWhere . ' ORDER BY env.create_receive_env desc ' .  $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

public function datos_detalle_examen_derivado($id) {
    $this->db->getConnection();
    $aparam = array($id);
    $this->sql = "SELECT dori.nom_depen dependencia_origen, tdp.abreviatura abrev_tipodoc_pac, p.nrodoc nrodoc_pac, CASE WHEN p.id_tipodoc = 1 THEN 'PERÚ' ELSE paisp.distrito END pais_nac_pac
, trim(COALESCE(p.primer_ape, '') || ' ' || COALESCE(p.segundo_ape, '') || ' ' || COALESCE(p.nombre_rs, '')) AS nombre_completo_pac
, trim(COALESCE(p.primer_ape, '')) primer_ape_pac, trim(COALESCE(p.segundo_ape, '')) segundo_ape_pac, p.nombre_rs nombre_pac
, p.fec_nac fec_nac_pac, CASE id_sexo WHEN 1 THEN 'M' ELSE 'F' END sexo_pac
, la.fec_cita::date fec_cita, pro.nom_producto producto
FROM lab.tbl_labproductoatencion_envio env 
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN public.tbl_producto pro ON dpro.id_producto = pro.id_producto
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion=la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_tipodoc tdp ON p.id_tipodoc = tdp.id_tipodoc
LEFT JOIN  public.tbl_ubigeo2019 paisp ON p.id_paisnac = paisp.id_pais AND paisp.id_pais <> 'PER'
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
WHERE env.id = $1;";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0];
  }
	
  public function get_datosEnvio($idEnvio) {
    $conet = $this->db->getConnection();
    $this->sql = "Select env.id, to_char(env.fec_envio, 'dd/mm/yyyy') fec_envio, env.nro_envio, env.anio_envio,
env.id_dependencia_origen, dor.nom_depen dependencia_origen, env.id_dependencia_destino, dd.nom_depen dependencia_destino, nom_producto,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg<>0) cnt_enviado,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg=2) cnt_soliaceptada,
(Select count(*) From lab.tbl_labenviodet Where id_envio=env.id And id_estado_reg=4) cnt_solirechazada,
(Select count(*) From lab.tbl_labenviodet det Inner Join lab.tbl_labproductoatencion lprod On det.id_producto_atencion = lprod.id Where det.id_envio=env.id And lprod.id_estado_resul=4) cnt_soliprocesada,
env.id_estado_reg id_estado_env, case env.id_estado_reg When 0 Then 'ANULADO' ELSE 'ENVIADO' END estado_env
From lab.tbl_labenvio env
Inner Join tbl_producto pro On env.id_producto = pro.id_producto
Inner Join tbl_dependencia dor On env.id_dependencia_origen=dor.id_dependencia
Inner Join tbl_dependencia dd On env.id_dependencia_destino=dd.id_dependencia
Where env.id=".$idEnvio.";";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_repDatosDetEnviado($idEnv) {
    $this->db->getConnection();
    $this->sql = "Select envd.id, la.nro_atencion, la.anio_atencion,
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, 
Case When la.id_tipatencion = 1 Then 'SI' Else 'NO' End nom_sispac, telf.nro_telefono, 
Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac, 
date_part('year',age(la.fec_atencion, p.fec_nac)) edad_pac, envd.id_tipoobsenvdet, recha.abrev_rechazo,
aprod.cod_ref_nro_atencion, aprod.id_estado_resul, env.id_estado_reg id_estado_envio, envd.id_estado_reg id_estado_enviodet, labresul.det_resul, la.id_tipo_genera_correlativo 
From lab.tbl_labenvio env
Inner Join lab.tbl_labenviodet envd On env.id=envd.id_envio
Inner Join lab.tbl_labatencion la On envd.id_atencion = la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Inner Join lab.tbl_labproductoatencion aprod On envd.id_producto_atencion = aprod.id
Left join lab.tbl_labresultadodet labresul On aprod.id_atencion=labresul.id_atencion And aprod.id_producto=labresul.id_producto And aprod.id_estado_reg=1 And labresul.id_productogrupocomp=363
Left join lab.tbl_labenviotiporechazo recha On envd.id_tipoobsenvdet=recha.id
Left Join public.tbl_historialtelefono telf On la.id_telfmovilpac = telf.id_histotelefono And telf.id_tipotelefono=2
Where env.id=" . $idEnv . " And envd.id_estado_reg<>0 Order By la.nro_atencion, la.anio_atencion";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
	public function get_tblDatosDatosDetEnviado($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "Select count(*) OVER() AS cant_rows, envd.id, la.nro_atencion, la.anio_atencion,
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, 
Case When la.id_tipatencion = 1 Then 'SI' Else 'NO' End nom_sispac, telf.nro_telefono, 
Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac, 
date_part('year',age(la.fec_atencion, p.fec_nac)) edad_pac
From lab.tbl_labenvio env
Inner Join lab.tbl_labenviodet envd On env.id=envd.id_envio
Inner Join lab.tbl_labatencion la On envd.id_atencion = la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Left Join public.tbl_historialtelefono telf On la.id_telfmovilpac = telf.id_histotelefono And telf.id_tipotelefono=2
Where env.id=" . $param[0]['id_envio'] . "";
		if (!empty($param[0]['id_estado'])) {
			$this->sql .= " And envd.id_estado_reg=" . $param[0]['id_estado'] . "";
		}
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosIngResultadoPSA($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "Select count(*) OVER() AS cant_rows, envd.id, la.id id_atencion, la.id_dependencia, labresul.id id_resuldet, la.nro_atencion, la.anio_atencion, 
		to_char(env.create_received_at, 'DD/MM/YYYY') fecha_recepcion, d.nom_depen nom_dependencia_origen, 
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, 
Case When la.id_tipatencion = 1 Then 'SI' Else 'NO' End nom_sispac, telf.nro_telefono, 
Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac, 
date_part('year',age(la.fec_atencion, p.fec_nac)) edad_pac, labresul.det_resul, lad.cod_ref_nro_atencion, to_char(lad.create_resultado, 'DD/MM/YYYY') fecha_resultado, envd.obs_doble_valid,
env.nro_envio, env.anio_envio, to_char(env.fec_envio, 'DD/MM/YYYY') fec_envio
From lab.tbl_labenvio env
Inner Join lab.tbl_labenviodet envd On env.id=envd.id_envio
Inner join lab.tbl_labproductoatencion lad On envd.id_producto_atencion = lad.id
Left join lab.tbl_labresultadodet labresul On lad.id_atencion=labresul.id_atencion And lad.id_producto=labresul.id_producto And lad.id_estado_reg=1 And labresul.estado=1 And labresul.id_productogrupocomp=363
Inner Join lab.tbl_labatencion la On envd.id_atencion = la.id
Inner Join tbl_dependencia d On la.id_dependencia=d.id_dependencia
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Left Join public.tbl_historialtelefono telf On la.id_telfmovilpac = telf.id_histotelefono And telf.id_tipotelefono=2
Where env.id_producto=60 And envd.id_estado_reg=2";
		if (!empty($param[0]['id_estado'])) {
			$this->sql .= " And lad.id_estado_resul=" . $param[0]['id_estado'] . "";
		}
		if(isset($param[0]['id_envio'])){
			if (!empty($param[0]['id_envio'])) {
				$this->sql .= " And env.id=" . $param[0]['id_envio'] . "";
			}
		}
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
  
	public function get_ObtenerDatosAtencionPSA($nrodoc, $id_producto, $nom_dep_excel) {
		$this->db->getConnection();
		$this->sql = "Select la.id id_atencion, la.nro_atencion, la.anio_atencion,
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, case p.id_sexo When '1' Then 'M' Else 'F' End id_sexopac, 
date_part('year',age(la.fec_atencion, p.fec_nac)) as edad_anio, date_part('month',age(la.fec_atencion, p.fec_nac)) as edad_mes, date_part('day',age(la.fec_atencion, p.fec_nac)) as edad_dia
From lab.tbl_labenvio env
Inner Join lab.tbl_labenviodet envd On env.id=envd.id_envio
Inner join lab.tbl_labproductoatencion lad On envd.id_producto_atencion = lad.id
Inner Join lab.tbl_labatencion la On envd.id_atencion = la.id
Inner Join tbl_dependencia d On la.id_dependencia = d.id_dependencia
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Where env.id_producto=".$id_producto." And envd.id_estado_reg=2 And lad.id_estado_resul<>4 And lad.id_estado_reg=1 And p.nrodoc='".$nrodoc."' And d.nom_depen_psa='".$nom_dep_excel."'";
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
  
	public function get_tblDatosIngResultadoTPHA($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "Select count(*) OVER() AS cant_rows, envd.id, la.id id_atencion, la.id_dependencia, la.nro_atencion, la.anio_atencion,
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, 
Case When la.id_tipatencion = 1 Then 'SI' Else 'NO' End nom_sispac, telf.nro_telefono, 
Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac, 
date_part('year',age(la.fec_atencion, p.fec_nac)) edad_pac, lad.cod_ref_nro_atencion, to_char(lad.create_resultado, 'DD/MM/YYYY') fecha_resultado, envd.obs_doble_valid
From lab.tbl_labenvio env
Inner Join lab.tbl_labenviodet envd On env.id=envd.id_envio
Inner join lab.tbl_labproductoatencion lad On envd.id_producto_atencion = lad.id
Inner Join lab.tbl_labatencion la On envd.id_atencion = la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Left Join public.tbl_historialtelefono telf On la.id_telfmovilpac = telf.id_histotelefono And telf.id_tipotelefono=2
Where env.id_producto=12 And envd.id_estado_reg=2";
		if (!empty($param[0]['id_estado'])) {
			$this->sql .= " And lad.id_estado_resul=" . $param[0]['id_estado'] . "";
		}
		if(isset($param[0]['id_envio'])){
			if (!empty($param[0]['id_envio'])) {
				$this->sql .= " And env.id=" . $param[0]['id_envio'] . "";
			}
		}
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	
  public function get_datosMedicoPorIdDependencia($iddep, $med) {
    $conet = $this->db->getConnection();
    $this->sql = "Select id, nombre_medico as value From lab.tbl_nombre_medico_eess
Where id_dependencia=".$iddep." And nombre_medico iLike '%". $med ."%' And id_estado_reg=1;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaTipoRechazadoEnvio($id_producto, $id_tiporechazo) {
    $conet = $this->db->getConnection();
    $this->sql = "Select id, Upper(descrip_rechazo) descrip_rechazo From lab.tbl_labenviotiporechazo Where id_producto= " . $id_producto . " And id_tiporechazo= " . $id_tiporechazo . ";";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosRechazoMuestraPorIdEnvAndIdDetEnv($id_env, $id_detenv=0) {
    $this->db->getConnection();
    $this->sql = "Select trecha.id id_detenv, Case id_tiporechazo When 3 Then 'OBSERVADA' Else 'RECHAZADA' End tipo_rechazo, 
trecha.descrip_rechazo, det_obs det_rechazo,
tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac
From lab.tbl_labenviodet envd
Inner Join lab.tbl_labatencion la On envd.id_atencion=la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Inner Join lab.tbl_labenviotiporechazo trecha On envd.id_tipoobsenvdet=trecha.id
Where envd.id_envio=" . $id_env . " And envd.id_estado_reg=4";
	if ($id_detenv <> 0) {
		$this->sql .= " And envd.id=" . $id_detenv;
	}
	$this->sql .= " Order By la.id;";

    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// REPORTE DE ATENCION ////////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/

  public function get_datosReporteCntAtencionesPorServicio($id_dependencia, $fec_inicio, $fec_final) {
    $this->db->getConnection();
    $this->sql = "Select ate.id_servicioori, ser.nom_servicio, count(Case when id_plantarifario=1 then 1 else Null End) cnt_sis, count(Case when id_plantarifario=2 then 1 else Null End) cnt_pag, count(Case when id_plantarifario=3 then 1 else Null End) cnt_est, count(Case when id_plantarifario=4 then 1 else Null End) cnt_exo 
From lab.tbl_labatencion ate Inner Join public.tbl_servicio ser On ate.id_servicioori = ser.id_servicio
Where id_servicioori is not null And id_estado_reg<>0 And ate.id_dependencia=" . $id_dependencia . " And fec_cita::date between '" . $fec_inicio . "' And '" . $fec_final . "'
Group by ate.id_servicioori, ser.nom_servicio, ser.orden_muestra_servicio Order by ser.orden_muestra_servicio";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_datosReporteCntAtencionesProfesionalPorServicio($id_dependencia, $id_servicio, $fec_inicio, $fec_final) {
    $this->db->getConnection();
    $this->sql = "Select ate.nombre_medico, count(Case when id_plantarifario=1 then 1 else Null End) cnt_sis, count(Case when id_plantarifario=2 then 1 else Null End) cnt_pag, count(Case when id_plantarifario=3 then 1 else Null End) cnt_est, count(Case when id_plantarifario=4 then 1 else Null End) cnt_exo 
From lab.tbl_labatencion ate Inner Join public.tbl_servicio ser On ate.id_servicioori = ser.id_servicio
Where id_servicioori=" . $id_servicio . " And id_estado_reg<>0 And ate.id_dependencia=" . $id_dependencia . " And fec_cita::date between '" . $fec_inicio . "' And '" . $fec_final . "'
Group by ate.nombre_medico Order by ate.nombre_medico";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// REPORTE DE PRODUCCION ////////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/
  
  
  
  
	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// LABORATORIO DE REFERENCIA ////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/
	
	////////////// VALIDA SI EXISTE ATENCION EN LAB DE REFERENCIA
	public function valid_existe_atencion_lab_ref($nro_atencion, $nro_doc_pac) {
		$this->db->getConnection();
		$aparam = array($nro_atencion, $nro_doc_pac);
		$this->sql = "Select lab.id id_atencion, labprod.id_producto, labprod.id_estado_resul From lab.tbl_labatencion lab
Inner Join public.tbl_persona p On lab.id_paciente = p.id_persona
Inner Join lab.tbl_labproductoatencion labprod On lab.id = labprod.id_atencion
where lab.id_dependencia=67 And lab.nro_atencion_manual=$1 And p.nrodoc=$2 And lab.id_estado_reg<>0 And labprod.id_estado_reg=1";
		$this->rs = $this->db->query_params($this->sql, $aparam);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	//////////////////////// PSA
  public function get_CntBKValidPorAnioMesAndIddependencia($labIdDepUser, $anio, $mes, $diag, $result) {
    $conet = $this->db->getConnection();
    $this->sql = "Select count(1) cnt_resul_valid From lab.tbl_labresultadodet dresul
Inner Join (Select dpro.id_atencion, dpro.id_producto from lab.tbl_labresultadodet dresul
Inner Join lab.tbl_labproductoatencion dpro On dresul.id_atencion=dpro.id_atencion And dresul.id_producto=dpro.id_producto
Where dpro.id_dependencia=" . $labIdDepUser . " And dpro.id_estado_resul=4 And extract(year From dpro.create_valid)=" . $anio . " And extract(month From dpro.create_valid)=" . $mes . "
And dresul.id_producto=79 And dresul.idseleccion_resul=45 And dresul.det_resul='" . $diag . "') diag
On dresul.id_atencion=diag.id_atencion And dresul.id_producto=diag.id_producto
Where dresul.idseleccion_resul=53";
if($result == 99){
	$this->sql .= " And dresul.det_resul in ('208','209','210','211','212','213','214','215','216')";
} else {
	$this->sql .= " And dresul.det_resul='" . $result . "'";
}
 
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
	if(isset($this->rs[0][0])){
		return $this->rs[0][0];
	} else {
		return 0;
	}
  }
  
  public function get_detalleBKValidPorAnioMesAndIddependencia($labIdDepUser, $anio, $mes) {
    $conet = $this->db->getConnection();
    $this->sql = "Select la.id id_atencion, la.id_tipo_genera_correlativo, la.nro_atencion, la.anio_atencion,  to_char(la.fec_cita, 'dd/mm/yy') fec_atencion,
Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rspac,
(Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=dpro.id_dependencia) nro_hcpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, p.id_sexo, p.id_sexo, date_part('year',age(la.fec_atencion, p.fec_nac)) as edad_anio, 
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=1 And dresul.id_productogrupocomp=664) nro_reg_bk,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=3 And dresul.idseleccion_resul=46) id_tip_muestra,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=3 And dresul.idseleccion_resul=48) id_cali_muestra,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=3 And dresul.idseleccion_resul=45) id_diagostico,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=3 And dresul.idseleccion_resul=49) id_nro_muestra,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=1 And dresul.id_productogrupocomp=440) mes_control,
(Select dresul.det_resul From lab.tbl_labresultadodet dresul Where dresul.id_atencion=la.id And idtipo_ingresol=3 And dresul.idseleccion_resul=53) id_resultado
From lab.tbl_labproductoatencion dpro
Inner Join lab.tbl_labatencion la On dpro.id_atencion=la.id
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Where dpro.id_dependencia=" . $labIdDepUser . " And dpro.id_producto=79 And dpro.id_estado_reg=1 And extract(year from dpro.create_valid)=" . $anio . " And extract(month from dpro.create_valid)=" . $mes . "
ORDER BY (SELECT CAST(NULLIF(dresul.det_resul, '') AS INTEGER) FROM lab.tbl_labresultadodet dresul WHERE dresul.id_atencion = la.id AND dresul.idtipo_ingresol=1 AND dresul.id_productogrupocomp=664)";
 
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
	return $this->rs;
  }

  public function get_datosSeleccionresuldetPorId($id) {
    $this->db->getConnection();
    $aparam = array($id);
    $this->sql = "Select id, nombre, abreviatura From tbl_componente_seleccionresuldet Where id=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
	return $this->rs;
  }
  
	////////////// DENGUE
	public function get_datosProfesionalProcesaAndValida_dengue_labref($id_atencion, $id_producto) {
		$this->db->getConnection();
		$aparam = array($id_atencion, $id_producto);
		$this->sql = "SELECT to_char(create_valid, 'dd/mm/yyyy') fec_validacion, 
Case When perusuresul.primer_ape isNull Then '' Else perusuresul.primer_ape End||' '||Case When perusuresul.segundo_ape isNull Then '' Else perusuresul.segundo_ape End ||' '||perusuresul.nombre_rs nombre_profresul, 
profusuresul.id_profesional id_profesionalresul, profeusuresul.abrev_profesion abrev_profesionresul, profeusuresul.abreviatura_colegiatura abrev_coleprofesionresul, profusuresul.nro_colegiatura nro_coleresul, profusuresul.nro_rne nro_rneresul, 
Case When perusuvalid.primer_ape isNull Then '' Else perusuvalid.primer_ape End||' '||Case When perusuvalid.segundo_ape isNull Then '' Else perusuvalid.segundo_ape End ||' '||perusuvalid.nombre_rs nombre_profvalid, 
profusuvalid.id_profesional id_profesionalvalid, profeusuvalid.abrev_profesion abrev_profesionvalid, profeusuvalid.abreviatura_colegiatura abrev_coleprofesionvalid, profusuvalid.nro_colegiatura nro_colevalid, profusuvalid.nro_rne nro_rnevalid FROM lab.tbl_labproductoatencion ateprod
Inner Join public.tbl_usuario usuresul On ateprod.user_create_resultado = usuresul.id_usuario
Inner Join public.tbl_persona perusuresul On usuresul.id_persona = perusuresul.id_persona
Inner Join public.tbl_profesional profusuresul On perusuresul.id_persona = profusuresul.id_persona
Inner Join public.tbl_profesion profeusuresul On profusuresul.id_profesion = profeusuresul.id_profesion
Inner Join public.tbl_usuario usuvalid On ateprod.user_create_valid = usuvalid.id_usuario
Inner Join public.tbl_persona perusuvalid On usuvalid.id_persona = perusuvalid.id_persona
Inner Join public.tbl_profesional profusuvalid On perusuvalid.id_persona = profusuvalid.id_persona
Inner Join public.tbl_profesion profeusuvalid On profusuvalid.id_profesion = profeusuvalid.id_profesion
where id_atencion=$1 And id_producto=$2 And id_estado_reg=1";
		$this->rs = $this->db->query_params($this->sql, $aparam);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	/**********************************************************************************************************************************/
	////////////////////////////////////// EXAMENES REFERENCIADOS EJEMPLO INMUNOLOGIA /////////////////////////////////////////////////
	/**********************************************************************************************************************************/
	public function get_datosDetalleResultadoPorIdExamenReferenciado($id_atencion, $id_examen) {
		$conet = $this->db->getConnection();
		$this->sql = "Select * From public.sp_show_atenciondetproducto_referenciado(" . $id_atencion .", " . $id_examen .", 'ref_cursor'); Fetch All In ref_cursor;";
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs[0];
	}

	/**********************************************************************************************************************************/
	/////////////////////////////////////////// REPORTE CALENDARIO CITADOS /////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/
  
  public function get_CantidadFechaCitadosPorAnioMes($id_dep, $anio, $mes) {
    $conet = $this->db->getConnection();
    $this->sql = "Select fec_cita::date fecha_cita, count(*) ctn_total, count(case when id_estado_resul<>1 then 1 else Null End) atendidos, count(case when id_estado_resul=1 then 1 else Null End) pendientes From lab.tbl_labatencion 
Where id_dependencia=" . $id_dep . " And extract(year from fec_cita)=" . $anio . " And extract(month from fec_cita)=" . $mes . " And id_estado_reg<>0
Group By fec_cita"; 
    $this->rs = $this->db->query_assoc($this->sql);
    $this->db->closeConnection();
	return $this->rs;
  }
	
	

	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// ORION ////////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/
	
	public function get_datosOrionDetalleAtencionEnvio($id) {
		$this->db->getConnection();
		$this->sql = "Select ate.id, ate.id_atencion_orionlab id_sistema_externo, dep.cod_depen_orionlab sucursal_id, tari.cod_plantarifa_orionlab categoria_id, Case ate.id_tipo_genera_correlativo When 1 Then ate.nro_atencion::Varchar||'-'||ate.anio_atencion::Varchar Else ate.nro_atencion::Varchar End numero_orden_externa, (ate.fec_atencion AT TIME ZONE 'America/Lima' AT TIME ZONE 'UTC') fecha_orden, 
tdocpac.cod_tipodoc_orionlab tipo_identificacion_pac, pac.nrodoc numero_identificacion_pac, pac.nombre_rs nombres_pac, pac.primer_ape||Case When pac.segundo_ape <> '' Then ' '||pac.segundo_ape Else '' End apellidos_pac, fec_nac fecha_nacimiento_pac, Case id_sexo When 1 Then 'M' Else 'F' End sexo_pac, 
(Select nro_hc From tbl_historialhc Where id_persona=pac.id_persona And id_dependencia=ate.id_dependencia) numero_historia_clinica_pac, email correo_pac, nro_telefono telefono_celular
From lab.tbl_labatencion ate
Inner Join tbl_dependencia dep On ate.id_dependencia = dep.id_dependencia
Inner Join tbl_plantarifa tari On ate.id_plantarifario = tari.id_plan
Inner Join tbl_persona pac On ate.id_paciente = pac.id_persona
Inner Join tbl_tipodoc tdocpac On pac.id_tipodoc = tdocpac.id_tipodoc
Left Join tbl_historialtelefono celpac On ate.id_telfmovilpac = celpac.id_histotelefono
Left Join tbl_historialemail emailpac On ate.id_emailpac = emailpac.id_histoemail
Where id=".$id;
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	public function get_datosOrionDetalleExamenEnvio($id, $caso = 0) {
		$this->db->getConnection();
		if($caso == 0){// And ateprod.create_toma isNull
			$this->sql = "Select prod.cod_producto_orionlab id_externo, 0 muestra_pendiente, null precio From lab.tbl_labproductoatencion ateprod
Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
Inner Join public.tbl_labproductodepen proddep On ateprod.id_producto = proddep.id_producto And ateprod.id_dependencia = proddep.id_dependencia
Where ateprod.id_atencion=" . $id . " And chek_es_americana=TRUE
And ateprod.id_estado_reg=1 Order by ateprod.orden_muestra_producto";
		} else {
			$this->sql = "Select prod.cod_producto_orionlab id_externo, 0 muestra_pendiente, null precio From lab.tbl_labproductoatencion ateprod
Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
Inner Join public.tbl_labproductodepen proddep On ateprod.id_producto = proddep.id_producto And ateprod.id_dependencia = proddep.id_dependencia
Where ateprod.id_atencion=" . $id . " And chek_es_americana=TRUE
And ateprod.id_estado_reg=1 Order by ateprod.orden_muestra_producto";
		}
		
		/*
		Select prod.cod_producto_orionlab id_externo, 0 muestra_pendiente, null precio From lab.tbl_labproductoatencion ateprod
	Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
	Where ateprod.id_atencion=".$id." And ateprod.id_estado_reg=1 And prod.cod_producto_orionlab<>'' And (prod.idtipo_producto in (1,2) Or prod.id_producto in (52,53,57,64,74)) Order by ateprod.orden_muestra_producto
		
		*/
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	/*public function reg_atencion_laboratorio_orion($id, $id_orion, $nro_atencion_orion, ) {
		$this->db->getConnection();
		$this->sql = "Update lab.tbl_labatencion Set id_atencion_orionlab=" . $id_orion . ", nro_atencion_orionlab=" . $nro_atencion_orion . " Where id=" . $id . ";";
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}*/
  
  public function reg_atencion_laboratorio_orion($id, $id_orion, $nro_atencion_orion, $id_usuario) {
    $this->db->getConnection();
    $aparam = array($id, $id_orion, $nro_atencion_orion, $id_usuario);
    $this->sql = "select lab.sp_crud_lab_atencion_ws($1,$2,$3,$4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
  
  
  public function reg_atencion_laboratorio_api_tercero($caso, $id, $id_orion, $nro_atencion_orion, $id_usuario) {
    $this->db->getConnection();
    $aparam = array($caso, $id, $id_orion, $nro_atencion_orion, $id_usuario);
    $this->sql = "select lab.sp_crud_lab_atencion_ws($1,$2,$3,$4,$5);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  
  public function get_listaMotivoRechazoMuestra($id_examen) {
    $this->db->getConnection();
    $this->sql = "Select id, descrip_rechazo motivo From lab.tbl_labenviotiporechazo Where id_producto in (" . $id_examen . ", 0) AND id_estado_reg = 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// DIAGNOSTICA ////////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/

	public function get_datosObtenerTokenOtraEmpresa($id) {
		$this->db->getConnection();
		$this->sql = "SELECT nom_usuario, pass_usuario FROM lab.tbl_usuario_ws where id=" . $id . " And create_up >= now();";
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		if (isset($this->rs[0])){
			$result = $this->rs[0];
		} else {
			$result = null;
		}
		return $result;
	}

	public function get_datosUpdateTokenOtraEmpresa($id, $token) {
		$this->db->getConnection();
		$this->sql = "UPDATE lab.tbl_usuario_ws SET pass_usuario='" . $token . "', create_at=now(), create_up = now() + INTERVAL '16 hours' where id=" . $id . ";";
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

	public function get_datosDiagnosticaDetalleAtencionEnvio($id) {
		$this->db->getConnection();
		$this->sql = "Select ate.id, Null id_sistema_externo, dep.id_dependencia establecimiento_id, tari.id_plan tipo_atencion_id, Case ate.id_tipo_genera_correlativo When 1 Then ate.nro_atencion::Varchar||'-'||ate.anio_atencion::Varchar Else ate.nro_atencion::Varchar End numero_registro_atencion, (ate.fec_atencion AT TIME ZONE 'America/Lima' AT TIME ZONE 'UTC') fecha_atencion, 
tdocpac.abreviatura tipo_identificacion_pac, pac.nrodoc numero_identificacion_pac, pac.nombre_rs nombres_pac, pac.primer_ape primer_apellido_pac, Case When pac.segundo_ape <> '' Then pac.segundo_ape Else '' End segundo_apellido_pac, fec_nac fecha_nacimiento_pac, Case id_sexo When 1 Then 'M' Else 'F' End sexo_pac, 
(Select nro_hc From tbl_historialhc Where id_persona=pac.id_persona And id_dependencia=ate.id_dependencia) numero_historia_clinica_pac, email correo_pac, nro_telefono telefono_celular, check_gestante
From lab.tbl_labatencion ate
Inner Join tbl_dependencia dep On ate.id_dependencia = dep.id_dependencia
Inner Join tbl_plantarifa tari On ate.id_plantarifario = tari.id_plan
Inner Join tbl_persona pac On ate.id_paciente = pac.id_persona
Inner Join tbl_tipodoc tdocpac On pac.id_tipodoc = tdocpac.id_tipodoc
Left Join tbl_historialtelefono celpac On ate.id_telfmovilpac = celpac.id_histotelefono
Left Join tbl_historialemail emailpac On ate.id_emailpac = emailpac.id_histoemail
Where id=".$id;
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	public function get_datosDiagnosticaDetalleExamenEnvio($id, $caso = 0) {
		$this->db->getConnection();
		if($caso == 0){
			$this->sql = "Select prod.cod_producto_orionlab id From lab.tbl_labproductoatencion ateprod
Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
Inner Join public.tbl_labproductodepen proddep On ateprod.id_producto = proddep.id_producto And ateprod.id_dependencia = proddep.id_dependencia
Where ateprod.id_atencion=".$id." And chek_es_diagnostica=TRUE
And ateprod.id_estado_reg=1 Order by ateprod.orden_muestra_producto";
		} else {
			/*$this->sql = "Select prod.cod_producto_orionlab id From lab.tbl_labproductoatencion ateprod
	Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
	Where ateprod.id_atencion=".$id." And prod.cod_producto_orionlab<>'' And prod.id_producto in (66,2,57,3,8,64,74) And ateprod.id_estado_reg=1 Order by ateprod.orden_muestra_producto";*/
			$this->sql = "Select prod.cod_producto_orionlab id From lab.tbl_labproductoatencion ateprod
Inner Join tbl_producto prod On ateprod.id_producto = prod.id_producto
Inner Join public.tbl_labproductodepen proddep On ateprod.id_producto = proddep.id_producto And ateprod.id_dependencia = proddep.id_dependencia
Where ateprod.id_atencion=".$id." And chek_es_diagnostica=TRUE
And ateprod.id_estado_reg=1 Order by ateprod.orden_muestra_producto";
		}
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

	/**********************************************************************************************************************************/
	//////////////////////////////////////////////// TICKET ////////////////////////////////////////////////////////////////
	/**********************************************************************************************************************************/

	public function get_datosTicket_id_atencion($id, $caso = 0) {
		$this->db->getConnection();
		if($caso == 0){
			$this->sql = "Select  pac.primer_ape||Case When pac.segundo_ape <> '' Then ' '||pac.segundo_ape Else '' End apellidos_pac, pac.nombre_rs nombres_pac, 
tdocpac.abreviatura tipo_documento_pac, pac.nrodoc numero_documento_pac, Case id_sexo When 1 Then 'M' Else 'F' End sexo_pac, date_part('year',age(ate.fec_cita, pac.fec_nac)) as edad_anio_pac, 
Case ate.id_tipo_genera_correlativo When 1 Then SUBSTRING(ate.anio_atencion::varchar FROM 3 FOR 2)||LPAD(ate.nro_atencion::Varchar, 5, '0') Else ate.nro_atencion::Varchar End numero_orden, 
to_char(ate.fec_cita, 'dd/mm/yyyy') fecha_atencion, '10' codigo_sufijo_muestra, 'Sangre Total' sufijo_muestra
From lab.tbl_labatencion ate
Inner Join tbl_dependencia dep On ate.id_dependencia = dep.id_dependencia
Inner Join tbl_plantarifa tari On ate.id_plantarifario = tari.id_plan
Inner Join tbl_persona pac On ate.id_paciente = pac.id_persona
Inner Join tbl_tipodoc tdocpac On pac.id_tipodoc = tdocpac.id_tipodoc
Where id=" . $id;
		}
		$this->rs = $this->db->query_assoc($this->sql);
		$this->db->closeConnection();
		return $this->rs[0];
	}
	
	public function reg_imprime_ticket($accion, $id_atencion, $impresora, $nro_atencion, $cod_zpl, $sufijo) {
		$this->db->getConnection();
		$aparam = array($accion, $id_atencion, $impresora, $nro_atencion, $cod_zpl, $sufijo);
		$this->sql = "select lab.sp_crud_lab_imprime_ticket($1,$2,$3,$4,$5,$6, null);";
		$this->rs = $this->db->query_params($this->sql, $aparam);
		$this->db->closeConnection();
		return $this->rs[0][0];
	}
}