$(document).ready(function () {
	var tableUsr = $("#tpeople").DataTable({
		columns: [
			{width: '100px', className: 'text-right'}, 
			null,
			null,
			{orderable: false, width: '70px', className: 'text-center'}],
		order: [[2, 'asc']],
		buttons: [
			{
				extend: 'excel',
				exportOptions: {
					columns: [0, 1, 2]
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
		minViewMode: 2,
		startDate: '-1y'
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			$('#gyear').removeClass('has-error').addClass('has-success');
			$('#iconyear').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$('#iNperiodo, #iNplanta, #iNcr, #iNserv, #iNestab, #iNesp').change(function () {
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
		$('#iNesp').html('').append('<option value="">Seleccione especialidad</option>');
		$('#gserv, #gesp').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "program/ajax.getServicios.php",
			dataType: 'json',
			data: {cr: $(this).val()}
		}).done(function (data) {
			$('#iNserv').html('').append('<option value="">Seleccione servicio</option>');

			$.each(data, function (k, v) {
				$('#iNserv').append(
					$('<option></option>').val(v.ser_id).html(v.ser_nombre)
				);
			});
		});
	});

	$('#iNserv').change(function () {
		$('#iNesp').html('').append('<option value="">Cargando especialidades...</option>');
		$('#gesp').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "program/ajax.getEspecialidades.php",
			dataType: 'json',
			data: {serv: $(this).val()}
		}).done(function (data) {
			$('#iNesp').html('').append('<option value="">Seleccione especialidad</option>');

			$.each(data, function (k, v) {
				$('#iNesp').append(
					$('<option></option>').val(v.esp_id).html(v.esp_nombre)
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
		tableUsr.clear();

		if (response !== null) {

			$.each(response, function (k, v) {
				var iden = v.a_year + '-' + v.a_month + '-' + v.a_serv + '-' + v.a_est + '-' + v.per_id + '-' + v.a_esp;
				tableUsr.row.add([
					v.per_rut,
					v.per_nombres,
					v.per_profesion,
					'<a class="peopleAgenda btn btn-xs btn-info" href="index.php?section=agenda&sbs=viewagenda&iden=' + iden + '" data-tooltip="tooltip" data-placement="top" title="Ver Agenda"><i class="fa fa-search-plus"></i></a>'
					+ ' <a class="peopleAgenda btn btn-xs btn-success" href="index.php?section=agenda&sbs=modifyagenda&iden=' + iden + '" data-tooltip="tooltip" data-placement="top" title="Modificar Agenda"><i class="fa fa-pencil"></i></a>'
				]);
			});
		}

		tableUsr.draw();
	}

	var options = {
		url: 'agenda/ajax.getPeopleAgendasByFilters.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#formNewProgram').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});