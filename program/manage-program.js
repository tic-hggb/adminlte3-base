$(document).ready(function () {
  const $table = $('#tpeople'),
    tableUsr = $table.DataTable({
      'columns': [
        { width: '100px', className: 'text-right' },
        null,
        null,
        null,
        null,
        null,
        null,
        { 'orderable': false, width: '70px', className: 'text-center' }
      ],
      'order': [[2, 'asc']]
    })

  function validateForm() {
    const values = true

    if (values) {
      $loader.css('display', 'inline-block')
      return true
    }
  }

  function showResponse(response) {
    $loader.css('display', 'none')
    tableUsr.clear()

    if (response !== null) {
      $.each(response, function (k, v) {
        tableUsr.row.add([
          v.per_rut,
          v.per_nombres,
          v.per_profesion,
          v.per_servicio,
          v.per_especialidad,
          v.per_ley,
          v.pes_horas,
          '<a class="peopleProgram btn btn-xs btn-success" href="index.php?section=program&sbs=editprogram&id=' + v.disp_id + '" data-tooltip="tooltip" data-placement="top" title="Editar programación"><i class="fa fa-pen"></i></a>' +
          ' <button class="deleteProgram btn btn-xs btn-danger" id="del_' + v.disp_id + '" data-tooltip="tooltip" data-placement="top" title="Eliminar programación"><i class="fa fa-times"></i></button>'
        ])
      })
    }

    tableUsr.draw()
  }

  const options = {
      url: 'program/ajax.getProgramByFilters.php',
      type: 'post',
      dataType: 'json',
      beforeSubmit: validateForm,
      success: showResponse
    },
    $loader = $('#submitLoader'),
    $gyear = $('#gyear'),
    $year = $('#iNyear')

  $('#submitLoader, #submitLoader2, #btncopy').css('display', 'none')

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

  $('#iNcr').change(function () {
    $('#iNserv').removeClass('is-valid').html('').append('<option value="">Cargando servicios...</option>')
    $('#iNesp').removeClass('is-valid').html('').append('<option value="">Seleccione especialidad</option>')

    $.ajax({
      type: 'POST',
      url: 'program/ajax.getServicios.php',
      dataType: 'json',
      data: { cr: $(this).val() }
    }).done(function (data) {
      $('#iNserv').html('').append('<option value="">TODOS LOS SERVICIOS</option>')

      $.each(data, function (k, v) {
        $('#iNserv').append(
          $('<option></option>').val(v.ser_id).html(v.ser_nombre)
        )
      })
    })
  })

  $('#iNserv').change(function () {
    $('#iNesp').removeClass('is-valid').html('').append('<option value="">Cargando especialidades...</option>')

    $.ajax({
      type: 'POST',
      url: 'program/ajax.getEspecialidades.php',
      dataType: 'json',
      data: { serv: $(this).val() }
    }).done(function (data) {
      $('#iNesp').html('').append('<option value="">TODAS LAS ESPECIALIDADES</option>')

      $.each(data, function (k, v) {
        $('#iNesp').append(
          $('<option></option>').val(v.esp_id).html(v.esp_nombre)
        )
      })
    })
  })

  $table.on('click', '.deleteProgram', function () {
    const idp = $(this).attr('id').split('_').pop()

    Swal.fire({
      title: '¿Está seguro de querer eliminar esta programación?',
      text: 'Esta acción borrará los datos asociados de forma definitiva.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Sí'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: 'POST',
          url: 'program/ajax.deleteProgram.php',
          dataType: 'json',
          data: { id: idp }
        }).done(function (r) {
          if (r.type) {
            new Noty({
              text: 'La programación ha sido eliminada con éxito.',
              type: 'success'
            }).show()
          } else {
            new Noty({
              text: '<b>¡Error!</b>' + r.msg,
              type: 'error'
            }).show()
          }

          $('#btnsubmit').click()
        })
      }
    })
  })

  $('#formNewProgram').submit(function () {
    $(this).ajaxSubmit(options)
    return false
  })
})
