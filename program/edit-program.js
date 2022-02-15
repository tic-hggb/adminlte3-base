$(document).ready(function () {
  const options = {
      url: 'program/ajax.editProgram.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $gdate = $('#gdate'),
    $gdate_t = $('#gdate_t'),
    $date = $('#iNdate'),
    $date_t = $('#iNdate_t'),
    $tdisponible = $('#iNtdisponible'),
    $total = $('#iNtotal'),
    $tapabellon = $('#iNtapabellon'),
    $brproy = $('#iNbrproy'),
    $brproyiq = $('#iNbrproyiq'),
    $totproy = $('#iNtotproy'),
    $tapolis = $('#iNtapoli, #iNtaapoli'),
    $brechas = $('#iNbrecha, #iNbrecha2'),
    $brechasiq = $('#iNbrechaiq, #iNbrechaiq2'),
    $tpoli = $('#iNtpoli'),
    $ind = $('.ind'),
    $thor = $('.thor')

  function validateForm() {
    let values = true

    if ($tdisponible.val() === '0.00' && $('#iNjustif').val() === '') {
      Swal.fire({
        title: 'Error de Programación',
        text: 'No se ha ingresado una justificación para una programación con cero horas disponibles.',
        icon: 'error',
        showCancelButton: false,
        confirmButtonText: 'Aceptar'
      })
      $tdisponible.removeClass('is-valid').addClass('is-invalid')
      values = false
    }

    $ind.each(function () {
      const val = $.trim($(this).val())

      if (val !== '0.00') {
        const idn = $(this).attr('id').split('N').pop()
        const rend = $.trim($('#iNr' + idn).val())

        if (rend === '0.00') {
          $(this).addClass('is-invalid')
          Swal.fire({
            title: 'Error de Programación',
            text: 'No se ha ingresado la totalidad de rendimientos para la programación.',
            type: 'error',
            showCancelButton: false,
            confirmButtonText: 'Aceptar'
          })
          values = false
        }
      }
    })

    $('.ind-proc').each(function () {
      const val = $.trim($(this).val())
      const idn = $(this).attr('id').split('N').pop()

      if (val !== '0.00') {
        const obs = $.trim($('#iNo' + idn).val())

        if (obs === '') {
          $(this).addClass('is-invalid')
          Swal.fire({
            title: 'Error de Programación',
            text: 'No se ha ingresado la totalidad de nombres de procedimientos para la programación.',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'Aceptar'
          })
          values = false
        }
      }
    })

    if ($tdisponible.val() !== $total.val()) {
      Swal.fire({
        title: 'Error de Programación',
        text: 'La suma de las horas disponibles no coincide con la suma de distribución horaria.',
        icon: 'error',
        showCancelButton: false,
        confirmButtonText: 'Aceptar'
      })
      values = false
    }

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
        text: 'La programación ha sido editada con éxito. Recargando datos...',
        type: 'success',
        callbacks: {
          afterClose: function () {
            document.location.reload()
          }
        }
      }).show()
    } else {
      new Noty({
        text: 'Hubo un problema al guardar la programación.<br>' + response.msg,
        type: 'error'
      }).show()
    }
  }

  function calTotal(id) {
    let tPoli = 0
    let tAPoli = 0
    const ind = parseFloat($('#iN' + id).val())
    let ren = parseFloat($('#iNr' + id).val())
    if (isNaN(ren)) {
      ren = 1
    }
    const total = ind * ren
    const hDispA = parseFloat($.trim($('#iNsemdisp').val()))

    $('#iNt' + id + ', #iNta' + id).val(total.toFixed(2))

    if ($brproy.val() !== undefined) {
      calBrecha()
    }

    if (total > 0) {
      $('#iNt' + id + ', #iNta' + id).addClass('is-valid')
    } else {
      $('#iNt' + id + ', #iNta' + id).removeClass('is-valid')
    }

    if ($('#iNt' + id).hasClass('tactp')) {
      $('.tactp').each(function () {
        tPoli += parseFloat($(this).val())
        tAPoli += parseFloat($(this).val()) * hDispA
      })

      $tapolis.val(tPoli.toFixed(2))

      if (tPoli > 0) {
        $tapolis.addClass('is-valid')
      } else {
        $tapolis.removeClass('is-valid')
      }
    }
  }

  function calTHoras(id) {
    let tHoras = 0

    $ind.each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        tHoras += parseFloat($(this).val())
      }
    })

    $thor.each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        tHoras += parseFloat($(this).val())
      }
    })

    $total.val(tHoras.toFixed(2))

    if (tHoras > 0) {
      $total.removeClass('is-invalid').addClass('is-valid')
      const hDisp = parseFloat($('#iNdisp').val())

      if (parseFloat(tHoras.toFixed(2)) > hDisp) {
        Swal.fire({
          title: 'Error de Programación',
          text: 'La suma de horas programadas (' + tHoras.toFixed(2) + ') es mayor que las horas disponibles ingresadas (' + hDisp + ').',
          icon: 'error',
          showCancelButton: false,
          confirmButtonText: 'Aceptar'
        })

        $('#iNtotal, #iN' + id).removeClass('is-valid').addClass('is-invalid')
      } else {
        $('#iN' + id).removeClass('is-invalid').addClass('is-valid')
      }
    } else {
      $total.removeClass('is-valid is-invalid')
    }
  }

  function calBrecha() {
    $('#iNbrproy, #iNbrproyiq').removeClass('is-valid is-invalid')
    let tActiv = 0

    $('.tanual').each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        tActiv += parseInt($(this).val(), 10)
      }
    })

    $('#iNactanuales').val(number_format(tActiv, 0, '', '.'))
    const bForm = parseInt($('#iNbrecha').val().replace('.', ''), 10)
    const bProy = tActiv + bForm
    $brproy.val(number_format(bProy, 0, '', '.'))

    if (bProy > 0) {
      $brproy.addClass('is-valid')
    } else {
      $brproy.addClass('is-invalid')
    }

    const tIQ = parseInt($tapabellon.val(), 10)
    $('#iNactanualesiq').val(number_format($tapabellon.val(), 0, '', '.'))
    const bFormIQ = parseInt($('#iNbrechaiq').val().replace('.', ''), 10)
    const bProyIQ = tIQ + bFormIQ
    $brproyiq.val(number_format(bProyIQ, 0, '', '.'))

    if (bProyIQ >= 0) {
      $brproyiq.addClass('is-valid')
    } else {
      $brproyiq.addClass('is-invalid')
    }
  }

  calTHoras()

  $loader.css('display', 'none')
  $('#activ-radio, #activ-rehab').css('display', 'none')

  $gdate.datetimepicker({
    format: 'MM/YYYY',
    minDate: moment().subtract(1, 'year')
  })
  $gdate.on('change.datetimepicker', function () {
    $date.addClass('is-valid')
  })
  $gdate_t.datetimepicker({
    format: 'MM/YYYY',
    maxDate: moment().add(2, 'year')
  })
  $gdate_t.on('change.datetimepicker', function () {
    $date_t.addClass('is-valid')
  })

  $('.form-control').change(function () {
    if ($.trim($(this).val()) !== '') {
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $('#iNcr').change(function () {
    $('#iNserv').html('').append('<option value="">Cargando servicios...</option>').removeClass('is-valid is-invalid')
    $('#iNesp').html('').append('<option value="">Seleccione especialidad</option>').removeClass('is-valid is-invalid')
    $('#iNtat, #iNtiq, #iNges, #iNgesiq, #iNtotalan, #iNtotalaniq, #iNtotalesp, #iNtotalespiq, #iNbrecha, #iNbrecha2, #iNbrechaiq, #iNbrechaiq2').val(0)

    $.ajax({
      type: 'POST',
      url: 'program/ajax.getServicios.php',
      dataType: 'json',
      data: { cr: $(this).val() }
    }).done(function (data) {
      $('#iNserv').html('').append('<option value="">Seleccione servicio</option>')

      $.each(data, function (k, v) {
        $('#iNserv').append(
          $('<option></option>').val(v.ser_id).html(v.ser_nombre)
        )
      })
    })
  })

  $('#iNserv').change(function () {
    $('#iNesp').html('').append('<option value="">Cargando especialidades...</option>').removeClass('is-valid is-invalid')
    $('#iNtat, #iNtiq, #iNges, #iNgesiq, #iNtotalan, #iNtotalaniq, #iNtotalesp, #iNtotalespiq, #iNbrecha, #iNbrecha2, #iNbrechaiq, #iNbrechaiq2').val(0)

    $.ajax({
      type: 'POST',
      url: 'program/ajax.getEspecialidades.php',
      dataType: 'json',
      data: { serv: $(this).val() }
    }).done(function (data) {
      $('#iNesp').html('').append('<option value="">Seleccione especialidad</option>')

      $.each(data, function (k, v) {
        $('#iNesp').append(
          $('<option></option>').val(v.esp_id).html(v.esp_nombre)
        )
      })
    })
  })

  $('#iNesp').change(function () {
    $('#iNtat, #iNtiq, #iNges, #iNgesiq, #iNtotalan, #iNtotalaniq, #iNtotalesp, #iNtotalespiq, #iNbrecha, #iNbrecha2, #iNbrechaiq, #iNbrechaiq2').val(0)

    $('.proces').each(function () {
      $(this).css('display', 'none')
    })
    $('.proces .input-number').each(function () {
      $(this).val('0.00')
    })
    calTHoras()

    $('.proces .form-group').each(function () {
      $(this).removeClass('has-success')
    })

    if ($(this).val() !== '') {
      $.ajax({
        type: 'POST',
        url: 'program/ajax.getBrecha.php',
        dataType: 'json',
        data: { d: $('#iNdate').val(), esp: $(this).val(), serv: $('#iNserv').val(), pes: $('#iid').val() }
      }).done(function (data) {
        if (data.dia_id !== null) {
          $('#iNtat').val(number_format(data.dia_total_esp, 0, '', '.'))
          $('#iNges').val(number_format(data.dia_lista, 0, '', '.'))

          $('#iNtiq').val(number_format(data.dia_total_esp_iq, 0, '', '.'))
          $('#iNgesiq').val(number_format(data.dia_lista_iq, 0, '', '.'))

          const total_an = parseInt(data.dia_total_esp, 10) + parseInt(data.dia_lista, 10)
          $('#iNtotalan').val(number_format(total_an, 0, '', '.'))
          $('#iNtotalesp').val(number_format(data.total_cc, 0, '', '.'))
          const totaliq_an = parseInt(data.dia_total_esp_iq, 10) + parseInt(data.dia_lista_iq, 10)
          $('#iNtotalaniq').val(number_format(totaliq_an, 0, '', '.'))
          $('#iNtotalespiq').val(number_format(data.total_iq, 0, '', '.'))

          const brecha = parseInt(data.total_cc, 10) - total_an
          $brechas.val(number_format(brecha, 0, '', '.'))
          if (brecha < 0) {
            $brechas.addClass('is-invalid')
          }

          const brecha_iq = parseInt(data.total_iq, 10) - totaliq_an
          $brechasiq.val(number_format(brecha_iq, 0, '', '.'))
          if (brecha_iq < 0) {
            $brechasiq.addClass('is-invalid')
          }

          $('#iNtatc').val(number_format(data.dia_disp_atc, 0, '', '.')).addClass('is-warning')
          $('#iNtata').val(number_format(data.dia_disp_ata, 0, '', '.')).addClass('is-warning')
          $('#iNtpro').val(number_format(data.dia_disp_pro, 0, '', '.')).addClass('is-warning')
          $('#iNthpro').val(number_format(data.total_disp, 0, '', '.'))
          const total_hesp = parseInt(data.dia_disp_atc, 10) + parseInt(data.dia_disp_ata, 10) + parseInt(data.dia_disp_pro, 10) - parseInt(data.total_disp, 10)
          $('#iNthesp, #iNthesp2').val(number_format(total_hesp, 0, '', '.')).addClass('is-valid')
        } else {
          Swal.fire({
            title: 'Atención',
            text: 'No se ha ingresado el diagnóstico anual para la especialidad escogida.',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Aceptar'
          })
        }

        $.ajax({
          type: 'POST',
          url: 'program/ajax.getIfProgrammed.php',
          dataType: 'json',
          data: { d_ini: $('#iNdate').val(), d_ter: $('#iNdate_t').val(), esp: $('#iNesp').val(), per: $('#iid').val() }
        }).done(function (data) {
          if (data > 0) {
            swal({
              title: 'Error',
              text: 'La especialidad escogida ya ha sido programada para el período. Por favor escoja una especialidad diferente.',
              type: 'error',
              showCancelButton: false,
              confirmButtonText: 'Aceptar'
            })

            $('#iNesp').val('').removeClass('is-valid')
            $('#iNtat, #iNges, #iNtotalan, #iNtotalesp, #iNbrecha, #iNbrecha2, #iNtiq, #iNgesiq, #iNtotalaniq, #iNtotalespiq, #iNbrechaiq, #iNbrechaiq2').val(0)
            $('#iNbrecha, #iNbrecha2, #iNbrechaiq, #iNbrechaiq2').removeClass('is-valid is-invalid')
          }
        })
      })
    } else {
      $('#iNtat, #iNges, #iNtotalan, #iNtotalesp, #iNbrecha, #iNtiq, #iNgesiq, #iNtotalaniq, #iNtotalespiq, #iNbrechaiq').val(0)
    }
  })

  $(document).on('keyup', '.input-number', function () {
    const v = this.value
    if ($.isNumeric(v) === false) {
      this.value = this.value.slice(0, -1)
    }
  })

  $('.disp').change(function () {
    let tDisp = 0
    $(this).removeClass('is-valid is-invalid')

    $('.disp').each(function () {
      const val = $.trim($(this).val())

      if (val !== '' && parseInt(val, 10) > 0) {
        tDisp += parseFloat($(this).val())
        $(this).removeClass('is-invalid').addClass('is-valid')
      }
    })

    const tmp = tDisp / 5
    const semDisp = parseFloat($('#weeks').val()) - tmp

    $('#iNsemdisp').val(semDisp)
  })

  $('#iNdisp, #iNuniversidad, #iNbecados').change(function () {
    let tDisp = 0
    const idn = $(this).attr('id').split('N')
    const val = $.trim($(this).val())

    if (val !== '') {
      calTotal(idn[1])
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }

    $('.disponib').each(function () {
      const val = $.trim($(this).val())

      if (val !== '') {
        tDisp += parseFloat($(this).val())
      }
    })

    $tdisponible.val(tDisp.toFixed(2))

    if (tDisp > 0) {
      $tdisponible.addClass('is-valid')
    } else {
      $tdisponible.removeClass('is-valid')
    }

    if (idn[1] === 'disp' && $brproy.val() !== undefined) {
      const totAnual = parseFloat($('#iNsemdisp').val()) * parseInt($(this).val(), 10)
      $('#iNthanual').val(number_format(totAnual, 2, '', '.'))
      const totDisp = parseInt($('#iNthesp2').val().replace('.', ''), 10) - totAnual
      $totproy.val(number_format(totDisp, 2, '', '.'))

      if (totDisp >= 0) {
        $totproy.addClass('is-valid')
      } else {
        $totproy.addClass('is-invalid')
      }
    }
  })

  $ind.change(function () {
    let tPoli = 0
    const idn = $(this).attr('id').split('N')
    const val = $.trim($(this).val())
    const hDisp = $.trim($('#iNdisp').val())

    if (val !== '') {
      calTotal(idn[1])
      calTHoras(idn[1])

      const perc = parseFloat(val) / parseFloat(hDisp)
      $('#iNp' + idn[1]).val(perc.toFixed(2)).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }

    if ($('#iN' + idn[1]).hasClass('tpoli')) {
      $('.tpoli').each(function () {
        const val = $.trim($(this).val())

        if (val !== '') {
          tPoli += parseFloat($(this).val())
        }
      })

      $tpoli.val(tPoli.toFixed(2))

      if (tPoli > 0) {
        $tpoli.addClass('is-valid')
      } else {
        $tpoli.removeClass('is-valid')
      }
    }
  })

  $('.rend').change(function () {
    const idn = $(this).attr('id').split('Nr')
    const val = $.trim($(this).val())

    if (val !== '') {
      calTotal(idn[1])
      calTHoras(idn[1])
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $('.obs').change(function () {
    const val = $.trim($(this).val())

    if (val !== '') {
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $thor.change(function () {
    const idn = $(this).attr('id').split('N')
    const val = $.trim($(this).val())
    const hDisp = $.trim($('#iNdisp').val())

    if (val !== '') {
      calTHoras(idn[1])
      const perc = parseFloat(val) / parseFloat(hDisp)
      $('#iNp' + idn[1]).val(perc.toFixed(2)).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $('#formNewProgram').submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })
})
