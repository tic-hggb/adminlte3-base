$(document).ready(function () {
  $('table').tooltip({
    html: true,
    selector: '[data-tooltip=tooltip]'
  })

  $('body').tooltip({
    html: true,
    selector: '[rel=tooltip]'
  })

  $('#btn-help').click(function () {
    Swal.fire({
      title: '¿Necesita ayuda?',
      html: 'Para cualquier duda o sugerencia, puede contactar a soporte de la aplicación al anexo 410405 o al e-mail <a href="mailto:soportedesarrollo@ssconcepcion.cl">soportedesarrollo@ssconcepcion.cl</a>',
      icon: 'warning',
      showCancelButton: false,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Aceptar'
    })
  })

  $('#btn-logout').click(function () {
    Swal.fire({
      title: '¿Está seguro de querer salir?',
      text: 'Esta acción cerrará su sesión en el sistema.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Salir'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: 'POST',
          url: 'src/logout.php',
          data: { src: 'btn' }
        }).done(function (msg) {
          if (msg === 'true') {
            window.location.replace('index.php')
          }
        })
      }
    })
  })
})
