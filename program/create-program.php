<?php include("class/classParametro.php") ?>
<?php include("class/classPersona.php") ?>
<?php include("class/classPersonaEstablecimiento.php") ?>
<?php include("class/classCr.php") ?>
<?php include("class/classServicio.php") ?>
<?php include("class/classEspecialidad.php") ?>
<?php include("class/classDistribucionProg.php") ?>
<?php include("class/classDistHorasProg.php") ?>
<?php include("class/classJustificacion.php") ?>
<?php include("class/classActividadProgramable.php") ?>

<?php $para = new Parametro() ?>
<?php $p = new Persona() ?>
<?php $pe = new PersonaEstablecimiento() ?>
<?php $cr = new Cr() ?>
<?php $se = new Servicio() ?>
<?php $es = new Especialidad() ?>
<?php $di = new DistribucionProg() ?>
<?php $dh = new DistHorasProg() ?>
<?php $js = new Justificacion() ?>
<?php $acp = new ActividadProgramable() ?>
<?php $t_date = explode('-', $date_ini) ?>
<?php $t_par = $para->get($t_date[0]) ?>
<?php $WEEKS = $t_par->par_semanas ?>
<?php $perest = $pe->get($id) ?>
<?php $per = $p->get($perest->per_id) ?>
<?php $prev = date('Y-m-d', strtotime('-3 month', strtotime($date_ini))); ?>
<?php $dis = $di->getByPerDate($id, $prev, $date_ter) ?>
<?php $cre = $cr->getByService($dis->disp_serid) ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Ingreso de programación</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item"><a href="index.php?section=program&sbs=listpeople">Personas registradas</a></li>
					<li class="breadcrumb-item active">Ingreso de programación</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<form role="form" id="formNewProgram">
			<input type="hidden" id="weeks" value="<?php echo $WEEKS ?>">
			<div class="alert alert-danger">Los campos marcados con (*) son obligatorios</div>

			<div class="card card-default">
				<div class="card-header">
					<h3 class="card-title">Datos Personales</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-4">
							<label>Nombre</label>
							<p class="form-control-static"><?php echo $per->per_nombres ?></p>
							<input type="hidden" id="iid" name="id" value="<?php echo $id ?>">
						</div>

						<div class="form-group col-sm-8">
							<label>Profesión</label>
							<p class="form-control-static"><?php echo $per->per_profesion ?></p>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-4">
							<label>Ley (correlativo)</label>
							<p class="form-control-static"><?php echo $perest->con_descripcion ?> (<?php echo $perest->pes_correlativo ?>)</p>
						</div>

						<div class="form-group col-sm-8">
							<label>Especialidad SIS</label>
							<p class="form-control-static"><?php echo $per->per_sis ?></p>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Datos de Programación</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNdate">Período de programación *</label>

							<div class="row">
								<div class="col-sm-6">
									<div id="gdate" class="input-group date" data-target-input="nearest">
										<div class="input-group-prepend" data-target="#gdate" data-toggle="datetimepicker">
											<span class="input-group-text">Desde</span>
										</div>
										<input type="text" class="form-control datetimepicker-input float-right" id="iNdate" name="idate" value="<?php echo getDateMonthToForm($date_ini) ?>" data-target="#gdate" required>
									</div>
								</div>

								<div class="col-sm-6">
									<div id="gdate_t" class="input-group date" data-target-input="nearest">
										<div class="input-group-prepend" data-target="#gdate_t" data-toggle="datetimepicker">
											<span class="input-group-text">Hasta</span>
										</div>
										<input type="text" class="form-control datetimepicker-input float-right" id="iNdate_t" name="idate_t" value="<?php echo getDateMonthToForm($date_ter) ?>" data-target="#gdate_t" required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNdesc">Descripción *</label>
							<input type="text" class="form-control<?php if ($dis->disp_descripcion !== ''): ?> is-valid<?php endif ?>" id="iNdesc" name="idesc" placeholder="Ingrese descripción para la programación" maxlength="64" value="<?php echo $dis->disp_descripcion ?>" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNcr">Centro de Responsabilidad *</label>
							<select class="form-control<?php if ($dis->disp_serid !== null): ?> is-valid<?php endif ?>" id="iNcr" name="icr" required>
								<option value="">Seleccione CR</option>
								<?php $cen = $cr->getAll() ?>
								<?php foreach ($cen as $c): ?>
									<option value="<?php echo $c->cr_id ?>"<?php if ($c->cr_id == $cre->cr_id): ?> selected<?php endif ?>><?php echo $c->cr_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6">
							<label for="iNserv">Servicio *</label>
							<select class="form-control<?php if ($dis->disp_serid !== null): ?> is-valid<?php endif ?>" id="iNserv" name="iserv" required>
								<option value="">Seleccione servicio</option>
								<?php if ($dis->disp_serid !== ''): ?>
									<?php $ser = $se->getByCR($cre->cr_id) ?>
									<?php foreach ($ser as $s): ?>
										<option value="<?php echo $s->ser_id ?>"<?php if ($s->ser_id == $dis->disp_serid): ?> selected<?php endif ?>><?php echo $s->ser_nombre ?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNesp">Especialidad *</label>
							<select class="form-control" id="iNesp" name="iesp" required>
								<option value="">Seleccione especialidad</option>
								<?php if ($dis->disp_espid !== ''): ?>
									<?php $esp = $es->getByServicio($dis->disp_serid) ?>
									<?php foreach ($esp as $e): ?>
										<option value="<?php echo $e->esp_id ?>"><?php echo $e->esp_nombre ?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
						</div>
					</div>

					<div class="form-group clearfix">
						<div class="icheck-info d-inline">
							<input type="checkbox" id="iNgeneral" name="igeneral">
							<label for="iNgeneral">Médico general</label>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNobserv">Observaciones</label>
							<input type="text" class="form-control<?php if ($dis->disp_observaciones !== ''): ?> is-valid<?php endif ?>" id="iNobserv" name="iobserv" placeholder="Ingrese observación (Liberado de guardia / PAO)" maxlength="64" value="<?php echo $dis->disp_observaciones ?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-2">
							<label for="iNvacaciones">Vacaciones *</label>
							<input type="text" class="form-control<?php if ($dis->disp_vacaciones > 0): ?> is-valid<?php endif ?> input-number disp" id="iNvacaciones" name="ivacaciones" placeholder="Ingrese días de vacaciones" value="<?php echo $dis->disp_vacaciones ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNpermiso">Permisos *</label>
							<input type="text" class="form-control<?php if ($dis->disp_permisos > 0): ?> is-valid<?php endif ?> input-number disp" id="iNpermiso" name="ipermiso" placeholder="Ingrese días de permiso" value="<?php echo $dis->disp_permisos ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNcongreso">Congreso *</label>
							<input type="text" class="form-control<?php if ($dis->disp_congreso > 0): ?> is-valid<?php endif ?> input-number disp" id="iNcongreso" name="icongreso" placeholder="Ingrese días de congresos" value="<?php echo $dis->disp_congreso ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNdescanso">Descanso comp. *</label>
							<input type="text" class="form-control<?php if ($dis->disp_congreso > 0): ?> is-valid<?php endif ?> input-number disp" id="iNdescanso" name="idescanso" placeholder="Ingrese días de descanso" value="<?php echo $dis->disp_descanso ?>" required>
						</div>

						<?php $total_dias = $dis->disp_vacaciones + $dis->disp_permisos + $dis->disp_congreso + $dis->disp_descanso ?>
						<?php $sem_disp = $WEEKS - ($total_dias / 5) ?>

						<div class="form-group col-sm-3 offset-sm-1">
							<label for="iNsemdisp">Semanas disponibles</label>
							<input type="text" class="form-control input-number" id="iNsemdisp" name="isemdisp" value="<?php echo $sem_disp ?>" tabindex="-1" disabled>
						</div>
					</div>
				</div>

				<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
					<div class="card-header">
						<h3 class="card-title">Diagnóstico Especialidad</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="form-group col-sm-2">
								<label for="iNtat">Total Cons. + Cont.</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtat" name="itat" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNges">Total Lista de Espera</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNges" name="iges" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalan">Total Anual</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalan" name="itotalan" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalesp">Total Programado</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalesp" name="itotalesp" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNbrecha">Brecha Calculada C+C</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D - (A + B)</div>
									</div>
									<input type="text" class="form-control input-number" id="iNbrecha" name="ibrecha" value="0" disabled>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-2">
								<label for="iNtiq">Total Interv. Quir.</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtiq" name="itiq" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNgesiq">Total Lista de Espera</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNgesiq" name="igesiq" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalaniq">Total Anual</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalaniq" name="itotalaniq" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalespiq">Total Programado</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalespiq" name="itotalespiq" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNbrechaiq">Brecha Calculada IQ</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D - (A + B)</div>
									</div>
									<input type="text" class="form-control input-number" id="iNbrechaiq" name="ibrechaiq" value="0" disabled>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-2">
								<label for="iNtatc">Total Horas At. Cerrada</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtatc" name="itatc" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtata">Total Horas At. Abierta</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtata" name="itata" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtpro">Total Horas Proced.</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">C</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtpro" name="itpro" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNthpro">Total Horas Programadas</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNthpro" name="ithpro" value="0" disabled>
								</div>
							</div>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNthesp">Total Horas Disponibles</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B + C - D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNthesp" name="ithesp" value="0" disabled>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
				<div class="card-header">
					<h3 class="card-title">Justificaciones</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNjustif">Justificación para programación nula</label>
							<select class="form-control" id="iNjustif" name="ijustif">
								<option value="">Seleccione una justificación</option>
								<?php $jus = $js->getAll() ?>
								<?php foreach ($jus as $j): ?>
									<option value="<?php echo $j->jus_id ?>"><?php echo $j->jus_descripcion ?></option>
								<?php endforeach ?>
							</select>
							<span id="helpBlock" class="form-text text-muted">Obligatoria sólo en el caso de programar una distribución con cero horas disponibles.</span>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Horas Semanales</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="iNcont">Horas Contrato</label>
							<input type="text" class="form-control input-number" id="iNcont" value="<?php echo number_format($perest->pes_horas, 2, '.', '') ?>" disabled>
						</div>
					</div>

					<div class="row">
						<?php $h_sem = 0 ?>
						<?php $d = $dh->getByPerTHDate($id, 1, $prev) ?>
						<?php $h_disponibles = $d->dh_cantidad ?>
						<?php $h_sem += $d->dh_cantidad ?>
						<div class="form-group col-sm-3">
							<label for="iNdisp">Horas Disponibles *</label>
							<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number disponib" id="iNdisp" name="disp" value="<?php echo $d->dh_cantidad ?>" required>
						</div>

						<?php $d = $dh->getByPerTHDate($id, 2, $prev) ?>
						<?php $h_sem += $d->dh_cantidad ?>
						<div class="form-group col-sm-3">
							<label for="iNuniversidad">Médico Universidad</label>
							<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number disponib" id="iNuniversidad" name="universidad" value="<?php echo $d->dh_cantidad ?>">
						</div>

						<?php $d = $dh->getByPerTHDate($id, 3, $prev) ?>
						<?php $h_sem += $d->dh_cantidad ?>
						<div class="form-group col-sm-3">
							<label for="iNbecados">Becados</label>
							<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number disponib" id="iNbecados" name="becados" value="<?php echo $d->dh_cantidad ?>">
						</div>

						<div class="form-group col-sm-3">
							<label for="iNtdisponible">Horas Semanales Disp.</label>
							<input type="text" class="form-control<?php if ($h_sem > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtdisponible" name="tdisponible" value="<?php echo number_format($h_sem, 2, '.', ',') ?>" disabled>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Actividades Policlínico</h3>
				</div>

				<div class="card-body">
					<!-- Consultas, Controles, Consultas abreviadas -->
					<?php $t_p = $ta_p = $ts_p = 0 ?>
					<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
						<?php $arr = array(4, 5, 21) ?>
					<?php else: ?>
						<?php $arr = array(126, 127, 75) ?>
					<?php endif ?>
					<?php foreach ($arr as $i): ?>
						<?php $a = $acp->get($i) ?>

						<?php $d = $dh->getByPerTHDate($id, $i, $prev) ?>
						<?php $t_p += $d->dh_cantidad ?>
						<div class="row">
							<div class="form-group col-sm-3">
								<label for="iNact<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
								<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number ind tpoli" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo $d->dh_cantidad ?>">
							</div>

							<?php $div_horas = ($h_disponibles == 0) ? 0 : $d->dh_cantidad / $h_disponibles ?>
							<?php $percent = number_format($div_horas, 2, '.', ',') ?>
							<div class="form-group col-sm-2">
								<label for="iNpact<?php echo $a->acp_id ?>">% Asignado</label>
								<input type="text" class="form-control<?php if ($percent > 0): ?> is-valid<?php endif ?> input-number" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo $percent ?>" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNract<?php echo $a->acp_id ?>">Rendimiento</label>
								<input type="text" class="form-control<?php if ($d->dh_rendimiento > 0): ?> is-valid<?php endif ?> input-number rend" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $d->dh_rendimiento ?>">
							</div>

							<?php $t = $d->dh_cantidad * $d->dh_rendimiento ?>
							<?php $ta_p += $t ?>
							<div class="form-group col-sm-2 offset-sm-1">
								<label for="iNtact<?php echo $a->acp_id ?>">Total</label>
								<input type="text" class="form-control<?php if ($t > 0): ?> is-valid<?php endif ?> input-number tactp" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
							</div>

							<?php $ts = $t * $sem_disp ?>
							<?php $ts_p += $ts ?>
							<div class="form-group col-sm-2">
								<label for="iNtaact<?php echo $a->acp_id ?>">Total Anual</label>
								<input type="text" class="form-control<?php if ($ts > 0): ?> is-valid<?php endif ?> input-number tanual" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($ts, 2, '.', ',') ?>" disabled>
							</div>
						</div>
					<?php endforeach ?>

					<!-- TOTAL POLICLINICO -->
					<?php $h_tot = 0 ?>
					<?php $h_tot += $t_p ?>
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="iNtpoli">Total Policlínico</label>
							<input type="text" class="form-control<?php if ($t_p > 0): ?> is-valid<?php endif ?> input-number" id="iNtpoli" tabindex="-1" name="tpoli" value="<?php echo number_format($t_p, 2, '.', ',') ?>" readonly>
						</div>

						<div class="form-group col-sm-2 offset-sm-5">
							<label for="iNtapoli">Total</label>
							<input type="text" class="form-control<?php if ($ta_p > 0): ?> is-valid<?php endif ?> input-number" id="iNtapoli" tabindex="-1" name="tapoli" value="<?php echo number_format($ta_p, 2, '.', ',') ?>" readonly>
						</div>

						<?php $taa_p = $ta_p * $sem_disp ?>
						<div class="form-group col-sm-2">
							<label for="iNtaapoli">Total Anual</label>
							<input type="text" class="form-control<?php if ($taa_p > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtaapoli" name="taapoli" value="<?php echo number_format($taa_p, 2, '.', ',') ?>" readonly>
						</div>
					</div>
				</div>

				<div id="activ-med">
					<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
						<div class="card-header">
							<h3 class="card-title">Otras actividades</h3>
						</div>

						<div class="card-body">
							<?php $exclude = array(21) ?>
							<?php $ac = $acp->getByType(1, $exclude) ?>

							<?php foreach ($ac as $it => $a): ?>
								<?php $d = $dh->getByPerTHDate($id, $a->acp_id, $prev) ?>
								<?php $h_tot += $d->dh_cantidad ?>

								<div class="row">
									<div class="form-group col-sm-3">
										<label for="iNact<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
										<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number ind" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo $d->dh_cantidad ?>">
									</div>

									<?php $div_horas = ($h_disponibles == 0) ? 0 : $d->dh_cantidad / $h_disponibles ?>
									<?php $percent = number_format($div_horas, 2, '.', ',') ?>
									<div class="form-group col-sm-2">
										<label for="iNpact<?php echo $a->acp_id ?>">% Asignado</label>
										<input type="text" class="form-control<?php if ($percent > 0): ?> is-valid<?php endif ?> input-number" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo $percent ?>" disabled>
									</div>

									<?php if ($a->acp_rendimiento): ?>
										<div class="form-group col-sm-2">
											<label for="iNract<?php echo $a->acp_id ?>">Rendimiento</label>
											<input type="text" class="form-control<?php if ($d->dh_rendimiento > 0): ?> is-valid<?php endif ?> input-number rend" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $d->dh_rendimiento ?>">
										</div>

										<?php $t = $d->dh_cantidad * $d->dh_rendimiento ?>
										<div class="form-group col-sm-2 offset-sm-1">
											<label for="iNtact<?php echo $a->acp_id ?>">Total</label>
											<input type="text" class="form-control<?php if ($t > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
										</div>

										<?php $ts = $t * $sem_disp ?>
										<div class="form-group col-sm-2">
											<label for="iNtaact<?php echo $a->acp_id ?>">Total Anual</label>
											<input type="text" class="form-control<?php if ($ts > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($ts, 2, '.', ',') ?>" disabled>
										</div>
									<?php endif ?>
								</div>
							<?php endforeach ?>
						</div>

					<?php else: ?>
						<div class="card-header">
							<h3 class="card-title">Actividades No Médicos</h3>
						</div>

						<div class="card-body">
							<?php $exclude = array(126, 127, 75) ?>
							<?php $ac = $acp->getByType(2, $exclude) ?>

							<?php foreach ($ac as $it => $a): ?>
								<?php $d = $dh->getByPerTHDate($id, $a->acp_id, $prev) ?>
								<?php $h_tot += $d->dh_cantidad ?>

								<div class="row">
									<div class="form-group col-sm-3">
										<label for="iNact<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
										<input type="text" class="form-control<?php if ($d->dh_cantidad > 0): ?> is-valid<?php endif ?> input-number ind" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo $d->dh_cantidad ?>">
									</div>

									<?php $div_horas = ($h_disponibles == 0) ? 0 : $d->dh_cantidad / $h_disponibles ?>
									<?php $percent = number_format($div_horas, 2, '.', ',') ?>
									<div class="form-group col-sm-2">
										<label for="iNpact<?php echo $a->acp_id ?>">% Asignado</label>
										<input type="text" class="form-control<?php if ($percent > 0): ?> is-valid<?php endif ?> input-number" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo $percent ?>" disabled>
									</div>

									<?php if ($a->acp_rendimiento): ?>
										<div class="form-group col-sm-2">
											<label for="iNract<?php echo $a->acp_id ?>">Rendimiento</label>
											<input type="text" class="form-control<?php if ($d->dh_rendimiento > 0): ?> is-valid<?php endif ?> input-number rend" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $d->dh_rendimiento ?>">
										</div>

										<?php $t = $d->dh_cantidad * $d->dh_rendimiento ?>
										<div class="form-group col-sm-2 offset-sm-1">
											<label for="iNtact<?php echo $a->acp_id ?>">Total</label>
											<input type="text" class="form-control<?php if ($t > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
										</div>

										<?php $ts = $t * $sem_disp ?>
										<div class="form-group col-sm-2">
											<label for="iNtaact<?php echo $a->acp_id ?>">Total Anual</label>
											<input type="text" class="form-control<?php if ($ts > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($ts, 2, '.', ',') ?>" disabled>
										</div>
									<?php endif ?>
								</div>
							<?php endforeach ?>
						</div>
					<?php endif ?>
				</div>

				<div class="card-header">
					<h3 class="card-title">Totales</h3>
				</div>

				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-3">
							<label for="iNtotal">Total</label>
							<input type="text" class="form-control<?php if ($h_tot > 0): ?> is-valid<?php endif ?> input-number" tabindex="-1" id="iNtotal" name="total" value="<?php echo number_format($h_tot, 2, '.', ',') ?>" readonly>
						</div>
						<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
							<div class="form-group col-sm-2 offset-sm-3">
								<label for="iNbrecha2">Brecha Calculada C+C</label>
								<input type="text" class="form-control input-number" id="iNbrecha2" name="ibrecha2" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNactanuales">Actividades Anuales</label>
								<input type="text" class="form-control input-number" id="iNactanuales" name="iactanuales" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNbrproy">Brecha Proyectada C+C</label>
								<input type="text" class="form-control input-number" id="iNbrproy" name="ibrproy" value="0" disabled>
							</div>
						<?php endif ?>
					</div>
					<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
						<div class="row">
							<div class="form-group col-sm-2 offset-sm-6">
								<label for="iNbrechaiq2">Brecha Calculada IQ</label>
								<input type="text" class="form-control input-number" id="iNbrechaiq2" name="ibrechaiq2" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNactanualesiq">Actividades Anuales</label>
								<input type="text" class="form-control input-number" id="iNactanualesiq" name="iactanualesiq" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNbrproyiq">Brecha Proyectada IQ</label>
								<input type="text" class="form-control input-number" id="iNbrproyiq" name="ibrproyiq" value="0" disabled>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-2 offset-sm-6">
								<label for="iNthesp2">Horas Disp. Especialidad</label>
								<input type="text" class="form-control input-number" id="iNthesp2" name="ithesp2" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNthanual">Horas Anuales</label>
								<input type="text" class="form-control input-number" id="iNthanual" name="ithanual" value="0" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotproy">Horas Disp. Proyectadas</label>
								<input type="text" class="form-control input-number" id="iNtotproy" name="itotproy" value="0" disabled>
							</div>
						</div>
					<?php endif ?>
				</div>

				<div class="card-footer">
					<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar datos</button>
					<button type="reset" class="btn btn-default" id="btnClear">Limpiar</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</div>
		</form>
	</div>
</section>

<script src="program/create-program.js"></script>
