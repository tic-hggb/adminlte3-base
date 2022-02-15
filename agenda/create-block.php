<?php include("class/classMotivoAusencia.php") ?>
<?php include("class/classBloqueDestino.php") ?>
<?php $ma = new MotivoAusencia() ?>
<?php $bde = new BloqueDestino() ?>

<section class="content-header">
	<h1>Agendamiento
		<small><i class="fa fa-angle-right"></i> Ingreso de Ausencias</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Ingreso de Ausencias</li>
	</ol>
</section>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Información de Ausencia</h3>
		</div>

			<form role="form" id="formNewAbsence">
				<div class="box-body">
					<div class="row">
						<div class="form-group col-xs-6 has-feedback" id="gpersona">
							<label class="control-label" for="ipersona">Profesional *</label>
							<input type="text" class="form-control" id="iNpersona" name="ipersona" placeholder="Nombre de profesional ausente" maxlength="128" required>
							<input type="hidden" id="iNperid" name="iperid">
							<span class="fa form-control-feedback" id="iconpersona"></span>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-4 has-feedback" id="gdate">
							<label class="control-label" for="idateas">Fecha de ausencia *</label>
							<div class="input-group input-daterange">
								<input type="text" class="form-control" id="iNdatei" name="idatei" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" required>
								<span class="input-group-addon">hasta</span>
								<input type="text" class="form-control" id="iNdatet" name="idatet" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-2">
							<div class="bootstrap-timepicker">
								<label>Inicio</label>

								<div class="input-group">
									<input type="text" class="form-control timepicker" id="hora-inicio" name="h_ini">

									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="form-group col-xs-2">
							<div class="bootstrap-timepicker">
								<label>Fin</label>

								<div class="input-group">
									<input type="text" class="form-control timepicker" id="hora-fin" name="h_fin">

									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-4">
							<label class="label-checkbox">
								<input class="minimal" type="checkbox" id="all-day">
								Todo el día
							</label>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-3 has-feedback" id="gmotivo">
							<label class="control-label" for="imotivo">Motivo *</label>
							<select class="form-control" name="imotivo" id="iNmotivo">
								<option value="">Seleccione motivo</option>
								<?php $mau = $ma->getAll() ?>
								<?php foreach ($mau as $m): ?>
									<option value="<?php echo $m->mau_id ?>"><?php echo $m->mau_descripcion ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-xs-3 has-feedback" id="gdestino">
							<label class="control-label" for="idestino">Destino *</label>
							<select class="form-control" name="idestino" id="iNdestino">
								<option value="">Seleccione destino</option>
								<?php $bdes = $bde->getAll() ?>
								<?php foreach ($bdes as $b): ?>
									<option value="<?php echo $b->bdes_id ?>"><?php echo $b->bdes_descripcion ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-6 has-feedback" id="gobs">
							<label class="control-label" for="iobs">Observación</label>
							<input type="text" class="form-control" name="iobs" id="iNobs">
							<i class="fa form-control-feedback" id="iconobs"></i>
							<p class="help-block"></p>
						</div>
					</div>
				</div>

				<div class="box-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit">
						<i class="fa fa-check"></i> Guardar
					</button>
					<button type="reset" class="btn btn-default btn-sm" id="btnClear">Limpiar</button>
					<span class="ajaxLoader" id="submitLoader"></span>
				</div>
			</form>
		</div>
	</div>
</section>

<script src="agenda/create-block.js"></script>