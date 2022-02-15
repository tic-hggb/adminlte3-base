<?php

include '../class/classMyDBC.php';
include '../class/classUser.php';
include '../src/fn.php';

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
$table = 'sgdoc_usuario';

// Table's primary key
$primaryKey = 'us_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'u.us_nombres', 'dt' => 0, 'field' => 'us_nombres'),
	array('db' => 'u.us_ap', 'dt' => 1, 'field' => 'us_ap'),
	array('db' => 'u.us_am', 'dt' => 2, 'field' => 'us_am'),
	array('db' => 'uu.us_telefono', 'dt' => 3, 'field' => 'us_telefono'),
	array('db' => 'u.us_email', 'dt' => 4, 'field' => 'us_email'),
	array('db' => 'un.un_descripcion', 'dt' => 5, 'field' => 'un_descripcion')
);

$joinQuery = "FROM sgdoc_usuario AS u";
$joinQuery .= " JOIN sgdoc_usuario_unidad AS uu ON u.us_id = uu.us_id";
$joinQuery .= " JOIN sgdoc_unidad AS un ON uu.un_id = un.un_id";

$extraWhere = "uu.us_telefono <> 0";
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
