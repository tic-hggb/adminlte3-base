$(document).ready(function () {
  const $table = $('#tpeople'),
    tableUsr = $table.DataTable({
      'columns': [
        { width: '100px', className: 'text-right' },
        null,
        null,
        null,
        null,
        { className: 'text-right' },
        { className: 'text-right' },
        { className: 'text-center' },
        { orderable: false, width: '50px', className: 'text-center' }
      ],
      'order': [[1, 'asc']],
      'serverSide': true,
      ajax: {
        url: 'program/ajax.getServerContratos.php',
        type: 'GET',
        length: 20
      }
    })

  $('#submitLoader').css('display', 'none')

  $('#btnsubmit').click(function () {
    tableUsr.ajax.url('program/ajax.getServerContratos.php?iplanta=' + $('#iNplanta').val()).load()
  })

  $table.on('click', '.desactCont', function () {
    const fid = $(this).attr('id').split('_').pop()

    Swal.fire({
      title: '¿Está seguro de desactivar el contrato?',
      text: 'Esta acción desactivará el contrato asociado a este funcionario.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí'
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: 'POST',
          url: 'program/ajax.deactivateContract.php',
          dataType: 'json',
          data: { id: fid }
        }).done(function (m) {
          if (m.type) {
            new Noty({
              text: 'El contrato ha sido desactivado con éxito.',
              icon: 'success'
            }).show()
          } else {
            new Noty({
              text: '<b>¡Error!</b>' + m.msg,
              icon: 'error'
            }).show()
          }

          $('#btnsubmit').click()
        })
      }
    })
  }).on('click', '.actCont', function () {
    const fid = $(this).attr('id').split('_').pop()

    Swal.fire({
      title: '¿Está seguro de activar el contrato?',
      text: 'Esta acción activará el contrato asociado a este funcionario.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí'
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          type: 'POST',
          url: 'program/ajax.activateContract.php',
          dataType: 'json',
          data: { id: fid }
        }).done(function (m) {
          if (m.type) {
            new Noty({
              text: 'El contrato ha sido activado con éxito.',
              icon: 'success'
            }).show()
          } else {
            new Noty({
              text: '<b>¡Error!</b>' + m.msg,
              icon: 'error'
            }).show()
          }

          $('#btnsubmit').click()
        })
      }
    })
  })
})

$('.form-control').change(function () {
  if ($.trim($(this).val()) !== '') {
    $(this).removeClass('is-invalid').addClass('is-valid')
  } else {
    $(this).removeClass('is-valid')
  }
})
