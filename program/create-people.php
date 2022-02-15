<?php include("class/classPersona.php") ?>
<?php include("class/classProfesion.php") ?>
<?php include("class/classTipoContrato.php") ?>
<?php $pro = new Profesion() ?>
<?php $tco = new TipoContrato() ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Creación de personal</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Creación de personal</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<form role="form" id="formNewPeople">
			<div class="alert alert-danger">Los campos marcados con (*) son obligatorios</div>

			<div class="card">
				<div class="card-header">
					<h3 class="card-title">Información General</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3" id="grut">
							<label for="iNrut">RUT *</label>
							<input type="text" class="form-control" id="iNrut" name="irut" placeholder="12345678-9" maxlength="12" required>
							<input type="hidden" id="iNid" name="iid">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="gname">
							<label for="iNname">Nombre completo *</label>
							<input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingrese nombre completo de la persona" required>
							<span class="form-text text-muted">Ingresar en orden APELLIDO PATERNO + APELLIDO MATERNO + NOMBRES</span>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="gprofesion">
							<label for="iNprofesion">Profesión *</label>
							<select class="form-control" id="iNprofesion" name="iprofesion">
								<option value="">Seleccione profesión</option>
								<?php $p = $pro->getAll() ?>
								<?php foreach ($p as $aux => $es): ?>
									<option value="<?php echo $es->prof_id ?>"><?php echo $es->prof_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6" id="gespec">
							<label for="iNespec">Especialidad SIS</label>
							<input type="text" class="form-control" id="iNespec" name="iespec" placeholder="Ingrese especialidad según SIS">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="gtcontrato">
							<label for="iNtcontrato">Tipo de Contrato *</label>
							<select class="form-control" id="iNtcontrato" name="itcontrato">
								<option value="">Seleccione tipo</option>
								<?php $t = $tco->getAll() ?>
								<?php foreach ($t as $aux => $tc): ?>
									<option value="<?php echo $tc->con_id ?>"><?php echo $tc->con_descripcion ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6" id="gcorr">
							<label for="iNcorr">Correlativo *</label>
							<input type="text" class="form-control" id="iNcorr" name="icorr" placeholder="Ingrese correlativo del contrato" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="ghoras">
							<label for="iNhoras">Número de horas *</label>
							<input type="text" class="form-control" id="iNhoras" name="ihoras" placeholder="Ingrese cantidad de horas de contrato" required>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar datos</button>
					<button type="reset" class="btn btn-default btn-sm" id="btnClear">Limpiar</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</div>
		</form>
	</div>
</section>

<script src="program/create-people.js"></script>
