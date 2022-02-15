<section class="content-header">
	<h1>Contactabilidad
		<small><i class="fa fa-angle-right"></i> Mensajes recibidos</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Mensajes recibidos</li>
	</ol>
</section>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Filtros de búsqueda</h3>
		</div>

		<?php
		$date_i = date('d/m/Y');
		$date_f = date('d/m/Y');
		?>

		<form role="form" id="formNewFilter">
			<div class="box-body">
				<div class="row">
					<div class="form-group col-xs-5 has-feedback" id="gdate">
						<label class="control-label" for="idate">Fecha</label>
						<div class="input-group input-daterange" id="iNdate">
							<input type="text" class="form-control" id="iNdatei" name="idate" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" value="<?php echo $date_i ?>" required>
							<span class="input-group-addon">hasta</span>
							<input type="text" class="form-control" id="iNdatet" name="idatet" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" value="<?php echo $date_f ?>" required>
						</div>
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

	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title" id="table-title-f">Mensajes recibidos</h3>
		</div>

		<div class="box-body">
			<table id="tsms" class="table table-hover table-striped">
				<thead>
				<tr>
					<th>ID</th>
					<th>RUT</th>
					<th>Número</th>
					<th>Texto</th>
					<th>Recepción</th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</section>

<script src="contactability/manage-received.js"></script>