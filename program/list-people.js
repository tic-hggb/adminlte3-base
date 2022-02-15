$(document).ready(function () {
  function validateForm() {
    const values = true

    if (values) {
      $loader.css('display', 'inline-block')
      return true
    } else {
      new Noty({
        text: 'Error al consultar datos.<br>Por favor, revise la fecha de consulta.',
        type: 'error'
      }).show()
      return false
    }
  }

  function showResponse(response) {
    $loader.css('display', 'none')
    tableUsr.clear()

    if (response !== null) {
      $.each(response.data, function (k, v) {
        tableUsr.row.add([
          v.per_rut,
          v.per_nombres,
          v.per_profesion,
          v.con_descripcion,
          v.pes_correlativo,
          v.pes_horas,
          '<a class="peopleProgram btn btn-xs btn-success" href="index.php?section=program&sbs=createprogram&id=' + v.pes_id + '&date_ini=' + response.fecha_ini + '&date_ter=' + response.fecha_ter + '" data-tooltip="tooltip" data-placement="top" title="Programar"><i class="fa fa-pen"></i></a>'
        ])
      })

      $copy.prop('disabled', false)
    }

    tableUsr.draw()
  }

  const tableUsr = $('#tpeople').DataTable({
      'columns': [
        { width: '100px', className: 'text-right' },
        null,
        null,
        null,
        null,
        null,
        { 'orderable': false, width: '70px', className: 'text-center' }
      ],
      'order': [[1, 'asc']]
    }),
    options = {
      url: 'program/ajax.getPeopleByDate.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $loader2 = $('#submitLoader2'),
    $gyear = $('#gyear'),
    $year = $('#iNyear'),
    $copy = $('#btncopy')

  $('#submitLoader, #submitLoader2').css('display', 'none')
  $copy.prop('disabled', true)

  $(document).on('focusin', '#iNyear', function () {
    $(this).prop('readonly', true)
  }).on('focusout', '#iNyear', function () {
    $(this).prop('readonly', false)
  })

  $gyear.datetimepicker({
    format: 'YYYY',
    minDate: '2019'
  })
  $gyear.on('change.datetimepicker', function () {
    $year.addClass('is-valid')
  })

  $('.form-control').change(function () {
    if ($.trim($(this).val()) !== '') {
      $(this).removeClass('is-invalid').addClass('is-valid')
    } else {
      $(this).removeClass('is-valid')
    }
  })

  $copy.click(function () {
    const date = $('#iNperiodo').val() + '/' + $year.val(),
      date_t = '12/' + $year.val()

    Swal.fire({
      title: '¿Está seguro que desea copiar la programación?',
      text: 'Esta acción no puede ser revertida. Sólo serán copiadas las programaciones no ingresadas para este período.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Sí'
    }).then((result) => {
      if (result.isConfirmed) {
        $loader2.css('display', 'inline-block')

        $.ajax({
          type: 'POST',
          url: 'program/ajax.copyProgram.php',
          dataType: 'json',
          data: { date: date, date_t: date_t, pl: $('#iNplanta').val() }
        }).done(function (data) {
          if (data.type) {
            $('#btnsubmit').click()
            $loader2.css('display', 'none')
            $copy.prop('disabled', true)

            Swal.fire({
              title: 'Éxito',
              html: 'Las programaciones ha sido registrada con éxito. Un total de ' + data.msg + ' distribuciones fueron copiadas.',
              icon: 'success',
              showCancelButton: false,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'Aceptar'
            })
          }
        })
      }
    })
  })

  $('#formNewProgram').submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })
})
