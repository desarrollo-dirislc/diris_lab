<?php
ini_set('memory_limit', '2048M');
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

/*foreach ($rsTP as $rowTP) {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $max, $rowTP['nombre_tipo_producto']);
}*/

if(isset($_GET['anio'])) {
	$csv_file = "ses_lab_csv" . $_GET['anio'] . $_GET['mes']. "_" . date('Ymd') . ".csv";
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=" . $csv_file . "");
	$fh = fopen('php://output', 'w');
	$delimiter = ",";
	$is_coloumn = true;
	$rs = $ses->get_ReportExportCsvPorAnioyMes($_GET['anio'], $_GET['mes']);
	if(!empty($rs)) {
		foreach($rs as $row) {
			if($is_coloumn) {
				//fputcsv($fh, array_keys($row));
				$cabecera = array('id_ris', 'nom_establecimiento', 'anio_informe', 'mes_informe', 'nombre_tipo_producto', 'nom_producto', 'tipo', 'cnt_examen');
				fputcsv($fh, $cabecera, $delimiter);
				$is_coloumn = false;
			}
			$data = array($row['id_ris'], $row['nom_depen'], $row['anio_informe'], $row['mes_informe'], $row['nombre_tipo_producto'], $row['nom_producto'], $row['tipo'], $row['cnt_examen']);
			//fputcsv($fh, array_values($row));
			fputcsv($fh, $data, $delimiter);
		}
		fclose($fh);
	}
	exit;
}