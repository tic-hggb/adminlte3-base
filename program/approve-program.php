<?php include("class/classEstablecimiento.php") ?>
<?php include("class/classActividadProgramable.php") ?>
<?php $e = new Establecimiento() ?>
<?php $a = new ActividadProgramable() ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Programaciones aprobadas</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Programaciones aprobadas</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="card card-default">
			<div class="card-header">
				<h3 class="card-title">Filtros de búsqueda</h3>
			</div>

			<form role="form" id="formNewProgram">
				<div class="card-body">
					<div class="row">
						<div class="form-group col-3">
							<label for="iNyear">Período *</label>
							<div id="gyear" class="input-group date" data-target-input="nearest">
								<input type="text" class="form-control datetimepicker-input" id="iNyear" name="iyear" data-target="#gyear" required>
								<div class="input-group-append" data-target="#gyear" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-4" id="gperiodo">
							<label for="iNperiodo">Periodo *</label>
							<select class="form-control" id="iNperiodo" name="iperiodo" required>
								<option value="">Seleccione un periodo de programación</option>
								<option value="01">PROGRAMACIÓN INICIO DE AÑO</option>
								<option value="04">REPROGRAMACIÓN MARZO</option>
								<option value="07">REPROGRAMACIÓN JUNIO</option>
								<option value="10">REPROGRAMACIÓN SEPTIEMBRE</option>
							</select>
						</div>

						<div class="form-group col-sm-4" id="gplanta">
							<label for="iNplanta">Planta *</label>
							<select class="form-control" id="iNplanta" name="iplanta" required>
								<option value="">Seleccione una planta</option>
								<option value="0">MÉDICA</option>
								<option value="1">NO MÉDICA</option>
								<option value="2">ODONTOLÓGICA</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-4" id="gappr">
							<label for="iNappr">Aprobación</label>
							<select class="form-control" id="iNappr" name="iappr">
								<option value="">TODAS</option>
								<option value="0">APROBADA</option>
								<option value="1">SIN APROBAR</option>
							</select>
						</div>
						<?php if ($_admin): ?>
							<div class="form-group col-sm-4" id="gest">
								<label for="iNest">Establecimiento</label>
								<select class="form-control" id="iNest" name="iest">
									<option value="">TODOS</option>
									<?php $est = $e->getAll() ?>
									<?php foreach ($est as $es): ?>
										<option value="<?php echo $es->est_id ?>"><?php echo $es->est_nombre ?></option>
									<?php endforeach ?>
								</select>
							</div>
						<?php endif ?>
					</div>
				</div>

				<div class="card-footer">
					<button type="button" class="btn btn-primary" id="btnsubmit">
						<i class="fa fa-search"></i> Buscar
					</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</form>
		</div>

		<div class="card card-default">
			<div class="card-header">
				<h3 class="card-title">Programaciones registradas</h3>
			</div>

			<?php $act = $a->getNoPoli() ?>

			<div class="card-body table-responsive">
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
						<th>Controles</th>
						<th>Consultas Abreviadas</th>
						<th>Total Policlínico</th>
						<?php foreach ($act as $i => $k): ?>
							<th><?php echo $k->acp_descripcion ?></th>
						<?php endforeach ?>
						<th>TOTAL</th>
						<th></th>
					</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<script src="program/approve-program.js"></script>
