var dtables = {
    dTable: null,
    dTable1: null
};
var echarte = null;
var grafica = null;


function datos_ultimo_levey(opt){
  $.ajax({
    url: "../../controller/ctrlLevey.php",
    type: "POST",
    data: {
      accion: 'GET_SHOW_LEVEYULTIMOPORIDCONTROLANDIDDEPANDANIOANDMES', anio: $("#txtBusAnio").val(), mes: $("#txtBusMes").val(), id_control_calidad: opt, id_dependencia: $("#txt_id_dependencia").val()
    },
    success: function (registro) {
		$("#datos_levey_" + opt).html(registro);
    }
  });
}

function filtrar(opt){
	if($("#txt_id_levey_" + opt).val()){
		//alert(opt);
		$.ajax({
		  url: "../../controller/ctrlLevey.php",
		  dataType: "json",
		  type: "post",
		  data: {
			accion: 'GRAFICACOLPATOLO',
			anio: $("#txtBusAnio").val(),
			mes: $("#txtBusMes").val(),
			id_control_calidad: opt,
			id_dependencia: $("#txt_id_dependencia").val()
		  },
		  processData: true,
		  success: function (data) {
			  if(data.rowscabecera.valor_min == 0){
				  $("#txt_fecha_" + opt).prop("disabled", true);
				  $("#txt_valor_" + opt).prop("disabled", true);
				  $("#btn_registra_" + opt).prop("disabled", true);
			  } else {
				  $("#txt_fecha_" + opt).prop("disabled", false);
				  $("#txt_valor_" + opt).prop("disabled", false);			  
				  $("#btn_registra_" + opt).prop("disabled", false);
			  }				  
			echarte = echarts.init(document.getElementById('echart_resumen_' + opt));
			grafica = {
			  title: {
				text: data.rowscabecera.nombre_tipo+': ' + data.rowscabecera.nombre_control
			  },
			  tooltip: {
				trigger: 'axis'
			  },
			  legend: {},
			  toolbox: {
				show: true,
				feature: {
				  //dataZoom: {
					//yAxisIndex: 'none'
				  //},
				  dataView: { readOnly: false },
				  magicType: { type: ['line', 'bar'] },
				  restore: {show: false},
				  saveAsImage: {}
				}
			  },
				grid: {
					//top:    60,
					//bottom: 60,
					left:   '8%',
					right:  '15%',
				},
			  xAxis: {
				type: 'category',
				boundaryGap: false,
				data: data.rows
			  },
			  yAxis: {
				type: 'value',
				min: data.rowscabecera.valor_min,
				max: data.rowscabecera.valor_max,
				axisLabel: {
				  formatter: '{value}'
				}
			  },
			  series: [
				{
				  //name: 'Highest',
				  type: 'line',
				  data: data.rowsvalor,
				  markPoint: {
					data: [
					  { type: 'max', name: 'Max' },
					  { type: 'min', name: 'Min' }
					]
				  },
				  markLine: {
					data: [
					  {
						name: 'MEDIA',
						yAxis: data.rowscabecera.media,
						label: {
						  formatter: 'MED (' + data.rowscabecera.media + ')',
						  coord: [0, 0]
						}
					  },
					  {
						name: '+1DS',
						yAxis: data.rowscabecera.x_1ds_posi,
						label: {
						  formatter: '+1DS (' + data.rowscabecera.x_1ds_posi + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#008080'
						}
					  },
					  {
						name: '+2DS',
						yAxis: data.rowscabecera.x_2ds_posi,
						label: {
						  formatter: '+2DS (' + data.rowscabecera.x_2ds_posi + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#FFD700'
						}
					  },
					  {
						name: '+3DS',
						yAxis: data.rowscabecera.x_3ds_posi,
						label: {
						  formatter: '+3DS (' + data.rowscabecera.x_3ds_posi + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#FF0000'
						}
					  },
					  {
						name: '-1DS',
						yAxis: data.rowscabecera.x_1ds_nega,
						label: {
						  formatter: '-1DS (' + data.rowscabecera.x_1ds_nega + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#008080'
						}
					  },
					  {
						name: '-2DS',
						yAxis: data.rowscabecera.x_2ds_nega,
						label: {
						  formatter: '-2DS (' + data.rowscabecera.x_2ds_nega + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#FFD700'
						}
					  },
					  {
						name: '-3DS',
						yAxis: data.rowscabecera.x_3ds_nega,
						label: {
						  formatter: '-3DS (' + data.rowscabecera.x_3ds_nega + ')',
						  coord: [0, 0]
						},
						lineStyle: {
						  color: '#FF0000'
						}
					  }
					]
				  }
				}
			  ]
			};
			echarte.setOption(grafica);
		  }
		});
	}
}

function initDataTable(opt) {
	if($("#txt_id_levey_" + opt).val()){
		dtables.dTable1 = $('#tbl_' + opt).DataTable({
			bLengthChange: true, //Paginado 10,20,50 o 100 [18,50]
			lengthMenu: [
				[10, 50, -1],
				[10, 50, 'All'],
			],
			pageLength: 10,
			bProcessing: true,
			bServerSide: true,
			bJQueryUI: false,
			responsive: true,
			bInfo: true,
			bFilter: false,
			language: {
				url: "../../assets/plugins/datatables/Spanish.json",
				lengthMenu: '_MENU_ registros por p\xe1gina',
				search: '<i class="glyphicon glyphicon-search"></i>',
				paginate: {
					previous: '<i class="glyphicon glyphicon-arrow-left"></i>',
					next: '<i class="glyphicon glyphicon-arrow-right"></i>'
				}
			},
			sAjaxSource: "tbl_col_patolo.php", // Load Data
			sServerMethod: "POST",
			fnServerParams: function (aoData){
				aoData.push({"name": "anio", "value": $("#txtBusAnio").val()});
				aoData.push({"name": "mes", "value": $("#txtBusMes").val()});
				aoData.push({"name": "id_control_calidad", "value": opt});
				aoData.push({"name": "id_dependencia", "value": $("#txt_id_dependencia").val()});
			},
			columnDefs: [
				{"orderable": true, "targets": 0, "searchable": true, "class": "small text-center"},
				{"orderable": false, "targets": 1, "searchable": false, "class": "small"},
				{"orderable": false, "targets": 2, "searchable": false, "class": "small text-right"},
				{"orderable": false, "targets": 3, "searchable": false, "class": "small text-right"},
				{"orderable": false, "targets": 4, "searchable": false, "class": "small text-center"}
			]
		});
	}
}


function initDataTableHema(opt) {
	if($("#txt_id_levey_" + opt).val()){
		dtables.dTable1 = $('#tbl_' + opt).DataTable({
			bLengthChange: true, //Paginado 10,20,50 o 100 [18,50]
			lengthMenu: [
				[10, 50, -1],
				[10, 50, 'All'],
			],
			pageLength: 10,
			bProcessing: true,
			bServerSide: true,
			bJQueryUI: false,
			responsive: true,
			bInfo: true,
			bFilter: false,
			language: {
				url: "../../assets/plugins/datatables/Spanish.json",
				lengthMenu: '_MENU_ registros por p\xe1gina',
				search: '<i class="glyphicon glyphicon-search"></i>',
				paginate: {
					previous: '<i class="glyphicon glyphicon-arrow-left"></i>',
					next: '<i class="glyphicon glyphicon-arrow-right"></i>'
				}
			},
			sAjaxSource: "tbl_hematologia.php", // Load Data
			sServerMethod: "POST",
			fnServerParams: function (aoData){
				aoData.push({"name": "anio", "value": $("#txtBusAnio").val()});
				aoData.push({"name": "mes", "value": $("#txtBusMes").val()});
				aoData.push({"name": "id_control_calidad", "value": opt});
				aoData.push({"name": "id_dependencia", "value": $("#txt_id_dependencia").val()});
			},
			columnDefs: [
				{"orderable": true, "targets": 0, "searchable": true, "class": "small text-center"},
				{"orderable": false, "targets": 1, "searchable": false, "class": "small"},
				{"orderable": false, "targets": 2, "searchable": false, "class": "small text-right"},
				{"orderable": false, "targets": 3, "searchable": false, "class": "small text-center"}
			]
		});
	}
}

function expor_datos(opt) {
	var id_dependencia = $("#txt_id_dependencia").val();
    var anio = $("#txtBusAnio").val();
    var mes= $("#txtBusMes").val();
		
	window.location = "xls_reporte_detallado_dia.php?id_dependencia=" + id_dependencia + "&anio="+ anio + "&mes=" + mes + "&opt=" + opt;

}