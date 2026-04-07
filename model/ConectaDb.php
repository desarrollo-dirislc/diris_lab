<?php

class ConectaDb {

    protected $cn;
    var $rs = null;

    function __construct() {
        $this->srv = '10.0.0.3';
          $this->db = 'pe_diris_slab_test';
          $this->user = 'usr_lab';
          $this->pwd = 'lab@12345';
    }

    public function getConnection() {
        try {
            $this->cn = @pg_connect("host=" . $this->srv . " port=5432 dbname=" . $this->db . " user=" . $this->user . " password=" . $this->pwd);
            if ($this->cn == NULL) {
                echo "Error conection";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $this->cn;
    }

    public function query($sql) {
        $l = array();
        $rs = pg_query($this->cn, $sql);

        while ($obj = pg_fetch_array($rs)) {
            $l[] = $obj;
        }

        $this->rs = $l;
        return $this->rs;
    }
	
    public function query_assoc($sql) {
        $l = array();
        $rs = pg_query($this->cn, $sql);

        while ($obj = pg_fetch_array($rs,null,PGSQL_ASSOC)) {
            $l[] = $obj;
        }

        $this->rs = $l;
        return $this->rs;
    }

    public function queryCRUD($sql) {
        $result = pg_query($this->cn, $sql);
        $rs = pg_affected_rows($result);
        return $rs;
    }

    public function query_params($sql, $aparams) {
        $l = array();
        //pg_set_client_encoding($this->cn, "UTF-8");
        $rs = pg_query_params($this->cn, $sql, $aparams);

        while ($obj = pg_fetch_array($rs)) {
            $l[] = $obj;
        }
        $this->rs = $l;
        return $this->rs;
    }

    public function count_rows() {
        return count($this->rs);
    }

    public function closeConnection() {
        $this->cn . pg_close();
    }

}
