<?php include("class/classActividadProgramable.php") ?>
<?php $a = new ActividadProgramable() ?>

<section class="content-header">
	<h1>Reportes
		<small><i class="fa fa-angle-right"></i> Programaciones Ingresadas</small>
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
					<div class="form-group col-xs-4 has-feedback" id="gperiodo">
						<label class="control-label" for="iperiodo">Periodo *</label>
						<select class="form-control" id="iNperiodo" name="iperiodo" required>
							<option value="">Seleccione un periodo de programación</option>
							<option value="01">PROGRAMACIÓN INICIO DE AÑO</option>
							<option value="04">REPROGRAMACIÓN MARZO</option>
							<option value="07">REPROGRAMACIÓN JUNIO</option>
							<option value="10">REPROGRAMACIÓN SEPTIEMBRE</option>
							<option value="00">CONSOLIDADO ANUAL</option>
						</select>
					</div>

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
			<h3 class="box-title" id="table-title-f">Programaciones ingresadas para el período</h3>
		</div>

		<?php $act = $a->getNoPoli() ?>

		<div class="box-body table-responsive">
			<table id="tprogram" class="table table-hover table-striped">
				<thead>
				<tr>
					<th>Nombre</th>
					<th>Servicio</th>
					<th>Especialidad</th>
					<th>Descripción</th>
					<th>Corte</th>
					<th>Horas Contratadas</th>
					<th>Médicos Universidad</th>
					<th>Becados</th>
					<th>Horas Disponibles</th>
					<th>Consultas Nuevas</th>
					<th>Rend.</th>
					<th>Controles</th>
					<th>Rend.</th>
					<th>Consultas Abreviadas</th>
					<th>Rend.</th>
					<th>Total Policlínico</th>
					<?php foreach ($act as $i => $k): ?>
						<th><?php echo $k->acp_descripcion ?></th>
						<th>Rend.</th>
					<?php endforeach ?>
					<th>TOTAL</th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script src="reports/view-program.js?202103250847"></script>