<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// -------------------- CONEXION --------------------
$host = '10.0.0.3';
$db   = 'pe_diris_slab_dev';
$user = 'usr_lab';
$pass = 'lab@12345';
$port = '5432';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// -------------------- RECIBIR ARCHIVO EXCEL --------------------
if (!isset($_FILES['excel_file'])) {
    die("No se recibió el archivo.");
}

$archivo = $_FILES['excel_file']['tmp_name'];

$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, false); // false = sin formatear, para obtener serial de fechas como número

// Eliminar cabecera
array_shift($rows);

// -------------------- QUERY BASE (CORREGIDO FECHA YYYY-MM-DD) --------------------
$querySelect = "
SELECT
  la.id AS id_atencion,
  env.id AS id_envio,
  p.nrodoc,
  dori.nom_depen AS dependencia_origen,
  DATE(dpro.create_toma) AS fecha_toma_muestra
FROM lab.tbl_labproductoatencion_envio env
INNER JOIN lab.tbl_labproductoatencion dpro ON env.id_producto_atencion = dpro.id
INNER JOIN lab.tbl_labatencion la ON dpro.id_atencion = la.id
INNER JOIN public.tbl_persona p ON la.id_paciente = p.id_persona
INNER JOIN public.tbl_dependencia dori ON la.id_dependencia = dori.id_dependencia
WHERE dpro.id_dependencia = 67 
  AND dpro.id_producto = 60 
  AND dpro.id_estado_reg = 1 
  AND env.id_estado_registro = 1 
  AND env.id_estado_env = 2 
  AND env.id_estado_resul = 2 
  AND dpro.id_estado_resul = 1
ORDER BY env.create_receive_env DESC
";

$stmtSelect = $pdo->query($querySelect);
$resultados = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

if (empty($resultados)) {
    die("No hay registros en la base para comparar.");
}

// -------------------- PREPARAR INSERTS --------------------

$sqlInsertResultado = "
INSERT INTO lab.tbl_labresultado (
    id_atencion,
    nro_atencionresul,
    anio_atencionresul,
    descrip_obsresul,
    idestado_resul,
    user_create_resul,
    create_resul,
    descrip_obsresulvalid,
    user_create_valid,
    create_valid
) VALUES (
    :id_atencion,
    NULL,
    NULL,
    NULL,
    2,
    1,
    NOW(),
    NULL,
    NULL,
    NULL
)
RETURNING id
";
$stmtInsertResultado = $pdo->prepare($sqlInsertResultado);

$sqlInsert1 = "
INSERT INTO lab.tbl_labresultadodet (
    id_atencion,id_resultado,id_producto,id_productogrupo,
    chk_muestra_grupo,orden_grupo,id_productogrupocomp,id_metodocomponente,
    chk_muestra_metodo,id_compvalref,ing_resul,idtipo_ingresol,
    det_resul,idseleccion_resul,ord_componente,valid_resul,
    user_create_valid,create_valid,estado,user_create_at,
    create_at,user_create_up,create_up,opt_origen_sistema,
    valor_ref_minimo,valor_ref_maximo
) VALUES (
    :id_atencion,:id_resultado,
    60,81,TRUE,1,363,142,FALSE,NULL,
    1,1,:resultado_excel,NULL,1,FALSE,NULL,NULL,1,1,
    NOW(),NULL,NULL,1,NULL,NULL
)";
$stmtInsert1 = $pdo->prepare($sqlInsert1);

$sqlInsert2 = "
INSERT INTO lab.tbl_labresultadodet (
    id_atencion,id_resultado,id_producto,id_productogrupo,
    chk_muestra_grupo,orden_grupo,id_productogrupocomp,id_metodocomponente,
    chk_muestra_metodo,id_compvalref,ing_resul,idtipo_ingresol,
    det_resul,idseleccion_resul,ord_componente,valid_resul,
    user_create_valid,create_valid,estado,user_create_at,
    create_at,user_create_up,create_up,opt_origen_sistema,
    valor_ref_minimo,valor_ref_maximo
) VALUES (
    :id_atencion,:id_resultado,
    60,60,FALSE,2,430,142,FALSE,NULL,
    0,2,NULL,NULL,1,FALSE,NULL,NULL,1,1,
    NOW(),NULL,NULL,1,NULL,NULL
)";
$stmtInsert2 = $pdo->prepare($sqlInsert2);

$sqlUpdateAtencion = "
UPDATE lab.tbl_labproductoatencion
SET id_estado_resul = 2
WHERE id_atencion = :id_atencion
";
$stmtUpdateAtencion = $pdo->prepare($sqlUpdateAtencion);

$sqlUpdateEnvio = "
UPDATE lab.tbl_labproductoatencion_envio
SET id_estado_resul = 4
WHERE id = :id_envio
";
$stmtUpdateEnvio = $pdo->prepare($sqlUpdateEnvio);

// -------------------- COMPARAR Y INSERTAR --------------------
$insertados = 0;

foreach ($rows as $fila) {

    // Saltar filas vacías o de cabecera (DNI debe ser numérico)
    $dni_check = isset($fila[10]) ? trim($fila[10]) : '';
    if (empty($dni_check) || !ctype_digit($dni_check)) {
        continue;
    }

    $dependencia_excel = isset($fila[2]) ? trim($fila[2]) : '';
    $dni_excel = $dni_check;
    $fecha_excel_raw = isset($fila[23]) ? $fila[23] : null;
    $resultado_excel = isset($fila[28]) ? trim($fila[28]) : '';

    // ---- Normalizar fecha del Excel a YYYY-MM-DD ----
    $fecha_excel = null;

    if (!empty($fecha_excel_raw)) {
        if (is_numeric($fecha_excel_raw)) {
            // Fecha guardada como número serial de Excel (ej: 45678)
            $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float)$fecha_excel_raw);
            $fecha_excel = $dt->format('Y-m-d');
        } else {
            // Fecha como texto: intentar múltiples formatos
            $fecha_excel_raw = trim($fecha_excel_raw);
            $formatos = ['d/m/Y', 'Y-m-d', 'm/d/Y', 'd-m-Y', 'Y/m/d', 'd/m/Y H:i:s', 'Y-m-d H:i:s'];
            foreach ($formatos as $fmt) {
                $dt = DateTime::createFromFormat($fmt, $fecha_excel_raw);
                if ($dt !== false) {
                    $fecha_excel = $dt->format('Y-m-d');
                    break;
                }
            }
            if ($fecha_excel === null) {
                try {
                    $dt = new DateTime($fecha_excel_raw);
                    $fecha_excel = $dt->format('Y-m-d');
                } catch (Exception $e) {
                    // no se pudo parsear, skip
                }
            }
        }
    }

    foreach ($resultados as $rowDB) {

        $dni_bd = trim($rowDB['nrodoc']);
        $dependencia_bd = trim($rowDB['dependencia_origen']);
        $fecha_bd = trim($rowDB['fecha_toma_muestra']); // ya viene YYYY-MM-DD

        // ---- COMPARACIÓN FINAL ----
        if (
            $dni_excel === $dni_bd &&
            $dependencia_excel === $dependencia_bd &&
            $fecha_excel === $fecha_bd
        ) {

            $id_atencion = $rowDB['id_atencion'];
            $id_envio    = $rowDB['id_envio'];

            // 1️⃣ Insert resultado
            $stmtInsertResultado->execute([
                ':id_atencion' => $id_atencion
            ]);
            $id_resultado = $stmtInsertResultado->fetchColumn();

            // 2️⃣ Insert detalle 1
            $stmtInsert1->execute([
                ':id_atencion' => $id_atencion,
                ':id_resultado' => $id_resultado,
                ':resultado_excel' => $resultado_excel
            ]);

            // 3️⃣ Insert detalle 2
            $stmtInsert2->execute([
                ':id_atencion' => $id_atencion,
                ':id_resultado' => $id_resultado
            ]);

            // 4️⃣ Update estado producto atencion
            $stmtUpdateAtencion->execute([
                ':id_atencion' => $id_atencion
            ]);

            // 5️⃣ Update estado envio → 4 (Validado/Atendido)
            $stmtUpdateEnvio->execute([
                ':id_envio' => $id_envio
            ]);

            $insertados += 3;

            break; // evita duplicar si ya encontró coincidencia
        }
    }
}

echo "Proceso finalizado. Cargado exitosamente. Inserts realizados: $insertados";