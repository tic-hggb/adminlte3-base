<?php include("class/classEstablecimientoLugar.php") ?>
<?php $l = new EstablecimientoLugar() ?>

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
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Información General</h3>
		</div>

		<form role="form" id="formNewOccupation">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-3 has-feedback" id="gdate">
						<label class="control-label" for="idate">Semana de consulta</label>
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
						<label class="control-label" for="ipiso">Lugar</label>
						<select class="form-control" id="iNpiso" name="ipiso">
							<option value="">Seleccione Lugar</option>
							<?php $lu = $l->getByEstab($_SESSION['prm_estid']) ?>
							<?php foreach ($lu as $lg): ?>
								<option value="<?php echo $lg->lug_id ?>"><?php echo $lg->lug_nombre ?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<button type="button" class="btn btn-primary" id="btnsubmit">
					<i class="fa fa-search"></i> Buscar
				</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
		</form>
	</div>

	<div class="box box-solid">
		<div class="box-body">
			<div id="calendar-cont" style="height: 75vh">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
</section>

<script src="box/occupation-floor.js"></script>