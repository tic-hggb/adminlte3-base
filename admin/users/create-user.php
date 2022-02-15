<section class="content-header">
	<h1>Panel de Control
		<small><i class="fa fa-angle-right"></i> Creación de Usuarios</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
		<li class="active">Creación de Usuarios</li>
	</ol>
</section>

<section class="content container-fluid">
	<form role="form" id="formNewUser">
		<p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Información General</h3>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gname">
						<label class="control-label" for="iname">Nombres *</label>
						<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombres del usuario" required>
						<i class="fa form-control-feedback" id="iconname"></i>
					</div>

					<div class="form-group col-xs-6 has-feedback" id="glastnamep">
						<label for="ilastnamep">Apellido Paterno *</label>
						<input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingrese apellido paterno" required>
						<i class="fa form-control-feedback" id="iconlastnamep"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="glastnamem">
						<label class="control-label" for="ilastnamem">Apellido Materno *</label>
						<input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingrese apellido materno" required>
						<i class="fa form-control-feedback" id="iconname"></i>
					</div>
					<div class="form-group col-xs-6 has-feedback" id="gemail">
						<label class="control-label" for="iemail">Correo Electrónico *</label>
						<input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingrese e-mail del usuario" required>
						<i class="fa form-control-feedback" id="iconEmail"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gusername">
						<label for="iusername">Nombre de Usuario *</label>
						<input type="text" class="form-control" id="iNusername" name="iusername" placeholder="Ingrese el nombre de usuario con el que entrará al sistema" maxlength="16" required>
						<i class="fa form-control-feedback" id="iconUsername"></i>
					</div>
					<div class="form-group col-xs-6 has-feedback" id="gpassword">
						<label for="ipassword">Contraseña *</label>
						<input type="text" class="form-control" name="ipassword" id="iNpassword" placeholder="Ingrese la contraseña con la que entrará al sistema" maxlength="64" required>
						<i class="fa form-control-feedback" id="iconpassword"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-12">
						<label for="iuserimage">Imagen de Cuenta</label>
						<div class="controls">
							<input name="iuserimage[]" class="multi" id="iuserimage" type="file" size="16" accept="gif|jpg|png|jpeg" maxlength="1">
							<p class="help-block">Formatos admitidos: GIF, JPG, JPEG, PNG</p>
						</div>
					</div>
				</div>
			</div>

			<div class="box-header with-border">
				<h3 class="box-title">Grupos de Usuario</h3>
			</div>

			<div class="box-body">
				<?php include("class/classGroup.php") ?>
				<?php $gr = new Group() ?>
				<?php $group = $gr->getAll() ?>

				<div class="row">
					<div class="form-group col-xs-6">
						<?php foreach ($group as $g): ?>
							<label class="label-checkbox">
								<input type="radio" class="minimal" name="iusergroups" id="iNusergroups_<?php echo $g->gr_id ?>" value="<?php echo $g->gr_id ?>"> <?php echo $g->gr_nombre ?>
							</label>
							<div class="clearfix"></div>
						<?php endforeach ?>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
				<button type="reset" class="btn btn-default" id="btnClear">Limpiar</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</div>
	</form>
</section>

<script src="admin/users/create-user.js"></script>