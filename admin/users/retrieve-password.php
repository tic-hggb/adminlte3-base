<body class="hold-transition">
<div class="wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6">
					<h1>Recuperación de contraseña</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
						<li class="breadcrumb-item active">Recuperación de contraseña</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">
			<form role="form" id="formChangePass">
				<div class="row">
					<div class="col-8 offset-2">
						<div class="callout callout-warning">
							<h4>¿Olvidó su contraseña?</h4>
							<p>Ingrese su nombre de usuario y una nueva contraseña será enviada a su correo. Luego podrá cambiarla en el menú de usuario en la barra superior, si así lo desea.</p>
						</div>

						<div class="card card-default">
							<div class="card-body">
								<div class="form-group col-6 offset-3">
									<label for="iNusername">Nombre de usuario</label>
									<input type="text" class="form-control" id="iNusername" name="iusername" placeholder="Ingrese su nombre de usuario" maxlength="16" required>
								</div>
							</div>

							<div class="card-footer text-center">
								<button type="submit" class="btn btn-lg btn-danger" id="btnsubmit"><i class="fa fa-check"></i> Enviar a mi correo</button>
								<i class="fas fa-cog fa-spin loginLoader" id="submitLoader"></i>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>

<script src="node_modules/jquery-form/dist/jquery.form.min.js"></script>
<script src="node_modules/noty/lib/noty.js"></script>
<script src="node_modules/moment/min/moment.min.js"></script>
<script src="node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="dist/js/fn.js"></script>
<script src="admin/users/retrieve-password.js"></script>
</body>
