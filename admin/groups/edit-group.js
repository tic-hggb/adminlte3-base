$(document).ready(function () {
	var _vName = true;

	var options = {
		url: 'admin/groups/ajax.editGroup.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#submitLoader').css('display', 'none');

	$('#iNname').change(function () {
		$('#gname').removeClass('has-error').removeClass('has-success');
		$('#iconname').removeClass('fa-remove').removeClass('fa-check');

		if ($(this).val() !== '') {
			$.ajax({
				type: "POST",
				url: "admin/groups/ajax.existGroup.php",
				dataType: 'json',
				data: {groupname: $('#iNname').val()}
			}).done(function (r) {
				if (r.msg === true) {
					_vName = false;
					$('#gname').addClass('has-error');
					$('#iconname').addClass('fa-remove');
					$('#iNname').val('');

					new Noty({
						text: 'El nombre de grupo elegido ya se encuentra registrado. <br>Por favor, escoja un nombre de grupo diferente.',
						type: 'error'
					}).show();
				}
				else {
					_vName = true;
					$('#gname').addClass('has-success');
					$('#iconname').addClass('fa-check');
				}
			});
		}
	});

	$('#iNname, #iNprofile').change(function () {
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

	$('#btnClear').click(function () {
		$('#gname, #gprofile').removeClass('has-error').removeClass('has-success');
		$('#iconname').removeClass('fa-remove').removeClass('fa-check');
	});


	$('#formModGroup').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});

	function validateForm(data, jF, o) {
		if (_vName) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		}
		else {
			new Noty({
				text: 'Error al editar grupo. <br>Por favor corrija los campos marcados con errores',
				type: 'error'
			}).show();
			return false;
		}
	}

	function showResponse(response) {
		$('#submitLoader').css('display', 'none');

		if (response.type === true) {
			new Noty({
				text: 'El grupo ha sido editado con éxito. Recargando formulario...',
				type: 'success',
				callbacks: {
					afterClose: function () {
						setTimeout(function () {
							document.location.reload(true);
						});
					}
				}
			}).show();

			$('#btnClear').click();
		}
		else {
			new Noty({
				text: 'Hubo un problema al editar el grupo. <br>' + response.msg,
				type: 'error'
			}).show();
		}
	}
});