<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersonaEstablecimiento.php");
include("../src/fn.php");
$_admin = $_prog = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']) $_admin = true;
if (isset($_SESSION['prm_userprog']) && $_SESSION['prm_userprog']) $_prog = true;

if (isset($_GET['iplanta']))
	$planta = $_GET['iplanta'];

$pe = new PersonaEstablecimiento();

// DB table to use
$table = 'prm_persona_establecimiento';

// Table's primary key
$primaryKey = 'pes_id';
$index = 0;

// indexes
$columns = array(
	array('db' => 'per_rut', 'dt' => $index, 'field' => 'per_rut'),
	array('db' => 'per_nombres', 'dt' => ++$index, 'field' => 'per_nombres'),
	array('db' => 'prof_nombre', 'dt' => ++$index, 'field' => 'prof_nombre'),
	array('db' => 'con_descripcion', 'dt' => ++$index, 'field' => 'con_descripcion'),
	array('db' => 'est_nombre', 'dt' => ++$index, 'field' => 'est_nombre'),
	array('db' => 'pes_correlativo', 'dt' => ++$index, 'field' => 'pes_correlativo'),
	array('db' => 'pes_horas', 'dt' => ++$index, 'field' => 'pes_horas'),
	array('db' => 'pes_activo', 'dt' => ++$index, 'field' => 'pes_activo',
		'formatter' => function ($d) {
			return ($d) ? '<small class="badge bg-green">ACTIVO</small>' : '<small class="badge bg-red">INACTIVO</small>';
		}
	),
	array('db' => 'pes_id', 'dt' => ++$index, 'field' => 'pes_id',
		'formatter' => function ($d) use ($_admin, $_prog, $pe) {
			$pes = $pe->get($d);
			$string = '';

			if ($_admin or $_prog):
				$string .= '<a href="index.php?section=program&sbs=editpeople&id=' . $d . '" class="btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Editar contrato"><i class="fa fa-pen"></i></a>';

				if ($pes->pes_activo)
					$string .= ' <button id="id_' . $d . '" class="desactCont btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Desactivar contrato"><i class="fa fa-times"></i></button>';
				else
					$string .= ' <button id="id_' . $d . '" class="actCont btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Activar contrato"><i class="fa fa-check"></i></button>';
			endif;

			return $string;
		})
);

$joinQuery = ' FROM prm_persona_establecimiento pe';
$joinQuery .= ' JOIN prm_persona p ON pe.per_id = p.per_id';
$joinQuery .= ' JOIN prm_profesion pr ON p.prof_id = pr.prof_id';
$joinQuery .= ' JOIN prm_tipo_contrato c ON pe.con_id = c.con_id';
$joinQuery .= ' JOIN prm_establecimiento e ON pe.est_id = e.est_id';

$extraWhere = '';

if (isset($planta) and $planta != ''):
	switch ($planta):
		case '0':
			$cond = "p.prof_id = 14";
			break;
		case '1':
			$cond = "p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
			break;
		case '2':
			$cond = "p.prof_id = 4 OR p.prof_id = 16";
			break;
		default:
			$cond = '';
			break;
	endswitch;

	$extraWhere .= $cond;
endif;

if (!$_admin):
	if (isset($planta) and $planta != '') $extraWhere .= ' AND ';
	$extraWhere .= "pe.est_id = " . $_SESSION['prm_estid'];
endif;

$groupBy = "";
$having = "";

// SQL server connection information
$sql_details = array(
	'user' => DB_USER,
	'pass' => DB_PASSWORD,
	'db' => DB_DATABASE,
	'host' => DB_HOST
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('../src/ssp2.class.php');

echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
