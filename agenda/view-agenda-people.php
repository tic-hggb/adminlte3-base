<?php include("class/classEstablecimientoLugar.php") ?>
<?php include("class/classAgenda.php") ?>
<?php include("class/classPersona.php") ?>
<?php include("class/classAgrupacion.php") ?>
<?php include("class/classDistribucionProg.php") ?>
<?php include("class/classDistHorasProg.php") ?>
<?php include("class/classEspecialidad.php") ?>
<?php include("class/classBloqueHora.php") ?>
<?php $l = new EstablecimientoLugar() ?>
<?php $a = new Agenda() ?>
<?php $p = new Persona() ?>
<?php $agr = new Agrupacion() ?>
<?php $dh = new DistribucionProg() ?>
<?php $dhp = new DistHorasProg() ?>
<?php $es = new Especialidad() ?>
<?php $bh = new BloqueHora() ?>

<?php $tmp = explode('-', $iden) ?>
<?php $year = $tmp[0] ?>
<?php $period = $tmp[1] ?>
<?php $est = $tmp[2] ?>
<?php $pers = $tmp[3] ?>

<?php $agen = $a->getEventsByPerson($pers, $est, $year, $period) ?>
<?php $tmp = explode('-', $agen[0]->age_periodo) ?>
<?php $per = $p->get($pers) ?>
<?php $disth = $dh->getLastByPer($pers, $est, $year) ?>

<?php
$especialidad = '';
$first = true;
$programado = 0;

foreach ($disth as $p => $v):
	if (!$first): $especialidad .= ', '; endif;
	$dataEsp = $es->get($v->disp_espid);
	$especialidad .= $dataEsp->esp_nombre;
	$first = false;

	$dhpro = $dhp->getByConsCont($v->disp_id);
	$programado += (float)$dhpro;
endforeach;
?>

<?php
$agendado = 0;

foreach ($agen as $ag => $v):
	$ag_c = $a->getCupos($v->age_id);

	foreach ($ag_c as $cu => $vc):
		$agendado += $vc->acu_numero;
	endforeach;
endforeach;
?>

<section class="content-header">
	<h1>Agenda
		<small><i class="fa fa-angle-right"></i> Calendario de Actividades Agendadas</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Inicio</a></li>
		<li><a href="index.php?section=agenda&sbs=agendasperson">Agendas por persona</a></li>
		<li class="active">Agenda</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Información General</h3>
				</div>

				<div class="box-body">
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label">Nombre</label>
							<p class="form-control-static"><?php echo $per->per_nombres ?></p>
							<input type="hidden" id="iNyear" value="<?php echo $year ?>">
							<input type="hidden" id="iNperiod" value="<?php echo $tmp[1] ?>">
							<input type="hidden" id="iNestab" value="<?php echo $est ?>">
							<input type="hidden" id="iNpers" value="<?php echo $pers ?>">
						</div>

						<div class="form-group col-xs-4">
							<label class="control-label">Profesión</label>
							<p class="form-control-static"><?php echo $per->per_profesion ?></p>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label">Cupos Programados/Agendados</label>
							<p class="form-control-static"><?php echo number_format($programado, 2, '.', ',') ?>/<?php echo number_format($agendado, 2, '.', ',') ?></p>
						</div>

						<div class="form-group col-xs-4">
							<label class="control-label">Especialidad</label>
							<p class="form-control-static"><?php echo $especialidad ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Actividades Agendadas</h3>
				</div>

				<div class="box-body">
					<div id="calendar"></div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="box box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Bloqueos Programados</h3>
				</div>

				<div class="box-body">
					<?php $blocks = $bh->getBlocks($pers, $est, $year) ?>

					<table id="tblocks" class="table table-hover table-striped">
						<thead>
						<tr>
							<th>Desde</th>
							<th>Hasta</th>
							<th>Motivo</th>
							<th>Destino Pacientes</th>
							<th>Observación</th>
						</tr>
						</thead>

						<tbody>
						<?php
						foreach ($blocks as $k => $o):
							$bl_begin = $bh->get($o->id_begin);
							$bl_end = $bh->get($o->id_end);
							?>
							<tr>
								<td><?php echo getDateToForm($bl_begin->bh_fecha) ?></td>
								<td><?php echo getDateToForm($bl_end->bh_fecha) ?></td>
								<td><?php echo $bl_begin->mau_descripcion ?></td>
								<td><?php echo $bl_begin->bdes_descripcion ?></td>
								<td><?php echo $bl_begin->bh_descripcion ?></td>
							</tr>
						<?php
						endforeach;
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="agenda/view-agenda-people.js"></script>