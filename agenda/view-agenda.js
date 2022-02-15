$(document).ready(function () {
	var fM = firstMonday($('#iNperiod').val()-1, $('#iNyear').val());
	var fDay = '0' + fM.getDate();
	var fMonth = ((fM.getMonth() + 1) < 10) ? '0' + (fM.getMonth() + 1) : (fM.getMonth() + 1);

	$('#tblocks').DataTable();

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
		allDaySlot: false,
		defaultTimedEventDuration: '01:00:00',
		eventRender: function (event, element) {
			var cuposBlock = '';
			var arrCupos = event.cupos.split(',');
			var arrCuposText = event.tipoCuposText.split(',');
			$.each(arrCupos, function(k, v) {
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
		editable: false,
		droppable: false,
		events: {
			url: 'agenda/ajax.getAgendaByPersonEsp.php',
			type: 'POST',
			data: { year: $('#iNyear').val(), period: $('#iNperiod').val(), per: $('#iNpers').val(), est: $('#iNestab').val(), esp: $('#iNesp').val() }
		}
	});
});