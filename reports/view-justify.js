$(document).ready(function () {
	var tablePeople = $('#tpeople').DataTable({
		'columns': [{'width': '120px', className: 'text-right'}, null, null, null],
		'order': [[1, 'asc']]
	});

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
		endDate: '+0y'
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			$('#gyear').removeClass('has-error').addClass('has-success');
			$('#iconyear').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$('#iNperiodo, #iNplanta, #iNestab').change(function () {
		var idn = $(this).attr('id').split('N');

		if ($.trim($(this).val()) !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});


	function validateForm() {
		var values = true;

		if (values) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		}
		else {
			new Noty({
				text: 'Error al consultar datos. <br>Por favor, revise la fecha de consulta.',
				type: 'error'
			}).show();

			return false;
		}
	}

	function showResponse(response) {
		$('#submitLoader').css('display', 'none');
		tablePeople.clear();

		if (response !== null) {

			$.each(response, function (k, v) {
				tablePeople.row.add([
					v.per_rut,
					v.per_nombres,
					v.per_profesion,
					v.per_justif
				]);
			});
		}

		tablePeople.draw();
	}

	var options = {
		url: 'reports/ajax.getJustifyMeds.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#formViewMedics').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});


