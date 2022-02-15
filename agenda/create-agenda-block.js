$(document).ready(function () {
	const bloqueos = [];
	let idBlock = 0;

	$('#gdate .input-daterange').each(function () {
		$(this).datepicker({
			startView: 0,
			minViewMode: 0//,
			//startDate: '+0d'
		});
	}).change(function () {
		$('#gdate').removeClass('has-success has error');
		if ($.trim($('#iNdatei').val()) !== '' && $.trim($('#iNdatet').val() !== '')) {
			$('#gdate').addClass('has-success');
		}
	});

	const tableBlock = $("#tblocks").DataTable({
		'columns': [{width: "120px", className: "text-center"}, {width: "120px", className: "text-center"}, null, null, null, {"orderable": false, width: "40px", className: "text-center"}],
		'dom': "<'row'<'col-md-12't>>"
	});

	$('#add-new-block').click(function () {
		const f_ini = getDateToBD($('#iNdatei').val());
		const f_ter = getDateToBD($('#iNdatet').val());
		const dif = daysBetweenDates(f_ini, f_ter);
		const difWD = workingDaysBetweenDates(f_ini, f_ter);

		if ($.trim($('#iNdatei').val()) === '' || $.trim($('#iNdatet').val()) === '') {
			swal({
				title: "Error!",
				html: "Debe especificar una fecha de inicio y una de término para el bloqueo de horario.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gdate').addClass('has-error');
		} else if ($('#iNmotivo').val() === '') {
			swal({
				title: "Error!",
				html: "Debe especificar un motivo para el bloqueo de horario.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gmotivo').addClass('has-error');
		} else if ($('#iNdestino').val() === '') {
			swal({
				title: "Error!",
				html: "Debe especificar un destino para el bloqueo de horario.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gdestino').addClass('has-error');
		} else if (parseInt($('#iNmotivo').val(), 10) === 13 && difWD > parseInt($('#vacTotal-id').val(), 10)) {
			swal({
				title: "Error!",
				html: "Los días de vacaciones especificados son mayores a los disponibles.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gdate').addClass('has-error');
		} else if (parseInt($('#iNmotivo').val(), 10) === 17 && dif > parseInt($('#perTotal-id').val(), 10)) {
			swal({
				title: "Error!",
				html: "Los días de permisos especificados son mayores a los disponibles.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gdate').addClass('has-error');
		} else if ((parseInt($('#iNmotivo').val(), 10) === 8 || parseInt($('#iNmotivo').val(), 10) === 18) && difWD > parseInt($('#conTotal-id').val(), 10)) {
			swal({
				title: "Error!",
				html: "Los días de congreso especificados son mayores a los disponibles.",
				type: "error",
				showCancelButton: false,
				confirmButtonText: "Aceptar"
			});
			$('#gdate').addClass('has-error');
		} else {
			const objBlock = {
				id: idBlock,
				f_ini: f_ini,
				f_ter: f_ter,
				n_dias: dif + 1,
				n_diasH: difWD,
				motivo: $('#iNmotivo').val(),
				motivoText: $('#iNmotivo :selected').text(),
				destino: $('#iNdestino').val(),
				destinoText: $('#iNdestino :selected').text(),
				obs: $('#iNobs').val()
			};
			bloqueos.push(objBlock);

			tableBlock.row.add([
				$('#iNdatei').val(),
				$('#iNdatet').val(),
				$('#iNmotivo :selected').text(),
				$('#iNdestino :selected').text(),
				$('#iNobs').val(),
				'<button type="button" id="del_' + idBlock + '" class="delBlock btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Eliminar Bloqueo"><i class="fa fa-remove"></i></button>'
			]).draw();

			idBlock++;

			if (parseInt($('#iNmotivo').val(), 10) === 13) {
				const restantesV = parseInt($('#vacTotal-id').val(), 10) - difWD;

				$('#vacTotal-id').val(restantesV);
				if (restantesV === 0) {
					$('#vacTotal-group').hasClass('has-success');
					$('#vacTotal-icon').hasClass('fa-check');
				}
			}
			if (parseInt($('#iNmotivo').val(), 10) === 17) {
				const restantesP = parseInt($('#perTotal-id').val(), 10) - dif;

				$('#perTotal-id').val(restantesP);
				if (restantesP === 0) {
					$('#perTotal-group').hasClass('has-success');
					$('#perTotal-icon').hasClass('fa-check');
				}
			}
			if (parseInt($('#iNmotivo').val(), 10) === 8 || parseInt($('#iNmotivo').val(), 10) === 18) {
				const restantesC = parseInt($('#conTotal-id').val(), 10) - difWD;

				$('#conTotal-id').val(restantesC);
				if (restantesC === 0) {
					$('#conTotal-group').hasClass('has-success');
					$('#conTotal-icon').hasClass('fa-check');
				}
			}

			$('#iNdatei, #iNdatet').val('');
			$('#gdate').removeClass('has-success has-error');
			$('#iNmotivo, #iNdestino, #iNobs').val('').change();
		}
	});

	$('#tblocks').on('click', '.delBlock', function () {
		const uid = $(this).attr('id').split("_").pop();

		swal({
			title: "¿Está seguro de querer eliminar el bloqueo?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#dd6b55",
			confirmButtonText: "Sí"
		}).then((result) => {
			if (result.value) {
				tableBlock.clear().draw();

				console.log('beforeDelete');
				console.log(bloqueos);
				for(let i = 0; i < bloqueos.length; i++) {
					if (parseInt(uid, 10) === parseInt(bloqueos[i].id, 10)) {
						if (parseInt(bloqueos[i].motivo, 10) === 13) {
							const restantesV = parseInt($('#vacTotal-id').val(), 10) + parseInt(bloqueos[i].n_dias, 10);
							$('#vacTotal-id').val(restantesV);

							if (restantesV > 0) {
								$('#vacTotal-group').removeClass('has-success');
								$('#vacTotal-icon').removeClass('fa-check');
							}
						}
						if (parseInt(bloqueos[i].motivo, 10) === 17) {
							const restantesP = parseInt($('#perTotal-id').val(), 10) + parseInt(bloqueos[i].n_dias, 10);
							$('#perTotal-id').val(restantesP);

							if (restantesP > 0) {
								$('#perTotal-group').removeClass('has-success');
								$('#perTotal-icon').removeClass('fa-check');
							}
						}
						if (parseInt(bloqueos[i].motivo, 10) === 8 || parseInt(bloqueos[i].motivo, 10) === 18) {
							const restantesC = parseInt($('#conTotal-id').val(), 10) + parseInt(bloqueos[i].n_dias, 10);
							$('#conTotal-id').val(restantesC);

							if (restantesC > 0) {
								$('#conTotal-group').removeClass('has-success');
								$('#conTotal-icon').removeClass('fa-check');
							}
						}

						bloqueos.splice(i, 1);
						i--;
						console.log('deleting');
						console.log(bloqueos);
					}
				}

				console.log('beforeTable');
				console.log(bloqueos);
				$.each(bloqueos, function (k, v) {
					tableBlock.row.add([
						v.f_ini,
						v.f_ter,
						v.motivoText,
						v.destinoText,
						v.obs,
						'<button type="button" id="del_' + v.id + '" class="delBlock btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-remove"></i></button>'
					]);
				});

				tableBlock.draw();
			}
		});
	});

	$('#iNmotivo, #iNdestino, #iNobs').change(function () {
		const idn = $(this).attr('id').split('N');
		const val = $.trim($(this).val());

		if (val !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
			$('#g' + idn[1]).removeClass('has-error has-success');
			$('#icon' + idn[1]).removeClass('fa-remove fa-check');
		}
	});

	$('#btnprevious').click(function () {
		$('a[href="#tab_a"]').tab('show')
	});

	$('#btnclear-block').click(function () {
		$('#iNdatei, #iNdatet').val('');
		$('#gdate').removeClass('has-success has-error');
		$('#iNmotivo, #iNdestino, #iNobs').val('').change();
		tableBlock.clear().draw();
	});

	$('#btnsubmit').click(function () {
		const ev = $('#calendar').fullCalendar('clientEvents');
		const arr = [];

		$.each(ev, function (k, v) {
			if (v.actId !== '') {
				const obj = {
					actId: v.actId,
					subespSin: v.subespSin,
					piso: v.piso,
					box: v.box,
					tipoCupos: v.tipoCupos,
					cupos: v.cupos,
					cuposObs: v.cuposObs,
					dia: v.day,
					ini: v.start._i,
					fin: v.end._i
				};

				arr.push(obj);
			}
		});
		console.log(arr);

		$.ajax({
			url: 'agenda/ajax.insertAgenda.php',
			type: 'post',
			dataType: 'json',
			data: {pers: $('#iNpers').val(), esp: $('#iNespec').val(), events: arr, blocks: bloqueos}
		}).done(function (response) {
			if (response.type === true) {
				$('a[href="#tab_a"]').tab('show');

				new Noty({
					text: '<b>¡Éxito!</b><br>La agenda ha sido guardada correctamente. Volviendo a lista de médicos agendables...',
					type: 'success',
					callbacks: {
						onClose: function() {
							window.location.href = 'index.php?section=agenda&sbs=createagenda';
						}
					}
				}).show();
			}
			else {
				new Noty({
					text: '<b>¡Error!</b><br>' + response.msg,
					type: 'error'
				}).show();
			}
		});
	});
});