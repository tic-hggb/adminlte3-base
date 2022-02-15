<body class="hold-transition login-page">
<div class="login-box">
	<div class="text-center">
		<img alt="sisplan" style="width: 60px" src="dist/img/sisplan.png">
	</div>

	<div class="login-logo">
		<a href="index.php"><b>SIS</b>Plan</a>
	</div>

	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Ingrese sus datos para iniciar sesión:</p>

			<form id="form-login" style="margin: 15px 0">
				<div class="input-group mb-3">
					<input type="text" class="form-control" id="inputUser" placeholder="Usuario" name="name" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-user"></span>
						</div>
					</div>
				</div>
				<div class="input-group mb-3">
					<input type="password" class="form-control" id="inputPassword" placeholder="Contraseña" name="passwd" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-block">
							Ingresar
							<i class="fas fa-cog fa-spin loginLoader" id="submitLoader"></i>
						</button>
					</div>
				</div>
			</form>

			<p class="mb-1">
				<a href="index.php?section=forgotpass">Olvidé mi contraseña</a>
			</p>
		</div>

		<?php if (isset($timeout) and $timeout == 1): ?>
			<div class="col-12 bg-class bg-danger text-center" style="display: block;" id="login-error">Se ha cerrado su sesión por inactividad.<br>Por favor, ingrese nuevamente.</div>
		<?php else: ?>
			<div class="col-12 bg-class bg-danger text-center" id="login-error"></div>
		<?php endif ?>
	</div>
</div>

<!-- Bootstrap 4 -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<script>
  $(document).ready(function () {
    $('#submitLoader').css('display', 'none')

    $('#form-login').submit(function (e) {
      e.preventDefault()
      $('#submitLoader').css('display', 'inline-block')
      $('#login-error').css('display', 'none')

      $.ajax({
        type: 'POST',
        url: 'src/session.php',
        data: { user: $('#inputUser').val(), passwd: $('#inputPassword').val() }
      }).done(function (msg) {
        if (msg === 'true') {
          $('#login-error').html('')
          window.location.replace('index.php')
        } else {
          $('#submitLoader').css('display', 'none')
          let message = '<strong>¡Error!</strong> ' + msg
          $('#login-error').html(message).css('display', 'block')
          $('#inputUser').val('')
          $('#inputPassword').val('')
        }
      })
    })
  })
</script>
</body>
