<?php

include_once 'ConectaDb.php';

class Componente {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

  public function post_reg_componente($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['componente'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_componente($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function post_reg_componentedet($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['componentedet'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_componentedet($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function post_reg_componentevalref($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['valreferencial'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_componentevalref($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_datosComponentePorId($idComp) {
    $this->db->getConnection();
    $aparam = array($idComp);
    $this->sql = "Select c.id_componente, c.descrip_comp, c.id_unimedida, um.descrip_unimedida uni_medida, c.descrip_valor,
    c.idtipo_ingresol, Case When c.idtipo_ingresol=1 Then 'LINEA'::Varchar Else 'MULTILINEA'::Varchar End ing_solu,
    idtipocaracter_ingresul, Case idtipocaracter_ingresul When 1 Then 'LETRAS/NUMEROS'::Varchar When 2 Then 'LETRAS'::Varchar When 3 Then 'NUMERO ENTERO'::Varchar Else 'NUMERO DECIMAL'::Varchar End nomtipocaracter_ingresul,
    detcaracter_ingresul,
    c.estado id_estado, Case When c.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
    Where c.id_componente=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponenteActivo() {
    $this->db->getConnection();
    $this->sql = "Select c.id_componente, c.descrip_comp From tbl_componente c Where c.estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaCompDetPorIdGrupoArea($idGrupoArea){
    $this->db->getConnection();
    $aparam = array($idGrupoArea);
    $this->sql = "Select cd.id_componentedet, c.id_componente, c.descrip_comp componente, Case When um.id_unimedida isNull Then '-' Else um.descrip_unimedida End uni_medida
    From tbl_componente c
    Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
    Where cd.estado=1 And c.estado=1 And cd.id_grupoarea=$1";
    $this->sql .= " Order By c.descrip_comp";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function post_reg_componentedetcpt($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['detcompcpt'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_componentedetcpt($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function post_reg_componentedetproducto($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['detcompprod'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_componentedetprod($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_listaComponentePorIdArea($idArea) {
    $this->db->getConnection();
    $aparam = array($idArea);
    $this->sql = "Select * From sp_show_componenteporidarea($1);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponentePorIdGrupoArea($idGrupoArea) {
    $this->db->getConnection();
    $aparam = array($idGrupoArea);
    $this->sql = "Select * From sp_show_componenteporidgrupoarea($1);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponentePorIdAreaAndIdAtencionAndIdProducto($idAtencion, $idArea, $idProd) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_componenteporidareaandidatencionandidproducto(" . $idAtencion . ", " . $idArea . ", " . $idProd . ", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponentePorIdGrupoAreaAndIdAtencion($idAtencion, $idGrupoArea) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_componenteporidgrupoareaandidatencion(" . $idAtencion . ", " . $idGrupoArea . ", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponentePorIdGrupoAreaAndIdAtencionAndIdProducto($idAtencion, $idGrupoArea, $idProd) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_componenteporidgrupoareaandidatencionandidproducto(" . $idAtencion . ", " . $idGrupoArea . ", " . $idProd . ", 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaComponenteResulPorIdGrupoAreaAndIdAtencionAndIdProducto($idGrupoArea, $idAtencion, $idProd) {
    $this->db->getConnection();
    $aparam = array($idGrupoArea, $idAtencion, $idProd);
    $this->sql = "Select * From sp_show_componenteresulporidgrupoareaandidatencionandidproducto($1, $2, $3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblDatosComponente($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "Select c.id_componente, c.descrip_comp, c.id_unimedida, um.descrip_unimedida uni_medida, c.descrip_valor,
    c.idtipo_ingresol, Case When c.idtipo_ingresol=1 Then 'LINEA'::Varchar Else 'MULTILINEA'::Varchar End ing_solu,
    idtipocaracter_ingresul, Case idtipocaracter_ingresul When 1 Then 'LETRAS/NUMEROS'::Varchar When 2 Then 'LETRAS'::Varchar When 3 Then 'NUMERO ENTERO'::Varchar Else 'NUMERO DECIMAL'::Varchar End nomtipocaracter_ingresul,
    detcaracter_ingresul,
    c.estado, Case When c.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida";
    $this->sql .= $sWhere. $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblCountComponente($sWhere, $param) {
    $this->db->getConnection();
    $this->sql = "Select count(*) cnt From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida";
    $this->sql .= $sWhere;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

  public function get_datosValorReferencialPorIdComp($idComp) {
    $this->db->getConnection();
    $aparam = array($idComp);
    $this->sql = "Select id_compvalref, c.id_componente, c.descrip_comp, id_sexo, edadanio_min, edadmes_min, edaddia_min,
edadanio_max, edadmes_max, edaddia_max, lim_inf, lim_sup, descip_valref,
cvr.estado, Case When cvr.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado
From tbl_componentevalref cvr
Inner Join tbl_componente c On cvr.id_componente = c.id_componente
Where c.id_componente=$1
Order By edadanio_min, edadmes_min, edaddia_min";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosDetCompPorIdGrupoArea($idGrupoArea) {
    $this->db->getConnection();
    $aparam = array($idGrupoArea);
    $this->sql = "Select cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, um.descrip_unimedida uni_medida, c.descrip_valor valor_ref, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
    cd.estado, Case When cd.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
    Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
    Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
    Inner Join tbl_area a On ga.id_area = a.id_area
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
    Where ga.estado=1 And g.estado=1 And a.estado=1 And ga.id_grupoarea=$1 Order By cd.ord_compodet";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosComponentePorIdCpt($idCpt) {
    $this->db->getConnection();
    $aparam = array($idCpt);
    $this->sql = "Select ccpt.id_componentedetcpt, cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
    cpt.id_cpt, cpt.denominacion_cpt, ccpt.estado, Case When ccpt.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
    Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
    Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
    Inner Join tbl_area a On ga.id_area = a.id_area
    Inner Join tbl_componentedetcpt ccpt On cd.id_componentedet = ccpt.id_componentedet
    Inner Join tbl_cpt cpt On ccpt.id_cpt = cpt.id_cpt
    Where ga.estado=1 And g.estado=1 And a.estado=1 And ccpt.id_cpt=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosComponentePorIdProducto($idProd) {
    $this->db->getConnection();
    $aparam = array($idProd);
    $this->sql = "Select cpr.id_componentedetprod, cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
    pr.id_producto, pr.nom_producto, cpr.estado, Case When cpr.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
    Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
    Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
    Inner Join tbl_area a On ga.id_area = a.id_area
    Inner Join tbl_componentedetprod cpr On cd.id_componentedet = cpr.id_componentedet
    Inner Join tbl_producto pr On cpr.id_producto = pr.id_producto
    Where ga.estado=1 And g.estado=1 And a.estado=1 And pr.id_producto=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosValidaValReferencialComp($idComp, $edadAnio, $edadMes, $edadDia, $idSexo) {
    $this->db->getConnection();
    $aparam = array($idComp, $edadAnio, $edadMes, $edadAnio, $idSexo);
    $this->sql = "Select * From sp_show_valorreefporsexoandedad($1, $2, $3, $4, $5);";
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

  public function get_tblDatosCpt($sWhere, $sOrder, $sLimit, $param) {
    $this->db->getConnection();
    $this->sql = "Select cpt.id_cpt, cpt.denominacion_cpt, (Select count(id_cpt) From tbl_componentedetcpt Where id_cpt=cpt.id_cpt And estado=1) cnt_comp From tbl_cpt cpt
    Where cpt.estado=1";
    $this->sql .= $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblCountCpt($sWhere, $param) {
    $this->db->getConnection();
    $this->sql = "Select count(*) cnt From tbl_cpt cpt
    Where cpt.estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

   public function get_listaTipoSeleccionResultado() {
    $this->db->getConnection();
    $this->sql = "SELECT id, nombre_resultado tipo FROM tbl_componente_seleccionresul Where estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaMetodos($id=0, $estadoreg=0) {
		$conet = $this->db->getConnection();
		$this->sql = "SELECT id, abreviatura_metodo, nombre_metodo, descrip_metodo,
    estado, case estado When 1 Then 'ACTIVO' ELSE 'INACTIVO' End nom_estado
    FROM lab.tbl_metodo";
		if ($id <> 0){
			$this->sql .= " Where id=" . $id;
		}
		if ($estadoreg <> 0){
			$this->sql .= " Where estado=" . $estadoreg;
		}
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

  /*

  public function get_tblDatosComponentePorGrupoArea($sWhere, $sOrder, $sLimit, $param) {
  $this->db->getConnection();
  $this->sql = "Select cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
  cd.estado, Case When cd.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
  Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
  Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
  Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
  Inner Join tbl_area a On ga.id_area = a.id_area
  Where ga.estado=1 And g.estado=1 And a.estado=1";
  if (!empty($param[0]['idEstado'])) {
  $this->sql .= " And cd.estado = '" . $param[0]['idEstado'] . "'";
}
$this->sql .= $sOrder . $sLimit;
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs;
}

public function get_tblCountComponentePorGrupoArea($sWhere, $param) {
$this->db->getConnection();
$this->sql = "Select count(*) cnt From tbl_componente c
Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
Inner Join tbl_area a On ga.id_area = a.id_area
Where ga.estado=1 And g.estado=1 And a.estado=1";
if (!empty($param[0]['idEstado'])) {
$this->sql .= " And cd.estado = '" . $param[0]['idEstado'] . "'";
}
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs[0]['cnt'];
}

public function get_tblDatosComponentePorCPT($sWhere, $sOrder, $sLimit, $param) {
$this->db->getConnection();
$this->sql = "Select cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
cpt.id_cpt, cpt.denominacion_cpt, cd.estado, Case When cd.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
Inner Join tbl_area a On ga.id_area = a.id_area
Inner Join tbl_componentedetcpt ccpt On cd.id_componentedet = ccpt.id_componentedet
Inner Join tbl_cpt cpt On ccpt.id_cpt = cpt.id_cpt
Where ga.estado=1 And g.estado=1 And a.estado=1";
if (!empty($param[0]['idEstado'])) {
$this->sql .= " And cd.estado = '" . $param[0]['idEstado'] . "'";
}
$this->sql .= $sOrder . $sLimit;
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs;
}

public function get_tblCountComponentePorCPT($sWhere, $param) {
$this->db->getConnection();
$this->sql = "Select count(*) cnt From tbl_componente c
Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
Inner Join tbl_area a On ga.id_area = a.id_area
Inner Join tbl_componentedetcpt ccpt On cd.id_componentedet = ccpt.id_componentedet
Inner Join tbl_cpt cpt On ccpt.id_cpt = cpt.id_cpt
Where ga.estado=1 And g.estado=1 And a.estado=1";
if (!empty($param[0]['idEstado'])) {
$this->sql .= " And cd.estado = '" . $param[0]['idEstado'] . "'";
}
$this->rs = $this->db->query($this->sql);
$this->db->closeConnection();
return $this->rs[0]['cnt'];
}
*/

}
