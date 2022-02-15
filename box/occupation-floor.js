$(document).ready(function () {
	var startOfWeek = moment().startOf('week').format('YYYY-MM-DD');
	/* var endOfWeek = moment().endOf('week').format('YYYY-MM-DD'); */

	$(document).on("focusin", "#iNdate", function () {
		$(this).prop('readonly', true);
	});
	$(document).on("focusout", "#iNdate", function () {
		$(this).prop('readonly', false);
	});

	$('#iNdate').datepicker({
		startView: 0,
		minViewMode: 0
	}).on('changeDate', function () {
		if ($.trim($(this).val()) !== '') {
			var value = $("#iNdate").val();
			var firstDate = moment(value, "DD/MM/YYYY").day(1).format("DD/MM/YYYY");
			$('#iNdate').datepicker('update', firstDate);
			$("#iNdate").val(firstDate);
			$('#gdate').removeClass('has-error').addClass('has-success');
			$('#icondate').removeClass('fa-remove fa-check').addClass('fa-check');
		}
	});

	$('#submitLoader').css('display', 'none');

	$('#iNestab, #iNpiso, #iNbox').change(function () {
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
				data: {floor: $('#iNpiso').val(), type: ''}
			}).done(function (resourceObjects) {
				callback(resourceObjects);
			});
		},
		events: function(start, end, timezone, callback) {
			$.ajax({
				url: 'box/ajax.getEventsByFloor.php',
				method: 'POST',
				dataType: 'json',
				data: {date: $('#iNdate').val(), floor: $('#iNpiso').val(), type: ''}
			}).done(function (eventObjects) {
				console.log(eventObjects);
				callback(eventObjects);
			});
		}
	});

	$('#iNestab').change(function () {
		$('#iNpiso').html('').append('<option value="">Seleccione un lugar</option>');
		//$('#calendar-boxat').fullCalendar('removeEvents');
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

	$('#btnsubmit').click(function () {
		var value = $("#iNdate").val();
		var date = moment(value, "DD/MM/YYYY").day(1).format("YYYY-MM-DD");
		$('#submitLoader').css('display', 'inline-block');
		$('#calendar').fullCalendar('gotoDate', date);
		$('#calendar').fullCalendar('refetchResources');
		$('#calendar').fullCalendar('refetchEvents');
		$('#submitLoader').css('display', 'none');
	});
});