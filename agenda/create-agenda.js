$(document).ready(function () {
	const $calendar = $('#calendar'), $horaInicio = $('#hora-inicio'), $horaFin = $('#hora-fin'), $cuposInner = $('#cupos-cont-inner'), $period = $('#iNperiod'), $year = $('#iNyear'),
		$cuposAgenda = $('#cuposagen-id'), $cuposOtro = $('#cuposotro-id'), $calendarBox = $('#calendar-boxat'), $boxatId = $('#boxat-id'), $espId = $('#esp-id'), $subespId = $('#subesp-id'),
		$eventId = $('#event-id'), $addEvent = $('#add-new-event'), $diaId = $('#dia-id'), $catId = $('#cat-id');
	/**
	 * chequea si horas de inicio/fin se superponen entre sí
	 * @param id
	 * @param ini
	 * @param fin
	 * @param multi
	 * @returns {boolean}
	 */
	function checkActivity(id, ini, fin, multi) {
		let free = true;

		$.each($calendar.fullCalendar('clientEvents'), function (k, o) {
			if (ini._d.toString() === o.start._d.toString() || fin._d.toString() === o.end._d.toString()) {
				free = false;
				//console.log('caso == cal1')
			}
			if (ini._d.toString() > o.start._d.toString() && ini._d.toString() < o.end._d.toString()) {
				free = false;
				//console.log('caso > ini cal1')
			}
			if (fin._d.toString() > o.start._d.toString() && fin._d.toString() < o.end._d.toString()) {
				free = false;
				//console.log('caso > fin cal1')
			}
		});

		$.each($calendarBox.fullCalendar('clientEvents'), function (k, o) {
			let sameAct = true;
			if ((parseInt(o.multi, 10) !== 1 && parseInt(multi, 10) !== 1) || (parseInt(o.actId, 10) !== parseInt(id, 10))) {
				sameAct = false;
			}
			if ((ini._d.toString() === o.start._d.toString() || fin._d.toString() === o.end._d.toString()) && !sameAct) {
				free = false;
				//console.log('caso == cal2')
			}
			if (ini._d.toString() > o.start._d.toString() && ini._d.toString() < o.end._d.toString()) {
				free = false;
				//console.log('caso > ini cal2')
			}
			if (fin._d.toString() > o.start._d.toString() && fin._d.toString() < o.end._d.toString()) {
				free = false;
				//console.log('caso > fin cal2')
			}
		});

		return free;
	}

	let numActividades = 0;
	let actPendientes = 0;
	let idIt = 1;

	$('#submitLoader').css('display', 'none');
	$addEvent.prop('disabled', true);
	$('#event-name').prop('disabled', true);
	$('#cupos-id0, #obscupos-id').prop('disabled', true);

	$('#grupos-cont').on('change', '.tipocupo', function () {
		if ($(this).val() !== '') {
			$('#cupos-id, #obscupos-id').prop('disabled', false);
			$('#tipocupo-group').addClass('has-success');
		} else {
			$('#cupos-id, #obscupos-id').val('').prop('disabled', true);
			$('#cupos-icon, #obscupos-icon').removeClass('fa-check');
			$('#cupos-group, #obscupos-group').removeClass('has-success');
			$('#tipocupo-group').removeClass('has-success');
		}
	});

	$('.timepicker').timepicker({
		showMeridian: false,
		showSeconds: false,
		minuteStep: 30,
		showInputs: false,
		defaultTime: false,
		disableFocus: true
	});
	$horaInicio.timepicker('setTime', '08:00 AM');
	$horaFin.timepicker('setTime', '08:30 AM');

	$horaInicio.timepicker().on('hide.timepicker', function (e) {
		let meridian = '', hour = '', minute = '';
		if (e.time.hours < 8) {
			swal({
				title: "Error!",
				text: "La hora de inicio no puede ser menor a las 8:00.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$horaInicio.timepicker('setTime', '08:00 AM');
		}
		if (e.time.hours === 17) {
			if (e.time.minutes === 30) {
				swal({
					title: "Error!",
					text: "La hora de inicio no puede ser mayor a las 17:00.",
					type: "error",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});
				$horaInicio.timepicker('setTime', '17:00 PM');
			}
		}
		if (e.time.hours > 17) {
			swal({
				title: "Error!",
				text: "La hora de inicio no puede ser mayor a las 17:00.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$horaInicio.timepicker('setTime', '17:00 PM');
		}

		if ($horaFin.data('timepicker').hour === e.time.hours) {
			if ($horaFin.data('timepicker').minute === e.time.minutes) {
				hour = $horaFin.data('timepicker').hour;
				minute = $horaInicio.data('timepicker').minute + 30;
				if (minute === 60) {
					minute = 0;
					hour++;
				}

				hour = (hour < 10) ? '0' + hour : hour;
				meridian = (hour > 11) ? 'PM' : 'AM';
				$horaFin.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
			} else if ($horaFin.data('timepicker').minute < e.time.minutes) {
				hour = $horaInicio.data('timepicker').hour + 1;
				minute = $horaInicio.data('timepicker').minute - 30;
				if (minute === 60) {
					minute = 0;
					hour++;
				}

				hour = (hour < 10) ? '0' + hour : hour;
				meridian = (hour > 11) ? 'PM' : 'AM';
				$horaFin.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
			}
		} else if ($horaFin.data('timepicker').hour < e.time.hours) {
			hour = $horaInicio.data('timepicker').hour;
			minute = $horaInicio.data('timepicker').minute + 30;
			if (minute === 60) {
				minute = 0;
				hour++;
			}

			hour = (hour < 10) ? '0' + hour : hour;
			meridian = (hour > 11) ? 'PM' : 'AM';
			$horaFin.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
		}
	});

	$horaFin.timepicker().on('hide.timepicker', function (e) {
		let meridian = '', hour = '', minute = '';
		if (e.time.hours === 8) {
			if (e.time.minutes === 0) {
				swal({
					title: "Error!",
					text: "La hora de término no puede ser menor a las 8:30.",
					type: "error",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});
				$horaFin.timepicker('setTime', '08:30 AM');
			}
		} else if (e.time.hours < 8) {
			swal({
				title: "Error!",
				text: "La hora de término no puede ser menor a las 8:30.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$horaFin.timepicker('setTime', '08:30 AM');
		}

		if (e.time.hours === 18) {
			if (e.time.minutes === 30) {
				swal({
					title: "Error!",
					text: "La hora de término no puede ser mayor a las 18:00.",
					type: "error",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});
				$horaFin.timepicker('setTime', '18:00 PM');
			}
		}
		if (e.time.hours > 18) {
			swal({
				title: "Error!",
				text: "La hora de inicio no puede ser mayor a las 18:00.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$horaFin.timepicker('setTime', '18:00 PM');
		}

		if ($horaInicio.data('timepicker').hour === e.time.hours) {
			if ($horaInicio.data('timepicker').minute === e.time.minutes) {
				hour = $horaFin.data('timepicker').hour - 1;
				minute = $horaFin.data('timepicker').minute + 30;

				if (minute === 60) {
					hour++;
					minute = 0;
				}

				hour = (hour < 10) ? '0' + hour : hour;
				meridian = (hour > 11) ? 'PM' : 'AM';
				$horaInicio.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
			} else if ($horaInicio.data('timepicker').minute > e.time.minutes) {
				hour = e.time.hours + 1;
				minute = 0;

				hour = (hour < 10) ? '0' + hour : hour;
				meridian = (hour > 11) ? 'PM' : 'AM';
				$horaFin.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
			}
		} else if ($horaInicio.data('timepicker').hour > e.time.hours) {
			hour = $horaFin.data('timepicker').hour - 1;
			minute = $horaFin.data('timepicker').minute + 30;

			if (minute === 60) {
				hour++;
				minute = 0;
			}

			hour = (hour < 10) ? '0' + hour : hour;
			meridian = (hour > 11) ? 'PM' : 'AM';
			$horaInicio.timepicker('setTime', hour + ':' + minute + ' ' + meridian);
		}
	});

	$('#btn-add-cupo').click(function () {
		$cuposInner.append('<div class="form-group col-xs-6" id="tipocupo-group' + idIt + '"><label class="control-label">Tipo de cupo</label></div>');
		$('#tipocupo-group' + idIt).append($('#tipocupo-id0').clone().prop('id', 'tipocupo-id' + idIt));
		$cuposInner.append('<div class="form-group col-xs-5 has-feedback" id="cupos-group' + idIt + '"><label class="control-label">Cupos</label></div>');
		$('#cupos-group' + idIt).append($('#cupos-id0').clone().prop('id', 'cupos-id' + idIt).prop('disabled', true).val('')).append('<span class="fa form-control-feedback" id="cupos-icon' + idIt + '"></span>');
		$cuposInner.append('<div class="form-group col-xs-1" id="gDel' + idIt + '"><label class="control-label">...</label></div>');
		$('#gDel' + idIt).append('<button type="button" class="btn btn-block btn-danger btn-del" id="btnDel' + idIt + '"><i class="fa fa-minus"></i></button>');
		idIt++;
	});

	$cuposInner.on('click', '.btn-del', function () {
		const id = $(this).attr('id').split('Del');
		$('#tipocupo-group' + id[1]).remove();
		$('#cupos-group' + id[1]).remove();
		$('#gDel' + id[1]).remove();
	});

	const fM = firstMonday($period.val() - 1, $year.val());
	const fDay = '0' + fM.getDate();
	const fMonth = ((fM.getMonth() + 1) < 10) ? '0' + (fM.getMonth() + 1) : (fM.getMonth() + 1);

	$calendar.fullCalendar({
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		locale: 'es',
		timezone: 'local',
		height: 'auto',
		defaultView: 'agendaWeek',
		defaultDate: moment($year.val() + '-' + fMonth + '-' + fDay),
		titleFormat: 'YYYY',
		firstDay: 1,
		header: false,
		columnHeaderFormat: 'dddd',
		minTime: '08:00:00',
		maxTime: '18:00:00',
		eventOverlap: false,
		allDaySlot: false,
		defaultTimedEventDuration: '01:00:00',
		eventClick: function (event) {
			$(".closon").click(function () {
				$calendar.fullCalendar('removeEvents', event._id);
				numActividades--;
			});
		},
		eventRender: function (event, element) {
			element.find(".fc-bg").css("pointer-events", "none");

			if (event.eventSource !== 'ajax') {
				element.append("<div style='position:absolute;bottom:0;right:0;z-index:10' class='closon'><button type='button' id='btnDeleteEvent' class='btn btn-xs btn-default' style='font-size:8px;border-radius:50%;'>X</button></div>");
			}

			element.find("#btnDeleteEvent").click(function () {
				if (typeof event.pendiente !== 'undefined') {
					actPendientes--;
				} else {
					let tCupos = 0;
					const arrCupos = event.cupos.split(',');
					$.each(arrCupos, function (k, v) {
						tCupos += parseFloat(v);
					});

					if (event.cxc) {
						let total = parseFloat($cuposAgenda.val()) - tCupos;
						$cuposAgenda.val(number_format(total, 2, '.', ''));
					} else {
						let total = parseFloat($cuposOtro.val()) - tCupos;
						$cuposOtro.val(number_format(total, 2, '.', ''));
					}
					numActividades--;
				}

				element.popover('destroy');

				if (parseFloat($cuposAgenda.val()) >= parseFloat($('#cuposprog-id').val())) {
					$('#cuposprog-group').addClass('has-success');
					$('#cuposprog-icon').addClass('fa-check');
				} else {
					$('#cuposprog-group').removeClass('has-success');
					$('#cuposprog-icon').removeClass('fa-check');
				}

				$calendar.fullCalendar('removeEvents', event._id);
			});

			let cuposBlock = '';
			const arrCupos = event.cupos.split(',');
			const arrCuposText = event.tipoCuposText.split(',');
			$.each(arrCupos, function (k, v) {
				cuposBlock += '<br> - ' + v + ' cupos ' + arrCuposText[k];
			});

			element.popover({
				title: event.title + ' ',
				html: true,
				content: 'Box ' + event.boxText + ', ' + event.pisoText + cuposBlock + '<br>' + event.espSinText + '<br>' + event.subespSinText,
				trigger: 'hover',
				placement: 'top',
				container: 'body'
			});
		},
		eventAfterAllRender: function (event) {
			$boxatId.change();
		},
		editable: true,
		droppable: false,
		events: {
			url: 'agenda/ajax.getAgendaEventsByPerson.php',
			type: 'POST',
			data: {year: $year.val(), period: $period.val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}
	});

	$calendarBox.fullCalendar({
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		locale: 'es',
		timezone: 'local',
		height: 'auto',
		defaultView: 'agendaWeek',
		defaultDate: moment($year.val() + '-' + fMonth + '-' + fDay),
		firstDay: 1,
		header: false,
		columnHeaderFormat: 'dddd',
		minTime: '08:00:00',
		maxTime: '18:00:00',
		allDaySlot: false,
		defaultTimedEventDuration: '01:00:00',
		editable: false,
		droppable: false,
		eventRender: function (event, element) {
			console.log(event);
			const multi = (event.multi === 1) ? ' (*)' : '';
			let tmp = event.start._i.split(' ');
			const h_ini = tmp[1];
			tmp = event.end._i.split(' ');
			const h_fin = tmp[1];

			element.popover({
				title: event.title + multi,
				html: true,
				content: event.personaText + '<br>' + h_ini + ' - ' + h_fin,
				trigger: 'hover',
				placement: 'top',
				container: 'body'
			});
		}
	});

	$('#piso-id, #boxat-id, #dia-id, #obscupos-id, #cat-id, #esp-id, #subesp-id, #event-id').change(function () {
		const idn = $(this).attr('id').split('-');
		const val = $.trim($(this).val());

		if (val !== '') {
			$('#' + idn[0] + '-group').removeClass('has-error').addClass('has-success');
			$('#' + idn[0] + '-icon').removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#' + idn[0] + '-group').removeClass('has-error has-success');
			$('#' + idn[0] + '-icon').removeClass('fa-remove fa-check');
		}
	});

	$('#cupos-cont').on('change', '.numcupos', function () {
		const idn = $(this).attr('id').split('id');
		const val = $.trim($(this).val());

		if (val !== '') {
			if (parseInt(val, 10) > 0) {
				$('#cupos-group' + idn[1]).removeClass('has-error').addClass('has-success');
				$('#cupos-icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
				$('#obscupos-id').prop('disabled', false);
			} else {
				swal({
					title: "Error!",
					html: "El número de cupos debe ser mayor a cero.<br>Por favor, ingréselos nuevamente.",
					type: "error",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});

				$('#cupos-id' + idn[1]).val('');
				$('#cupos-group' + idn[1]).removeClass('has-error has-success');
				$('#cupos-icon' + idn[1]).removeClass('fa-remove fa-check');
			}
		} else {
			$('#cupos-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove fa-check');

		}

		let n_c = false;
		$('.numcupos').each(function () {
			if ($.trim($(this).val()) !== '') n_c = true;
		});
		if (!n_c) $('#obscupos-id').prop('disabled', true);
	}).on('change', '.tipocupo', function () {
		const idn = $(this).attr('id').split('id');
		const val = $.trim($(this).val());

		if (val !== '') {
			$('#tipocupo-group' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#cupos-id' + idn[1]).prop('disabled', false);
		} else {
			$('#tipocupo-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove fa-check');
			$('#cupos-id' + idn[1]).prop('disabled', true).val('');
		}

		let t_c = false;
		$('.tipocupo').each(function () {
			if ($.trim($(this).val()) !== '') t_c = true;
		});
		if (!t_c) $addEvent.prop('disabled', true);
	});

	$('#piso-id').change(function () {
		$addEvent.prop('disabled', true);
		$boxatId.html('').append('<option value="">Seleccione box de atención</option>');
		$('#box-detail-title').html('Ocupación del Box');
		$calendarBox.fullCalendar('removeEvents');
		$('#boxat-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$boxatId.html('').append('<option value="">Cargando boxes...</option>');

			$.ajax({
				type: "POST",
				url: "box/ajax.getBoxes.php",
				dataType: 'json',
				data: {id: $(this).val(), type: ''}
			}).done(function (data) {
				$boxatId.html('').append('<option value="">Seleccione box de atención</option>');

				$.each(data, function (k, v) {
					$boxatId.append(
						$('<option></option>').val(v.box_id).html(v.box_pasillo + ' - ' + v.box_numero)
					);
				});
			});
		}
	});

	$boxatId.change(function () {
		$addEvent.prop('disabled', true);
		$calendarBox.fullCalendar('removeEvents');

		if ($(this).val() !== '') {
			$('#box-detail-title').html('Ocupación del Box ' + $('#boxat-id :selected').text());

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getAgendaEvents.php",
				dataType: 'json',
				data: {year: $year.val(), period: $period.val(), box: $(this).val()}
			}).done(function (data) {
				$calendarBox.fullCalendar('renderEvents', data, true);
			});
		}
	});

	$catId.change(function () {
		$addEvent.prop('disabled', true);
		$espId.html('').append('<option value="">Seleccione especialidad</option>');
		$subespId.html('').append('<option value="">Seleccione sub-especialidad</option>');
		$eventId.html('').append('<option value="">Seleccione actividad</option>');
		$('#esp-group, #subesp-group, #event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$espId.html('').append('<option value="">Cargando especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getEspecialidades.php",
				dataType: 'json',
				data: {agr: $(this).val()}
			}).done(function (data) {
				$espId.html('').append('<option value="">Seleccione especialidad</option>');

				$.each(data, function (k, v) {
					$espId.append(
						$('<option></option>').val(v.sesp_id).html(v.sesp_nombre)
					);
				});
			});
		}
	});

	$espId.change(function () {
		$addEvent.prop('disabled', true);
		$subespId.html('').append('<option value="">Seleccione sub-especialidad</option>');
		$eventId.html('').append('<option value="">Seleccione actividad</option>');
		$('#subesp-group, #event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$subespId.html('').append('<option value="">Cargando sub-especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getSubEspecialidades.php",
				dataType: 'json',
				data: {esp: $(this).val()}
			}).done(function (data) {
				$subespId.html('').append('<option value="">Seleccione sub-especialidad</option>');

				$.each(data, function (k, v) {
					$subespId.append(
						$('<option></option>').val(v.ssub_id).html(v.ssub_nombre)
					);
				});
			});
		}
	});

	$subespId.change(function () {
		$addEvent.prop('disabled', true);
		$eventId.html('').append('<option value="">Seleccione actividad</option>');
		$('#event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$eventId.html('').append('<option value="">Cargando actividades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getActividades.php",
				dataType: 'json',
				data: {sesp: $(this).val()}
			}).done(function (data) {
				$eventId.html('').append('<option value="">Seleccione actividad</option>');

				$.each(data, function (k, v) {
					const act_n = (v.act_multi === 1) ? ' (*)' : '';

					$eventId.append(
						$('<option></option>').val(v.act_id).html(v.act_nombre + act_n)
					);
				});
			});
		}
	});

	$eventId.change(function () {
		$('#event-multi').val('');
		$('#event-comite').val('');

		if ($(this).val() !== '') {
			$.ajax({
				type: "POST",
				url: "agenda/ajax.getActividadDetail.php",
				dataType: 'json',
				data: {id: $(this).val()}
			}).done(function (data) {
				$('#event-multi').val(data.act_multi);
				$('#event-comite').val(data.act_comite);
			});
		}
	});

	$('#boxat-id, #dia-id, #cupos-id0, #event-id').change(function () {
		($boxatId.val() !== '' && $diaId.val() !== '' && $.trim($('#cupos-id0').val()) !== '' && $eventId.val() !== '') ? $addEvent.prop('disabled', false) : $addEvent.prop('disabled', true);
	});

	let currColor = '#3c8dbc';

	$('#color-chooser > li > a').click(function (e) {
		e.preventDefault();
		currColor = $(this).css('color');
		$addEvent.css({'background-color': currColor, 'border-color': currColor});
	});

	$addEvent.click(function (e) {
		e.preventDefault();
		const d_ini = moment($year.val() + '-' + $period.val() + '-' + $diaId.val() + ' ' + $horaInicio.val() + ':00');
		const d_fin = moment($year.val() + '-' + $period.val() + '-' + $diaId.val() + ' ' + $horaFin.val() + ':00');
		const dayWeek = (d_ini._d.getDay() === 0) ? 6 : d_ini._d.getDay() - 1;
		const multiAc = parseInt($('#event-multi').val(), 10);
		const comite = parseInt($('#event-comite').val(), 10);
		const cxc = (comite !== 1 && ($catId.val() === "3" || $catId.val() === "5"));

		let tCupos = '';
		let textCupos = '';
		let nCupos = '';
		$('.tipocupo').each(function () {
			const id = $(this).attr('id').split('id');

			if ($(this).val() !== '' && $('#cupos-id' + id[1]).val() !== '') {
				tCupos += $(this).val() + ',';
				textCupos += this.selectedOptions[0].text + ',';
				nCupos += $('#cupos-id' + id[1]).val() + ',';
			}
		});
		tCupos = tCupos.substring(0, tCupos.length - 1);
		textCupos = textCupos.substring(0, textCupos.length - 1);
		nCupos = nCupos.substring(0, nCupos.length - 1);

		const event = {
			title: $('#event-id :selected').text(),
			day: dayWeek,
			start: d_ini,
			end: d_fin,
			actId: $eventId.val(),
			piso: $('#piso-id').val(),
			pisoText: $('#piso-id :selected').text(),
			box: $boxatId.val(),
			boxText: $('#boxat-id :selected').text(),
			tipoCupos: tCupos,
			tipoCuposText: textCupos,
			cupos: nCupos,
			cuposObs: $('#obscupos-id').val(),
			especialidad: $('#iNespnom').val(),
			espSin: $espId.val(),
			espSinText: $('#esp-id :selected').text(),
			subespSin: $subespId.val(),
			subespSinText: $('#subesp-id :selected').text(),
			multi: multiAc,
			cxc: cxc,
			editable: false,
			backgroundColor: currColor,
			borderColor: currColor,
			overlap: false
		};
		console.log(event);

		if (checkActivity($eventId.val(), d_ini, d_fin, multiAc)) {
			$calendar.fullCalendar('renderEvent', event, true);
			let totalCupos = 0;

			// Evento es C+C
			if (cxc) {
				$('.tipocupo').each(function () {
					const id = $(this).attr('id').split('id');
					totalCupos += parseFloat($('#cupos-id' + id[1]).val());
				});
				const total = totalCupos + parseFloat($cuposAgenda.val());
				$cuposAgenda.val(number_format(total, 2, '.', ''));
			}
			// Evento es otro tipo
			else {
				$('.tipocupo').each(function () {
					const id = $(this).attr('id').split('id');
					totalCupos += parseFloat($('#cupos-id' + id[1]).val());
				});
				const totalOtro = totalCupos + parseFloat($cuposOtro.val());
				$cuposOtro.val(number_format(totalOtro, 2, '.', ''));
			}

			if (parseFloat($cuposAgenda.val()) >= parseFloat($('#cuposprog-id').val())) {
				$('#cuposprog-group').addClass('has-success');
				$('#cuposprog-icon').addClass('fa-check');
				$('#btnsubmit').prop('disabled', false);
			} else {
				$('#cuposprog-group').removeClass('has-success');
				$('#cuposprog-icon').removeClass('fa-check');
				$('#btnsubmit').prop('disabled', true);
			}

			$cuposInner.html('');
			$('#event-name, #event-id, #tipocupo-id0, #cupos-id0, #obscupos-id').val('');
			$subespId.val('').change();
			$('#event-group, #tipocupo-group0, #cupos-group0, #obscupos-group').removeClass('has-success');
			$('#cupos-icon0, #obscupos-icon').removeClass('fa-check');
			$('#event-name').prop('disabled', true);
			$(this).prop('disabled', true);
			numActividades++;
		} else
			swal({
				title: "Error!",
				html: "La actividad no puede superponerse a una ya registrada.<br>Por favor, revise sus horas de inicio y/o de término.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
	});

	$('#btn-load-agenda').click(function () {
		$.ajax({
			url: 'agenda/ajax.getPreviousAgenda.php',
			type: 'post',
			dataType: 'json',
			data: {year: $year.val(), period: $period.val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}).done(function (data) {
			const totalAct = data.done.length + data.pend.length;

			if (totalAct > 0) {
				$calendar.fullCalendar('removeEvents', function (event) {
					if (event.rendering !== 'background') return true;
				});

				$cuposAgenda.val('0.00');
				$calendar.fullCalendar('renderEvents', data.done, true);
				numActividades = data.done.length;

				$.each(data.done, function (k, v) {
					let tCupos = 0;
					const arrCupos = v.cupos.split(',');
					$.each(arrCupos, function (kc, vc) {
						tCupos += parseFloat(vc);
					});

					const total = parseFloat($cuposAgenda.val()) + tCupos;
					$cuposAgenda.val(number_format(total, 2, '.', ''));
				});

				if (parseFloat($cuposAgenda.val()) >= parseFloat($('#cuposprog-id').val())) {
					$('#cuposprog-group').addClass('has-success');
					$('#cuposprog-icon').addClass('fa-check');
				}

				if (data.pend.length > 0) {
					actPendientes = data.pend.length;
					$calendar.fullCalendar('renderEvents', data.pend, true);
				}
			} else
				swal({
					title: "Atención!",
					html: "No existe una agenda registrada en el período anterior.",
					type: "warning",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});
		});
	});

	$('#btnclear').click(function () {
		$calendar.fullCalendar('removeEvents');
		$('#cuposagen-id, #cuposotro-id').val('0');
		$cuposInner.html('');
		$('#dia-group, #tipocupo-group0, #cupos-group0, #obscupos-group, #cuposprog-group').removeClass('has-success');
		$('#cupos-icon0, #obscupos-icon, #cuposprog-icon').removeClass('fa-check');
		$('#piso-id, #tipocupo-id0').val('').change();
		$('#dia-id, #cupos-id0, #obscupos-id').val('');
		$horaInicio.timepicker('setTime', '08:00 AM');
		$horaFin.timepicker('setTime', '09:00 AM');
		$catId.val('').change();

		$.ajax({
			type: "POST",
			url: "agenda/ajax.getAgendaEventsByPerson.php",
			dataType: 'json',
			data: {year: $year.val(), period: $period.val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}).done(function (data) {
			$calendar.fullCalendar('renderEvents', data, true);

			let total = 0;
			let totalOtros = 0;
			$.each(data, function (k, v) {
				if (v.espec === $('#iNespec').val()) {
					let tCupos = 0;
					const arrCupos = v.cupos.split(',');

					if (v.cc === 1) {
						$.each(arrCupos, function (kc, vc) {
							tCupos += parseFloat(vc);
						});
						total += parseFloat(tCupos);
					} else {
						$.each(arrCupos, function (kc, vc) {
							tCupos += parseFloat(vc);
						});
						totalOtros += parseFloat(tCupos);
					}
				}
			});

			$cuposAgenda.val(number_format(total, 2, '.', ','));
			$cuposOtro.val(number_format(totalOtros, 2, '.', ','));
			if (total > 0) {
				$('#cuposprog-group').addClass('has-success');
				$('#cuposprog-icon').addClass('fa-check');
			}
		});
	});

	$('#btnnext').click(function () {
		if (numActividades > 0 && actPendientes === 0) {
			$('a[href="#tab_bh"]').tab('show')
		} else {
			let msg = 'Por favor, resuelva los siguientes problemas:<br><br>';

			if (numActividades === 0) msg += '- Debe agregar al menos una actividad a la agenda antes de definir los bloqueos de horario.<br><br>';
			if (actPendientes > 0) msg += '- Tiene actividades pendientes de reagendamiento. Puede hacer esto eliminándolas o reagendándolas en un horario diferente.';

			swal({
				title: "Error!",
				html: msg,
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
		}
	});
});