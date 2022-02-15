<?php include("class/classGroup.php") ?>
<?php include("class/classProfile.php") ?>
<?php $gr = new Group() ?>
<?php $pro = new Profile() ?>
<?php $g = $gr->get($id) ?>

<section class="content-header">
	<h1>Panel de Control
		<small><i class="fa fa-angle-right"></i> Creación de Grupos</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
		<li class="active">Creación de Grupos</li>
	</ol>
</section>

<section class="content container-fluid">
	<form role="form" id="formNewGroup">
		<p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Información General</h3>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gname">
						<label for="iname">Nombre *</label>
						<input type="hidden" name="id" value="<?php echo $id ?>">
						<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombre del grupo" value="<?php echo $g->gr_nombre ?>" required>
						<i class="fa form-control-feedback" id="iconname"></i>
					</div>
				</div>

				<?php $profile = $pro->getAll() ?>
				<div class="row">
					<div class="form-group col-xs-6 has-feedback" id="gprofile">
						<label for="iname">Perfil *</label>
						<select class="form-control" id="iNprofile" name="iprofile" required>
							<option value="">Seleccione un perfil</option>
							<?php foreach ($profile as $aux => $p): ?>
								<option value="<?php echo $p->perf_id ?>"<?php if ($p->perf_id == $g->gr_pid): ?> selected<?php endif ?>><?php echo $p->perf_nombre ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-check"></i> Guardar</button>
				<button type="reset" class="btn btn-default btn-sm" id="btnClear">Limpiar</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</div>
	</form>
</section>

<script src="admin/groups/edit-group.js"></script>