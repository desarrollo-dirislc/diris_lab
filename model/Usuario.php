<?php

include_once 'ConectaDb.php';

class Usuario {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

  public function get_listaUsuarioActivo() {
    $this->db->getConnection();
    $aparam = array('1');
    $this->sql = "Select u.id_usuario, p.nombre_rs, p.primer_ape, p.segundo_ape  From tbl_usuario u
    Inner Join tbl_persona p On u.id_persona = p.id_persona
    Where idest_usuario=$1";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaUsuarios($param) {
    $conet = $this->db->getConnection();
    $this->sql = "Select u.id_usuario, p.nombre_rs, p.primer_ape, p.segundo_ape,
    Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When primer_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs nombrecompleto_per From tbl_usuario u
    Inner Join tbl_persona p On u.id_persona = p.id_persona";
    if (!empty($param[0]['nomPerUsu'])) {
      $this->sql .= " Where Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When primer_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs Like Upper('%" . $param[0]['nomPerUsu'] . "%')";
    }
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_repDatosUsuario($param) {
    $conet = $this->db->getConnection();
    $this->sql = "Select u.id_usuario, u.login, u.passwd, u.id_persona, dp.idtipo_doc, tdp.val_abr abrev_tipdocper,dp.nro_documento, p.nombre_rs, p.primer_ape, p.segundo_ape,
    u.id_rol, rol.descripcion nom_rol, u.idest_usuario, u.id_depen, dep.codrefencial_depen codref_depen, dep.nombre_depen nomdep From tbl_usuario u
    Inner Join tbl_persona p On u.id_persona = p.id_persona
    Inner Join tbl_personadocumento dp On p.id_persona = dp.id_persona And idprioridad_doc=1 And idestado_doc='1'
    Inner Join tablatipo tdp on dp.idtipo_doc = tdp.id_tipo and tdp.id_tabla='9'
    Inner Join tablatipo rol On u.id_rol=rol.id_tipo And rol.id_tabla='TR'
    Inner Join tbl_dependencia dep On u.id_depen=dep.id_depen";
    if (!empty($param[0]['nomComplePer'])) {
      $this->sql .= " And Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When primer_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs Like Upper('%" . $param[0]['nomComplePer'] . "%')";
    }
    $this->sql .= " Order By p.primer_ape";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function post_add_accesousuario($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['idMenuUsu'], $param[0]['idUsu'], $param[0]['audiUsuAccion']);
    $this->sql = "insert into tbl_acceso (id_menu, id_usuario, auditoria_ingreso) Values ($1,$2,$3);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->sql;
  }

  public function post_del_accesousuario($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['idUsu'], $param[0]['idMenuUsu']);
    $this->sql = "Delete From tbl_acceso Where id_usuario=$1 And id_menu=$2;";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs;
  }

  /*     * ****************** Login ********************* */

  public function get_ValidUserLogin($idUsu, $passUsu) {
    $this->db->getConnection();
    $this->sql = "Select u.id_usuario, nom_usuario, u.valid_pass, u.fec_expiracion, p.id_persona, p.nombre_rs, p.primer_ape, p.segundo_ape,
(Select count(1) From tbl_profesionalservicio Where id_usuario=u.id_usuario And estado=1) cant_rol
From tbl_usuario u
Inner Join tbl_persona p On u.id_persona = p.id_persona
    Where upper(nom_usuario)=upper('" . $idUsu . "') And pass_usuario=md5('" . $passUsu ."') And u.estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_RolUserPorDep($idUsu, $idDep = 0) {
    $this->db->getConnection();
    $this->sql = "Select ps.id id_profesionalservicio, ds.id_serviciodep, ps.id_dependencia, d.nom_depen, d.lab_id_tipo_generacorrelativo, s.id_servicio, s.nom_servicio,
pr.id_profesional, p.cod_refprofesion,p.nom_profesion profesion,pr.nro_colegiatura,pr.nro_rne,
ps.id_rol, rl.abrev_rol, rl.nom_rol,
ps.id_rol, rl.abrev_rol, rl.nom_rol, 
(Select count(1) FRom tbl_labproductodepen Where id_dependencia=ps.id_dependencia And chek_es_americana=true And estado=1) envia_americana,
(Select count(1) FRom tbl_labproductodepen Where id_dependencia=ps.id_dependencia And chek_es_diagnostica=true And estado=1) envia_diagnostica
From tbl_profesionalservicio ps
Inner Join tbl_dependencia d On ps.id_dependencia = d.id_dependencia
Inner Join tbl_profesional pr On ps.id_profesional = pr.id_profesional
Inner Join tbl_profesion p On pr.id_profesion=p.id_profesion
Inner Join tbl_rol rl On ps.id_rol = rl.id_rol
Inner Join tbl_serviciodependencia ds On ps.id_serviciodep=ds.id_serviciodep
Inner Join tbl_servicio s On ds.id_servicio=s.id_servicio
Where ps.id_usuario=" . $idUsu . "";
    if($idDep <> 0){
      $this->sql .= " And ps.id_dependencia=" . $idDep . "";
    }
    $this->sql .= " And ps.estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosUsuarioPorId($idUsu) {
      $this->db->getConnection();
      $aparam = array($idUsu);
      $this->sql = "Select u.id_usuario, p.id_persona, p.id_tipodoc, p.nrodoc, u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion,
      u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado From tbl_usuario u
Inner Join tbl_persona p On u.id_persona = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Where u.id_usuario=$1";
      $this->rs = $this->db->query_params($this->sql, $aparam);
      $this->db->closeConnection();
      return $this->rs;
  }

  public function get_listaRol() {
    $this->db->getConnection();
    $this->sql = "Select id_rol, nom_rol From tbl_rol Where estado=1 And id_rol<>1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaRol_admin() {
    $this->db->getConnection();
    $this->sql = "Select id_rol, nom_rol From tbl_rol Where estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaCondicion_laboral() {
    $this->db->getConnection();
    $this->sql = "Select id, abreviatura, nombre From tbl_condicion_laboral Where id_estado_reg=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaProfesion() {
    $this->db->getConnection();
    $this->sql = "Select id_profesion, cod_refprofesion, nom_profesion From tbl_profesion Where estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaCargo() {
    $this->db->getConnection();
    $this->sql = "Select id_cargo, nom_cargo From tbl_cargo Where estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function post_valid_exis_usuario($tipdoc, $nrodoc) {
    $this->db->getConnection();
    $this->sql = "Select count(id_usuario) cnt From tbl_usuario u
    Inner Join tbl_persona p On u.id_persona=p.id_persona Where p.id_tipodoc=" . $tipdoc . " And p.nrodoc='" . $nrodoc . "'";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs[0]['cnt'];
  }

  public function post_reg_usuario($param) {
      $this->db->getConnection();
      $aparam = array($param[0]['accion'], $param[0]['persona'], $param[0]['usuario'], $param[0]['userIngreso']);
      $this->sql = "select sp_reg_usuario($1,$2,$3,$4);";
      $this->rs = $this->db->query_params($this->sql, $aparam);
      $this->db->closeConnection();
      return $this->rs[0][0];
    }

    public function get_tblDatosUsuario($sWhere, $sOrder, $sLimit, $param) {
      $this->db->getConnection();
    if (empty($param[0]['idRol'])) {
		$this->sql = "Select u.id_usuario, p.id_persona id_paciente, p.id_tipodoc id_tipodocpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rsusu,
		u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion, u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado
		From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Where 1=1";
		if (!empty($param[0]['busDoc'])) {
			$this->sql .= " And Upper(p.nrodoc) Like Upper('%" . pg_escape_string($param[0]['busDoc']) . "%')";
		}
		if (!empty($param[0]['busUsuario'])) {
			$this->sql .= " And Upper(u.nom_usuario) Like Upper('%" . pg_escape_string($param[0]['busUsuario']) . "%')";
		}
	} else {
		$this->sql = "Select * From (Select u.id_usuario, p.id_persona id_paciente, p.id_tipodoc id_tipodocpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rsusu,
		u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion, u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado
		From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Inner Join (Select Distinct id_usuario From tbl_profesionalservicio Where id_rol in (6,7,8,9,10) And estado=1) una On u.id_usuario=una.id_usuario
		Union All
		Select u.id_usuario, p.id_persona id_paciente, p.id_tipodoc id_tipodocpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rsusu,
		u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion, u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado
		From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Where u.id_usuario Not in (Select Distinct id_usuario From tbl_profesionalservicio Where estado=1)) u
		Where 1=1";
		if (!empty($param[0]['busDoc'])) {
			$this->sql .= " And Upper(nro_docpac) Like Upper('%" . pg_escape_string($param[0]['busDoc']) . "%')";
		}
		if (!empty($param[0]['busUsuario'])) {
			$this->sql .= " And Upper(nom_usuario) Like Upper('%" . pg_escape_string($param[0]['busUsuario']) . "%')";
		}
	}
      $this->sql .= $sOrder . $sLimit;
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs;
    }

    public function get_tblCountUsuario($sWhere, $param) {
      $this->db->getConnection();
	  if (empty($param[0]['idRol'])) {
		$this->sql = "Select count(*) cnt From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Where 1=1";
		if (!empty($param[0]['busDoc'])) {
			$this->sql .= " And Upper(p.nrodoc) Like Upper('%" . pg_escape_string($param[0]['busDoc']) . "%')";
		}
		if (!empty($param[0]['busUsuario'])) {
			$this->sql .= " And Upper(u.nom_usuario) Like Upper('%" . pg_escape_string($param[0]['busUsuario']) . "%')";
		}
	  } else {
		$this->sql = "Select count(*) cnt From (Select u.id_usuario, p.id_persona id_paciente, p.id_tipodoc id_tipodocpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rsusu,
		u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion, u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado
		From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Inner Join (Select Distinct id_usuario From tbl_profesionalservicio Where id_rol in (6,7,8,9,10) And estado=1) una On u.id_usuario=una.id_usuario
		Union All
		Select u.id_usuario, p.id_persona id_paciente, p.id_tipodoc id_tipodocpac, tdp.abreviatura abrev_tipodocpac, p.nrodoc nro_docpac, Case When p.primer_ape isNull Then '' Else p.primer_ape End||' '||Case When p.segundo_ape isNull Then '' Else p.segundo_ape End ||' '||p.nombre_rs nombre_rsusu,
		u.nom_usuario, to_char(u.fec_expiracion, 'DD/MM/YYYY') fec_expiracion, u.estado, Case When u.estado=1 Then 'ACTIVO'::Varchar Else 'DESACTIVADO'::Varchar End nom_estado
		From tbl_usuario u
		Inner Join tbl_persona p On u.id_persona = p.id_persona
		Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
		Where u.id_usuario Not in (Select Distinct id_usuario From tbl_profesionalservicio Where estado=1)) u
		Where 1=1";
		if (!empty($param[0]['busDoc'])) {
			$this->sql .= " And Upper(nro_docpac) Like Upper('%" . pg_escape_string($param[0]['busDoc']) . "%')";
		}
		if (!empty($param[0]['busUsuario'])) {
			$this->sql .= " And Upper(nom_usuario) Like Upper('%" . pg_escape_string($param[0]['busUsuario']) . "%')";
		}
	  }
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs[0]['cnt'];
    }

}
