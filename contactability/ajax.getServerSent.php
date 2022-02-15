<?php

include("../class/classMyDBC.php");
include("../src/fn.php");
session_start();

$f_ini = (isset($_GET['idate'])) ? setDateBD($_GET['idate']) : setDateBD($_GET['date_i']);
$f_ter = (isset($_GET['idatet'])) ? setDateBD($_GET['idatet']) : setDateBD($_GET['date_t']);

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
$table = 'prm_sms';

// Table's primary key
$primaryKey = 'sms_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'sms_id', 'dt' => 0, 'field' => 'sms_id'),
	array('db' => 'sms_key', 'dt' => 1, 'field' => 'sms_key'),
	array('db' => "CONCAT(per_nombres, ' ', per_ap)", 'dt' => 2, 'field' => 'nombres', 'as' => 'nombres',
		'formatter' => function ($d, $row) {
			return utf8_encode($d);
		}
	),
	array('db' => 'sms_rut', 'dt' => 3, 'field' => 'sms_rut'),
	array('db' => 'sms_numero', 'dt' => 4, 'field' => 'sms_numero'),
	array('db' => 'sms_atencion', 'dt' => 5, 'field' => 'sms_atencion',
		'formatter' => function ($d, $row) {
			return getDateHourBD($d);
		}
	),
	array('db' => 'sms_registro', 'dt' => 6, 'field' => 'sms_registro',
		'formatter' => function ($d, $row) {
			return getDateHourBD($d);
		}
	)
);

$joinQuery = "FROM prm_sms AS s";
$joinQuery .= " LEFT JOIN prm_persona AS p ON s.per_id = p.per_id ";
$extraWhere = "sms_registro BETWEEN '" . $f_ini . " 00:00:00' AND '" . $f_ter . " 23:59:59'";

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
