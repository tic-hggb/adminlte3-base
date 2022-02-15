<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersona.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../src/fn.php");
$_admin = false;
$db = new myDBC();
$di = new DistribucionProg();
$h = new DistHorasProg();

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

$fecha = $_GET['iyear'] . '-' . $_GET['iperiodo'] . '-01';
$fecha_ter = $_GET['iyear'] . '-12-31';

$est = (!$_admin) ? $_SESSION['prm_estid'] : $_GET['iestab'];

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'prm_persona';

// Table's primary key
$primaryKey = 'prm_persona.per_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array(
		'db' => 'per_rut',
		'dt' => 0
	),
	array(
		'db' => 'per_nombres',
		'dt' => 1,
		'formatter' => function ($d, $row) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'ser_nombre',
		'dt' => 2,
		'formatter' => function ($d, $row) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'esp_nombre',
		'dt' => 3,
		'formatter' => function ($d, $row) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'disp_descripcion',
		'dt' => 4,
		'formatter' => function ($d, $row) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'disp_fecha_ini',
		'dt' => 5,
		'formatter' => function ($d, $row) use ($di) {
			return getDateBD($d);
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 6,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'C');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 7,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'H');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 8,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'I');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 9,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'CMA');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 10,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'P');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 11,
		'formatter' => function ($d, $row) use ($h) {
			return $h->getByCategory($d, 'A');
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => 12,
		'formatter' => function ($d, $row) use ($di) {
			return $di->getTotalDisp($d);
		}
	)
);

$joins = ' JOIN prm_persona_establecimiento ON prm_persona.per_id = prm_persona_establecimiento.per_id';
$joins .= ' JOIN prm_distribucion_prog ON prm_persona_establecimiento.pes_id = prm_distribucion_prog.pes_id';
$joins .= ' JOIN prm_dist_horas_prog ON prm_distribucion_prog.disp_id = prm_dist_horas_prog.disp_id';
$joins .= ' JOIN prm_actividad_prog ON prm_dist_horas_prog.acp_id = prm_actividad_prog.acp_id';
$joins .= ' LEFT JOIN prm_especialidad ON prm_distribucion_prog.esp_id = prm_especialidad.esp_id';
$joins .= ' LEFT JOIN prm_servicio ON prm_distribucion_prog.ser_id = prm_servicio.ser_id ';

$cond = '';

switch ($est):
	case '':
		$cond .= "";
		break;
	default:
		$cond .= " AND prm_persona_establecimiento.est_id = $est";
		break;
endswitch;

if ($_GET['iperiodo'] != '00'):
	$where = " disp_fecha_ini = '" . $fecha . "' AND disp_fecha_ter = '" . $fecha_ter . "' AND jus_id IS NULL AND prof_id IN (14,16) $cond";
else:
	$where = " prm_distribucion_prog.disp_ultima IS TRUE AND prm_distribucion_prog.jus_id IS NULL AND YEAR(disp_fecha_ini) = '" . $_GET['iyear'] . "' $cond";
endif;

$where .= " GROUP BY prm_distribucion_prog.disp_id";

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

require('../src/ssp.class.php');

echo json_encode(
	SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $joins, null, $where)
);