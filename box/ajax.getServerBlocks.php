<?php

include("../class/classMyDBC.php");
include("../src/fn.php");
session_start();

$f_ini = (isset($_GET['idate'])) ? setDateBD($_GET['idate']) : setDateBD($_GET['date_i']);
$f_ter = (isset($_GET['idatet'])) ? setDateBD($_GET['idatet']) : setDateBD($_GET['date_t']);

// DB table to use
$table = 'prm_agenda';

// Table's primary key
$primaryKey = 'age_id';
$index = 0;

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'lug_nombre', 'dt' => $index, 'field' => 'lug_nombre',
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array('db' => 'box_numero', 'dt' => ++$index, 'field' => 'box_numero',
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array('db' => 'per_rut', 'dt' => ++$index, 'field' => 'per_rut'),
	array('db' => 'per_nombres', 'dt' => ++$index, 'field' => 'per_nombres',
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array('db' => 'act_nombre', 'dt' => ++$index, 'field' => 'act_nombre',
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array('db' => 'ssub_nombre', 'dt' => ++$index, 'field' => 'ssub_nombre',
		'formatter' => function ($d) {
			return utf8_encode($d);
		}
	),
	array('db' => 'age_hora_ini', 'dt' => ++$index, 'field' => 'age_hora_ini'),
	array('db' => 'age_hora_ter', 'dt' => ++$index, 'field' => 'age_hora_ter')
);

$mes_ini = explode('-', $f_ini)[1];
$year_ini = explode('-', $f_ini)[0];
$mes_fin = explode('-', $f_ter)[1];
$year_fin = explode('-', $f_ter)[0];

if ((int)$mes_ini >= 1 and (int)$mes_ini <= 3)
	$start_date = $year_ini . '-01-00';
elseif ((int)$mes_ini >= 4 and (int)$mes_ini <= 6)
	$start_date = $year_ini . '-04-00';
elseif ((int)$mes_ini >= 7 and (int)$mes_ini <= 9)
	$start_date = $year_ini . '-07-00';
else
	$start_date = $year_ini . '-10-00';

if ((int)$mes_fin >= 1 and (int)$mes_fin <= 3)
	$end_date = $year_fin . '-03-00';
elseif ((int)$mes_fin >= 4 and (int)$mes_fin <= 6)
	$end_date = $year_fin . '-06-00';
elseif ((int)$mes_fin >= 7 and (int)$mes_fin <= 9)
	$end_date = $year_fin . '-09-00';
else
	$end_date = $year_fin . '-12-00';

$str_estab = (isset($_GET['iestab'])) ? 'AND el.est_id = ' . $_GET['iestab'] : 'AND el.est_id = ' . $_SESSION['prm_estid'];
$str_lugar = (!empty($_GET['ipiso'])) ? 'AND el.lug_id = ' . $_GET['ipiso'] : '';
$str_box = (!empty($_GET['ibox'])) ? 'AND b.box_id = ' . $_GET['ibox'] : '';

$joinQuery = "FROM prm_agenda AS a";
$joinQuery .= " JOIN prm_persona AS p ON a.per_id = p.per_id ";
$joinQuery .= " JOIN prm_actividad AS ac ON a.act_id = ac.act_id ";
$joinQuery .= " JOIN prm_sin_subespecialidad AS ss ON a.ssub_id = ss.ssub_id ";
$joinQuery .= " JOIN prm_box AS b ON a.box_id = b.box_id ";
$joinQuery .= " JOIN prm_estab_lugar AS el ON b.lug_id = el.lug_id ";
$extraWhere = "age_ultima IS TRUE AND age_periodo BETWEEN '" . $start_date. "' AND '" . $end_date . "' $str_estab $str_lugar $str_box";

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
