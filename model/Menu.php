<?php

include_once 'ConectaDb.php';

class Menu {

    private $db;
    private $sql;

    public function __construct() {
        $this->db = new ConectaDb();
        $this->rs = array();
    }

    public function listaMenuActivoPorRol($idRol) {
        $this->db->getConnection();
        $this->sql = "Select m.id_menu From tbl_menu m Where id_group_rol like '%" . $idRol . "%';";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function get_DetAccesoUser($idUsuDep) {
        $this->db->getConnection();
        $this->sql = "Select id_menu, nom_detmenu, link_detmenu From tbl_usuarioacceso a
Inner Join tbl_menudetalle m On a.id_detmenu = m.id_detmenu
Where a.id_profesionalservicio=" . $idUsuDep . " And a.estado=1 And m.estado=1 And m.id_modulo isNull Order By id_menu, order_detmenu";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function listaMenuPrincipal() {
        $this->db->getConnection();
        $this->sql = "Select Distinct pad_name From tbl_menu Order By pad_name";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function get_repDatosAccesoPorUsuario($param) {
        $this->db->getConnection();
        $this->sql = "Select a.id_usuario, a.id_menu, m.pad_name, m.bar_name, bar_link from tbl_acceso a inner join tbl_menu m on a.id_menu=m.id_menu
Where id_usuario=" . $param[0]['idUsu'] . "";
        if (!empty($param[0]['idMenuUsu'])) {
            $this->sql .= " And m.pad_name='" . $param[0]['idMenuUsu'] . "'";
        }
        $this->sql .= " Order By id_menu,bar_name";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function get_repDatosAccesoNoIncluUsuario($param) {
        $this->db->getConnection();
        $this->sql = "Select m.id_menu, m.pad_name, m.bar_name, m.bar_link  From tbl_menu m
Where id_menu Not In (Select id_menu From tbl_acceso Where id_usuario=" . $param[0]['idUsu'] . ")";
        if (!empty($param[0]['idMenuUsu'])) {
            $this->sql .= " And m.pad_name='" . $param[0]['idMenuUsu'] . "'";
        }
        $this->sql .= " Order By id_menu,bar_name";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

    /* =========================================================
     *  ACCESOS x USUARIO — Métodos de mantenimiento
     * ========================================================= */

    public function get_buscarProfServParaAcceso($busqueda) {
        $this->db->getConnection();
        $aparam = array('%' . $busqueda . '%');
        $this->sql = "Select tpros.id AS id_profesionalservicio,
            tpros.id_dependencia, d.nom_depen,
            tdp.abreviatura AS abrev_tipodoc, tp.nrodoc,
            Coalesce(tp.primer_ape,'') || ' ' || Coalesce(tp.segundo_ape,'') || ' ' || tp.nombre_rs AS nombre_completo,
            u.nom_usuario, rl.nom_rol
        From public.tbl_profesionalservicio tpros
        Inner Join public.tbl_profesional   tpro On tpros.id_profesional = tpro.id_profesional
        Inner Join public.tbl_persona       tp   On tpro.id_persona      = tp.id_persona
        Inner Join public.tbl_tipodoc       tdp  On tp.id_tipodoc        = tdp.id_tipodoc
        Inner Join public.tbl_dependencia   d    On tpros.id_dependencia = d.id_dependencia
        Inner Join public.tbl_rol           rl   On tpros.id_rol         = rl.id_rol
        Left  Join public.tbl_usuario       u    On tp.id_persona        = u.id_persona
        Where tpros.estado = 1
          And (tp.nrodoc ILike $1 Or tp.primer_ape ILike $1
               Or tp.nombre_rs ILike $1 Or u.nom_usuario ILike $1)
        Order By d.nom_depen, tp.primer_ape
        Limit 30";
        $this->rs = $this->db->query_params($this->sql, $aparam);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function get_accesosAsignados($id_profesionalservicio, $id_menu) {
        $this->db->getConnection();
        $aparam = array($id_profesionalservicio, $id_menu);
        $this->sql = "Select ua.id_detmenu, md.nom_detmenu, md.id_modulo
        From tbl_usuarioacceso ua
        Inner Join tbl_menudetalle md On ua.id_detmenu = md.id_detmenu
        Where ua.id_profesionalservicio = $1
          And ua.estado = 1
          And md.estado = 1
          And md.id_menu = $2
        Order By md.id_modulo Nulls First, md.order_detmenu";
        $this->rs = $this->db->query_params($this->sql, $aparam);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function get_accesosDisponibles($id_profesionalservicio, $id_menu) {
        $this->db->getConnection();
        $aparam = array($id_profesionalservicio, $id_menu);
        $this->sql = "Select md.id_detmenu, md.nom_detmenu, md.id_modulo
        From tbl_menudetalle md
        Where md.estado = 1
          And md.id_menu = $2
          And md.id_detmenu Not In (
              Select ua.id_detmenu From tbl_usuarioacceso ua
              Where ua.id_profesionalservicio = $1 And ua.estado = 1
          )
        Order By md.id_modulo Nulls First, md.order_detmenu";
        $this->rs = $this->db->query_params($this->sql, $aparam);
        $this->db->closeConnection();
        return $this->rs;
    }

    public function post_add_accesoUsuario($id_profserv, $id_detmenu, $user_create) {
        $this->db->getConnection();
        $aparam = array($id_profserv, $id_detmenu);
        $this->sql = "Select id_profesionalservicio From tbl_usuarioacceso
        Where id_profesionalservicio=$1 And id_detmenu=$2";
        $existe = $this->db->query_params($this->sql, $aparam);
        if (count($existe) > 0) {
            $aparam2 = array($id_profserv, $id_detmenu, $user_create);
            $this->sql = "Update tbl_usuarioacceso Set estado=1, user_create_up=$3, create_up=now()
            Where id_profesionalservicio=$1 And id_detmenu=$2";
        } else {
            $aparam2 = array($id_profserv, $id_detmenu, $user_create);
            $this->sql = "Insert Into tbl_usuarioacceso (id_profesionalservicio, id_detmenu, estado, user_create_at, create_at)
            Values ($1, $2, 1, $3, now())";
        }
        $this->db->query_params($this->sql, $aparam2);
        $this->db->closeConnection();
        return "OK";
    }

    public function post_del_accesoUsuario($id_profserv, $id_detmenu, $user_create) {
        $this->db->getConnection();
        $aparam = array($id_profserv, $id_detmenu, $user_create);
        $this->sql = "Update tbl_usuarioacceso Set estado=0, user_create_up=$3, create_up=now()
        Where id_profesionalservicio=$1 And id_detmenu=$2";
        $this->db->query_params($this->sql, $aparam);
        $this->db->closeConnection();
        return "OK";
    }

}
