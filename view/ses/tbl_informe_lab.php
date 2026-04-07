<?php
session_start();

if (!isset($_SESSION["labAccess"])) {
  header("location:../index.php");
  exit();
}
if ($_SESSION["labAccess"] <> "Yes") {
  header("location:../index.php");
  exit();
}
$labIdUser = $_SESSION['labIdUser'];
$labIdDepUser = $_SESSION['labIdDepUser'];

require_once '../../model/Ses.php';
$ses = new Ses();

$aColumns = array('d.nom_depen', '', 'inf.mes_informe', '', 'inf.cnt_total_exa_lab', '', 'cnt_total_exa_bac', ''); //Kolom Pada Tabel
// Indexed column (used for fast and accurate table cardinality)
$sIndexColumn = 'inf.mes_informe';

// DB table to use
//$sTable = 'tbl_equipos'; // Nama Tabel
// Database connection information
//$gaSql['port']     = 5433; // 3306 is the default MySQL port
// Input method (use $_GET, $_POST or $_REQUEST)
$input = & $_POST;

$gaSql['charset'] = 'utf8';

/**
 * MySQL connection
 */
//$db = pg_connect($gaSql['server'], $gaSql['port'],$gaSql['db'] ,$gaSql['user'], $gaSql['password']);

/* if (!$db->set_charset($gaSql['charset'])) {
  die( 'Error loading character set "'.$gaSql['charset'].'": '.$db->error );
  }
 */

/**
 * Paging
 */
$sLimit = "";
if (isset($input['iDisplayStart']) && $input['iDisplayLength'] != '-1') {
    $sLimit = " LIMIT " . intval($input['iDisplayLength']) . " OFFSET " . intval($input['iDisplayStart']);
}


/**
 * Ordering
 */
$aOrderingRules = array();
if (isset($input['iSortCol_0'])) {
    $iSortingCols = intval($input['iSortingCols']);
    for ($i = 0; $i < $iSortingCols; $i++) {
        if ($input['bSortable_' . intval($input['iSortCol_' . $i])] == 'true') {
            $aOrderingRules[] = "" . $aColumns[intval($input['iSortCol_' . $i])] . " "
                    . ($input['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc');
        }
    }
}

if (!empty($aOrderingRules)) {
    $sOrder = " ORDER BY " . implode(", ", $aOrderingRules);
} else {
    $sOrder = " Orde By la.create_resul";
}


/**
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$iColumnCount = count($aColumns);
if (isset($input['sSearch']) && $input['sSearch'] != "") {
    $aFilteringRules = array();
    for ($i = 0; $i < $iColumnCount; $i++) {
        if (isset($input['bSearchable_' . $i]) && $input['bSearchable_' . $i] == 'true') {
            $aFilteringRules[] = "" . $aColumns[$i] . " LIKE '%" . $input['sSearch'] . "%'";
        }
    }
    if (!empty($aFilteringRules)) {
        $aFilteringRules = array('(' . implode(" OR ", $aFilteringRules) . ')');
    }
}

// Individual column filtering
for ($i = 0; $i < $iColumnCount; $i++) {
    if (isset($input['bSearchable_' . $i]) && $input['bSearchable_' . $i] == 'true' && $input['sSearch_' . $i] != '') {
        $aFilteringRules[] = "" . $aColumns[$i] . " LIKE '%" . mb_strtoupper(pg_escape_string($input['sSearch_' . $i]), 'UTF-8') . "%'";
    }
}

if (!empty($aFilteringRules)) {
    $sWhere = "
		and  " . implode(" AND ", $aFilteringRules);
} else {
    $sWhere = "
		";
}


/**
 * SQL queries
 * Get data to display
 */
$aQueryColumns = array();
foreach ($aColumns as $col) {
    if ($col != ' ') {
        $aQueryColumns[] = $col;
    }
}

$param[0]['tipo_repor'] = $input['tipo_repor'];
$param[0]['mes'] = $input['mes'];
$param[0]['anio'] = $input['anio'];
$param[0]['id_dependencia'] = $input['id_dependencia'];

//print_r($param);
//Aqui se manda los parametros de busqueda
$rResult = $ses->get_tblDatosInformeSES($sWhere, $sOrder, $sLimit, $param);
//print_r($rResult);
$rResultFilterTotal = 0;
if(isset($rResult[0]["cant_rows"])){$rResultFilterTotal = $rResult[0]["cant_rows"];}

list($iFilteredTotal) = $rResultFilterTotal;
$rResultTotal = $rResultFilterTotal;
list($iTotal) = $rResultTotal;


/**
 * Output
 */
$output = array(
    //"sEcho"                => intval($input['sEcho']),
    "iTotalRecords" => $rResultFilterTotal,
    "iTotalDisplayRecords" => $rResultFilterTotal,
    "aaData" => array(),
);

// Voy a mostrar la informaci�n que tiene que ser igual a las cabecera de la tabla (th)

$meses_arr = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Setiembre","Octubre","Noviembre","Diciembre"];

foreach ($rResult as $aRow) {
    $row = array();

    for ($i = 0; $i < $iColumnCount; $i++) {
        if (isset($aRow[$aColumns[$i]]))
            $row[] = $aRow[$aColumns[$i]];
    }
	$btnEdit = '';
	if ($aRow['es_bloqueado'] == "SI"){
		//if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "15") Or ($_SESSION['labIdRolUser'] == "19")){
		if(($_SESSION['labIdRolUser'] == "1") Or ($_SESSION['labIdRolUser'] == "2") Or ($_SESSION['labIdRolUser'] == "19")){ //admin sistem --admin lab -- admin est
			$btnEdit = '<button class="btn btn-success btn-xs" onclick="edit_registro(\'' . $aRow['id'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
		}
	} else {
		if (($_SESSION['labIdRolUser'] == "3") Or ($_SESSION['labIdRolUser'] == "4") Or ($_SESSION['labIdRolUser'] == "5") Or ($_SESSION['labIdRolUser'] == "14")){ //admision lab -- resul lab -- res lab eess --admision lab y resul lab
			$btnEdit = '';
		} else {
			$btnEdit = '<button class="btn btn-success btn-xs" onclick="edit_registro(\'' . $aRow['id'] . '\');"><i class="glyphicon glyphicon-pencil"></i></button>';
		}
	}

    $row = array($aRow['nom_dependencia'], $aRow['anio_informe'], $meses_arr[$aRow['mes_informe']], number_format($aRow['cnt_total_ate_lab']), number_format($aRow['cnt_total_exa_lab']), number_format($aRow['cnt_total_ate_bac']), number_format($aRow['cnt_total_exa_bac']), $aRow['fecha_registro'],$btnEdit);
    $output['aaData'][] = $row;
}
echo json_encode($output);
?>