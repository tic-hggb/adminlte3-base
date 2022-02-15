<?php include("class/classCr.php") ?>
<?php $cr = new Cr() ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Edición de programaciones</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Programaciones registradas</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="card">
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
							<label for="iNplanta">Planta</label>
							<select class="form-control" id="iNplanta" name="iplanta">
								<option value="">TODAS LAS PLANTAS</option>
								<option value="0">MÉDICA</option>
								<option value="1">NO MÉDICA</option>
								<option value="2">ODONTOLÓGICA</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-4" id="gcr">
							<label for="iNcr">Centro de Responsabilidad</label>
							<select class="form-control" id="iNcr" name="icr">
								<option value="">Seleccione CR</option>
								<?php $cen = $cr->getAll() ?>
								<?php foreach ($cen as $c): ?>
									<option value="<?php echo $c->cr_id ?>"><?php echo $c->cr_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-4" id="gserv">
							<label for="iNserv">Servicio</label>
							<select class="form-control" id="iNserv" name="iserv">
								<option value="">Seleccione servicio</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-4" id="gesp">
							<label for="iNesp">Especialidad</label>
							<select class="form-control" id="iNesp" name="iesp">
								<option value="">Seleccione especialidad</option>
							</select>
						</div>
					</div>

					<?php if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']): ?>
						<?php include("class/classEstablecimiento.php") ?>
						<?php $es = new Establecimiento() ?>
						<?php $est = $es->getAll() ?>

						<div class="row">
							<div class="form-group col-sm-4" id="gestab">
								<label for="iNestab">Establecimiento</label>
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

				<div class="card-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit">
						<i class="fa fa-search"></i> Buscar
					</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</form>
		</div>

		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Programaciones registradas</h3>
			</div>

			<div class="card-body">
				<table id="tpeople" class="table table-hover table-striped">
					<thead>
					<tr>
						<th>RUT</th>
						<th>Nombre</th>
						<th>Profesión</th>
						<th>Servicio</th>
						<th>Especialidad</th>
						<th>Ley</th>
						<th>Horas</th>
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

<script src="program/manage-program.js"></script>
