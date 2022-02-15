<section class="content-header">
	<h1>Menú de Usuario
		<small><i class="fa fa-angle-right"></i> Cambio de contraseña</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Cambio de contraseña</li>
	</ol>
</section>

<section class="content container-fluid">
	<form role="form" id="formChangePass">
		<p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Contraseña de Acceso</h3>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="goldpass">
						<label for="ioldpass">Ingrese su contraseña actual *</label>
						<input type="password" class="form-control" id="iNoldpass" name="ioldpass" placeholder="Ingrese su contraseña actual" maxlength="16" required>
						<input type="hidden" name="uid" id="uid" value="<?php echo $_SESSION['prm_userid'] ?>">
						<i class="fa form-control-feedback" id="iconoldpass"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gnewpass">
						<label for="inewpass">Ingrese su nueva contraseña *</label>
						<input type="password" class="form-control" name="inewpass" id="iNnewpass" placeholder="Ingrese su contraseña nueva" maxlength="16" required>
						<i class="fa form-control-feedback" id="iconnewpass"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gcnpass">
						<label for="icnpass">Confirme su nueva contraseña *</label>
						<input type="password" class="form-control" name="icnpass" id="iNcnpass" placeholder="Confirme su contraseña nueva ingresándola nuevamente" maxlength="16" required>
						<i class="fa form-control-feedback" id="iconcnpass"></i>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
				<button type="reset" class="btn btn-default btn-sm" id="btnClear">Limpiar</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</div>
	</form>
</section>

<script src="admin/users/change-password.js"></script>