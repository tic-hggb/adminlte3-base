$(document).ready( function() {
    var tableProg = $("#tprogram").DataTable({
        "columns": [ null, null,
                    null, null,
                    null, null,
                    null, null,
                    null, null,
                    null, null,
                    null, null,
                    { type: 'num', className: "text-right t-bold" }, { type: 'num', className: "text-right" },
                    { type: 'num', className: "text-right t-bold" } ],
        'order': [[0, 'asc']],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11,
                                12, 13, 14, 15, 16]
                }
            }
        ]
    });
    
    var options = {
        url: 'reports/ajax.getServerPerform.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };
    
    $('#submitLoader').css('display', 'none');
    
    $(document).on("focusin", "#iNdate", function (event) {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#iNdate", function (event) {
        $(this).prop('readonly', false);
    });
    
    $('#iNdate').datepicker({
        startView: 1,
        minViewMode: 2,
        endDate: '+0y'
    }).on('changeDate', function () {
        if ($.trim($(this).val()) !== '') {
            $('#gdate').removeClass('has-error').addClass('has-success');
            $('#icondate').removeClass('fa-remove fa-check').addClass('fa-check');
        }
    });

	$('#iNcr').change(function () {
		$('#iNserv').html('').append('<option value="">Cargando servicios...</option>');
		$('#gserv').removeClass('has-error has-success');

		$.ajax({
			type: "POST",
			url: "reports/ajax.getServicios.php",
			dataType: 'json',
			data: {cr: $(this).val()}
		}).done(function (data) {
			$('#iNserv').html('').append('<option value="">TODOS LOS SERVICIOS</option>');

			$.each(data, function (k, v) {
				$('#iNserv').append(
					$('<option></option>').val(v.ser_id).html(v.ser_nombre)
				);
			});
		});
	});

	$('#iNplanta, #iNestab, #iNcr, #iNserv').change( function() {
		var idn = $(this).attr('id').split('N');
		var val = $.trim($(this).val());

		if (val !== '') {
			$('#g' + idn[1]).removeClass('has-error').addClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
		}
		else {
			$('#g' + idn[1]).removeClass('has-success');
			$('#icon' + idn[1]).removeClass('fa-check');
		}
	});
 
    
    $('#formNewProgram').submit( function() {
        $(this).ajaxSubmit(options);
        return false;
    });
    
    function validateForm(data, jF, o) {
        var values = true;

        if (values) {
            $('#submitLoader').css('display', 'inline-block');
            return true;
        }
        else {
            new Noty({
                text: 'Error al consultar datos. <br>Por favor, revise la fecha de consulta.',
                type: 'error'
            }).show();
            return false;
        }
    }
    
    function showResponse(response) {
        $('#submitLoader').css('display', 'none');
        tableProg.clear();
        
        if (response !== null) {

            $.each(response, function(k, v){
                tableProg.row.add( [
                    v.per_nombres, v.per_especialidad, v.m_01, v.m_02, v.m_03, v.m_04, v.m_05, v.m_06, v.m_07,
                    v.m_08, v.m_09, v.m_10, v.m_11, v.m_12, parseInt(v.per_prodtotal, 10), parseInt(v.per_progtotal, 10), parseInt(v.per_cumpl, 10)
                ] );
            });
        }
        
        tableProg.draw();
    }

    $(window).scroll( function() {
        if ($(this).scrollTop() > 200) {
            $('.scrollToTop').fadeIn();
        } 
        else {
            $('.scrollToTop').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scrollToTop').click( function() {
        $('html, body').animate({scrollTop : 0}, 800);
        return false;
    });
});