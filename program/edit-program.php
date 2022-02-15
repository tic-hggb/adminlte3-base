<?php include("class/classParametro.php") ?>
<?php include("class/classPersonaEstablecimiento.php") ?>
<?php include("class/classDistribucionProg.php") ?>
<?php include("class/classDistHorasProg.php") ?>
<?php include("class/classDiagnostico.php") ?>
<?php include("class/classPersona.php") ?>
<?php include("class/classCr.php") ?>
<?php include("class/classServicio.php") ?>
<?php include("class/classEspecialidad.php") ?>
<?php include("class/classJustificacion.php") ?>
<?php include("class/classActividadProgramable.php") ?>

<?php $para = new Parametro() ?>
<?php $pe = new PersonaEstablecimiento() ?>
<?php $di = new DistribucionProg() ?>
<?php $dh = new DistHorasProg() ?>
<?php $dg = new Diagnostico() ?>
<?php $p = new Persona() ?>
<?php $cr = new Cr() ?>
<?php $ser = new Servicio() ?>
<?php $es = new Especialidad() ?>
<?php $js = new Justificacion() ?>
<?php $acp = new ActividadProgramable() ?>
<?php $disp = $di->get($id) ?>
<?php $pes = $pe->get($disp->disp_pesid) ?>
<?php $t_date = explode('-', $disp->disp_fecha_ini) ?>
<?php $t_par = $para->get($t_date[0]) ?>
<?php $WEEKS = $t_par->par_semanas ?>
<?php $per = $p->get($pes->per_id) ?>
<?php $serv = $ser->get($disp->disp_serid) ?>

<?php $sem_disp = $WEEKS - (($disp->disp_vacaciones + $disp->disp_permisos + $disp->disp_congreso + $disp->disp_descanso) / 5) ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Edición de programación</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item"><a href="index.php?section=program&sbs=manageprogram">Programaciones registradas</a></li>
					<li class="breadcrumb-item active">Edición de programación</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<form role="form" id="formNewProgram">
			<input type="hidden" id="weeks" value="<?php echo $WEEKS ?>">
			<p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

			<div class="card">
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
							<p class="form-control-static"><?php echo $pes->con_descripcion ?> (<?php echo $pes->pes_correlativo ?>)</p>
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
										<input type="text" class="form-control datetimepicker-input float-right is-valid" id="iNdate" name="idate" value="<?php echo getDateMonthToForm($disp->disp_fecha_ini) ?>" data-target="#gdate" required>
									</div>
								</div>

								<div class="col-sm-6">
									<div id="gdate_t" class="input-group date" data-target-input="nearest">
										<div class="input-group-prepend" data-target="#gdate_t" data-toggle="datetimepicker">
											<span class="input-group-text">Hasta</span>
										</div>
										<input type="text" class="form-control datetimepicker-input float-right is-valid" id="iNdate_t" name="idate_t" value="<?php echo getDateMonthToForm($disp->disp_fecha_ter) ?>" data-target="#gdate_t" required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNdesc">Descripción *</label>
							<input type="text" class="form-control is-valid" id="iNdesc" name="idesc" placeholder="Ingrese descripción para la programación" value="<?php echo $disp->disp_descripcion ?>" maxlength="64" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNcr">Centro de Responsabilidad *</label>
							<select class="form-control is-valid" id="iNcr" name="icr" required>
								<option value="">Seleccione CR</option>
								<?php $cen = $cr->getAll() ?>
								<?php foreach ($cen as $c): ?>
									<option value="<?php echo $c->cr_id ?>" <?php if ($c->cr_id == $serv->cr_id): ?>selected<?php endif ?>><?php echo $c->cr_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>

						<div class="form-group col-sm-6" id="gserv">
							<label for="iNserv">Servicio *</label>
							<select class="form-control is-valid" id="iNserv" name="iserv" required>
								<option value="">Seleccione servicio</option>
								<?php $se = $ser->getByCR($serv->cr_id) ?>
								<?php foreach ($se as $s): ?>
									<option value="<?php echo $s->ser_id ?>" <?php if ($s->ser_id == $serv->ser_id): ?>selected<?php endif ?>><?php echo $s->ser_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6" id="gesp">
							<label for="iNesp">Especialidad *</label>
							<select class="form-control is-valid" id="iNesp" name="iesp" required>
								<option value="">Seleccione especialidad</option>
								<?php $esp = $es->getByServicio($serv->ser_id) ?>
								<?php foreach ($esp as $e): ?>
									<option value="<?php echo $e->esp_id ?>" <?php if ($e->esp_id == $disp->disp_espid): ?>selected<?php endif ?>><?php echo $e->esp_nombre ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>

					<div class="form-group clearfix">
						<div class="icheck-info d-inline">
							<input type="checkbox" id="iNgeneral" name="igeneral"<?php if ($disp->disp_med_general): ?> checked<?php endif ?>>
							<label for="iNgeneral">Médico general</label>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="iNobserv">Observaciones</label>
							<input type="text" class="form-control<?php if ($disp->disp_observaciones !== ''): ?> is-valid<?php endif ?>" id="iNobserv" name="iobserv" placeholder="Ingrese observación (Liberado de guardia / PAO)" maxlength="64" value="<?php echo $disp->disp_observaciones ?>">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-2">
							<label for="iNvacaciones">Vacaciones *</label>
							<input type="text" class="form-control<?php echo ($disp->disp_vacaciones >= 0) ? ' is-valid' : '' ?> input-number disp" id="iNvacaciones" name="ivacaciones" placeholder="Ingrese días de vacaciones" value="<?php echo $disp->disp_vacaciones ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNpermiso">Permisos *</label>
							<input type="text" class="form-control<?php echo ($disp->disp_permisos >= 0) ? ' is-valid' : '' ?> input-number disp" id="iNpermiso" name="ipermiso" placeholder="Ingrese días de permiso" value="<?php echo $disp->disp_permisos ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNcongreso">Congreso *</label>
							<input type="text" class="form-control<?php echo ($disp->disp_congreso >= 0) ? ' is-valid' : '' ?> input-number disp" id="iNcongreso" name="icongreso" placeholder="Ingrese días de congresos" value="<?php echo $disp->disp_congreso ?>" required>
						</div>

						<div class="form-group col-sm-2">
							<label for="iNdescanso">Descanso comp. *</label>
							<input type="text" class="form-control<?php echo ($disp->disp_descanso >= 0) ? ' is-valid' : '' ?> input-number disp" id="iNdescanso" name="idescanso" placeholder="Ingrese días de descansos" value="<?php echo $disp->disp_descanso ?>" required>
						</div>

						<div class="form-group col-sm-3 offset-sm-1">
							<label for="iNsemdisp">Semanas disponibles</label>
							<input type="text" class="form-control input-number" id="iNsemdisp" name="isemdisp" tabindex="-1" value="<?php echo $sem_disp ?>" disabled>
						</div>
					</div>
				</div>

				<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
					<?php $tmp = explode('-', $disp->disp_fecha_ini) ?>
					<?php $date_d = $tmp[0] . '-01-01' ?>
					<?php $diag = $dg->getByEspDate($_SESSION['prm_estid'], $disp->disp_espid, $disp->disp_serid, $date_d) ?>

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
									<input type="text" class="form-control input-number" id="iNtat" name="itat" value="<?php echo number_format($diag->dia_total_esp, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNges">Total Lista de Espera</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNges" name="iges" value="<?php echo number_format($diag->dia_lista, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalan">Total Anual</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B</div>
									</div>
									<input type="text" class="form-control input-number is-valid" id="iNtotalan" name="itotalan" value="<?php echo number_format($diag->dia_total_esp + $diag->dia_lista, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<?php $p_cc = $di->getProgrammedCC($_SESSION['prm_estid'], $disp->disp_espid, $disp->disp_pesid) ?>
							<?php
							$total_cc = 0;
							foreach ($p_cc as $k => $v):
								$total = $WEEKS - round(($v->vacas + $v->permisos + $v->congreso) / 5);

								$tot_anual = $total * $v->disponibles;
								$total_cc += $tot_anual;
							endforeach;
							?>

							<div class="form-group col-sm-2">
								<label for="iNtotalesp">Total Programado</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number is-valid" id="iNtotalesp" name="itotalesp" value="<?php echo number_format(round($total_cc), 0, '', '.') ?>" disabled>
								</div>
								<i class="fa form-control-feedback" id="icontotalesp"></i>
							</div>

							<?php $br_cc = $total_cc - $diag->dia_total_esp + $diag->dia_lista ?>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNbrecha">Brecha Calculada C+C</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D - (A + B)</div>
									</div>
									<input type="text" class="form-control input-number<?php echo ($br_cc >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrecha" name="ibrecha" value="<?php echo number_format(round($br_cc), 0, '', '.') ?>" disabled>
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
									<input type="text" class="form-control input-number" id="iNtiq" name="itiq" value="<?php echo number_format($diag->dia_total_esp_iq, 0, '', '.') ?>" disabled>
								</div>
								<i class="fa form-control-feedback" id="icontiq"></i>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNgesiq">Total Lista de Espera</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNgesiq" name="igesiq" value="<?php echo number_format($diag->dia_lista_iq, 0, '', '.') ?>" disabled>
								</div>
								<i class="fa form-control-feedback" id="icongesiq"></i>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtotalaniq">Total Anual</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalaniq" name="itotalaniq" value="<?php echo number_format($diag->dia_total_esp_iq + $diag->dia_lista_iq, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<?php $p_iq = $di->getProgrammedIQ($_SESSION['prm_estid'], $disp->disp_espid, $disp->disp_pesid) ?>
							<?php
							$total_iq = 0;
							foreach ($p_iq as $k => $v):
								$total = $WEEKS - round(($v->vacas + $v->permisos + $v->congreso) / 5);

								$tot_anual = $total * $v->disponibles;
								$total_iq += $tot_anual;
							endforeach;
							?>

							<div class="form-group col-sm-2">
								<label for="iNtotalespiq">Total Programado</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNtotalespiq" name="itotalespiq" value="<?php echo number_format(round($total_iq), 0, '', '.') ?>" disabled>
								</div>
							</div>

							<?php $br_iq = $total_iq - $diag->dia_total_esp_iq + $diag->dia_lista_iq ?>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNbrechaiq">Brecha Calculada IQ</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D - (A + B)</div>
									</div>
									<input type="text" class="form-control input-number<?php echo ($br_iq >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrechaiq" name="ibrechaiq" value="<?php echo number_format(round($br_iq), 0, '', '.') ?>" disabled>
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
									<input type="text" class="form-control input-number is-warning" id="iNtatc" name="itatc" value="<?php echo number_format($diag->dia_disp_atc, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtata">Total Horas At. Abierta</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">B</div>
									</div>
									<input type="text" class="form-control input-number is-warning" id="iNtata" name="itata" value="<?php echo number_format($diag->dia_disp_ata, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNtpro">Total Horas Proced.</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">C</div>
									</div>
									<input type="text" class="form-control input-number is-warning" id="iNtpro" name="itpro" value="<?php echo number_format($diag->dia_disp_pro, 0, '', '.') ?>" disabled>
								</div>
							</div>

							<?php $p_hrs = $di->getProgrammedEsp($_SESSION['prm_estid'], $disp->disp_espid, $disp->disp_pesid); ?>
							<?php
							$total_disp = 0;
							foreach ($p_hrs as $k => $v):
								$total = $WEEKS - round(($v->vacas + $v->permisos + $v->congreso) / 5);

								$tot_anual = $total * $v->disponibles;
								$total_disp += $tot_anual;
							endforeach;
							?>

							<div class="form-group col-sm-2">
								<label for="iNthpro">Total Horas Programadas</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">D</div>
									</div>
									<input type="text" class="form-control input-number" id="iNthpro" name="ithpro" value="<?php echo number_format(round($total_disp), 0, '', '.') ?>" disabled>
								</div>
							</div>

							<?php $br_esp = $diag->dia_disp_atc + $diag->dia_disp_ata + $diag->dia_disp_pro - $total_disp ?>

							<div class="form-group col-sm-3 offset-sm-1">
								<label for="iNthesp">Total Horas Disponibles</label>
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<div class="input-group-text">A + B + C - D</div>
									</div>
									<input type="text" class="form-control input-number<?php echo ($br_esp <= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNthesp" name="ithesp" value="<?php echo number_format(round($br_esp), 0, '', '.') ?>" disabled>
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
							<select class="form-control<?php if ($disp->disp_jusid != ''): ?> is-valid<?php endif ?>" id="iNjustif" name="ijustif">
								<option value="">Seleccione una justificación</option>
								<?php $jus = $js->getAll() ?>
								<?php foreach ($jus as $j): ?>
									<option value="<?php echo $j->jus_id ?>" <?php if ($j->jus_id == $disp->disp_jusid): ?>selected<?php endif ?>><?php echo $j->jus_descripcion ?></option>
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
							<input type="text" class="form-control input-number" id="iNcont" value="<?php echo number_format($pes->pes_horas, 2, '.', '') ?>" disabled>
						</div>
					</div>

					<div class="row">
						<?php $h_sem = 0 ?>
						<?php $dh_d = $dh->getByDistTH($disp->disp_id, 1) ?>
						<?php $h_disp = $dh_d->dhp_cantidad ?>
						<?php $h_sem += $dh_d->dhp_cantidad ?>
						<div class="form-group col-sm-3">
							<label for="iNdisp">Horas Disponibles *</label>
							<input type="text" class="form-control input-number disponib<?php echo ($dh_d->dhp_cantidad > 0) ? ' is-valid' : '' ?>" id="iNdisp" name="disp" value="<?php echo number_format($dh_d->dhp_cantidad, 2, '.', '') ?>" required>
						</div>

						<?php $dh_d = $dh->getByDistTH($disp->disp_id, 2) ?>
						<?php $h_sem += $dh_d->dhp_cantidad ?>
						<div class="form-group col-sm-3" id="guniversidad">
							<label for="iNuniversidad">Médico Universidad</label>
							<input type="text" class="form-control input-number disponib<?php echo ($dh_d->dhp_cantidad > 0) ? ' is-valid' : '' ?>" id="iNuniversidad" name="universidad" value="<?php echo $dh_d->dhp_cantidad ?>">
						</div>

						<?php $dh_d = $dh->getByDistTH($disp->disp_id, 3) ?>
						<?php $h_sem += $dh_d->dhp_cantidad ?>
						<div class="form-group col-sm-3" id="gbecados">
							<label for="iNbecados">Becados</label>
							<input type="text" class="form-control input-number disponib <?php echo ($dh_d->dhp_cantidad > 0) ? ' is-valid' : '' ?>" id="iNbecados" name="becados" value="<?php echo $dh_d->dhp_cantidad ?>">
						</div>

						<div class="form-group col-sm-3" id="gtdisponible">
							<label for="iNtdisponible">Horas Semanales Disp.</label>
							<input type="text" class="form-control input-number<?php echo ($h_sem > 0) ? ' is-valid' : '' ?>" tabindex="-1" id="iNtdisponible" name="tdisponible" value="<?php echo number_format($h_sem, 2, '.', '') ?>" disabled>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Actividades Policlínico</h3>
				</div>

				<div class="card-body">
					<?php $total_horas = 0; ?>
					<?php $total_horas_poli = 0; ?>
					<?php $total_act_poli = 0; ?>
					<?php $total_anual = 0; ?>

					<!-- Consultas, Controles, Consultas abreviadas -->
					<?php $t_p = $ta_p = $ts_p = 0 ?>
					<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
						<?php $arr = array(4, 5, 21) ?>
					<?php else: ?>
						<?php $arr = array(126, 127, 75) ?>
					<?php endif ?>
					<?php foreach ($arr as $i): ?>
						<?php $a = $acp->get($i) ?>

						<?php $dh_d = $dh->getByDistTH($disp->disp_id, $i) ?>
						<?php $total_horas += $dh_d->dhp_cantidad ?>
						<?php $total_horas_poli += $dh_d->dhp_cantidad ?>
						<div class="row">
							<div class="form-group col-sm-3">
								<label for="iNact<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
								<input type="text" class="form-control input-number ind tpoli<?php if ($dh_d->dhp_cantidad > 0): ?> is-valid<?php endif ?>" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo number_format($dh_d->dhp_cantidad, 2, '.', '') ?>">
							</div>

							<?php $div_horas = ($h_disp == 0) ? 0 : $dh_d->dhp_cantidad / $h_disp ?>
							<?php $percent = number_format($div_horas, 2, '.', ',') ?>
							<div class="form-group col-sm-2">
								<label for="iNpact<?php echo $a->acp_id ?>">% Asignado</label>
								<input type="text" class="form-control input-number<?php if ($percent > 0): ?> is-valid<?php endif ?>" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo number_format($percent, 2, '.', '') ?>" disabled>
							</div>

							<div class="form-group col-sm-2">
								<label for="iNract<?php echo $a->acp_id ?>">Rendimiento</label>
								<input type="text" class="form-control input-number rend<?php if ($dh_d->dhp_rendimiento > 0): ?> is-valid<?php endif ?>" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $dh_d->dhp_rendimiento ?>">
							</div>

							<?php $t = $dh_d->dhp_cantidad * $dh_d->dhp_rendimiento ?>
							<?php $total_act_poli += $t ?>
							<div class="form-group col-sm-2 offset-sm-1">
								<label for="iNtact<?php echo $a->acp_id ?>">Total</label>
								<input type="text" class="form-control input-number tactp<?php if ($t > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
							</div>

							<?php $tot_anual = $t * $sem_disp ?>
							<?php $total_anual += $tot_anual ?>
							<?php //$total_act_brecha = $total_anual ?>
							<div class="form-group col-sm-2">
								<label for="iNtaact<?php echo $a->acp_id ?>">Total Anual</label>
								<input type="text" class="form-control input-number tanual<?php if ($tot_anual > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($tot_anual, 2, '.', ',') ?>" disabled>
							</div>
						</div>
					<?php endforeach ?>

					<!-- TOTAL POLICLINICO -->
					<div class="row">
						<div class="form-group col-sm-3" id="gtpoli">
							<label for="iNtpoli">Total Policlínico</label>
							<input type="text" class="form-control input-number<?php echo ($total_horas_poli > 0) ? ' is-valid' : '' ?>" id="iNtpoli" tabindex="-1" name="tpoli" value="<?php echo number_format($total_horas_poli, 2, '.', '') ?>" disabled>
						</div>

						<div class="form-group col-sm-2 offset-sm-5" id="gtapoli">
							<label for="iNtapoli">Total</label>
							<input type="text" class="form-control input-number<?php echo ($total_act_poli > 0) ? ' is-valid' : '' ?>" id="iNtapoli" tabindex="-1" name="tapoli" value="<?php echo number_format($total_act_poli, 2, '.', '') ?>" disabled>
						</div>

						<div class="form-group col-sm-2" id="gtaapoli">
							<label for="iNtaapoli">Total Anual</label>
							<input type="text" class="form-control input-number<?php echo ($total_anual > 0) ? ' is-valid' : '' ?>" tabindex="-1" id="iNtaapoli" name="taapoli" value="<?php echo number_format(round($total_anual), 0, '', '.') ?>" disabled>
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
								<?php $dh_d = $dh->getByDistTH($disp->disp_id, $a->acp_id) ?>
								<?php $total_horas += $dh_d->dhp_cantidad ?>

								<div class="row">
									<div class="form-group col-sm-3">
										<label for="iacp<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
										<input type="text" class="form-control input-number ind ind-proc<?php echo ($dh_d->dhp_cantidad > 0) ? ' is-valid' : '' ?>" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo $dh_d->dhp_cantidad ?>">
									</div>

									<?php $div_horas = ($h_disp == 0) ? 0 : $dh_d->dhp_cantidad / $h_disp ?>
									<?php $percent = number_format($div_horas, 2, '.', ',') ?>
									<div class="form-group col-sm-2">
										<label for="pact<?php echo $a->acp_id ?>">% Asignado</label>
										<input type="text" class="form-control input-number<?php if ($percent > 0): ?> is-valid<?php endif ?>" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo $percent ?>" disabled>
									</div>

									<?php if ($a->acp_rendimiento): ?>
										<div class="form-group col-sm-2">
											<label for="ract<?php echo $a->acp_id ?>">Rendimiento</label>
											<input type="text" class="form-control input-number rend<?php echo ($dh_d->dhp_rendimiento > 0) ? ' is-valid' : '' ?>" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $dh_d->dhp_rendimiento ?>">
										</div>

										<?php $t = $dh_d->dhp_cantidad * $dh_d->dhp_rendimiento ?>
										<div class="form-group col-sm-2 offset-sm-1">
											<label for="tact<?php echo $a->acp_id ?>">Total</label>
											<input type="text" class="form-control input-number<?php if ($t > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
										</div>

										<?php $ts = $t * $sem_disp ?>
										<div class="form-group col-sm-2">
											<label for="taact<?php echo $a->acp_id ?>">Total Anual</label>
											<input type="text" class="form-control input-number<?php if ($ts > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($ts, 2, '.', ',') ?>" disabled>
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
								<?php $dh_d = $dh->getByDistTH($disp->disp_id, $a->acp_id) ?>
								<?php $total_horas += $dh_d->dhp_cantidad ?>

								<div class="row">
									<div class="form-group col-sm-3">
										<label for="iacp<?php echo $a->acp_id ?>"><?php echo $a->acp_descripcion ?></label>
										<input type="text" class="form-control input-number ind ind-proc<?php echo ($dh_d->dhp_cantidad > 0) ? ' is-valid' : '' ?>" id="iNact<?php echo $a->acp_id ?>" name="iact<?php echo $a->acp_id ?>" value="<?php echo $dh_d->dhp_cantidad ?>">
									</div>

									<?php $div_horas = ($h_disp == 0) ? 0 : $dh_d->dhp_cantidad / $h_disp ?>
									<?php $percent = number_format($div_horas, 2, '.', ',') ?>
									<div class="form-group col-sm-2">
										<label for="pact<?php echo $a->acp_id ?>">% Asignado</label>
										<input type="text" class="form-control input-number<?php if ($percent > 0): ?> is-valid<?php endif ?>" id="iNpact<?php echo $a->acp_id ?>" tabindex="-1" name="pact<?php echo $a->acp_id ?>" value="<?php echo $percent ?>" disabled>
									</div>

									<?php if ($a->acp_rendimiento): ?>
										<div class="form-group col-sm-2">
											<label for="ract<?php echo $a->acp_id ?>">Rendimiento</label>
											<input type="text" class="form-control input-number rend<?php echo ($dh_d->dhp_rendimiento > 0) ? ' is-valid' : '' ?>" id="iNract<?php echo $a->acp_id ?>" name="ract<?php echo $a->acp_id ?>" value="<?php echo $dh_d->dhp_rendimiento ?>">
										</div>

										<?php $t = $dh_d->dhp_cantidad * $dh_d->dhp_rendimiento ?>
										<div class="form-group col-sm-2 offset-sm-1">
											<label for="tact<?php echo $a->acp_id ?>">Total</label>
											<input type="text" class="form-control input-number<?php if ($t > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtact<?php echo $a->acp_id ?>" name="tact<?php echo $a->acp_id ?>" value="<?php echo number_format($t, 2, '.', ',') ?>" disabled>
										</div>

										<?php $ts = $t * $sem_disp ?>
										<div class="form-group col-sm-2">
											<label for="taact<?php echo $a->acp_id ?>">Total Anual</label>
											<input type="text" class="form-control input-number<?php if ($ts > 0): ?> is-valid<?php endif ?>" tabindex="-1" id="iNtaact<?php echo $a->acp_id ?>" name="taact<?php echo $a->acp_id ?>" value="<?php echo number_format($ts, 2, '.', ',') ?>" disabled>
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
							<input type="text" class="form-control input-number<?php echo ($total_horas > 0) ? ' is-valid' : '' ?>" tabindex="-1" id="iNtotal" name="total" value="<?php echo number_format($total_horas, 2, '.', '') ?>" readonly>
						</div>
						<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
							<div class="form-group col-sm-2 offset-sm-3">
								<label for="iNbrecha2">Brecha Calculada C+C</label>
								<input type="text" class="form-control input-number<?php echo ($br_cc >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrecha2" name="ibrecha2" value="<?php echo number_format(round($br_cc), 0, '', '.') ?>" disabled>
							</div>
							<?php $total_act_brecha = 0 ?>
							<div class="form-group col-sm-2">
								<label for="iNactanuales">Actividades Anuales</label>
								<input type="text" class="form-control input-number" id="iNactanuales" name="iactanuales" value="<?php echo number_format(round($total_act_brecha), 0, '', '.') ?>" disabled>
							</div>

							<?php $br_pro = $br_cc + $total_act_brecha ?>

							<div class="form-group col-sm-2">
								<label for="iNbrproy">Brecha Proyectada C+C</label>
								<input type="text" class="form-control input-number<?php echo ($br_pro >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrproy" name="ibrproy" value="<?php echo number_format(round($br_pro), 0, '', '.') ?>" disabled>
							</div>
						<?php endif ?>
					</div>
					<?php if ($per->per_prid == 4 or $per->per_prid == 14 or $per->per_prid == 16): ?>
						<div class="row">
							<div class="form-group col-sm-2 offset-sm-6">
								<label for="iNbrechaiq2">Brecha Calculada IQ</label>
								<input type="text" class="form-control input-number<?php echo ($br_iq >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrechaiq2" name="ibrechaiq2" value="<?php echo number_format(round($br_iq), 0, '', '.') ?>" disabled>
							</div>

							<?php $total_act_brecha_iq = 0 ?>

							<div class="form-group col-sm-2">
								<label for="iNactanualesiq">Actividades Anuales</label>
								<input type="text" class="form-control input-number" id="iNactanualesiq" name="iactanualesiq" value="<?php echo number_format(round($total_act_brecha_iq), 0, '', '.') ?>" disabled>
							</div>

							<?php $br_pro_iq = $br_iq + $total_act_brecha_iq ?>

							<div class="form-group col-sm-2">
								<label for="iNbrproyiq">Brecha Proyectada IQ</label>
								<input type="text" class="form-control input-number<?php echo ($br_pro_iq >= 0) ? ' is-valid' : ' is-invalid' ?>" id="iNbrproyiq" name="ibrproyiq" value="<?php echo number_format(round($br_pro_iq), 0, '', '.') ?>" disabled>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-2 offset-sm-6">
								<label for="iNthesp2">Horas Disp. Especialidad</label>
								<input type="text" class="form-control input-number<?php echo ($br_esp < 0) ? ' is-valid' : ' is-invalid' ?>" id="iNthesp2" name="ithesp2" value="<?php echo number_format(round($br_esp), 0, '', '.') ?>" disabled>
							</div>

							<div class="form-group col-sm-2" id="gthanual">
								<label for="iNthanual">Horas Anuales</label>
								<input type="text" class="form-control input-number" id="iNthanual" name="ithanual" value="0" disabled>
							</div>

							<div class="form-group col-sm-2" id="gtotproy">
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

<script src="program/edit-program.js"></script>
