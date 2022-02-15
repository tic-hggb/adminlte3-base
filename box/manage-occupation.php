<?php include("class/classEstablecimientoLugar.php") ?>
<?php $l = new EstablecimientoLugar() ?>

<section class="content-header">
	<h1>Gestión de Box
		<small><i class="fa fa-angle-right"></i> Ocupaciones de Box Ingresadas</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Ocupaciones de box ingresadas</li>
	</ol>
</section>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Filtros de búsqueda</h3>
		</div>

		<?php
		$date_i = date('01/m/Y');
		$date_f = date('d/m/Y');
		?>

		<form role="form" id="formNewProgram">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-5 has-feedback" id="gdate">
						<label class="control-label" for="idate">Fecha</label>
						<div class="input-group input-daterange" id="iNdate">
							<input type="text" class="form-control" id="iNdatei" name="idate" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" value="<?php echo $date_i ?>" required>
							<span class="input-group-addon">hasta</span>
							<input type="text" class="form-control" id="iNdatet" name="idatet" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" value="<?php echo $date_f ?>" required>
						</div>
					</div>
				</div>

				<?php if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']): ?>
					<?php include("class/classEstablecimiento.php") ?>
					<?php $es = new Establecimiento() ?>
					<?php $est = $es->getAll() ?>

					<div class="row">
						<div class="form-group col-xs-4 has-feedback" id="gestab">
							<label class="control-label" for="iestab">Establecimiento</label>
							<select class="form-control" id="iNestab" name="iestab">
								<option value="">Seleccione un establecimiento</option>
								<?php foreach ($est as $e): ?>
									<option value="<?php echo $e->est_id ?>"><?php echo $e->est_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
				<?php endif ?>

				<div class="row">
					<div class="form-group col-xs-4 has-feedback" id="gpiso">
						<label class="control-label" for="ipiso">Lugar</label>
						<select class="form-control" id="iNpiso" name="ipiso">
							<option value="">Seleccione Lugar</option>
							<?php $lu = $l->getByEstab($_SESSION['prm_estid']) ?>
							<?php foreach ($lu as $lg): ?>
								<option value="<?php echo $lg->lug_id ?>"><?php echo $lg->lug_nombre ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="form-group col-xs-4 has-feedback" id="gbox">
						<label class="control-label" for="ibox">Box</label>
						<select class="form-control" id="iNbox" name="ibox">
							<option value="">Seleccione Box</option>
						</select>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="button" class="btn btn-primary" id="btnsubmit">
					<i class="fa fa-search"></i> Buscar
				</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</form>
	</div>

	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title" id="table-title-f">Ocupaciones de box registradas</h3>
		</div>

		<div class="box-body">
			<table id="tbhoras" class="table table-hover table-striped">
				<thead>
				<tr>
					<th>Lugar</th>
					<th>Box</th>
					<th>RUT</th>
					<th>Nombre</th>
					<th>Actividad</th>
					<th>Sub-especialidad</th>
					<th>Inicio</th>
					<th>Término</th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script src="box/manage-occupation.js"></script>