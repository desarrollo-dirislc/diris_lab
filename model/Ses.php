<?php

include_once 'ConectaDb.php';

class Ses {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }
  
  public function reg_informe_ses($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['dato_informe'], $param[0]['dato_atencion'], $param[0]['dato_examen'], $param[0]['dato_det_examen'], $param[0]['userIngreso']);
    $this->sql = "select lab_ses.sp_crud_lab_informe($1,$2,$3,$4,$5,$6,$7);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
  
  public function get_tblDatosInformeSES($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "SELECT count(*) OVER() AS cant_rows, inf.id, inf.id_establecimiento, d.nom_depen nom_dependencia, inf.anio_informe, inf.mes_informe, inf.cnt_total_ate_lab, inf.cnt_total_exa_lab, cnt_total_ate_bac, cnt_total_exa_bac, to_char(inf.create_at, 'DD/MM/YYY') fecha_registro, 
chk_bloqueado, case When chk_bloqueado = true Then 'SI' Else 'NO' End es_bloqueado
FROM lab_ses.tbl_informe inf
Inner Join public.tbl_dependencia d On inf.id_establecimiento=d.id_dependencia
Where inf.anio_informe=" . $param[0]['anio'];
    if ($param[0]['tipo_repor'] == "0") {
		$this->sql .= " And inf.mes_informe=" . $param[0]['mes'];
	} 
    if (!empty($param[0]['id_dependencia'])) {
		$this->sql .= " And inf.id_establecimiento=" . $param[0]['id_dependencia'];
	}
    $this->sql .= $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_existe_informePorAnioMesIdDep($param) {
    $this->db->getConnection();
	$this->sql = "select count(1) from lab_ses.tbl_informe
Where id_establecimiento=" . $param[0]['id_dependencia'] . " and anio_informe=" . $param[0]['anio'] . " And mes_informe=" . $param[0]['mes'] . "";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }
  
  public function get_repCntAtencionPorAnioMesAndIdDependencia($param) {
    $this->db->getConnection();
	$this->sql = "Select nro_ris_pertenece, id_dependencia, nom_depen, sum(cnt) cnt, sum(cnt_bk) cnt_bk From (
Select nro_ris_pertenece, id_dependencia, nom_depen, 0 cnt, 0 cnt_bk From public.tbl_dependencia Where check_lab_diris=TRUE";
    if (!empty($param[0]['id_dependencia'])) {
		$this->sql .= " And id_dependencia=" . $param[0]['id_dependencia'];
	}
$this->sql .= " Union all
SELECT d.nro_ris_pertenece, inf.id_establecimiento, d.nom_depen nom_dependencia, inf.cnt_total_exa_lab, inf.cnt_total_ate_bac cnt_bk
FROM lab_ses.tbl_informe inf
Inner Join public.tbl_dependencia d On inf.id_establecimiento=d.id_dependencia
Where inf.anio_informe=" . $param[0]['anio'] . " And inf.mes_informe=" . $param[0]['mes'] . " And inf.id_estado=1";
    if (!empty($param[0]['id_dependencia'])) {
		$this->sql .= " And inf.id_establecimiento=" . $param[0]['id_dependencia'];
	}
$this->sql .= " ) x group by nro_ris_pertenece, id_dependencia, nom_depen order By nro_ris_pertenece";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  /*****************************************************************************************/
  ///////////////////////////////////////  EDITAR  /////////////////////////////////////////
  /*****************************************************************************************/
  
  public function get_datosCabeceraInformeSESLab($id) {
    $conet = $this->db->getConnection();
    $this->sql = "SELECT inf.id, inf.id_establecimiento, d.nom_depen nom_dependencia, inf.anio_informe, inf.mes_informe, inf.cnt_total_ate_lab, inf.cnt_total_exa_lab, to_char(inf.create_at, 'DD/MM/YYY') fecha_registro
FROM lab_ses.tbl_informe inf
Inner Join public.tbl_dependencia d On inf.id_establecimiento=d.id_dependencia
Where inf.id=" . $id;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  
  public function get_datosDetalleInformeSESLabAtencion($id_informe) {
    $conet = $this->db->getConnection();
    $this->sql = "SELECT id, id_informe, idtipo_producto, id_producto, orden_producto, cnt_sis, cnt_pagante, cnt_estrategia, cnt_exonerado, cnt_total
FROM lab_ses.tbl_informe_lab_det Where id_informe=" . $id_informe . " And idtipo_producto=0";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_datosDetalleInformeSESLabExamen($id_informe, $tipprod) {
    $conet = $this->db->getConnection();
    $this->sql = "SELECT infd.id, infd.id_informe, infd.idtipo_producto, infd.id_producto, pr.nom_producto, infd.orden_producto, infd.cnt_sis, infd.cnt_pagante, infd.cnt_estrategia, infd.cnt_exonerado, infd.cnt_total
FROM lab_ses.tbl_informe_lab_det infd 
Inner Join tbl_producto pr On infd.id_producto=pr.id_producto
Where id_informe=" . $id_informe . " And infd.idtipo_producto=" . $tipprod . " Order By infd.orden_producto";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  
  public function get_datosDetalleInformeSESBacBasiloscopia($id_informe) {
    $conet = $this->db->getConnection();
    $this->sql = "SELECT infd.id_diagnostico id, infd.id_informe, c.nombre, infd.orden_diagnostico, infd.cnt_atencion, infd.cnt_posi1, infd.cnt_posi2, infd.cnt_posi3, infd.cnt_pau, infd.cnt_totalposi
FROM lab_ses.tbl_informe_bac_det infd 
Inner Join tbl_componente_seleccionresuldet c On infd.id_diagnostico = c.id And c.id_componente_seleccionresul=45
Where id_informe=" . $id_informe . " Order By infd.orden_diagnostico";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  /*****************************************************************************************/
  ///////////////////////////////////////  REORTES  /////////////////////////////////////////
  /*****************************************************************************************/
  //Informe
  //Atenciones
  public function get_CntInformeSESLabAtencionAndExamenes($tip_rep, $anio, $mes_desde, $mes_hasta, $id_dep) {
    $conet = $this->db->getConnection();
	$this->sql = "SELECT sum(cnt_total_ate_lab) cnt_total_ate_lab, sum(cnt_total_exa_lab) cnt_total_exa_lab From lab_ses.tbl_informe inf 
	Where inf.anio_informe=" . $anio . " And inf.mes_informe Between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  //Atenciones
  public function get_CntDetalleInformeSESLabAtencion($tip_rep, $anio, $mes_desde, $mes_hasta, $id_dep) {
    $conet = $this->db->getConnection();
	if($tip_rep == "EESS"){
		$this->sql = "SELECT dinf.cnt_sis, dinf.cnt_pagante, dinf.cnt_estrategia, dinf.cnt_exonerado, dinf.cnt_total
	FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
	Where inf.anio_informe=" . $anio . " And inf.mes_informe=" . $mes_desde . " And inf.id_establecimiento=" . $id_dep . " And dinf.idtipo_producto=0";
	} else {
		$this->sql = "SELECT Sum(dinf.cnt_sis) cnt_sis, Sum(dinf.cnt_pagante) cnt_pagante, Sum(dinf.cnt_estrategia) cnt_estrategia, Sum(dinf.cnt_exonerado) cnt_exonerado, Sum(dinf.cnt_total) cnt_total
	FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
	Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . " And dinf.idtipo_producto=0";		
	}
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  //Lista de productos 
  public function get_CntDetalleInformeSESLabDetProducto($anio, $mes_desde, $mes_hasta, $tipo_prod) {
    $conet = $this->db->getConnection();
		$this->sql = "SELECT pr.id_producto, pr.nom_producto, dinf.orden_producto
FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Inner Join tbl_producto pr On dinf.id_producto=pr.id_producto
Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . "
And dinf.idtipo_producto=" . $tipo_prod . " Group By pr.id_producto, pr.nom_producto, dinf.orden_producto Order By dinf.orden_producto";		
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  //Total por tipo de producto
  public function get_CntDetalleInformeSESLabDetTipoProducto($tip_rep, $anio, $mes_desde, $mes_hasta, $id_dep, $tipo_prod) {
    $conet = $this->db->getConnection();
	if($tip_rep == "EESS"){
		$this->sql = "SELECT sum(dinf.cnt_sis)cnt_sis, sum(dinf.cnt_pagante)cnt_pagante, sum(dinf.cnt_estrategia)cnt_estrategia, sum(dinf.cnt_exonerado)cnt_exonerado, sum(dinf.cnt_total)cnt_total
FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe=" . $mes_desde . " And inf.id_establecimiento=" . $id_dep . " And dinf.idtipo_producto=".$tipo_prod;		
	} else if($tip_rep == "EESSACU"){
		$this->sql = "SELECT sum(dinf.cnt_sis)cnt_sis, sum(dinf.cnt_pagante)cnt_pagante, sum(dinf.cnt_estrategia)cnt_estrategia, sum(dinf.cnt_exonerado)cnt_exonerado, sum(dinf.cnt_total)cnt_total
FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . " And dinf.idtipo_producto=".$tipo_prod;		
	} else {
		$this->sql = "SELECT sum(dinf.cnt_total) cnt_total
FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . " And dinf.idtipo_producto=".$tipo_prod;				
	}
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  //Detalle de examenes por producto
  public function get_CntDetalleInformeSESLabTotLabExamenXProd($tip_rep, $anio, $mes_desde, $mes_hasta, $id_dep, $id_prod) {
    $conet = $this->db->getConnection();
	if($tip_rep == "EESS"){
		$this->sql = "SELECT dinf.cnt_sis, dinf.cnt_pagante, dinf.cnt_estrategia, dinf.cnt_exonerado, dinf.cnt_total
		FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
		Where inf.anio_informe=" . $anio . " And inf.mes_informe=" . $mes_desde . " And inf.id_establecimiento=" . $id_dep . " And dinf.id_producto=".$id_prod;
	} else if($tip_rep == "EESSACU"){
		$this->sql = "SELECT sum(dinf.cnt_sis)cnt_sis, sum(dinf.cnt_pagante)cnt_pagante, sum(dinf.cnt_estrategia)cnt_estrategia, sum(dinf.cnt_exonerado)cnt_exonerado, sum(dinf.cnt_total)cnt_total
FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . " And dinf.id_producto=".$id_prod;
	} else {
		$this->sql = "SELECT SUM(dinf.cnt_total) cnt_total
		FROM lab_ses.tbl_informe_lab_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
		Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . " And dinf.id_producto=".$id_prod;		
	}
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////
    
  //Detalle de examenes por producto
  public function get_CntDetalleInformeSESLabTotBacBasiloscopia($tip_rep, $anio, $mes_desde, $mes_hasta, $id_dep) {
    $conet = $this->db->getConnection();
	if($tip_rep == "EESS"){
		$this->sql = "SELECT dinf.id_diagnostico, dinf.cnt_atencion, dinf.cnt_posi1, dinf.cnt_posi2, dinf.cnt_posi3, dinf.cnt_pau
FROM lab_ses.tbl_informe_bac_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe=" . $mes_desde . " And inf.id_establecimiento=" . $id_dep;
	} else {
		$this->sql = "SELECT dinf.id_diagnostico, sum(dinf.cnt_atencion) cnt_atencion, sum(dinf.cnt_posi1) cnt_posi1, sum(dinf.cnt_posi2) cnt_posi2, sum(dinf.cnt_posi3) cnt_posi3, sum(dinf.cnt_pau) cnt_pau
FROM lab_ses.tbl_informe_bac_det dinf Inner Join lab_ses.tbl_informe inf On dinf.id_informe=inf.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe between " . $mes_desde . " And " . $mes_hasta . " And inf.id_establecimiento=" . $id_dep . "
Group By dinf.id_diagnostico";
	}
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  //Exportar en csv para Power bi
  public function get_ReportExportCsvPorAnioyMes($anio, $mes) {
    $conet = $this->db->getConnection();
	$this->sql = "Select d.nro_ris_pertenece id_ris, d.nom_depen, inf.anio_informe, inf.mes_informe, tprod.abrev_tipo_producto nombre_tipo_producto, prod.nom_producto, 
unnest(array[cnt_sis, cnt_pagante, cnt_estrategia, cnt_exonerado]) AS cnt_examen,
unnest(array['SIS', 'PAG', 'EST', 'EXO']) AS tipo
from lab_ses.tbl_informe inf
Inner join tbl_dependencia d On inf.id_establecimiento=id_dependencia
Inner join lab_ses.tbl_informe_lab_det inflab On inf.id=inflab.id_informe
Inner Join tbl_producto prod On inflab.id_producto=prod.id_producto
Inner Join tbl_labtipo_producto tprod On prod.idtipo_producto=tprod.id
Where inf.anio_informe=" . $anio . " And inf.mes_informe=" . $mes . " And inf.id_establecimiento<>1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  
}
