<?php

include("../class/classMyDBC.php");
include("../class/classPersonaEstablecimiento.php");
include("../src/fn.php");

if (extract($_POST)):
	$db = new myDBC();
	$pe = new PersonaEstablecimiento();

	try {
		$db->autoCommit(FALSE);

		$ins = $pe->setState($id, 1, $db);

		if (!$ins['estado']):
			throw new Exception('Error al activar el contrato. ' . $ins['msg']);
		endif;

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
