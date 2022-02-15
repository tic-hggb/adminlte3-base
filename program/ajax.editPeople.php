<?php

session_start();
include("../class/classMyDBC.php");
include("../class/classPersona.php");
include("../class/classPersonaEstablecimiento.php");

if (extract($_POST)):
	$db = new myDBC();
	$per = new Persona();
	$pes = new PersonaEstablecimiento();

	try {
		$db->autoCommit(FALSE);
		$pe = $pes->get($iid);

		$ins = $per->mod($pe->per_id, strtoupper($iname), $iprofesion, strtoupper($iespec), $db);

		if (!$ins['estado']):
			throw new Exception('Error al editar los datos de la persona. ' . $ins['msg']);
		endif;

		$ins_es = $pes->mod($iid, $itcontrato, $icorr, $ihoras, $db);

		if (!$ins_es['estado']):
			throw new Exception('Error al editar los datos del contrato. ' . $ins_es['msg']);
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