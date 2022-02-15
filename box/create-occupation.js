$(document).ready(function () {
	/**
	 * chequea si horas de inicio/fin se superponen entre sí
	 * @param ini
	 * @param fin
	 * @param multi
	 * @returns {boolean}
	 */
	function checkActivity(ini, fin, multi) {
		var free = true;

		$.each($('#calendar').fullCalendar('clientEvents'), function (k, o) {
			if (o.multi !== 1 && multi !== 1) {
				if (ini._d.toString() === o.start._d.toString() || fin._d.toString() === o.end._d.toString()) {
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
			}
		});

		return free;
	}

	/**
	 * limpia formulario de ingreso de asignacion
	 */
	function clearFormBox() {
		$('#iNbox, #iNpersona, #iNperid').val('');
		$("#iNdateas").datepicker("setDate", new Date());
		$('#hora-inicio').timepicker('setTime', '08:00 AM');
		$('#hora-fin').timepicker('setTime', '09:00 AM');
		$('#cupos-cont-inner').html('');
		$('#tipocupo-id').val('').change();
		$('#iNcat').val('').change();
		$('#gbox, #gpersona, #gdateas, #gevent, #tipocupo-group0, #cupos-group0, #gcat').removeClass('has-success');
		$('#icondateas, #cupos-icon0').removeClass('fa-check');
		$('#add-event').prop('disabled', true);
	}

	var startOfWeek = moment().startOf('week').format('YYYY-MM-DD');
	/* var endOfWeek = moment().endOf('week').format('YYYY-MM-DD'); */
	var idIt = 1;

	$('#iNdate').datepicker({
		startView: 0,
		minViewMode: 0
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			var value = $("#iNdate").val();
			var firstDate = moment(value, "DD/MM/YYYY").day(1).format("DD/MM/YYYY");
			$('#iNdate').datepicker('update', firstDate);
			$("#iNdate").val(firstDate);
			$('#gdate').removeClass('has-error has-success').addClass('has-success');
			$('#icondate').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$(document).on("focusin", "#iNdate", function () {
		$(this).prop('readonly', true);
	});
	$(document).on("focusout", "#iNdate", function () {
		$(this).prop('readonly', false);
	});

	$('#iNdateas').datepicker({
		startView: 0,
		minViewMode: 0,
		startDate: '-1d',
		endDate: '+2d'
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			$('#gdateas').removeClass('has-error has-success').addClass('has-success');
			$('#icondateas').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$(document).on("focusin", "#iNdateas", function () {
		$(this).prop('readonly', true);
	});
	$(document).on("focusout", "#iNdateas", function () {
		$(this).prop('readonly', false);
	});

	$('#submitLoader').css('display', 'none');
	$('#assign-box, #add-event').prop('disabled', true);

	$('#calendar').fullCalendar({
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		locale: 'es',
		timezone: 'local',
		height: 'parent',
		defaultView: 'timelineWorkWeek',
		views: {
			timelineWorkWeek: {
				type: 'timeline',
				duration: {days: 5}
			}
		},
		resourceAreaWidth: '100px',
		resourceLabelText: 'Boxes',
		groupByDateAndResource: true,
		defaultDate: moment(startOfWeek),
		firstDay: 1,
		header: {left: 'title', center: '', right: ''},
		columnHeaderFormat: 'dddd',
		minTime: '08:00:00',
		maxTime: '18:00:00',
		allDaySlot: false,
		defaultTimedEventDuration: '01:00:00',
		eventOrder: "tipo",
		eventRender: function (event, element) {
			var tit = ''; var cont = '';
			if (event.tipo === 1) {
				tit = event.especialista;
				cont = event.motivo_ausencia;
			} else {
				var cuposBlock = '';
				var arrCupos = event.cupos.split(',');
				var arrCuposText = event.tipoCuposText.split(',');
				$.each(arrCupos, function(k, v) {
					cuposBlock += '<br> - ' + v + ' cupos ' + arrCuposText[k];
				});

				tit = event.title;
				cont = event.especialista + '<br>Box ' + event.boxText + ', ' + event.pisoText + cuposBlock + '<br>' + event.espSinText + '<br>' + event.subespSinText;
			}
			element.popover({
				title: tit,
				html: true,
				content: cont,
				trigger: 'hover',
				placement: 'top',
				container: 'body'
			});
		},
		editable: false,
		droppable: false,
		resources: function (callback) {
			$.ajax({
				url: 'box/ajax.getResourcesByFloor.php',
				method: 'POST',
				dataType: 'json',
				data: {floor: $('#iNpiso').val(), type: $('#iNtbox').val()}
			}).done(function (resourceObjects) {
				callback(resourceObjects);
			});
		},
		events: function(start, end, timezone, callback) {
			$.ajax({
				url: 'box/ajax.getEventsByFloor.php',
				method: 'POST',
				dataType: 'json',
				data: {date: $('#iNdate').val(), floor: $('#iNpiso').val(), type: $('#iNtbox').val()}
			}).done(function (eventObjects) {
				callback(eventObjects);
			});
		}
	});

	$('#iNestab').change(function () {
		$('#iNpiso').html('').append('<option value="">Seleccione un lugar</option>');
		$('#gpiso').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#iNpiso').html('').append('<option value="">Cargando lugares...</option>');

			$.ajax({
				type: "POST",
				url: "box/ajax.getLugares.php",
				dataType: 'json',
				data: {est: $(this).val()}
			}).done(function (data) {
				$('#iNpiso').html('').append('<option value="">Seleccione un lugar</option>');

				$.each(data, function (k, v) {
					$('#iNpiso').append(
						$('<option></option>').val(v.lug_id).html(v.lug_nombre)
					);
				});
			});
		}
	});

	$('#iNpiso, #iNtbox').change(function () {
		$('#assign-box').prop('disabled', true);
		$('#iNbox').html('').append('<option value="">Cargando Boxes...</option>');

		if ($('#iNpiso').val() !== '') {
			$.ajax({
				type: "POST",
				url: "box/ajax.getBoxes.php",
				dataType: 'json',
				data: {id: $('#iNpiso').val(), type: $('#iNtbox').val()}
			}).done(function (data) {
				$('#iNbox').html('').append('<option value="">Seleccione Box</option>');

				$.each(data, function (k, v) {
					$('#iNbox').append(
						$('<option></option>').val(v.box_id).html(v.box_pasillo + ' - ' + v.box_numero)
					);
				});
			});
		}
	});

	$('#assign-box').click(function() {
		$('#assignBox').modal('toggle');
	});

	$('#assignBox').on('hidden.bs.modal', function () {
		clearFormBox();
	});

	$('#btnsubmit').click(function () {
		$('#assign-box').prop('disabled', false);

		var value = $("#iNdate").val();
		var date = moment(value, "DD/MM/YYYY").day(1).format("YYYY-MM-DD");

		$('#submitLoader').css('display', 'inline-block');
		$('#calendar').fullCalendar('gotoDate', date);
		$('#calendar').fullCalendar('refetchResources');
		$('#calendar').fullCalendar('refetchEvents');
		$('#submitLoader').css('display', 'none');
	});

	$('.timepicker').timepicker({
		showMeridian: false,
		showSeconds: false,
		minuteStep: 60,
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
		$('#cupos-group' + idIt).append($('#cupos-id0').clone().prop('id', 'cupos-id' + idIt).val('')).append('<span class="fa form-control-feedback" id="cupos-icon' + idIt + '"></span>');
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

	$('#cupos-cont').on('change', '.numcupos', function() {
		var idn = $(this).attr('id').split('id');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#cupos-group' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
			$('#cupos-group' + idn[1]).removeClass('has-error has-success');
			$('#cupos-icon' + idn[1]).removeClass('fa-remove fa-check');
		}
	}).on('change', '.tipocupo', function() {
		var idn = $(this).attr('id').split('id');

		if ($(this).val() !== '') {
			$('#tipocupo-group' + idn[1]).addClass('has-success');
		}
		else {
			$('#tipocupo-group' + idn[1]).removeClass('has-error has-success');
		}
	});

	$('#iNcat').change(function () {
		$('#add-event').prop('disabled', true);
		$('#iNesp').html('').append('<option value="">Seleccione especialidad</option>');
		$('#iNsubesp').html('').append('<option value="">Seleccione sub-especialidad</option>');
		$('#iNevent').html('').append('<option value="">Seleccione actividad</option>');
		$('#gesp, #gsubesp, #gevent').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#iNesp').html('').append('<option value="">Cargando especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getEspecialidades.php",
				dataType: 'json',
				data: {agr: $(this).val()}
			}).done(function (data) {
				$('#iNesp').html('').append('<option value="">Seleccione especialidad</option>');

				$.each(data, function (k, v) {
					$('#iNesp').append(
						$('<option></option>').val(v.sesp_id).html(v.sesp_nombre)
					);
				});
			});
		}
	});

	$('#iNesp').change(function () {
		$('#add-event').prop('disabled', true);
		$('#iNsubesp').html('').append('<option value="">Seleccione sub-especialidad</option>');
		$('#iNevent').html('').append('<option value="">Seleccione actividad</option>');
		$('#gsubesp, #gevent').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#iNsubesp').html('').append('<option value="">Cargando sub-especialidades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getSubEspecialidades.php",
				dataType: 'json',
				data: {esp: $(this).val()}
			}).done(function (data) {
				$('#iNsubesp').html('').append('<option value="">Seleccione sub-especialidad</option>');

				$.each(data, function (k, v) {
					$('#iNsubesp').append(
						$('<option></option>').val(v.ssub_id).html(v.ssub_nombre)
					);
				});
			});
		}
	});

	$('#iNsubesp').change(function () {
		$('#add-event').prop('disabled', true);
		$('#iNevent').html('').append('<option value="">Seleccione actividad</option>');
		$('#gevent').removeClass('has-error has-success');

		if ($(this).val() !== '') {
			$('#iNevent').html('').append('<option value="">Cargando actividades...</option>');

			$.ajax({
				type: "POST",
				url: "agenda/ajax.getActividades.php",
				dataType: 'json',
				data: {sesp: $(this).val()}
			}).done(function (data) {
				$('#iNevent').html('').append('<option value="">Seleccione actividad</option>');

				$.each(data, function (k, v) {
					var act_n = (v.act_multi === 1) ? ' (*)' : '';

					$('#iNevent').append(
						$('<option></option>').val(v.act_id).html(v.act_nombre + act_n)
					);
				});
			});
		}
	});

	$('#iNevent').change(function () {
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

	$('#iNbox, #iNdateas, #iNcupos, #iNevent').change(function () {
		($('#iNbox').val() !== '' && $('#iNdateas').val() !== '' && $.trim($('#cupos-id0').val()) !== '' && $('#iNevent').val() !== '') ? $('#add-event').prop('disabled', false) : $('#add-event').prop('disabled', true);
	});

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

	$('#iNestab, #iNpiso, #iNtbox, #iNbox, #iNpersona, #iNactividad, #tipocupo-id0, #cupos-id0, #iNobscupos, #iNcat, #iNesp, #iNsubesp, #iNevent').change(function () {
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


	function validateForm() {
		var values = false;

		var tmp = $('#iNdateas').val().split('/');
		var d_ini = moment(tmp[2] + '-' + tmp[1] + '-' + tmp[0] + ' ' + $('#hora-inicio').val() + ':00');
		var d_fin = moment(tmp[2] + '-' + tmp[1] + '-' + tmp[0] + ' ' + $('#hora-fin').val() + ':00');
		var multiAc = parseInt($('#event-multi').val(), 10);

		if (checkActivity(d_ini, d_fin, multiAc)) {
			values = true;
		}
		else
			swal({
				title: "Error!",
				html: "La actividad no puede superponerse a una ya registrada.<br>Por favor, revise sus horas de inicio y/o de término.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});

		if (values) {
			$('#submitLoader').css('display', 'inline-block');
			return true;
		}
	}

	function showResponse(response) {
		if (response.type) {
			new Noty({
				text: '<b>¡Éxito!</b><br>La asignación de box ha sido guardada correctamente.',
				type: 'success'
			}).show();

			$('#calendar').fullCalendar('refetchResources');
			$('#calendar').fullCalendar('refetchEvents');
			$('#assignBox').modal('toggle');
		}
		else {
			new Noty({
				text: '<b>¡Error!</b><br>' + response.msg,
				type: 'error'
			}).show();
		}
	}

	var options = {
		url: 'box/ajax.insertOccupation.php',
		type: 'post',
		dataType: 'json',
		beforeSubmit: validateForm,
		success: showResponse
	};

	$('#formNewOccupationAsign').submit(function () {
		$(this).ajaxSubmit(options);
		return false;
	});
});