function buscar_datos_personales(origen){
	$('#txtIdPer').val('0');
	var msg = "";
	var sw = true;
	var txtIdTipDoc = $('#txtIdTipDocPac').val();
	var txtNroDoc = $('#txtNroDocPac').val();
	var txtNroDocLn = txtNroDoc.length;

	bloquea_y_limpia_datos_personales_antes_de_buscar();

	//alert(txtIdTipDoc);
	if (txtIdTipDoc == "1") {
		if (txtNroDocLn != 8) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
		if(validateNumber(txtNroDoc) == "0"){
		  msg += "Ingrese el Nro. de documento correctamente (Digitar valores numéricos)<br/>";
		  sw = false;
		}
	} else if(txtIdTipDoc == "2" || txtIdTipDoc == "4"){
		if (txtNroDocLn <= 5) {
		  msg += "Ingrese el Nro. de documento correctamente 1<br/>";
		  sw = false;
		}
	} else if(txtIdTipDoc == "10"){
		if (txtNroDocLn <= 4) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	} else if(txtIdTipDoc == "8"){
		if (txtNroDocLn <= 2) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	} else {
		if (txtNroDocLn <= 6) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	}

	if (sw == false) {
		bootbox.alert(msg);
		$('#btn-pac-search').prop("disabled", false);
		return false;
	}

	$('#btn-pac-search').prop("disabled", true);
	$.ajax({
	url: "../../controller/ctrlPersona.php",
	type: "POST",
	dataType: 'json',
	data: {
		accion: 'GET_SHOW_PERSONULTIMAATENCIONPORIDDEP', txtIdTipDoc: txtIdTipDoc, txtNroDoc: txtNroDoc, interfaz: origen
	},
	beforeSend: function (objeto) {
	  bootbox.dialog({
		message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Por favor espere...</p>',
		closeButton: false
	  });
	},
	success: function (registro) {
	  var datos = eval(registro);
	  $("#txtIdPer").val(datos[0]);
	  if(datos[0] == "E"){
		$("#txtIdPer").val('0');
		setTimeout(function(){$('#txtNroDocPac').trigger('focus');}, 2);
		showMessage("No se encontró el DNI en consulta RENIEC, verifíque por favor.", "error");
	  }  else if (datos[0] == "NE"){
		$("#txtIdPer").val('0');
		$("#txtIdPer").val('0');
		habilita_datos_personales_y_hc_focus();
		showMessage("No se encontró registrada a la paciente</b>, por favor ingrese sus datos manualmente.", "error");
	  } else if(datos[0] == "C"){ //Consulta reniec no disponible
		$("#txtIdPer").val('0');
		showMessage(datos[1], "error");
		//habilita_datos_personales_y_hc_focus();
		//showMessage("El servicio de consulta RENIEC no está disponible, por favor ingrese los datos manualmente...", "error");
		
	  } else if((datos[4] == null) || (datos[4] == "")){
		$("#txtIdPer").val('0');
		habilita_datos_personales_y_hc_focus();
		showMessage("El servicio de consulta RENIEC no está disponible, por favor ingrese los datos manualmente...", "error");
	  } else {
		$("#txtIdPaisNacPac").val(datos[21]).trigger("change");
		if(datos[22] != ""){
		  $("#txtIdEtniaPac").val(datos[22]).trigger("change");
		} else {
		  $('#txtIdEtniaPac').prop("disabled", false);
		}
		$("#txtNomPac").val(datos[4]);
		$("#txtPriApePac").val(datos[5]);
		$("#txtSegApePac").val(datos[6]);
		$("#txtIdSexoPac").val(datos[7]);
		$('#txtFecNacPac').val(datos[9]);
		$("#txtEdadPac").val(datos[20]);
		$("#txtNroTelFijoPac").val(datos[11]);
		$("#txtNroTelMovilPac").val(datos[12]);
		$("#txtEmailPac").val(datos[13]);
		$("#txtNroHCPac").val(datos[10]);

		$("#txt_id_ubigeo_temp").val(datos[14]);
		$("#txt_direccion_temp").val(datos[18]);

		$('#txtNroTelFijoPac').prop("disabled", false);
		$('#txtNroTelMovilPac').prop("disabled", false);
		$('#txtEmailPac').prop("disabled", false);
		$('#txtNroHCPac').prop("disabled", false);
		if(datos[7] == ""){
			$('#txtIdSexoPac').prop("disabled", false);
		}
		if(datos[9] == ""){
		  $('#txtFecNacPac').prop("disabled", false);
		}
		if(datos[20] == ""){
			$('#txtEdadPac').prop("readonly", false);
		} else {
			$('#txtEdadPac').prop("readonly", true);
		}
		if(datos[10] == ""){
			$("#txtNroHCPac").trigger('focus');
		} else{
		  $("#txtNroTelFijoPac").trigger('focus');
		}

		// VALIDACIONES PARA LABORATORIO REFERENCIAL
		var validacion_edad = datos[27]; // 1 = cumple (40-65 años), 0 = no cumple
		var validacion_sexo = datos[28]; // 1 = cumple (masculino), 0 = no cumple
		var puede_atenderse = datos[29]; // 1 = puede, 0 = no puede (tiene resultado < 1 año)
		var dias_ultima_atencion = datos[25];

		// Guardar valores de validación en campos hidden
		$("#txtValidacionEdad").val(validacion_edad);
		$("#txtValidacionSexo").val(validacion_sexo);
		$("#txtPuedeAtenderse").val(puede_atenderse);

		var mensajes_advertencia = [];

		if(validacion_edad == 0){
			mensajes_advertencia.push("⚠️ ADVERTENCIA: El paciente debe tener entre 40 y 65 años (Edad actual: " + datos[20] + " años)");
		}

		if(validacion_sexo == 0){
			mensajes_advertencia.push("⚠️ ADVERTENCIA: Este examen solo está disponible para pacientes varones");
		}

		if(puede_atenderse == 0){
			var dias_faltantes = 365 - dias_ultima_atencion;
			mensajes_advertencia.push("⚠️ ADVERTENCIA: El paciente tiene un resultado validado hace " + dias_ultima_atencion + " días. Debe esperar " + dias_faltantes + " días más (1 año desde el último resultado)");
		}

		if(mensajes_advertencia.length > 0){
			Swal.fire({
				icon: 'warning',
				title: 'VALIDACIÓN DE CRITERIOS PARA LABORATORIO REFERENCIAL',
				html: mensajes_advertencia.join("<br/><br/>"),
				confirmButtonText: 'Entendido',
				confirmButtonColor: '#f39c12',
				width: '600px'
			});
		}

		buscar_datos_direc(datos[0]);
	  }

	  $('#txtUBIGEOPac').prop("disabled", false);
	  $('#txtIdTipDocSoli').prop("disabled", false);
	  $('#txtNroDocSoli').prop("disabled", false);
	  $('#btnSoliSearch').prop("disabled", false);

	  /*if(document.frmPaciente.txtTipPac.value == "1"){
		buscar_datos_sis();
	  } else {
		bootbox.hideAll();
	  }*/
	},
	complete: function (xhr, status) {
        // Siempre se ejecuta, haya éxito o error
        bootbox.hideAll();
    },
	});
}

function bloquea_y_limpia_datos_personales_antes_de_buscar(){
	$('#txtNroHCPac').prop("disabled", true);
	$("#txtIdPaisNacPac").prop("disabled", true);
	//$("#txtIdEtniaPac").prop("disabled", true);
	$('#txtNomPac').prop("readonly", true);
	$('#txtPriApePac').prop("readonly", true);
	$('#txtSegApePac').prop("readonly", false);
	$('#txtIdSexoPac').prop("disabled", true);
	$('#txtFecNacPac').prop("disabled", true);
	$('#txtNroTelFijoPac').prop("disabled", true);
	$('#txtNroTelMovilPac').prop("disabled", true);
	$('#txtEmailPac').prop("disabled", false);
	$('#txtNroHCPac').val('');
	$("#txtIdPaisNacPac").val('').trigger("change");
	$("#txtIdEtniaPac").val('').trigger("change");
	$('#txtNomPac').val('');
	$('#txtPriApePac').val('');
	$('#txtSegApePac').val('');
	$('#txtIdSexoPac').val('');
	$('#txtFecNacPac').val('');
	$('#txtEdadPac').val('');
	$('#txtNroTelFijoPac').val('');
	$('#txtNroTelMovilPac').val('');
	$('#txtEmailPac').val('');
	
	$('#txtUBIGEOPac').prop("disabled", true);
	$("#txtUBIGEOPac").val('').trigger("change");
	$("#txtIdAvDirPac").val('');
	$("#txtNomAvDirPac").val('');
	$("#txtNroDirPac").val('');
	$("#txtIntDirPac").val('');
	$("#txtDptoDirPac").val('');
	$("#txtMzDirPac").val('');
	$("#txtLtDirPac").val('');
	$("#txtIdPoblaDirPac").val('');
	$("#txtNomPoblaDirPac").val('');
	$("#txtDirPac").val('');
	$("#txtDirRefPac").val('');
}

function habilita_datos_personales_y_hc_focus(){
	$('#txtNroHCPac').prop("disabled", false);
	$("#txtIdPaisNacPac").prop("disabled", false);
	$("#txtIdEtniaPac").prop("disabled", false);
	$('#txtNomPac').prop("readonly", false);
	$('#txtPriApePac').prop("readonly", false);
	$('#txtSegApePac').prop("readonly", false);
	$('#txtIdSexoPac').prop("disabled", false);
	$('#txtFecNacPac').prop("disabled", false);
	$('#txtNroTelFijoPac').prop("disabled", false);
	$('#txtNroTelMovilPac').prop("disabled", false);
	$('#txtEmailPac').prop("disabled", false);
	setTimeout(function(){$('#txtNroHCPac').trigger('focus');}, 3);
}

function buscar_datos_direc(id){
	if (id != '0'){
		var txtIdTipDoc = '';
		var txtNroDoc = '';
		$.ajax({
		url: "../../controller/ctrlPersona.php",
		type: "POST",
		dataType: 'json',
		data: {
		  accion: 'GET_SHOW_DETDISRECCIONVIAPORPERSONA', txtIdPer: id, txtIdTipDoc: txtIdTipDoc, txtNroDoc: txtNroDoc
		},
		success: function (registro) {
		  var datos = eval(registro);
		  if((datos[0] == null) || (datos[0] == "0")){
			$("#txtUBIGEOPac").val($("#txt_id_ubigeo_temp").val()).trigger("change");
			$("#txtDirRefPac").val($("#txt_direccion_temp").val());
			$("#txtIdAvDirPac").val('');
			$("#txtNomAvDirPac").val('');
			$("#txtNroDirPac").val('');
			$("#txtIntDirPac").val('');
			$("#txtDptoDirPac").val('');
			$("#txtMzDirPac").val('');
			$("#txtLtDirPac").val('');
			$("#txtIdPoblaDirPac").val('');
			$("#txtNomPoblaDirPac").val('');
			$("#txtDirPac").val('');
		  } else {
			var idTipVia = "";
			var idTipPobla = "";
			$("#txtUBIGEOPac").val(datos[1]).trigger("change");
			if(datos[5] != ""){
			  idTipVia = datos[5]+"#"+datos[6];
			}
			$("#txtIdAvDirPac").val(idTipVia);
			$("#txtNomAvDirPac").val(datos[8]);
			$("#txtNroDirPac").val(datos[9]);
			$("#txtIntDirPac").val(datos[10]);
			$("#txtDptoDirPac").val(datos[11]);
			$("#txtMzDirPac").val(datos[12]);
			$("#txtLtDirPac").val(datos[13]);
			if(datos[14] != ""){
			  idTipPobla = datos[14]+"#"+datos[15];
			}
			$("#txtIdPoblaDirPac").val(idTipPobla);
			$("#txtNomPoblaDirPac").val(datos[17]);
			$("#txtDirPac").val(datos[18]);
			$("#txtDirRefPac").val(datos[18]);
		  }
		}
		});
	} else {
		$("#txtUBIGEOPac").val($("#txt_id_ubigeo_temp").val()).trigger("change");
		$("#txtDirRefPac").val($("#txt_direccion_temp").val());
		$("#txtIdAvDirPac").val('');
		$("#txtNomAvDirPac").val('');
		$("#txtNroDirPac").val('');
		$("#txtIntDirPac").val('');
		$("#txtDptoDirPac").val('');
		$("#txtMzDirPac").val('');
		$("#txtLtDirPac").val('');
		$("#txtIdPoblaDirPac").val('');
		$("#txtNomPoblaDirPac").val('');
		$("#txtDirPac").val('');
	}
}

function buscar_datos_personalessoli(){
	$('#txtIdSoli').val('0');
	var msg = "";
	var sw = true;
	var txtIdTipDoc = $('#txtIdTipDocSoli').val();
	var txtNroDoc = $('#txtNroDocSoli').val();
	var txtNroDocLn = txtNroDoc.length;

	bloquea_y_limpia_datos_personalessoli_antes_de_buscar();

	if (txtIdTipDoc == "1") {
		if (txtNroDocLn != 8) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	} else if(txtIdTipDoc == "2" || txtIdTipDoc == "4" || txtIdTipDoc == "10"){
		if (txtNroDocLn <= 4) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	} else {
		if (txtNroDocLn <= 9) {
		  msg += "Ingrese el Nro. de documento correctamente<br/>";
		  sw = false;
		}
	}

	if (sw == false) {
		bootbox.alert(msg);
		$('#btn-pac-search').prop("disabled", false);
		return false;
	}

	$('#btnSoliSearch').prop("disabled", true);
	$.ajax({
		url: "../../controller/ctrlPersona.php",
		type: "POST",
		dataType: 'json',
		data: {
		  accion: 'GET_SHOW_PERSONULTIMAATENCIONPORIDDEP', txtIdTipDoc: txtIdTipDoc, txtNroDoc: txtNroDoc
		},
		beforeSend: function (objeto) {
		  bootbox.dialog({
			message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Por favor espere...</p>',
			closeButton: false
		  });
		},
		success: function (registro) {
			bootbox.hideAll();
			var datos = eval(registro);
			$("#txtIdSoli").val(datos[0]);
			if(datos[0] == "E"){
				$("#txtIdSoli").val('0');
				setTimeout(function(){$('#txtNroDocSoli').trigger('focus');}, 2);
				showMessage("No se encontró el DNI en consulta RENIEC, verifíque por favor.", "error");
			}  else if (datos[0] == "NE"){
				$("#txtIdSoli").val('0');
				habilita_datos_personales_soli();
				showMessage("No se encontró registrada a la paciente</b>, por favor ingrese sus datos manualmente.", "error");
			} else if(datos[0] == "C"){ //Consulta reniec no disponible
				$("#txtIdSoli").val('0');
				habilita_datos_personales_soli();
				showMessage("El servicio de consulta RENIEC no está disponible, por favor ingrese los datos manualmente...", "error");
			} else if((datos[4] == null) || (datos[4] == "")){
				$("#txtIdSoli").val('0');
				habilita_datos_personales_soli();
				showMessage("El servicio de consulta RENIEC no está disponible, por favor ingrese los datos manualmente...", "error");
			} else {
				$("#txtIdPaisNacSoli").val(datos[21]).trigger("change");
				$("#txtNomSoli").val(datos[4]);
				$("#txtPriApeSoli").val(datos[5]);
				$("#txtSegApeSoli").val(datos[6]);
				$("#txtIdSexoSoli").val(datos[7]);
				$("#txtFecNacSoli").val(datos[9]);
				$("#txtNroTelFijoSoli").val(datos[11]);
				$("#txtNroTelMovilSoli").val(datos[12]);
				$("#txtEmailSoli").val(datos[13]);
				$('#txtIdParenSoli').prop("disabled", false);
				$('#txtNroTelFijoSoli').prop("disabled", false);
				$('#txtNroTelMovilSoli').prop("disabled", false);
				$('#txtEmailSoli').prop("disabled", false);
				$('#btnSoliSearch').prop("disabled", false);
				if(datos[9] == ""){
				  $('#txtFecNacSoli').prop("disabled", false);
				}
				$("#txtIdParenSoli").trigger('focus');
		  }
		}
	});
}

function limpia_datos_personalsoli(){
	if ($("#txtNroDocSoli").val() == ""){
		$('#txtIdSoli').val('0');
		bloquea_y_limpia_datos_personalessoli_antes_de_buscar();
	} else {
		return false;
	}
}

function bloquea_y_limpia_datos_personalessoli_antes_de_buscar(){
	
	$('#txtNomSoli').prop("readonly", true);
	$('#txtPriApeSoli').prop("readonly", true);
	$('#txtSegApeSoli').prop("readonly", true);
	$('#txtIdSexoSoli').prop("disabled", true);
	$('#txtFecNacSoli').prop("disabled", true);
	$('#txtIdParenSoli').prop("disabled", true);
	$('#txtNroTelFijoSoli').prop("disabled", true);
	$('#txtNroTelMovilSoli').prop("disabled", true);
	$('#txtEmailSoli').prop("disabled", true);

	$("#txtIdPaisNacSoli").val('').trigger("change");
	$("#txtIdParenSoli").val('').trigger("change");
	$("#txtNomSoli").val('');
	$("#txtPriApeSoli").val('');
	$("#txtSegApeSoli").val('');
	$("#txtIdSexoSoli").val('');
	$("#txtFecNacSoli").val('');
	$("#txtNroTelFijoSoli").val('');
	$("#txtNroTelMovilSoli").val('');
	$("#txtEmailSoli").val('');
}

function habilita_datos_personales_soli(){
	$("#txtIdPaisNacSoli").prop("disabled", false);
	$('#txtIdSexoSoli').prop("disabled", false);
	$('#txtIdParenSoli').prop("disabled", false);
	$('#txtNomSoli').prop("readonly", false);
	$('#txtPriApeSoli').prop("readonly", false);
	$('#txtSegApeSoli').prop("readonly", false);
	$('#txtFecNacSoli').prop("disabled", false);

	$('#txtNroTelFijoSoli').prop("disabled", false);
	$('#txtNroTelMovilSoli').prop("disabled", false);
	$('#txtEmailSoli').prop("disabled", false);
	setTimeout(function(){$('#txtIdPaisNacSoli').trigger('focus');}, 3);
}

function validarFechaNacimiento(fecha) {
	var msg = "";
	var sw = true;

    // 1Validar que no esté vacía
    if (fecha === "") {
		msg += "La fecha de nacimiento es obligatoria<br/>";
    }

    // 2Validar el formato correcto (DD/MM/YYYY)
    var regex = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/;
    if (!regex.test(fecha)) {
		msg += "Formato de fecha incorrecto (use DD/MM/YYYY)<br/>";
    }

    // 3Convertir de DD/MM/YYYY a YYYY-MM-DD
    var partes = fecha.split("/");
    var fechaFormatoISO = `${partes[2]}-${partes[1]}-${partes[0]}`; // YYYY-MM-DD

    // 4Convertir a objeto Date y validar si es real
    var fechaNacimiento = new Date(fechaFormatoISO);
    if (isNaN(fechaNacimiento.getTime())) {
		msg += "Fecha de nacimiento inválida<br/>";
    }

    // 5Validar que la fecha no sea en el futuro
    var hoy = new Date();
    if (fechaNacimiento > hoy) {
		msg += "La fecha de nacimiento no puede ser futura<br/>";
    }

    return msg;
}

function buscar_datos_personales_editar(id_atencion, interfaz){
	$.ajax({
	url: "../../controller/ctrlPersona.php",
	type: "POST",
	dataType: 'json',
	data: {
		accion: 'GET_SHOW_PERSONA_EDITAR', id_atencion: id_atencion, interfaz_origen: interfaz
	},
	success: function (registro) {
		var datos = eval(registro);
		$("#txt_edit_id_paciente").val(datos[0]);
		$("#txt_edit_id_tipo_doc_pac").val(datos[1]).trigger("change");
		$("#txt_edit_nro_doc_pac").val(datos[3]);
		$("#txt_edit_nro_hc_pac").val(datos[10]);
		$("#txt_edit_id_pais_pac").val(datos[21]).trigger("change");
		$("#txt_edit_primer_ape_pac").val(datos[5]);
		$("#txt_edit_segundo_ape_pac").val(datos[6]);
		$("#txt_edit_nombres_pac").val(datos[4]);
		$("#txt_edit_id_sexo_pac").val(datos[7]);
		$('#txt_edit_fec_nac_pac').val(datos[9]);
		$("#txt_edit_edad_pac").val(datos[20]);
		$("#txt_id_etnia_pac").val(datos[22]).trigger("change");				
		
		$("#txt_edit_tel_fij_pac").val(datos[11]);
		$("#txt_edit_tel_mov_pac").val(datos[12]);
		$("#txt_edit_email_pac").val(datos[13]);
		$("#txt_edit_id_ubigeo_pac").val(datos[14]).trigger("change");
		$("#txt_edit_direccion_pac").val(datos[18]);
		$("#txt_edit_ref_direccion_pac").val(datos[19]);
	}});
}

function save_personales_editar(acc) {
	$('#btn_frm_edit_pac').prop("disabled", true);
	var msg = "";
	var sw = true;
	
	var regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/; //Solo letras y espacio
	
	var txtUBIGEOPac = $('#txt_edit_id_ubigeo_pac').val();
	if(txtUBIGEOPac === null){txtUBIGEOPac = "";}

	var txtIdPaisNacPac = $('#txt_edit_id_pais_pac').val().trim();
	var txtNroHC = $('#txt_edit_nro_hc_pac').val().trim();
	var txtNomPac = $('#txt_edit_nombres_pac').val().trim();
	var txtPriApePac = $('#txt_edit_primer_ape_pac').val().trim();
	var txtIdSexoPac = $('#txt_edit_id_sexo_pac').val().trim();
	var txtFecNacPac = $('#txt_edit_fec_nac_pac').val().trim();
	if (txtIdPaisNacPac == "") { msg += "Seleccione el PAIS de nacimiento del Paciente<br/>"; sw = false;}
	if (txtNroHC == "") { msg += "Ingrese el Nro. de Historia Clínica del Paciente<br/>"; sw = false;}
	if (txtNomPac == "") { msg += "Ingrese el nombre del Paciente<br/>"; sw = false;}
    if (!regex.test(txtNomPac)) {msg += "El nombre solo puede contener letras y espacios<br/>"; sw = false;}
	if (txtNomPac.length < 2 || txtNomPac.length > 100) {msg += "El nombre debe tener entre 2 y 100 caracteres<br/>"; sw = false;}
	if (txtPriApePac == "") { msg += "Ingrese el primer apellido del Paciente<br/>"; sw = false;}
	if (txtIdSexoPac == "") { msg += "Seleccione SEXO del paciente<br/>"; sw = false;}
	txtFecNacPac = validarFechaNacimiento(txtFecNacPac);
	if (txtFecNacPac != ""){
		msg += txtFecNacPac; sw = false;
	}
	
	if (sw == false) {
		bootbox.alert(msg);
		$('#btn_frm_edit_pac').prop("disabled", false);
		return sw;
	}

	$.ajax( {
	  type: 'POST',
	  url: '../../controller/ctrlPersona.php',
	  data: "id_paciente=" + $('#txt_edit_id_paciente').val() + "&id_atencion=" + $('#txt_id_atencion').val() + "&nro_hc_pac=" + $('#txt_edit_nro_hc_pac').val()
	  + "&id_tipo_doc_pac=" + $('#txt_edit_id_tipo_doc_pac').val() + "&nro_doc_pac=" + $('#txt_edit_nro_doc_pac').val() + "&id_pais_pac=" + $('#txt_edit_id_pais_pac').val() + "&id_etnia_pac=" + $('#txt_id_etnia_pac').val() + "&id_sexo_pac=" + $('#txt_edit_id_sexo_pac').val() + "&fec_nac_pac=" + $('#txt_edit_fec_nac_pac').val() + "&nombres_pac=" + $('#txt_edit_nombres_pac').val() + "&primer_ape_pac=" + $('#txt_edit_primer_ape_pac').val() + "&segundo_ape_pac=" + $('#txt_edit_segundo_ape_pac').val() + "&tel_fij_pac=" + $('#txt_edit_tel_fij_pac').val() + "&tel_mov_pac=" + $('#txt_edit_tel_mov_pac').val() + "&email_pac=" + $('#txt_edit_email_pac').val()
	  + "&id_ubigeo_pac=" + txtUBIGEOPac + "&direccion_pac=" + $('#txt_edit_direccion_pac').val() + "&ref_direccion_pac=" + $('#txt_edit_ref_direccion_pac').val()
	  + "&accion=POST_REG_PERSONA&interfaz=" + acc,
	  success: function(data) {
		$('#btn_frm_edit_pac').prop("disabled", false);
		if(data === ""){
			if (acc == "LAB"){
				$("#tblAtencion").dataTable().fnDraw();
				$('#modal_edit_persona').modal('hide');
			}
			showMessage('Registro actualizado correctamente', "success");
			return false;
		} else {
			showMessage(msg, "error");
			return false;
		}
	  }
	});
}