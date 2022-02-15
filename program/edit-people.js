$(document).ready(function () {
	function validateForm() {
		const values = true;

		if (values) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		} else {
			return false;
		}
	}

	function showResponse(response) {
		$('#submitLoader').css('display', 'none');

		if (response.type === true) {
			new Noty({
				text: 'El contrato ha sido editado con éxito.',
				type: 'success'
			}).show();
		} else {
			new Noty({
				text: 'Hubo un problema al editar el contrato.<br>' + response.msg,
				type: 'error'
			}).show();
		}
	}

	const options = {
		url: 'program/ajax.editPeople.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#submitLoader').css('display', 'none');

	$('#iNcorr').change(function () {
		const $rut = $('#iNrut'), $con = $('#iNtcontrato');

		if ($rut.val() !== '' && $con.val() !== '' && $(this).val() !== '') {
			$.ajax({
				url: 'program/ajax.getPeopleByRutLey.php',
				type: 'post',
				dataType: 'json',
				data: {rut: $rut.val(), con: $con.val(), corr: $(this).val()}
			}).done(function (d) {
				if (d.per_id !== null) {
					swal({
						title: "Error!",
						html: "El RUT ingresado corresponde a una persona ya registrada bajo este correlativo y modalidad de contrato en este establecimiento.",
						type: "error"
					});

					$('#iNcorr').val('');
					$('#gcorr').addClass('has-error');
					$('#iconcorr').addClass('fa-remove');
				}
			});
		}
	});

	$('#iNname, #iNprofesion, #iNtcontrato, #iNcorr, #iNhoras').change(function () {
		const idn = $(this).attr('id').split('N');

		if ($.trim($(this).val()) !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});

	$('#btnClear').click(function () {
		$('#grut, #gname, #gprofesion, #gespec, #gtcontrato, #gcorr, #ghoras').removeClass('has-error').removeClass('has-success');
		$('#iconrut, #iconname, #iconespec, #iconcorr, #iconhoras').removeClass('fa-remove').removeClass('fa-check');
	});

	$('#formEditPeople').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});