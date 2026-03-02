<?php
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip_local = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip_local = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip_local = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip_local = $_SERVER['REMOTE_ADDR'];
	else
		$ip_local = "unknown";

	function obtenerIpPublica() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api64.ipify.org");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ip = curl_exec($ch);
		curl_close($ch);
		return $ip;
	}

	$dni = $_POST['txtNroDoc'];
	$id_sistema_origen = '25';
	$id_usuario_origen = $labIdUser;
	$nom_usuario_origen = $labNomUser;
	$interfaz_origen = isset($_POST['interfaz_origen']) ? $_POST['interfaz_origen'] : "";
	$descripcion_respuesta = "Correcto";
	$ip_pc = $ip_local;
	$ip_publica = obtenerIpPublica();
	$nombre_pc = gethostname();

	//if(!$dni){ exit(json_encode(["error"=>"Error: nro de dni es requerido"]));}
	if(!$interfaz_origen){ exit(json_encode(["error"=>"Error:interfaz es requerido"]));}

	$curl = curl_init();

	 curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://app1.dirislimacentro.gob.pe/api_dlc/api_dni/index.php/consulta_reniec',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => array(
		'dni' => $dni,
		'id_sistema_origen' => $id_sistema_origen,
		'id_usuario_origen' => $id_usuario_origen,
		'nom_usuario_origen' => $nom_usuario_origen,
		'interfaz_origen' => $interfaz_origen,
		'descripcion_respuesta' => $descripcion_respuesta,
		'ip_pc' => $ip_pc,
		'ip_publica' => $ip_publica,
		'nombre_pc' => $nombre_pc
	),
	  CURLOPT_HTTPHEADER => array(
		'Authorization: 2307c63be925b57b58d2f7f9a1b32f31',
		'Cookie: PHPSESSID=ph6df1shl6ifhql8n21r3ha12e'
	  ),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	//echo $response;
	$data = json_decode($response, true);
	if (isset($data['row'])) {
		$row = $data['row'];

		$nombres = trim($row['nombres']);
		$apellido_paterno = trim($row['apellido_paterno']);
		$apellido_materno = trim($row['apellido_materno']);
		$direccion_actual = trim($row['domicilio_direccion_actual']);
		$direccion = trim($row['domicilio_direccion']);
		$fecha_nacimiento = trim($row['fecha_nacimiento']);
		$sexo = trim($row['sexo']);
		$abrev_sexo = (trim($row['sexo']) == '1') ? 'M' : (($row['sexo'] == '2') ? 'F' : '');
		$id_departamento = trim($row['id_departamento']);
		$id_provincia = trim($row['id_provincia']);
		$id_distrito = trim($row['id_distrito']);
		$foto = trim($row['foto']);
		
		if($labIdDepUser == "67"){ $nro_hc = "00";}
		$nomDpto = "";
		$nomTipVia = "";
		$nomPobla = "";
		$nomMZ = "";
		$nomLT = "";
		$direccion = "";
		$etnia = "";

		if(($fecha_nacimiento == "") OR ($fecha_nacimiento == "--")){
			$fecNacPac = "";
			$edad = "";
		} else {
			//$fecNacPac = date_create($result['row']['fecha_nacimiento']);
			//$fecNacPac = date_format($fecNacPac, "d/m/Y");
			$fecNacPac = formatFechaNacimiento($fecha_nacimiento);
			$rsE = $t->function_calculaEdad($fecNacPac, date("d/m/Y"));
			$edad = $rsE[0];
		}
		
		$datos = array(
			0 => 0,
			1 => $_POST['txtIdTipDoc'],
			2 => 'DNI',
			3 => $_POST['txtNroDoc'],
			4 => $nombres,
			5 => $apellido_paterno,
			6 => $apellido_materno,
			7 => $sexo,
			8 => $abrev_sexo,
			9 => $fecNacPac,
			10 => $nro_hc,//Nro HC
			11 => '',//Nro Tel fijo
			12 => '',//Nro Tel Movil
			13 => '',//Email
			14 => $id_distrito,//Id Ubigeo
			15 => '',//Nombre departamento
			16 =>	'',//Nombre Provincia
			17 => '',//Nombre Distrito
			18 => $direccion,
			19 => '',//Referencia Dirección
			20 => $edad,
			21 => 'PER',
			22 => $etnia,
			23 => 'MINSA'
		);

	} else {
		$datos = array(
			0 => "EM",
			1 => $data['error']
		); 
	}
	
	echo json_encode($datos);

/*


	$result=file_get_contents("http://200.123.29.214/sismed_/ajaxDNI.php?nro_doc=".$_POST['txtNroDoc']."&app=LAB");
	$result = json_decode($result, true);//con esto se convierte de object a array


	if($result['row']['sexo']=='1'){$abrev_sexo='M';}else{$abrev_sexo='F';}
	if(($result['row']['fecha_nacimiento'] == "") OR ($result['row']['fecha_nacimiento'] == "--")){
		$fecNacPac = "";
		$edad = "";
	} else {
		//if (27\/11\/1989)
		//$fecNacPac = date_create($result['row']['fecha_nacimiento']);
		//$fecNacPac = date_format($fecNacPac, "d/m/Y");
		$fecNacPac = formatFechaNacimiento($result['row']['fecha_nacimiento']);
		$rsE = $t->function_calculaEdad($fecNacPac, date("d/m/Y"));
		$edad = $rsE[0];
	}
	if($labIdDepUser == "67"){ $nro_hc = "00";}
	$nomDpto = "";
	$nomTipVia = "";
	$nomPobla = "";
	$nomMZ = "";
	$nomLT = "";
	$direccion = "";
	$etnia = "";
				
	$datos = array(
	  0 => 0,
	  1 => $_POST['txtIdTipDoc'],
	  2 => 'DNI',
	  3 => $_POST['txtNroDoc'],
	  4 => trim($result['row']['nombres']),
	  5 => trim($result['row']['apellido_paterno']),
	  6 => trim($result['row']['apellido_materno']),
	  7 => $result['row']['sexo'],
	  8 => $abrev_sexo,
	  9 => $fecNacPac,
	  10 => $nro_hc,//Nro HC
	  11 => '',//Nro Tel fijo
	  12 => '',//Nro Tel Movil
	  13 => '',//Email
	  14 => $result['row']['id_distrito'],//Id Ubigeo
	  15 => '',//Nombre departamento
	  16 =>	'',//Nombre Provincia
	  17 => '',//Nombre Distrito
	  18 => $direccion,
	  19 => '',//Referencia Dirección
	  20 => $edad,
	  21 => 'PER',
	  22 => $etnia,
	  23 => 'MINSA'
	);
	echo json_encode($datos);*/

?>