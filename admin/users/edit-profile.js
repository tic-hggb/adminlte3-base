$(document).ready(function () {
  let _vEmail = true

  const options = {
      url: 'admin/users/ajax.editProfile.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $form = $('#formNewUser'),
    $btn = $('#btnsubmit')

  $loader.css('display', 'none')

  function validateForm() {
    if (_vEmail) {
      $loader.css('display', 'inline-block')
      return true
    } else {
      new Noty({
        text: 'Error al editar usuario. <br>Por favor corrija los campos marcados con errores',
        type: 'error'
      }).show()
      return false
    }
  }

  function showResponse(response) {
    $loader.css('display', 'none')

    if (response.type) {
      new Noty({
        text: 'El usuario ha sido editado con éxito. Recargando formulario...',
        type: 'success',
        callbacks: {
          afterClose: function () {
            document.location.reload(true)
          }
        }
      }).show()

      $('#btnClear').click()
    } else {
      new Noty({
        text: 'Hubo un problema al editar el usuario.<br>' + response.msg,
        type: 'error'
      }).show()
    }
  }

  $('#iNemail').change(function () {
    $(this).removeClass('is-invalid is-valid')

    const email_reg = /^([\w-.]+@([\w-]+\.)+[\w-]{2,4})?$/

    if ($(this).val() !== '') {
      if (!email_reg.test($.trim($(this).val()))) {
        _vEmail = false
        $(this).addClass('is-invalid')
      } else {
        _vEmail = true
        $(this).addClass('is-valid')
      }
    }
  })

  const myDropzone = new Dropzone('div#imageUpload', {
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: false,
    maxFiles: 1,
    paramName: 'iuserimage[]',
    clickable: true,
    url: 'admin/users/ajax.editProfile.php',
    init: function () {},
    error: function (file, response) {},
    success: function (file, response) {},
    reset: function () {
      this.removeAllFiles(true)
    }
  })

  $('.form-control').not('#iNemail').change(function () {
    if ($.trim($(this).val()) !== '') {
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  /*$('#formNewUser').submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })*/
})
