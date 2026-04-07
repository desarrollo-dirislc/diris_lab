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
$labIdServicio = $_SESSION['labIdServicio'];

require_once '../../model/Levey.php';
$at = new Levey();

$aColumns = array('led.fecha', '', '', ''); //Kolom Pada Tabel
$sIndexColumn = '';

$input = & $_POST;

$gaSql['charset'] = 'utf8';
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
    $sOrder = " Order By s.fecha";
}

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

$param[0]['anio'] = $input['anio'];
$param[0]['mes'] = $input['mes'];
$param[0]['id_control_calidad'] = $input['id_control_calidad'];
$param[0]['id_estado_levey_dep'] = '';
$param[0]['id_dependencia'] = $input['id_dependencia'];

//Aqui se manda los parametros de busqueda
$rResult = $at->get_tblDatosLeveyDetalle($sWhere, $sOrder, $sLimit, $param);
//print_r($rResult);
$rResultFilterTotal = 0;
if(isset($rResult[0]["cant_rows"])){$rResultFilterTotal = $rResult[0]["cant_rows"];}

list($iFilteredTotal) = $rResultFilterTotal;
$rResultTotal = $rResultFilterTotal;
list($iTotal) = $rResultTotal;

$output = array(
    //"sEcho"                => intval($input['sEcho']),
    "iTotalRecords" => $rResultFilterTotal,
    "iTotalDisplayRecords" => $rResultFilterTotal,
    "aaData" => array(),
);

// Voy a mostrar la información que tiene que ser igual a las cabecera de la tabla (th)
$correTipoPlanDep = 1;
$nroTipoPlanDep = "";
foreach ($rResult as $aRow) {
    $row = array();

    for ($i = 0; $i < $iColumnCount; $i++) {
        if (isset($aRow[$aColumns[$i]]))
            $row[] = $aRow[$aColumns[$i]];
    }
	$btnJusti = '';
	$btnEdit = '';
	$btnEli = '';
	//$btnEli = ' <a href="#" data-target="#editModal" class="delete" data-toggle="tooltip" data-placement="top" title="Anular" onclick="event.preventDefault();open_edit(\'' . $aRow['id'] . '\',\'' . $aRow['fecha'] . '\',\'E\');"><i class="glyphicon glyphicon-trash"></i></a>';
	if($aRow['id_estado_levey_dep'] <> "3"){
		$btnEdit = ' <a href="#" data-target="#editModal" class="acept" data-toggle="tooltip" data-placement="top" title="Editar" onclick="event.preventDefault();open_edit(\'' . $aRow['id'] . '\',\'' . $aRow['fecha'] . '\',\'' . $aRow['valor_fecha'] . '\',\'' . $input['id_control_calidad'] . '\');"><i class="glyphicon glyphicon-pencil"></i></a>';	
	}
	if($aRow['justificacion'] <> ""){
		$justificacion = str_replace(PHP_EOL, '<p>', $aRow['justificacion']);
		//$btnJusti = ' <a href="#" data-target="#editModal" class="detail" data-toggle="tooltip" data-placement="top" title="Editar" onclick="event.preventDefault();justificacion(\'' . $justificacion. '\');"><i class="glyphicon glyphicon-eye-open"></i></a>';
		$btnJusti = ' <a href="#" data-target="#editModal" class="warning" data-toggle="tooltip" data-placement="top" title="Detalle registro" onclick="event.preventDefault();open_detalle(\'' . $aRow['id']. '\',\'' . $aRow['fecha'] . '\');"><i class="glyphicon glyphicon-eye-open"></i></a>';
	} else {
		$btnJusti = ' <a href="#" data-target="#editModal" class="detail" data-toggle="tooltip" data-placement="top" title="Detalle registro" onclick="event.preventDefault();open_detalle(\'' . $aRow['id']. '\',\'' . $aRow['fecha'] . '\');"><i class="glyphicon glyphicon-eye-open"></i></a>';	
	}
	
    $row = array($aRow['dia'], $aRow['nro_lote'], $aRow['valor_fecha'], $aRow['z_socre'], $btnEdit.$btnEli.$btnJusti);
    $output['aaData'][] = $row;
}
echo json_encode($output);
?>
