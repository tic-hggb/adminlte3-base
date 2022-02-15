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
$table = 'prm_sms_response';

// Table's primary key
$primaryKey = 'res_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'DISTINCT(res_id)', 'dt' => 0, 'field' => 'res_id'),
	array('db' => 'sms_rut', 'dt' => 1, 'field' => 'sms_rut'),
	array('db' => 'res_numero', 'dt' => 2, 'field' => 'res_numero'),
	array('db' => 'res_texto', 'dt' => 3, 'field' => 'res_texto'),
	array('db' => 'res_fecha', 'dt' => 4, 'field' => 'res_fecha',
		'formatter' => function ($d, $row) {
			return getDateHourBD($d);
		}
	)
);

$joinQuery = "FROM prm_sms_response AS sr";
$joinQuery .= " LEFT JOIN prm_sms AS s ON sr.res_numero = s.sms_numero";
$extraWhere = "res_registro BETWEEN '" . $f_ini . " 00:00:00' AND '" . $f_ter . " 23:59:59'";

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
