<?php

session_start();
if (!isset($_SESSION["labAccess"])) {
  header("location:../../");
  exit();
}
if ($_SESSION["labAccess"] <> "Yes") {
  header("location:../../");
  exit();
}
if(!isset($_SESSION["labAccesoMenuUsu"])){
	header("location:../../");
	exit();
}

$labIdUser = $_SESSION['labIdUser'];
$labNomUser = $_SESSION['labNomUser'];
$labNomPer = $_SESSION['labNomPer'];
$labApePatPer = $_SESSION['labApePatPer'];
$labApeMatPer = $_SESSION['labApeMatPer'];
$labCantRol = $_SESSION['labCantRol'];
$labIdRolUser = $_SESSION['labIdRolUser'];
$labAccesoMenuUsu = $_SESSION['labAccesoMenuUsu'];
$labIdDepUser = $_SESSION['labIdDepUser'];
$labIdServicioDep = $_SESSION['labIdServicioDep'];
$labIdServicio = $_SESSION['labIdServicio'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximugm-scale=1, user-scalable=no" name="viewport">
  <title>DIRIS - SRALAB</title>
  <link rel="icon" href="../../iconlab.png" sizes="32x34">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../assets/css/adminlte/2.3.2/AdminLTE.min.css" type="text/css"/>
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../assets/css/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../assets/font-awesome/css/font-awesome.min.css" type="text/css"/>
  <!-- Select2 -->
  <link rel="stylesheet" href="../../assets/plugins/select2/select2.min.css" type="text/css"/>
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="../../assets/plugins/datepicker/datepicker3.css" type="text/css"/>
  <!-- CSS Core Style -->
  <link rel="stylesheet" href="../../assets/css/style.css" type="text/css"/>
  <!-- datatable -->
  <link rel="stylesheet" href="../../assets/plugins/datatables/dataTables.bootstrap.css" type="text/css"/>
  <link rel="stylesheet" href="../../assets/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" type="text/css"/>
  <script type="text/javascript" language="javascript" src="../../assets/plugins/datatables/jquery.js"></script>
  <!-- jQuery UI -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
  <script type="text/javascript" language="javascript" src="../../assets/plugins/datatables/jquery.dataTables.js"></script>
  <script type="text/javascript" language="javascript" src="../../assets/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
  <script type="text/javascript" language="javascript" src="../../assets/plugins/datatables/dataTables.bootstrap.js"></script>
  <script type="text/javascript" src="../../assets/plugins/datatables/datatables.min.js"></script>
  <script type="text/javascript" src="../../assets/plugins/datatables/dataTables.checkboxes.min.js"></script>
  <script type="text/javascript" src="../../assets/plugins/datatables/dataTables.buttons.min.js"></script>

<!-- bootstrap datetimepicker-->
<script type="text/javascript" language="javascript" src="../../assets/plugins/datetimepicker/moments.js"></script>
<link rel="stylesheet" href="../../assets/plugins/datetimepicker/bootstrap-datetimepicker.css" type="text/css"/>
<script type="text/javascript" language="javascript" src="../../assets/plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
<!-- dropzone -->
<!--<link rel="stylesheet" href="../../assets/plugins/dropzone/dropzone.css" type="text/css"/>-->
<!-- dropzone -->
<link rel="stylesheet" href="../../assets/css/toastr/toastr.min.css" type="text/css"/>
<!-- tableHeadFixer -->
<script type="text/javascript" src="../../assets/plugins/tableHeadFixer/tableHeadFixer.js"></script>

  <style>
  .font-weit {
    font-weight: bold;
  }
  .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding-top: 5px;
    padding-bottom: 5px;
  }

  .login-box-body {
    /*background: rgba(255, 255, 255, 0.8) none repeat scroll 0 0;*/
    border-radius: 4px;
    bottom: -8px;
    content: "";
    left: -8px;
    right: -8px;
    top: -8px;
    z-index: -1;
  }
  .img-responsive {
    margin: 0 auto;
  }

  .sel-cursor {
    cursor: pointer;
  }
  </style>
</head>
<body>
