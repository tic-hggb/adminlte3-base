$(document).ready(function () {
	var tableProg = $("#tprogram").DataTable({
		"columns": [
			{width: "100px", className: "text-right"},
			{width: "320px"}, null,
			null, null,
			{className: "text-center"}, {className: "text-center"},
			{className: "text-center"}, {className: "text-center"},
			{className: "text-center"}, {className: "text-center"},
			{className: "text-center"}, {className: "text-center"}],
		'order': [[1, 'asc']],
		'drawCallback' : function () {
			console.log('asd');
			$('#submitLoader').css('display', 'none');
		}
	});

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

	$('#iNperiodo, #iNestab').change(function () {
		var idn = $(this).attr('id').split('N');

		if ($.trim($(this).val()) !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		} else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});

	$('#btnsubmit').click(function () {
		$('#submitLoader').css('display', 'inline-block');
		tableProg.ajax
			.url('reports/ajax.getServerProgramPerc.php?iyear=' + $('#iNyear').val() + '&iperiodo=' + $('#iNperiodo').val() + '&iestab=' + $('#iNestab').val())
			.load();
	});

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