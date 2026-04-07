<?php

include_once 'ConectaDb.php';

class Levey {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }
  
  public function post_reg_levey($paramReg) {
    $this->db->getConnection();
    $aparam = array($paramReg[0]['accion'], $paramReg[0]['id'], $paramReg[0]['datos'], $paramReg[0]['userIngreso']);
    $this->sql = "Select * from lab_levey.sp_crud_levey($1, $2, $3, $4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_datosLeveyUltimoPorIdControlAndIdDepAndAnioAndMes($id_control, $id_dependencia, $anio, $mes) {
    $this->db->getConnection();
    $this->sql = "Select ledep.id, le.id_control_calidad, cc.nombre_control, tcc.nombre_tipo, le.nro_lote, le.ds, le.media, 
round(le.x_3ds_nega,2)x_3ds_nega, round(le.x_2ds_nega,2)x_2ds_nega, round(le.x_1ds_nega,2)x_1ds_nega, round(le.x_3ds_posi,2)x_3ds_posi, round(le.x_2ds_posi,2)x_2ds_posi, round(le.x_1ds_posi,2)x_1ds_posi,
Case When le.x_3ds_nega < 20 Then round(le.x_3ds_nega,2) Else round(le.x_3ds_nega - 2,2) End valor_min, 
Case When le.x_3ds_posi < 20 Then round(le.x_3ds_posi,2) Else round(le.x_3ds_posi + 2,2) End valor_max
From lab_levey.tbl_levey_dependencia ledep 
Inner Join lab_levey.tbl_levey le On ledep.id_levey = le.id
Inner Join lab_levey.tbl_control_calidad cc On le.id_control_calidad=cc.id
Inner Join lab_levey.tbl_tipo tcc On cc.id_tipo=tcc.id
Where le.id_control_calidad=" . $id_control . " And ledep.id_dependencia=" . $id_dependencia . " And anio=" . $anio . " And mes=" . $mes . " And le.id_estado=1 And ledep.id_estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosLeveyTodosPorIdControlAndIdDepAndAnioAndMes($id_control, $id_dependencia, $anio, $mes) {
    $this->db->getConnection();
    $this->sql = "Select le.id_control_calidad, cc.nombre_control, tcc.nombre_tipo, le.nro_lote, le.ds, le.media, 
round(le.x_3ds_nega,2)x_3ds_nega, round(le.x_2ds_nega,2)x_2ds_nega, round(le.x_1ds_nega,2)x_1ds_nega, round(le.x_3ds_posi,2)x_3ds_posi, round(le.x_2ds_posi,2)x_2ds_posi, round(le.x_1ds_posi,2)x_1ds_posi,
Case When le.x_3ds_nega < 20 Then round(le.x_3ds_nega,2) Else round(le.x_3ds_nega - 2,2) End valor_min, 
Case When le.x_3ds_posi < 20 Then round(le.x_3ds_posi,2) Else round(le.x_3ds_posi + 2,2) End valor_max, ledep.create_at::date
From lab_levey.tbl_levey_dependencia ledep 
Inner Join lab_levey.tbl_levey le On ledep.id_levey = le.id
Inner Join lab_levey.tbl_control_calidad cc On le.id_control_calidad=cc.id
Inner Join lab_levey.tbl_tipo tcc On cc.id_tipo=tcc.id
Where le.id_control_calidad=" . $id_control . " And ledep.id_dependencia=" . $id_dependencia . " And anio=" . $anio . " And mes=" . $mes . " And le.id_estado=1 And ledep.id_estado in (1,3)
Order by ledep.create_at::date";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_datosControlCalidadPorId($id) {
    $this->db->getConnection();
    $this->sql = "Select cc.id, cc.nombre_control, tcc.nombre_tipo From lab_levey.tbl_control_calidad cc 
Inner Join lab_levey.tbl_tipo tcc On cc.id_tipo=tcc.id Where cc.id=" . $id;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

	public function get_listaControlCalidad() {
		$conet = $this->db->getConnection();
		$this->sql = "Select cc.id, cc.nombre_control, tcc.nombre_tipo From lab_levey.tbl_control_calidad cc 
Inner Join lab_levey.tbl_tipo tcc On cc.id_tipo=tcc.id Where cc.id_estado=1";
		$this->sql .= " Order By cc.id";
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
	
	public function get_tblDatosLevey($sWhere, $sOrder, $sLimit, $param) {
		$this->db->getConnection();
		$this->sql = "SELECT count(*) OVER() AS cant_rows, le.id, le.id_control_calidad, tcc.nombre_tipo, cc.nombre_control, le.nro_lote, le.ds, le.media, 
round(le.x_3ds_nega,2)x_3ds_nega, round(le.x_2ds_nega,2)x_2ds_nega, round(le.x_1ds_nega,2)x_1ds_nega, round(le.x_3ds_posi,2)x_3ds_posi, round(le.x_2ds_posi,2)x_2ds_posi, round(le.x_1ds_posi,2)x_1ds_posi, 
(Select count(1) From lab_levey.tbl_levey_dependencia Where id_levey=le.id And id_estado=1) cnt_leveydep,
le.id_estado, Case le.id_estado When 1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado
FROM lab_levey.tbl_levey le Inner Join lab_levey.tbl_control_calidad cc On le.id_control_calidad=cc.id
Inner Join lab_levey.tbl_tipo tcc On cc.id_tipo=tcc.id";
		if (!empty($param[0]['id_control_calidad'])) {
			$this->sql .= " And le.id_control_calidad=" . $param[0]['id_control_calidad'] . "";
		}
		if (!empty($param[0]['nro_lote'])) {
			$this->sql .= " And le.nro_lote ilike '%" . $param[0]['nro_lote'] . "%'";
		}
		$this->sql .= $sWhere. $sOrder . $sLimit;
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

  public function get_tblDatosLeveyDetalle($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "SELECT count(*) OVER() AS cant_rows, led.id, le.nro_lote, ledep.id_dependencia, extract(day from led.fecha) dia, to_char(led.fecha, 'dd/mm/yyyy') fecha, led.valor_fecha, led.z_socre, led.justificacion, ledep.id_estado id_estado_levey_dep FROM lab_levey.tbl_levey_detalle led 
	Inner Join lab_levey.tbl_levey_dependencia ledep On led.id_levey_dep=ledep.id
	Inner Join lab_levey.tbl_levey le On ledep.id_levey=le.id where led.id_estado=1";
    if (!empty($param[0]['id_estado_levey_dep'])) {
		$this->sql .= " And ledep.id_estado in (" . $param[0]['id_estado_levey_dep'] . ")";
	}
    if (!empty($param[0]['id_control_calidad'])) {
		$this->sql .= " And le.id_control_calidad=" . $param[0]['id_control_calidad'] . "";
	}
    if (!empty($param[0]['id_dependencia'])) {
		$this->sql .= " And ledep.id_dependencia=" . $param[0]['id_dependencia'] . "";
	}
    if (!empty($param[0]['anio'])) {
		$this->sql .= " And extract(year from led.fecha)=" . $param[0]['anio'] . "";
	}
    if (!empty($param[0]['mes'])) {
		$this->sql .= " And extract(month from led.fecha)=" . $param[0]['mes'] . "";
	}
    $this->sql .= $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_datosDependenciaPorIdLevey($id) {
    $this->db->getConnection();
    $aparam = array($id);
    $this->sql = "Select ledep.id, d.nom_depen, ledep.anio, ledep.mes From tbl_dependencia d
Inner Join lab_levey.tbl_levey_dependencia ledep On d.id_dependencia = ledep.id_dependencia
Where ledep.id_levey=$1 And ledep.id_estado=1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }
    
  public function get_datosListaDetalleLeveyPorId($id) {
    $this->db->getConnection();
    $this->sql = "Select to_char(led.create_at, 'dd/mm/yyyy hh12:mi:ss AM') fec_registro, nom_usuario nom_usuregistro, valor_fecha, justificacion, id_estado From lab_levey.tbl_levey_detalle led
Inner Join tbl_usuario ureg On led.user_create_at = ureg.id_usuario
Where id_levey_dep::Varchar||fecha::Varchar=(Select id_levey_dep::Varchar||fecha::Varchar From lab_levey.tbl_levey_detalle Where id=" . $id . ")
Order By led.create_at";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
    
  public function get_repListaDetalleLevey($id_area, $anio, $mes, $id_dependencia=0) {
    $this->db->getConnection();
    $this->sql = "SELECT led.id, dep.nom_depen, tipexa.nombre_tipo, exa.nombre_control,
extract(day from led.fecha) dia, to_char(led.fecha, 'dd/mm/yyyy') fecha, le.nro_lote, 
led.valor_fecha, ledep.id_estado id_estado_levey_dep FROM lab_levey.tbl_levey_detalle led 
Inner Join lab_levey.tbl_levey_dependencia ledep On led.id_levey_dep=ledep.id
Inner Join lab_levey.tbl_levey le On ledep.id_levey=le.id 
Inner Join lab_levey.tbl_control_calidad exa On le.id_control_calidad=exa.id
Inner Join lab_levey.tbl_tipo tipexa On exa.id_tipo=tipexa.id
Inner join public.tbl_dependencia dep On ledep.id_dependencia=dep.id_dependencia
where tipexa.id_area=" . $id_area . " And led.id_estado=1 And extract(year from led.fecha)=" . $anio . " And extract(month from led.fecha)=" . $mes;
	if($id_dependencia <> 0) {
		$this->sql .= " And dep.id_dependencia=" . $id_dependencia;
	}
	$this->sql .= " Order By dep.nro_ris_pertenece, dep.nom_depen, tipexa.id, exa.id, led.fecha";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

}
