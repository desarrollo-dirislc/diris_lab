<?php

include_once 'ConectaDb.php';

class PerfilLab {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

	public function post_crud_perfillab($param) {
        $this->db->getConnection();
        $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['datos'], $param[0]['userIngreso']);
        $this->sql = "select sp_crud_perfillab($1,$2,$3,$4);";
        $this->rs = $this->db->query_params($this->sql, $aparam);
        $this->db->closeConnection();
        return $this->rs[0][0];
	}

	public function get_listaPerfilLab() {
		$this->db->getConnection();
		$this->sql = "SELECT id, nombre_perfil FROM tbl_labperfil Where estado=1;";
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}

	public function get_datosComponenteporPerfil($id_perfil) {
		$this->db->getConnection();
		$this->sql = "Select perprod.id, per.nombre_perfil, prod.nom_producto From public.tbl_labperfilxproducto perprod
Inner Join public.tbl_labperfil per On perprod.id_perfillab=per.id
Inner Join public.tbl_producto prod On perprod.id_producto=prod.id_producto
Where per.id=" . $id_perfil . " And perprod.estado=1";
		$this->rs = $this->db->query($this->sql);
		$this->db->closeConnection();
		return $this->rs;
	}
  
	public function get_tblDatosProducto($sWhere, $sOrder, $sLimit, $param) {
      $this->db->getConnection();
      $this->sql = "Select id_producto id, nom_producto From public.tbl_producto Where estado=1";
		if (!empty($param[0]['nombre'])) {
		  $this->sql .= " And nom_producto ilike '%" . $param[0]['nombre'] . "%'";
		}
      $this->sql .= $sWhere. $sOrder . $sLimit;
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs;
    }

    public function get_tblCountProducto($sWhere, $param) {
      $this->db->getConnection();
      $this->sql = "Select count(*) cnt From public.tbl_producto Where estado=1";
		if (!empty($param[0]['nombre'])) {
		  $this->sql .= " And nom_producto ilike '%" . $param[0]['nombre'] . "%'";
		}
      $this->sql .= $sWhere;
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs[0]['cnt'];
    }

}
