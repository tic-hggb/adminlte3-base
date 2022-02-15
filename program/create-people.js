$(document).ready(function () {
  function validateForm() {
    const values = true

    if (values) {
      $loader.css('display', 'inline-block')
      return true
    } else {
      return false
    }
  }

  function showResponse(response) {
    $loader.css('display', 'none')

    if (response.type) {
      new Noty({
        text: '<strong>¡Éxito!</strong><br>La persona ha sido guardada correctamente.',
        type: 'success'
      }).show()

      $form.clearForm()
      $clear.click()
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + response.msg,
        icon: 'error'
      }).show()
    }
  }

  const options = {
      url: 'program/ajax.insertPeople.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $rut = $('#iNrut'),
    $clear = $('#btnClear'),
    $form = $('#formNewPeople')

  $loader.css('display', 'none')

  $rut.change(function () {
    $('#iNid, #iNname, #iNprofesion, #iNespec, #iNtcontrato, #iNcorr, #iNhoras').removeClass('is-valid').val('')

    if ($rut.val() !== '') {
      $.ajax({
        url: 'program/ajax.getPeopleByRut.php',
        type: 'post',
        dataType: 'json',
        data: { rut: $rut.val() }
      }).done(function (d) {
        if (d.per_id !== null) {
          $('#iNid').val(d.per_id)
          $('#iNname').addClass('is-valid').val(d.per_nombres)
          $('#iNprofesion').addClass('is-valid').val(d.per_profid)
          $('#iNespec').addClass('is-valid').val(d.per_sis)
        }
      })
    }
  }).Rut({
    on_error: function () {
      swal({
        title: 'Error!',
        html: 'El RUT ingresado no es válido.',
        type: 'error'
      })

      $rut.removeClass('is-valid is-invalid').val('')
    }, on_success: function () {
      $rut.addClass('is-valid')
    }, format_on: 'keyup'
  })

  $('#iNcorr').change(function () {
    const $con = $('#iNtcontrato')

    if ($rut.val() !== '' && $con.val() !== '' && $(this).val() !== '') {
      $.ajax({
        url: 'program/ajax.getPeopleByRutLey.php',
        type: 'post',
        dataType: 'json',
        data: { rut: $rut.val(), con: $con.val(), corr: $(this).val() }
      }).done(function (d) {
        if (d.per_id !== null) {
          Swal.fire({
            icon: 'Error!',
            html: 'El RUT ingresado corresponde a una persona ya registrada bajo este correlativo y modalidad de contrato en este establecimiento.',
            type: 'error'
          })

          $('#iNcorr').addClass('is-invalid').val('')
        }
      })
    }
  })

  $('.form-control').change(function () {
    if ($.trim($(this).val()) !== '') {
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $clear.click(function () {
    $('.form-control').removeClass('is-valid is-invalid')
  })

  $form.submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })
})
