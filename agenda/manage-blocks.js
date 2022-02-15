$(document).ready(function () {
	var tBlocks = $('#tblocks').DataTable({
		columns: [
			null,
			null,
			{width: '120px', className: 'text-center'},
			{width: '120px', className: 'text-center'},
			null,
			null,
			null,
			{orderable: false, width: '30px', className: 'text-center'}],
		order: [[1, 'asc']],
		buttons: [
			{
				extend: 'excel',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5, 6]
				}
			}
		]
	});

	$('#submitLoader').css('display', 'none');

	$(document).on('focusin', '#iNyear', function () {
		$(this).prop('readonly', true);
	});
	$(document).on('focusout', '#iNyear', function () {
		$(this).prop('readonly', false);
	});

	$('#iNyear').datepicker({
		startView: 2,
		minViewMode: 2
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			$('#gyear').removeClass('has-error').addClass('has-success');
			$('#iconyear').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$('#iNperiodo, #iNplanta, #iNcr, #iNserv, #iNestab').change(function () {
		var idn = $(this).attr('id').split('N');

		if ($.trim($(this).val()) !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});

	$('#iNcr').change(function () {
		$('#iNserv').html('').append('<option value="">Cargando servicios...</option>');
		$('#gserv').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "program/ajax.getServicios.php",
			dataType: 'json',
			data: {cr: $(this).val()}
		}).done(function (data) {
			$('#iNserv').html('').append('<option value="">TODOS LOS SERVICIOS</option>');

			$.each(data, function (k, v) {
				$('#iNserv').append(
					$('<option></option>').val(v.ser_id).html(v.ser_nombre)
				);
			});
		});
	});


	function validateForm() {
		var values = true;

		if (values) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		} else {
			new Noty({
				text: 'Error al consultar datos. <br>Por favor, revise la fecha de consulta.',
				type: 'error'
			}).show();
			return false;
		}
	}

	function showResponse(response) {
		$('#submitLoader').css('display', 'none');
		tBlocks.clear();

		if (response !== null) {
			$.each(response, function (k, v) {
				var iden = v.id_begin.bh_id + '-' + v.id_end.bh_id;

				tBlocks.row.add([
					v.id_begin.per_nombres,
					(v.id_begin.bh_programado) ? 'PROGRAMADO' : 'NO PROGRAMADO',
					getDateBD(v.id_begin.bh_fecha) + ' ' + v.id_begin.bh_hora_ini,
					getDateBD(v.id_end.bh_fecha) + ' ' + v.id_end.bh_hora_ter,
					v.id_begin.mau_descripcion,
					v.id_begin.bdes_descripcion,
					v.id_begin.bh_descripcion,
					'<a class="peopleAgenda btn btn-xs btn-danger btnDel" id="iN' + iden + '" data-tooltip="tooltip" data-placement="top" title="Eliminar Registro"><i class="fa fa-remove"></i></a>'
				]);
			});
		}
		tBlocks.draw();
	}

	var options = {
		url: 'agenda/ajax.getAbsencesByFilters.php',
		type: 'POST',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#formNewProgram').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});