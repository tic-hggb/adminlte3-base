<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classDistribucionProg.php");
include("../src/fn.php");

$enabled = false;
if (isset($_SESSION['prm_useradmin']) or isset($_SESSION['prm_userprog'])):
	$enabled = true;
endif;

if (extract($_POST)):
	$db = new myDBC();
	$dp = new DistribucionProg();

	try {
		if (!$enabled)
			throw new Exception('Error al eliminar la programación. Usted no cuenta con los permisos requeridos para ejecutar la acción.');

		$db->autoCommit(FALSE);
		$dist = $dp->get($id);
		$prev = $dp->getPreviousLast($dist->disp_pesid, $dist->espid, $dist->serid);
		$setLast = $dp->setPreviousLast($prev->disp_id, $db);

		if (!$setLast['estado'])
			throw new Exception('Error al actualizar la programación anterior. ' . $setLast['msg']);

		$ins = $dp->del($id, $db);

		if (!$ins['estado'])
			throw new Exception('Error al eliminar la programación. ' . $ins['msg']);

		$db->Commit();
		$db->autoCommit(TRUE);
		$response = array('type' => true, 'msg' => 'OK');
		echo json_encode($response);
	} catch (Exception $e) {
		$db->Rollback();
		$db->autoCommit(TRUE);
		$response = array('type' => false, 'msg' => $e->getMessage());
		echo json_encode($response);
	}
endif;
