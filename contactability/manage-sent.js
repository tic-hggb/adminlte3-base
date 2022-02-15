$(document).ready(function () {
	var tableSms = $('#tsms').DataTable({
		'columns': [
			null,
			null,
			null,
			null,
			null,
			null,
			null
		],
		'buttons': [
			{
				extend: 'excel',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				}
			}
		],
		serverSide: true,
		ajax: {
			url: 'contactability/ajax.getServerSent.php',
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
			endDate: '+0d'
		});
	});

	$('#btnsubmit').click(function () {
		tableSms.ajax.url('contactability/ajax.getServerSent.php?idate=' + $('#iNdatei').val() + '&idatet=' + $('#iNdatet').val()).load();
	});
});