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
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['valreferencial'], $param[0]['userIngreso']);
    $this->sql = "select lab.sp_reg_metodo_componente($1,$2,$3,$4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_datosComponentePorId($idComp) {
    $this->db->getConnection();
    $aparam = array($idComp);
    $this->sql = "Select c.id_componente, c.descrip_comp, c.id_unimedida, um.descrip_unimedida uni_medida, c.descrip_valor,
    c.idtipo_ingresol, Case c.idtipo_ingresol When 1 Then 'LINEA'::Varchar When 2 Then 'MULTILINEA'::Varchar Else 'SELECCION' End ing_solu,
    idtipocaracter_ingresul, Case idtipocaracter_ingresul When 1 Then 'LETRAS/NUMEROS'::Varchar When 2 Then 'LETRAS'::Varchar When 3 Then 'NUMERO ENTERO'::Varchar When 4 Then 'NUMERO DECIMAL'::Varchar Else ''::Varchar End nomtipocaracter_ingresul,
    detcaracter_ingresul, c.idseleccion_ingresul, selresul.nombre_resultado nombre_selecresultado,
    c.estado id_estado, Case When c.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
	Left Join tbl_componente_seleccionresul selresul On c.idseleccion_ingresul = selresul.id
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
    c.idtipo_ingresol, Case c.idtipo_ingresol When 1 Then 'LINEA'::Varchar When 2 Then 'MULTILINEA'::Varchar Else 'SELECCION'::Varchar End ing_solu,
    idtipocaracter_ingresul, 
	Case idtipocaracter_ingresul When 1 Then 'LETRAS/NUMEROS'::Varchar When 2 Then 'LETRAS'::Varchar When 3 Then 'NUMERO ENTERO'::Varchar When 4 Then 'NUMERO DECIMAL'::Varchar Else ''::Varchar End nomtipocaracter_ingresul,
    detcaracter_ingresul, c.idseleccion_ingresul, selresul.nombre_resultado nombre_selecresultado,
    c.estado, Case When c.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
	Left Join tbl_componente_seleccionresul selresul On c.idseleccion_ingresul = selresul.id";
    $this->sql .= $sWhere. $sOrder . $sLimit;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_tblCountComponente($sWhere, $param) {
    $this->db->getConnection();
    $this->sql = "Select count(*) cnt From tbl_componente c
    Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
	Left Join tbl_componente_seleccionresul selresul On c.idseleccion_ingresul = selresul.id";
    $this->sql .= $sWhere;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

  public function get_datosValorReferencialPorIdCompMet($idCompmet) {
    $this->db->getConnection();
    $aparam = array($idCompmet);
    $this->sql = "Select cvr.id, cvr.id_sexo, cvr.edadanio_min, cvr.edadmes_min, cvr.edaddia_min,
cvr.edadanio_max, cvr.edadmes_max, cvr.edaddia_max, cvr.lim_inf, cvr.lim_sup, cvr.descip_valref,
cvr.estado, Case When cvr.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado
From lab.tbl_componente_metodovalref cvr
Where cvr.id_componente_metodo=$1 And cvr.estado=1
Order By cvr.edadanio_min, cvr.edadmes_min, cvr.edaddia_min, cvr.lim_inf";
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
    $this->sql = "Select cpr.id_componentedetprod, cd.id_componentedet, c.id_componente, cd.ord_compodet nro_comp, c.descrip_comp componente, um.descrip_unimedida uni_medida, ga.id_grupoarea, ga.ord_grupoarea nro_grupoarea, descrip_grupo grupo, a.ord_area nro_area, descrip_area area,
c.idtipocaracter_ingresul, Case idtipocaracter_ingresul When 1 Then 'LETRAS/NUMEROS'::Varchar When 2 Then 'LETRAS'::Varchar When 3 Then 'NUMERO ENTERO'::Varchar When 4 Then 'NUMERO DECIMAL'::Varchar Else 'SELECCION'::Varchar End nomtipocaracter_ingresul,
pr.id_producto, pr.nom_producto, prori.nom_producto nom_productoori,
cpr.estado, Case When cpr.estado=1 Then 'ACTIVO'::Varchar Else 'INACTIVO'::Varchar End nom_estado From tbl_componente c
Inner Join tbl_componentedet cd On c.id_componente = cd.id_componente
Inner Join tbl_grupoxarea ga On cd.id_grupoarea = ga.id_grupoarea
Inner Join tbl_grupo g On ga.id_grupo = g.id_grupo
Inner Join tbl_area a On ga.id_area = a.id_area
Inner Join tbl_componentedetprod cpr On cd.id_componentedet = cpr.id_componentedet
Inner Join tbl_producto pr On cpr.id_producto = pr.id_producto
Left Join tbl_unimedida um On c.id_unimedida = um.id_unimedida
Left Join tbl_producto prori On cpr.id_productoori = prori.id_producto
Where ga.estado=1 And g.estado=1 And a.estado=1 And pr.id_producto=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosValidaValReferencialComp($idProdGrupoComp, $idCompMet, $valoring, $edadAnio, $edadMes, $edadDia, $idSexo) {
    $this->db->getConnection();
    $aparam = array($idProdGrupoComp, $idCompMet, $valoring, $edadAnio, $edadMes, $edadAnio, $idSexo);
    $this->sql = "Select * From lab.sp_show_valorreefporsexoandedad($1, $2, $3, $4, $5, $6, $7);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_datosValidaValReferencialCompResul($id) {
    $this->db->getConnection();
    $this->sql = "Select cmvr.id idcompvalref, lim_inf liminf, lim_sup limsup, descip_valref descripvalref 
	From lab.tbl_componente_metodovalref cmvr Where id=".$id;
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
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

  public function get_valor_defectoSeleccionResultado($idTipo) {
    $this->db->getConnection();
    $this->sql = "select coalesce((SELECT id FROM tbl_componente_seleccionresuldet Where id_componente_seleccionresul=".$idTipo." And chk_valor_defecto=TRUE And estado=1 limit 1), 0) id_val_defaul";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    $val_defecto = $this->rs[0]['id_val_defaul'];
	if ($val_defecto == "0"){ $val_defecto='';}
	return $val_defecto;
  }
  
  public function get_listaSeleccionResultadoPorTipo($idTipo) {
    $this->db->getConnection();
    $this->sql = "SELECT id, nombre FROM tbl_componente_seleccionresuldet Where id_componente_seleccionresul=".$idTipo." And estado=1 order by orden_muestra_resul";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaDetSeleccionResultadoPorIdSeleccion($idtipo) {
      $conet = $this->db->getConnection();
      $this->sql = "Select id, nombre, abreviatura, orden_muestra_resul, case when chk_negrita=TRUE Then 'SI' Else 'NO' End negrita, case when chk_valor_defecto=TRUE Then 'SI' Else 'NO' End valor_defecto_resul  From tbl_componente_seleccionresuldet Where id_componente_seleccionresul=".$idtipo." And estado=1 order by orden_muestra_resul;";
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs;
  }
  
  public function post_reg_componente_seleccionresul($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['datos'], $param[0]['userIngreso']);
    $this->sql = "select sp_reg_lab_componente_seleccionresul($1,$2,$3,$4);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

    public function get_listaDetComponentePorProductos() {
        $conet = $this->db->getConnection();
        $this->sql = "Select cd.id_componentedet, c.descrip_comp componente From public.tbl_componente c
Inner Join public.tbl_componentedet cd On c.id_componente=cd.id_componente
Inner Join public.tbl_componentedetprod cdpr On cd.id_componentedet=cdpr.id_componentedet
Group By cd.id_componentedet, c.descrip_comp";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

	public function get_listaMetodoPorIdComponente($idcomp, $estadoreg=0) {
		$conet = $this->db->getConnection();
		$this->sql = "Select compmet.id, met.nombre_metodo, met.descrip_metodo,
Case id_tipo_val_ref When 1 Then 'POR EDAD' else 'POR PORCENTAJE' end nombre_tipo_val_ref,
(Select count(1) From lab.tbl_componente_metodovalref Where id_componente_metodo=compmet.id And estado=1) cnt_valref,
compmet.estado, case compmet.estado When 1 Then 'ACTIVO' ELSE 'INACTIVO' End nom_estado From tbl_componente co 
Inner Join lab.tbl_componente_metodo compmet On co.id_componente = compmet.id_componente
Inner Join lab.tbl_metodo met On compmet.id_metodo=met.id
Where co.id_componente=" . $idcomp . "";
		if ($estadoreg <> 0){
			$this->sql .= " And compmet.estado=" . $estadoreg;
		}
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
