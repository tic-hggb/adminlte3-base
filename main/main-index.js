$(document).ready(function () {
	const tableUsers = $('#tusers').DataTable({
		'columns': [
			null,
			null,
			null,
			null,
			null,
			null
		],
		'order': [[1, 'asc'], [2, 'asc'], [0, 'asc']],
		'pageLength': 5,
		'lengthChange': false,
		'buttons': [],
		serverSide: true,
		ajax: {
			url: 'main/ajax.getServerUsers.php',
			type: 'GET',
			length: 5
		}
	});

	$.ajax({
		url: 'main/ajax.getProduction.php',
		method: 'POST',
		dataType: 'json',
		data: { planta: 1 },
		success: function (data) {
			const line = new Morris.Line({
				element: 'line-chart',
				data: data,
				xkey: 'mes',
				ykeys: ['cantidad'],
				labels: ['Cantidad'],
				lineColors: ['#efefef'],
				dataLabelsColor: '#fff',
				lineWidth: 2,
				hideHover: 'auto',
				gridTextColor: '#fff',
				gridStrokeWidth: 0.4,
				pointSize: 4,
				pointStrokeColors: ['#efefef'],
				gridLineColor: '#efefef',
				gridTextFamily: 'Open Sans',
				gridTextSize: 10,
				parseTime: false,
				resize: true,
				hoverCallback: function (index, options, content, row) {
					return row.mes + ': ' + number_format(row.cantidad, 0, '', '.');
				},
			});
		}
	});

	$.ajax({
		url: 'main/ajax.getProduction.php',
		method: 'POST',
		dataType: 'json',
		data: { planta: 2 },
		success: function (data) {
			const line = new Morris.Line({
				element: 'line-chart-nm',
				data: data,
				xkey: 'mes',
				ykeys: ['cantidad'],
				labels: ['Cantidad'],
				lineColors: ['#efefef'],
				dataLabelsColor: '#fff',
				lineWidth: 2,
				hideHover: 'auto',
				gridTextColor: '#fff',
				gridStrokeWidth: 0.4,
				pointSize: 4,
				pointStrokeColors: ['#efefef'],
				gridLineColor: '#efefef',
				gridTextFamily: 'Open Sans',
				gridTextSize: 10,
				parseTime: false,
				resize: true,
				hoverCallback: function (index, options, content, row) {
					return row.mes + ': ' + number_format(row.cantidad, 0, '', '.');
				},
			});
		}
	});
});
