<?php include 'class/classPersonaEstablecimiento.php' ?>
<?php include 'class/classDistribucionProg.php' ?>
<?php $pe = new PersonaEstablecimiento() ?>
<?php $d = new DistribucionProg() ?>

<?php $est = ($_admin) ? null : $_SESSION['prm_estid']; ?>
<?php $numTotalMed = $pe->getTotalContratosByType(1, $est) ?>
<?php $numTotalNoMed = $pe->getTotalContratosByType(2, $est) ?>

<?php $curMonth = date('m') ?>
<?php
if ($curMonth <= 3) $per = date('Y') . '-01-01';
else if ($curMonth <= 6) $per = date('Y') . '-04-01';
else if ($curMonth <= 9) $per = date('Y') . '-07-01';
else $per = date('Y', strtotime('+1 year')) . '-01-01';
?>

<?php $numIngMed = $d->getProgramsByPeriodEstab(1, $est, $per) ?>
<?php $numIngNoMed = $d->getProgramsByPeriodEstab(2, $est, $per) ?>
<?php $percMed = $numIngMed / $numTotalMed * 100 ?>
<?php $percNoMed = $numIngNoMed / $numTotalNoMed * 100 ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="callout callout-info">
			<h5><i class="fa fa-check text-info"></i> <small>Bienvenido(a),</small> <?php echo $_SESSION['prm_userfname'] ?></h5>
			<p>A la plataforma de Programación Médica del Servicio de Salud Concepción.</p>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<h5 class="mt-4 mb-2">
			Programaciones ingresadas
		</h5>

		<div class="row">
			<div class="col-sm-6">
				<div class="card card-solid bg-gradient-green">
					<div class="card-header">
						<h3 class="card-title"><i class="fa fa-area-chart"></i> Producción médica anual</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="chart" id="line-chart" style="height: 225px;"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="card card-solid bg-gradient-dark">
					<div class="card-header">
						<h3 class="card-title"><i class="fa fa-area-chart"></i> Producción no médica anual</h3>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="chart" id="line-chart-nm" style="height: 225px;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="info-box bg-blue">
					<span class="info-box-icon"><i class="ion ion-ios-people"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Programaciones planta médica</span>
						<span class="info-box-number"><?php echo $numIngMed ?> de <?php echo $numTotalMed ?></span>
						<div class="progress">
							<div class="progress-bar" style="width: <?php echo round($percMed, 2) ?>%"></div>
						</div>
						<span class="progress-description"><?php echo round($percMed, 2) ?>% programados</span>
					</div>
				</div>

				<div class="info-box bg-red">
					<span class="info-box-icon"><i class="ion ion-ios-people"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Programaciones planta no médica</span>
						<span class="info-box-number"><?php echo $numIngNoMed ?> de <?php echo $numTotalNoMed ?></span>
						<div class="progress">
							<div class="progress-bar" style="width: <?php echo round($percNoMed, 2) ?>%"></div>
						</div>
						<span class="progress-description"><?php echo round($percNoMed, 2) ?>% programados</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="main/main-index.js?20200813"></script>
