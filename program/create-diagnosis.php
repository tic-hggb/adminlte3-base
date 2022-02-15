<?php include("class/classPersona.php") ?>
<?php include("class/classEspecialidad.php") ?>
<?php include("class/classServicio.php") ?>
<?php include("class/classActividadProgramable.php") ?>
<?php $s = new Servicio() ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Ingreso de diagnóstico anual</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Ingreso de diagnóstico anual</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<form role="form" id="formNewDiagno">
			<div class="alert alert-danger">Los campos marcados con (*) son obligatorios</div>

			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title">Información General</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="iNdate">Período *</label>
							<div id="gdate" class="input-group date" data-target-input="nearest">
								<input type="text" class="form-control datetimepicker-input" id="iNdate" name="idate" data-target="#gdate" required>
								<div class="input-group-append" data-target="#gdate" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNserv">Servicio *</label>
							<select class="form-control" id="iNserv" name="iserv">
								<option value="">Seleccione servicio</option>
								<?php $ser = $s->getAll() ?>
								<?php foreach ($ser as $se): ?>
									<option value="<?php echo $se->ser_id ?>"><?php echo $se->ser_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="gesp">
							<label for="iNesp">Especialidad *</label>
							<select class="form-control" id="iNesp" name="iesp">
								<option value="">Seleccione especialidad</option>
							</select>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Brecha anual para la especialidad en año anterior</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3" id="gtat">
							<label for="iNtat">Total Consultas + Controles *</label>
							<input type="text" class="form-control input-number sum" id="iNtat" name="itat" placeholder="Ingrese el número de C+C" required>
						</div>

						<div class="form-group col-sm-3" id="gges">
							<label for="iNges">Total Lista de Espera *</label>
							<input type="text" class="form-control input-number sum" id="iNges" name="iges" placeholder="Ingrese la lista de espera" required>
						</div>

						<div class="form-group col-sm-3" id="gtotal">
							<label for="iNtotal">Total Anual C+C</label>
							<input type="text" class="form-control input-number" id="iNtotal" name="itotal" value="0" disabled>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-3" id="gtiq">
							<label for="iNtiq">Total Int. Quirúrgicas *</label>
							<input type="text" class="form-control input-number sumiq" id="iNtiq" name="itiq" placeholder="Ingrese el número de IQ" required>
						</div>

						<div class="form-group col-sm-3" id="ggesiq">
							<label for="iNgesiq">Total Lista de Espera *</label>
							<input type="text" class="form-control input-number sumiq" id="iNgesiq" name="igesiq" placeholder="Ingrese la lista de espera IQ" required>
						</div>

						<div class="form-group col-sm-3" id="gtotaliq">
							<label for="iNtotaliq">Total Anual IQ</label>
							<input type="text" class="form-control input-number" id="iNtotaliq" name="itotaliq" value="0" disabled>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Horas anuales de especialidad año anterior</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3" id="gtaa">
							<label for="iNtaa">Total Horas At. Abierta *</label>
							<input type="text" class="form-control input-number sumhes" id="iNtaa" name="itaa" placeholder="Ingrese el número de horas para atención abierta" required>
						</div>

						<div class="form-group col-sm-3" id="gtac">
							<label for="iNtac">Total Horas At. Cerrada *</label>
							<input type="text" class="form-control input-number sumhes" id="iNtac" name="itac" placeholder="Ingrese el número de horas para atención cerrada" required>
						</div>

						<div class="form-group col-sm-3" id="gtpro">
							<label for="iNtpro">Total Horas Procedimiento *</label>
							<input type="text" class="form-control input-number sumhes" id="iNtpro" name="itpro" placeholder="Ingrese el número de horas para procedimientos" required>
						</div>

						<div class="form-group col-sm-3" id="gtesp">
							<label for="iNtesp">Total Horas Especialidad</label>
							<input type="text" class="form-control input-number" id="iNtesp" name="itesp" value="0" disabled>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
					<button type="reset" class="btn btn-default" id="btnClear">Limpiar</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</div>
		</form>
	</div>
</section>

<script src="program/create-diagnosis.js"></script>
