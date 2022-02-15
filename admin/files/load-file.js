$(document).ready( function() {

    function fillBar() {
        var i = 0;
        if (i === 0) {
            i = 1;
            var progressBar = $('.progress-bar');
            var width = 0;
            id = setInterval(frame, 450);

            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    i = 0;
                } else {
                    width++;
                    progressBar.css('width', width + '%');
                }
            }
        }
    }

    function validateForm() {
        if ($('input[name="ifile[]"]').length > 1) {
            var bar = $('.progress');
            bar.css('display', 'block');
            $('#submitLoader').css('display', 'inline-block');
            fillBar();
            return true;
        } else {
            new Noty({
                text: '¡Error!<br>Debe escoger un archivo para importación.',
                type: 'error'
            }).show();
            return false;
        }
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');
        clearInterval(id);
        var progressBar = $('.progress-bar');
        progressBar.css('width', '100%');
        $('.progress').removeClass('active');

        if (response.type) {
            new Noty({
                text: 'El archivo ha sido importado con éxito.',
                type: 'success'
            }).show();

            $('input:file').MultiFile('reset');
            $('#iNdate').val();
            $('#iNresult').html(response.msg);
        } else {
            new Noty({
                text: '¡Error!<br>' + response.msg,
                type: 'error'
            }).show();
        }
    }
    
    var options = {
        url: 'admin/files/ajax.reader.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };
    
    $('#submitLoader').css('display', 'none');
    
    $(document).on("focusin", "#iNdate", function () {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#iNdate", function () {
        $(this).prop('readonly', false);
    });
    
    $('#iNdate').datepicker({
        startView: 1,
        minViewMode: 1,
        endDate: '0m'
    }).on('changeDate', function () {
        if ($.trim($(this).val()) !== '') {
            $('#gdate').removeClass('has-error').addClass('has-success');
            $('#icondate').removeClass('fa-remove fa-check').addClass('fa-check');
        }
    });

    $('#formNewFile').submit( function() {
        $(this).ajaxSubmit(options);
        return false;
    });
});