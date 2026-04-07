<?php

include_once 'ConectaDb.php';

class UnidadMedida {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

  public function get_listaUnidadMedida() {
    $this->db->getConnection();
    $this->sql = "Select id_unimedida, descrip_unimedida From tbl_unimedida Where estado=1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

}
