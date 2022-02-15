$(document).ready( function() {
    var _vPass = true;
    
    var options = {
        url:            'admin/users/ajax.editPassword.php',
        type:           'post',
        dataType:       'json',
        beforeSubmit:   validateForm,
        success:        showResponse
    };
    
    $('#submitLoader').css('display', 'none');
    
    $('#iNoldpass').change( function() {
        $('#goldpass').removeClass('has-error').removeClass('has-success');
        $('#iconoldpass').removeClass('fa-remove').removeClass('fa-check');
        
        $.ajax( {
            type    : 'POST',
            url     : 'admin/users/ajax.checkPass.php',
            dataType: 'json',
            data    : { id: $('#uid').val(), pass: $('#iNoldpass').val() }
        }).done(function(r){
            if (r.msg === false) {
                $('#goldpass').addClass('has-error');
                $('#iconoldpass').addClass('fa-remove');
                $('#iNoldpass').val( '' );

                swal("Error!", "La contraseña actual ingresada no es correcta.", "error");
            }
            else {
                $('#goldpass').addClass('has-success');
                $('#iconoldpass').addClass('fa-check');
            }
        });
    });
    
    $('#iNnewpass').change( function() {
        if ($(this).val() !== '' && $('#iNcnpass').val() !== '') {
            if ($(this).val() !== $('#iNcnpass').val()) {
                swal("Error!", "Las contraseñas ingresadas no coinciden.", "error");
                
                $('#gnewpass, #gcnpass').removeClass('has-success').addClass('has-error');
                $('#iconnewpass, #iconcnpass').removeClass('fa-check').addClass('fa-remove');
                $('#iNcnpass').val( '' );
            }
            else {
                $('#gnewpass, #gcnpass').removeClass('has-error').addClass('has-success');
                $('#iconnewpass, #iconcnpass').removeClass('fa-remove').addClass('fa-check');
            }
        }
    });
    
    $('#iNcnpass').change( function() {
        if ($(this).val() !== '' && $('#iNnewpass').val() !== '') {
            if ($(this).val() !== $('#iNnewpass').val()) {
                swal("Error!", "Las contraseñas ingresadas no coinciden.", "error");
                
                $('#gnewpass, #gcnpass').removeClass('has-success').addClass('has-error');
                $('#iconnewpass, #iconcnpass').removeClass('fa-check').addClass('fa-remove');
                $('#iNcnpass').val( '' );
            }
            else {
                $('#gnewpass, #gcnpass').removeClass('has-error').addClass('has-success');
                $('#iconnewpass, #iconcnpass').removeClass('fa-remove').addClass('fa-check');
            }
        }
    });
    
    $('#btnClear').click( function() {
        $('#goldpass, #gnewpass, #gcnpass').removeClass('has-error').removeClass('has-success');
        $('#iconoldpass, #iconnewpass, #iconcnpass').removeClass('fa-remove').removeClass('fa-check');
    });
    

    $('#formChangePass').submit( function() {
        $(this).ajaxSubmit(options);
        return false;
    });

    function validateForm(data, jF, o) {
        if ($('#iNnewpass').val() !== $('#iNcnpass').val()) {
            swal("Error!", "Las contraseñas ingresadas no coinciden.", "error");
            _vPass = false;
        }

        if (_vPass) {
            $('#submitLoader').css('display', 'inline-block');
            return true;
        }
    }
    
    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type === true) {
            new Noty({
                text: 'La contraseña ha sido cambiada con éxito. Volviendo a la pantalla de inicio...',
                type: 'success',
                callbacks: {
                    afterClose: function () {
                        setTimeout(function () {
                            location.href = 'index.php';
                        });
                    }
                }
            }).show();

            $('#btnClear').click();
            $('#formChangePass').clearForm();
        }
        else {
            new Noty({
                text: 'Hubo un problema al cambiar la contraseña. <br>' + response.msg,
                type: 'error'
            }).show();
        }
    }
});