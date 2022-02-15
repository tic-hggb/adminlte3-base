<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersona.php");
include("../class/classDistribucionProg.php");
include("../class/classDistHorasProg.php");
include("../class/classActividadProgramable.php");
include("../src/fn.php");
$_admin = false;
$di = new DistribucionProg();
$h = new DistHorasProg();
$ap = new ActividadProgramable();

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']):
	$_admin = true;
endif;

$fecha = (!isset($_GET['iyear'])) ? $_POST['iyear'] . '-' . $_POST['iperiodo'] . '-01' : $_GET['iyear'] . '-' . $_GET['iperiodo'] . '-01';
$fecha_ter = (!isset($_GET['iyear'])) ? $_POST['iyear'] . '-12-31' : $_GET['iyear'] . '-12-31';

$planta = (!isset($_GET['iplanta'])) ? $_POST['iplanta'] : $_GET['iplanta'];
$apro = (!isset($_GET['iappr'])) ? $_POST['iappr'] : $_GET['iappr'];

if (!isset($_GET['iest']))
	if (!$_admin)
		$est = $_SESSION['prm_estid'];
	else
		if (!isset($_GET['iest']))
			$est = $_POST['iest'];
		else
			$est = $_GET['iest'];

// DB table to use
$table = 'prm_persona';

// Table's primary key
$primaryKey = 'per_id';
$index = 0;

$columns = array(
	array('db' => 'per_nombres', 'dt' => $index, 'field' => 'per_nombres'),
	array('db' => 'ser_nombre', 'dt' => ++$index, 'field' => 'ser_nombre'),
	array('db' => 'esp_nombre', 'dt' => ++$index, 'field' => 'esp_nombre'),
	array('db' => 'disp_descripcion', 'dt' => ++$index, 'field' => 'disp_descripcion'),
	array('db' => 'disp_fecha_ini', 'dt' => ++$index, 'field' => 'disp_fecha_ini',
		'formatter' => function ($d) use ($di) {
			return getDateBD($d);
		}
	),
	array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 1);
			return $num->dhp_cantidad;
		}
	),
	array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 2);
			return $num->dhp_cantidad;
		}
	),
	array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
		'formatter' => function ($d) use ($h) {
			$num = $h->getByDistTH($d, 3);
			return $num->dhp_cantidad;
		}
	),
	array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
		'formatter' => function ($d) use ($di) {
			return $di->getTotalDisp($d);
		}
	)
);

//actividades policlinico
$ind = array(4, 5, 21);
foreach ($ind as $i):
	$actp = $ap->get($i);
	$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
		'formatter' => function ($d) use ($h, $i) {
			$num = $h->getByDistTH($d, $i);
			return $num->dhp_cantidad;
		}
	);
	array_push($columns, $ar);
endforeach;

//TOTAL POLI
$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
	'formatter' => function ($d) use ($di) {
		return $di->getTotalPoli($d);
	}
);
array_push($columns, $ar);

for ($i = 6; $i < 21; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);
		array_push($columns, $ar);
	endif;
endfor;

//17
for ($i = 22; $i < 55; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);
		array_push($columns, $ar);
	endif;
endfor;

//49
for ($i = 128; $i < 139; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);
		array_push($columns, $ar);
	endif;
endfor;

//59
for ($i = 150; $i < 159; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);
		array_push($columns, $ar);
	endif;
endfor;

for ($i = 187; $i < 202; $i++):
	$actp = $ap->get($i);

	if ($actp->acp_vigente):
		$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
			'formatter' => function ($d) use ($h, $i) {
				$num = $h->getByDistTH($d, $i);
				return $num->dhp_cantidad;
			}
		);
		array_push($columns, $ar);
	endif;
endfor;

//65
//TOTAL
$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
	'formatter' => function ($d) use ($di) {
		return $di->getTotal($d);
	}
);
array_push($columns, $ar);

//BOTONES
$ar = array('db' => 'dp.disp_id', 'dt' => ++$index, 'field' => 'disp_id',
	'formatter' => function ($d) use ($di, $_admin) {
		$dist = $di->get($d);

		$string = '';

		if (!$dist->disp_aprobada and $_admin):
			$string .= ' <button id="aprid_' . $d . '" class="approve btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Aprobar"><i class="fa fa-check"></i></button>';
		endif;

		return $string;
	}
);
array_push($columns, $ar);

$joinQuery = 'FROM prm_persona p';
$joinQuery .= ' JOIN prm_persona_establecimiento pe ON p.per_id = pe.per_id ';
$joinQuery .= ' JOIN prm_distribucion_prog dp ON pe.pes_id = dp.pes_id ';
$joinQuery .= ' LEFT JOIN prm_especialidad e ON dp.esp_id = e.esp_id ';
$joinQuery .= ' LEFT JOIN prm_servicio s ON dp.ser_id = s.ser_id ';

switch ($planta):
	case '0':
		$str = "AND p.prof_id = 14";
		break;
	case '1':
		$str = "AND p.prof_id <> 14 AND p.prof_id <> 4 AND p.prof_id <> 16";
		break;
	case '2':
		$str = "AND p.prof_id = 4 OR p.prof_id = 16";
		break;
	default:
		$str = '';
		break;
endswitch;

if (!empty($est)) $str .= " AND pe.est_id = $est";

$extraWhere = "disp_fecha_ini = '" . $fecha . "' AND disp_fecha_ter = '" . $fecha_ter . "' $str";

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
	SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
