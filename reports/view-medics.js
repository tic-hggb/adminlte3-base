$(document).ready( function() {
    var tablePeople = $('#tpeople').DataTable({
        'columns': [ { 'width': '120px', className:'text-right' }, null, null, null, null ],
        'order': [[ 2, 'asc' ]],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            }
        ]
    });
    
    var options = {
        url: 'reports/ajax.getNPMedics.php',
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
        minViewMode: 1
    }).on('changeDate', function () {
        if ($.trim($(this).val()) !== '') {
            $('#gdate').removeClass('has-error').addClass('has-success');
            ;
            $('#icondate').removeClass('fa-remove fa-check').addClass('fa-check');
        }
    });
    

    $('#formViewMedics').submit( function() {
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
            noty({
                text: 'Error al consultar datos. <br>Por favor, revise la fecha de consulta.',
                type: 'error'
            });
            return false;
        }
    }
    
    function showResponse(response) {
        $('#submitLoader').css('display', 'none');
        tablePeople.clear();        

        if (response !== null) {

            $.each(response, function(k, v){
                tablePeople.row.add( [
                    v.per_rut,
                    v.per_nombres,
                    v.per_ap,
                    v.per_am,
                    v.per_profesion
                ] );
            });
        }
        
        tablePeople.draw();
    }

    //Check to see if the window is top if not then display button
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


