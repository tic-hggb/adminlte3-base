$(document).ready(function () {
	/**
	 * chequea si horas de inicio/fin se superponen entre sí
	 * @param id
	 * @param ini
	 * @param fin
	 * @param multi
	 * @returns {boolean}
	 */
	function checkActivity(id, ini, fin, multi) {
		var free = true;

		$.each($('#calendar').fullCalendar('clientEvents'), function (k, o) {
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

		$.each($('#calendar-boxat').fullCalendar('clientEvents'), function (k, o) {
			var sameAct = true;
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

	var numActividades = 0;
	var actPendientes = 0;

	$('#submitLoader').css('display', 'none');
	$('#add-new-event').prop('disabled', true);
	$('#event-name').prop('disabled', true);
	$('#cupos-id0, #obscupos-id').prop('disabled', true);

	$('#tipocupo-id').change(function () {
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
		defaultTime: false
	});
	$('#hora-inicio').timepicker('setTime', '08:00 AM');
	$('#hora-fin').timepicker('setTime', '09:00 AM');

	$('#hora-inicio').timepicker().on('changeTime.timepicker', function (e) {
		if (e.time.hours < 8) {
			swal({
				title: "Error!",
				text: "La hora de inicio no puede ser menor a las 8 de la mañana.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#hora-inicio').timepicker('setTime', '08:00 AM');
		}
		if (e.time.hours > 18) {
			swal({
				title: "Error!",
				text: "La hora de inicio no puede ser mayor a las 6 de la tarde.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#hora-inicio').timepicker('setTime', '18:00 PM');
		}

		if ($('#hora-fin').data('timepicker').hour <= e.time.hours) {
			var hour = e.time.hours + 1;
			hour = (hour < 10) ? '0' + hour : hour;
			var meridian = (hour > 11) ? 'PM' : 'AM';
			$('#hora-fin').timepicker('setTime', hour + ':00 ' + meridian);
		}
	});

	$('#hora-fin').timepicker().on('changeTime.timepicker', function (e) {
		if (e.time.hours < 9) {
			swal({
				title: "Error!",
				text: "La hora de término no puede ser menor a las 9 de la mañana.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#hora-fin').timepicker('setTime', '09:00 AM');
		}
		if (e.time.hours > 19) {
			swal({
				title: "Error!",
				text: "La hora de término no puede ser mayor a las 7 de la tarde.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#hora-fin').timepicker('setTime', '19:00 PM');
		}

		if ($('#hora-inicio').data('timepicker').hour >= e.time.hours) {
			var hour = e.time.hours - 1;
			hour = (hour < 10) ? '0' + hour : hour;
			var meridian = (hour > 11) ? 'PM' : 'AM';
			$('#hora-inicio').timepicker('setTime', hour + ':00 ' + meridian);
		}
	});

	$('#btn-add-cupo').click(function () {
		$('#cupos-cont-inner').append('<div class="form-group col-xs-6" id="tipocupo-group' + idIt + '"><label class="control-label">Tipo de cupo</label></div>');
		$('#tipocupo-group' + idIt).append($('#tipocupo-id0').clone().prop('id', 'tipocupo-id' + idIt));
		$('#cupos-cont-inner').append('<div class="form-group col-xs-5 has-feedback" id="cupos-group' + idIt + '"><label class="control-label">Cupos</label></div>');
		$('#cupos-group' + idIt).append($('#cupos-id0').clone().prop('id', 'cupos-id' + idIt).prop('disabled', true).val('')).append('<span class="fa form-control-feedback" id="cupos-icon' + idIt + '"></span>');
		$('#cupos-cont-inner').append('<div class="form-group col-xs-1" id="gDel' + idIt + '"><label class="control-label">...</label></div>');
		$('#gDel' + idIt).append('<button type="button" class="btn btn-block btn-danger btn-del" id="btnDel' + idIt + '"><i class="fa fa-minus"></i></button>');
		idIt++;
	});

	$('#cupos-cont-inner').on('click', '.btn-del', function () {
		var id = $(this).attr('id').split('Del');
		$('#tipocupo-group' + id[1]).remove();
		$('#cupos-group' + id[1]).remove();
		$('#gDel' + id[1]).remove();
	});

	var fM = firstMonday($('#iNperiod').val() - 1, $('#iNyear').val());
	var fDay = '0' + fM.getDate();
	var fMonth = ((fM.getMonth() + 1) < 10) ? '0' + (fM.getMonth() + 1) : (fM.getMonth() + 1);

	$('#calendar').fullCalendar({
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		locale: 'es',
		timezone: 'local',
		height: 'auto',
		defaultView: 'agendaWeek',
		defaultDate: moment($('#iNyear').val() + '-' + fMonth + '-' + fDay),
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
				$('#calendar').fullCalendar('removeEvents', event._id);
				numActividades--;
			});
		},
		eventRender: function (event, element) {
			element.find(".fc-bg").css("pointer-events", "none");

			if (event.rendering !== 'background') {
				numActividades++;
				element.append("<div style='position:absolute;bottom:0;right:0;z-index:10' class='closon'><button type='button' id='btnDeleteEvent' class='btn btn-xs btn-default' style='font-size:8px;border-radius:50%;'>X</button></div>");
			}

			element.find("#btnDeleteEvent").click(function () {
				if (typeof event.pendiente !== 'undefined') {
					actPendientes--;
				} else {
					var tCupos = 0;
					var arrCupos = event.cupos.split(',');
					$.each(arrCupos, function (k, v) {
						tCupos += parseFloat(v);
					});

					if (event.cxc) {
						var total = parseFloat($('#cuposagen-id').val()) - tCupos;
						$('#cuposagen-id').val(number_format(total, 2, '.', ''));
					} else {
						var total = parseFloat($('#cuposotro-id').val()) - tCupos;
						$('#cuposotro-id').val(number_format(total, 2, '.', ''));
					}
					numActividades--;
				}

				element.popover('destroy');

				if (parseFloat($('#cuposagen-id').val()) >= parseFloat($('#cuposprog-id').val())) {
					$('#cuposprog-group').addClass('has-success');
					$('#cuposprog-icon').addClass('fa-check');
				} else {
					$('#cuposprog-group').removeClass('has-success');
					$('#cuposprog-icon').removeClass('fa-check');
				}

				$('#calendar').fullCalendar('removeEvents', event._id);
			});

			var cuposBlock = '';
			var arrCupos = event.cupos.split(',');
			var arrCuposText = event.tipoCuposText.split(',');
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
			$('#boxat-id').change();
		},
		editable: true,
		droppable: false,
		events: {
			url: 'agenda/ajax.getAgendaEventsByPerson.php',
			type: 'POST',
			data: {year: $('#iNyear').val(), period: $('#iNperiod').val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}
	});

	$('#calendar-boxat').fullCalendar({
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		locale: 'es',
		timezone: 'local',
		height: 'auto',
		defaultView: 'agendaWeek',
		defaultDate: moment($('#iNyear').val() + '-' + fMonth + '-' + fDay),
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
			//console.log(event);
			var multi = (event.multi === 1) ? ' (*)' : '';
			var tmp = event.start._i.split(' ');
			var h_ini = tmp[1];
			var tmp = event.end._i.split(' ');
			var h_fin = tmp[1];

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
		var idn = $(this).attr('id').split('-');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#' + idn[0] + '-group').removeClass('has-error').addClass('has-success');
			$('#' + idn[0] + '-icon').removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#' + idn[0] + '-group').removeClass('has-error has-success');
			$('#' + idn[0] + '-icon').removeClass('fa-remove fa-check');
		}
	});

	$('#cupos-cont').on('change', '.numcupos', function () {
		var idn = $(this).attr('id').split('id');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#cupos-group' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
			$('#obscupos-id').prop('disabled', false);
		} else {
			$('#cupos-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove fa-check');
		}

		var n_c = false;
		$('.numcupos').each(function () {
			if ($.trim($(this).val()) !== '') n_c = true;
		});
		if (!n_c) $('#obscupos-id').prop('disabled', true);
	}).on('change', '.tipocupo', function () {
		var idn = $(this).attr('id').split('id');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#tipocupo-group' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#cupos-id' + idn[1]).prop('disabled', false);
		} else {
			$('#tipocupo-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-id' + idn[1]).prop('disabled', true);
		}
	});

	$('#piso-id').change(function () {
		$('#add-new-event').prop('disabled', true);
		$('#boxat-id').html('').append('<option value="">Seleccione box de atención</option>');
		$('#box-detail-title').html('Ocupación del Box');
		$('#calendar-boxat').fullCalendar('removeEvents');
		$('#boxat-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#boxat-id').html('').append('<option value="">Cargando boxes...</option>');

			$.ajax({
				type: "POST",
				url: "box/ajax.getBoxes.php",
				dataType: 'json',
				data: {id: $(this).val(), type: ''}
			}).done(function (data) {
				$('#boxat-id').html('').append('<option value="">Seleccione box de atención</option>');

				$.each(data, function (k, v) {
					$('#boxat-id').append(
						$('<option></option>').val(v.box_id).html(v.box_pasillo + ' - ' + v.box_numero)
					);
				});
			});
		}
	});

	$('#boxat-id').change(function () {
		$('#add-new-event').prop('disabled', true);
		$('#calendar-boxat').fullCalendar('removeEvents');

		if ($(this).val() !== '') {
			$('#box-detail-title').html('Ocupación del Box ' + $('#boxat-id :selected').text());

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getAgendaEvents.php",
				dataType: 'json',
				data: {year: $('#iNyear').val(), period: $('#iNperiod').val(), box: $(this).val()}
			}).done(function (data) {
				$('#calendar-boxat').fullCalendar('renderEvents', data, true);
			});
		}
	});

	$('#cat-id').change(function () {
		$('#add-new-event').prop('disabled', true);
		$('#esp-id').html('').append('<option value="">Seleccione especialidad</option>');
		$('#subesp-id').html('').append('<option value="">Seleccione sub-especialidad</option>');
		$('#event-id').html('').append('<option value="">Seleccione actividad</option>');
		$('#esp-group, #subesp-group, #event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#esp-id').html('').append('<option value="">Cargando especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getEspecialidades.php",
				dataType: 'json',
				data: {agr: $(this).val()}
			}).done(function (data) {
				$('#esp-id').html('').append('<option value="">Seleccione especialidad</option>');

				$.each(data, function (k, v) {
					$('#esp-id').append(
						$('<option></option>').val(v.sesp_id).html(v.sesp_nombre)
					);
				});
			});
		}
	});

	$('#esp-id').change(function () {
		$('#add-new-event').prop('disabled', true);
		$('#subesp-id').html('').append('<option value="">Seleccione sub-especialidad</option>');
		$('#event-id').html('').append('<option value="">Seleccione actividad</option>');
		$('#subesp-group, #event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#subesp-id').html('').append('<option value="">Cargando sub-especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getSubEspecialidades.php",
				dataType: 'json',
				data: {esp: $(this).val()}
			}).done(function (data) {
				$('#subesp-id').html('').append('<option value="">Seleccione sub-especialidad</option>');

				$.each(data, function (k, v) {
					$('#subesp-id').append(
						$('<option></option>').val(v.ssub_id).html(v.ssub_nombre)
					);
				});
			});
		}
	});

	$('#subesp-id').change(function () {
		$('#add-new-event').prop('disabled', true);
		$('#event-id').html('').append('<option value="">Seleccione actividad</option>');
		$('#event-group').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#event-id').html('').append('<option value="">Cargando actividades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getActividades.php",
				dataType: 'json',
				data: {sesp: $(this).val()}
			}).done(function (data) {
				$('#event-id').html('').append('<option value="">Seleccione actividad</option>');

				$.each(data, function (k, v) {
					var act_n = (v.act_multi === 1) ? ' (*)' : '';

					$('#event-id').append(
						$('<option></option>').val(v.act_id).html(v.act_nombre + act_n)
					);
				});
			});
		}
	});

	$('#event-id').change(function () {
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
		($('#boxat-id').val() !== '' && $('#dia-id').val() !== '' && $.trim($('#cupos-id0').val()) !== '' && $('#event-id').val() !== '') ? $('#add-new-event').prop('disabled', false) : $('#add-new-event').prop('disabled', true);
	});

	var currColor = '#3c8dbc';

	$('#color-chooser > li > a').click(function (e) {
		e.preventDefault();
		currColor = $(this).css('color');
		$('#add-new-event').css({'background-color': currColor, 'border-color': currColor});
	});

	$('#add-new-event').click(function (e) {
		e.preventDefault();
		var d_ini = moment($('#iNyear').val() + '-' + $('#iNperiod').val() + '-' + $('#dia-id').val() + ' ' + $('#hora-inicio').val() + ':00');
		var d_fin = moment($('#iNyear').val() + '-' + $('#iNperiod').val() + '-' + $('#dia-id').val() + ' ' + $('#hora-fin').val() + ':00');
		var dayWeek = (d_ini._d.getDay() === 0) ? 6 : d_ini._d.getDay() - 1;
		var multiAc = parseInt($('#event-multi').val(), 10);
		var comite = parseInt($('#event-comite').val(), 10);
		var cxc = (comite !== 1 && ($('#cat-id').val() === "1" || $('#cat-id').val() === "2"));

		var tCupos = '';
		var textCupos = '';
		var nCupos = '';
		$('.tipocupo').each(function () {
			var id = $(this).attr('id').split('id');
			tCupos += $(this).val() + ',';
			textCupos += this.selectedOptions[0].text + ',';
			nCupos += $('#cupos-id' + id[1]).val() + ',';
		});
		tCupos = tCupos.substring(0, tCupos.length - 1);
		textCupos = textCupos.substring(0, textCupos.length - 1);
		nCupos = nCupos.substring(0, nCupos.length - 1);

		var event = {
			title: $('#event-id :selected').text(),
			day: dayWeek,
			start: d_ini,
			end: d_fin,
			actId: $('#event-id').val(),
			piso: $('#piso-id').val(),
			pisoText: $('#piso-id :selected').text(),
			box: $('#boxat-id').val(),
			boxText: $('#boxat-id :selected').text(),
			tipoCupos: tCupos,
			tipoCuposText: textCupos,
			cupos: nCupos,
			cuposObs: $('#obscupos-id').val(),
			especialidad: $('#iNespnom').val(),
			espSin: $('#esp-id').val(),
			espSinText: $('#esp-id :selected').text(),
			subespSin: $('#subesp-id').val(),
			subespSinText: $('#subesp-id :selected').text(),
			multi: multiAc,
			cxc: cxc,
			editable: false,
			backgroundColor: currColor,
			borderColor: currColor,
			overlap: false
		};
		console.log(event);

		if (checkActivity($('#event-id').val(), d_ini, d_fin, multiAc)) {
			$('#calendar').fullCalendar('renderEvent', event, true);
			var totalCupos = 0;

			// Evento es C+C
			if (cxc) {
				$('.tipocupo').each(function () {
					var id = $(this).attr('id').split('id');
					totalCupos += parseFloat($('#cupos-id' + id[1]).val());
				});
				var total = totalCupos + parseFloat($('#cuposagen-id').val());
				$('#cuposagen-id').val(number_format(total, 2, '.', ''));
			}
			// Evento es otro tipo
			else {
				$('.tipocupo').each(function () {
					var id = $(this).attr('id').split('id');
					totalCupos += parseFloat($('#cupos-id' + id[1]).val());
				});
				var totalOtro = totalCupos + parseFloat($('#cuposotro-id').val());
				$('#cuposotro-id').val(number_format(totalOtro, 2, '.', ''));
			}

			if (parseFloat($('#cuposagen-id').val()) >= parseFloat($('#cuposprog-id').val())) {
				$('#cuposprog-group').addClass('has-success');
				$('#cuposprog-icon').addClass('fa-check');
				$('#btnsubmit').prop('disabled', false);
			} else {
				$('#cuposprog-group').removeClass('has-success');
				$('#cuposprog-icon').removeClass('fa-check');
				$('#btnsubmit').prop('disabled', true);
			}

			$('#cupos-cont-inner').html('');
			$('#event-name, #event-id, #tipocupo-id0, #cupos-id0, #obscupos-id').val('');
			$('#subesp-id').val('').change();
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
			data: {year: $('#iNyear').val(), period: $('#iNperiod').val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}).done(function (data) {
			var totalAct = data.done.length + data.pend.length;

			if (totalAct > 0) {
				$('#calendar').fullCalendar('removeEvents', function (event) {
					if (event.rendering !== 'background') return true;
				});

				$('#cuposagen-id').val('0.00');
				$('#calendar').fullCalendar('renderEvents', data.done, true);
				numActividades = data.done.length;

				$.each(data.done, function (k, v) {
					var total = parseFloat($('#cuposagen-id').val()) + parseFloat(v.cupos);
					$('#cuposagen-id').val(number_format(total, 2, '.', ''));
				});

				if (parseFloat($('#cuposagen-id').val()) >= parseFloat($('#cuposprog-id').val())) {
					$('#cuposprog-group').addClass('has-success');
					$('#cuposprog-icon').addClass('fa-check');
				}

				if (data.pend.length > 0) {
					actPendientes = data.pend.length;
					$('#calendar').fullCalendar('renderEvents', data.pend, true);
				}
			} else
				swal({
					title: "Error!",
					html: "No existe una agenda registrada en el período anterior.",
					type: "error",
					showCancelButton: false,
					confirmButtonText: "Aceptar"
				});
		});
	});

	$('#btnclear').click(function () {
		$('#calendar').fullCalendar('removeEvents');
		$('#cuposagen-id, #cuposotro-id').val('0');
		$('#cupos-cont-inner').html('');
		$('#dia-group, #tipocupo-group0, #cupos-group0, #obscupos-group, #cuposprog-group').removeClass('has-success');
		$('#cupos-icon0, #obscupos-icon, #cuposprog-icon').removeClass('fa-check');
		$('#piso-id, #tipocupo-id').val('').change();
		$('#dia-id, #cupos-id, #obscupos-id').val('');
		$('#hora-inicio').timepicker('setTime', '08:00 AM');
		$('#hora-fin').timepicker('setTime', '09:00 AM');
		$('#cat-id').val('').change();

		$.ajax({
			type: "POST",
			url: "agenda/ajax.getAgendaEventsByPerson.php",
			dataType: 'json',
			data: {year: $('#iNyear').val(), period: $('#iNperiod').val(), per: $('#iNpers').val(), esp: $('#iNespec').val(), est: $('#iNestab').val()}
		}).done(function (data) {
			$('#calendar').fullCalendar('renderEvents', data, true);

			var total = 0;
			var totalOtros = 0;
			$.each(data, function (k, v) {
				if (v.espec == $('#iNespec').val()) {
					var tCupos = 0;
					var arrCupos = v.cupos.split(',');

					if (v.cc == 1) {
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

			$('#cuposagen-id').val(number_format(total, 2, '.', ','));
			$('#cuposotro-id').val(number_format(totalOtros, 2, '.', ','));
			if (total > 0) {
				$('#cuposprog-group').addClass('has-success');
				$('#cuposprog-icon').addClass('fa-check')
			}
		});
	});

	$('#btnnext').click(function () {
		if (numActividades > 0 && actPendientes === 0) {
			$('a[href="#tab_bh"]').tab('show')
		} else {
			var msg = 'Por favor, resulva los siguientes problemas:<br><br>';

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