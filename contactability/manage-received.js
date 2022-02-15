$(document).ready(function () {
	var tableSms = $('#tsms').DataTable({
		'columns': [
			{'orderable': false},
			null,
			null,
			null,
			null
		],
		'order': [4, 'asc'],
		'buttons': [
			{
				extend: 'excel',
				exportOptions: {
					columns: [0, 1, 2, 3]
				}
			}
		],
		serverSide: true,
		ajax: {
			url: 'contactability/ajax.getServerReceived.php',
			type: 'GET',
			length: 20,
			data: {date_i: $('#iNdatei').val(), date_t: $('#iNdatet').val()}
		}
	});

	$('#submitLoader').css('display', 'none');

	$('#gdate .input-daterange').each(function () {
		$(this).datepicker({
			startView: 0,
			minViewMode: 0,
			endDate: '-1d'
		});
	});

	$('#btnsubmit').click(function () {
		tableSms.ajax.url('contactability/ajax.getServerReceived.php?idate=' + $('#iNdatei').val() + '&idatet=' + $('#iNdatet').val()).load();
	});
});