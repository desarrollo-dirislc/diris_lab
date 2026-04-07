<?php

include_once 'ConectaDb.php';

class Tipo {

  private $db;
  private $sql;

  public function __construct() {
    $this->db = new ConectaDb();
    $this->rs = array();
  }

  public function get_listaTipoDocPerNatu() {
    $this->db->getConnection();
    $this->sql = "Select id_tipodoc, abreviatura, descripcion From tbl_tipodoc Where estado=1 And (id_cattipodoc=1 Or id_cattipodoc=3) Order By id_tipodoc";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  public function get_listaTipoDocPerNatuConDoc() {
    $this->db->getConnection();
    $this->sql = "Select id_tipodoc, abreviatura, descripcion From tbl_tipodoc Where estado=1 And id_cattipodoc=1 Order By id_tipodoc";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaTipoDocPerNatuConDocAdulto() {
    $this->db->getConnection();
    $this->sql = "Select id_tipodoc, abreviatura, descripcion From tbl_tipodoc Where estado=1 And id_cattipodoc=1 And id_tipodoc<>5 Order By id_tipodoc";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaTipoDocPerNatuSinDocAndConDocAdulto() {
    $this->db->getConnection();
    $this->sql = "Select id_tipodoc, abreviatura, descripcion From tbl_tipodoc Where estado=1 And (id_cattipodoc=1 Or id_cattipodoc=3) And id_tipodoc<>5 Order By id_tipodoc";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }


    public function get_listaTipoDocPerNatuSinDocAndConDocMenor() {
      $this->db->getConnection();
      $this->sql = "Select id_tipodoc, abreviatura, descripcion From tbl_tipodoc Where estado=1 And (id_tipodoc=1 Or id_tipodoc=7 Or id_tipodoc=8) Order By id_tipodoc";
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs;
    }

  public function selecina_tablatipo($id_tbl, $est, $ord) {
    $this->db->getConnection();
    $this->sql = "SELECT id_tabla, id_tipo, descripcion, val_abr, id_categoria FROM tablatipo WHERE id_tabla='" . $id_tbl . "'";
    if (!empty($ord)) {
      $this->sql.=" And idest_tablatipo='" . $est . "'";
    }
    if (!empty($ord)) {
      $this->sql.=" ORDER BY " . $ord . "";
    }
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
    //return $this->sql;
  }

  public function selecina_tablatipo_id($id_tabla, $est, $id) {
    $this->db->getConnection();
    $this->sql = "SELECT id_tabla, id_tipo, descripcion, val_abr, id_categoria FROM tablatipo WHERE id_tabla='" . $id_tabla . "' And id_tipo='" . $id . "'";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
    //return $this->sql;
  }

  public function selecina_tablatipo_valabr($id_tabla, $est, $valabr) {
    $this->db->getConnection();
    $this->sql = "SELECT id_tabla, id_tipo, descripcion, val_abr, id_categoria FROM tablatipo WHERE id_tabla='" . $id_tabla . "' And val_abr='" . $valabr . "'";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    //return $this->rs;
    return $this->sql;
  }

  public function selecina_tablatipo_categoria($id_tbl, $id_cat, $est, $ord) {
    $this->db->getConnection();
    $this->sql = "SELECT id_tabla, id_tipo, descripcion, val_abr, id_categoria FROM tablatipo WHERE id_tabla='" . $id_tbl . "' and id_categoria in (" . $id_cat . ")";
    if (!empty($ord)) {
      $this->sql.=" ORDER BY " . $ord . "";
    }
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
    //return $this->sql;
  }

  public function selecina_tipo_dh() {
    $this->db->getConnection();
    $this->sql = "SELECT id_tabla, id_tipo, descripcion, val_abr, id_categoria FROM tablatipo WHERE id_tabla='86' and id_tipo<>'1'";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function selecina_parentesco() {
    $this->db->getConnection();
    $this->sql = "select id_tipo,descripcion, id_categoria from tablatipo where id_tabla='86' and id_tipo in('3','2','5','6','7','1')  order by 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaParentesco() {
    $this->db->getConnection();
    $this->sql = "Select id_parentesco, nom_parentesco From tbl_parentesco Where estado=1 Order By nom_parentesco";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaFinanciador() {
    $this->db->getConnection();
    $this->sql = "Select id, nom_financiador From tbl_financiador Where id_estadoreg=1 Order By nom_financiador";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaFinanciadorSinSIS() {
    $this->db->getConnection();
    $this->sql = "Select id, nom_financiador From tbl_financiador Where id_estadoreg=1 And id<>2 Order By nom_financiador";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  
  public function get_listaCie10PAP() {
    $this->db->getConnection();
    $this->sql = "Select id_cie, nom_cie From tbl_cie10 Where id_estadoreg=1 Order By nom_cie";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaTipoSeguroSis() {
    $this->db->getConnection();
    $this->sql = "Select id_codigoasegurado, nom_codasegurado From tbl_codigoasegurado Where estado=1 Order By nom_codasegurado";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaTipoVia() {
    $this->db->getConnection();
    $this->sql = "Select id_tipovia, abrev_tipovia, nom_tipovia From tbl_direciontipovia Where estado=1 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaTipoPoblacion() {
    $this->db->getConnection();
    $this->sql = "Select id_tipopoblacion, abrev_tipopoblacion, nom_tipopoblacion From tbl_direciontipopoblacion Where estado=1 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_listaEtnia() {
    $this->db->getConnection();
    $this->sql = "Select id_etnia, nom_etnia From tbl_etnia Where id_estadoreg=1 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaBACTipoMuestra() {
    $this->db->getConnection();
    $this->sql = "Select id_tipo id, cod_referencial, abrev_tipo abreviatura, descr_tipo tipo From tbl_bacteriologia_tablatipo Where id_estadoreg=1 And id_tabla=1 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaBACTipoExamen() {
    $this->db->getConnection();
    $this->sql = "Select id_tipo id, cod_referencial, abrev_tipo abreviatura, descr_tipo tipo From tbl_bacteriologia_tablatipo Where id_estadoreg=1 And id_tabla=6 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaBACPruebaRapida() {
    $this->db->getConnection();
    $this->sql = "Select id_tipo id, cod_referencial, abrev_tipo abreviatura, descr_tipo tipo From tbl_bacteriologia_tablatipo Where id_estadoreg=1 And id_tabla=7 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  public function get_listaBACPruebaConvencional() {
    $this->db->getConnection();
    $this->sql = "Select id_tipo id, cod_referencial, abrev_tipo abreviatura, descr_tipo tipo From tbl_bacteriologia_tablatipo Where id_estadoreg=1 And id_tabla=8 Order By 1";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
  
  
  public function get_listaCodPrestacional($id_aplicativo) {
    $this->db->getConnection();
    $this->sql = "Select id, codigo_referencial, nombre From public.tbl_codigo_prestacional Where id_aplicativo=" . $id_aplicativo . " And estado_reg=1 Order By codigo_referencial";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

    public function get_listaTipoNotificacion() {
      $this->db->getConnection();
      $this->sql = "Select id_tiponotif, nom_tiponotif From tbl_tiponotificicacion Where estado=1 Order By nom_tiponotif";
      $this->rs = $this->db->query($this->sql);
      $this->db->closeConnection();
      return $this->rs;
    }

    public function get_listaRx() {
        $conet = $this->db->getConnection();
        $this->sql = "Select id, nom_tiporx From tbl_rxtipoexamen Where estado=1";
        $this->sql .= " Order By nom_tiporx";
        $this->rs = $this->db->query($this->sql);
        $this->db->closeConnection();
        return $this->rs;
    }

  function function_calculaEdad($fecha_ini, $fecha_fin){
    date_default_timezone_set('America/Lima');
    //rearmar la fecha
    $f1=explode("/", $fecha_ini);
    $fecha_ini=$f1[2]."-".$f1[1]."-".$f1[0];

    //separamos en partes las fechas
    $array_fecini = explode("-", $fecha_ini);
    $array_fecfin = explode("/", $fecha_fin);


    $anos =  $array_fecfin[2] - $array_fecini[0]; // calculamos años
    $meses = $array_fecfin[1] - $array_fecini[1]; // calculamos meses
    $dias =  $array_fecfin[0] - $array_fecini[2]; // calculamos días

    //ajuste de posible negativo en $días
    if ($dias < 0){
      --$meses;

      //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
      switch ($array_fecfin[1]) {
        case 1:   $dias_mes_anterior=31; break;
        case 2:   $dias_mes_anterior=31; break;
        case 3:
        //if (bisiesto($array_fecfin[0])){
        if (($array_fecfin[0]%4 == 0 && $array_fecfin[0]%100 != 0) || $array_fecfin[0]%400 == 0){
          $dias_mes_anterior=29; break;
        }else{
          $dias_mes_anterior=28; break;
        }
        case 4:   $dias_mes_anterior=31; break;
        case 5:   $dias_mes_anterior=30; break;
        case 6:   $dias_mes_anterior=31; break;
        case 7:   $dias_mes_anterior=30; break;
        case 8:   $dias_mes_anterior=31; break;
        case 9:   $dias_mes_anterior=31; break;
        case 10:   $dias_mes_anterior=30; break;
        case 11:   $dias_mes_anterior=31; break;
        case 12:   $dias_mes_anterior=30; break;
      }
      $dias=$dias + $dias_mes_anterior;
    }

    //ajuste de posible negativo en $meses
    if ($meses < 0){
      --$anos;
      $meses=$meses + 12;
    }

    $arrayName = array(0 => $anos, 1 => $meses, 2 => $dias);
    return $arrayName;
  }

  public function get_datosfecHoraActual() {
    $conet = $this->db->getConnection();
    $this->sql = "Select to_char(now(), 'dd/mm/yyyy hh12:mi:ss AM') fechora_actual;";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  function nombrar_fecha($fecha){
    $mes=array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $dianom=date("d", strtotime($fecha));
    $mesnom=date("m", strtotime($fecha))*1;
    $anionom=date("Y", strtotime($fecha));
    $cadena=$dianom." de ".$mes[$mesnom]." del ".$anionom;
    return $cadena;
  }

  function nombrar_mes($mes){
    $nommes=array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    return $nommes[$mes];
  }

  public function get_detalleRepDatosResultadoExamenPorFecha($param) {
    $conet = $this->db->getConnection();
    $this->sql = "Select la.nro_atencion, la.anio_atencion, to_char(la.fec_cita, 'dd/mm/yyyy') fec_atencion, ta.sigla_plan, 
Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When segundo_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs nombre_rs, 
tdp.abreviatura abrev_tipodoc, nrodoc, (Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, p.id_sexo, to_char(p.fec_nac, 'dd/mm/yyyy') fecha_nac, date_part('year',age(la.fec_cita, p.fec_nac)) edad, date_part('month',age(la.fec_cita, p.fec_nac)) mes, date_part('day',age(la.fec_cita, p.fec_nac)) dia,
nom_servicio, nombre_medico, pro.nom_producto, to_char(dpro.create_toma, 'dd/mm/yyyy') fecha_toma,
uingresul.nom_usuario usu_ing_resul, to_char(dpro.create_resultado, 'dd/mm/yyyy') fec_ing_resul, 
uvalidresul.nom_usuario usu_valid_resul, to_char(dpro.create_valid, 'dd/mm/yyyy') fec_valid_resul,

comp.descrip_comp componente, um.descrip_unimedida uni_medida, dresul.det_resul det_result, 
dresul.id_metodocomponente, case when dresul.id_metodocomponente is Null Then 'SIN METODO' ELSE met.nombre_metodo END metodocomponente, compmet.id_tipo_val_ref, 
dresul.ord_componente orden_componente, dresul.chk_muestra_metodo,
dresul.idtipo_ingresol, comp.idtipocaracter_ingresul, comp.detcaracter_ingresul, dresul.idseleccion_resul idseleccion_ingresul, comp.descrip_valor valor_ref, 
Case When pgrcomp.chk_muestra_metodo=TRUE Then 'SI'::Varchar Else 'NO'::Varchar End nom_visible,
compmet.descrip_valref_metodo, compmet.chk_muestra_valref_especifico,
lim_inf liminf, lim_sup limsup, descip_valref descripvalref, rsele.nombre nombreseleccion_resul, la.id_tipo_genera_correlativo 

From lab.tbl_labatencion la
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Inner Join tbl_plantarifa ta On la.id_plantarifario = ta.id_plan
Inner Join lab.tbl_labproductoatencion dpro On la.id=dpro.id_atencion
Inner Join tbl_producto pro On dpro.id_producto = pro.id_producto
Inner Join tbl_labtipo_producto tpro On pro.idtipo_producto=tpro.id
Inner Join lab.tbl_labresultadodet dresul On dresul.id_atencion=dpro.id_atencion And dresul.id_producto=dpro.id_producto
Inner Join lab.tbl_producto_grupo_componente pgrcomp On dresul.id_productogrupocomp=pgrcomp.id

Inner Join tbl_componente comp On pgrcomp.id_componente=comp.id_componente
Left Join tbl_unimedida um On comp.id_unimedida = um.id_unimedida
Left Join tbl_componentevalref cvr On dresul.id_compvalref = cvr.id_compvalref
Left Join tbl_componente_seleccionresuldet rsele On dresul.det_resul=rsele.id::Varchar
Left Join lab.tbl_componente_metodo compmet On dresul.id_metodocomponente=compmet.id
Left Join lab.tbl_metodo met On compmet.id_metodo=met.id

Inner Join tbl_usuario uingresul On dpro.user_create_resultado = uingresul.id_usuario
Left Join tbl_servicio ser On la.id_servicioori=ser.id_servicio
Left Join tbl_usuario uvalidresul On dpro.user_create_valid = uvalidresul.id_usuario
Where dpro.id_estado_resul In (3,4) And dpro.id_estado_reg<>0 And dpro.id_dependencia=" . $param[0]['idDepAten'] . "";
	if ($param[0]['tipo_resultado'] == "1") {
		if (!empty($param[0]['fecIniAte'])) {
		  $this->sql .= " And dpro.create_valid::date between '" . $param[0]['fecIniAte'] . "' And '" . $param[0]['fecFinAte'] . "'";
		}
		if (!empty($param[0]['id_usuprofesional'])) {
		  $this->sql .= " And dpro.user_create_valid=" . $param[0]['id_usuprofesional'] . "";
		}
	} else {
		if (!empty($param[0]['fecIniAte'])) {
		  $this->sql .= " And dpro.create_resultado::date between '" . $param[0]['fecIniAte'] . "' And '" . $param[0]['fecFinAte'] . "'";
		}
		if (!empty($param[0]['id_usuprofesional'])) {
		  $this->sql .= " And dpro.user_create_resultado=" . $param[0]['id_usuprofesional'] . "";
		}		
	}
	
	if (isset($param[0]['chk_gestante'])) {
      if ($param[0]['chk_gestante'] <> 99) {
		  if ($param[0]['chk_gestante'] == 1) {
			$this->sql .= " And la.check_gestante=TRUE";
			if ($param[0]['condicion_eg'] <> "") {
				$this->sql .= " And la.edad_gestacional" . $param[0]['condicion_eg'] . $param[0]['nro_eg'];
			}
		  } else {
			  $this->sql .= " And la.check_gestante=FALSE";
		  }
      }
    }
	if (isset($param[0]['condicion_edad'])) {
		if (!empty($param[0]['edad_hasta'])) {
			$this->sql .= " And date_part('year',age(la.fec_atencion, p.fec_nac)) between " . $param[0]['edad_desde'] . " And " . $param[0]['edad_hasta'];
		}
	}
	
    if (!empty($param[0]['id_producto'])) {
      $this->sql .= " And pro.id_producto=".$param[0]['id_producto']."";
    }
	
	if (isset($param[0]['id_tipo_correlativo'])) {
		if($param[0]['id_tipo_correlativo'] == "1"){
			$this->sql .= " Order By la.nro_atencion, la.anio_atencion, dpro.id, dresul.orden_grupo";
		} else {
			$this->sql .= " Order By la.create_at, dpro.id, dresul.orden_grupo";
		}
	} else {
		$this->sql .= " Order By la.nro_atencion, la.anio_atencion, dpro.id, dresul.orden_grupo";
	}
		
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_detalleRepDatosLabReferencialExamenPorFecha($param) {
    $conet = $this->db->getConnection();
    $this->sql = "Select la.id id_atencion, depori.nom_depen nomdepen_origen, la.nro_atencion_manual, to_char(la.fec_atencion, 'dd/mm/yyyy') fec_atencion, ta.sigla_plan, 
Case When primer_ape isNull Then '' Else primer_ape End||' '||Case When segundo_ape isNull Then '' Else segundo_ape End ||' '||nombre_rs nombre_rs, 
tdp.abreviatura abrev_tipodoc, nrodoc, (Select nro_hc From tbl_historialhc Where id_persona=p.id_persona And id_dependencia=la.id_dependencia) nro_hc, p.id_sexo, to_char(p.fec_nac, 'dd/mm/yyyy') fecha_nac, date_part('year',age(la.fec_cita, p.fec_nac)) edad, date_part('month',age(la.fec_cita, p.fec_nac)) mes, date_part('day',age(la.fec_cita, p.fec_nac)) dia,
dpro.id_producto, pro.nom_producto, to_char(dpro.create_toma, 'dd/mm/yyyy') fecha_toma,
uingresul.nom_usuario usu_ing_resul, to_char(dpro.create_resultado, 'dd/mm/yyyy') fec_ing_resul, 
uvalidresul.nom_usuario usu_valid_resul, to_char(dpro.create_valid, 'dd/mm/yyyy') fec_valid_resul,
uregresul.nom_usuario usu_create_resul, to_char(resul.create_resul, 'dd/mm/yyyy') fec_create_resul

From lab.tbl_labatencion la
Inner Join tbl_persona p On la.id_paciente = p.id_persona
Inner Join tbl_tipodoc tdp On p.id_tipodoc = tdp.id_tipodoc
Inner Join tbl_plantarifa ta On la.id_plantarifario = ta.id_plan
Inner Join lab.tbl_labproductoatencion dpro On la.id=dpro.id_atencion
Inner Join tbl_producto pro On dpro.id_producto = pro.id_producto

Inner Join lab.tbl_labresultado resul On la.id = resul.id_atencion
Inner Join tbl_usuario uregresul On resul.user_create_resul = uregresul.id_usuario 
Inner Join tbl_usuario uingresul On dpro.user_create_resultado = uingresul.id_usuario
Inner Join tbl_usuario uvalidresul On dpro.user_create_valid = uvalidresul.id_usuario
Left Join tbl_dependencia depori On la.id_depenori = depori.id_dependencia
Where la.id_dependencia=67 And la.id_estado_reg<>0
And dpro.id_producto in (80,92,50,83) And dpro.id_estado_resul=4 And dpro.id_estado_reg<>0";
	if (!empty($param[0]['fecIniAte'])) {
		$this->sql .= " And dpro.create_valid::date between '" . $param[0]['fecIniAte'] . "' And '" . $param[0]['fecFinAte'] . "'";
	}
    $this->sql .= " Order By dpro.create_valid::date";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }

  public function get_detalleRepDatosLabReferencialExamenPorFechaDet($id_atencion, $id_producto) {
    $conet = $this->db->getConnection();
    $this->sql = "Select comp.descrip_comp componente, um.descrip_unimedida uni_medida, dresul.idtipo_ingresol, dresul.det_resul det_result, rsele.nombre nombreseleccion_resul, 
dresul.id_metodocomponente, case when dresul.id_metodocomponente is Null Then 'SIN METODO' ELSE met.nombre_metodo END metodocomponente, compmet.id_tipo_val_ref, 
dresul.ord_componente orden_componente, dresul.idseleccion_resul idseleccion_ingresul, comp.descrip_valor valor_ref, 
compmet.descrip_valref_metodo, compmet.chk_muestra_valref_especifico,
lim_inf liminf, lim_sup limsup, descip_valref descripvalref  
From  lab.tbl_labresultadodet dresul 
Inner Join lab.tbl_producto_grupo_componente pgrcomp On dresul.id_productogrupocomp=pgrcomp.id

Inner Join tbl_componente comp On pgrcomp.id_componente=comp.id_componente
Left Join tbl_unimedida um On comp.id_unimedida = um.id_unimedida
Left Join tbl_componentevalref cvr On dresul.id_compvalref = cvr.id_compvalref
Left Join tbl_componente_seleccionresuldet rsele On dresul.det_resul=rsele.id::Varchar
Left Join lab.tbl_componente_metodo compmet On dresul.id_metodocomponente=compmet.id
Left Join lab.tbl_metodo met On compmet.id_metodo=met.id
Where dresul.id_atencion=" . $id_atencion . " And dresul.id_producto=" . $id_producto . " And dresul.estado=1";
    $this->sql .= " Order By dresul.orden_grupo, dresul.ord_componente";
    $this->rs = $this->db->query($this->sql);
    $this->db->closeConnection();
    return $this->rs;
  }
}
