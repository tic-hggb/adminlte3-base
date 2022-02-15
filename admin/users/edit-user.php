<?php include("class/classUser.php") ?>
<?php $us = new User() ?>
<?php $u = $us->get($id) ?>

<section class="content-header">
	<h1>Panel de Control
		<small><i class="fa fa-angle-right"></i> Edición de Usuarios</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
		<li><a href="index.php?section=users&sbs=manageusers">Administración de Usuarios</a></li>
		<li class="active">Edición de Usuarios</li>
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
						<input type="hidden" name="id" value="<?php echo $id ?>">
						<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombres del usuario" value="<?php echo $u->us_nombres ?>" required>
						<i class="fa form-control-feedback" id="iconname"></i>
					</div>

					<div class="form-group col-xs-6 has-feedback" id="glastnamep">
						<label for="ilastnamep">Apellido Paterno *</label>
						<input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingrese apellido paterno" value="<?php echo $u->us_ap ?>" required>
						<i class="fa form-control-feedback" id="iconlastnamep"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="glastnamem">
						<label class="control-label" for="ilastnamem">Apellido Materno *</label>
						<input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingrese apellido materno" value="<?php echo $u->us_am ?>" required>
						<i class="fa form-control-feedback" id="iconname"></i>
					</div>

					<div class="form-group col-xs-6 has-feedback" id="gemail">
						<label class="control-label" for="iemail">Correo Electrónico *</label>
						<input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingrese e-mail del usuario" value="<?php echo $u->us_email ?>" required>
						<i class="fa form-control-feedback" id="iconEmail"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gusername">
						<label for="iusername">Nombre de Usuario</label>
						<input type="text" class="form-control" id="iNusername" name="iusername" placeholder="Ingrese el nombre de usuario con el que entrará al sistema" value="<?php echo $u->us_username ?>" readonly>
						<i class="fa form-control-feedback" id="iconUsername"></i>
					</div>

					<div class="form-group col-xs-6 has-feedback" id="gpassword">
						<label for="ipassword">Contraseña</label>
						<input type="text" class="form-control" name="ipassword" id="iNpassword" placeholder="Ingrese la contraseña con la que entrará al sistema" maxlength="64">
						<i class="fa form-control-feedback" id="iconpassword"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-12">
						<label for="iuserimage">Imagen de Cuenta</label>
						<div class="controls">
							<img src="dist/img/<?php echo $u->us_pic ?>" width="100" height="100"><br><br>
							<input name="iuserimage[]" class="multi" id="iuserimage" type="file" size="16" accept="gif|jpg|png|jpeg" maxlength="1">
							<p class="help-block">Formatos admitidos: GIF, JPG, JPEG, PNG</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-6">
						<label for="igroups">Estado</label>
						<p>
							<label class="label-checkbox">
								<input type="checkbox" class="minimal" name="iactive"<?php if ($u->us_activo): ?> checked<?php endif ?>> Activo
							</label>
						</p>
					</div>
				</div>
				<?php include("class/classGroup.php") ?>
				<?php $gr = new Group() ?>
				<?php $group = $gr->getAll() ?>
				<?php $u_g = $us->getGroups($id) ?>
			</div>

			<div class="box-header with-border">
				<h3 class="box-title">Grupos de Usuario</h3>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-6">
						<?php foreach ($group as $g): ?>
							<label class="label-checkbox">
								<input type="radio" class="minimal" name="iusergroups" value="<?php echo $g->gr_id ?>" <?php foreach ($u_g as $ug): if ($g->gr_id == $ug): ?> checked<?php endif; endforeach ?>> <?php echo $g->gr_nombre ?>
							</label>
							<div class="clearfix"></div>
						<?php endforeach ?>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Editar</button>
				<a href="javascript:history.back()" class="btn btn-sm btn-default">Volver</a>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</div>
	</form>
</section>

<script src="admin/users/edit-user.js"></script>