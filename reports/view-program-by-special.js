$(document).ready(function () {
	function fillBar() {
		var i = 0;
		if (i === 0) {
			i = 1;
			var progressBar = $('.progress-bar');
			var width = 0;
			id = setInterval(frame, 150);

			function frame() {
				if (width >= 100) {
					clearInterval(id);
					i = 0;
				} else {
					width++;
					progressBar.css('width', width + '%');
				}
			}
		}
	}

	function validateForm() {
		var values = true;
		var bar = $('.progress');
		bar.css('display', 'block');
		fillBar();

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
		clearInterval(id);
		var progressBar = $('.progress-bar');
		progressBar.css('width', '100%');
		$('.progress').removeClass('active');

		if (response.type) {
			var $a = $("<a>");
			$a.attr("href", "upload/Planilla Programacion Planta " + response.msg + ".xlsx");
			$a.text("down");
			$("#formNewReport").append($a);
			$a[0].click();
			$a.remove();
		} else {
			new Noty({
				text: 'Error al generar archivo. <br>' + response.msg,
				type: 'error'
			}).show();
		}
	}

	var options = {
		url: 'reports/ajax.getProgramByEsp.php',
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
		endDate: '+0y'
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			$('#gyear').removeClass('has-error').addClass('has-success');
			$('#iconyear').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$('#iNperiodo, #iNplanta, #iNestab').change(function () {
		var idn = $(this).attr('id').split('N');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});

	$('#formNewReport').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});