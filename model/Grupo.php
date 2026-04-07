<?php

include_once 'ConectaDb.php';

class Grupo {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

  public function post_reg_grupo($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['grupo'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_grupo($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_listaGrupoPorIdArea($idArea) {
    $this->db->getConnection();
    $aparam = array($idArea);
    $this->sql = "Select * From sp_show_grupoporidarea($1);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }


  public function get_datoGrupoPorId($idGrupo) {
    $this->db->getConnection();
    $aparam = array($idGrupo);
    $this->sql = "Select id_grupo, descrip_grupo, estado From tbl_grupo Where id_grupo=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaGrupoActivo() {
    $this->db->getConnection();
    $this->sql = "Select g.id_grupo, g.descrip_grupo From tbl_grupo g Where g.estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaGrupoPorIdAreaAndIdAtencion($idAtencion, $idArea, $idGrupo) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_grupoporidareaandidatencion(" . $idAtencion . ", " . $idArea . ", " . $idGrupo . ", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaGrupoPorIdAreaAndIdAtencionAndIdProducto($idAtencion, $idArea, $idProd) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_grupoporidareaandidatencionandidproducto(" . $idAtencion . ", " . $idArea . ", " . $idProd . ", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function post_reg_grupoarea($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['grupoarea'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_grupoarea($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_tblDatosGrupo($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "Select g.id_grupo, g.descrip_grupo, g.estado,
    Case When g.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_grupo g";
    if (!empty($param[0]['idEstado'])) {
      $this->sql .= " Where g.estado = '" . $param[0]['idEstado'] . "'";
    }
    $this->sql .= $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblCountGrupo($sWhere, $param) {
    $this->db->getConnection();
    $this->sql = "Select count(*) cnt From tbl_grupo g";
    if (!empty($param[0]['idEstado'])) {
      $this->sql .= " Where g.estado = '" . $param[0]['idEstado'] . "'";
    }
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

  public function get_listaGrupoAreaPorIdArea($idArea){
    $this->db->getConnection();
    $aparam = array($idArea);
    $this->sql = "Select ga.id_grupoarea, g.descrip_grupo grupo From tbl_grupo g
    Inner Join tbl_grupoxarea ga On ga.id_grupo = g.id_grupo
    Where ga.estado=1 And ga.id_area=$1";
    $this->sql .= " Order By ga.ord_grupoarea";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosGrupoPorIdArea($idArea, $idEst = 0) {
    $this->db->getConnection();
    $aparam = array($idArea);
    $this->sql = "Select ga.id_grupoarea, a.ord_area nro_area, descrip_area area, descrip_grupo grupo, ga.ord_grupoarea nro_grupoarea, ga.visible, ga.estado,
    Case When ga.visible='1' Then 'VISIBLE'::Varchar Else 'NO VISIBLE'::Varchar End nom_visible,
    (Select count(*) From tbl_componentedet cd Where cd.id_grupoarea=ga.id_grupoarea And cd.estado=1) cnt_comp,
    Case When ga.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_grupo g
    Inner Join tbl_grupoxarea ga On ga.id_grupo = g.id_grupo
    Inner Join tbl_area a On ga.id_area = a.id_area
    Where g.estado=1 And a.estado=1 And a.id_area=$1";
    if ($idEst <> 0) {
      $this->sql .= " And ga.estado = " . $idEst . "";
    }
    $this->sql .= " Order By ga.ord_grupoarea";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblDatosArea($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "Select a.id_area, a.ord_area, a.abrev_area, a.descrip_area, a.visible, (Select count(id_area) From tbl_grupoxarea Where id_area=a.id_area And estado=1) cnt_grupo,
    Case When a.visible='1' Then 'VISIBLE'::Varchar Else 'NO VISIBLE'::Varchar End nom_visible From tbl_area a
    Where a.estado = 1";
    $this->sql .= $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblCountArea($sWhere, $param) {
    $this->db->getConnection();
    $this->sql = "Select count(*) cnt From tbl_area a
    Where a.estado = 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

  /*public function get_tblDatosGrupoPorArea($sWhere, $sOrder, $sLimit, $param) {
  $this->db->getConnection();
  $this->sql = "Select ga.id_grupoarea, a.ord_area nro_area, descrip_area area, id_grupoarea, descrip_grupo grupo, ga.ord_grupoarea nro_grupoarea, ga.visible, ga.estado,
  Case When ga.visible='1' Then 'VISIBLE'::Varchar Else 'NO VISIBLE'::Varchar End nom_visible,
  Case When ga.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_grupo g
  Inner Join tbl_grupoxarea ga On ga.id_grupo = g.id_grupo
  Inner Join tbl_area a On ga.id_area = a.id_area
  Where g.estado=1 And a.estado=1";
  if (!empty($param[0]['idEstado'])) {
  $this->sql .= " And ga.estado = '" . $param[0]['idEstado'] . "'";
}
$this->sql .= $sOrder . $sLimit;
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs;
}

public function get_tblCountGrupoPorArea($sWhere, $param) {
$this->db->getConnection();
$this->sql = "Select count(*) cnt From tbl_grupo g
Inner Join tbl_grupoxarea ga On ga.id_grupo = g.id_grupo
Inner Join tbl_area a On ga.id_area = a.id_area
Where g.estado=1 And a.estado=1";
if (!empty($param[0]['idEstado'])) {
$this->sql .= " And ga.estado = '" . $param[0]['idEstado'] . "'";
}
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs[0]['cnt'];
}*/

}
