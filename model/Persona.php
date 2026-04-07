<?php

include_once 'ConectaDb.php';

class Persona {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }
  
  public function post_reg_persona($param) {
    $this->db->getConnection();
    $aparam = array($param[0]['accion'], $param[0]['id'], $param[0]['id_atencion'], $param[0]['datos'], $param[0]['userIngreso']);
    $this->sql = "select sp_crud_persona($1,$2,$3,$4,$5);";
    $this->rs = $this->db->query_params($this->sql, $aparam);
    $this->db->closeConnection();
    return $this->rs[0][0];
  }

  public function get_datosDetallePersona($optRep, $tipDocPer, $nroDoc) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_personadetalle('" . $optRep ."', '" . $tipDocPer . "', '" . $nroDoc . "', 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosDetallePersonaUltimaAtencionPorIdDed($optRep, $tipDocPer, $nroDoc, $idDep) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_personaultimaatencionpordependencia('" . $optRep ."', '" . $tipDocPer . "', '" . $nroDoc . "', '" . $idDep . "', 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosDetallePersonaPadron($nroDoc) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_consult_personapadron('" . $nroDoc . "', 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_datosDetalleDireccionConViaPorDatosPersona($optRep, $tipDocPer, $nroDoc) {
    $conet = $this->db->getConnection();
    $this->sql = "Select * From sp_show_direccionconviaporpersona('" . $optRep ."', '" . $tipDocPer . "', '" . $nroDoc . "', 'ref_cursor'); Fetch All In ref_cursor;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_ultimaAtencionConResultadoPorPersona($idPersona, $idDep) {
    $conet = $this->db->getConnection();
    $this->sql = "SELECT
                    MAX(fec_atencion) as fecha_ultima_atencion,
                    CURRENT_DATE - MAX(fec_atencion)::date as dias_desde_ultima_atencion,
                    COUNT(*) as tiene_resultado
                  FROM lab.tbl_labatencion
                  WHERE id_paciente = '" . $idPersona . "'
                    AND id_dependencia = '" . $idDep . "'
                    AND id_estado_resul = 4";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
}
