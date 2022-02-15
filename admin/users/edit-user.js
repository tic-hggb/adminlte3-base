$(document).ready( function() {
    var _vEmail = true;
    
    var options = {
        url:            'admin/users/ajax.editUser.php',
        type:           'post',
        dataType:       'json',
        beforeSubmit:   validateForm,
        success:        showResponse
    };

    $('#submitLoader').css('display', 'none');

    $('#iNemail').change( function() {
        $('#gemail').removeClass('has-error').removeClass('has-success');
        $('#iconEmail').removeClass('fa-remove').removeClass('fa-check');

        var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/; 

        if($(this).val() !== '') {
            if (!email_reg.test($.trim($(this).val()))) {
                _vEmail = false;
                $('#gemail').addClass('has-error');
                $('#iconEmail').addClass('fa-remove');
            }
            else {
                _vEmail = true;
                $('#gemail').addClass('has-success');
                $('#iconEmail').addClass('fa-check');
            }
        }
    });
    
    $('#iNname, #iNlastnamep, #iNlastnamem, #iNpassword').change( function() {
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
    
    $('#btnClear').click( function() {
        $('#gname, #glastnamep, #glastnamem, #gemail, #gusername, #gpassword').removeClass('has-error').removeClass('has-success');
        $('#iconname, #iconlastnamep, #iconlastnamem, #iconEmail, #iconUsername, #iconpassword').removeClass('fa-remove').removeClass('fa-check');
    });
    

    $('#formNewUser').submit( function() {
        $(this).ajaxSubmit(options);
        return false;
    });
    
    function validateForm(data, jF, o) {
        if (_vEmail) {
            $('#submitLoader').css('display', 'inline-block');
            return true;
        }
        else {
            new Noty({
                text: 'Error al editar usuario. <br>Por favor corrija los campos marcados con errores',
                type: 'error'
            }).show();
            return false;
        }
    }
    
    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type === true) {
            new Noty({
                text: 'El usuario ha sido editado con éxito. Recargando formulario...',
                type: 'success',
                callbacks: {
                    afterClose: function () {
                        setTimeout(function () {
                            document.location.reload(true);
                        });
                    }
                }
            }).show();

            $('#btnClear').click();
            $('input:file').MultiFile('reset');
        }
        else {
            new Noty({
                text: 'Hubo un problema al editar el usuario. <br>' + response.msg,
                type: 'error'
            }).show();
        }
    }
});