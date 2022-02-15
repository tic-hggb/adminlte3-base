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
        text: '<strong>¡Éxito!</strong><br>El diagnóstico ha sido guardado correctamente.',
        type: 'success'
      }).show()

      $form.clearForm()
      $clear.click()
    } else {
      new Noty({
        text: '<strong>¡Error!</strong><br>' + response.msg,
        type: 'error'
      }).show()
    }
  }

  function calTotal() {
    let t = 0

    $sum.each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        t += parseInt($(this).val(), 10)
      }
    })

    $total.val(number_format(t, 0, '', '.'))

    if (t > 0) {
      $total.addClass('is-valid')
    } else {
      $total.removeClass('is-valid')
    }
  }

  function calTotalIQ() {
    let t = 0

    $sumiq.each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        t += parseInt($(this).val(), 10)
      }
    })

    $totaliq.val(number_format(t, 0, '', '.'))

    if (t > 0) {
      $totaliq.addClass('is-valid')
    } else {
      $totaliq.removeClass('is-valid')
    }
  }

  function calTotalHes() {
    let t = 0

    $sumhes.each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        t += parseInt($(this).val(), 10)
      }
    })

    $totalesp.val(number_format(t, 0, '', '.'))

    if (t > 0) {
      $totalesp.addClass('is-valid')
    } else {
      $totalesp.removeClass('is-valid')
    }
  }

  const options = {
      url: 'program/ajax.insertDiagnosis.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $gdate = $('#gdate'),
    $date = $('#iNdate'),
    $serv = $('#iNserv'),
    $esp = $('#iNesp'),
    $sum = $('.sum'),
    $sumiq = $('.sumiq'),
    $sumhes = $('.sumhes'),
    $clear = $('#btnClear'),
    $form = $('#formNewDiagno'),
    $total = $('#iNtotal'),
    $totaliq = $('#iNtotaliq'),
    $totalesp = $('#iNtesp')

  $(document).on('keyup', '.input-number', function () {
    const v = this.value
    if ($.isNumeric(v) === false) {
      this.value = this.value.slice(0, -1)
    }
  })

  $loader.css('display', 'none')

  $(document).on('focusin', '#iNdate', function () {
    $(this).prop('readonly', true)
  }).on('focusout', '#iNdate', function () {
    $(this).prop('readonly', false)
  })

  $gdate.datetimepicker({
    format: 'YYYY',
    maxDate: moment()
  })
  $gdate.on('change.datetimepicker', function () {
    $date.addClass('is-valid')

    if ($.trim($date.val()) !== '' && $serv.val() !== '') {
      $.ajax({
        type: 'POST',
        url: 'program/ajax.getEspNoDiagno.php',
        dataType: 'json',
        data: { idate: $date.val(), iserv: $serv.val() }
      }).done(function (data) {
        $esp.html('').append('<option value="">Seleccione especialidad</option>')

        $.each(data, function (k, v) {
          $esp.append(
            $('<option></option>').val(v.esp_id).html(v.esp_nombre)
          )
        })
      })
    }
  })

  $serv.change(function () {
    $esp.html('')
    if ($.trim($(this).val()) !== '' && $date.val() !== '') {
      $.ajax({
        type: 'POST',
        url: 'program/ajax.getEspNoDiagno.php',
        dataType: 'json',
        data: { idate: $('#iNdate').val(), iserv: $(this).val() }
      }).done(function (data) {
        $esp.html('').append('<option value="">Seleccione especialidad</option>')

        $.each(data, function (k, v) {
          $esp.append(
            $('<option></option>').val(v.esp_id).html(v.esp_nombre)
          )
        })
      })
    }
  })

  $sum.change(function () {
    const val = $.trim($(this).val())

    if (val !== '') {
      calTotal()
      $(this).addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $sumiq.change(function () {
    const val = $.trim($(this).val())

    if (val !== '') {
      calTotalIQ()
      $(this).addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $sumhes.change(function () {
    const val = $.trim($(this).val())

    if (val !== '') {
      calTotalHes()
      $(this).addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
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
