function fechaDeNacimientoDesdeEdad(edad) {
	var fechaActual = new Date();
	var añoActual = fechaActual.getFullYear();
	var añoNacimiento = añoActual - edad;
	
	// Establecer la fecha de nacimiento como el 1 de enero del año calculado
	var fechaNacimiento = new Date(añoNacimiento, 0, 1);

	// Formatear la fecha de nacimiento
	var dia = fechaNacimiento.getDate();
	var mes = fechaNacimiento.getMonth() + 1; // Se suma 1 porque los meses van de 0 a 11
	var año = fechaNacimiento.getFullYear();

	// Agregar ceros a la izquierda si el día o el mes son menores que 10
	if (dia < 10) {
		dia = '0' + dia;
	}
	if (mes < 10) {
		mes = '0' + mes;
	}

	// Devolver la fecha en formato dd/mm/yyyy
	return dia + '/' + mes + '/' + año;
	//$('#txtFecNacPac').val(dia + '/' + mes + '/' + año);
}

function obtener_nroatencion(fecha){
	$.post("../../controller/ctrlAtencion.php", { txtFechaAten: fecha, accion: "GET_SHOW_NEWNROATENCIONPORFECHAYIDDEP" }, function(result){
		$("#txtNroRefAtencion").val(result);
	});
}

function detRow(idpro){
  $.ajax({
    url: "../../controller/ctrlProducton.php",
    type: "POST",
    data: {
      accion: 'GET_SHOW_DETPRODUCTOPORIDPRO', txtIdPro: idpro
    },
    success: function (result) {
      var datos = eval(result);
      var newOption = "";
      $(datos[1]).each(function (ii, oo) {
        newOption += "<tr><td><small>" + oo.id_cpt + "</small></td><td><small>" + oo.descrip_prepapro + "</small></td><td><small>" + oo.descrip_insupro + "</small></td><td><small>" + oo.descrip_obspro + "</small></td></tr>";
      });
      $("#det-producto").html(newOption);
	  $("#det-examen-producto").html(datos[2]);
    }
  });
}

function addRow(id){
	if(id == ""){
		if($("#txtIdProducto").val()=='' || $("#txtIdProducto").val()==null){
			bootbox.alert("Selecciona un Exámen");
			return false;
		}
		var cmbid_producto = $("#txtIdProducto option:selected").val();
	} else {
		var cmbid_producto = id;
	}

	if($("#txtIdPlanTari").val()==''){
	bootbox.alert("Selecciona un Plan tarifario");
	return false;
	}

	var cmbid_plan = $("#txtIdPlanTari option:selected").val();
	var datosp = cmbid_plan.split("#");
	var id_plan = datosp[0];
	var check_prec = datosp[1];

	//var cmbid_producto = $("#txtIdProducto option:selected").val();
	var datos = cmbid_producto.split("#");
	var id_producto = datos[0];
	var codref_producto = datos[1];
	var nomtipo_producto = datos[2];
	var prec_sis = datos[3];
	var prec_parti = datos[4];
	if(id == ""){
		var nom_producto = $('#txtIdProducto').select2('data')[0]['text'];
	} else {
		var nom_producto = datos[5];
	}
	if(document.frmPaciente.txtIdPlanTari.value == "1"){
		precio = prec_sis;
	} else {
		if(check_prec == "t"){
			precio = prec_parti;
		} else {
			precio = "0.0000";
		}
	}

	idRow="tr"+id_producto;
	if (document.getElementById(idRow)){
		bootbox.alert("Exámen ya fue agregado");
		return false;
	}

	var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
	r = tabled.rows.length;
	var newRow = tabled.insertRow(r);

	newRow.id=idRow;
	cell1 = newRow.insertCell(0),
	cell2 = newRow.insertCell(1),
	cell3 = newRow.insertCell(2),
	cell4 = newRow.insertCell(3),
	cell5 = newRow.insertCell(4),

	cell1.innerHTML = codref_producto;
	cell1.id="td_codref_pro";
	cell1.setAttribute("onclick","detRow('" + id_producto + "')");
	
	cell2.innerHTML = "<b>" + nom_producto + "</b>";
	cell2.id="td_nombre_pro";
	cell2.setAttribute("onclick","detRow('" + id_producto + "')");
	
	cell3.innerHTML = nomtipo_producto;
	cell3.id="td_tipo_pro";
	cell3.setAttribute("onclick","detRow('" + id_producto + "')");

	cell4.innerHTML = '<input type="hidden" name="tot_precProd" id="tot_precProd" value="'+precio+'">'+precio;
	cell4.id="td_prec_pro";
	cell4.setAttribute("onclick","detRow('" + id_producto + "')");
	cell4.className = "text-right";

	cell5.innerHTML = '<input type="hidden" name="item_idProducto" id="item_idProducto" value="'+id_producto+'_'+precio+'"><button type="button" class="delete btn-link" data-toggle="tooltip" data-placement="top" title="Elimar exámen" onclick="delRow(\''+idRow+'\');"><i class="glyphicon glyphicon-trash"></i></button>';
	cell5.id="td_acci_pro";
	cell5.className = "text-center";

	$('#btn-submit').prop("disabled", false);
	//$("#txtIdProducto").val('').trigger("change");
	if(id == ""){
		setTimeout(function(){tot_precio();}, 2);
	} else {
		var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
		r = tabled.rows.length;
		$("#nro_examen").text(r);
	}
}

function addRowPerfil(){
	if($("#cbx_id_perfil").val()=='' || $("#cbx_id_perfil").val()==null){
		bootbox.alert("Selecciona un Perfil");
		return false;
	}

	if($("#txtIdPlanTari").val()==''){
		bootbox.alert("Selecciona un Plan tarifario");
		return false;
	}
  
	var cmbid_plan = $("#txtIdPlanTari option:selected").val();
	var datosp = cmbid_plan.split("#");
	var id_plan = datosp[0];
	var check_prec = datosp[1];  
  
	$.ajax({
		url: "../../controller/ctrlProducto.php",
		type: "POST",
		dataType: "json",
		data: {
			accion: 'POST_SHOW_PRODUCTOPORPERFIL', id_perfil: $("#cbx_id_perfil").val()
		},
		success: function (result) {
			
		$(result).each(function (ii, oo) {

			var id_producto = oo.id_producto;
			var codref_producto = oo.codref_producto;
			var nomtipo_producto = oo.nomtipo_producto;
			var prec_sis = oo.prec_sis;
			var prec_parti =  oo.prec_parti;
			if(document.frmPaciente.txtIdPlanTari.value == "1"){
				precio = prec_sis;
			} else {
				if(check_prec == "t"){
					precio = prec_parti;
				} else {
					precio = "0.0000";
				}
			}

			idRow="tr"+id_producto;
				if (document.getElementById(idRow)){
				bootbox.alert("Exámen ya fue agregado");
				return false;
			}

			var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
			r = tabled.rows.length;
			var newRow = tabled.insertRow(r);

			newRow.id=idRow;
			cell1 = newRow.insertCell(0),
			cell2 = newRow.insertCell(1),
			cell3 = newRow.insertCell(2),
			cell4 = newRow.insertCell(3),
			cell5 = newRow.insertCell(4),

			cell1.innerHTML = codref_producto;
			cell1.id="td_codref_pro";
			cell1.setAttribute("onclick","detRow('" + id_producto + "')");
			cell2.innerHTML = oo.nom_producto;
			cell2.id="td_nombre_pro";
			cell2.setAttribute("onclick","detRow('" + id_producto + "')");
			cell3.innerHTML = nomtipo_producto;
			cell3.id="td_tipo_pro";
			cell3.setAttribute("onclick","detRow('" + id_producto + "')");

			cell4.innerHTML = '<input type="hidden" name="tot_precProd" id="tot_precProd" value="'+precio+'">'+precio;
			cell4.id="td_prec_pro";
			cell4.setAttribute("onclick","detRow('" + id_producto + "')");
			cell4.className = "text-right";

			cell5.innerHTML = '<input type="hidden" name="item_idProducto" id="item_idProducto" value="'+id_producto+'_'+precio+'"><button type="button" class="delete btn-link" data-toggle="tooltip" data-placement="top" title="Elimar exámen" onclick="delRow(\''+idRow+'\');"><i class="glyphicon glyphicon-trash"></i></button>';
			cell5.id="td_acci_pro";
			cell5.className = "text-center";		  
		});
		  
		$('#btn-submit').prop("disabled", false);
		//$("#txtIdProducto").val('').trigger("change");
		$("#cbx_id_perfil").val('');
		setTimeout(function(){tot_precio();}, 2);
	  }
	});
}

function poner_cero(input){
	if($("#"+input).val() == "") $("#"+input).val('0.00');
}

function tot_con_descuento(){
	var subtotal = validateNumber($("#totPrecProd").text());
	if(validateNumber($("#txtPorDescuentoMonto").val()) > 100){
		showMessage("El valor del porcentaje es mayor a 100", "error");	
		return false;
	}
	if (subtotal == 0){
		$("#txtDescuentoMonto").val('0.0000');
		$("#txtTotalMonto").val(subtotal.toFixed(4));
	} else {
		var descuento = validateNumber($("#txtPorDescuentoMonto").val());
		var tot_descuento =  (descuento * subtotal) / 100;
		$("#txtDescuentoMonto").val(tot_descuento.toFixed(4));
		var total = subtotal - tot_descuento;
		$("#txtTotalMonto").val(total.toFixed(4));
	}
}

function tot_con_descuento_manual(){
	var subtotal = validateNumber($("#totPrecProd").text());
	if(subtotal < validateNumber($("#txtDescuentoMonto").val())){
		showMessage("El valor del descuento es mayor al SUB TOTAL", "error");	
		return false;
	}
	if (subtotal == 0){
		$("#txtPorDescuentoMonto").val('0.00');
		$("#txtTotalMonto").val(subtotal.toFixed(4));
	} else {
		var descuento = validateNumber($("#txtDescuentoMonto").val());
		var total = subtotal - descuento;
		$("#txtTotalMonto").val(total.toFixed(4));
		var tot_descuento =  (descuento * 100 / subtotal);
		$("#txtPorDescuentoMonto").val(tot_descuento.toFixed(2));
	}
}

function tot_precio(){
  var tot = parseFloat("0.0000");
  $("input[name='tot_precProd']").each(function() {
    tot = parseFloat($(this).val()) + tot;
  });
  setTimeout(function(){
		$("#totPrecProd").text(tot.toFixed(4));
		$("#txtPorDescuentoMonto").val("0");
		$("#txtDescuentoMonto").val("0.0000");
		$("#txtTotalMonto").val(tot.toFixed(4));
		var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
		r = tabled.rows.length;
		$("#nro_examen").text(r);
	}, 2);
}

function delRow(idpro){
  bootbox.confirm({
    title: "Mensaje",
    message: "Esta seguro de quitar el Exámen?",
    buttons: {
      cancel: {
        label: '<i class="fa fa-times"></i> Cancelar'
      },
      confirm: {
        label: '<i class="fa fa-check"></i> Aceptar'
      }
    },
    callback: function (result) {
      if (result){
        $("#"+idpro).remove();
        setTimeout(function(){desabilita_btn_submit();}, 2);
      }
    }
  });

  var newOption = '<tr><td colspan="4">Seleccione un exámen</td></tr>';
  $("#det-producto").html(newOption);
}

function desabilita_btn_submit(){
  var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
  r = tabled.rows.length;
  if(r >= 1){
    $('#btn-submit').prop("disabled", false);
    setTimeout(function(){tot_precio();}, 2);
  } else {
    $('#btn-submit').prop("disabled", true);
    setTimeout(function(){
		$("#totPrecProd").text("0.0000");
		$("#nro_examen").text("0");
		$("#txtPorDescuentoMonto").val("0");
		$("#txtDescuentoMonto").val("0.0000");
		$("#txtTotalMonto").val("0.0000");
	}, 2);
  }
}

function change_tipoatencion() {
  if($("#txtIdTipAtencion").val() == "2"){
    $("#txtNroRefDep").val('');
    $("#txtIdDepRef").val('').trigger("change");
    $('#txtIdDepRef').prop("disabled", false);
    $('#txtNroRefDep').prop("readonly", false);
	$('#txtAnioRefDep').prop("readonly", false);
    $("#txtIdDepRef").select2('open');
    $("#txtIdServicio").val('').trigger("change");
    $('#txtIdServicio').prop("disabled", false);
  } else if($("#txtIdTipAtencion").val() == "4"){
    $("#txtNroRefDep").val('');
	$("#txtAnioRefDep").val('');
    $("#txtIdDepRef").val('').trigger("change");
    $('#txtIdDepRef').prop("disabled", true);
    $('#txtNroRefDep').prop("readonly", true);
	$('#txtAnioRefDep').prop("readonly", true);
    $("#txtIdServicio").val('').trigger("change");
    $('#txtIdServicio').prop("disabled", false);
    $("#txtIdServicio").select2('open');
  } else {
    $("#txtNroRefDep").val('');
	$("#txtAnioRefDep").val('');
    $("#txtIdDepRef").val('').trigger("change");
    $('#txtIdDepRef').prop("disabled", true);
    $('#txtNroRefDep').prop("readonly", true);
	$('#txtAnioRefDep').prop("readonly", true);
    $("#txtIdServicio").val('').trigger("change");
    $('#txtIdServicio').prop("disabled", true);
  }
}

$(function() {

  jQuery('#txtNroDocPac').keypress(function (tecla) {
    var idTipDocPer = $("#txtIdTipDocPac").val();
    if (idTipDocPer == "1") {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
      return false;
    } else {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode != 0))//(Numeros y letras)(0=borrar)
      return false;
    }
  });
  jQuery('#txtPriApePac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtSegApePac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtNomPac').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });

  jQuery('#txtNroTelFijoPac').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });
  jQuery('#txtNroTelfMovilPac').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });
  
  
  jQuery('#txtNroDocSoli').keypress(function (tecla) {
    var idTipDocPer = $("#txtIdTipDocSoli").val();
    if (idTipDocPer == "1") {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
      return false;
    } else {
      if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode != 0))//(Numeros y letras)(0=borrar)
      return false;
    }
  });
  jQuery('#txtPriApeSoli').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtSegApeSoli').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtNomSoli').keypress(function (tecla) {
    if ((tecla.charCode < 97 || tecla.charCode > 122) && (tecla.charCode < 65 || tecla.charCode > 90) && (tecla.charCode != 45) && (tecla.charCode <= 192 || tecla.charCode >= 255) && (tecla.charCode != 0) && (tecla.charCode != 32) && (tecla.charCode != 39))
    return false;
  });
  jQuery('#txtNroTelFijoSoli').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });
  jQuery('#txtNroTelMovilSoli').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });
  
  
  jQuery('#txtNroRefDep').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });
  jQuery('#txtAnioRefDep').keypress(function (tecla) {
    if ((tecla.charCode < 48 || tecla.charCode > 57) && (tecla.charCode != 0))//(Solo Numeros)(0=borrar)
    return false;
  });

	$('[name="txtIdGestante"]').change(function(){
		if ($(this).is(':checked')) {
			$("#txtEdadGest").prop('disabled', false);
			$("#txtFechaParto").prop('disabled', false);		
			setTimeout(function(){$('#txtEdadGest').trigger('focus');}, 2);
		} else {
			$("#txtEdadGest").val('');
			$("#txtFechaParto").val('');
			$("#txtEdadGest").prop('disabled', true);
			$("#txtFechaParto").prop('disabled', true);
		};
	});
	
	$('#txtIdProducto').on("change", function(e) { 
		// what you would like to happen
		addRow('');
	});

});

function validForm(acc) {
	$('#btn-submit').prop("disabled", true);
	var msg = "";
	var sw = true;

	var txtIdTipDoc = $('#txtIdTipDocPac').val();
	var txtIdPaisNacPac = $('#txtIdPaisNacPac').val();
	var txtIdEtniaPac = $('#txtIdEtniaPac').val();
	var txtNroDoc = $('#txtNroDocPac').val();
	var txtNroDocLn = txtNroDoc.length;
	var txtNroHC = $('#txtNroHCPac').val();
	var txtNomPac = $('#txtNomPac').val();
	var txtPriApePac = $('#txtPriApePac').val();
	var txtSegApePac = $('#txtSegApePac').val();
	var txtIdSexoPac = $('#txtIdSexoPac').val();
	var txtFecNacPac = $('#txtFecNacPac').val();
	var edad_pac = $('#txtEdadPac').val();
	var txtNroTelMovilPac = $('#txtNroTelMovilPac').val();
	var txtNroTelFijoPac = $('#txtNroTelFijoPac').val();
	var txtUBIGEOPac = $('#txtUBIGEOPac').val();
	if(txtUBIGEOPac === null){txtUBIGEOPac = "";}
	
	var txtIdTipDocSoli = $('#txtIdTipDocSoli').val();
	var txtIdPaisNacSoli = $('#txtIdPaisNacSoli').val();
	var txtNroDocSoli = $('#txtNroDocSoli').val();
	var txtNroDocLnSoli = txtNroDocSoli.length;
	var txtNomSoli = $('#txtNomSoli').val();
	var txtPriApeSoli = $('#txtPriApeSoli').val();
	var txtSegApeSoli = $('#txtSegApeSoli').val();
	var txtNroTelFijoSoli = $('#txtNroTelFijoSoli').val();
	var txtNroTelMovilSoli = $('#txtNroTelMovilSoli').val();
	var txtEmailSoli = $('#txtEmailSoli').val();
	var txtIdParenSoli = $('#txtIdParenSoli').val();

	var txtIdPlanTari = $('#txtIdPlanTari').val();
	var txtNroRefAtencion = $('#txtNroRefAtencion').val();
	var txtFechaAten = $('#txtFechaAten').val();
	var txtHoraAten = $('#txtHoraAten').val();
	var txtIdTipAtencion = $('#txtIdTipAtencion').val();
	var txtNroRefDep = $('#txtNroRefDep').val();
	var txtAnioRefDep = $('#txtAnioRefDep').val();
	var txt_EdadGest = $('#txtEdadGest').val();
	var txt_FechaParto = $('#txtFechaParto').val();

	var subtotal = validateNumber($("#totPrecProd").text());
	var txtTotalMonto = validateNumber($('#txtTotalMonto').val());
	var txtDescuentoMonto = validateNumber($('#txtDescuentoMonto').val());
	var txtPorDescuentoMonto = validateNumber($('#txtPorDescuentoMonto').val());

	// VALIDACIONES PARA LABORATORIO REFERENCIAL
	var txtValidacionEdad = $('#txtValidacionEdad').val();
	var txtValidacionSexo = $('#txtValidacionSexo').val();
	var txtPuedeAtenderse = $('#txtPuedeAtenderse').val();

	if (txtValidacionEdad == "0") {
		msg += "El paciente debe tener entre 40 y 65 años para este tipo de atención<br/>";
		sw = false;
	}

	if (txtValidacionSexo == "0") {
		msg += "Este examen solo está disponible para pacientes varones<br/>";
		sw = false;
	}

	if (txtPuedeAtenderse == "0") {
		msg += "El paciente tiene un resultado validado hace menos de 1 año. Debe esperar para volver a atenderse<br/>";
		sw = false;
	}

	if (txtIdTipDoc == "1") {
		if (txtNroDocLn != 8) { msg += "Ingrese el Nro. de documento del Paciente correctamente<br/>"; sw = false;}
	} else {
		if (txtNroDocLn <= 5) { msg += "Ingrese el Nro. de documento del Paciente correctamente<br/>";sw = false;}
	}
	
	if (txtIdPaisNacPac == "") { msg += "Seleccione el PAIS de nacimiento del Paciente<br/>"; sw = false;}
	//if (txtIdEtniaPac == "") { msg += "Seleccione la ETNIA del Paciente<br/>"; sw = false;}
	if (txtNroHC == "") { msg += "Ingrese el Nro. de Historia Clínica del Paciente<br/>"; sw = false;}
	if (txtNomPac == "") { msg += "Ingrese el nombre del Paciente<br/>"; sw = false;}
	if (txtPriApePac == "") { msg += "Ingrese el primer apellido del Paciente<br/>"; sw = false;}
	if (txtIdSexoPac == "") { msg += "Seleccione SEXO del paciente<br/>"; sw = false;}
	if(txtFecNacPac == "" && edad_pac == ""){
		msg += "Ingrese FECHA DE NACIMIENTO o EDAD del paciente<br/>"; sw = false;
	}
	
	
	/*if (txtNroTelFijoPac == "") { 
		if (txtNroTelMovilPac == "") { msg += "Ingrese el Nro. de Telefono fijo o móvil del paciente<br/>"; sw = false;}
	}*/
	//if (txtUBIGEOPac == "") { msg += "Seleccione Distrito UBIGEO<br/>"; sw = false;}
	
	
	if (txtNroDocLnSoli > 0) {
		if (txtIdTipDocSoli == "1") {
			if (txtNroDocLnSoli != 8) { msg += "Ingrese el Nro. de documento del apoderado correctamente<br/>"; sw = false;}
		} else {
			if (txtNroDocLnSoli <= 5) { msg += "Ingrese el Nro. de documento del apoderado correctamente<br/>";sw = false;}
		}
		
		if (txtIdPaisNacSoli == "") { msg += "Seleccione el PAIS de nacimiento del apoderado<br/>"; sw = false;}
		if (txtNomSoli == "") { msg += "Ingrese el nombre del apoderado<br/>"; sw = false;}
		if (txtPriApeSoli == "") { msg += "Ingrese el primer apellido del apoderado<br/>"; sw = false;}
		if (txtNroTelFijoSoli == "") { 
			if (txtNroTelMovilSoli == "") { msg += "Ingrese el Nro. de Telefono fijo o móvil del apoderado<br/>"; sw = false;}
		}
		if (txtIdParenSoli == "") { msg += "Seleccione el PARENTESCO del apoderado con el paciente<br/>"; sw = false;}
	}

	if (txtIdPlanTari == "") { msg += "Seleccione Plan Tarifario<br/>"; sw = false;}
	if (txtFechaAten == "") { msg += "Ingrese la fecha de atención<br/>"; sw = false;}
	if (txtHoraAten == "") {msg += "Ingrese la hora de atención<br/>";sw = false;}
	if (txtNroRefAtencion == "") { msg += "Ingrese el número de atención<br/>"; sw = false;}
	if (txtIdTipAtencion == "") { msg += "Ingrese el origen de la atención<br/>"; sw = false;}
	
	if(txtNroRefDep != ""){
		if(txtAnioRefDep == ""){ msg += "Ingrese el año de la referencia<br/>"; sw = false;}
	}
	
	if ($("input[name='txtIdGestante']").is(':checked')) {
		//if(txt_EdadGest == ""){ msg += "Ingrese la edad gestacional<br/>"; sw = false;}
		//if(txt_FechaParto == ""){ msg += "Ingrese la fecha probable de parto<br/>"; sw = false;}
		if(txtIdSexoPac == "1"){ msg += "El paciente de sexo MASCULINO, no puede ser gestante<br/>"; sw = false;}
    }
	
	if (txtDescuentoMonto > subtotal) { msg += "El DESCUENTO no debe ser mayor al VALOR SUB TOTAL<br/>"; sw = false;}
	if (txtPorDescuentoMonto > 100) { msg += "El PORCENTAJE DESCUENTO no debe ser mayor a 100%<br/>"; sw = false;}
	
	var tabled = document.getElementById("tblDet").getElementsByTagName('tbody')[0];
	r = tabled.rows.length;
	if (r<=0){ msg += "No existen exámenes solicitados"; sw = false;}

	if (sw == false) {
		bootbox.alert(msg);
		$('#btn-submit').prop("disabled", false);
		return sw;
	} else {
		save_atencion(acc);
	}
	return false;
}

function save_atencion(acc) {
		var fec_nac = $('#txtFecNacPac').val();
		var edad_pac = $('#txtEdadPac').val();
		if(fec_nac == "" && edad_pac != ""){
			fec_nac = fechaDeNacimientoDesdeEdad(edad_pac);
		}
		
		var txtUBIGEOPac = $('#txtUBIGEOPac').val();
		if(txtUBIGEOPac === null){txtUBIGEOPac = "";}
		  
        var id_av = "";
        if($("#txtIdAvDirPac").val() != "") {
          var cmbid_av = $("#txtIdAvDirPac option:selected").val();
          var datos = cmbid_av.split("#");
          id_av = datos[0];
        }

        var id_po = "";
        if($("#txtIdPoblaDirPac").val() != "") {
          var cmbid_po = $("#txtIdPoblaDirPac option:selected").val();
          var datospo = cmbid_po.split("#");
          id_po = datospo[0];
        }
		
		var chk_gestante = 0;
		var txt_EdadGest = "";
		var txt_FechaParto = "";
        if ($("input[name='txtIdGestante']").is(':checked')) {
			chk_gestante = 1;
			txt_EdadGest = $('#txtEdadGest').val()
			txt_FechaParto = $('#txtFechaParto').val()
        }

        var urge = 0;
        if ($("input[name='txtAtenUrgente']").is(':checked')) {
          urge = 1;
        }
		
		var persalud = 0;
		if ($("input[name='txtPersonalSalud']").is(':checked')) {
          persalud = 1;
        }
		
        var cmbid_plan = $("#txtIdPlanTari option:selected").val();
        var datosp = cmbid_plan.split("#");
        var id_plan = datosp[0];

        var selectednumbers = [];
        $("input[name='item_idProducto']").each(function() {
          selectednumbers.push($(this).val());
        });

        $.ajax( {
          type: 'POST',
          url: '../../controller/ctrlAtencion.php',
          data: "txtIdPer=" + $('#txtIdPer').val() + "&txtNroHC=" + $('#txtNroHCPac').val() + "&txtIdTipDoc=" + $('#txtIdTipDocPac').val() + "&txtNroDoc=" + $('#txtNroDocPac').val() + "&txtIdPaisNacPac=" + $('#txtIdPaisNacPac').val() + "&txtIdEtniaPac=" + $('#txtIdEtniaPac').val() + "&txtIdSexoPac=" + $('#txtIdSexoPac').val() + "&txtFecNacPac=" + fec_nac + "&txtNomPac=" + $('#txtNomPac').val() + "&txtPriApePac=" + $('#txtPriApePac').val() + "&txtSegApePac=" + $('#txtSegApePac').val() + "&txtNroTelFijoPac=" + $('#txtNroTelFijoPac').val() + "&txtNroTelMovilPac=" + $('#txtNroTelMovilPac').val() + "&txtEmailPac=" + $('#txtEmailPac').val()
          + "&txtUBIGEOPac=" + txtUBIGEOPac + "&txtIdAvDirPac=" + id_av + "&txtNomAvDirPac=" + $('#txtNomAvDirPac').val() + "&txtNroDirPac=" + $('#txtNroDirPac').val() + "&txtIntDirPac=" + $('#txtIntDirPac').val() + "&txtDptoDirPac=" + $('#txtDptoDirPac').val() + "&txtMzDirPac=" + $('#txtMzDirPac').val() + "&txtLtDirPac=" + $('#txtLtDirPac').val() + "&txtIdPoblaDirPac=" + id_po + "&txtNomPoblaDirPac=" + $('#txtNomPoblaDirPac').val() + "&txtDirPac=" + $('#txtDirPac').val() + "&txtDirRefPac=" + $('#txtDirRefPac').val()
          + "&txtIdSoli=" + $('#txtIdSoli').val() + "&txtIdTipDocSoli=" + $('#txtIdTipDocSoli').val() + "&txtNroDocSoli=" + $('#txtNroDocSoli').val() + "&txtIdPaisNacSoli=" + $('#txtIdPaisNacSoli').val() + "&txtIdSexoSoli=" + $('#txtIdSexoSoli').val() + "&txtFecNacSoli=" + $('#txtFecNacSoli').val() + "&txtNomSoli=" + $('#txtNomSoli').val() + "&txtPriApeSoli=" + $('#txtPriApeSoli').val() + "&txtSegApeSoli=" + $('#txtSegApeSoli').val() + "&txtNroTelFijoSoli=" + $('#txtNroTelFijoSoli').val() + "&txtNroTelMovilSoli=" + $('#txtNroTelMovilSoli').val() + "&txtEmailSoli=" + $('#txtEmailSoli').val()
          + "&txtIdPlanTari=" + id_plan + "&txtFechaAten=" + $('#txtFechaAten').val() + "&txtNroRefAtencion=" + $('#txtNroRefAtencion').val() + "&txtIdTipAtencion=" + $('#txtIdTipAtencion').val() + "&txtIdServicio=" + $('#txtIdServicio').val() + "&txtIdDepRef=" + $('#txtIdDepRef').val() + "&txtNroRefDep=" + $('#txtNroRefDep').val() + "&txtAnioRefDep=" + $('#txtAnioRefDep').val() + "&txtFechaPedido=" + $('#txtFechaPedido').val() + "&txtNombreMedico=" + $('#txtNombreMedico').val() + "&txtAtenUrgente=" + urge + "&txtPersonalSalud=" + persalud
		  + "&txtIdGestante=" + chk_gestante + "&txtEdadGest=" + txt_EdadGest + "&txtFechaParto=" + txt_FechaParto + "&txtPesoPac=" + $('#txtPesoPac').val() + "&txtTallaPac=" + $('#txtTallaPac').val()
          + "&txtIdProducto=" + selectednumbers + "&txtIdParenSoli=" + $('#txtIdParenSoli').val() + "&txtSubTotal=" + $("#totPrecProd").text() + "&txtPorDescuentoMonto=" + $('#txtPorDescuentoMonto').val() + "&txtDescuentoMonto=" + $('#txtDescuentoMonto').val() + "&txtTotalMonto=" + $('#txtTotalMonto').val() + "&txtIdAtencion=" + $('#txtIdAtencion').val()
		  + "&txtNroAteManual=" + $('#txtNroAteManual').val() + "&txtFechaTomaMuestra=" + $('#txtFechaTomaMuestra').val()
          + "&txtTipIng=S&accion=POST_ADD_REGSOLICITUDLABREF",
          success: function(data) {
            var tmsg = data.substring(0, 2);
            var lmsg = data.length;
            var msg = data.substring(3, lmsg);
            //console.log(tmsg);
            if(tmsg == "OK"){
				if(acc == "E"){
				  /*bootbox.alert({
					message: "El registro se guardo correctamente.",
					callback: function () {
					  window.location = './main_principalsoli.php';
					}
				  });*/
				  open_datoslamina(msg);
				} else {
					open_datoslamina(msg);
				}
            } else {
              $('#btn-submit').prop("disabled", false);
			  showMessage(msg, "error");
              return false;
            }
          }
        });
}

function open_datoslamina(id){
  $('#mostrar_datospac').modal('show');
  $.ajax({
    url: '../../controller/ctrlLab.php',
    type: 'POST',
    data: 'accion=GET_SHOW_DATOSATENCION&opt=F&id=' + id,
    success: function(data){
      $('#mostrar_datospac').html(data);
    }
  });
}

function reg_resultado(idatencion) {
	window.location = '../lab/main_regresultadoprod2.php?nroSoli='+idatencion+'&ori=LR';
}

function back() {
  window.location = './main_principalsoli.php';
}

function recargar_pag_reg() {
  window.location = './main_regsolicitudlabref.php';
}

function expor_atenciones_hoy(fecha) {
    var urlwindow = "pdf_repatencion.php?fecIni=" + fecha + "&fecFin=" + fecha;
    day = new Date();
    id = day.getTime();
    Xpos = (screen.width / 2) - 390;
    Ypos = (screen.height / 2) - 300;
    eval("page" + id + " = window.open(urlwindow, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=780,height=600,left = '+Xpos+',top = '+Ypos);");
}

function show_datos_adicionales_pac() {
  if($('#show-datos-adicionales-pac').text() == "Mostrar"){
    $('#datos-adicionales-pac').show();
    $('#show-datos-adicionales-pac').text("Ocultar");
  } else {
    $('#datos-adicionales-pac').hide();
    $('#show-datos-adicionales-pac').text("Mostrar");
  }
}

function show_datos_adicionales_aten() {
  if($('#show-datos-adicionales-aten').text() == "Mostrar"){
    $('#datos-adicionales-aten').show();
    $('#show-datos-adicionales-aten').text("Ocultar");
  } else {
    $('#datos-adicionales-aten').hide();
    $('#show-datos-adicionales-aten').text("Mostrar");
  }
}

function show_datos_soli() {
  if($('#show-datos-soli').text() == "Mostrar"){
    $('#datos-soli').show();
    $('#show-datos-soli').text("Ocultar");
    $('#txtNroDocSoliT').trigger('focus');
  } else {
    $('#datos-soli').hide();
    $('#show-datos-soli').text("Mostrar");
  }
}