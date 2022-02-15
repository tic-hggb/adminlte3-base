<section class="content-header">
	<h1>Producción
		<small><i class="fa fa-angle-right"></i> Carga de archivo de producción</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
		<li class="active">Carga de archivo de producción</li>
	</ol>
</section>

<section class="content container-fluid">
	<form role="form" id="formNewFile">
		<p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Información General</h3>
			</div>

			<div class="box-body">
				<div class="row">
					<div class="form-group col-sm-3 has-feedback" id="gdate">
						<label class="control-label" for="idate">Mes de Producción *</label>
						<div class="input-group">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input type="text" class="form-control" id="iNdate" name="idate" data-date-format="mm/yyyy" placeholder="MM/AAAA">
						</div>
						<i class="fa form-control-feedback" id="icondate"></i>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label class="control-label" for="idocument">Archivo *</label>
						<div class="controls">
							<input name="ifile[]" class="multi" id="ifile" type="file" size="16" accept="xlsx" maxlength="1">
							<p class="help-block">Formatos admitidos: xlsx</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="progress progress-sm active" style="display: none">
							<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-6">
						<label class="control-label" for="iresult">Resultado</label>
						<div class="controls">
							<div style="min-height: 200px; border:1px solid #ccc; padding: 5px;" id="iNresult"></div>
						</div>
					</div>
				</div>

			</div>

			<div class="box-footer">
				<button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
				<button type="reset" class="btn btn-default" id="btnClear">Limpiar</button>
				<span class="ajaxLoader" id="submitLoader"></span>
			</div>
	</form>
</section>

<script src="admin/files/load-file.js"></script>