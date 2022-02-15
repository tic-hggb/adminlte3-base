<?php include("class/classEstablecimientoLugar.php") ?>
<?php include("class/classAgenda.php") ?>
<?php include("class/classPersona.php") ?>
<?php include("class/classTipoCupo.php") ?>
<?php include("class/classAgrupacion.php") ?>
<?php include("class/classDistribucionProg.php") ?>
<?php include("class/classDistHorasProg.php") ?>
<?php include("class/classEspecialidad.php") ?>
<?php include("class/classMotivoAusencia.php") ?>
<?php include("class/classBloqueHora.php") ?>
<?php include("class/classBloqueDestino.php") ?>

<?php $l = new EstablecimientoLugar() ?>
<?php $a = new Agenda() ?>
<?php $p = new Persona() ?>
<?php $tc = new TipoCupo() ?>
<?php $agr = new Agrupacion() ?>
<?php $dh = new DistribucionProg() ?>
<?php $dhp = new DistHorasProg() ?>
<?php $es = new Especialidad() ?>
<?php $ma = new MotivoAusencia() ?>
<?php $bhr = new BloqueHora() ?>
<?php $bde = new BloqueDestino() ?>

<?php $tmp = explode('-', $iden) ?>
<?php $year = $tmp[0] ?>
<?php $period = $tmp[1] ?>
<?php $serv = $tmp[2] ?>
<?php $est = $tmp[3] ?>
<?php $pers = $tmp[4] ?>
<?php $espec = $tmp[5] ?>

<?php $agen = $a->getEventsByPersonEsp($pers, $espec, $est, $year, $period) ?>
<?php $per = $p->get($pers) ?>
<?php $disth = $dh->getLastByPerEsp($pers, $est, $espec, $year) ?>
<?php $dhpro = $dhp->getByConsCont($disth->disp_id) ?>
<?php $especialidad = $es->get($disth->disp_espid) ?>

<?php
$agendado = 0;
$agendadoOtros = 0;
foreach ($agen as $ag => $v):
	$ag_c = $a->getCupos($v->age_id);

	if ($v->act_cxc):
		foreach ($ag_c as $cu => $vc):
			$agendado += $vc->acu_numero;
		endforeach;
	else:
		foreach ($ag_c as $cu => $vc):
			$agendadoOtros += $vc->acu_numero;
		endforeach;
	endif;
endforeach;
?>

<section class="content-header">
	<h1>Agenda
		<small><i class="fa fa-angle-right"></i> Calendario de Actividades</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Inicio</a></li>
		<li><a href="index.php?section=agenda&sbs=manageagendas">Personas disponibles para agendar</a></li>
		<li class="active">Calendario de actividades</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom nav-tabs-calendar">
				<ul class="nav nav-tabs">
					<li class="disabled active"><a href="#tab_a">Agendamiento</a></li>
					<li class="disabled"><a href="#tab_bh">Bloqueos de horario</a></li>
				</ul>

				<div class="tab-content tab-calendar">
					<div class="tab-pane fade in active" id="tab_a">
						<div class="row">
							<div class="col-md-6">
								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title">Información General</h3>
									</div>

									<div class="box-body">
										<div class="row">
											<div class="form-group col-xs-7">
												<label class="control-label">Nombre</label>
												<p class="form-control-static"><?php echo $per->per_nombres ?></p>
												<input type="hidden" id="iNyear" value="<?php echo $year ?>">
												<input type="hidden" id="iNperiod" value="<?php echo $period ?>">
												<input type="hidden" id="iNservice" value="<?php echo $serv ?>">
												<input type="hidden" id="iNestab" value="<?php echo $est ?>">
												<input type="hidden" id="iNpers" value="<?php echo $pers ?>">
												<input type="hidden" id="iNdist" value="<?php echo $disth->disp_id ?>">
											</div>

											<div class="form-group col-xs-5">
												<label class="control-label">Especialidad</label>
												<p class="form-control-static"><?php echo $especialidad->esp_nombre ?></p>
												<input type="hidden" id="iNespec" value="<?php echo $disth->disp_espid ?>">
												<input type="hidden" id="iNespnom" value="<?php echo $especialidad->esp_nombre ?>">
											</div>
										</div>
									</div>
								</div>

								<div class="box box-danger">
									<div class="box-body">
										<div class="row">
											<div class="form-group col-xs-6 has-feedback<?php if ($agendado >= $dhpro): ?> has-success<?php endif ?>" id="cuposprog-group">
												<label class="control-label">Cupos Programados C/C</label>
												<input type="text" class="form-control input-lg input-number" id="cuposprog-id" value="<?php echo $dhpro ?>" readonly>
												<i class="fa form-control-feedback<?php if ($agendado >= $dhpro): ?> fa-check<?php endif ?>" id="cuposprog-icon"></i>
											</div>

											<div class="form-group col-xs-6 has-feedback" id="cuposagen-group">
												<label class="control-label">Cupos Agendados C/C</label>
												<input type="text" class="form-control input-lg input-number" id="cuposagen-id" value="<?php echo number_format($agendado, 2, '.', ',') ?>" readonly>
												<i class="fa form-control-feedback" id="cuposagen-icon"></i>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-xs-6 col-xs-offset-6 has-feedback" id="cuposotro-group">
												<label class="control-label">Otros Cupos Agendados</label>
												<input type="text" class="form-control input-number" id="cuposotro-id" value="<?php echo number_format($agendadoOtros, 2, '.', ',') ?>" readonly>
												<i class="fa form-control-feedback" id="cuposotro-icon"></i>
											</div>
										</div>
									</div>
								</div>

								<div class="box box-default">
									<div class="box-header with-border">
										<h3 class="box-title">Detalle de la Actividad</h3>
									</div>

									<div class="box-body">
										<div class="row">
											<div class="form-group col-xs-6 has-feedback" id="piso-group">
												<label class="control-label">Lugar</label>
												<select class="form-control" id="piso-id">
													<option value="">Seleccione lugar</option>
													<?php $lu = $l->getByEstab($est) ?>
													<?php foreach ($lu as $lg): ?>
														<option value="<?php echo $lg->lug_id ?>"><?php echo $lg->lug_nombre ?></option>
													<?php endforeach ?>
												</select>
											</div>

											<div class="form-group col-xs-6 has-feedback" id="boxat-group">
												<label class="control-label">Box</label>
												<select class="form-control" id="boxat-id">
													<option value="">Seleccione box de atención</option>
												</select>
											</div>
										</div>

										<?php $days = [] ?>
										<?php $fD = getFirstDay(0, $period, $year) ?>
										<?php $tmp = explode('-', $fD) ?>
										<?php $days[] = $tmp[2] ?>
										<?php for ($i = 1; $i < 7; $i++): ?>
											<?php $days[] = (($tmp[2] + $i) < 10) ? '0' . ($tmp[2] + $i) : ($tmp[2] + $i) ?>
										<?php endfor ?>

										<div class="row">
											<div class="form-group col-xs-6 has-feedback" id="dia-group">
												<label class="control-label">Día</label>
												<select class="form-control" id="dia-id">
													<option value="">Seleccione día</option>
													<option value="<?php echo $days[0] ?>">Lunes</option>
													<option value="<?php echo $days[1] ?>">Martes</option>
													<option value="<?php echo $days[2] ?>">Miércoles</option>
													<option value="<?php echo $days[3] ?>">Jueves</option>
													<option value="<?php echo $days[4] ?>">Viernes</option>
													<option value="<?php echo $days[5] ?>">Sábado</option>
													<option value="<?php echo $days[6] ?>">Domingo</option>
												</select>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-xs-6">
												<div class="bootstrap-timepicker">
													<label>Inicio</label>

													<div class="input-group">
														<input type="text" class="form-control timepicker" id="hora-inicio">

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
														<input type="text" class="form-control timepicker" id="hora-fin">

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
												<select class="form-control tipocupo" id="tipocupo-id0">
													<option value="">Seleccione tipo</option>
													<?php $tcu = $tc->getAll() ?>
													<?php foreach ($tcu as $t): ?>
														<option value="<?php echo $t->tcu_id ?>"><?php echo $t->tcu_descripcion ?></option>
													<?php endforeach ?>
												</select>
											</div>

											<div class="form-group col-xs-5 has-feedback" id="cupos-group0">
												<label class="control-label">Cupos</label>
												<input type="text" class="form-control numcupos" id="cupos-id0" placeholder="Ingrese número de cupos" maxlength="5">
												<span class="fa form-control-feedback" id="cupos-icon0"></span>
											</div>

											<div class="col-xs-1">
												<label class="control-label">...</label>
												<button id="btn-add-cupo" type="button" class="btn btn-block btn-info"><i class="fa fa-plus"></i></button>
											</div>

											<div id="cupos-cont-inner"></div>
										</div>

										<div class="row">
											<div class="form-group col-xs-12 has-feedback" id="obscupos-group">
												<label class="control-label">Observación</label>
												<input type="text" class="form-control" id="obscupos-id" placeholder="Observación respecto de los cupos especificados" maxlength="120">
												<i class="fa form-control-feedback" id="obscupos-icon"></i>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-xs-12 has-feedback" id="cat-group">
												<label class="control-label">Actividad</label>
												<select class="form-control" id="cat-id">
													<option value="">Seleccione agrupación</option>
													<?php $ag = $agr->getAll() ?>
													<?php foreach ($ag as $a): ?>
														<option value="<?php echo $a->sagr_id ?>"><?php echo $a->sagr_nombre ?></option>
													<?php endforeach ?>
												</select>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-xs-6 has-feedback" id="esp-group">
												<select class="form-control" id="esp-id">
													<option value="">Seleccione especialidad</option>
												</select>
											</div>

											<div class="form-group col-xs-6 has-feedback" id="subesp-group">
												<select class="form-control" id="subesp-id">
													<option value="">Seleccione sub-especialidad</option>
												</select>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-xs-12 has-feedback" id="event-group">
												<select class="form-control" id="event-id">
													<option value="">Seleccione actividad</option>
												</select>
												<input type="hidden" id="event-multi">
												<input type="hidden" id="event-comite">
											</div>
										</div>

										<div class="btn-group" style="width: 100%;">
											<label class="control-label">Color</label>
											<ul class="fc-color-picker" id="color-chooser">
												<li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
												<li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
											</ul>
										</div>
									</div>

									<div class="box box-solid">
										<div class="box-footer">
											<button id="add-new-event" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Crear Bloque</button>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="box box-default">
									<div id="calendar"></div>
								</div>

								<div class="box box-default no-border">
									<div class="box-body text-center">
										<button id="btn-load-agenda" type="button" class="btn btn-lg btn-primary"><i class="fa fa-cloud-download"></i> Replicar Agenda Período Anterior</button>
									</div>
								</div>

								<div class="box box-default no-border" id="box-detail">
									<div class="box-body no-padding">
										<div class="box-header with-border">
											<h3 class="box-title" id="box-detail-title">Ocupación del Box</h3>
										</div>

										<div class="box-body no-padding">
											<div id="calendar-boxat"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="box box-solid text-right">
									<div class="box-footer">
										<button type="button" class="btn btn-default" id="btnclear"><i class="fa fa-remove"></i> Limpiar Agenda</button>
										<button type="button" class="btn btn-lg btn-success" id="btnnext">Siguiente <i class="fa fa-long-arrow-right"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="tab_bh">
						<div class="box box-default">
							<div class="box-header with-border">
								<h3 class="box-title">Información General</h3>
							</div>

							<div class="box-body">
								<div class="row">
									<div class="form-group col-xs-4">
										<label class="control-label">Nombre</label>
										<p class="form-control-static"><?php echo $per->per_nombres ?></p>
									</div>

									<div class="form-group col-xs-4">
										<label class="control-label">Especialidad</label>
										<p class="form-control-static"><?php echo $especialidad->esp_nombre ?></p>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-xs-4">
										<label class="control-label">Días de Vacaciones</label>
										<p class="form-control-static"><?php echo $disth->disp_vacaciones ?></p>
									</div>

									<div class="form-group col-xs-4">
										<label class="control-label">Días de Permiso</label>
										<p class="form-control-static"><?php echo $disth->disp_permisos ?></p>
									</div>

									<div class="form-group col-xs-4">
										<label class="control-label">Días de Congreso</label>
										<p class="form-control-static"><?php echo $disth->disp_congreso ?></p>
									</div>
								</div>
							</div>

							<div class="box-header with-border">
								<h3 class="box-title">Creación de Bloqueos</h3>
							</div>

							<div class="box-body">
								<p class="bg-class bg-warning"><i class="fa fa-warning"></i> Los bloqueos creados en esta sección serán marcados como bloqueos programados.</p>
								<div class="row">
									<div class="form-group col-xs-4 has-feedback" id="gdate">
										<label class="control-label" for="idate">Fecha de bloqueo *</label>
										<div class="input-group input-daterange">
											<input type="text" class="form-control" id="iNdatei" name="idatei" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" required>
											<span class="input-group-addon">hasta</span>
											<input type="text" class="form-control" id="iNdatet" name="idatet" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" required>
										</div>
									</div>

									<div class="form-group col-xs-4 has-feedback" id="gmotivo">
										<label class="control-label" for="imotivo">Motivo *</label>
										<select class="form-control" name="imotivo" id="iNmotivo">
											<option value="">Seleccione motivo</option>
											<?php $mau = $ma->getAll() ?>
											<?php foreach ($mau as $m): ?>
												<option value="<?php echo $m->mau_id ?>"><?php echo $m->mau_descripcion ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-xs-4 has-feedback" id="gdestino">
										<label class="control-label" for="idestino">Destino *</label>
										<select class="form-control" name="idestino" id="iNdestino">
											<option value="">Seleccione destino</option>
											<?php $bdes = $bde->getAll() ?>
											<?php foreach ($bdes as $b): ?>
												<option value="<?php echo $b->bdes_id ?>"><?php echo $b->bdes_descripcion ?></option>
											<?php endforeach ?>
										</select>
									</div>

									<div class="form-group col-xs-4 has-feedback" id="gobs">
										<label class="control-label" for="iobs">Observación</label>
										<input type="text" class="form-control" name="iobs" id="iNobs">
										<i class="fa form-control-feedback" id="iconobs"></i>
									</div>
								</div>
							</div>

							<div class="box-footer">
								<button id="add-new-block" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar Bloqueo</button>
							</div>
						</div>

						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Bloqueos Agregados</h3>
							</div>

							<?php $blocks = $bhr->getBlocks($pers, $est, $year) ?>
							<div class="box-body">
								<table id="tblocks" class="table table-hover table-striped">
									<thead>
									<tr>
										<th>Desde</th>
										<th>Hasta</th>
										<th>Motivo</th>
										<th>Destino</th>
										<th>Observación</th>
										<th></th>
									</tr>
									</thead>

									<tbody>
									<?php
									$idBlock = 0;
									foreach ($blocks as $k => $o):
										$bl_begin = $bhr->get($o->id_begin);
										$bl_end = $bhr->get($o->id_end);
										?>
										<tr>
											<td><?php echo getDateToForm($bl_begin->bh_fecha) ?></td>
											<td><?php echo getDateToForm($bl_end->bh_fecha) ?></td>
											<td><?php echo $bl_begin->mau_descripcion ?></td>
											<td><?php echo $bl_begin->bdes_descripcion ?></td>
											<td><?php echo $bl_begin->bh_descripcion ?></td>
											<td>
												<button id="del_<?php echo $idBlock ?>" class="btn btn-danger btn-xs delBlock" data-tooltip="tooltip" data-placement="top" title="Eliminar Bloqueo"><i class="fa fa-remove"></i></button>
												<input type="hidden" class="f_ini" id="f_ini<?php echo $idBlock ?>" value="<?php echo $bl_begin->bh_fecha ?>">
												<input type="hidden" id="f_ter<?php echo $idBlock ?>" value="<?php echo $bl_end->bh_fecha ?>">
												<input type="hidden" id="mau_id<?php echo $idBlock ?>" value="<?php echo $bl_begin->mau_id ?>">
												<input type="hidden" id="mau_desc<?php echo $idBlock ?>" value="<?php echo $bl_begin->mau_descripcion ?>">
												<input type="hidden" id="bdes_id<?php echo $idBlock ?>" value="<?php echo $bl_begin->bdes_id ?>">
												<input type="hidden" id="bdes_desc<?php echo $idBlock ?>" value="<?php echo $bl_begin->bdes_descripcion ?>">
												<input type="hidden" id="bh_desc<?php echo $idBlock ?>" value="<?php echo $bl_begin->bh_descripcion ?>">
											</td>
										</tr>
									<?php
									$idBlock++;
									endforeach;
									?>
									</tbody>
								</table>
							</div>
						</div>

						<?php $days = $bhr->getDiasOff($pers, $est, $year) ?>
						<?php $totalVacaciones = $disth->disp_vacaciones - $days->dias_vacaciones ?>
						<?php $totalPermiso = $disth->disp_permisos - $days->dias_permiso ?>
						<?php $totalCongreso = $disth->disp_congreso - $days->dias_congreso ?>

						<div class="box box-danger">
							<div class="box-body">
								<div class="row">
									<div class="form-group col-xs-4 has-feedback<?php if ($totalVacaciones == 0): ?> has-success<?php endif ?>" id="vacTotal-group">
										<label class="control-label">Disponible vacaciones</label>
										<input type="text" class="form-control input-number" id="vacTotal-id" value="<?php echo $totalVacaciones ?>" readonly>
										<i class="fa form-control-feedback<?php if ($totalVacaciones == 0): ?> fa-check<?php endif ?>" id="vacTotal-icon"></i>
									</div>

									<div class="form-group col-xs-4 has-feedback<?php if ($totalPermiso == 0): ?> has-success<?php endif ?>" id="perTotal-group">
										<label class="control-label">Disponible permisos</label>
										<input type="text" class="form-control input-number" id="perTotal-id" value="<?php echo $totalPermiso ?>" readonly>
										<i class="fa form-control-feedback<?php if ($totalPermiso == 0): ?> fa-check<?php endif ?>" id="perTotal-icon"></i>
									</div>

									<div class="form-group col-xs-4 has-feedback<?php if ($totalCongreso == 0): ?> has-success<?php endif ?>" id="conTotal-group">
										<label class="control-label">Disponible congreso</label>
										<input type="text" class="form-control input-number" id="conTotal-id" value="<?php echo $totalCongreso ?>" readonly>
										<i class="fa form-control-feedback<?php if ($totalCongreso == 0): ?> fa-check<?php endif ?>" id="conTotal-icon"></i>
									</div>
								</div>
							</div>
						</div>

						<div class="box box-solid">
							<div class="box-footer">
								<div class="row">
									<div class="col-md-6">
										<button type="button" class="btn btn-default" id="btnprevious"><i class="fa fa-long-arrow-left"></i> Anterior</button>
									</div>

									<div class="col-md-6 text-right">
										<button type="button" class="btn btn-default" id="btnclear-block"><i class="fa fa-remove"></i> Limpiar Bloqueos</button>
										<button type="button" class="btn btn-lg btn-success" id="btnsubmit"><i class="fa fa-check"></i> Guardar Agenda</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="agenda/modify-agenda.js"></script>
<script src="agenda/modify-agenda-block.js"></script>