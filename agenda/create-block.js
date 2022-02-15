$(document).ready(function () {

	function getCurrentPeriod(p) {
		if (p === 1 || p === 2 || p === 3) return '01';
		else if (p === 4 || p === 5 || p === 6) return '04';
		else if (p === 7 || p === 8 || p === 9) return '07';
		else if (p === 10 || p === 11 || p === 12) return '10';
	}

	$('#submitLoader').css('display', 'none');

	$('#iNpersona').devbridgeAutocomplete({
		minChars: 1,
		autoSelectFirst: true,
		showNoSuggestionNotice: true,
		noSuggestionNotice: 'No hay resultados',
		lookup: function (query, done) {
			$.ajax({
				url: 'box/ajax.getPersonaResults.php',
				type: 'post',
				dataType: 'json',
				data: {string: query}
			}).done(function (d) {
				//console.log(d);
				var result = {suggestions: d};

				done(result);
			});
		},
		lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
			var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
			return re.test(suggestion.value);
		},
		onSelect: function (selected) {
			$('#gpersona').removeClass('has-error').addClass('has-success');
			$('#iconpersona').removeClass('fa-remove').addClass('fa-ok');

			$.ajax({
				url: 'box/ajax.getPersonaByName.php',
				type: 'post',
				dataType: 'json',
				data: {name: selected.value}
			}).done(function (d) {
				$('#iNperid').val(d.per_id);
			});
		},
		onInvalidateSelection: function () {
			$('#gpersona').removeClass('has-error has-success');
			$('#iconpersona').removeClass('fa-remove fa-ok');
		}
	});

	$('#iNpersona').blur(function () {
		if ($.trim($(this).val()) === '') {
			$('#iNpersona').val('');
		}
	});

	$('#gdate .input-daterange').each(function () {
		$(this).datepicker({
			startView: 0,
			minViewMode: 0,
			//startDate: '+0d'
		});
	}).change(function () {
		$('#gdate').removeClass('has-success has error');
		if ($.trim($('#iNdatei').val()) !== '' && $.trim($('#iNdatet').val()) !== '' && $('#iNperid').val() !== '') {
			var begin_date = moment(getDateToBD($('#iNdatei').val()));
			var end_date = moment(getDateToBD($('#iNdatet').val()));
			var begin_month = begin_date.month() + 1;
			var begin_year = begin_date.year();
			var end_month = end_date.month() + 1;

			var ini_period = getCurrentPeriod(begin_month);
			var fin_period = getCurrentPeriod(end_month);

			if (ini_period !== fin_period) {
				swal({
					title: "Atención!",
					html: "Las fechas elegidas no forman parte del mismo corte de agenda.<br>La fecha de término será ajustada al último día del corte de la fecha de inicio.<br><br>Los días restantes deberán ser ingresados como ausencia programada al momento de ingresar la agenda correspondiente.",
					type: "warning",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				}).then((result) => {
					if (result.value) {
						if (ini_period === '01') month = 3;
						else if (ini_period === '04') month = 6;
						else if (ini_period === '07') month = 9;
						else month = 12;

						var d = new Date(begin_year, month, 0);
						end_date = ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" + d.getFullYear();
						$('#iNdatet').datepicker('setDate', d);

						$.ajax({
							type: "POST",
							url: "agenda/ajax.getAbsencesByDate.php",
							dataType: 'json',
							data: {f_ini: $('#iNdatei').val(), f_ter: $('#iNdatet').val(), per: $('#iNperid').val()}
						}).done(function (data) {
							if (data.length > 0) {
								$('#iNdatei, #iNdatet').val('').datepicker('clearDates').datepicker('hide').blur();

								swal({
									title: "Error!",
									html: "La fecha elegida coincide con una ausencia registrada anteriormente.<br>Por favor, elija la fecha nuevamente.",
									type: "error",
									showCancelButton: false,
									confirmButtonText: "Aceptar"
								});
							}
							else
								$('#gdate').addClass('has-success');
						});
					}
				});
			}
			else {
				$.ajax({
					type: "POST",
					url: "agenda/ajax.getAbsencesByDate.php",
					dataType: 'json',
					data: {f_ini: $('#iNdatei').val(), f_ter: $('#iNdatet').val(), per: $('#iNperid').val()}
				}).done(function (data) {
					if (data.length > 0) {
						$('#iNdatei, #iNdatet').val('').datepicker('clearDates').datepicker('hide').blur();

						swal({
							title: "Error!",
							html: "La fecha elegida coincide con una ausencia registrada anteriormente.<br>Por favor, elija la fecha nuevamente.",
							type: "error",
							showCancelButton: false,
							confirmButtonText: "Aceptar"
						});
					}
					else
						$('#gdate').addClass('has-success');
				});
			}
		}
	});

	$('.timepicker').timepicker({
		showMeridian: false,
		showSeconds: false,
		minuteStep: 60,
		showInputs: false,
		defaultTime: false
	});

	$('#hora-inicio').timepicker('setTime', '08:00 AM');
	$('#hora-fin').timepicker('setTime', '09:00 PM');

	$('#hora-inicio').timepicker().on('changeTime.timepicker', function (e) {
		if ($('#hora-fin').data('timepicker').hour <= e.time.hours) {
			var hour = e.time.hours + 1;
			hour = (hour < 10) ? '0' + hour : hour;
			var meridian = (hour > 11) ? 'PM' : 'AM';
			$('#hora-fin').timepicker('setTime', hour + ':00 ' + meridian);
		}
	});

	$('#hora-fin').timepicker().on('changeTime.timepicker', function (e) {
		if ($('#hora-inicio').data('timepicker').hour >= e.time.hours) {
			var hour = e.time.hours - 1;
			hour = (hour < 10) ? '0' + hour : hour;
			var meridian = (hour > 11) ? 'PM' : 'AM';
			$('#hora-inicio').timepicker('setTime', hour + ':00 ' + meridian);
		}
	});

	$('#all-day').on('ifChanged', function () {
		if (!!$(this).prop('checked')) {
			$('#hora-inicio').timepicker('setTime', '00:00 AM');
			$('#hora-fin').timepicker('setTime', '23:59 PM');
		}
		else {
			$('#hora-inicio').timepicker('setTime', '08:00 AM');
			$('#hora-fin').timepicker('setTime', '09:00 AM');
		}
	});

	$('#iNpersona, #iNmotivo, #iNdestino, #iNobs').change(function () {
		var idn = $(this).attr('id').split('N');
		var val = $.trim($(this).val());
		$('.help-block').html('');

		if (val !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
			$('#g' + idn[1]).removeClass('has-error has-success');
			$('#icon' + idn[1]).removeClass('fa-remove fa-check');
		}
	});

	$('#btnClear').click(function () {
		$('#gpersona, #gdate, #gmotivo, #gdestino, #gobs').removeClass('has-success has-error');
		$('#iconpersona, #iconobs').removeClass('fa-check fa-remove');
		$('#hora-inicio').timepicker('setTime', '08:00 AM');
		$('#hora-fin').timepicker('setTime', '09:00 AM');
		$('#all-day').prop('checked', false).iCheck('update');
		$('#iNdatei, #iNdatet').datepicker('clearDates');
		$('.help-block').html('');
	});


	function validateForm(data, jF, o) {
		var values = true, msg = '';

		if ($('#iNmotivo').val() === '20' && $('#iNobs').val() === '') {
			values = false;
			msg = 'Debe justificar la ausencia especificando la actividad realizada.';

			$('#gobs').removeClass('has-success').addClass('has-error');
			$('#iconobs').removeClass('fa-check').addClass('fa-remove');
			$('.help-block').html('Ingrese la actividad para justificar la ausencia.');
		}

		if ($('#iNmotivo').val() === '16' && $('#iNobs').val() === '') {
			values = false;
			msg = 'Debe justificar la ausencia indicando la institución a la que acudirá.';

			$('#gobs').removeClass('has-success').addClass('has-error');
			$('#iconobs').removeClass('fa-check').addClass('fa-remove');
			$('.help-block').html('Ingrese la institución donde tendrá lugar la reunión.');
		}

		if (values) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		} else {
			swal({
				title: "Error!",
				html: "Error al guardar los datos. " + msg,
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});

			return false;
		}
	}

	function showResponse(response) {
		$('#submitLoader').css('display', 'none');

		if (response.type) {
			$('#btnClear').click();

			new Noty({
				text: '<b>¡Éxito!</b><br>La ausencia ha sido guardada correctamente.',
				type: 'success'
			}).show();
		}
		else {
			new Noty({
				text: '<b>¡Error!</b><br>' + response.msg,
				type: 'error'
			}).show();
		}
	}

	var options = {
		url: 'agenda/ajax.insertAbsence.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#formNewAbsence').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});