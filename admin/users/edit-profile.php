<?php include("class/classUser.php") ?>
<?php $us = new User() ?>
<?php $u = $us->get($_SESSION['prm_userid']) ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Edición de perfil</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Edición de perfil</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<form role="form" id="formNewUser">
			<div class="alert alert-danger">Los campos marcados con (*) son obligatorios</div>

			<div class="card card-default">
				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNname">Nombres *</label>
							<input type="hidden" name="id" value="<?php echo $_SESSION['prm_userid'] ?>">
							<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombres del usuario" value="<?php echo $u->us_nombres ?>" required>
						</div>

						<div class="form-group col-sm-6">
							<label for="iNlastnamep">Apellido Paterno *</label>
							<input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingrese apellido paterno" value="<?php echo $u->us_ap ?>" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNlastnamem">Apellido Materno *</label>
							<input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingrese apellido materno" value="<?php echo $u->us_am ?>" required>
						</div>

						<div class="form-group col-sm-6">
							<label for="iNemail">Correo Electrónico *</label>
							<input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingrese e-mail" value="<?php echo $u->us_email ?>" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-3">
							<label for="iuserimage">Imagen de Cuenta</label>
							<div class="controls">
								<img alt="perfil" src="dist/img/<?php echo $u->us_pic ?>" width="100" height="100"><br><br>
								<!--<div id="uploadDiv" style="width: 100%; height: 100px; border: 1px dashed gray">
									<input name="iuserimage[]" id="iuserimage" type="file">
								</div>-->

								<div id="imageUpload" class="dropzone"></div>
								<span id="helpBlock" class="form-text text-muted">Formatos admitidos: GIF, JPG, JPEG, PNG</span>
							</div>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="button" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
					<a href="javascript:history.back()" class="btn btn-default btn-sm">Volver</a>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</div>
		</form>
	</div>
</section>

<script src="admin/users/edit-profile.js"></script>
