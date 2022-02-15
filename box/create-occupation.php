<?php include("class/classEstablecimientoLugar.php") ?>
<?php include("class/classTipoCupo.php") ?>
<?php include("class/classAgrupacion.php") ?>
<?php include("class/classBoxTipo.php") ?>
<?php $l = new EstablecimientoLugar() ?>
<?php $tc = new TipoCupo() ?>
<?php $agr = new Agrupacion() ?>
<?php $bt = new BoxTipo() ?>

<section class="content-header">
	<h1>Gestión de Box
		<small><i class="fa fa-angle-right"></i> Ver Ocupación de Boxes</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
		<li class="active">Ver Ocupación de Boxes</li>
	</ol>
</section>

<?php
$cur = firstLastWeekDay('d/m/Y');
?>
<section class="content container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title">Filtros de Búsqueda</h3>
				</div>

				<form role="form" id="formNewOccupation">
					<div class="box-body">

						<div class="row">
							<div class="form-group col-xs-3 has-feedback" id="gdate">
								<label class="control-label" for="idate">Semana de consulta *</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="iNdate" name="idate" data-date-format="dd/mm/yyyy" value="<?php echo $cur->start ?>" required>
								</div>
								<i class="fa form-control-feedback" id="icondate"></i>
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
								<label class="control-label" for="ipiso">Lugar *</label>
								<select class="form-control" id="iNpiso" name="ipiso">
									<option value="">Seleccione Lugar</option>
									<?php $lu = $l->getByEstab($_SESSION['prm_estid']) ?>
									<?php foreach ($lu as $lg): ?>
										<option value="<?php echo $lg->lug_id ?>"><?php echo $lg->lug_nombre ?></option>
									<?php endforeach ?>
								</select>
							</div>

							<div class="form-group col-xs-4 has-feedback" id="gtbox">
								<label class="control-label" for="itbox">Tipo de Box</label>
								<select class="form-control" id="iNtbox" name="itbox">
									<option value="">TODOS</option>
									<?php $tbo = $bt->getAll() ?>
									<?php foreach ($tbo as $t): ?>
										<option value="<?php echo $t->tbox_id ?>"><?php echo $t->tbox_descripcion ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>

					<div class="box-footer">
						<button type="button" class="btn btn-primary" id="btnsubmit">
							<i class="fa fa-search"></i> Buscar
						</button>

						<button type="button" class="btn btn-success" id="assign-box">
							<i class="fa fa-plus"></i> Asignar box
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="box box-solid">
		<div class="box-body">
			<div id="calendar-cont" style="height: 75vh">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="assignBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form role="form" id="formNewOccupationAsign">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Asignación de Ocupación</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-xs-6 has-feedback" id="gbox">
							<label class="control-label">Box</label>
							<select class="form-control" id="iNbox" name="ibox">
								<option value="">Seleccione box de atención</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-12 has-feedback" id="gpersona">
							<label class="control-label" for="ipersona">Profesional</label>
							<input type="text" class="form-control" id="iNpersona" name="ipersona" placeholder="Profesional asignado al bloque" maxlength="128" required>
							<input type="hidden" id="iNperid" name="iperid">
							<span class="fa form-control-feedback" id="iconpersona"></span>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-6 has-feedback" id="gdateas">
							<label class="control-label" for="idateas">Fecha de asignación</label>
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control" id="iNdateas" name="idateas" data-date-format="dd/mm/yyyy" placeholder="DD/MM/AAAA" value="<?php echo date('d/m/Y') ?>" required>
							</div>
							<i class="fa form-control-feedback" id="icondateas"></i>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-6">
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

						<div class="form-group col-xs-6">
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

					<div class="row" id="cupos-cont">
						<div class="form-group col-xs-6 has-feedback" id="tipocupo-group0">
							<label class="control-label">Tipo de cupo</label>
							<select class="form-control tipocupo" id="tipocupo-id0" name="itipocupos[]">
								<option value="">Seleccione tipo</option>
								<?php $tcu = $tc->getAll() ?>
								<?php foreach ($tcu as $t): ?>
									<option value="<?php echo $t->tcu_id ?>"><?php echo $t->tcu_descripcion ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-xs-5 has-feedback" id="cupos-group0">
							<label class="control-label">Cupos</label>
							<input type="text" class="form-control numcupos" id="cupos-id0" name="icupos[]" placeholder="Ingrese número de cupos" maxlength="5">
							<span class="fa form-control-feedback" id="cupos-icon0"></span>
						</div>

						<div class="col-xs-1">
							<label class="control-label">...</label>
							<button id="btn-add-cupo" type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i></button>
						</div>

						<div id="cupos-cont-inner"></div>
					</div>

					<div class="row">
						<div class="form-group col-xs-12 has-feedback" id="gobscupos">
							<label class="control-label">Observación</label>
							<input type="text" class="form-control" id="iNobscupos" name="iobscupos" placeholder="Observación respecto de los cupos especificados" maxlength="120">
							<i class="fa form-control-feedback" id="iconobscupos"></i>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-12 has-feedback" id="gcat">
							<label class="control-label">Actividad</label>
							<select class="form-control" id="iNcat" name="icat">
								<option value="">Seleccione agrupación</option>
								<?php $ag = $agr->getAll() ?>
								<?php foreach ($ag as $a): ?>
									<option value="<?php echo $a->sagr_id ?>"><?php echo $a->sagr_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-6 has-feedback" id="gesp">
							<select class="form-control" id="iNesp" name="iesp">
								<option value="">Seleccione especialidad</option>
							</select>
						</div>

						<div class="form-group col-xs-6 has-feedback" id="gsubesp">
							<select class="form-control" id="iNsubesp" name="isubesp">
								<option value="">Seleccione sub-especialidad</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-12 has-feedback" id="gevent">
							<select class="form-control" id="iNevent" name="ievent">
								<option value="">Seleccione actividad</option>
							</select>
							<input type="hidden" id="event-multi">
							<input type="hidden" id="event-comite">
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="add-event">
						<i class="fa fa-plus"></i> Agregar
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="box/create-occupation.js"></script>