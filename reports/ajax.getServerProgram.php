<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersona.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../class/classActividadProgramable.php");
include("../src/fn.php");
$_admin = false;
$db = new myDBC();
$di = new DistribucionProg();
$h = new DistHorasProg();
$ap = new ActividadProgramable();

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

$fecha = $_GET['iyear'] . '-' . $_GET['iperiodo'] . '-01';
$fecha_ter = $_GET['iyear'] . '-12-31';
$planta = $_GET['iplanta'];

$est = (!$_admin) ? $_SESSION['prm_estid'] : $_GET['iestab'];

// DB table to use
$table = 'prm_persona';

// Table's primary key
$primaryKey = 'prm_persona.per_id';
$index = 0;

$columns = array(
	array(
		'db' => 'per_nombres',
		'dt' => $index,
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'ser_nombre',
		'dt' => ++$index,
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'esp_nombre',
		'dt' => ++$index,
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'disp_descripcion',
		'dt' => ++$index,
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array(
		'db' => 'disp_fecha_ini',
		'dt' => ++$index,
		'formatter' => function ($d) use ($di) {
			return getDateBD($d);
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 1);
			return $num->dhp_cantidad;
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 2);
			return $num->dhp_cantidad;
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 3);
			return $num->dhp_cantidad;
		}
	),
	array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($di) {
			return $di->getTotalDisp($d);
		}
	)
);

//actividades policlinico
$ind = array(4, 5, 21);
foreach ($ind as $i):
	$actp = $ap->get($i);

	$ar = array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($h, $i) {
			$num = $h->getByDistTH($d, $i);
			return $num->dhp_cantidad;
		}
	);

	array_push($columns, $ar);

	$ar = array(
		'db' => 'prm_distribucion_prog.disp_id',
		'dt' => ++$index,
		'formatter' => function ($d) use ($h, $i) {
			$num = $h->getByDistTH($d, $i);
			return $num->dhp_rendimiento;
		}
	);

	array_push($columns, $ar);
endforeach;

//TOTAL POLI
$ar = array(
	'db' => 'prm_distribucion_prog.disp_id',
	'dt' => ++$index,
	'formatter' => function ($d) use ($di) {
		return $di->getTotalPoli($d);
	}
);
array_push($columns, $ar);

for ($i = 6; $i < 21; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);

		array_push($columns, $ar);

		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return is_null($num->dhp_rendimiento) ? '' : $num->dhp_rendimiento;
			}
		);

		array_push($columns, $ar);
	endif;
endfor;

//46
for ($i = 22; $i < 55; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);

		array_push($columns, $ar);

		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return is_null($num->dhp_rendimiento) ? '' : $num->dhp_rendimiento;
			}
		);

		array_push($columns, $ar);
	endif;
endfor;

for ($i = 128; $i < 139; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);

		array_push($columns, $ar);

		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return is_null($num->dhp_rendimiento) ? '' : $num->dhp_rendimiento;
			}
		);

		array_push($columns, $ar);
	endif;
endfor;

for ($i = 150; $i < 202; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);

		array_push($columns, $ar);

		$ar = array(
			'db' => 'prm_distribucion_prog.disp_id',
			'dt' => ++$index,
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return is_null($num->dhp_rendimiento) ? '' : $num->dhp_rendimiento;
			}
		);

		array_push($columns, $ar);
	endif;
endfor;

//TOTAL
$ar = array(
	'db' => 'prm_distribucion_prog.disp_id',
	'dt' => ++$index,
	'formatter' => function ($d) use ($di) {
		return $di->getTotal($d);
	}
);
array_push($columns, $ar);

$joins = ' JOIN prm_persona_establecimiento ON prm_persona.per_id = prm_persona_establecimiento.per_id';
$joins .= ' JOIN prm_distribucion_prog ON prm_persona_establecimiento.pes_id = prm_distribucion_prog.pes_id';
$joins .= ' JOIN prm_especialidad ON prm_distribucion_prog.esp_id = prm_especialidad.esp_id';
$joins .= ' JOIN prm_servicio ON prm_distribucion_prog.ser_id = prm_servicio.ser_id ';

$cond = '';
switch ($planta):
	case '0':
		$cond .= "prm_persona.prof_id = 14";
		break;
	case '1':
		$cond .= "prm_persona.prof_id <> 14 AND prm_persona.prof_id <> 4 AND prm_persona.prof_id <> 16";
		break;
	case '2':
		$cond .= "prm_persona.prof_id = 4 OR prm_persona.prof_id = 16";
		break;
	default:
		$cond .= '';
		break;
endswitch;

if (!empty($est)) $cond .= " AND prm_persona_establecimiento.est_id = $est";

if ($_GET['iperiodo'] != '00'):
	$where = " prm_distribucion_prog.disp_ultima IS TRUE AND prm_distribucion_prog.disp_fecha_ini = '" . $fecha . "' AND prm_distribucion_prog.disp_fecha_ter = '" . $fecha_ter . "' AND prm_distribucion_prog.jus_id IS NULL AND $cond";
else:
	$where = " prm_distribucion_prog.disp_ultima IS TRUE AND prm_distribucion_prog.jus_id IS NULL AND YEAR(disp_fecha_ini) = '" . $_GET['iyear'] . "' AND $cond";
endif;

//print_r($columns);

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