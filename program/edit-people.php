<?php include("class/classPersonaEstablecimiento.php") ?>
<?php include("class/classPersona.php") ?>
<?php include("class/classProfesion.php") ?>
<?php include("class/classTipoContrato.php") ?>
<?php $pro = new Profesion() ?>
<?php $tco = new TipoContrato() ?>
<?php $pe = new PersonaEstablecimiento() ?>
<?php $p = new Persona() ?>
<?php $pes = $pe->get($id) ?>
<?php $per = $p->get($pes->per_id) ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Creación de personal</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item"><a href="index.php?section=program&sbs=managepersonal">Contratos registrados</a></li>
					<li class="breadcrumb-item active">Edición de personal</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="content-fluid">
		<form role="form" id="formEditPeople">
			<div class="alert alert-danger">Los campos marcados con (*) son obligatorios</div>

			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Información General</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="irut">RUT *</label>
							<input type="text" class="form-control" id="iNrut" name="irut" placeholder="12345678-9" maxlength="12" value="<?php echo $per->per_rut ?>" disabled>
							<input type="hidden" id="iNid" name="iid" value="<?php echo $id ?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNname">Nombre completo *</label>
							<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombre completo de la persona" value="<?php echo $per->per_nombres ?>" required>
							<span class="form-text text-muted">Ingresar en orden APELLIDO PATERNO + APELLIDO MATERNO + NOMBRES</span>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNprofesion">Profesión *</label>
							<select class="form-control" id="iNprofesion" name="iprofesion">
								<option value="">Seleccione profesión</option>
								<?php $p = $pro->getAll() ?>
								<?php foreach ($p as $aux => $es): ?>
									<option value="<?php echo $es->prof_id ?>"<?php if ($es->prof_id == $per->per_prid): ?> selected<?php endif ?>><?php echo $es->prof_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="iNespec">Especialidad SIS</label>
							<input type="text" class="form-control" id="iNespec" name="iespec" placeholder="Ingrese especialidad según SIS" value="<?php echo $per->per_sis ?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNtcontrato">Tipo de Contrato *</label>
							<select class="form-control" id="iNtcontrato" name="itcontrato">
								<option value="">Seleccione tipo</option>
								<?php $t = $tco->getAll() ?>
								<?php foreach ($t as $aux => $tc): ?>
									<option value="<?php echo $tc->con_id ?>"<?php if ($tc->con_id == $pes->con_id): ?> selected<?php endif ?>><?php echo $tc->con_descripcion ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="iNcorr">Correlativo *</label>
							<input type="text" class="form-control" id="iNcorr" name="icorr" placeholder="Ingrese correlativo del contrato" value="<?php echo $pes->pes_correlativo ?>" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNhoras">Número de horas *</label>
							<input type="text" class="form-control" id="iNhoras" name="ihoras" placeholder="Ingrese cantidad de horas de contrato" value="<?php echo $pes->pes_horas ?>" required>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Editar datos</button>
					<button type="reset" class="btn btn-default btn-sm" id="btnClear">Limpiar</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</div>
		</form>
	</div>
</section>

<script src="program/edit-people.js"></script>
