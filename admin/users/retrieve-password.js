$(document).ready(function () {
  const options = {
      url: 'admin/users/ajax.retrievePassword.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $username = $('#iNusername'),
    $clear = $('#btnClear'),
    $form = $('#formChangePass')

  $loader.css('display', 'none')

  $username.change(function () {
    if ($(this).val() !== '') {
      $(this).addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $clear.click(function () {
    $username.removeClass('is-valid')
  })

  $form.submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })

  function validateForm() {
    $loader.css('display', 'inline-block')
    return true
  }

  function showResponse(response) {
    $loader.css('display', 'none')

    if (response.type) {
      new Noty({
        text: '<strong>¡Éxito!</strong><br>La nueva contraseña ha sido enviada a su correo. Volviendo a la pantalla de inicio...',
        type: 'success',
        callbacks: {
          afterClose: function () {
            location.href = 'index.php'
          }
        }
      }).show()

      $clear.click()
      $form.clearForm()
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + response.msg,
        type: 'error'
      }).show()

      $username.val('').removeClass('is-valid')
    }
  }
})
