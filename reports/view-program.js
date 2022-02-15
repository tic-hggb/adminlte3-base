$(document).ready(function () {
  const $table = $('#tprogram'),
    tableProg = $table.DataTable({
      columns: [
        { width: '320px' }, null,
        null, null,
        { className: 'text-center' }, { className: 'text-center' }, //horas contratadas
        { visible: false }, { visible: false },
        { className: 'text-center' }, //horas disponibles
        { visible: false }, { visible: false },
        { visible: false }, { visible: false },
        { visible: false }, { visible: false },
        { className: 'text-center' }, //total policlinico -> 16
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },//10
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },//20
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },
        { visible: false }, { visible: false }, { visible: false }, { visible: false },//30
        { visible: false }, { visible: false }, { visible: false }, { visible: false },//124 -> 140
        { visible: false }, { visible: false },
        { className: 'text-center' }
      ], //141
      order: [[0, 'asc']],
      drawCallback: function () {
        $loader.css('display', 'none')
      }
    }),
    $loader = $('#submitLoader'),
    $gyear = $('#gyear'),
    $year = $('#iNyear')

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

  $('#btnsubmit').click(function () {
    $loader.css('display', 'inline-block')
    tableProg.ajax
      .url('reports/ajax.getServerProgram.php?iyear=' + $('#iNyear').val() + '&iperiodo=' + $('#iNperiodo').val() + '&iplanta=' + $('#iNplanta').val() + '&iestab=' + $('#iNestab').val())
      .load()
  })
})
