$(document).ready(function () {
	var tableBlocks = $('#tbhoras').DataTable({
		'columns': [
			null,
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
					columns: [0, 1, 2, 3, 4, 5, 6, 7]
				}
			}
		],
		serverSide: true,
		ajax: {
			url: 'box/ajax.getServerBlocks.php',
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
		tableBlocks.ajax.url('box/ajax.getServerBlocks.php?idate=' + $('#iNdatei').val() + '&idatet=' + $('#iNdatet').val() + '&iestab=' + $('#iNestab').val() + '&ipiso=' + $('#iNpiso').val() + '&ibox=' + $('#iNbox').val()).load();
	});

	$('#iNestab').change(function () {
		$('#iNpiso').html('').append('<option value="">Seleccione un lugar</option>');
		$('#iNbox').html('').append('<option value="">Seleccione un box</option>');
		$('#gpiso, #gbox').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#iNpiso').html('').append('<option value="">Cargando lugares...</option>');

			$.ajax({
				type: "POST",
				url: "box/ajax.getLugares.php",
				dataType: 'json',
				data: {est: $(this).val()}
			}).done(function (data) {
				$('#iNpiso').html('').append('<option value="">Seleccione un lugar</option>');

				$.each(data, function (k, v) {
					$('#iNpiso').append(
						$('<option></option>').val(v.lug_id).html(v.lug_nombre)
					);
				});
			});
		}
	});

	$('#iNpiso').change(function () {
		$('#iNbox').html('').append('<option value="">Cargando boxes...</option>');
		$('#gbox').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "box/ajax.getBoxes.php",
			dataType: 'json',
			data: {id: $(this).val()}
		}).done(function (data) {
			$('#iNbox').html('').append('<option value="">Seleccione un box</option>');

			$.each(data, function (k, v) {
				$('#iNbox').append(
					$('<option></option>').val(v.box_id).html(v.box_pasillo + ' - ' + v.box_numero)
				);
			});
		});
	});

	$('#iNestab, #iNpiso, #iNbox').change(function () {
		var idn = $(this).attr('id').split('N');

		if ($.trim($(this).val()) !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});
});