<?php
require '../../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\IOFactory;

// -------------------- CONEXION --------------------
$host = '10.0.0.3';
$db   = 'pe_diris_slab_test';
$user = 'usr_lab';
$pass = 'lab@12345';
$port = '5432'; // si es PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// -------------------- RECIBIR ARCHIVO EXCEL --------------------
if (!isset($_FILES['excel_file'])) {
    die("No se recibió el archivo.");
}

$archivo = $_FILES['excel_file']['tmp_name'];

// Cargar Excel
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

// Eliminar cabecera
array_shift($rows);

// -------------------- OBTENER QUERY BASE --------------------
$querySelect = "
SELECT 
  la.id AS id_atencion,
  p.nrodoc,
  dori.nom_depen AS dependencia_origen,
  to_char(dpro.create_toma, 'DD/MM/YYYY') AS fecha_toma_muestra
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

//  Insert en tbl_labresultado
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

//  Primer insert en tbl_labresultadodet
$sqlInsert1 = "
INSERT INTO lab.tbl_labresultadodet (
    id_atencion,
    id_resultado,
    id_producto,
    id_productogrupo,
    chk_muestra_grupo,
    orden_grupo,
    id_productogrupocomp,
    id_metodocomponente,
    chk_muestra_metodo,
    id_compvalref,
    ing_resul,
    idtipo_ingresol,
    det_resul,
    idseleccion_resul,
    ord_componente,
    valid_resul,
    user_create_valid,
    create_valid,
    estado,
    user_create_at,
    create_at,
    user_create_up,
    create_up,
    opt_origen_sistema,
    valor_ref_minimo,
    valor_ref_maximo
) VALUES (
    :id_atencion,
    :id_resultado,
    60, 81, TRUE, 1, 363, 142, FALSE, NULL,
    1, 1, :resultado_excel, NULL, 1, FALSE, NULL, NULL, 1, 1,
    NOW(), NULL, NULL, 1, NULL, NULL
)";
$stmtInsert1 = $pdo->prepare($sqlInsert1);

//  Segundo insert en tbl_labresultadodet
$sqlInsert2 = "
INSERT INTO lab.tbl_labresultadodet (
    id_atencion,
    id_resultado,
    id_producto,
    id_productogrupo,
    chk_muestra_grupo,
    orden_grupo,
    id_productogrupocomp,
    id_metodocomponente,
    chk_muestra_metodo,
    id_compvalref,
    ing_resul,
    idtipo_ingresol,
    det_resul,
    idseleccion_resul,
    ord_componente,
    valid_resul,
    user_create_valid,
    create_valid,
    estado,
    user_create_at,
    create_at,
    user_create_up,
    create_up,
    opt_origen_sistema,
    valor_ref_minimo,
    valor_ref_maximo
) VALUES (
    :id_atencion,
    :id_resultado,
    60, 60, FALSE, 2, 430, 142, FALSE, NULL,
    0, 2, NULL, NULL, 1, FALSE, NULL, NULL, 1, 1,
    NOW(), NULL, NULL, 1, NULL, NULL
)";
$stmtInsert2 = $pdo->prepare($sqlInsert2);

//  UPDATE a tbl_labproductoatencion (SEGÚN id_atencion)
$sqlUpdateAtencion = "
UPDATE lab.tbl_labproductoatencion
SET id_estado_resul = 2
WHERE id_atencion = :id_atencion
";
$stmtUpdateAtencion = $pdo->prepare($sqlUpdateAtencion);

// -------------------- COMPARAR Y INSERTAR --------------------
$insertados = 0;

foreach ($rows as $fila) {

    //  NUEVAS COLUMNAS DEL EXCEL (según lo que me indicaste)
    $dependencia_excel = trim($fila[2]);   // C → Categoría
    $dni_excel = trim($fila[10]);          // K → Identificación
    $fecha_excel_raw = trim($fila[23]);    // X → Fechas toma muestras
    $resultado_excel = trim($fila[28]);    // AC → Resultado

    // ---- Normalizar fecha del Excel a DD/MM/YYYY ----
    // ---- Normalizar fecha del Excel (FORMATO SEGURO) ----
    $fecha_excel = null;

    if (!empty($fecha_excel_raw)) {

        // Si viene como objeto de fecha de Excel, convertir primero a string
        if ($fecha_excel_raw instanceof \PhpOffice\PhpSpreadsheet\Shared\Date) {
            $fecha_excel_raw = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha_excel_raw)
                ->format('Y-m-d H:i:s');
        }

        // Quitar cualquier zona horaria rara si existe
        $fecha_excel_raw = preg_replace('/\s*\(.*\)$/', '', $fecha_excel_raw);

        try {
            $dt = new DateTime($fecha_excel_raw);
            $fecha_excel = $dt->format('d/m/Y'); // <-- formato igual a tu BD
        } catch (Exception $e) {
            continue; // si no puede convertir, salta esta fila
        }
    }

    foreach ($resultados as $rowDB) {

        // ---- Normalizar dependencia y DNI (evita problemas de espacios) ----
        $dni_bd = trim($rowDB['nrodoc']);
        $dependencia_bd = trim($rowDB['dependencia_origen']);
        $fecha_bd = trim($rowDB['fecha_toma_muestra']); // ya viene como DD/MM/YYYY

        // COMPARACIÓN FINAL (independiente del formato de fecha original)
        if (
    $dni_excel == $dni_bd &&
    $dependencia_excel == $dependencia_bd
) {

            $id_atencion = $rowDB['id_atencion'];

            // 1️ Insert en tbl_labresultado (captura id generado)
            $stmtInsertResultado->execute([':id_atencion' => $id_atencion]);
            $id_resultado = $stmtInsertResultado->fetchColumn();

            // 2️ Primer insert detalle (usa resultado del Excel)
            $stmtInsert1->execute([
                ':id_atencion' => $id_atencion,
                ':id_resultado' => $id_resultado,
                ':resultado_excel' => $resultado_excel
            ]);

            // 3️ Segundo insert detalle (usa mismo resultado del Excel)
            $stmtInsert2->execute([
                ':id_atencion' => $id_atencion,
                ':id_resultado' => $id_resultado
                
            ]);

            // 4️ UPDATE: marcar atención con estado 2 (usando el mismo id_atencion)
            $stmtUpdateAtencion->execute([
                ':id_atencion' => $id_atencion
            ]);

            $insertados += 3;
        }
    }
}


//echo "Proceso finalizado. Inserts realizados: $insertados";


echo "Proceso finalizado. Cargado exitosamente";
