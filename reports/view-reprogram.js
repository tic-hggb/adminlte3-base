$(document).ready(function () {
	var options = {
		url: 'reports/ajax.getReprogram.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#submitLoader').css('display', 'none');

	$(document).on("focusin", "#iNyear", function (event) {
		$(this).prop('readonly', true);
	});
	$(document).on("focusout", "#iNyear", function (event) {
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

	$('#iNplanta, #iNestab, #iNcr, #iNserv, #iNesp').change(function () {
		var idn = $(this).attr('id').split('N');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
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
			url: "reports/ajax.getServicios.php",
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

	$('#iNserv').change(function () {
		$('#iNesp').html('').append('<option value="">Cargando especialidades...</option>');
		$('#gesp').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "reports/ajax.getEspecialidades.php",
			dataType: 'json',
			data: {serv: $(this).val()}
		}).done(function (data) {
			$('#iNesp').html('').append('<option value="">TODAS LAS ESPECIALIDADES</option>');

			$.each(data, function (k, v) {
				$('#iNesp').append(
					$('<option></option>').val(v.esp_id).html(v.esp_nombre)
				);
			});
		});
	});

	$('#formNewReport').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});

	function validateForm(data, jF, o) {
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

		if (response.type === true) {
			var $a = $("<a>");
			$a.attr("href", "upload/Planilla Reprogramacion " + response.msg + ".xlsx");
			$a.text("down");
			$("#formNewReport").append($a);
			$a[0].click();
			$a.remove();
		}
	}

	//Check to see if the window is top if not then display button
	$(window).scroll(function () {
		if ($(this).scrollTop() > 200) {
			$('.scrollToTop').fadeIn();
		}
		else {
			$('.scrollToTop').fadeOut();
		}
	});

	//Click event to scroll to top
	$('.scrollToTop').click(function () {
		$('html, body').animate({scrollTop: 0}, 800);
		return false;
	});
});