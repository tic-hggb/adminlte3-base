<?php include("class/classCr.php") ?>
<?php $cr = new Cr() ?>

<section class="content-header">
	<h1>Reportes
		<small><i class="fa fa-angle-right"></i> Cumplimiento de Programación de Consultas</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Programaciones Registradas</li>
	</ol>
</section>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Filtros de búsqueda</h3>
		</div>

		<form role="form" id="formNewProgram">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-3 has-feedback" id="gdate">
						<label class="control-label" for="idate">Año de Programación</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control" id="iNdate" name="idate" data-date-format="yyyy" placeholder="AAAA" required>
						</div>
						<i class="fa form-control-feedback" id="icondate"></i>
					</div>
				</div>

				<div class="row">
				<?php if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']): ?>
					<?php include("class/classEstablecimiento.php") ?>
					<?php $es = new Establecimiento() ?>
					<?php $est = $es->getAll() ?>
						<div class="form-group col-xs-4 has-feedback" id="gestab">
							<label class="control-label" for="iestab">Establecimiento *</label>
							<select class="form-control" id="iNestab" name="iestab" required>
								<option value="">Seleccione un establecimiento</option>
								<?php foreach ($est as $e): ?>
									<option value="<?php echo $e->est_id ?>"><?php echo $e->est_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
				<?php endif ?>

					<div class="form-group col-xs-4 has-feedback" id="gplanta">
						<label class="control-label" for="iplanta">Planta *</label>
						<select class="form-control" id="iNplanta" name="iplanta" required>
							<option value="">Seleccione una planta</option>
							<option value="0">MÉDICA</option>
							<option value="1">NO MÉDICA</option>
							<option value="2">ODONTOLÓGICA</option>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-4 has-feedback" id="gcr">
						<label class="control-label" for="icr">Centro de Responsabilidad</label>
						<select class="form-control" id="iNcr" name="icr">
							<option value="">Seleccione CR</option>
							<?php $cen = $cr->getAll() ?>
							<?php foreach ($cen as $c): ?>
								<option value="<?php echo $c->cr_id ?>"><?php echo $c->cr_nombre ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="form-group col-xs-4 has-feedback" id="gserv">
						<label class="control-label" for="iserv">Servicio</label>
						<select class="form-control" id="iNserv" name="iserv">
							<option value="">Seleccione servicio</option>
						</select>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit">
					<i class="fa fa-search"></i> Buscar
				</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</form>
	</div>

	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title" id="table-title-f">Rendimientos Registrados</h3>
		</div>

		<div class="box-body table-responsive">
			<table id="tprogram" class="table table-hover table-striped">
				<thead>
				<tr>
					<th>Nombre</th>
					<th>Especialidad</th>
					<th>Ene</th>
					<th>Feb</th>
					<th>Mar</th>
					<th>Abr</th>
					<th>May</th>
					<th>Jun</th>
					<th>Jul</th>
					<th>Ago</th>
					<th>Sep</th>
					<th>Oct</th>
					<th>Nov</th>
					<th>Dic</th>
					<th>Total</th>
					<th>Prog.</th>
					<th>Cump. (%)</th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script src="reports/view-perform.js"></script>