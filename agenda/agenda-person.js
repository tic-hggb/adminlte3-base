$(document).ready(function () {
	var tableUsr = $("#tpeople").DataTable({
		columns: [{width: "100px", className: "text-right"}, null, null, {orderable: false, width: "70px", className: "text-center"}],
		order: [[2, 'asc']]
	});

	var options = {
		url: 'agenda/ajax.getAgendasByFilters.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#submitLoader').css('display', 'none');

	$(document).on("focusin", "#iNyear", function () {
		$(this).prop('readonly', true);
	});
	$(document).on("focusout", "#iNyear", function () {
		$(this).prop('readonly', false);
	});

	$('#iNyear').datepicker({
		startView: 2,
		minViewMode: 2,
		startDate: '-1y'
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
			$('#iNserv').html('').append('<option value="">TODOS</option>');

			$.each(data, function (k, v) {
				$('#iNserv').append(
					$('<option></option>').val(v.ser_id).html(v.ser_nombre)
				);
			});
		});
	});

	$('#formNewProgram').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});

	function validateForm(data, jF, o) {
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
		tableUsr.clear();

		if (response !== null) {

			$.each(response, function (k, v) {
				var iden = v.ayear + '-' + v.aperiod + '-' + v.estab + '-' + v.per_id;
				tableUsr.row.add([
					v.per_rut,
					v.per_nombres,
					v.per_profesion,
					'<a class="peopleAgenda btn btn-xs btn-info" href="index.php?section=agenda&sbs=viewagendapeople&iden=' + iden + '" data-tooltip="tooltip" data-placement="top" title="Ver Agenda"><i class="fa fa-search-plus"></i></a>'
				]);
			});
		}

		tableUsr.draw();
	}
});