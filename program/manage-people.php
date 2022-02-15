<section class="content-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<h1>Contratos registrados</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="index.php?section=home">Home</a></li>
					<li class="breadcrumb-item active">Contratos registrados</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Filtros de búsqueda</h3>
			</div>

			<form role="form" id="formNewProgram">
				<div class="card-body">
					<div class="row">
						<div class="form-group col-sm-4" id="gplanta">
							<label for="iNplanta">Planta</label>
							<select class="form-control" id="iNplanta" name="iplanta">
								<option value="">TODAS</option>
								<option value="0">MÉDICA</option>
								<option value="1">NO MÉDICA</option>
								<option value="2">ODONTOLÓGICA</option>
							</select>
						</div>
					</div>
				</div>

				<div class="card-footer">
					<button type="button" class="btn btn-primary" id="btnsubmit">
						<i class="fa fa-search"></i> Buscar
					</button>
					<i class="fas fa-cog fa-spin ajaxLoader" id="submitLoader"></i>
				</div>
			</form>
		</div>

		<div class="card">
			<div class="card-header">
				<h3 class="card-title" id="table-title-f">Contratos registrados</h3>
			</div>

			<div class="card-body">
				<table id="tpeople" class="table table-hover table-striped">
					<thead>
					<tr>
						<th>RUT</th>
						<th>Nombre</th>
						<th>Profesión</th>
						<th>Ley</th>
						<th>Establecimiento</th>
						<th>Correlativo</th>
						<th>Horas</th>
						<th>Estado</th>
						<th></th>
					</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<script src="program/manage-people.js"></script>
