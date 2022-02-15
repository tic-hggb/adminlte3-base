<?php include("class/classGroup.php") ?>
<?php $gr = new Group() ?>

<section class="content-header">
	<h1>Panel de Control
		<small><i class="fa fa-angle-right"></i> Administración de Grupos</small>
	</h1>

	<ol class="breadcrumb">
		<li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
		<li class="active">Administración de Grupos</li>
	</ol>
</section>

<?php $groups = $gr->getAll(); ?>

<section class="content container-fluid">
	<div class="box box-default">
		<div class="box-header with-border">
			<h3 class="box-title">Grupos registrados</h3>
		</div>

		<div class="box-body">
			<table id="tgroups" class="table table-hover table-striped">
				<thead>
				<tr>
					<th>Nombre</th>
					<th>Fecha creación</th>
					<th></th>
				</tr>
				</thead>

				<tbody>
				<?php foreach ($groups as $aux => $g): ?>
					<tr>
						<td><?php echo $g->gr_nombre ?></td>
						<td><?php echo getDateBD($g->gr_fecha) ?></td>
						<td>
							<button id="id_<?php echo $g->gr_id ?>" data-toggle="modal" data-target="#groupDetail" class="groupModal btn btn-xs btn-info" data-tooltip="tooltip" data-placement="top" title="Ver detalles"><i class="fa fa-search"></i></button>
							<a class="groupEdit btn btn-xs btn-success" href="index.php?section=groups&sbs=editgroup&id=<?php echo $g->gr_id ?>" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>
							<button id="del_<?php echo $g->gr_id ?>" class="groupDelete btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-remove"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<!-- Modal -->
<div class="modal fade" id="groupDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Información de grupo
					<small id="g_name"></small>
				</h4>
			</div>
			<div class="modal-body">
				<div class="td-div">
					<p class="td-div-t">Nombre</p>
					<p class="td-div-i" id="g_nombre"></p>
				</div>
				<div class="td-div">
					<p class="td-div-t">Fecha de creación</p>
					<p class="td-div-i" id="g_fecha"></p>
				</div>
				<div class="td-div no-bottom">
					<p class="td-div-t">Perfil</p>
					<p class="td-div-i" id="g_pnombre"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<script src="admin/groups/manage-groups.js"></script>