<?php include("class/classCr.php") ?>
<?php $cr = new Cr() ?>

<section class="content-header">
	<h1>Reportes
		<small><i class="fa fa-angle-right"></i> Reporte de Reprogramaciones</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Reporte de reprogramaciones</li>
	</ol>
</section>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Filtros de búsqueda</h3>
		</div>

		<form role="form" id="formNewReport">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-3 has-feedback" id="gyear">
						<label class="control-label" for="iyear">Año de Planificación *</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control" id="iNyear" name="iyear" data-date-format="yyyy" placeholder="AAAA" required>
						</div>
						<i class="fa form-control-feedback" id="iconyear"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-xs-4 has-feedback" id="gplanta">
						<label class="control-label" for="iplanta">Planta *</label>
						<select class="form-control" id="iNplanta" name="iplanta">
							<option value="">Seleccione una planta</option>
							<option value="0">MÉDICA</option>
							<option value="1">NO MÉDICA</option>
							<option value="2">ODONTOLÓGICA</option>
						</select>
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

				<div class="row">
					<div class="form-group col-xs-4 has-feedback" id="gesp">
						<label class="control-label" for="iesp">Especialidad</label>
						<select class="form-control" id="iNesp" name="iesp">
							<option value="">Seleccione especialidad</option>
						</select>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit">
					<i class="fa fa-check"></i> Generar
				</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</form>
	</div>
</section>

<script src="reports/view-reprogram.js"></script>