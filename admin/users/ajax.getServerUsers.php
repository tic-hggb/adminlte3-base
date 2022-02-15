<?php

include("../../class/classMyDBC.php");
include("../../class/classUser.php");
include("../../class/classUnidad.php");
include("../../src/fn.php");
session_start();

$us = new User();
$un = new Unidad();
$_admin = $_secretary = false;

if (isset($_SESSION['prm_useradmin']) && $_SESSION['prm_useradmin']): $_admin = true; endif;
if (isset($_SESSION['prm_rol']['per']) and $_SESSION['prm_rol']['per'] == '3'): $_secretary = true; endif;

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
	array('db' => 'us_nombres', 'dt' => 0, 'field' => 'us_nombres'),
	array('db' => 'us_ap', 'dt' => 1, 'field' => 'us_ap'),
	array('db' => 'us_am', 'dt' => 2, 'field' => 'us_am'),
	array('db' => 'us_username', 'dt' => 3, 'field' => 'us_username'),
	array('db' => 'us_fecha', 'dt' => 4, 'field' => 'us_fecha',
		'formatter' => function ($d, $row) {
			return getDateToForm($d);
		}),
	array('db' => 'u.us_id', 'dt' => 5, 'field' => 'us_id',
		'formatter' => function ($d, $row) use ($_admin) {
			$string = '<button id="id_' . $d . '" data-toggle="modal" data-target="#userDetail" class="userModal btn btn-xs btn-info" data-tooltip="tooltip" data-placement="top" title="Ver detalles"><i class="fa fa-search"></i></button>';
			$string .= ' <a class="userEdit btn btn-xs btn-info" href="index.php?section=users&sbs=edituser&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';

			if ($_admin):
				$string .= ' <button id="del_' . $d . '" class="userDelete btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-remove"></i></button>';
			endif;

			return $string;
		}
	)
);

$joinQuery = "FROM sgdoc_usuario u";
$extraWhere = "";

if ($_secretary):
	$joinQuery .= " JOIN sgdoc_usuario_unidad uu ON u.us_id = uu.us_id";

	$uns = $un->getUnidadesByUser($_SESSION['prm_userid']);
	$extraWhere .= "((";
	$or = false;

	foreach ($uns as $i => $u):
		if ($or): $extraWhere .= ") OR ("; endif;
		$extraWhere .= "un_id = " . $u->un_id;
		$or = true;
	endforeach;

	$extraWhere .= '))';
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

require('../../src/ssp2.class.php');

echo json_encode(
	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
