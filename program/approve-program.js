$(document).ready(function () {
  const $table = $('#tprogram'),
    $loader = $('#submitLoader'),
    $gyear = $('#gyear'),
    $year = $('#iNyear'),
    $periodo = $('#iNperiodo'),
    $planta = $('#iNplanta'),
    $appr = $('#iNappr'),
    $est = $('#iNest'),
    tableProg = $table.DataTable({
      columns: [
        {width: "320px"}, null,
        null, null,
        {className: "text-center"}, {className: "text-center"}, //horas contratadas
        {visible: false}, {visible: false},
        {className: "text-center"}, //horas disponibles
        {visible: false}, {visible: false},
        {visible: false},
        {className: "text-center"}, //total policlinico -> 16
        {visible: false}, {visible: false}, {visible: false}, {visible: false},//20
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},//40
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},//60
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {visible: false},//76
        {visible: false}, {visible: false}, {visible: false}, {visible: false},
        {visible: false}, {visible: false}, {visible: false}, {className: "text-center"},
        { orderable: false, width: '30px', className: 'text-center' }
      ],
      order: [[0, 'asc']],
      buttons: [],
      serverSide: true,
      ajax: {
        url: 'program/ajax.getServerApproveProgram.php',
        type: 'POST',
        length: 20,
        data: { iyear: $year.val(), iperiodo: $periodo.val(), iplanta: $planta.val(), iappr: $appr.val(), iest: $est.val() }
      }
    })

  $loader.css('display', 'none')

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

  $table.on('click', '.approve', function () {
    const fid = $(this).attr('id').split('_').pop()

    Swal.fire({
      title: '¿Está seguro de ejecutar la aprobación?',
      text: 'Esta acción aprobará definitivamente la programación asociada a este funcionario.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí'
    }).then(function (result) {
      if (!result.dismiss) {
        $.ajax({
          type: 'POST',
          url: 'program/ajax.approveProgram.php',
          dataType: 'json',
          data: { id: fid }
        }).done(function (msg) {
          if (msg.type) {
            new Noty({
              text: 'La aprobación ha sido guardada con éxito.',
              type: 'success'
            }).show()
          } else {
            new Noty({
              text: 'Error al registrar aprobación. <br>Por favor, inténtelo más tarde.',
              type: 'error'
            }).show()
          }

          $('#btnsubmit').click()
        })
      }
    })
  })

  $('#btnsubmit').click(function () {
    tableProg.ajax.url('program/ajax.getServerApproveProgram.php?iyear=' + $year.val() + '&iperiodo=' + $periodo.val() + '&iplanta=' + $planta.val() + '&iappr=' + $appr.val() + '&iest=' + $est.val())
      .load(null, false)
  })
})
